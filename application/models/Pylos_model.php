<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Pylos Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 2.0 (2018.08.25.1340$2)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Pylos_model extends CI_Model {

	public function __construct()
	{
		$this->config->load('pylos');
		$this->load->database();
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
	/* 
	 *	Get a list of members of our org
	 */

    public function github_members($url) {
	    // https://api.github.com/orgs/zgfarchitectsllp/members

	}
    
	/* 
	 *	The Pylos github blocks api
	 */
	public function github_blocks() {
		// how long will this take?
		$mtime = explode(" ",microtime()); 
		$starttime = $mtime[1] + $mtime[0];; 
	    $config = $this->config->item('pylos');

		$context = curl_init();
		//multiple calls to get() here
		//$forks = json_decode($this->get_curl('https://api.github.com/repos/HydraShare/Hydra/forks',null,$context));
		$forks = json_decode($this->get_curl('https://api.github.com/repos/'.$config['ORIGIN_ACCOUNT'].'/'.$config['ORIGIN_REPO'].'/forks',null,$context));

		$repos = array();
		foreach ($forks as $fork) {
			$repos[] = array(
				'uri' => $fork->full_name,
				'repo' => $fork->name,
				'user' => $fork->owner->login,
				'created' => $fork->created_at,
				'updated' => $fork->updated_at
			);
		}

		$return = array(
			'success' => 'success',
			'samples' => array(),
			'last_updated' => date(DATE_ATOM)
		);

		foreach ($repos as $repo) {
			// get tree
			$tree = json_decode($this->get_curl('https://api.github.com/repos/'.$repo['uri'].'/git/trees/master','&recursive=1',$context));
			$files = $tree->tree;
			$samples = array();
			foreach ($files as $file) {
				// if in name input.json
				if (strpos($file->path, '/input.json')) {
					//get path and name
					$path = explode('/', $file->path);
					$count = count($path)-2;
					$name = $path[$count];
					$path = str_replace('/input.json', '', $file->path);
					// get tags
					$input = json_decode($this->get_curl('https://raw.githubusercontent.com/'.$repo['uri'].'/master/'.$path.'/input.json',null,$context));
					$keywords = array();
					//var_dump($input);
					foreach ($input->tags as $tag) $keywords[] = $tag;
					foreach ($input->dependencies as $tag) $keywords[] = $tag;
					foreach ($input->components as $tag => $misc) $keywords[] = $tag;
					$tags = $repo['user'].$name.$repo['repo'];
					foreach ($keywords as $tag) $tags .= $tag;

					// add to samples
					$samples[] = array(
						'name' => $name,
						'path' => $path,
						'tags' => strtolower($tags)
					);
				}
			}

			foreach ($samples as $sample) {
				// add sample block to return array 
				$return['samples'][] = array(
					"owner" => $repo['user'],
					"id" => $sample['name'],
					"fork" => $repo['repo'],
					"created_at" => $repo['created'],
					"modified_at" => $repo['updated'],
					"thumbnail" => 'https://raw.githubusercontent.com/'.$repo['uri'].'/master/'.$sample['path'].'/thumbnail.png',
					"tags" => $sample['tags']
				);
			}
		}

		curl_close($context);

		// how long will this take?
		$mtime = explode(" ",microtime()); 
		$endtime = $mtime[1] + $mtime[0];; 

		$return['load'] = round($endtime - $starttime, 2); 

		header('Content-Type: application/json');
		print_r(json_encode($return, 64));
    }

	// get data
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

	// returns an array of tag arrays for each tag type in the database
	public function get_tags($type=false,$taxonomy=false)
	{
		// Setup
		$query = ($taxonomy === false) ? $this->db->get("build_pylos_tags"): $this->db->get("build_pylos_taxonomy");
		$tags = $query->result_array();
		
		// place into array
		
		$return = array();
		foreach ($tags as $t) {
			if ($taxonomy === false) {
				$return[$t['type']][$t['title']] = $t['title'];
			} else {
				$return[$t['type']][$t['slug']] = $t['title'];
			}
		}
		return ($type) ? $return[$type] : $return;
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

	// remove content
	public function remove($type, $id)
	{
		// toggle this to false to echo files to be deleted instead of deleting
		$live = true;

		// Get the files from the database
		$query = $this->db->get_where("build_$type", array('id'=>$id));
		$result = $query->row_array();
		
		$files = $this->get_data2("pylos_files",false,array('parentid'=>$id, 'parenttype'=>$type));

		// Delete main record
		$this->db->where('id', $id);
		if ($live) $this->db->delete("build_$type");

		// Delete links
		$this->db->where(array('hostid'=>$id,'hosttype'=>$type));
		if ($live) $this->db->delete("build_link");

		// Delete relationships
		$this->db->where(array('primary'=>$id,'type'=>$type));
		if ($live) $this->db->delete("build_relationship");

		$this->db->where(array($type=>$id));
		if ($live) $this->db->delete("build_relationship");
		
		// Delete tags
		$this->db->where(array('parentid'=>$id,'parenttype'=>$type));
		if ($live) $this->db->delete("build_pylos_tags");

		// Delete revisions
		$revisions = $this->get_data2("pylos_revisions",false,array('parentid'=>$id, 'parenttype'=>$type));
		$this->db->where(array('parentid'=>$id,'parenttype'=>$type));
		if ($live) $this->db->delete("build_pylos_tags");

		// Delete files records
		$this->db->where(array('parentid'=>$id, 'parenttype'=>$type));
		if ($live) {
			$this->db->delete("build_pylos_files");
		} else {
			echo '<pre>';
		}

		// Delete files
		if ($type == 'pylos_block' || $type == 'pylos_guides' || $type == 'pylos_files' || $type == 'pylos_steps') {
			$this->load->helper('file');
			$i = 0;
			if ($live === false) {
				print_r($result);
			}
			if (isset($result['file']) && !empty($result['file']) && is_string($result['file'])) {
				$path = str_replace('/store/', '', $result['file']);
				$path = './store/'.$path;
				if (($path == "./store/")) {
					$i++;
				} else {
					if ($live) {
						if (is_file($path)) unlink($path);
					} else {
						echo 'delete: ['.$path."] is file: " .var_dump(is_file($path))."\n";
					}
				}
			}
			if ($type == 'pylos_files') {
				if (isset($result['url']) && !empty($result['url']) && is_string($result['url'])) {
					$path = str_replace('/store/', '', $result['url']);
					$path = './store/'.$path;
					if (($path == "./store/")) {
						$i++;
					} else {
						if ($live) {
							if (is_file($path)) unlink($path);
						} else {
							echo 'delete: ['.$path."] is file: " .var_dump(is_file($path))."\n";
						}
					}
				}
			}
			if (isset($result['thumbnail']) && !empty($result['thumbnail']) && is_string($result['thumbnail'])) {
				$path = str_replace('/store/', '', $result['thumbnail']);
				$path = './store/'.$path;
				if (($path == "./store/")) {
					$i++;
				} else {
					if ($live) {
						if (is_file($path)) unlink($path);
					} else {
						echo 'delete: ['.$path."] is file: " .var_dump(is_file($path))."\n";
					}
				}
			}
			if (isset($files) && is_array($files) && !empty($files)) {
				foreach ($files as $file) {
					if (isset($file['url']) && !empty($file['url']) && is_string($file['url'])) {
						$path = str_replace('/store/', '', $file['url']);
						$path = './store/'.$path;
						if (($path == "./store/")) {
							$i++;
						} else {
							if ($live) {
								if (is_file($path)) unlink($path);
							} else {
								echo 'delete: ['.$path."] is file: " .var_dump(is_file($path))."\n";
							}
						}
					}
				}
				
			}
			if (isset($revisions) && is_array($revisions) && !empty($revisions)) {
				foreach ($revisions as $file) {
					if (isset($file['url']) && !empty($file['url']) && is_string($file['url'])) {
						$path = str_replace('/store/', '', $file['url']);
						$path = './store/'.$path;
						if (($path == "./store/")) {
							$i++;
						} else {
							if ($live) {
								if (is_file($path)) unlink($path);
							} else {
								echo 'delete: ['.$path."] is file: " .var_dump(is_file($path))."\n";
							}
						}
					}
				}
				
			}
	
		}
		return true;
	}

	// remove temporary and orphaned files
	public function purgefiles($debug=true)
	{
		// toggle this to false to echo files to be deleted instead of deleting
		$live = ($debug === false) ? true : false;

		if (!$live) echo '<pre>';
		// Get the files from the database
		$this->db->where('parenttype','');
		$this->db->where('parentid','');
		$this->db->from('build_pylos_files');
		if (count($this->db->count_all_results()) === 0) return true;

		$this->db->where('parenttype','');
		$this->db->where('parentid','');
		$this->db->from('build_pylos_files');
		$query = $this->db->get();
		$result = $query->result_array();
		
		foreach ($result as $single) {
			if (isset($single['url']) && !empty($single['url']) && is_string($single['url'])) {
				$path = str_replace('/store/', '', $single['url']);
				$path = './store/'.$path;
				if (($path == "./store/")) {
					$i++;
				} else {
					if ($live) {
						if (is_file($path)) unlink($path);
					} else {
						echo 'delete: ['.$path."] is file: " .var_dump(is_file($path))."\n";
					}
				}
			}
		}

		// Delete the records
		$this->db->where('parenttype','');
		$this->db->where('parentid','');
		if ($live) {
			$this->db->delete("build_pylos_files");
		} else {
			echo $this->db->count_all_results(); 
		}
		return true;
	}

	// new content
	public function create($type=false, $preload=false)
	{
		// Set up
		$user = $this->ion_auth->user()->row();
		if ($preload === false) {
			$post = $this->input->post('payload');
		} elseif (is_array($preload)) {
			$post = $preload;
		}
		
		//$post = $preload;

		if ($type === false) {
			$return = array(
				'result' => false,
				'error' => 'We need to know what type of element you are creating.',
				'code' => 403
			);
			return $return;
		}
		
		$relate = array();

		// Cases
		switch ($type) {
			case "pylos_block":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');
				$this->form_validation->set_rules('payload[unique]', 'csrf token', 'required');
				
				$tags_insert = array();

				$insert = array(
					'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					//'unique' => sha1('pylos-'.microtime()),
					'unique' => $post['unique'],
					'description' => (isset($post['description'])) ? $post['description']: '',
					'author' => $post['author'],
					'source' => $post['source'],
					'private' => (isset($post['private'])) ? '1': '0',
					'thumbnail' => (isset($post['importthumbnail'])) ? $post['importthumbnail']: '',
					'code' => (isset($post['code'])) ? $post['code']: null,
					'video' => (isset($post['video'])) ? $post['video']: '',
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'type' => $post['type'],
					'user' => $user->id,
				);
				$tags_flag = false;
				if (isset($post['dependencies']) && !empty($post['dependencies'])) {
					$dependencies = array_map('trim',explode(",",$post['dependencies']));
					foreach ($dependencies as $depa => $depb) {
						if ($depb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'dependency',
								'title' => $depb,
							);
						}
					}
					$tags_flag = true;				
				}
				if (isset($post['tags']) && !empty($post['tags'])) {
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['strategies']) && !empty($post['strategies'])) {
					$tags = array_map('trim',explode(",",$post['strategies']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'strategy',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['components']) && !empty($post['components'])) {
					$tags = array_map('trim',explode(",",$post['components']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'component',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['phase']) && !empty($post['phase'])) {
					$tags = $post['phase'];
					foreach ($tags as $tag) {
						if ($tag == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'phase',
								'title' => $tag,
							);
						}
					}					
				}
				if (isset($post['project']) && !empty($post['project'])) {
					$tags_insert[] = array(
						'parenttype' => 'pylos_block',
						'type' => 'project',
						'title' => $post['project'],
					);
				}

				break;
			case "pylos_guides":
				$this->form_validation->set_rules('payload[question]', 'question', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[why]', 'why', 'required');
				$this->form_validation->set_rules('payload[conclusion]', 'conclusion', 'required');
				$this->form_validation->set_rules('payload[difficulty]', 'difficulty', 'required');
				$this->form_validation->set_rules('payload[unique]', 'csrf token', 'required');
				$this->form_validation->set_rules('payload[time]', 'time', 'required');

				$insert = array(
					'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					//'unique' => sha1('pylos-'.microtime()),
					'unique' => $post['unique'],
					'question' => $post['question'],
					'why' => $post['why'],
					'conclusion' => $post['conclusion'],
					'time' => $post['time'],
					'difficulty' => $post['difficulty'],
					'conclusion' => $post['conclusion'],
					'video' => (isset($post['video'])) ? $post['video'] : '',
					'private' => (isset($post['private'])) ? '1': '0',
					'thumbnail' => '',
					'title' => $post['title'],
					'user' => $user->id,
				);
				$tags_flag = false;
				if (isset($post['tags']) && !empty($post['tags'])) {
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['dependencies']) && !empty($post['dependencies'])) {
					$dependencies = array_map('trim',explode(",",$post['dependencies']));
					foreach ($dependencies as $depa => $depb) {
						if ($depb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'dependency',
								'title' => $depb,
							);
						}
					}
					$tags_flag = true;				
				}
				if (isset($post['strategies']) && !empty($post['strategies'])) {
					$tags = array_map('trim',explode(",",$post['strategies']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'strategy',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['phase']) && !empty($post['phase'])) {
					$tags = $post['phase'];
					foreach ($tags as $tag) {
						if ($tag == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'phase',
								'title' => $tag,
							);
						}
					}					
				}
				if (isset($post['project']) && !empty($post['project'])) {
					$tags_insert[] = array(
						'parenttype' => 'pylos_guides',
						'type' => 'project',
						'title' => $post['project'],
					);
				}
				
				// Flag the files and images for updating with our created guide id
				$relate[] = array('tempkey'=>$post['unique'],'type'=>'guide');
				$relate[] = array('tempkey'=>$post['unique'],'type'=>'thumbnail');

				break;
			case "pylos_steps":
				$this->form_validation->set_rules('payload[text]', 'question', 'required');
				$this->form_validation->set_rules('payload[unique]', 'csrf token', 'required');

				$insert = array(
					'timestamp' => time(),
					'unique' => $post['unique'],
					'text' => $post['text'],
					'order' => $post['order'],
					'parentid' => $post['parentid'],
					'parenttype' => $post['parenttype'],
					'title' => (isset($post['title'])) ? $post['title']: '',
					'video' => (isset($post['video'])) ? $post['video']: '',
					'user' => $user->id,
				);
				/* associate blocks with step - soon
					dummy text below but should be similar
					
				$tags_flag = false;
				if (isset($post['tags']) && !empty($post['tags'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				*/
				
				// Flag the files and images for updating with our created guide id
				if (isset($post['thumbnail']) && !empty($post['thumbnail'])) $relate[] = array('tempkey'=>$post['unique'],'type'=>'thumbnail');
				break;

			case "pylos_presentations":
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');

				$tags_insert = array();
				$insert = array(
					'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					//'unique' => sha1('pylos-'.microtime()),
					'unique' => $post['unique'],
					'title' => $post['title'],
					'description' => $post['description'],
					'excerpt' => $post['excerpt'],
					'type' => $post['type'],
					'author' => (isset($post['author'])) ? $post['author'] : '',
					'source' => (isset($post['source'])) ? $post['source'] : '',
					'video' => (isset($post['video'])) ? $post['video'] : '',
					'private' => (isset($post['private'])) ? '1': '0',
					'user' => $user->id,
				);
				$tags_flag = false;
				if (isset($post['tags']) && !empty($post['tags'])) {
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_presentations',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['strategies']) && !empty($post['strategies'])) {
					$tags = array_map('trim',explode(",",$post['strategies']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_presentations',
								'type' => 'strategy',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['dependencies']) && !empty($post['dependencies'])) {
					$dependencies = array_map('trim',explode(",",$post['dependencies']));
					foreach ($dependencies as $depa => $depb) {
						if ($depb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_presentations',
								'type' => 'dependency',
								'title' => $depb,
							);
						}
					}
					$tags_flag = true;				
				}
				if (isset($post['phase']) && !empty($post['phase'])) {
					$tags = $post['phase'];
					foreach ($tags as $tag) {
						if ($tag == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_presentations',
								'type' => 'phase',
								'title' => $tag,
							);
						}
					}					
				}
				if (isset($post['project']) && !empty($post['project'])) {
					$tags_insert[] = array(
						'parenttype' => 'pylos_presentations',
						'type' => 'project',
						'title' => $post['project'],
					);
				}
				
				// Flag the files and images for updating with our created guide id
				$relate[] = array('tempkey'=>$post['unique'],'type'=>'presentation');
				$relate[] = array('tempkey'=>$post['unique'],'type'=>'thumbnail');

				break;
			case "pylos_strategies":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');
				$this->form_validation->set_rules('payload[unique]', 'csrf token', 'required');
				
				$tags_insert = array();

				$insert = array(
					'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					//'unique' => sha1('pylos-'.microtime()),
					'unique' => $post['unique'],
					'description' => $post['description'],
					'time' => $post['time'],
					'duration' => $post['duration'],
					'staff' => $post['staff'],
					'difficulty' => $post['difficulty'],
					'questions' => $post['questions'],
					'image' => (isset($post['image'])) ? $post['image']: '',
					'url' => (isset($post['url'])) ? $post['url']: '',
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'type' => $post['type'],
					'user' => $user->id,
				);
				$tags_flag = false;
				if (isset($post['themes']) && !empty($post['themes'])) {
					$themes = array_map('trim',explode(",",$post['themes']));
					foreach ($themes as $depa => $depb) {
						if ($depb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_strategies',
								'type' => 'theme',
								'title' => $depb,
							);
						}
					}
					$tags_flag = true;
				}
				if (isset($post['terms']) && !empty($post['terms'])) {
					$tags = array_map('trim',explode(",",$post['terms']));
					foreach ($terms as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_strategies',
								'type' => 'term',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['phases']) && !empty($post['phases'])) {
					$phases = $post['phases'];
					foreach ($phases as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_strategies',
								'type' => 'phase',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['image']) && !empty($post['image'])) $relate[] = array('tempkey'=>$post['unique'],'type'=>'thumbnail');
				break;
			case "pylos_taxonomy":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');
				//$this->form_validation->set_rules('payload[unique]', 'csrf token', 'required');
				$insert = array(
					'slug' => $this->slug($post['title'],$type,'slug'),
					'timestamp' => time(),
					//'unique' => $post['unique'],
					'description' => $post['description'],
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'type' => $post['type'],
					'user' => $user->id,
				);
				if (isset($post['url'])) $insert['url'] = $post['url'];
				// tags unused here, copy from strategies, it will work.
				break;
			case "pylos_steps_duplicate?":
				$this->form_validation->set_rules('payload[text]', 'question', 'required');
				$this->form_validation->set_rules('payload[unique]', 'csrf token', 'required');

				$insert = array(
					'timestamp' => time(),
					'unique' => $post['unique'],
					'text' => $post['text'],
					'order' => $post['order'],
					'parentid' => $post['parentid'],
					'parenttype' => $post['parenttype'],
					'title' => (isset($post['title'])) ? $post['title']: '',
					'video' => (isset($post['video'])) ? $post['video']: '',
					'user' => $user->id,
				);
				/* associate blocks with step - soon
					dummy text below but should be similar
					
				$tags_flag = false;
				if (isset($post['tags']) && !empty($post['tags'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				*/
				
				// Flag the files and images for updating with our created guide id
				if (isset($post['thumbnail']) && !empty($post['thumbnail'])) $relate[] = array('tempkey'=>$post['unique'],'type'=>'thumbnail');

				break;
			case "pylos_revisions":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[url]', 'uploaded file', 'required');
				$this->form_validation->set_rules('payload[parentid]', 'parent id', 'required');
				$this->form_validation->set_rules('payload[parenttype]', 'parent type', 'required');

				$insert = array(
					'parentid' => $post['parentid'],
					'timestamp' => time(),
					'unique' => $post['unique'],
					'url' => $post['url'],
					'description' => $post['description'],
					'parenttype' => 'pylos_block',
					'user' => $user->id,
				);

				$db_files = array();
				$files = $this->get_data2('pylos_files',false,array('tempkey'=>$post['unique']));
				if ($files) {
					foreach ($files as $file) {
						if ($file['type'] == 'file') {
							$newfile = $file;
							$db_files[] = array(
								'id' => $file['id'],
								'parenttype' => $post['parenttype'],
								'parentid' => $post['parentid'],
								'tempkey' => null,
								'temp' => null
							);
						}
					}
				}
				if (count($db_files) > 1) {
					$keep = array_pop($db_files);
					foreach ($db_files as $i) {
						$this->db->delete('pylos_files', array('id' => $i['id']));
					}
				} else {
					$keep = isset($db_files[0]) ? $db_files[0] : null;
				}
				
				// we will do this later
				//$insert['url'] = $keep['url'];
				
				$this->db->update_batch('build_pylos_files', $db_files, 'id'); 

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
			case "glossary":
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'exceprt', 'required');

				$insert = array(
					'timestamp' => time(),
					'description' => (isset($post['description'])) ? $post['description']: '',
					'excerpt' => $post['excerpt'],
					'title' => $post['title'],
					'timestamp' => time(),
					'user' => $user->id,
				);

				break;
			case "pylos_taxonomy_old":
				$this->form_validation->set_rules('payload[slug]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');

				$insert = array(
					'timestamp' => time(),
					'excerpt' => $post['excerpt'],
					'title' => (isset($post['title'])) ? $post['title']: $post['slug'],
					'url' => (isset($post['url'])) ? $post['url']: '',
					'description' => (isset($post['description'])) ? $post['description']: '',
					'slug' => $post['slug'],
					'parenttype' => (isset($post['parenttype'])) ? $post['parenttype']: '',
					'user' => $user->id,
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
			if ($type == 'relationship') {
				$query = $this->db->insert_batch("build_relationship",$insert);
			} else {
				$query = $this->db->insert("build_$type",$insert);
			}
			
			$return = array(
				'result' => $query,
				'error' => false,
				'code' => 200,
				'insert' => $insert,
				'id' => $this->db->insert_id(),
				'message' => "Your $type has been added to the site!",
			);
			$insert_id = $this->db->insert_id();

			if ($type == 'pylos_steps') {
				$steps = $this->get_data2('pylos_steps',false,array('parenttype'=>$insert['parenttype'],'parentid'=>$insert['parentid']));
				if ($steps !== false && count($steps) > 1) {
					$steps_insert = array();
					foreach ($steps as $step) {
						if ($step['id'] == $insert_id) {
							$current = true;
						} else {
							if ($step['order'] >= $insert['order']) $steps_insert[] = array('id'=>$step['id'],'order'=>($step['order']+1));
						}
					}
					$return['steps'] = $this->db->update_batch("build_pylos_steps",$steps_insert,'id');
				}
				
			}
			if (!empty($relate)) {
				$relate_array = array();
				foreach ($relate as $r) {
					$files = $this->get_data2('pylos_files',false,$r);
					if ($files !== false) {
						foreach ($files as $f) {
							$f['parentid'] = $insert_id;
							$f['parenttype'] = $type;
							$relate_array[] = $f;
						}
					}
				}
				if (!empty($relate_array)) {
					$return['files'] = $this->db->update_batch("build_pylos_files",$relate_array,'id');
				}
			}
			if (isset($insert['url'])) $return['url'] = $insert['url'];
			if (isset($insert['slug'])) {
				if ($type === 'pylos_block') {
					$return['url'] = site_url('pylos/blocks/'.$insert['slug']);
				} else { 
					$return['url'] = site_url(str_ireplace('_', '/', $type).'/'.$insert['slug']);
				}

			}
			if (!empty($tags_insert)) {
				// walk array and insert our new id
				$insert_r = array();
				foreach ($tags_insert as $a) {
					$a['parentid'] = $insert_id;
					$insert_r[] = $a;
				}
				//print_r($insert_r); die;
				$this->db->insert_batch("build_pylos_tags",$insert_r);
				$return['tags_insert'] = true;
			}
			if (isset($ratings_args)) {
				$result = $this->rate($ratings_args[0],$insert_id,$ratings_args[1],$ratings_insert,$ratings_args[2],$ratings_args[3]);
				$return['ratings_insert'] = $result;
			}
			if ($type == 'attribute') {
				$insert = array(
					'type' => 'attribute',
					'typeid' => $insert_id,
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
			case "pylos_block":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');

				$update = array(
					'timestamp' => time(),
					'description' => $post['description'],
					'author' => $post['author'],
					'source' => $post['source'],
					'private' => (isset($post['private'])) ? '1': '0',
					'video' => $post['video'],
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'type' => $post['type'],
					'user' => $user->id,
				);
				if (isset($post['code'])) $update['code'] = $post['code'];
				/*
				$tags_flag = false;
				if (isset($post['dependencies']) && !empty($post['dependencies'])) {
					$tags_insert = array();
					$dependencies = array_map('trim',explode(",",$post['dependencies']));
					foreach ($dependencies as $depa => $depb) {
						if ($depb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'dependency',
								'title' => $depb,
							);
						}
					}
					$tags_flag = true;				
				}
				if (isset($post['tags']) && !empty($post['tags'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['components']) && !empty($post['components'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = array_map('trim',explode(",",$post['components']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'component',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['phase']) && !empty($post['phase'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = $post['phase'];
					foreach ($tags as $tag) {
						if ($tag == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_block',
								'type' => 'phase',
								'title' => $tag,
							);
						}
					}					
				}
				if (isset($post['project']) && !empty($post['project'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags_insert[] = array(
						'parenttype' => 'pylos_block',
						'type' => 'project',
						'title' => $post['project'],
					);
				}
				*/

				break;
			case "pylos_guides":
				$this->form_validation->set_rules('payload[question]', 'question', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[why]', 'why', 'required');
				$this->form_validation->set_rules('payload[conclusion]', 'conclusion', 'required');
				$this->form_validation->set_rules('payload[difficulty]', 'difficulty', 'required');
				$this->form_validation->set_rules('payload[time]', 'time', 'required');

				$update = array(
					'timestamp' => time(),
					'question' => $post['question'],
					'why' => $post['why'],
					'conclusion' => $post['conclusion'],
					'time' => $post['time'],
					'difficulty' => $post['difficulty'],
					'conclusion' => $post['conclusion'],
					'video' => (isset($post['video'])) ? $post['video'] : '',
					//'private' => (isset($post['private'])) ? '1': '0',
					'title' => $post['title'],
					'user' => $user->id,
				);
				/*
				$tags_flag = false;
				if (isset($post['tags']) && !empty($post['tags'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['project']) && !empty($post['project'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags_insert[] = array(
						'parenttype' => 'pylos_guides',
						'type' => 'project',
						'title' => $post['project'],
					);
				}
				*/
				break;
			case "pylos_presentations":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[type]', 'type', 'required');

				$update = array(
					'timestamp' => time(),
					'description' => $post['description'],
					'author' => $post['author'],
					'source' => $post['source'],
					'private' => (isset($post['private'])) ? '1': '0',
					'video' => $post['video'],
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'type' => $post['type'],
					'user' => $user->id,
				);
				break;
			case "pylos_strategies":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[time]', 'time', 'required');
				$this->form_validation->set_rules('payload[duration]', 'duration', 'required');

				$update = array(
					'timestamp' => time(),
					'description' => $post['description'],
					'time' => $post['time'],
					'duration' => $post['duration'],
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'staff' => $post['staff'],
					'difficulty' => $post['difficulty'],
					'questions' => $post['questions'],
					'user' => $user->id,
				);
				break;
			case "pylos_taxonomy":
				$this->form_validation->set_rules('payload[title]', 'title', 'required');
				$this->form_validation->set_rules('payload[excerpt]', 'excerpt', 'required');
				$this->form_validation->set_rules('payload[description]', 'description', 'required');

				$update = array(
					'timestamp' => time(),
					'title' => $post['title'],
					'excerpt' => $post['excerpt'],
					'description' => $post['description'],
					'order' => (isset($post['order'])) ? $post['order'] : '',
					'url' => (isset($post['url'])) ? $post['url'] : '',
					'image' => (isset($post['image'])) ? $post['image'] : '',
					//'private' => (isset($post['private'])) ? '1': '0',
					'user' => $user->id,
				);
				/*
				$tags_flag = false;
				if (isset($post['tags']) && !empty($post['tags'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags = array_map('trim',explode(",",$post['tags']));
					foreach ($tags as $taga => $tagb) {
						if ($tagb == '') {
							continue;
						} else {
							$tags_insert[] = array(
								'parenttype' => 'pylos_guides',
								'type' => 'tag',
								'title' => $tagb,
							);
						}
					}					
				}
				if (isset($post['project']) && !empty($post['project'])) {
					if (!$tags_flag) $tags_insert = array();
					$tags_insert[] = array(
						'parenttype' => 'pylos_guides',
						'type' => 'project',
						'title' => $post['project'],
					);
				}
				*/
				break;
			case "pylos_steps":
				$this->form_validation->set_rules('payload[text]', 'question', 'required');

				$update = array(
					'timestamp' => time(),
					'text' => $post['text'],
					'title' => (isset($post['title'])) ? $post['title']: '',
					'video' => (isset($post['video'])) ? $post['video']: '',
					'user' => $user->id,
				);
				break;
			case "pylos_dependency":
				$this->form_validation->set_rules('payload[excerpt]', 'question', 'required');
				$this->form_validation->set_rules('payload[slug]', 'slug', 'required');
				$update = array(
					'timestamp' => time(),
					'excerpt' => $post['excerpt'],
					'title' => (isset($post['title'])) ? $post['title']: $post['slug'],
					'url' => (isset($post['url'])) ? $post['url']: '',
					'description' => (isset($post['description'])) ? $post['description']: '',
					'slug' => $post['slug'],
					'type' => 'dependency',
					'parenttype' => 'pylos_block',
					'user' => $user->id,
				);
				if (isset($post['dependencyid'])) {
					$type = 'pylos_taxonomy';
					$id = $post['dependencyid'];
				} else {
					return $this->create('pylos_taxonomy', $update);
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
			case "pylos_revisions":
				$this->form_validation->set_rules('payload[description]', 'description', 'required');
				$this->form_validation->set_rules('payload[url]', 'uploaded file', 'required');
				$this->form_validation->set_rules('payload[parentid]', 'parent id', 'required');
				$this->form_validation->set_rules('payload[parenttype]', 'parent type', 'required');

				$update = array(
					'timestamp' => time(),
					'unique' => $post['unique'],
					'description' => $post['description'],
					'user' => $user->id,
				);
				break;
			/*
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
			*/
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
					$return['relationship_insert'] = true;
				}
			}
			return $return;
		}
	}

	// Block Import from Grasshopper Component
	public function import($token)
	{
		$this->load->library('Unzip');
		$return = array(
			'json' => '',
			'images' => array(),
			'title' => '',
			'tags' => array(),
			'components' => array(),
			'dependencies' => array(),
			'file' => '',
			'description' => ''
		);
		
		$unique = $this->get_data2('pylos_files',false,array('temp'=>$token),false);
		$path = dirname(realpath(substr($unique[0]['url'], 1))).'/'.$token;
		mkdir($path);

		// unzip the file
		$this->unzip->allow(array('zip', 'json', 'png', 'gif', 'jpeg', 'jpg', 'md'));
		$extract = $this->unzip->extract(substr($unique[0]['url'], 1), $path, true);
		//var_dump($extract);

		// get the json out
		$json = json_decode(file_get_contents($path.'/input.json'),true);
		$return = array(
			'json' => $json,
			'title' => str_replace('_', ' ', $json['filename']),
			'tags' => $json['tags'],
			'components' => $json['components'],
			'dependencies' => $json['dependencies'],
			'file' => $json['file'],
			'description' => isset($json['description']) ? $json['description'][0]: '',
		);
		
		$user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : false;

		// prepare images - move to pylosimg and add to database
		$storepath = str_replace('system/', '', BASEPATH);
		$json['images'][] = array('thumbnail.jpg'=>'thumbnail');
		foreach ($json['images'] as $h) {
			foreach ($h as $i => $j) {
				$j = pathinfo($path.'/'.$i);
				$k = sha1($i.microtime());
				if ($i=='thumbnail.jpg') {
					rename($path.'/'.$i, $storepath."/store/pylosimg/".$k."-thumb.".$j['extension']);
					$return['thumbnail'] = "/store/pylosimg/$k-thumb.".$j['extension'];
					
				} else {
					rename($path.'/'.$i, $storepath."/store/pylosimg/$k.".$j['extension']);
					//var_dump(array($j, $i, $k)); //die;
					$return['images'][] = "/store/pylosimg/$k.".$j['extension'];
					$insert[] = array(
						'url' => "/store/pylosimg/$k.".$j['extension'],
						'timestamp' => time(),
						'tempkey' => $token,
						'type' => 'thumbnail',
						'user' => ($this->ion_auth->logged_in()) ? $user->id: '',
					);
				}
			}
		}
		
		// prepare zip - move to pylosfiles and add to database
		$i = $json['file'];
		$j = pathinfo($path.'/'.$i);
		$k = sha1($i.microtime());
		rename($path.'/'.$i, $storepath."/store/pylosfiles/test-$k.".$j['extension']);
		$return['images'][] = "/store/pylosfiles/test-$k.".$j['extension'];
		$insert[] = array(
			'url' => "/store/pylosfiles/test-$k.".$j['extension'],
			'timestamp' => time(),
			'tempkey' => $token,
			'type' => 'file',
			'user' => ($this->ion_auth->logged_in()) ? $user->id: '',
		);
		$query = $this->db->insert_batch("build_pylos_files",$insert);
		
		//remove the unzipped file directory
		$this->rrmdir($path);
		
		return $return;
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
			$config['allowed_types'] = 'zip|xlsx|xlsm|pptx';
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

	// first run initialize the block with images and files.
	public function block_firstrun($single)
	{

		$parent = $single[0];

		// get our thumbnail image
		$resources['images'] = array();
		$resources['files'] = array();
		$update_files = array();


		$files = $this->get_data2('pylos_files',false,array('tempkey'=>$parent['unique']));
		if ($files) {
			foreach ($files as $file) {
				if ($file['type'] == 'file') {
					$resources['files'][] = $file;
					$update_files[] = array(
						'id' => $file['id'],
						'parenttype' => 'pylos_block',
						'parentid' => $parent['id']
					);
				}
				if ($file['type'] == 'thumbnail') {
					$resources['images'][] = $file;
					$update_files[] = array(
						'id' => $file['id'],
						'parenttype' => 'pylos_block',
						'parentid' => $parent['id']
					);
				}
			}
		}
				
		gc_collect_cycles();
		
		
		
		$makethumb = (isset($parent['thumbnail']) && !empty($parent['thumbnail'])) ? false : true;
		
		if (isset($resources['images'][0]['url'])) {
			$path = '.'.$resources['images'][0]['url'];
			if (is_file($path)) { 
				$isfile = true;
			} else {
				$makethumb = false;
			}
		} else {
			$makethumb = false;
		}

		
		/* alternative resize script 
		// make thumbnail
		$storepath = str_replace('/system/', '', BASEPATH);
		$thumbnail = explode('.', $resources['images'][0]['url']);
		
		$update_block = array(
			'thumbnail'=>$thumbnail[0].'_thumb.'.$thumbnail[1],
			'file'=>$resources['files'][0]['url']
		);

		$src = $storepath.$resources['images'][0]['url'];
		$dest = $storepath.$thumbnail[0].'_thumb.'.$thumbnail[1];
		$width = 300;

		$this->make_thumb($src, $dest, $width);
		*/
		// make thumbnail
		
		if ($makethumb) {
			$config['image_library'] = 'gd2';
			$config['source_image']	= '.'.$resources['images'][0]['url'];
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['height'] = 500;
			$config['width'] = 500;
			$config['quality'] = 90;
	
					
			$this->load->library('image_lib', $config); 
			
			//print_r(array('peak'=>memory_get_peak_usage(),'usage'=>memory_get_usage()));
			
			$img = $this->image_lib->resize();
			
			if ( ! $img)
			{
			    echo $this->image_lib->display_errors();
			    die;
			}
			
			$thumbnail = explode('.', $resources['images'][0]['url']);
			
			$update_block['thumbnail'] = $thumbnail[0].'_thumb.'.$thumbnail[1];
		}

		if (is_file('.'.$resources['files'][0]['url'])) $update_block['file'] = $resources['files'][0]['url'];
		
		if (isset($update_block)) {
			$this->db->update_batch('build_pylos_files', $update_files, 'id'); 
		
			$query = $this->db->update('build_pylos_block', $update_block, array('id' => $parent['id']));
		//print_r(array('peak'=>memory_get_peak_usage(),'usage'=>memory_get_usage()));
			return $query;	
		} else {
			return true;
		}
	}
	// first run initialize a block and make a thumbnail.
	public function init_thumbnail($record,$type)
	{

		$parent = $record;

		// get our thumbnail image
		$resources['images'] = array();
		$update_files = array();


		$files = $this->get_data2('pylos_files',false,array('tempkey'=>$parent['unique'],'type'=>'thumbnail'));
		if ($files === false) { 
			return false;
		} else {
			foreach ($files as $file) {
				$resources['images'][] = $file;
				$update_files[] = array(
					'id' => $file['id'],
					'parenttype' => 'pylos_block',
					'parentid' => $parent['id']
				);
			}
		}
				
		gc_collect_cycles();
		
		
		$makethumb = (isset($parent['thumbnail']) && !empty($parent['thumbnail'])) ? false : true;
		/* alternative resize script 
		// make thumbnail
		$storepath = str_replace('/system/', '', BASEPATH);
		$thumbnail = explode('.', $resources['images'][0]['url']);
		
		$update_block = array(
			'thumbnail'=>$thumbnail[0].'_thumb.'.$thumbnail[1],
			'file'=>$resources['files'][0]['url']
		);

		$src = $storepath.$resources['images'][0]['url'];
		$dest = $storepath.$thumbnail[0].'_thumb.'.$thumbnail[1];
		$width = 300;

		$this->make_thumb($src, $dest, $width);
		*/
		// make thumbnail
		
		if ($makethumb) {
			$config['image_library'] = 'gd2';
			$config['source_image']	= '.'.$resources['images'][0]['url'];
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['height'] = 500;
			$config['width'] = 500;
			$config['quality'] = 90;
	
					
			$this->load->library('image_lib', $config); 
			
			//print_r(array('peak'=>memory_get_peak_usage(),'usage'=>memory_get_usage()));
			
			$img = $this->image_lib->resize();
			
			if ( ! $img)
			{
			    echo $this->image_lib->display_errors();
			    die;
			}
			
			$thumbnail = explode('.', $resources['images'][0]['url']);
			
			$update_block['thumbnail'] = $thumbnail[0].'_thumb.'.$thumbnail[1];
		}

		$query = $this->db->update("build_$type", $update_block, array('id' => $parent['id']));
		//print_r(array('peak'=>memory_get_peak_usage(),'usage'=>memory_get_usage()));
		return $query;	
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

	// handlebar parser
	public function handlebar_links($input,$link=true) {	
		if ($link === true) {
			return preg_replace_callback('/\{\{(.+?)\}\}/', array($this,'handlebar_links_helper'), $input);
		} else {
			return preg_replace_callback('/\{\{(.+?)\}\}/', array($this,'handlebar_title_helper'), $input);
		}
	}

	// explode with multiple delimiters
	public function multiexplode($delimiters,$string) {
		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);
		return  $launch;
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
		    'site' => 'Builder - ',
		    'footer' => "<br><br>Sent by Sean's website, builder."
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

	public function videoembedurl($url) {
	    //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
	    $finalUrl = '';
	    if(strpos($url, 'facebook.com/') !== false) {
	        //it is FB video
	        $finalUrl.='https://www.facebook.com/plugins/video.php?href='.rawurlencode($url).'&show_text=1&width=200';
	    }else if(strpos($url, 'vimeo.com/') !== false) {
	        //it is Vimeo video
	        $videoId = explode("vimeo.com/",$url)[1];
	        if(strpos($videoId, '&') !== false){
	            $videoId = explode("&",$videoId)[0];
	        }
	        $finalUrl.='https://player.vimeo.com/video/'.$videoId;
	    }else if(strpos($url, 'youtube.com/') !== false) {
	        //it is Youtube video
	        $videoId = explode("v=",$url)[1];
	        if(strpos($videoId, '&') !== false){
	            $videoId = explode("&",$videoId)[0];
	        }
	        $finalUrl.='https://www.youtube.com/embed/'.$videoId;
	    }else if(strpos($url, 'youtu.be/') !== false){
	        //it is Youtube video
	        $videoId = explode("youtu.be/",$url)[1];
	        if(strpos($videoId, '&') !== false){
	            $videoId = explode("&",$videoId)[0];
	        }
	        $finalUrl.='https://www.youtube.com/embed/'.$videoId;
	    }else{
	        //Enter valid video URL
	    }
	    return $finalUrl;
	}
	
	public function echovar($var, $debug=false) {
		if (isset($var) && !empty($var)) {
			echo $var;
		} else {
			if ($debug) var_dump($var);
		}
	}
	
	public function make_thumb($src, $dest, $desired_width) {
	
		ini_set('memory_limit','500M');

		gc_collect_cycles();
	
		/* read the source image */
		$ext = pathinfo($src, PATHINFO_EXTENSION);
		if($ext == "png"){
			$image = imagecreatefrompng($src);
		} else {
			$image = imagecreatefromjpeg($src);
		}
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		imagejpeg($virtual_image, $dest);
	}
	
	public function array_first(array $arr) {
		foreach($arr as $key => $unused) {
			return $key;
		}
		return NULL;
	}
}