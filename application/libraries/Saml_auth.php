<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Saml_auth {

	/*
		Library for login with identity providers over SAML and create a ion_auth compatible session. 

		author: Sean Wittmeyer
	*/

	public function __construct() {

		// get Codeigniter instance
	    $this->CI =& get_instance();

		$this->CI->load->file('application/libraries/Xmlseclibs.php');
		$this->CI->load->file('application/libraries/saml_auth/toolkit_loader.php');

	    // Load config
	    $this->CI->load->config('saml_auth', TRUE);
		$this->saml_settings = $this->CI->config->item('settingsInfo', 'saml_auth');
		
		
		
	}

    public function login($return = null) {


		session_start();
		$auth = new OneLogin_Saml2_Auth($this->saml_settings);


	    //$auth->login();
	
	    # If AuthNRequest ID need to be saved in order to later validate it, do instead
	    # $ssoBuiltUrl = $auth->login(null, array(), false, false, true);
	    # $_SESSION['AuthNRequestID'] = $auth->getLastRequestID();
	    # header('Pragma: no-cache');
	    # header('Cache-Control: no-cache, must-revalidate');
	    # header('Location: ' . $ssoBuiltUrl);
	    # exit();

		$returnTo = ($return === null) ? '' : "?return=$return";

	    $auth->login(site_url("auth/saml/finishlogin$returnTo"));

    }

    public function finishlogin($return = null) {


		if (empty($_SESSION)) session_start();

		if (isset($_SESSION['samlUserdata']) && !empty($_SESSION['samlUserdata'])) {
		    $attributes = $_SESSION['samlUserdata'];
		    		
			$user = array(
				'first_name' => $attributes['User.FirstName'][0], 
				'last_name' => $attributes['User.LastName'][0], 
				'email' => $attributes['User.email'][0], 
			);
		    
			// check if this user is already registered
			if(!$this->CI->ion_auth_model->identity_check($user['email'])){
				$register = $this->CI->ion_auth->register('saml_'.$user['email'], 'tKiQeUkEcekEbe0OREjvKOlX772ugraAH%0', $user['email'], $user);
				$login = $this->CI->ion_auth->login($user['email'], 'tKiQeUkEcekEbe0OREjvKOlX772ugraAH%0', 1);
			} else {
				$login = $this->CI->ion_auth->login($user['email'], 'tKiQeUkEcekEbe0OREjvKOlX772ugraAH%0', 1);
			}
			
			$returnTo = ($return === null) ? 'pylos' : $return;
			redirect($returnTo, 'refresh');
			//return true;
			//print_r('logged in successfully!'); die;
		    
		} else {
			// something didnt work, tell user fail.
			print_r('shucks, login failed. i sent an email to sean to see what the deal is. the saml response from onelogin was invalid which means someone is screwing with my website.'); die;
			return false;
		} 

    }
    
    // SAML login
    public function simplelogin($return = null) {
		if (empty($_SESSION)) session_start();
		$auth = new OneLogin_Saml2_Auth($this->saml_settings);


	    //$auth->login();
	
	    # If AuthNRequest ID need to be saved in order to later validate it, do instead
	    # $ssoBuiltUrl = $auth->login(null, array(), false, false, true);
	    # $_SESSION['AuthNRequestID'] = $auth->getLastRequestID();
	    # header('Pragma: no-cache');
	    # header('Cache-Control: no-cache, must-revalidate');
	    # header('Location: ' . $ssoBuiltUrl);
	    # exit();

		
	    $returnTo = ($return === null) ? site_url('auth/saml/attr') : site_url($return);
	    $auth->login($returnTo);


    }
    
    //SAML login and response endpoint for the identity provider
    public function acs() {

		if (empty($_SESSION)) session_start();
		$auth = new OneLogin_Saml2_Auth($this->saml_settings);
		

	    if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
	        $requestID = $_SESSION['AuthNRequestID'];
	    } else {
	        $requestID = null;
	    }
	
	    $auth->processResponse($requestID);
	
	    $errors = $auth->getErrors();
	
	    if (!empty($errors)) {
	        echo '<p>',implode(', ', $errors),'</p>';
	    }
	
	    if (!$auth->isAuthenticated()) {
	        echo "<p>Not authenticated</p>";
	        exit();
	    }
	
	    $_SESSION['samlUserdata'] = $auth->getAttributes();
	    $_SESSION['samlNameId'] = $auth->getNameId();
	    $_SESSION['samlNameIdFormat'] = $auth->getNameIdFormat();
	    $_SESSION['samlSessionIndex'] = $auth->getSessionIndex();
	    unset($_SESSION['AuthNRequestID']);
	    if (isset($_POST['RelayState']) && OneLogin_Saml2_Utils::getSelfURL() != $_POST['RelayState']) {
	        $auth->redirectTo($_POST['RelayState']);
	    }
	    
		redirect('auth/saml/finishlogin', 'refresh');
	}

	// SAML identity provider (IdP) initiated signout
    public function slo() {

		if (empty($_SESSION)) session_start();
		$auth = new OneLogin_Saml2_Auth($this->saml_settings);

	    $returnTo = null;
	    $parameters = array();
	    $nameId = null;
	    $sessionIndex = null;
	    $nameIdFormat = null;
	
	    if (isset($_SESSION['samlNameId'])) {
	        $nameId = $_SESSION['samlNameId'];
	    }
	    if (isset($_SESSION['samlSessionIndex'])) {
	        $sessionIndex = $_SESSION['samlSessionIndex'];
	    }
	    if (isset($_SESSION['samlNameIdFormat'])) {
	        $nameIdFormat = $_SESSION['samlNameIdFormat'];
	    }

		session_destroy();


	    $auth->logout($returnTo, $parameters, $nameId, $sessionIndex, false, $nameIdFormat);
	
	    # If LogoutRequest ID need to be saved in order to later validate it, do instead
	    # $sloBuiltUrl = $auth->logout(null, $paramters, $nameId, $sessionIndex, true);
	    # $_SESSION['LogoutRequestID'] = $auth->getLastRequestID();
	    # header('Pragma: no-cache');
	    # header('Cache-Control: no-cache, must-revalidate');
	    # header('Location: ' . $sloBuiltUrl);
	    # exit();
	
	}

	// SAML service provider (that's us) (SP) initiated signout
    public function sls() {

		if (empty($_SESSION)) session_start();
		$auth = new OneLogin_Saml2_Auth($this->saml_settings);

	    if (isset($_SESSION) && isset($_SESSION['LogoutRequestID'])) {
	        $requestID = $_SESSION['LogoutRequestID'];
	    } else {
	        $requestID = null;
	    }
		session_destroy();
		
	    $auth->processSLO(false, $requestID);
	    $errors = $auth->getErrors();
	    if (empty($errors)) {
	        //echo '<p>Signed out.</p>';
	        redirect('pylos#signedout', 'refresh');
	    } else {
	        echo '<p>', implode(', ', $errors), '</p>';
	    }
	
	}


    public function attr() {

		if (empty($_SESSION)) session_start();
		if (isset($_SESSION['samlUserdata'])) {
		    if (!empty($_SESSION['samlUserdata'])) {
		        $attributes = $_SESSION['samlUserdata'];
		        echo 'You have the following attributes:<br>';
		        echo '<table><thead><th>Name</th><th>Values</th></thead><tbody>';
		        foreach ($attributes as $attributeName => $attributeValues) {
		            echo '<tr><td>' . htmlentities($attributeName) . '</td><td><ul>';
		            foreach ($attributeValues as $attributeValue) {
		                echo '<li>' . htmlentities($attributeValue) . '</li>';
		            }
		            echo '</ul></td></tr>';
		        }
		        echo '</tbody></table>';
		    } else {
		        echo "<p>You don't have any attribute</p>";
		    }
		
		    echo '<p><a href="/auth/saml/logout" >Logout</a></p>';
		} else {
		    echo '<p><a href="/auth/saml/login" >Login and access later to this page</a></p>';
		}

    }
    public function metadata() {
		if (empty($_SESSION)) session_start();
	    try {
		    #$auth = new OneLogin_Saml2_Auth($settingsInfo);
		    #$settings = $auth->getSettings();
		    // Now we only validate SP settings
		    $settings = new OneLogin_Saml2_Settings($this->saml_settings, true);
		    $metadata = $settings->getSPMetadata();
		    $errors = $settings->validateMetadata($metadata);
		    if (empty($errors)) {
		        header('Content-Type: text/xml');
		        echo $metadata;
		    } else {
		        throw new OneLogin_Saml2_Error(
		            'Invalid SP metadata: '.implode(', ', $errors),
		            OneLogin_Saml2_Error::METADATA_SP_INVALID
		        );
		    }
		} catch (Exception $e) {
		    echo $e->getMessage();
		}

    }
}

/* End of file Saml_auth.php */