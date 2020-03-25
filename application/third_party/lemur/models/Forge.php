<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Lemur Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 1.0.0 (20190523 2336)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Forge extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	/* 
	 *	curl for forge
	 *  
 	 *	provide curl options in $settings and http headers in $httpheader
 	 *
	 */

	public function do_curl($httpheader=false, $settings=false, $json=true, $printheaders=false, $host="developer.api.autodesk.com") {
		if (!$httpheader) $httpheader = array();
		if (!$settings) $settings = array();
		$curl = curl_init();

		/* user supplied - use this in other calls:

		$postfield = "client_id=$client_id&client_secret=$client_secret&grant_type=client_credentials&scope=code%3Aall";
		$settings = array(
			CURLOPT_URL => "https://developer.api.autodesk.com/authentication/v1/authenticate",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Content-Type: application/x-www-form-urlencoded", // Content-Type: application/json
			"Host: developer.api.autodesk.com",
			"User-Agent: com.seanwittmeyer.builder/7.13.0",
			"Content-Length: " . strlen($postfield)
		);
		
		$response = $this->do_curl($httpheader, $settings);

		/**/
		
		$httpheader = array_merge(array(
			"Accept: */*",
			"Cache-Control: no-cache",
			"Connection: keep-alive",
			"accept-encoding: gzip, deflate",
			"cache-control: no-cache",
			"Host: $host",
			"User-Agent: com.seanwittmeyer.builder/19.5.0",
		), $httpheader);

		$curlopt = $settings + array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_HTTPHEADER => $httpheader,
		);
		
		foreach ($curlopt as $k => $v) curl_setopt($curl, $k, $v);
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		if ($printheaders) {
			echo "\n ====================================== print headers \n";
			var_dump(curl_getinfo($curl));
			echo "\n ====================================== end headers \n";
		}
			
		curl_close($curl);
		
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return ($json) ? json_decode($response,true): $response;
		}
    }


	/* 
	 * Forge Authenticate - 2 legged id/secret method
	 *
	 * On success, it returns an object ['access_token','token_type',expires_in] 
	 * and sets $_SESSION vars for 'access_token' and 'freshtime'. It will reauthenticate
	 * if the token times out.
	 */

	public function auth_2_leg($client_id=false,$client_secret=false) {
	    if (isset($_SESSION['access_token'])) {
		    if (time() < $_SESSION['freshtime']) return ['access_token'=>$_SESSION['access_token'],'token_type'=>'Bearer','expires_in'=>$_SESSION['freshtime']-time()];
	    }
	    $config = $this->config->item('lemur');
		if (!$client_id) $client_id = $config['client_id'];
		if (!$client_secret) $client_secret = $config['client_secret'];
		
		$postfield = "client_id=$client_id&client_secret=$client_secret&grant_type=client_credentials&scope=code%3Aall";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/authentication/v1/authenticate",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Content-Type: application/x-www-form-urlencoded", // Content-Type: application/json
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

	    $this->session->set_userdata('access_token', $response['access_token']);
	    $this->session->set_userdata('freshtime', $response['expires_in'] + time());
		
		return $response;
    }

	/* 
	 * Forge Data API Authenticate - 2 legged id/secret method
	 *
	 * On success, it returns an object ['access_token','token_type',expires_in] 
	 * and sets $_SESSION vars for 'access_token' and 'freshtime'. It will reauthenticate
	 * if the token times out.
	 */

	public function data_auth_2_leg($client_id=false,$client_secret=false) {
	    if (isset($_SESSION['data_access_token'])) {
		    if (time() < $_SESSION['datafreshtime']) return ['data_access_token'=>$_SESSION['data_access_token'],'token_type'=>'Bearer','expires_in'=>$_SESSION['datafreshtime']-time()];
	    }
	    $config = $this->config->item('lemur');
		if (!$client_id) $client_id = $config['client_id'];
		if (!$client_secret) $client_secret = $config['client_secret'];
		
		$postfield = "client_id=$client_id&client_secret=$client_secret&grant_type=client_credentials&scope=bucket%3Aread%20bucket%3Aupdate%20bucket%3Acreate%20bucket%3Adelete%20data%3Awrite%20data%3Acreate%20data%3Aread";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/authentication/v1/authenticate",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Content-Type: application/x-www-form-urlencoded", // Content-Type: application/json
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

	    $this->session->set_userdata('data_access_token', $response['access_token']);
	    $this->session->set_userdata('datafreshtime', $response['expires_in'] + time());
		
		return $response;
    }

	/* 
	 * Create / Patch Nickname
	 *
	 * On success, it returns true
	 */

	public function patch_nickname($access_token,$nickname=false) {
	    $config = $this->config->item('lemur');
		if (!$nickname) $nickname = $config['dasNickName'];
		
		$postfield = "{\r\n\t\"nickname\": \"$nickname\"\r\n}";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/forgeapps/me",
			CURLOPT_CUSTOMREQUEST => "PATCH",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
			"Content-Type: application/json", // Content-Type: 
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, false);

		//print_r($response);
		return $response;
	}

	/* 
	 * Get Current Nickname
	 *
	 * On success, it returns the nickname wrapped in double quotes
	 */

	public function get_nickname($access_token) {
		$config = $this->config->item('lemur');
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/forgeapps/me",
			CURLOPT_CUSTOMREQUEST => "GET",
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
		);

		$response = $this->do_curl($httpheader, $settings, false);

		//print_r($response);
		return $response;
	}

	/* 
	 * Delete Nickname and App Bundles
	 *
	 * On success, it returns true
	 */

	public function delete_nickname($access_token) {
		$config = $this->config->item('lemur');
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/forgeapps/me",
			CURLOPT_CUSTOMREQUEST => "DELETE",
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
		);

		$response = $this->do_curl($httpheader, $settings, false);

		//print_r($response);
		return $response;
	}

	/* 
	 * Get details for a single engine or list of all engines
	 *
	 * On success, it returns object of paginationToken and 'data' list of id's 
	 * for all and an object with 'package' link, 'id', 'engine', 'description', 
	 * and the 'version'
	 */

	public function get_engines($engine='') {
		$config = $this->config->item('lemur');
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/engines/$engine",
			CURLOPT_CUSTOMREQUEST => "GET",
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Get details for a single appbundle or list of all app bundles
	 *
	 * On success, it returns object of paginationToken and 'data' list of id's 
	 * for all and an object with 'package' link, 'id', 'engine', 'description', 
	 * and the 'version'
	 */


	public function get_appbundles($appid='') {
		$config = $this->config->item('lemur');
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/appbundles/".$appid,
			CURLOPT_CUSTOMREQUEST => "GET",
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Create an App Bundle
	 *
	 * On success, it returns an object:
	 *    id, engine, description, version, and object uploadParameters:
	 *        endpointURL, object formData:
	 *            key, content-type, policy, success_action_status, success_action_redirect, x-amz-signature, x-amz-credential, x-amz-algorithm, x-amz-date, x-amz-serverside-encryption, x-amz-security-token
	 */

	public function create_appbundle($access_token=false,$nickname=false,$title,$engine,$description) {
	    $config = $this->config->item('lemur');
		if (!$nickname) $nickname = $config['dasNickName'];

		$postfield = "{\r\n\t\"id\": \"$title\",\r\n\t\"engine\": \"$engine\",\r\n\t\"description\": \"$description\"\r\n}";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/appbundles",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
			"Content-Type: application/json",
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Upload the App Bundle to Forge
	 *
	 * On success, it returns true
	 */

	public function upload_appbundle($key,$policy,$signature,$credential,$date,$securitytoken,$redirect,$path) {

		// USER OPTIONS
		// Replace these values with ones appropriate to you.
		//$accessKeyId = 'YOUR_ACCESS_KEY_ID';
		//$secretKey = 'YOUR_SECRET_KEY';
		//$bucket = 'YOUR_BUCKET_NAME';
		//$region = 'us-east-1'; // us-west-2, us-east-1, etc
		
		$acl = 'ACCESS_CONTROL_LIST'; // private, public-read, etc
		$filePath = ''.$_SERVER['DOCUMENT_ROOT'].$path;
		$info = pathinfo($filePath);
		$fileName = $info['basename'];
		//$fileType = mime_content_type($filePath);
		$fileType = "application/octet-stream";
		
		// VARIABLES
		// These are used throughout the request.
		//$longDate = gmdate('Ymd\THis\Z');
		//$shortDate = gmdate('Ymd');
		//$credential = $accessKeyId . '/' . $shortDate . '/' . $region . '/s3/aws4_request';
		
		// POST POLICY
		// Amazon requires a base64-encoded POST policy written in JSON.
		// This tells Amazon what is acceptable for this request. For
		// simplicity, we set the expiration date to always be 24H in 
		// the future. The two "starts-with" fields are used to restrict
		// the content of "key" and "Content-Type", which are specified
		// later in the POST fields. Again for simplicity, we use blank
		// values ('') to not put any restrictions on those two fields.
		
		/* forge makes this for us
		$policy = base64_encode(json_encode([
		    'expiration' => gmdate('Y-m-d\TH:i:s\Z', time() + 86400),
		    'conditions' => [
		        ['acl' => $acl],
		        ['bucket' => $bucket],
		        ['starts-with', '$Content-Type', ''],
		        ['starts-with', '$key', ''],
		        ['x-amz-algorithm' => 'AWS4-HMAC-SHA256'],
		        ['x-amz-credential' => $credential],
		        ['x-amz-date' => $longDate]
		    ]
		]));*/
		
		// SIGNATURE
		// A base64-encoded HMAC hashed signature with your secret key.
		// This is used so Amazon can verify your request, and will be
		// passed along in a POST field later.
		
		/* forge makes this for us
		$signingKey = hash_hmac('sha256', $shortDate, 'AWS4' . $secretKey, true);
		$signingKey = hash_hmac('sha256', $region, $signingKey, true);
		$signingKey = hash_hmac('sha256', 's3', $signingKey, true);
		$signingKey = hash_hmac('sha256', 'aws4_request', $signingKey, true);
		$signature = hash_hmac('sha256', $policy, $signingKey);
		*/
		
		// CURL
		// The cURL request. Passes in the full URL to your Amazon bucket.
		// Sets RETURNTRANSFER and HEADER to true to see the full response from
		// Amazon, including body and head. Sets POST fields for cURL.
		// Then executes the cURL request.
		$ch = curl_init('https://dasprod-store.s3.amazonaws.com');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, [
		    'Content-Type' =>  $fileType,
		    //'acl' => $acl,
		    'key' => $key,
		    'policy' =>  $policy,
			'success_action_status' => '200',
		    'x-amz-algorithm' => 'AWS4-HMAC-SHA256',
		    'x-amz-credential' => $credential,
		    'x-amz-date' => $date,
		    'x-amz-signature' => $signature,
			'x-amz-server-side-encryption' => 'AES256',
			'x-amz-security-token' => $securitytoken,
			'success_action_redirect' => $redirect,
		    'file' => new CurlFile(realpath($filePath), $fileType, $fileName)
		]);
		$response = curl_exec($ch);
		
		// RESPONSE
		// If Amazon returns a response code of 204, the request was
		// successful and the file should be sitting in your Amazon S3
		// bucket. If a code other than 204 is returned, there will be an
		// XML-formatted error code in the body. For simplicity, we use
		// substr to extract the error code and output it.
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 204) {
		    echo 'Success!';
		} else {
		    $error = substr($response, strpos($response, '<Code>') + 6);
		    echo substr($error, 0, strpos($error, '</Code>'));
		}


		curl_close ($ch);
		//print_r($response);
		return $response;
	}

	/* 
	 * Create an App Bundle Alias
	 *
	 * On success, it returns an object:
	 *    id, engine, description, version, and object uploadParameters:
	 *        endpointURL, object formData:
	 *            key, content-type, policy, success_action_status, success_action_redirect, x-amz-signature, x-amz-credential, x-amz-algorithm, x-amz-date, x-amz-serverside-encryption, x-amz-security-token
	 */

	public function create_app_alias($access_token,$title,$version,$alias) {
	    $config = $this->config->item('lemur');

		$postfield = "{\"version\": $version,\"id\": \"$alias\"}";
		//print_r($postfield); die;
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/appbundles/".$title."/aliases",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
			"Content-Type: application/json",
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Create an Activity
	 *
	 * On success, it returns an object:
	 *    id, engine, description, version, and object uploadParameters:
	 *        endpointURL, object formData:
	 *            key, content-type, policy, success_action_status, success_action_redirect, x-amz-signature, x-amz-credential, x-amz-algorithm, x-amz-date, x-amz-serverside-encryption, x-amz-security-token
	 */

	public function create_activity($activitytitle, $apptitle, $app, $engine, $description, $title) {
	    $config = $this->config->item('lemur');

		/* defaults */
		
		/**/

		$postfield = '{
			"id": "'.$activitytitle.'",
			"commandLine": [ "$(engine.path)\\\\revitcoreconsole.exe /i $(args[rvtFile].path) /al $(appbundles['.$apptitle.'].path)" ],
			"parameters": {
			  "rvtFile": {
				"zip": false,
				"ondemand": false,
				"verb": "get",
				"description": "Input Revit model",
				"required": true
			  },
			  "'.$title.'Params": {
				"zip": false,
				"ondemand": false,
				"verb": "get",
				"description": "'.$title.' parameters",
				"required": false,
				"localName": "Params.json"
			  },
			  "result": {
				"zip": false,
				"ondemand": false,
				"verb": "put",
				"description": "Results",
				"required": true,
				"localName": "result.txt"
			  }
			},
			"engine": "'.$engine.'",
			"appbundles": [ "'.$app.'" ],
			"description": "'.$description.'"
		}';
		$postfield = '{
			"id": "'.$activitytitle.'",
			"commandLine": [ "$(engine.path)\\\\revitcoreconsole.exe /al $(appbundles['.$apptitle.'].path)" ],
			"parameters": {
			  "'.$title.'Input": {
				"zip": false,
				"ondemand": false,
				"verb": "get",
				"description": "'.$title.' input parameters",
				"required": true,
				"localName": "'.$title.'Input.json"
			  },
			  "result": {
				"zip": false,
				"ondemand": false,
				"verb": "put",
				"description": "Results",
				"required": true,
				"localName": "sketchIt.rvt"
			  }
			},
			"engine": "'.$engine.'",
			"appbundles": [ "'.$app.'" ],
			"description": "'.$description.'"
		}';
		$postfield = '{
			"id": "'.$activitytitle.'",
			"commandLine": [ "$(engine.path)\\\\revitcoreconsole.exe /i $(args[inputFile].path) /al $(appbundles['.$apptitle.'].path)" ],
			"parameters": {
			  "inputFile": {
				"zip": false,
				"ondemand": false,
				"verb": "get",
				"description": "Input Revit model",
				"required": true
			  },
			  "outputFile": {
				"zip": false,
				"ondemand": false,
				"verb": "put",
				"description": "Results",
				"required": true,
				"localName": "Output.json"
			  }
			},
			"engine": "'.$engine.'",
			"appbundles": [ "'.$app.'" ],
			"description": "'.$description.'"
		}';		//$postfield = "{\r\n\t\"id\": \"$title\",\r\n\t\"engine\": \"$engine\",\r\n\t\"description\": \"$description\"\r\n}";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/activities",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
			"Content-Type: application/json",
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Create an Activity Alias
	 *
	 * On success, it returns an object:
	 *    id, engine, description, version, and object uploadParameters:
	 *        endpointURL, object formData:
	 *            key, content-type, policy, success_action_status, success_action_redirect, x-amz-signature, x-amz-credential, x-amz-algorithm, x-amz-date, x-amz-serverside-encryption, x-amz-security-token
	 */

	public function create_activity_alias($access_token,$title,$sketchItActivityVersion,$alias) {
	    $config = $this->config->item('lemur');

		$postfield = "{\"id\": \"$alias\",\"version\": $sketchItActivityVersion}";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/activities/$title/aliases",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
			"Content-Type: application/json",
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Create a Signed Url
	 *
	 * On success, it returns an object:
	 *    id, engine, description, version, and object uploadParameters:
	 *        endpointURL, object formData:
	 *            key, content-type, policy, success_action_status, success_action_redirect, x-amz-signature, x-amz-credential, x-amz-algorithm, x-amz-date, x-amz-serverside-encryption, x-amz-security-token
	 */

	public function create_signed_url($bucketkey,$outputfile) {
	    $config = $this->config->item('lemur');

		$postfield = "{}";
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/oss/v2/buckets/$bucketkey/objects/$outputfile/signed?access=write",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postfield,
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['data_access_token']}",
			"Content-Type: application/json",
			"Content-Length: " . strlen($postfield)
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Create a Workitem
	 *
	 * On success, it returns an object:
	 *    id, engine, description, version, and object uploadParameters:
	 *        endpointURL, object formData:
	 *            key, content-type, policy, success_action_status, success_action_redirect, x-amz-signature, x-amz-credential, x-amz-algorithm, x-amz-date, x-amz-serverside-encryption, x-amz-security-token
	 */

	public function create_workitem($app, $inputpath, $outputpath) {
	    $config = $this->config->item('lemur');

		$postfield = [
			"activityId" => $app,
			"arguments" => [
				"SketchItInput" => [
					"url" => "data:application/json,{ 'walls': [ {'start': { 'x': -100, 'y': 100, 'z': 0.0}, 'end': { 'x': 100, 'y': 100, 'z': 0.0}}, {'start': { 'x': -100, 'y': 100, 'z': 0.0}, 'end': { 'x': 100, 'y': 100, 'z': 0.0}}, {'start': { 'x': 100, 'y': 100, 'z': 0.0}, 'end': { 'x': 100, 'y': -100, 'z': 0.0}}, {'start': { 'x': 100, 'y': -100, 'z': 0.0}, 'end': { 'x': -100, 'y': -100, 'z': 0.0}}, {'start': { 'x': -100, 'y': -100, 'z': 0.0}, 'end': { 'x': -100, 'y': 100, 'z': 0.0}}, {'start': { 'x': -500, 'y': -300, 'z': 0.0}, 'end': { 'x': -300, 'y': -300, 'z': 0.0}}, {'start': { 'x': -300, 'y': -300, 'z': 0.0}, 'end': { 'x': -300, 'y': -500, 'z': 0.0}}, {'start': { 'x': -300, 'y': -500, 'z': 0.0}, 'end': { 'x': -500, 'y': -500, 'z': 0.0}}, {'start': { 'x': -500, 'y': -500, 'z': 0.0}, 'end': { 'x': -500, 'y': -300, 'z': 0.0}}],'floors' : [ [{'x': -100, 'y': 100, 'z':0.0}, {'x': 100, 'y': 100, 'z': 0.0}, {'x': 100, 'y': -100, 'z': 0.0}, {'x': -100, 'y': -100, 'z': 0.0}], [{'x': -500, 'y': -300, 'z':0.0}, {'x': -300, 'y': -300, 'z': 0.0}, {'x': -300, 'y': -500, 'z': 0.0}, {'x': -500, 'y': -500, 'z': 0.0}] ]}"
				],
				"result" => [
					"verb" => "put",
					"url" => $outputpath
				]
			]
		];

		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/workitems",
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($postfield),
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
			"Content-Type: application/json",
			"Content-Length: " . strlen(json_encode($postfield))
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Does GET in forge
	 *
	 * On success, it returns object of paginationToken and 'data' list of id's
	 */

	public function blablabla() {
		$config = $this->config->item('lemur');
		$settings = array(
			CURLOPT_URL => $config['baseUrl']."/da/us-east/v3/appbundles",
			CURLOPT_CUSTOMREQUEST => "GET",
		);
		$httpheader = array(
			"Authorization: Bearer {$_SESSION['access_token']}",
		);

		$response = $this->do_curl($httpheader, $settings, true);

		//print_r($response);
		return $response;
	}

	/* 
	 * Little helper function to format files for multipart http post
	 *
	 * Returns the postdata for a curl request
	 */
	public function build_data_files($boundary, $fields, $files){
		$data = '';
		$eol = "\r\n";
		
		$delimiter = '-------------' . $boundary;
		
		foreach ($fields as $name => $content) {
			$data .= "--" . $delimiter . $eol
			. 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
			. $content . $eol;
		}
		
		foreach ($files as $name => $content) {
			$data .= "--" . $delimiter . $eol
			. 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
			//. 'Content-Type: image/png'.$eol
			. 'Content-Transfer-Encoding: binary'.$eol
			;
			
			$data .= $eol;
			$data .= $content . $eol;
		}
		$data .= "--" . $delimiter . "--".$eol;
		
		return $data;
	}

	public function displayoutput($debug=false,$var,$message) {
		if ($debug) {
			echo "\n ====================================== $message \n";
			var_dump($var);
		}
	}


}