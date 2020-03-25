<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Shared Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Vr_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function get_related($hosttype,$hostid,$combined=true)
	{
		// Setup
		//if ($where) $this->db->where($where);
		//($sorttitle) ? $this->db->order_by("title", "asc") : $this->db->order_by("timestamp", "desc");

		//$related = array();
		$query = $this->db->get_where("build_relationship",array('primary'=>$hostid,'type'=>$hosttype));
		$result = $query->result_array();
		if (empty($result)) return false;
		$return = array();
		foreach ($result as $single) {
			if ($single['definition'] != "0") { $i = array('definition',$single['definition']); }
			elseif ($single['taxonomy'] != "0") { $i = array('taxonomy',$single['taxonomy']); }
			elseif ($single['page'] != "0") { $i = array('page',$single['page']); }
			elseif ($single['paper'] != "0") { $i = array('paper',$single['paper']); }
			else return false;
				
			$return[] = $this->get_data($i[0],$i[1],false,true,false);
			
		}
		return $return;
	}

	// this function requires the type to be set. if you don't 
	// have the type or need to guess it, use get_byslug_notype()
	
	public function get_byslug($type,$slug=false,$where=false,$sorttitle=false)
	{
		// Setup
		//if ($where) $this->db->where($where);
		//($sorttitle) ? $this->db->order_by("title", "asc") : $this->db->order_by("timestamp", "desc");

		if ($slug) $this->db->where(array('slug' => $slug));
		$query = $this->db->get("build_$type");
		$result = ($slug) ? $query->row_array() : $query->result_array();
		if (empty($result)) return false;
		return $result;
	}

	public function get_byslug_notype($slug)
	{
		foreach (array('taxonomy','definition','page','paper') as $type) {
			$this->db->where(array('slug' => $slug));
			$query = $this->db->get("build_$type");
			$result = $query->row_array();
			if (empty($result)) continue;
			return array($type,$result);
		}
		// couldn't find any matching content
		return false;
	}

	public function is_child($type, $primary, $child, $childid) {
		$this->db->where(array('primary'=>$primary,'type'=>$type, $child=>$childid));
		$query = $this->db->get('build_relationship');
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function is_parent($type, $primary, $parent, $parentid) {
		$this->db->where(array('primary'=>$parentid,'type'=>$parent, $type=>$primary));
		$query = $this->db->get('build_relationship');
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
		
	}

	public function list_bytype($type,$select=false,$sorttitle=false)
	{
		if ($select === false) {
			
			$this->db->select('id, title, slug');
		} else {
			$this->db->select($select);
		}
		$this->db->order_by("title", "asc");

		$query = $this->db->get("build_$type");
		$result = $query->result_array();
		if (empty($result)) return false;
		return $result;
	}

	// slug maker
	public function slug($string,$type,$column) {
		$slug = url_title($string, 'dash', TRUE);
		
		slugStart:
		$query = $this->db->get_where("build_$type",array($column=>$slug));
		if ($query->num_rows() > 0) { 
			$slug = $slug.'-1';
			goto slugStart;
		}
		return $slug;
	}

	// new content
	public function remove($type, $id)
	{
		// Delete main record
		$this->db->where('id', $id);
		$this->db->delete("build_$type");

		// Delete links
		$this->db->where(array('hostid'=>$id,'hosttype'=>$type));
		$this->db->delete("build_link");

		// Delete relationships
		$this->db->where(array('primary'=>$id,'type'=>$type));
		$this->db->delete("build_relationship");

		$this->db->where(array($type=>$id));
		$this->db->delete("build_relationship");
		
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
			);
			return $return;
		}

		// Cases
		switch ($type) {
			case "attribute":
				$this->form_validation->set_rules('payload[title]', 'title', 'required');

				$insert = array(
					'timestamp' => time(),
					'title' => $post['title'],
					'user' => $user->id
				);
				break;
			case "diagram":

				$this->form_validation->set_rules('payload[url]', 'url', 'required');
				$this->form_validation->set_rules('payload[typeid]', 'type id', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');
				$this->form_validation->set_rules('payload[target]', 'target', 'required');

				$insert = array(
					'timestamp' => time(),
					'type' => $post['type'],
					'typeid' => $post['typeid'],
					'url' => $post['url'],
					'user' => $user->id,
				);
				if (isset($post['rating']) && array($post['rating'])) {
					$ratings_insert = array();
					foreach ($post['rating'] as $ra => $rb) {
						$ratings_insert[] = array(
							'targetid' => $ra,
							'value' => $rb,
						);
					}
					$ratings_args = array('diagram','attribute',false,false);
					
				}

				break;
			case "comment":
				$this->form_validation->set_rules('payload[type]', 'type', 'required');
				$this->form_validation->set_rules('payload[typeid]', 'typeid', 'required');
				$this->form_validation->set_rules('payload[value]', 'value', 'required');

				$insert = array(
					'timestamp' => time(),
					'content' => (isset($post['content'])) ? $post['content']: '',
					'value' => $post['value'],
					'type' => $post['type'],
					'typeid' => $post['typeid'],
					'user' => $user->id,
				);

				break;
			case "definition":
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
			case "taxonomy":
					$this->form_validation->set_rules('payload[body]', 'body', 'required');
					$this->form_validation->set_rules('payload[title]', 'title', 'required');
					$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');

				$post['payload'] = array();
				$insert = array(
					'user' => $user->id,
					'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'unique' => sha1('cas-'.microtime()),
					'timestamp' => time(),
					'payload' => serialize($post['payload']),
					'definition' => (isset($post['definition'])) ? $post['definition']: '-',
					'link' => (isset($post['link'])) ? $post['link']: '-',
					'page' => (isset($post['page'])) ? $post['page']: '-',
					'paper' => (isset($post['paper'])) ? $post['paper']: '-',
					'synonym' => (isset($post['synonym'])) ? $post['synonym']: '-',
					'taxonomy' => (isset($post['taxonomy'])) ? $post['taxonomy']: '-',
				);
				if (isset($post['relationships'])) {
					$relationships_insert = array();
					foreach ($post['relationships'] as $rel_type => $rel_array) {
						if (!empty($rel_array)) {
							foreach ($rel_array as $rel_id) $relationships_insert[] = array(
								'type' => 'taxonomy',
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
			case "link":
				$this->form_validation->set_rules('payload[hostid]', 'id', 'required');
				$this->form_validation->set_rules('payload[hosttype]', 'type', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[uri]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$post['payload'] = array();
				$insert = array(
					'user' => $user->id,
					'hosttype' => $post['hosttype'],
					'hostid' => $post['hostid'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'unique' => sha1('cas-'.microtime()),
					'timestamp' => time(),
					'type' => $post['type'],
					'uri' => $post['uri'],
					'payload' => serialize($post['payload']),
				);
				
				break;
			case "page":
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$post['payload'] = array();
				$insert = array(
					'user' => $user->id,
					'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'template' => $post['template'],
					'timestamp' => time(),
					'type' => $post['type'],
					'payload' => serialize($post['payload']),
					'author' => $post['author'],
				);
				
				break;
			case "paper":
				// $this->form_validation->set_rules('restaurant', 'restaurant', 'required');
				$post['payload'] = array();
				$insert = array(
					'user' => $user->id,
					'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'timestamp' => time(),
					'type' => $post['type'],
					'uri' => $post['uri'],
					//'storage' => $image,
					'payload' => serialize($post['payload']),
					'author' => $post['author'],
					'abstract' => $post['abstract'],
				);
				
				break;
			case "relationship":
				// $this->form_validation->set_rules('restaurant', 'restaurant', 'required');
				$count = count($post['relationships']['type']);
				
				$insert = array();
				for ($i=1; $i <= $count; $i++) {
					$insert[] = array(
						'type' => $post['relationships']['hosttype'],
						'primary' => $post['relationships']['hostid'],
						'definition' => ($post['relationships']['type'][$i] == 'definition') ? $post['relationships']['id'][$i]: '',
						'link' => ($post['relationships']['type'][$i] == 'link') ? $post['relationships']['id'][$i]: '',
						'page' => ($post['relationships']['type'][$i] == 'page') ? $post['relationships']['id'][$i]: '',
						'paper' => ($post['relationships']['type'][$i] == 'paper') ? $post['relationships']['id'][$i]: '',
						'synonym' => ($post['relationships']['type'][$i] == 'synonym') ? $post['relationships']['id'][$i]: '',
						'taxonomy' => ($post['relationships']['type'][$i] == 'taxonomy') ? $post['relationships']['id'][$i]: '',
					);
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
				'error' => true
			);
			return $return;
		} else {
			// success
			$query = ($type == 'relationship') ? $this->db->insert_batch("build_relationship",$insert) : $this->db->insert("build_$type",$insert);
			$return = array(
				'result' => $query,
				'error' => false,
				'insert' => $insert,
				'id' => $this->db->insert_id(),
				'message' => "Your $type has been added to the site!",
			);
			if (isset($insert['url'])) $return['url'] = $insert['url'];
			if (isset($insert['slug'])) $return['url'] = site_url($type.'/'.$insert['slug']);
			if (isset($relationships_insert)) {
				// walk array and insert our new id
				$insert_r = array();
				foreach ($relationships_insert as $a) {
					$a['primary'] = $this->db->insert_id();
					$insert_r[] = $a;
				}
				//print_r($insert_r); die;
				$this->db->insert_batch("build_relationship",$insert_r);
				$return['relationship_insert'] = true;
			}
			if (isset($ratings_args)) {
				$result = $this->rate($ratings_args[0],$this->db->insert_id(),$ratings_args[1],$ratings_insert,$ratings_args[2],$ratings_args[3]);
				$return['ratings_insert'] = $result;
			}
			if ($type == 'attribute') {
				$insert = array(
					'type' => 'attribute',
					'typeid' => $this->db->insert_id(),
					'user' => 0, // user 0 is test/demo user
					'timestamp' => time(),
					'value' => .5
				);
				$this->db->insert("build_rating",$insert);
			}

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
			case "taxonomy":
				if ($file !== false) {
					$this->form_validation->set_rules('payload[img]['.$file.']', 'title', 'required');
					$update = array(
						'img' => serialize(array($file=>$post['img'][$file])),
						'timestamp' => time(),
					);
					
					break;
				}
				
				//$this->form_validation->set_rules('payload[body]', 'body', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');

				$update = array(
					//'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'timestamp' => time(),
					'payload' => (isset($post['payload'])) ? serialize($post['payload']) : '',
				);
				if (isset($post['relationships'])) {
					$relationships_insert = array();
					foreach ($post['relationships'] as $rel_type => $rel_array) {
						if (!empty($rel_array)) {
							foreach ($rel_array as $rel_id) $relationships_insert[] = array(
								'primary' => $id,
								'type' => 'taxonomy',
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
			case "page":
				if ($file !== false) {
					$this->form_validation->set_rules('payload[img]['.$file.']', 'title', 'required');
					$update = array(
						'img' => serialize(array($file=>$post['img'][$file])),
						'timestamp' => time(),
					);
					
					break;
				}
				//$this->form_validation->set_rules('payload[body]', 'body', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[body]', 'body', 'required');
				
				$update = array(
					//'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'template' => $post['template'],
					'timestamp' => time(),
					'author' => (isset($post['author'])) ? $post['author']:'Sharon',
				);
				if (!empty($post['payload'])) $update['payload'] = serialize($post['payload']);
				if (isset($post['relationships'])) {
					$relationships_insert = array();
					foreach ($post['relationships'] as $rel_type => $rel_array) {
						if (!empty($rel_array)) {
							foreach ($rel_array as $rel_id) $relationships_insert[] = array(
								'primary' => $id,
								'type' => 'taxonomy',
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
			case "paper":
				// $this->form_validation->set_rules('restaurant', 'restaurant', 'required');
				$post['payload'] = array();
				$update = array(
					//'slug' => $this->slug($post['title'],$type,'slug'),
					'body' => $post['body'],
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'timestamp' => time(),
					'type' => $post['type'],
					'template' => $post['template'],
					'uri' => $post['uri'],
					//'storage' => $image,
					//'payload' => serialize($post['payload']),
					'author' => $post['author'],
					'abstract' => $post['abstract'],
				);
				
				break;
			case "relationship":
				// $this->form_validation->set_rules('restaurant', 'restaurant', 'required');
				if (isset($post['relationships'])) {
					$relationships_insert = array();
					foreach ($post['relationships'] as $rel_type => $rel_array) {
						if (!empty($rel_array)) {
							foreach ($rel_array as $rel_id) $relationships_insert[] = array(
								'primary' => $id,
								'type' => 'taxonomy',
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
				$this->db->delete("build_relationship"); 
				if (isset($relationships_insert)) {
					//print_r($insert_r); die;
					$this->db->insert_batch("build_relationship",$relationships_insert);
					$return['relathionship_insert'] = true;
				}
			}
			return $return;
		}
	}

	// Image Upload
	public function storeimage($return=false)
	{

		if (empty($_FILES['userfile'])) {
		    echo json_encode(array('error'=>'No files found for upload.')); 
		    // or you can throw an exception 
		    return; // terminate
		}
		
		//$images = $_FILES['image'];
		
		// fetch form data
		$userid = empty($_POST['userid']) ? '' : $_POST['userid'];
		$success = null;
		//$filenames = $images['name'];
		$config['upload_path'] = './upload/img/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['max_size']	= '10000';
		$config['max_width']  = '4000';
		$config['max_height']  = '4000';
		$config['encrypt_name']  = true;
		
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			json_encode(array('error' => $this->upload->display_errors()));
			$success = false;
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
			
			$path = '/upload/img/'.$data['file_name'];
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
		    $output = array('error'=>'Crap. Make sure your image is less than 10mb and is a jpg/png/gif no bigger than 4000px.');
		} else {
		    $output = array('error'=>'Well shoot, no files were processed.');
		}
		if ($return == 'url') {
			return (isset($output['error'])) ? 'poop!' : $path;
		} else {
			echo json_encode($output);
		}
	}



}