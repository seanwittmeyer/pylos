<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Design Explorer Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 2.0 (2018.08.25.1340$2)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Designexplorer_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get_data($type,$id=false,$where=false,$sorttitle=false,$select=false,$limit=false)
	{
		// Setup
		if ($where) $this->db->where($where);
		if ($limit) $this->db->limit($limit);
		if ($sorttitle) $this->db->order_by("title", "asc"); else $this->db->order_by("timestamp", "desc");

		if ($select) $this->db->select($select);

		if ($id === false) {
		// Get all
			$query = $this->db->get("build_$type");
			if ($type === 'link') {
				$result = $query->result_array();
			} else {
				$return = $query->result_array();
				//var_dump($return);die;
				$result = array();
				foreach ($result as $single) {
					$single['type'] = $type;
					$single['__id'] = $id;
					$result[] = $single;
				}
			}
			//var_dump($result);die;
		} else {
		// Get one
			$query = $this->db->get_where("build_$type", array('id'=>$id));
			$result = $query->row_array();
			if ($type != 'link') $result['type'] = $type;
		}
		if (empty($result)) return false;
		return $result;
	}

	public function get_data2($type,$id=false,$where=false,$sorttitle=false,$select=false,$limit=false)
	{
		// Setup
		if ($where) $this->db->where($where);
		if ($limit) $this->db->limit($limit);
		if ($sorttitle) $this->db->order_by("title", "asc"); else $this->db->order_by("timestamp", "desc");

		if ($select) $this->db->select($select);

		if ($id === false) {
		// Get all
			$query = $this->db->get("build_$type");
			$result = $query->result_array();
		} else {
		// Get one
			$query = $this->db->get_where("build_$type", array('id'=>$id));
			$result = $query->row_array();
		}
		if (empty($result)) return false;
		return $result;
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
			case "pylos_designexplorer":
				$this->form_validation->set_rules('payload[file]', 'body', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$url = '/store/de/'.$post['unique'].'/data.csv';
				$insert = array(
					'timestamp' => time(),
					'title' => $post['title'],
					'user' => $user->id,
					'private' => (isset($post['private'])) ? '1': '0',
					'url' => $url
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


		if ($this->form_validation->run() == FALSE)	{
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
			$query = $this->db->insert("build_$type",$insert);
			$return = array(
				'result' => $query,
				'error' => false,
				'code' => 200,
				'insert' => $insert,
				'id' => $this->db->insert_id(),
				'message' => "Your $type has been added to the site!",
			);
			if ($type == 'pylos_designexplorer') {
				if ($this->import($post['unique'])) {
					// be cool
				}
			}

			//if (isset($insert['url'])) $return['url'] = $insert['url'];
			$url = base64_encode(site_url('store/de/'.$post['unique']));
			$return['url'] = site_url("designexplorer/view?ID=$url");
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

	// Import and unzip design explorer dataset
	public function import($token)
	{
		$this->load->library('Unzip');
		
		$unique = $this->get_data2('pylos_files',false,array('tempkey'=>$token),false);
		$path = dirname(realpath(substr($unique[0]['url'], 1))).'/'.$token;
		mkdir($path);

		// unzip the file
		$this->unzip->allow(array('json', 'png', 'gif', 'jpeg', 'jpg', 'csv'));
		$extract = $this->unzip->extract(substr($unique[0]['url'], 1), $path, false);
		//var_dump($extract);

		// prepare images - move to pylosimg and add to database
		$storepath = str_replace('system/', '', BASEPATH);
		
		unlink(substr($unique[0]['url'], 1));
		
		return true;
	}


	// Image Upload
	public function storedata($return=false,$hostunique=false,$type='data')
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
			$config['upload_path'] = './store/de';
			$config['return_path'] = '/store/de/';
			$config['max_size']	= '150000';
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
		    $output = array('error'=>'Crap. Make sure your image is less than 200mb.');
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
			'type' => 'designexplorer',
			'user' => ($this->ion_auth->logged_in()) ? $user->id: '',
		);
		
		if ($success && $type == 'grasshopper') {
			$insert['temp'] = sha1('pyloswizard-'.microtime());
		}
		$query = $this->db->insert("build_pylos_files",$insert);


		if ($return == 'url') {
			return (isset($output['error'])) ? 'poop!' : $path;
		} else if ($type == 'file' && $hostunique == 'wizard') {
			echo site_url('designexplorer/import/'.$insert['temp']);
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

	// make handlebar links
	public function handlebar_links_helper($match) {
		//var_dump($match);die;
		$slug = str_replace(array('{','}'), '', $match[0]);
		$content = $this->get_byslug_notype($match[1]);
		if (is_array($content)) {
			$excerpt = $this->handlebar_links($content[1]['excerpt'],false);
			return '<a href="/'.$content[0].'/'.$content[1]['slug'].'" data-toggle="tooltip" data-title="'.$excerpt.'">'.$content[1]['title'].'</a>';
		} else {
			return($match[0].' HANDLEBAR FAIL');
		}
	}

	// remove handlebars in text
	public function handlebar_title_helper($match) {
		//var_dump($match);die;
		$slug = str_replace(array('{','}'), '', $match[0]);
		$content = $this->get_byslug_notype($match[1]);
		if (is_array($content)) {
			return $content[1]['title'];
		} else {
			return($match[0]);
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