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

class Lemur_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->config->load('lemur_config');
	}

	/* 
	 *	Use curl to get content
	 */

    public function get_curl($url,$args='',$context=false) {
	    //global $context;
	    if ($context === false) curl_init();
	    $config = $this->config->item('pylos');
	    $url = $url.$config['AUTH_ARGS'].$args;
	    curl_setopt($context, CURLOPT_URL, $url);
		$headers[] = 'User-Agent: '.$config['ORIGIN_ACCOUNT'].'/'.$config['ORIGIN_REPO'];
		curl_setopt($context, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($context, CURLOPT_RETURNTRANSFER, true);
	    return curl_exec($context);
    }

	/* 
	 *	Revised curl function to get content if not recent in cache
	 *
	 *  $locator = false (default) or array(database column key => $var)
	 *    limit use of keys to existing columns in the store table
	 *    example for nexus api projects array('type'=>'project','arg1'=>'P23796.00')
	 */

    public function get_curl2($app=false,$url=false,$locator=false,$context=false,$decode=true) {

		// check if the resource is cached, and return if not expired
		if ($locator) {
			$cache = $this->get_data2('pylos_store',false,$locator,array('expires','desc'));
			if ($cache && (time() < $cache[0]['expires'])) return unserialize($cache[0]['payload']);
		}
		
		// get the resource
		$curl = curl_init();
		$config = $this->config->item('pylos');
		
		if (isset($config['curl'][$app])) {
			$source = $config['curl'][$app]['baseurl'].$url;
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $source,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => $config['curl'][$app]['httpheader']
			));
			$expires = $config['curl'][$app]['fresh']; // time from time() this will expire
		} else {
			$source = $url.$args;
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $source,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
			    "Accept: */*",
			    "Cache-Control: no-cache",
			    "Connection: keep-alive",
			    "User-Agent: PostmanRuntime/7.11.0",
			    "accept-encoding: gzip, deflate",
			    "cache-control: no-cache",
			    "referer: ".$url.$args
			  ),
			));
			$expires = 86400; // 1 day, time from time() this will expire
		}

		// return the resource or errors associated
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			// Cache the record and return it
			$insert = (is_array($locator)) ? $locator : array();
			
			$insert['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row()->id : 0;
			$insert['timestamp'] = time();
			$insert['expires'] = time()+$expires;
			$insert['source'] = $source;
			$insert['payload'] = ($decode) ? serialize(json_decode($response,true)) : serialize($response);
				
			$this->db->insert("build_pylos_store",$insert);
			// no error checking yet, maybe later

			if ($decode) {
				return json_decode($response,true);
			} else {
				echo $response;
			}
		}    
	}

	public function get_data2($type,$id=false,$where=false,$sorttitle=false,$select=false,$limit=false)
	{
		// Setup
		if ($where) $this->db->where($where);
		if ($limit) $this->db->limit($limit);
		if (is_array($sorttitle)) {
			$this->db->order_by($sorttitle[0],$sorttitle[1]);
		} elseif ($sorttitle === true) {
			$this->db->order_by("title", "asc");
		} else {
			$this->db->order_by("timestamp", "desc");
		}

		if ($select) $this->db->select($select);

		if ($id === false) {
		// Get all
			$query = $this->db->get("build_$type");
			if ($query) {
				$result = $query->result_array();
			} else {
				$return = false;
			}
		} else {
		// Get one
			$query = $this->db->get_where("build_$type", array('id'=>$id));
			$result = $query->row_array();
		}
		if (empty($result)) return false;
		return $result;
	}

	// Image Upload
	public function storeimage($return=false,$hostunique=false,$type='image',$hostid=null,$hosttype=null)
	{

		if (empty($_FILES['userfile'])) {
		    echo json_encode(array('error'=>'Please try again and make sure your file is not too large.')); 
		    // or you can throw an exception 
		    return; // terminate
		}
		
		//$images = $_FILES['image'];
		
		// fetch form data
		$userid = empty($_POST['userid']) ? '' : $_POST['userid'];
		$success = null;
		//$filenames = $images['name'];

		if ($type == 'file') {
			$config['allowed_types'] = 'zip';
			$config['upload_path'] = './store/pylosfiles';
			$config['return_path'] = '/store/pylosfiles/';
			$config['max_size']	= '550000';
			//$config['max_size']	= '200000';
			$config['encrypt_name']  = true;

			$this->load->library('upload', $config);
		
		} elseif ($type == 'guide') {
			$config['allowed_types'] = 'zip';
			$config['upload_path'] = './store/pylosfiles';
			$config['return_path'] = '/store/pylosfiles/';
			$config['max_size']	= '550000';
			//$config['max_size']	= '200000';
			$config['encrypt_name']  = true;
			

			$this->load->library('upload', $config);
		
		} elseif ($type == 'grasshopper') {
			$config['allowed_types'] = 'zip';
			$config['upload_path'] = './store/pylosimport';
			$config['return_path'] = '/store/pylosimport/';
			$config['max_size']	= '550000';
			//$config['max_size']	= '200000';
			$config['encrypt_name']  = true;
			

			$this->load->library('upload', $config);
		
		} elseif ($type == 'presentation') {
			$config['allowed_types'] = 'pdf';
			$config['upload_path'] = './store/pylosimg';
			$config['return_path'] = '/store/pylosimg/';
			$config['max_size']	= '150000';
			$config['encrypt_name']  = true;
			

			$this->load->library('upload', $config);
		
		} elseif ($type == 'thumbnail' || $type == 'image') {
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['upload_path'] = './store/pylosimg';
			$config['return_path'] = '/store/pylosimg/';
			$config['max_size']	= '20000';
			$config['max_width']  = '25000';
			$config['max_height']  = '20000';
			$config['encrypt_name']  = true;
		
			$this->load->library('upload', $config);
		} else {
			$output = array('error'=>'Hmmm, your file type is unsupported via this method.');
		}
		
		if ( ! $this->upload->do_upload())
		{
			json_encode(array('error' => $this->upload->display_errors()));
			$success = false;
			$path = false;
		}
		else
		{
			$data = $this->upload->data();
		    /* Sample Response
				[file_name]    => mypic.jpg
			    [file_type]    => image/jpeg
			    [file_path]    => /path/to/your/upload/
			    [full_path]    => /path/to/your/upload/jpg.jpg
			    [raw_name]     => mypic
			    [orig_name]    => mypic.jpg
			    [client_name]  => mypic.jpg
			    [file_ext]     => .jpg
			    [file_size]    => 22.2
			    [is_image]     => 1
			    [image_width]  => 800
			    [image_height] => 600
			    [image_type]   => jpeg
			    [image_size_str] => width="800" height="200"
			*/
			
			$path = $config['return_path'].$data['file_name'];
			if ($return == 'url') {
				print $path;
				return true;
			}
			//$output = $data;
			$success = true;
		}
		// check and process based on successful status 
		if ($success === true) {
		    $output = array('filename' => $path);
		} elseif ($success === false) {
		    switch ($type) {
		    	case ('file'): $output = array('error'=>'Uh oh... Please upload a zip file no larger than 500mb.'); break;
		    	case ('guide'): $output = array('error'=>'Uh oh... Please upload a zip file no larger than 500mb.'); break;
		    	case ('grasshopper'): $output = array('error'=>'Blast, make sure your zip file is less than 500mb.'); break;
		    	case ('presentation'): $output = array('error'=>'Hold up professor, we only take PDF files less than 100mb.'); break;
		    	case ('thumbnail'): $output = array('error'=>'Keep your png/jpg smaller than 20,000px wide and less than 20mb.'); break;
		    	default: $output = array('error'=>'It\'s us, not you, try reloading the page because something isn\'t right.'); break;
		    }
		} else {
		    $output = array('error'=>'Well shoot, no files were processed.');
		}
		
		if ($hostunique === false) $hostunique = '';

		// let's store this in the database too
		$user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : false;
		$insert = array(
			'url' => $path,
			'timestamp' => time(),
			'tempkey' => $hostunique,
			'type' => $type,
			'user' => ($this->ion_auth->logged_in()) ? $user->id: '',
			'parenttype' => (is_null($hostid)) ? '' : $hosttype,
			'parentid' => (is_null($hostid)) ? '' : $hostid,
		);
		
		if ($success && $type == 'grasshopper') {
			$insert['temp'] = sha1('pyloswizard-'.microtime());
		}
		$query = $this->db->insert("build_pylos_files",$insert);


		if ($return == 'url') {
			return (isset($output['error'])) ? 'poop!' : $path;
		} else if ($type == 'grasshopper' && $hostunique == 'wizard') {
			echo site_url('pylos/blocks/import/'.$insert['temp']);
		} else {
			echo json_encode($output);
		}
	}

	// Time Difference
	public function twitterdate($date)
	{
		if(empty($date)) {
			return "No date provided"; 
		}

		//$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$periods = array("s", "m", "h", "d", "w", " month", " year", " decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		
		$now = time();
		//$unix_date = strtotime($date);
		$unix_date = $date;
		// Check validity of date
		if(empty($unix_date)) {
			return "Bad date"; 
		}
		
		// Determine tense of date
		if($now > $unix_date) {
			$difference = $now - $unix_date;
			$tense = "ago"; } else {
			$difference = $unix_date - $now;
			$tense = "from now";
		}
		
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
		
		$difference = round($difference);
		
		if($difference != 1) {
			//$periods[$j].= "s";
		}
		
		//return "$difference $periods[$j] {$tense}";
		return "$difference$periods[$j] {$tense}";
		
		/*
		$result = twitterdate($date);
		echo $result;
		*/
	}	
	
	// html special characters
	public function q($string) {
		return htmlspecialchars($string);
	}

	// explode with multiple delimiters
	public function multiexplode($delimiters,$string) {
		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}



	public function rrmdir($src) {
		$dir = opendir($src);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				$full = $src . '/' . $file;
				if ( is_dir($full) ) {
					rrmdir($full);
				}
				else {
					unlink($full);
				}
			}
		}
		closedir($dir);
		rmdir($src);
	}

	
	public function echovar($var, $debug=false) {
		if (isset($var) && !empty($var)) {
			echo $var;
		} else {
			if ($debug) var_dump($var);
		}
	}
	
	
	public function array_first(array $arr) {
		foreach($arr as $key => $unused) {
			return $key;
		}
		return NULL;
	}
}