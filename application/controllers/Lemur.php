<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Lemur API Controller
 *
 * This controller returns methods for supported URIs relating to the postman
 * sample set from the forge docs. It includes all of the methods as a service 
 * as a way to automate the process of creating forge apps without any coding 
 * beyond the development of the supported engine addins.
 *
 * Version 1.0.0 (20190523 2307)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Lemur extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/lemur/');
        //->library('foo_bar');
		$this->config->load('lemur_config');
		$this->load->model('lemur_model');
		$this->load->model('forge');
	}

	public function index()
	{
		$data['pagetitle'] = 'Pylos'; // Capitalize the first letter
		$data['contenttitle'] = 'Revit Resources';
		$data['section'] = 'lemur';
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-basic', $data);
		$this->load->view('index', $data);
		$this->load->view('app/pylos/templates/footer', $data);
		// this is all we will do here
	}

	public function method($method)
	{
		$this->load->view("$method",$data);
	}

	/* 
	 * ninja is a direct access method for public functions 
	 *
	 * up to 3 args can be passed via the url, "/" delimited.
	 * unless methods here have output, many functions will not return 
	 * any data. it is used for debugging and limited use.
	 *
	 */
	public function ninja($method,$arg1=null,$arg2=null,$arg3=null)
	{
		$this->forge->$method($arg1=null,$arg2=null,$arg3=null);
	}

	/* 
	 * nickname checks for and resets the nickname for the app 
	 *
	 * you can clear an app in forge with this, it will take time to 
	 * remove the data on their side when used.
	 *
	 */
	public function create_activity()
	{
		$data['pagetitle'] = 'Pylos'; // Capitalize the first letter
		$data['contenttitle'] = 'Upload a App Bundle for Forge';
		$data['section'] = 'lemur';
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-basic', $data);

		// Setup Process
		$config = $this->config->item('lemur');
		
		// Authenticate and Get/Set Nickname
		$auth = $this->forge->auth_2_leg();
		//$nickname = $this->forge->get_nickname($auth['access_token']);
		//if ($config['dasNickName'] !== str_ireplace('"', '', $nickname)) $nickname = $this->forge->create_nickname($auth['access_token'],$config['dasNickName']);
		
		// Set Response
		//$data['success'] = "Nickname was reset to the default, ".$config['dasNickName'];

		$this->load->view('upload', $data);
		$this->load->view('app/pylos/templates/footer', $data);
		
	}

	/* 
	 * nickname checks for and resets the nickname for the app 
	 *
	 * you can clear an app in forge with this, it will take time to 
	 * remove the data on their side when used.
	 *
	 */
	public function nickname()
	{
		$data['pagetitle'] = 'Pylos'; // Capitalize the first letter
		$data['contenttitle'] = 'Revit Resources';
		$data['section'] = 'lemur';
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-basic', $data);

		// Setup Process
		$config = $this->config->item('lemur');
		
		// Authenticate and Get/Set Nickname
		$auth = $this->forge->auth_2_leg();
		$nickname = $this->forge->get_nickname($auth['access_token']);
		if ($config['dasNickName'] !== str_ireplace('"', '', $nickname)) $nickname = $this->forge->create_nickname($auth['access_token'],$config['dasNickName']);
		
		// Set Response
		$data['success'] = "Nickname was reset to the default, ".$config['dasNickName'];

		$this->load->view('index', $data);
		$this->load->view('app/pylos/templates/footer', $data);
		
	}

	/* 
	 * create app walks you through making an app bundle and activity 
	 *
	 * this will run through the whole process of making an activity 
	 * and running it on forge, up to actually running a workitem.
	 *
	 */
	public function create_app($preload=false)
	{
		$debug = true;
		$debug = false;

		$this->forge->displayoutput($debug, $preload, "here we go");

		$preload = array(
			"title" => "SketchIt",
			"apptitle" => "SketchItApp",
			"activitytitle" => "SketchItActivity",
			"nickname" => "lemur",
			"engine" => "Autodesk.Revit+2018",
			"description" => "SketchIt app based on Revit 2018",
			"alias" => "test",
			"appbundlealias" => "test",
			"path" => "/store/forge/SketchItApp.zip",
			
		);
		
		// Setup Process
		$config = $this->config->item('lemur');
		if ($this->ion_auth->logged_in()) {
			$user = $this->ion_auth->user()->row();
		} else {
			$return = array(
				'code' => '403',
				'result' => 'unauthorized',
				'message' => "Woah there! You gotta sign in to modify app bundles and activities in Pylos.",
				'error' => true
			);
		}
		if ($preload === false) {
			$env = $this->input->post('payload');
		} elseif (is_array($preload)) {
			$env = $preload;
		} else {
			$return = array(
				'code' => '400',
				'result' => 'failure',
				'message' => "Your input was empty or invalid...",
				'error' => true
			);
		}
		if (isset($return)) {	
			$this->output->set_status_header($return['code']);
			print json_encode($return); die;
		}
		
		// Authenticate and Get/Set Nickname
		$auth = $this->forge->auth_2_leg();
		$env["dasApiToken"] = $auth['access_token'];
		$this->forge->displayoutput($debug, $auth, "authenticated");

		$env["nickname"] = str_ireplace('"', '', $this->forge->get_nickname($auth['access_token']));
		if ($config['dasNickName'] !== $env['nickname']) $this->forge->patch_nickname($auth['access_token'],$config['dasNickName']);
		$env["nickname"] = $config['dasNickName'];
		$env['app'] = $env['nickname'].'.'.$env['apptitle'].'+'.$env['alias'];
		$this->forge->displayoutput($debug, $env, "nicknamed");

		// Create AppBundle Link
		$appbundle = $this->forge->create_appbundle($auth["access_token"],$env["nickname"],$env["apptitle"],$env["engine"],$env["description"]);
		$this->forge->displayoutput($debug, $appbundle, "appbundled");

		$env["appVersion"] = $appbundle['version'];
		$env["appUrl"] = $appbundle["uploadParameters"]["endpointURL"];
		$env["appFormDataKey"] = $appbundle["uploadParameters"]["formData"]["key"];
		$env["appFormDataPolicy"] = $appbundle["uploadParameters"]["formData"]["policy"];
		$env["appFormDataSignature"] = $appbundle["uploadParameters"]["formData"]["x-amz-signature"];
		$env["appFormDataCredential"] = $appbundle["uploadParameters"]["formData"]["x-amz-credential"];
		$env["appFormDataDate"] = $appbundle["uploadParameters"]["formData"]["x-amz-date"];
		$env["appFormDataToken"] = $appbundle["uploadParameters"]["formData"]["x-amz-security-token"];
		$env["appFormRedirect"] = $appbundle["uploadParameters"]["formData"]["success_action_redirect"];

		$this->forge->displayoutput($debug, $env, "env updated");

		// Upload AppBundle and make alias
		$appbundle = $this->forge->upload_appbundle($env["appFormDataKey"],$env["appFormDataPolicy"],$env["appFormDataSignature"],$env["appFormDataCredential"],$env["appFormDataDate"],$env["appFormDataToken"],$env["appFormRedirect"],$env["path"]);

		$alias = $this->forge->create_app_alias($auth['access_token'],$env['apptitle'],$env["appVersion"],$env["appbundlealias"]);
		
		$this->forge->displayoutput($debug, $appbundle, "appbundle uploaded");
		$this->forge->displayoutput($debug, $alias, "appbundle aliased");
		$this->forge->displayoutput($debug, $env, "env updated");

		// Create an Activity and Alias
		$env['activitydescription'] = 'Creates walls and floors from an input JSON file.';
		$activity = $this->forge->create_activity($env['activitytitle'], $env['apptitle'], $env['app'], $env['engine'], $env['activitydescription'], $env['title']);

		$this->forge->displayoutput($debug, $activity, "activited");

		$env["activityversion"] = $activity["version"];

		$activityalias = $this->forge->create_activity_alias($auth['access_token'],$env['activitytitle'],$env["activityversion"],$env["alias"]);

		$this->forge->displayoutput($debug, $env, "env updated");
		$this->forge->displayoutput($debug, $activityalias, "activity aliased");
		$this->forge->displayoutput($debug, $env, "env updated");
		
		$result = true;

		// Set Response
		if ($result === true) {
			$return = array(
				'code' => '200',
				'result' => 'success',
				'message' => "The {$env['app']} app for {$env['engine']} was created and aliased.",
				'error' => false
			);
		} else {
			$return = array(
				'code' => '400',
				'result' => 'failure',
				'message' => "The {$env['app']} app for {$env['engine']} was not created.",
				'error' => true
			);
		}
		
		$this->output->set_status_header($return['code']);
		print json_encode($return);

		
	}

	/* 
	 * run app accepts a file, app, and engine and creates a workitem in forge 
	 *
	 * this will check the app and engine input and run a workitem.
	 *
	 */
	public function run_app($preload=false)
	{
		$debug = true;
		//$debug = false;

		$this->forge->displayoutput($debug, $preload, "here we go");

		$preload = array(
			"bucketkey" => "lemur",
			"apptitle" => "SketchItActivity",
			"version" => 1,
			"alias" => "test",
			"nickname" => "lemur",
			"engine" => "Autodesk.Revit+2020",
			"path" => "/store/forge/Project2020test.rvt",
			"outputtitle" => "Output.json",
		);
		
		// Setup Process
		$config = $this->config->item('lemur');
		if ($this->ion_auth->logged_in()) {
			$user = $this->ion_auth->user()->row();
		} else {
			$return = array(
				'code' => '403',
				'result' => 'unauthorized',
				'message' => "Woah there! You gotta sign in to modify app bundles and activities in Pylos.",
				'error' => true
			);
		}
		if ($preload === false) {
			$env = $this->input->post('payload');
		} elseif (is_array($preload)) {
			$env = $preload;
		} else {
			$return = array(
				'code' => '400',
				'result' => 'failure',
				'message' => "Your input was empty or invalid...",
				'error' => true
			);
		}
		if (isset($return)) {	
			$this->output->set_status_header($return['code']);
			print json_encode($return); die;
		}
		
		// Authenticate and Get/Set Nickname
		$auth = $this->forge->auth_2_leg();
		$env["dasApiToken"] = $auth['access_token'];
		$this->forge->displayoutput($debug, $auth, "data authenticated");
		$dataauth = $this->forge->data_auth_2_leg();
		$env["dataApiToken"] = $dataauth['data_access_token'];
		$this->forge->displayoutput($debug, $dataauth, "data authenticated");

		$env['app'] = $env['nickname'].'.'.$env['apptitle'].'+'.$env['alias'];
		$this->forge->displayoutput($debug, $env, "apped");

		// Create a signed URL
		$signurl = $this->forge->create_signed_url($env['bucketkey'], $env['outputtitle']);

		$this->forge->displayoutput($debug, $signurl, "signed");

		$env["outputpath"] = $signurl["signedUrl"];

		// Create a workitem
		$env['inputpath'] = site_url($env['path']);
		$this->forge->displayoutput($debug, $env, "paths set");
		$activity = $this->forge->create_workitem($env['app'], $env['inputpath'], $env['outputpath']);

		$this->forge->displayoutput($debug, $activity, "running");
		
		$result = true;

		// Set Response
		if ($result === true) {
			$return = array(
				'code' => '200',
				'result' => 'success',
				'message' => "The {$env['app']} app is running.",
				'error' => false
			);
		} else {
			$return = array(
				'code' => '400',
				'result' => 'failure',
				'message' => "The {$env['app']} app did not run for your file.",
				'error' => true
			);
		}
		
		$this->output->set_status_header($return['code']);
		print json_encode($return);

	}

	public function update($type=false,$id=false,$file=false)
	{
		// this is sample code for now, don't mind it
		if ($type===false || $id===false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>"Blast, don't forget to specify the type and ID. Do not pass go."));
			die;
		}
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't update ".$type."s unless you are logged in."));
			die;
		}
		$result = $this->shared->update($type,$id,$file);
		if ($result['error'] === true) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			//print_r($post = $this->input->post('payload'));
			die;
		}
		$this->output->set_status_header('200');
		print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
		
	}

	public function api($type="post",$collection=false,$method=false,$locator=false)
	{
		if ($locator) {
			$payload = $this->get_data2('pylos_store',false,$locator,array('expires','desc'));
			if ($payload && (time() < $payload[0]['expires'])) return unserialize($payload[0]['payload']);
		}
		$result = $this->lemur_model->run($type,$collection,$method,$payload);
		$this->load->view("/app/lemur/$collection/$method",$result);
		
	}

}
