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

class shared extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	// get order(s)
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
		if ($sorttitle === true) {
			$this->db->order_by("title", "asc");
		} elseif (is_array($sorttitle)) {
			$this->db->order_by($sorttitle[0], $sorttitle[1]);
		} else {
			$this->db->order_by("timestamp", "desc");
		}
			
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

	public function get_rating_average($type,$typeid,$target=false)
	{
		// Setup
		$this->db->where(array('type'=>$type,'typeid'=>$typeid,'target'=>$target));
		$query = $this->db->get("build_rating");
		$result = $query->result_array();
		$ratings = array();
		foreach ($result as $single) {
			$ratings[$single['targetid']][] = $single['value'];
		}
		$return = array();
		foreach ($ratings as $key => $single) {
			$return[$key]['count'] = count($single);
			$return[$key]['value'] = array_sum($single) / $return[$key]['count'];
		}
		return $return;
	}


	public function get_attributes($type='attribute')
	{
		// Setup
		$this->db->order_by("id", "asc");
		$query = $this->db->get("build_$type");
		$attributes = $query->result_array();

		// ranking algorithm - simple over/under normalization 
		// 1. Get ratings for this type
		$this->db->where('type', $type);
		$this->db->order_by("typeid", "asc");
		$query = $this->db->get("build_rating");
		$ratings = $query->result_array();
		
		// calculate normalized score for each attribute
		$att_ratings = array();
		foreach ($ratings as $a) {
			$att_ratings[$a['typeid']][] = $a['value'];	
		}
		$att_scores = array();
		foreach ($attributes as $key => $attribute) {
			$b = array_sum($att_ratings[$attribute['id']]);
			$c = count($att_ratings[$attribute['id']]);
			$att_scores[$key] = (string)($b/$c);
			$attributes[$key]['score'] = (string)($b/$c);
		}
		arsort($att_scores);
		
		// sort attributes according to att_sorted
		$sorted = array();
		foreach ($att_scores as $key => $value) {
			$sorted[] = $attributes[$key];
		}
		$return = array('sorted' => $sorted, 'raw' => $attributes, 'ratings' => $ratings);
		return $return;
	}

	public function get_diagrams($type='diagram',$target='definition',$targetid=false,$ratingtarget='',$diagrams=false)
	{
		// Setup
		if ($diagrams === false) {
			$this->db->order_by("timestamp", "desc");
			$this->db->where(array('type'=>$target,'typeid'=>$targetid));
			$query = $this->db->get("build_$type");
			$diagrams = $query->result_array();
		}

		// ranking algorithm - simple over/under normalization 
		// 1. Get ratings for this type
		// $this->db->where('type', $type); // this was counting attribute rankings too.
		$this->db->where(array('type'=>$type,'target'=>$ratingtarget));
		$this->db->order_by("timestamp", "desc");
		$query = $this->db->get("build_rating");
		$ratings = $query->result_array();
		
		// calculate normalized score for each diagram
		$dia_ratings = array();
		foreach ($ratings as $a) {
			$dia_ratings[$a['typeid']][] = $a['value'];	
		}
		$dia_scores = array();
		foreach ($diagrams as $key => $diagram) {
			if (!isset($dia_ratings[$diagram['id']])) {
				$diagrams[$key]['score'] = .5;
				$dia_scores[$key] = .5;
			} else {
				$b = array_sum($dia_ratings[$diagram['id']]);
				$c = count($dia_ratings[$diagram['id']]);
				$dia_scores[$key] = (string)($b/$c);
				$diagrams[$key]['score'] = (string)($b/$c);
			}
		}
		arsort($dia_scores);
		
		// sort diagrams according to dia_sorted
		$sorted = array();
		foreach ($dia_scores as $key => $value) {
			$sorted[] = $diagrams[$key];
		}
		$return = array('sorted' => $sorted, 'raw' => $diagrams, 'ratings' => $ratings);
		return $return;
	}

	public function has_new_diagrams($typeid,$type='definition',$time=86400) {
		// get id and timestamp for our newest diagram
		$this->db->order_by("timestamp", "desc");
		$this->db->where(array('type'=>$type,'typeid'=>$typeid));
		//$this->db->select('id, timestamp');
		$query = $this->db->get("build_diagram");
		if ($query->num_rows() > 0) {
			$diagram = $query->row_array();
			if ($diagram['timestamp'] >= time()-$time) return true;
		}
		return false;
	}

	public function get_ratings($user=false, $type=false, $target=false)
	{
		// Setup
		if ($user === false) {
			$this->db->order_by("user", "asc");
		} else {
			$this->db->where("user",$user);
		}
		if ($type === false) {
			return false; // type is required.
		} elseif ($target === false) {
			$this->db->where("type",$type);
		} else {
			$this->db->where(array("type"=>$type,"target"=>$target));
		}
		$query = $this->db->get("build_rating");
		$ratings = $query->result_array();

		$return = array();
		foreach ($ratings as $rating) {
			$return[$rating['typeid']][] = $rating;	
		}
		return $return;
	}

	public function get_related_parents($hosttype,$hostid,$combined=true)
	{
		// Setup
		//if ($where) $this->db->where($where);
		//($sorttitle) ? $this->db->order_by("title", "asc") : $this->db->order_by("timestamp", "desc");

		//$related = array();
		$query = $this->db->get_where("build_relationship",array($hosttype=>$hostid));
		$result = $query->result_array();
		if (empty($result)) return false;
		$return = array();
		foreach ($result as $single) {
			$return[] = $this->get_data($single['type'],$single['primary'],false,true,false);
			
		}
		return $return;
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
		$slug = url_title($string, '-', TRUE);
		
		slugStart:
		$query = $this->db->get_where("build_$type",array($column=>$slug));
		if ($query->num_rows() > 0) { 
			$slug = $slug.'-1';
			goto slugStart;
		}
		return $slug;
	}

	// add rating
	public function rate($type=false,$typeid=false,$target=false,$targetid=false,$value=false,$verify=true) {
		$user = $this->ion_auth->user()->row();
		$post = $this->input->post('payload');
		if ($type === false) $this->form_validation->set_rules('payload[type]', 'type', 'required');
		if ($typeid === false) $this->form_validation->set_rules('payload[typeid]', 'typeid', 'required');
		//if ($target === false) $this->form_validation->set_rules('payload[target]', 'target', 'required');
		
		if ($verify === true && $this->form_validation->run() == FALSE)	{
			// fail
			$return = array(
				'result' => false,
				'message' => 'Missing arguments...',
				'error' => true
			);
			return $return;
		}
		if ($targetid === false && isset($post['rating']) && is_array($post['rating'])) {
			$targetid = array();
			foreach ($post['rating'] as $ra => $rb) {
				$targetid[] = array(
					'targetid' => $ra,
					'value' => $rb,
				);
			}
		}
		if (is_array($targetid)) {
			$insert = array();
			foreach ($targetid as $single) {
				$insert[] = array(
					'type' => ($type === false) ? $post['type']: $type,
					'typeid' => ($typeid === false) ? $post['typeid']: $typeid,
					'target' => ($target === false) ? $post['target']: $target,
					'targetid' => $single['targetid'],
					'user' => $user->id,
					'timestamp' => time(),
					'value' => (float)$single['value'],
				);
			}
			$this->db->insert_batch("build_rating",$insert);
			//var_dump($insert);die;
		} else {
			$insert = array(
				'type' => ($type === false) ? $post['type']: $type,
				'typeid' => ($typeid === false) ? $post['typeid']: $typeid,
				'user' => $user->id,
				'timestamp' => time(),
				'value' => $post['value']
			);
			if (isset($post['target'])) $insert['target'] = $post['target'];
			if (isset($post['targetid'])) $insert['targetid'] = $post['targetid'];
			$this->db->insert("build_rating",$insert);
			if ($this->db->insert_id() === false) return false;
		}
		return true;
	}

	// add rating
	public function sortdiagrams() {
		$user = $this->ion_auth->user()->row();
		$post = $this->input->post('payload');
		$this->form_validation->set_rules('payload[order]', 'type', 'required');
		
		if ($this->form_validation->run() == FALSE)	{
			// fail
			$return = array(
				'result' => false,
				'message' => 'Missing arguments...',
				'error' => true,
				'code' => 404
			);
			return $return;
		}

		$this->db->order_by("timestamp", "desc");
		$this->db->where(array("type"=>"diagram","target"=>"diagramapp","user"=>$user->id));
		$query = $this->db->get("build_rating");
		$ratings = $query->result_array();
		//print_r($ratings);
		if (isset($ratings[0]['timestamp'])) {
			if ( (time()-43200) <= $ratings[0]['timestamp'] ) return array('result' => false, 'message' => 'You can only rate once per 12 hours...', 'error' => true, 'code' => 404);
		}

		$ratings = array();
		$a = count($post['order']);
		$post['order'] = array_reverse($post['order']);
		$i = 1;
		foreach ($post['order'] as $b) {
			$c = $i/$a;
			if ($c >= .9) $c = .9;
			if ($c <= .1) $c = .1;
			$ratings[] = array(
				'id' => $b,
				'value' => $c,
			);
			$i++;
		}
		$insert = array();
		foreach ($ratings as $single) {
			$insert[] = array(
				'type' => 'diagram',
				'typeid' => $single['id'],
				'target' => 'diagramapp',
				'targetid' => 0,
				'user' => $user->id,
				'timestamp' => time(),
				'value' => (float)$single['value'],
			);
		}
		$this->db->insert_batch("build_rating",$insert);
		//var_dump($insert);die;
		return true;
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

	public function niceduration($seconds) {
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;
		//return "$hours:$minutes:$seconds";
		return $hours > 0 ? $hours."h ".$minutes."m" : ($minutes > 0 ? "$minutes min $seconds sec" : "$seconds sec");
	}
	
	// error handler for potentially null or unset variables with context
	public function nicevar($input,$var,$pre="",$post="") {
		if (isset($input[$var]) && !empty($input[$var])) {
			echo $pre.$input[$var].$post;
		}
	}

	// this function assigns colors from a scale based on an input number and range
	public function speedcolor($speed,$c) {
		$colors = array('#ffd700','#ffbf1e','#ffa636','#fb8c46','#f4734f','#e95c50','#db444c','#ca2f43','#b81a33','#a2071f','#8b0000');
		$level = round((($speed-$c[1])/($c[0]-$c[1]))*10,0);
		return $colors[$level];
	}

	
	// escape double quotes
	public function q($string) {
		return htmlspecialchars($string);
	}

	// curl get
	public function get_curl($url=false, $decode=false, $ua=false) {
		if ($ua === false) $ua = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => $ua
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		
		return ($decode) ? json_decode($resp,true) : $resp;
	}

	// curl get
	public function post_curl($url=false, $payload=false, $decode=false, $ua=false) {
		if ($ua === false) $ua = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
		// Get cURL resource
		$curl = curl_init();
		if (is_array($payload)) {
			$fields = array();
			foreach ($payload as $k => $v) $fields[$k] = urlencode($v);
			foreach ($fields as $k => $v) { $fields_string .= $k.'='.$v.'&'; }
			rtrim($fields_string, '&');
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_USERAGENT => $ua,
				CURLOPT_URL => $url,
				CURLOPT_POST => count($fields),
				CURLOPT_POSTFIELDS => $fields_string
			));
		} else {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);                                                                  
			curl_setopt($curl, CURLOPT_USERAGENT, $ua);                                                                  
			curl_setopt($curl, CURLOPT_URL, $url);                                                                  
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($payload))                                                                       
			);                                                                                                                   
		}


		// Send the request & save response to $resp
		$resp = curl_exec($curl);

		// Close request to clear up some resources
		curl_close($curl);
		
		return ($decode) ? json_decode($resp,true) : $resp;
	}

	// escape double quotes
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

	// escape double quotes
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

	// handlebar parser
	public function handlebar_links($input,$link=true) {	
		if ($link === true) {
			return preg_replace_callback('/\{\{(.+?)\}\}/', array($this,'handlebar_links_helper'), $input);
		} else {
			return preg_replace_callback('/\{\{(.+?)\}\}/', array($this,'handlebar_title_helper'), $input);
		}
	}

	// Send an email, send in an id or the user object
	public function send_email($to,$subject,$message) {
	    $this->load->library('email');
		if (is_numeric($to)) {
			$to = $this->ion_auth->user($to)->row();
			if ($to === false) return false;
		} elseif (!is_object($to)) {
			return false;
		}

	    $defaults = array(
		    'site' => 'CAS Explorer - ',
		    'footer' => "<br><br>Sent by CAS Explorer."
	    );
	    // just in case
	    $this->email->clear();
	
	    $this->email->to($to->email);
	    $this->email->from('theseanwitt@gmail.com');
	    $this->email->subject($defaults['site'].$subject);
	    $this->email->message("Hi {$to->first_name},<br>{$message}{$defaults['footer']}");
	    $this->email->send();
	    
	    /* to use this:
		$email = $this->send_email($user,'Order Posted',"Great success, your {$order['restaurant']} order has been posted. You'll get an email when someone claims to deliver it! \nJust know there isn't any guarantee that it will be picked up and delivered. If no one claims it, you may want to go and get it yourself (and maybe deliver some other orders for karma!).");
		*/

	}

	// Get the weather for a location (string or array of lat/lon) of source default or ski location and echo or return the result
	public function weather($location=false,$source='ip',$formatted='navbar') {
		if ($source == 'ip') {
			// Use IP address to get location. 
			$string = ($location == false) ? $_SERVER['REMOTE_ADDR'] : $location;
			// Get recent weather for this IP location
			$this->db->where(array('query'=>$string,'source'=>$source));
			//$this->db->select('id, timestamp');
			$query = $this->db->get("build_weather");
			$flag = false;
			if ($query->num_rows() > 0) {
				$cache = $query->row_array();
				$time = '3600'; // allow the cache to expire after one hour
				if ($cache['timestamp'] >= time()-$time) {
					$flag = true;
					$cache['weather'] = unserialize($cache['weather']);
					$weather = $cache;
					$cacheflag = true;
				} else {
					$weather = $cache;
					$this->db->delete('build_weather', array('id' => $cache['id'])); 
				}
				
			} else {
	        	$details_url = "http://ip-api.com/json/".$string;
		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $details_url);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		        $response = json_decode(curl_exec($ch), true);
		        curl_close($ch);
				$weather = $response;
		        //print_r($response); die;
		        
			}

			if ($flag == false) {
	        	$details_url = "https://api.darksky.net/forecast/53f70ad9e7a06363b1bf6e5ddf7e66e1/".$weather['lat'].','.$weather['lon'];
	        	//print_r($details_url); die;
		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $details_url);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		        $response = json_decode(curl_exec($ch), true);
		        curl_close($ch);
				$weather['weather'] = $response;
				$insert = $weather;
				$insert['timestamp'] = time();
				$insert['source'] = 'ip';
				
				$insert['weather'] = serialize($insert['weather']);
		        //print_r($insert); die;
				$this->db->insert("build_weather",$insert);

			}
			if ($formatted == 'navbar') {
				return floor($weather['weather']['currently']['apparentTemperature']).'&deg; and '.$weather['weather']['currently']['summary'].' in '.$weather['city'];
			} else {
				return $weather;
			}
		}
	}

	public function rgbhex($r,$g,$b) {
		return sprintf("#%02x%02x%02x", $r, $g, $b);
	}

	/*
	// new user
	public function new_user($details)
	{
		// validate
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('orderpassword', 'Password', 'required');
		$this->form_validation->set_rules('orderemail', 'Email', 'required|valid_email');
		if ($this->form_validation->run() == FALSE)	{
			// fail
			return false;
		} else {
			// login if user is already an user
			if ($this->ion_auth->email_check($details['orderemail'])) {
				$login = $this->ion_auth->login($details['orderemail'], $details['orderpassword'], true);
				if (!$login) return false;
			} else {
				$username = $details['orderemail'];
				$password = $details['orderpassword'];
				$email = $details['orderemail'];
				$additional_data = array(
					'first_name' => $details['first_name'],
					'last_name' => $details['last_name'],
					'phone' => $details['phone'],
					'location' => $details['location'],
				);
				$result = $this->ion_auth->register($username, $password, $email, $additional_data);
				if ($result === false) return false;
				$this->ion_auth->login($details['orderemail'], $details['orderpassword'], true);
			}

			$user = $this->ion_auth->user($result)->row();
			return $user;
		}
	}
	*/

}