<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Ski Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 2.0 (2018.08.25.1340$2)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Ski_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	// remove content
	public function remove($type, $id)
	{
		$this->load->helper('file');

		$query = $this->db->get_where("build_$type", array('id'=>$id));
		$result = $query->row_array();

		// Delete main record
		$this->db->where('id', $id);
		$this->db->delete("build_$type");
		
		if (empty($result['url'])) return false;

		// Delete files
		$path = str_replace('/data.csv', '', $result['url']);
		$path = str_replace('/store/de', '', $path);
		$path = './store/de'.$path;
		delete_files($path,TRUE);
		rmdir($path);
		//print_r('delete this folder: '.$path);
		return true;
	}

	// new content
	public function create($type=false, $preload=false)
	{
		// Set up
		$user = $this->ion_auth->user()->row();
		$post = $this->input->post('payload');
		$batch['flag'] = false;
		$bypass = false;
		//$post = $preload;

		if ($type === false) {
			$return = array(
				'result' => false,
				'error' => 'We need to know what type of element you are creating.',
				'code' => 403
			);
			return $return;
		}

		// Cases
		switch ($type) {
			case "days":

				$import = $this->import($post['unique']);

				$check = $this->db->get_where('build_ski_days', array('dayid'=>$import['track']['dayid']));
				if ($check->num_rows() > 0)
				{
					$return = array(
						'result' => false,
						'message' => 'You already imported this day.',
						'error' => true,
						'code' => 403,
					);
					return $return;
				} 

				$batch['flag'] = true;
				$batch['insert']['segments'] = $import['segments'];
				$batch['insert']['nodes'] = $import['nodes'];
				$batch['insert']['battery'] = $import['battery'];
				
				$this->form_validation->set_rules('payload[file]', 'body', 'required');
				$this->form_validation->set_rules('payload[season]', 'season', 'required');
				$this->form_validation->set_rules('payload[resort]', 'resport', 'required');
				$url = '/ski/days/'.$import['track']["dayid"];
				$insert = array(
					'season' => $post['season'],
					'resort' => $post['resort'],
					'timestamp' => time(),
					'user' => $user->id,
					"rating" => $import['track']["rating"], // 0
					"start" => date("U",strtotime($import['track']["start"])), // 2017-01-06T10:05:02.138-08:00
					"finish" => date("U",strtotime($import['track']["finish"])), // 2017-01-06T16:29:52.928-08:00
					"description" => $import['track']["description"], // Mount Hood Meadows
					"version" => $import['track']["version"], // 1.0
					//"modified" => date("U",strtotime($import['track']["modified"])), // 2017-02-26T19:14:03.464-08:00
					"tz" => $import['track']["tz"], // -08:00
					//"includeinseason" => $import['track']["includeinseason"], // true
					"conditions" => $import['track']["conditions"], // unknown
					"platform" => $import['track']["platform"], // iPhone8,1/ios-10.2/SkiTracks-1.5.1(174)
					"weather" => $import['track']["weather"], // unknown
					"activity" => $import['track']["activity"], // skiing
					//"hidden" => $import['track']["hidden"], // false
					"trackduration" => $import['track']["duration"], // 21088.695375
					"name" => $import['track']["name"], // Day 1 - 2016/2017
					"dayid" => $import['track']["dayid"], // kTFClqub3D
					"maxspeed" => $import['track']["maxspeed"], // 22.248861
					"maxdescentspeed" => $import['track']["maxdescentspeed"], // 22.248861
					"maxascentspeed" => $import['track']["maxascentspeed"], // 15.210088
					"maxdescentsteepness" => $import['track']["maxdescentsteepness"], // 32.465549
					"maxascentsteepness" => $import['track']["maxascentsteepness"], // 28.830662
					//"maxverticaldescentspeed" => $import['track']["maxverticaldescentspeed"], // 22.248861
					//"maxverticalascentspeed" => $import['track']["maxverticalascentspeed"], // 15.210088
					"totalascent" => $import['track']["totalascent"], // 7065.899414
					"totaldescent" => $import['track']["totaldescent"], // 7424.458008
					"maxaltitude" => $import['track']["maxaltitude"], // 2226.428711
					"minaltitude" => $import['track']["minaltitude"], // 1384.797852
					"distance" => $import['track']["distance"], // 77972.226562
					//"profiledistance" => $import['track']["profiledistance"], // 0.000000
					"descentdistance" => $import['track']["descentdistance"], // 45662.984375
					"ascentdistance" => $import['track']["ascentdistance"], // 32309.246094
					"averagespeed" => $import['track']["averagespeed"], // 3.377854
					"averagedescentspeed" => $import['track']["averagedescentspeed"], // 5.754669
					"averageascentspeed" => $import['track']["averageascentspeed"], // 2.236158
					"movingaveragespeed" => $import['track']["movingaveragespeed"], // 8.318140
					//"movingaveragedescentspeed" => $import['track']["movingaveragedescentspeed"], // 0.000000
					//"movingaverageascentspeed" => $import['track']["movingaverageascentspeed"], // 0.000000
					"duration" => $import['track']["duration"], // 23083.365234
					"startaltitude" => $import['track']["startaltitude"], // 1595.658936
					"finishaltitude" => $import['track']["finishaltitude"], // 1384.797852
					"ascents" => $import['track']["ascents"], // 19
					"descents" => $import['track']["descents"], // 20
					"laps" => $import['track']["laps"] // 0
				);

				//print_r($insert);

				break;
			case "resorts":
				$bypass = true;
				//$url = "https://build.seanwittmeyer.com/includes/test/snow";
				$url = "http://feeds.snocountry.net/conditions.php?apiKey=SnoCountry.example&ids=".$preload[0];
				$result = $this->shared->get_curl($url,true);
				$resort = $result['items'][0];
				$insert = array(
					'slug' => $preload[1],
					'timestamp' => time(),
					'unique' => sha1('build-'.microtime()),
					'snocountry' => $resort['id'],
					'name' => $resort['resortName'],
					'state' => $resort['state'],
					'country' => $resort['country'],
					'lat' => $resort['latitude'],
					'lon' => $resort['longitude'],
					'cams' => $resort['webCamLink'],
					'map' => $resort['lgTrailMapURL'],
					'timezone' => $resort['timezone'],
					'trailsbeginner' => $resort['numberBeginnerTrails'],
					'trailsintermediate' => $resort['numberIntermediateTrails'],
					'trailsadvanced' => $resort['numberAdvancedTrails'],
					'trailsexpert' => $resort['numberExpertTrails'],
					'elevationbottom' => $resort['lowBaseElevation'],
					'elevationtop' => $resort['highLiftElevation'],
					'vertical' => $resort['verticalDrop'],
					'longesttrail' => $resort['longestTrail'],
					'longesttraillength' => $resort['longestTrailLength'],
					'phone' => $resort['reservationPhone'],
					'website' => $resort['webSiteLink'],
					'nighttrails' => $resort['nightSkiingTrails'],
					'nightlifts' => $resort['nightSkiingLifts'],
					'downhilltrails' => $resort['maxOpenDownHillTrails'],
					'downhillacres' => $resort['maxOpenDownHillAcres'],
					'downhilllifts' => $resort['maxOpenDownHillLifts'],
					'address' => $resort['resortAddress'],
					'raw' => serialize($result),
					'user' => $user->id
				);

				break;
			case "dataset":
				$this->form_validation->set_rules('payload[body]', 'body', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');

				$insert = array(
					'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					'unique' => sha1('cas-'.microtime()),
					'body' => $post['body'],
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'user' => $user->id,
				);
				if (isset($post['relationships'])) {
					$relationships_insert = array();
					foreach ($post['relationships'] as $rel_type => $rel_array) {
						if (!empty($rel_array)) {
							foreach ($rel_array as $rel_id) $relationships_insert[] = array(
								'type' => 'definition',
								'definition' => '',
								'link' => '',
								'page' => '',
								'paper' => '',
								'synonym' => '',
								'taxonomy' => '',
								$rel_type => $rel_id,
							);
						}
					}
				}

				break;
			case "full list":
				// $this->form_validation->set_rules('restaurant', 'restaurant', 'required');
				die;
				$insert = array(
					'user' => $user->id,
					'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'unique' => sha1('cas-'.microtime()),
					'timestamp' => time(),
					'type' => $post['type'],
					'uri' => $post['uri'],
					//'storage' => $image,
					'payload' => serialize($post['payload']),
					'caption' => $post['caption'],
					'doi' => $post['doi'],
					'author' => $post['author'],
					'abstract' => $post['abstract'],
					'primary' => $post['primary'],
					'alternate' => $post['alternate'],
					'definition' => (isset($post['definition'])) ? $post['definition']: '-',
					'link' => (isset($post['link'])) ? $post['link']: '-',
					'page' => (isset($post['page'])) ? $post['page']: '-',
					'paper' => (isset($post['paper'])) ? $post['paper']: '-',
					'synonym' => (isset($post['synonym'])) ? $post['synonym']: '-',
					'taxonomy' => (isset($post['taxonomy'])) ? $post['taxonomy']: '-',
				);
				break;
			default:
				$return = array(
					'error' => true,
					'message' => 'We can only create supported types.',
				);
				return $return;
				break;
		}


		if ($bypass == false and $this->form_validation->run() == FALSE)	{
			// fail
			$return = array(
				'result' => false,
				'message' => 'Missing arguments...',
				'error' => true,
				'code' => 403,
			);
			return $return;
		} else {
			// success
			$query = $this->db->insert("build_ski_$type",$insert);
			$return = array(
				'result' => $query,
				'error' => false,
				'code' => 200,
				'insert' => $insert,
				'id' => $this->db->insert_id(),
				'message' => "Your $type has been added to the site!",
			);
			
			if ($batch['flag']) {
				foreach ($batch['insert'] as $table => $values) {
					$this->db->insert_batch("build_ski_$table", $values);
				}
			}

			//if (isset($insert['url'])) $return['url'] = $insert['url'];
			//$url = base64_encode(site_url('store/de/'.$post['unique']));
			$return['url'] = site_url($url);
			if (isset($insert['slug'])) $return['url'] = site_url(str_ireplace('_', '/', $type).'/'.$insert['slug']);

			return $return;
		}
	}
	

	// new content
	public function update($type, $id, $file = false)
	{
		// Set up
		$user = $this->ion_auth->user()->row();
		$post = $this->input->post('payload');
		//$post = $preload;

		// Cases
		switch ($type) {
			case "definition":
				
				$this->form_validation->set_rules('payload[body]', 'body', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');

				$update = array(
					//'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					'body' => $post['body'],
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'payload' => (isset($post['payload'])) ? serialize($post['payload']) : '',
				);
				if (isset($post['relationships'])) {
					$relationships_insert = array();
					foreach ($post['relationships'] as $rel_type => $rel_array) {
						if (!empty($rel_array)) {
							foreach ($rel_array as $rel_id) $relationships_insert[] = array(
								'primary' => $id,
								'type' => 'definition',
								'definition' => '',
								'link' => '',
								'page' => '',
								'paper' => '',
								'synonym' => '',
								'taxonomy' => '',
								$rel_type => $rel_id,
							);
						}
					}
				}

				break;
			default:
				$return = array(
					'error' => true,
					'message' => 'We can only update supported types.',
				);
				return $return;
				break;
		}

		if ($this->form_validation->run() == FALSE)	{
			// fail
			$return = array(
				'result' => false,
				'message' => 'Missing arguments...',
				'error' => true
			);
			return $return;
		} else {
			// success
			$this->db->where('id', $id);
			//json_encode($update);die;
			$query = $this->db->update("build_$type",$update);
			$return = array(
				'result' => $query,
				'error' => false,
				'update' => $update,
				'id' => $id,
				//'url' => (isset($insert['slug'])) ? site_url($type.'/'.$insert['slug']) : $insert['uri'],
				'message' => "Your $type has been updated!",
			);
			if ($file === false) {
				$this->db->where(array('primary'=>$id,'type'=>$type));
			}
			return $return;
		}
	}

	// Import and unzip ski dataset
	public function import($token)
	{
		$this->load->library('Unzip');
		
		$unique = $this->shared->get_data2('pylos_files',false,array('tempkey'=>$token),false);
		$path = dirname(realpath(substr($unique[0]['url'], 1))).'/'.$token;
		mkdir($path);

		// unzip the file
		$this->unzip->allow(array('xml', 'csv'));
		$extract = $this->unzip->extract(substr($unique[0]['url'], 1), $path, false);
		//var_dump($extract);

		// prepare images - move to pylosimg and add to database
		$storepath = str_replace('system/', '', BASEPATH);
		
		unlink(substr($unique[0]['url'], 1));
		
		$return = array();
		
		//list($path) = get_included_files(); 
		//$path = str_replace('/index.php', $block['url'], $path);

		$xml = simplexml_load_file($path.'/Track.xml');
		$json_string = json_encode($xml);
		$track = json_decode($json_string, TRUE);
		$return['track'] = array(
			"rating" 					=> $track["@attributes"]["rating"], // 0
			"start" 					=> $track["@attributes"]["start"], // 2017-01-06T10:05:02.138-08:00
			"finish" 					=> $track["@attributes"]["finish"], // 2017-01-06T16:29:52.928-08:00
			"description" 				=> $track["@attributes"]["description"], // Mount Hood Meadows
			"version" 					=> $track["@attributes"]["version"], // 1.0
			//"modified" 					=> $track["@attributes"]["modified"], // 2017-02-26T19:14:03.464-08:00
			"tz" 						=> $track["@attributes"]["tz"], // -08:00
			"includeinseason" 			=> $track["@attributes"]["includeinseason"], // true
			"conditions" 				=> $track["@attributes"]["conditions"], // unknown
			"platform" 					=> $track["@attributes"]["platform"], // iPhone8,1/ios-10.2/SkiTracks-1.5.1(174)
			"weather" 					=> $track["@attributes"]["weather"], // unknown
			"activity" 					=> $track["@attributes"]["activity"], // skiing
			"hidden" 					=> $track["@attributes"]["hidden"], // false
			"duration" 					=> $track["@attributes"]["duration"], // 21088.695375
			"name" 						=> $track["@attributes"]["name"], // Day 1 - 2016/2017
			"maxspeed" 					=> (float)$track["metrics"]["maxspeed"] * 2.237, // 22.248861
			"maxdescentspeed" 			=> (float)$track["metrics"]["maxdescentspeed"] * 2.237, // 22.248861
			"maxascentspeed" 			=> (float)$track["metrics"]["maxascentspeed"] * 2.237, // 15.210088
			"maxdescentsteepness" 		=> $track["metrics"]["maxdescentsteepness"], // 32.465549
			"maxascentsteepness" 		=> $track["metrics"]["maxascentsteepness"], // 28.830662
			//"maxverticaldescentspeed" 	=> (float)$track["metrics"]["maxverticaldescentspeed"] * 2.237, // 22.248861
			//"maxverticalascentspeed" 	=> (float)$track["metrics"]["maxverticalascentspeed"] * 2.237, // 15.210088
			"totalascent" 				=> (float)$track["metrics"]["totalascent"] * 3.281, // 7065.899414
			"totaldescent" 				=> (float)$track["metrics"]["totaldescent"] * 3.281, // 7424.458008
			"maxaltitude" 				=> (float)$track["metrics"]["maxaltitude"] * 3.281, // 2226.428711
			"minaltitude" 				=> (float)$track["metrics"]["minaltitude"] * 3.281, // 1384.797852
			"distance" 					=> (float)$track["metrics"]["distance"] * 3.281, // 77972.226562
			"profiledistance" 			=> (isset($track["metrics"]["profiledistance"])) ? (float)$track["metrics"]["profiledistance"] * 3.281 : 0, // 0.000000
			"descentdistance" 			=> (float)$track["metrics"]["descentdistance"] * 3.281, // 45662.984375
			"ascentdistance" 			=> (float)$track["metrics"]["ascentdistance"] * 3.281, // 32309.246094
			"averagespeed" 				=> (float)$track["metrics"]["averagespeed"] * 2.237, // 3.377854
			"averagedescentspeed" 		=> (float)$track["metrics"]["averagedescentspeed"] * 2.237, // 5.754669
			"averageascentspeed" 		=> (float)$track["metrics"]["averageascentspeed"] * 2.237, // 2.236158
			"movingaveragespeed" 		=> (float)$track["metrics"]["movingaveragespeed"] * 2.237, // 8.318140
			//"movingaveragedescentspeed" => (float)$track["metrics"]["movingaveragedescentspeed"] * 2.237, // 0.000000
			//"movingaverageascentspeed" 	=> (float)$track["metrics"]["movingaverageascentspeed"] * 2.237, // 0.000000
			"duration" 					=> $track["metrics"]["duration"], // 23083.365234
			"startaltitude" 			=> (float)$track["metrics"]["startaltitude"] * 3.281, // 1595.658936
			"finishaltitude" 			=> (float)$track["metrics"]["finishaltitude"] * 3.281, // 1384.797852
			"ascents" 					=> $track["metrics"]["ascents"], // 19
			"descents" 					=> $track["metrics"]["descents"], // 20
			"laps" 						=> (isset($track["metrics"]["laps"])) ? $track["metrics"]["laps"]: 0 // 0
		);
		$return['track']['dayid'] = (isset($track["@attributes"]["parseObjectId"])) ? $track["@attributes"]["parseObjectId"] : substr($track["@attributes"]["syncIdentifier"],-10); // kTFClqub3D


		$nodes = file($path.'/Nodes.csv');
		$return['nodes'] = array();

		foreach ($nodes as $n) {
			$m = explode(',', $n);
			$return['nodes'][] = array(
				'time' => $m[0],
				'lat' => $m[1],
				'lon' => $m[2],
				'elev' => (float)$m[3] * 3.281,
				'vector' => $m[4],
				'speed' => (float)$m[5] * 2.237,
				'x' => $m[6],
				'y' => trim(preg_replace('/\s+/', ' ', $m[7])),
				'parenttype' => 'ski_days',
				'parentid' => $return['track']['dayid'],
			);
		}

		$segment = file($path.'/Segment.csv');
		$return['segments'] = array();

		$i = 0;
		foreach ($segment as $n) {
			if ($i > 0) {
				$m = explode(',', $n);
				$return['segments'][] = array(
					'start' => $m[0],
					'end' => $m[1],
					'type' => ($m[2] == 10) ? 'lift':'run',
					'run' => $m[4],
					'title' => trim(preg_replace('/\s+/', ' ', $m[5])),
					'parenttype' => 'ski_days',
					'parentid' => $return['track']['dayid'],
				);
			}
			$i++;
		}


		$battery = file($path.'/Battery.csv');
		$return['battery'] = array();
		

		foreach ($battery as $n) {
			$m = explode(',', $n);
			$return['battery'][] = array(
				'time' => date("U",strtotime($m[0])),
				'state' => $m[1],
				'level' => trim(preg_replace('/\s+/', ' ', $m[2])),
				'parenttype' => 'ski_days',
				'parentid' => $return['track']['dayid'],
			);
		}
		
		$this->rrmdir($path);
				
		return $return;
	}


	// Image Upload
	public function storedata($return=false,$hostunique=false,$type='data')
	{

		if (empty($_FILES['userfile'])) {
		    echo json_encode(array('error'=>'Only valid .skiz files please.')); 
		    // or you can throw an exception 
		    return; // terminate
		}
		
		//$images = $_FILES['image'];
		
		// fetch form data
		$userid = empty($_POST['userid']) ? '' : $_POST['userid'];
		$success = null;
		//$filenames = $images['name'];

		if ($type == 'file') {
			$config['allowed_types'] = 'zip|skiz';
			$config['upload_path'] = './store/ski';
			$config['return_path'] = '/store/ski/';
			$config['max_size']	= '100000';
			$config['encrypt_name']  = true;

			$this->load->library('upload', $config);
		
		} else {
			$output = array('error'=>'Hmmm, your file type is unsupported via this method.');
		}
		
		if ( ! $this->upload->do_upload())
		{
			$error = json_encode(array('error' => $this->upload->display_errors()));
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
		    $output = array('error'=>$error);
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
			'type' => 'ski',
			'user' => ($this->ion_auth->logged_in()) ? $user->id: '',
		);
		
		if ($success && $type == 'grasshopper') {
			$insert['temp'] = sha1('pyloswizard-'.microtime());
		}
		$query = $this->db->insert("build_pylos_files",$insert);


		if ($return == 'url') {
			return (isset($output['error'])) ? 'poop!' : $path;
		} else if ($type == 'file' && $hostunique == 'wizard') {
			echo site_url('ski/import/'.$insert['temp']);
		} else {
			echo json_encode($output);
		}
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


}