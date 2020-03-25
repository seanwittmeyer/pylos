<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Pylos Controller
 *
 * The pylos controller takes in URL requests and calls relevant models then diverts them to views in the site.
 *
 * Version 2.1.1 (2020.02.14.1028$2)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Pylos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->config->load('pylos');
		$this->load->model('pylos_model'); 
		
	}

	public function index()
	{
		$data['pagetitle'] = 'Pylos'; // Capitalize the first letter
		$data['contenttitle'] = 'Design Resources';
		$data['section'] = array('start', 'home');
		$data['heromenu'] = true;
		$data['filter'] = true;
		$data['fullwidth'] = true;
		$data['blocks'] = $this->pylos_model->get_data2('pylos_block',false);
		$data['guides'] = $this->pylos_model->get_data2('pylos_guides',false);
		$data['presentations'] = $this->pylos_model->get_data2('pylos_presentations',false);

		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-basic', $data);
		$this->load->view('app/pylos/index', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	public function hydra()
	{
		$data = array(
			'pagetitle' => 'Hydra Import', // Capitalize the first letter
			'contenttitle' => 'Hydrashare',
			'section' => array('apps', 'hydra'),
			'filter' => true;
		);
		$data['loadjs']['hydrashare'] = true;
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-default', $data);
		$this->load->view('app/pylos/hydra-list', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}
	public function admin()
	{
		$data['pagetitle'] = 'Platform Tools'; // Capitalize the first letter
		$data['contenttitle'] = 'Platform Tools';
		$data['section'] = array('admin', 'tools');
		$data['fullwidth'] = true;
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-default', $data);
		$this->load->view('app/pylos/admin-tools', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}
	public function weather()
	{
		$data['pagetitle'] = 'Energy Plus Weather Map'; // Capitalize the first letter
		$data['contenttitle'] = 'Energy Plus Weather Map';
		$data['section'] = array('apps', 'weather');
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-weathermap', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	public function charts()
	{
		$data['pagetitle'] = 'Charts'; // Capitalize the first letter
		$data['contenttitle'] = 'Charts';
		$data['section'] = array('apps', 'charts');
		$data['loadjs']['charts'] = true;
		//$this->load->view('app/pylos/templates/header-charts', $data);
		//$this->load->view('app/pylos/charts', $data);
		$this->load->view('app/pylos/charts-test', $data);
	}

	public function single()
	{
		$data['pagetitle'] = 'Hydra Import'; // Capitalize the first letter
		$data['contenttitle'] = 'Hydrashare Listing';
		$data['section'] = array('apps', 'hydra');
		$data['loadjs']['hydrashare'] = true;
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-carousel', $data);
		$this->load->view('app/pylos/hydra-single', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	public function strategies($collection=false, $slug=false)
	{
		$data['slug'] = ($slug === false) ? $collection : $slug;
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		if ($collection == 'create') {
			$data['pagetitle'] = 'Add a Strategy'; // Capitalize the first letter
			$data['contenttitle'] = 'Add a Strategy';
			$data['section'] = array('strategies', 'single');
			$data['tags'] = $this->pylos_model->get_tags(false,true);
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-basic', $data);
			$this->load->view('app/pylos/strategies-new', $data);
			$this->load->view('app/pylos/templates/footer', $data);
		} else {
			if ($collection === false) {
				showAllStrategies:
			
				$data['pagetitle'] = 'Strategies'; // Capitalize the first letter
				$data['contenttitle'] = 'Strategies';
				$data['section'] = array('strategies', 'all');
				$data['filter'] = true;
				$data['fullwidth'] = true;		
				$data['strategies'] = $this->pylos_model->get_data2('pylos_strategies',false);
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/strategies-list', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {
				// show our single strategy

				// see if the strategy exists
				$strategy = $this->pylos_model->get_data2('pylos_strategies',false,array('slug'=>$data['slug']));
				// if it does, return $single = true
				if ($strategy === false) goto showAllStrategies;
		
				if ($this->input->get('firstrun', TRUE) === 'true') {
					$data['firstrun'] = $this->pylos_model->init_thumbnail($strategy[0],'pylos_strategies');
					if ($data['firstrun']) $strategy[0] = $this->pylos_model->get_data2('pylos_strategies',false,array('slug'=>$data['slug']));
					redirect("pylos/strategies/{$data['slug']}", 'refresh');
				}
				$strategy = $strategy[0];
				
				// setup presentation data for views
				$data['strategy'] = $strategy;
				$data['images'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$strategy['id'],'parenttype'=>'pylos_strategies','type'=>'thumbnail'));
				$data['files'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$strategy['id'],'parenttype'=>'pylos_strategies','type'=>'strategy'));
		
				// get and process tags
				$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_strategies','parentid'=>$strategy['id']),true);
		
				$data['tags'] = array();
				$data['dependencies'] = array();
				$data['phases'] = array();
				$data['strategies'] = array();
				$data['projects'] = array();

				if ($tags) {
					foreach ($tags as $tag) {
						if ($tag['type'] == 'tag') $data['tags'][] = $tag['title'];
						if ($tag['type'] == 'project') $data['projects'][] = $tag['title'];
						if ($tag['type'] == 'phase') $data['phases'][] = $tag['title'];
						if ($tag['type'] == 'strategy') $data['strategies'][] = $tag['title'];
						if ($tag['type'] == 'dependency') $data['dependencies'][] = $tag['title'];
					}
				}


				// Get content tagged with this strategy
				$tags = $this->pylos_model->get_data2('pylos_tags',false,array('type'=>'strategy','title'=>urldecode($data['slug'])),true);
				if (count($tags) == 0) {
					$data['blocks'] = false;
					$data['guides'] = false;
					$data['presentations'] = false;
				} else {
					if (isset($tags) && !empty($tags)) {			
						foreach ($tags as $t) {
							switch ($t['parenttype']) {
								case "pylos_block":
									$data['blocks'][] = $this->pylos_model->get_data2('pylos_block',$t['parentid']);
									break;
								case "pylos_guides":
									$data['guides'][] = $this->pylos_model->get_data2('pylos_guides',$t['parentid']);
									break;
								case "pylos_presentations":
									$data['presentations'][] = $this->pylos_model->get_data2('pylos_presentations',$t['parentid']);
									break;
							}
						}
					} else {
						$data['blocks'] = false;
						$data['guides'] = false;
						$data['presentations'] = false;
					}
				}
				
				foreach (array('blocks','guides','presentations') as $t) if (!isset($data[$t])) $data[$t] = false;
				// prepare views
				$data['pagetitle'] = $data['strategy']['title']; // Capitalize the first letter
				$data['contenttitle'] = $data['strategy']['title'];
				$data['section'] = array('strategies', 'strategies');
				//$data['loadjs']['pylosblock'] = true;
				$data['fullwidth'] = true;		
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-basic', $data);
				$this->load->view('app/pylos/strategies-single', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			}

		}
	
	}
	public function taxonomy($slug=false,$type='theme')
	{
		$data['slug'] = urldecode($slug);
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		if ($slug == 'create') {
			$data['pagetitle'] = 'Add a '.ucfirst($type); // Capitalize the first letter
			$data['contenttitle'] = 'Add a '.ucfirst($type);
			$data['section'] = array('strategies', 'themes');
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-basic', $data);
			$this->load->view('app/pylos/taxonomy-new', $data);
			$this->load->view('app/pylos/templates/footer', $data);
		} else {
			if ($slug === false or $slug === 'all') {
				showAllTax:
				// looks like we are showing everything
				$data['pagetitle'] = 'Themes'; // Capitalize the first letter
				$data['contenttitle'] = 'Themes';
				$data['section'] = array('strategies', 'themes');
				$data['filter'] = true;
				$data['fullwidth'] = true;		
				$data['taxonomies'] = $this->pylos_model->get_data2('pylos_taxonomy',false,array('type'=>$type));
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/taxonomy-list', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {
				// show our single theme

				// see if the theme exists
				$taxonomy = $this->pylos_model->get_data2('pylos_taxonomy',false,array('slug'=>$data['slug']));
				// if it does, return $single = true
				if ($taxonomy === false) goto showAllTax;
		
				if ($this->input->get('firstrun', TRUE) === 'true') {
					$data['firstrun'] = $this->pylos_model->init_thumbnail($taxonomy[0],'pylos_taxonomy');
					if ($data['firstrun']) $taxonomy[0] = $this->pylos_model->get_data2('pylos_taxonomy',false,array('slug'=>$data['slug']));
					redirect("pylos/taxonomy/{$data['slug']}", 'refresh');
				}
				
				// setup presentation data for views
				$data['taxonomy'] = $taxonomy[0];
		
				// prepare views
				$data['pagetitle'] = $data['taxonomy']['title']; // Capitalize the first letter
				$data['contenttitle'] = $data['taxonomy']['title'];
				$data['section'] = array('strategies', 'themes');
				//$data['loadjs']['pylosblock'] = true;
				$data['fullwidth'] = true;		
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-basic', $data);
				$this->load->view('app/pylos/taxonomy-single', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			}

		}
	
	}

	public function github()
	{
		$this->pylos_model->github_blocks();
	}

	public function blocks($slug=false, $tag=false)
	{
		$data['slug'] = $slug;
		$data['tag'] = $tag;
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		$data['section'] = array('library', 'blocks');
		if ($slug == 'create') {
			// Let's add a block
			$data['pagetitle'] = 'Add a block'; // Capitalize the first letter
			$data['contenttitle'] = 'Add a Block';
			$data['fullwidth'] = true;		
			$data['tags'] = $this->pylos_model->get_tags();
			$data['strategies'] = $this->pylos_model->get_data2('pylos_strategies',false);
			if ($tag == 'code') $data['loadjs']['codeeditor'] = true;
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			if ($tag == 'code') {
				$this->load->view('app/pylos/blocks-new-code', $data);
			} else {
				$this->load->view('app/pylos/blocks-new', $data);
			}
			$this->load->view('app/pylos/templates/footer', $data);
			
		} elseif ($slug == 'tag' || $slug == 'dependency' || $slug == 'phase') {
			if ($tag === false) goto showAllBlocks;
			// looks like we are showing a tag or dependency
			$data['pagetitle'] = urldecode($tag); // Capitalize the first letter
			$data['contenttitle'] = "$slug &rarr; ".urldecode($tag);
			$data['filter'] = true;
			$data['fullwidth'] = true;		
			$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_block','type'=>$slug,'title'=>urldecode($tag)),true);

			$data['tags'] = array();
			$data['dependencies'] = array();
			$data['phases'] = array();
			$data['strategies'] = array();
			$data['projects'] = array();

			if ($tags) {
				foreach ($tags as $tag) {
					if ($tag['type'] == 'tag') $data['tags'][] = $tag['title'];
					if ($tag['type'] == 'project') $data['projects'][] = $tag['title'];
					if ($tag['type'] == 'phase') $data['phases'][] = $tag['title'];
					if ($tag['type'] == 'strategy') $data['strategies'][] = $tag['title'];
					if ($tag['type'] == 'dependency') $data['dependencies'][] = $tag['title'];
				}
			}
			
			if ($slug == 'dependency') {
				$data['dependency'] = $this->pylos_model->get_data2('pylos_taxonomy',false,array('parenttype'=>'pylos_block','slug'=>urldecode($tag)));
				$data['dependency'] = $data['dependency'][0];
			} else {
				$data['dependency'] = false;
			}
			$data['fullwidth'] = true;		
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/blocks-list', $data);
			$this->load->view('app/pylos/templates/footer', $data);

		} elseif ($slug == 'import') {
			if ($tag === false) goto showAllBlocks;
			$this->load->helper('file');
			// looks like we are showing a tag or dependency
			$data['pagetitle'] = 'Import your Block'; // Capitalize the first letter
			$data['contenttitle'] = "Finish your Pylos import";
			$data['fullwidth'] = true;		
			$data['block'] = $this->pylos_model->import($tag);
			$data['newunique'] = $tag;
			$data['tags'] = $this->pylos_model->get_tags();
			$data['strategies'] = $this->pylos_model->get_data2('pylos_strategies',false);

			$data['slug'] = '';
			
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/blocks-import', $data);
			$this->load->view('app/pylos/templates/footer', $data);

		} else {
			// Not adding a block, let's find a block or list them all
			if ($slug === false) {
				showAllBlocks:
				// looks like we are showing everything
				$data['pagetitle'] = 'Blocks'; // Capitalize the first letter
				$data['contenttitle'] = 'Blocks';
				$data['slug'] = '';
				$data['filter'] = true;
				$data['fullwidth'] = true;		
				$data['dependency'] = false;
				$data['blocks'] = $this->pylos_model->get_data2('pylos_block',false);
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/blocks-list', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {
				// see if the block exists
				$single = $this->pylos_model->get_data2('pylos_block',false,array('slug'=>$slug));
				// if it does, return $single = true
				if ($single === false) goto showAllBlocks;
		
				if ($this->input->get('firstrun', TRUE) === 'true') {
					$data['firstrun'] = $this->pylos_model->block_firstrun($single);
					redirect('pylos/blocks/'.$slug, 'refresh');
					//if ($data['firstrun']) $single = $this->pylos_model->get_data2('pylos_block',false,array('slug'=>$slug));
				}
		
				// see if the block has revisions
				$revisions = $this->pylos_model->get_data2('pylos_revisions',false,array('parenttype'=>'pylos_block','parentid'=>$single[0]['id']));
				// if it does, return $single = true
				$data['revisions'] = ($revisions === false) ? array() : $revisions;
		
				// setup block data and revisions for views
				$data['block'] = $single[0];
				$data['images'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$single[0]['id'],'parenttype'=>'pylos_block','type'=>'thumbnail'));
				$data['files'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$single[0]['id'],'parenttype'=>'pylos_block','type'=>'file'));
		
				// get and process tags
				$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_block','parentid'=>$data['block']['id']),true);

				$data['tags'] = array();
				$data['dependencies'] = array();
				$data['phases'] = array();
				$data['projects'] = array();
				$data['strategies'] = array();
				$data['components'] = array();

				if ($tags) {
					foreach ($tags as $tag) {
						if ($tag['type'] == 'tag') $data['tags'][] = $tag['title'];
						if ($tag['type'] == 'project') $data['projects'][] = $tag['title'];
						if ($tag['type'] == 'phase') $data['phases'][] = $tag['title'];
						if ($tag['type'] == 'strategy') $data['strategies'][] = $tag['title'];
						if ($tag['type'] == 'dependency') $data['dependencies'][] = $tag['title'];
						if ($tag['type'] == 'component') $data['components'][] = $tag['title'];
					}
				}

				if ($data['block']['type'] == 'maxscript' || $data['block']['type'] == 'python' || $data['block']['type'] == 'script') {
					$data['loadjs']['codeeditor'] = true;
				}

				// prepare views
				$data['pagetitle'] = $data['block']['title']; // Capitalize the first letter
				$data['contenttitle'] = $data['block']['title'];
				$data['loadjs']['pylosblock'] = true;
				$data['loadjs']['panzoom'] = true;
				$this->load->view('app/pylos/templates/header', $data);
			    switch ($data['block']['type']) {
					case ('excel'): $this->load->view('app/pylos/templates/frontmatter-office', $data); break;
					default: $this->load->view('app/pylos/templates/frontmatter-block', $data); break;
				}
				$this->load->view('app/pylos/blocks-single', $data);
				$this->load->view('app/pylos/templates/footer', $data);

			}
		}
	}

	public function guides($slug=false, $tag=false)
	{
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		$data['section'] = 'guides';
		$data['section'] = array('library', 'guides');

		if ($slug == 'create') {
			// Let's add a block
			$data['pagetitle'] = 'Add a Guide'; // Capitalize the first letter
			$data['contenttitle'] = 'Add a Guide';
			$data['fullwidth'] = true;		
			$data['tags'] = $this->pylos_model->get_tags();
			$data['strategies'] = $this->pylos_model->get_data2('pylos_strategies',false);
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/guides-new', $data);
			$this->load->view('app/pylos/templates/footer', $data);
			
		} elseif ($slug == 'tag' || $slug == 'phase') {
			if ($tag === false) goto showAllGuides;
			// looks like we are showing a tag or dependency
			$data['pagetitle'] = urldecode($tag); // Capitalize the first letter
			$data['contenttitle'] = "$slug &rarr; ".urldecode($tag);
			$data['slug'] = $slug;
			$data['filter'] = true;
			$data['fullwidth'] = true;		
			$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_guides','type'=>$slug,'title'=>urldecode($tag)),true);
			if (count($tags) == 0) {
				$data['guides'] = false;
			} else {
				if (isset($tags) && !empty($tags)) {			
					foreach ($tags as $t) {
						$data['guides'][] = $this->pylos_model->get_data2('pylos_guides',$t['parentid']);
					}
				} else {
					$data['guides'] = false;
				}
			}
			
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/guides-list', $data);
			$this->load->view('app/pylos/templates/footer', $data);

		} else {
			// Not adding a guide, let's find a guide or list them all
			if ($slug === false) {
				showAllGuides:
				// looks like we are showing everything
				$data['pagetitle'] = 'Guides'; // Capitalize the first letter
				$data['contenttitle'] = 'Guides';
				$data['filter'] = true;
				$data['fullwidth'] = true;		
				$data['guides'] = $this->pylos_model->get_data2('pylos_guides',false);
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/guides-list', $data);
				$this->load->view('app/pylos/templates/footer', $data);

			} else {
				// show our single guide

				// see if the guide exists
				$guide = $this->pylos_model->get_data2('pylos_guides',false,array('slug'=>$slug));
				// if it does, return $single = true
				if ($guide === false) goto showAllGuides;
		
				if ($this->input->get('firstrun', TRUE) === 'true') {
					$data['firstrun'] = $this->pylos_model->init_thumbnail($guide[0],'pylos_guides');
					if ($data['firstrun']) $guide[0] = $this->pylos_model->get_data2('pylos_guides',false,array('slug'=>$slug));
					redirect("pylos/guides/$slug", 'refresh');
				}
				$guide = $guide[0];
				
				// get steps and any associated images
				$steps = $this->pylos_model->get_data2('pylos_steps',false,array('parenttype'=>'pylos_guides','parentid'=>$guide['id']),array('order','asc'));
				// if it does, return $single = true
				if ($steps === false) {
					$data['steps'] = array();
				} else {
					$data['steps'] = array();
					foreach ($steps as $step) {
						$step['images'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$step['id'],'parenttype'=>'pylos_steps','type'=>'thumbnail'));
						$step['files'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$step['id'],'parenttype'=>'pylos_steps','type'=>'file'));
						$data['steps'][] = $step;
					}
				}

				// setup guide data for views
				$data['guide'] = $guide;
				$data['images'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$guide['id'],'parenttype'=>'pylos_guides','type'=>'thumbnail'));
				$data['files'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$guide['id'],'parenttype'=>'pylos_guides','type'=>'guide'));

				// get and process tags
				$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_guides','parentid'=>$guide['id']),true);

				$data['tags'] = array();
				$data['dependencies'] = array();
				$data['phases'] = array();
				$data['strategies'] = array();
				$data['projects'] = array();

				if ($tags) {
					foreach ($tags as $tag) {
						if ($tag['type'] == 'tag') $data['tags'][] = $tag['title'];
						if ($tag['type'] == 'project') $data['projects'][] = $tag['title'];
						if ($tag['type'] == 'phase') $data['phases'][] = $tag['title'];
						if ($tag['type'] == 'strategy') $data['strategies'][] = $tag['title'];
						if ($tag['type'] == 'dependency') $data['dependencies'][] = $tag['title'];
					}
				}

				// prepare views
				$data['pagetitle'] = $data['guide']['title']; // Capitalize the first letter
				$data['contenttitle'] = $data['guide']['title'];
				$data['loadjs']['pylosblock'] = true;
				$data['loadjs']['panzoom'] = true;
				$data['fullwidth'] = true;		
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/guides-single', $data);
				$this->load->view('app/pylos/templates/footer', $data);

			}
		}
	}

	public function presentations($slug=false, $tag=false)
	{
		$data['section'] = 'presentations';
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		$data['section'] = array('library', 'presentations');
		if ($slug == 'test') {
			$data['fullwidth'] = true;		
			$data['presentation'] = array(
				'id' => 44,
				'slug' => 'toomuchglare',
				'excerpt' => 'How much is too much glare in the space?',
				'description' => "Finding the ASE for a space helps you understand how much glare is in the space. That's it, you can use this to make presentation graphics or you can use the data for parametrically driven workflows.",
				'timestamp' => time()-5030,
				'author' => 'Jacob Dunn',
				'type' => 'Lunch and Learn',
				'user' => 43,
				'private' => 1,
				'image' => '/store/pylosimg/3d9a5f1db374ecb2ebf79853292a06fb.jpg',
				'thumb' => '/store/pylosimg/3d9a5f1db374ecb2ebf79853292a06fb.jpg',
				'video' => 'https://vimeo.com/289029793',
				'source' => 'https://seanwittmeyer.com/',
				'title' => "Daylight Basics: Find the ASE and sDA for a Space"
			);
			$data['images'] = array(array(
				'url' => 'store/pylosimg/3d9a5f1db374ecb2ebf79853292a06fb.jpg'
			));
			$data['files'] = array(array(
				'url' => 'store/temporary/2018-02-15 10Timber.pdf'
			));
			$data['dependencies'] = array();
			$data['tags'] = array();
			$data['pagetitle'] = $data['presentation']['title']; // Capitalize the first letter
			$data['contenttitle'] = $data['presentation']['title'];
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-presentations', $data);
			$this->load->view('app/pylos/presentations-single', $data);
			$this->load->view('app/pylos/templates/footer', $data);
		} elseif ($slug == 'create') {
			// Let's add a block
			$data['pagetitle'] = 'Add a Presentation'; // Capitalize the first letter
			$data['contenttitle'] = 'Add a Presentation';
			$data['tags'] = $this->pylos_model->get_tags();
			$data['strategies'] = $this->pylos_model->get_data2('pylos_strategies',false);
			$data['fullwidth'] = true;		
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/presentations-new', $data);
			$this->load->view('app/pylos/templates/footer', $data);
			
		} elseif ($slug == 'tag' || $slug == 'phase') {
			if ($tag === false) goto showAllPresentations;
			// looks like we are showing a tag or dependency
			$data['pagetitle'] = urldecode($tag); // Capitalize the first letter
			$data['contenttitle'] = "$slug &rarr; ".urldecode($tag);
			$data['slug'] = $slug;
			$data['filter'] = true;
			$data['fullwidth'] = true;		
			$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_presentations','type'=>$slug,'title'=>urldecode($tag)),true);
			if (count($tags) == 0) {
				$data['presentations'] = false;
			} else {
				if (isset($tags) && !empty($tags)) {			
					foreach ($tags as $t) {
						$data['presentations'][] = $this->pylos_model->get_data2('pylos_presentations',$t['parentid']);
					}
				} else {
					$data['presentations'] = false;
				}
			}
			
			$this->load->view('pylos/templates/header', $data);
			$this->load->view('pylos/templates/frontmatter-default', $data);
			$this->load->view('pylos/presentations-list', $data);
			$this->load->view('pylos/templates/footer', $data);

		} else {
			// Not adding a presentation, let's find a presentation or list them all
			if ($slug === false) {
				showAllPresentations:
				// looks like we are showing everything
				$data['pagetitle'] = 'Presentations'; // Capitalize the first letter
				$data['contenttitle'] = 'Presentations';
				$data['filter'] = true;
				$data['fullwidth'] = true;		
				$data['presentations'] = $this->pylos_model->get_data2('pylos_presentations',false);
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/presentations-list', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {
				// show our single presentation

				// see if the presentation exists
				$presentation = $this->pylos_model->get_data2('pylos_presentations',false,array('slug'=>$slug));
				// if it does, return $single = true
				if ($presentation === false) goto showAllPresentations;
		
				if ($this->input->get('firstrun', TRUE) === 'true') {
					$data['firstrun'] = $this->pylos_model->init_thumbnail($presentation[0],'pylos_presentations');
					if ($data['firstrun']) $presentation[0] = $this->pylos_model->get_data2('pylos_presentations',false,array('slug'=>$slug));
					redirect("pylos/presentations/$slug", 'refresh');
				}
				$presentation = $presentation[0];
				
				// setup presentation data for views
				$data['presentation'] = $presentation;
				$data['images'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$presentation['id'],'parenttype'=>'pylos_presentations','type'=>'thumbnail'));
				$data['files'] = $this->pylos_model->get_data2('pylos_files',false,array('parentid'=>$presentation['id'],'parenttype'=>'pylos_presentations','type'=>'presentation'));
		
				// get and process tags
				$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_presentations','parentid'=>$presentation['id']),true);
		
				$data['tags'] = array();
				$data['dependencies'] = array();
				$data['phases'] = array();
				$data['strategies'] = array();
				$data['projects'] = array();

				if ($tags) {
					foreach ($tags as $tag) {
						if ($tag['type'] == 'tag') $data['tags'][] = $tag['title'];
						if ($tag['type'] == 'project') $data['projects'][] = $tag['title'];
						if ($tag['type'] == 'phase') $data['phases'][] = $tag['title'];
						if ($tag['type'] == 'strategy') $data['strategies'][] = $tag['title'];
						if ($tag['type'] == 'dependency') $data['dependencies'][] = $tag['title'];
					}
				}
		
				// prepare views
				$data['pagetitle'] = $data['presentation']['title']; // Capitalize the first letter
				$data['contenttitle'] = $data['presentation']['title'];
				$data['loadjs']['pylosblock'] = true;
				$data['fullwidth'] = true;		
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-presentations', $data);
				$this->load->view('app/pylos/presentations-single', $data);
				$this->load->view('app/pylos/templates/footer', $data);

			}
		}
	}

	public function phases($slug = 'all') {
		$data['fullwidth'] = true;		
		$data['section'] = 'phases';
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		$data['filter'] = true;
		$data['section'] = array('strategies', 'phases');
		$phases = $this->pylos_model->get_data2('pylos_taxonomy',false,array('type'=>'phase'),array('order','asc'));

		$data['phases'] = array();
		foreach ($phases as $phase) {
			$data['phases'][$phase['slug']] = $phase;
		}
		if (isset($data['phases'][urldecode($slug)])) {
			// Showing a single phase

			// Get resources tagged with this phase
			$resources = $this->pylos_model->get_data2('pylos_tags',false,array('type'=>'phase','title'=>$slug),true);
			if ($resources === false) {
				$data['blocks'] = false;
				$data['guides'] = false;
				$data['presentations'] = false;
			} else {
				$data['blocks'] = array();
				$data['guides'] = array();
				$data['presentations'] = array();
				foreach ($resources as $t) {
					switch ($t['parenttype']) {
						case "pylos_block":
							$data['blocks'][] = $this->pylos_model->get_data2('pylos_block',$t['parentid']);
							break;
						case "pylos_guides":
							$data['guides'][] = $this->pylos_model->get_data2('pylos_guides',$t['parentid']);
							break;
						case "pylos_presentations":
							$data['presentations'][] = $this->pylos_model->get_data2('pylos_presentations',$t['parentid']);
							break;
					}
				}
			}
			//print_r($resources);die;
			// list of themes
			$_themes = $this->pylos_model->get_data2('pylos_taxonomy',false,array('type'=>'theme'),true);
			$themes = array();
			foreach ($_themes as $theme) $themes[$theme['slug']] = $theme;
			$data['allthemes'] = $themes;
			
			// $themes - all themes by slug
			
			$strategies = $this->pylos_model->get_data2('pylos_strategies',false,false,true);
			$data['strategies'] = array();
			foreach ($strategies as $strategy) $data['strategies'][$strategy['id']] = $strategy;
			
			// $data['strategies'] - all strategies by id
			
			$ptags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_strategies','type'=>'phase','title'=>$slug),true);
			$data['tags'] = array();
			if ($ptags) foreach ($ptags as $tag) $data['tags'][$tag['parentid']] = $tag['parentid'];
			
			// $data['tags'] - this phase strategies by id
			
			$ttags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_strategies','type'=>'theme'),true);
			$data['themes'] = array();
			//foreach ($ttags as $tag) $data['themes'][$tag['parentid']] = $tag['title'];
/*	*/		foreach ($ttags as $tag) {
				if (in_array($tag['parentid'], $data['tags'])) {
					
					if (empty($data['themes'][$tag['title']])) $data['themes'][$tag['title']] = $themes[$tag['title']];
					$data['themes'][$tag['title']]['strategies'][] = $data['strategies'][$tag['parentid']];
				} 
			}
/* */			
			// $data['themes'] - this phase strategies by theme

			$data['phase'] = $data['phases'][urldecode($slug)];
			$data['pagetitle'] = $data['phase']['title']; // Capitalize the first letter
			$data['contenttitle'] = "Phase &rarr; ".$data['phase']['title'];
			$data['slug'] = $slug;
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-basic', $data);
			$template = 'single';

		} else {
			// Show all phases

			foreach (array('blocks','guides','presentations') as $t) if (!isset($data[$t])) $data[$t] = false;

			$data['pagetitle'] = 'Phases';
			$data['contenttitle'] = 'Phases';
			$data['slug'] = 'Phase';
			$data['fullwidth'] = true;		
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$template = 'list';
		}

		$this->load->view("app/pylos/phases-$template", $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	public function tools($slug=false, $tag=false)
	{
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
		$data['section'] = array('tools', 'tools');
		if ($slug == 'create') {
			// Let's add a block
			$data['pagetitle'] = 'Add a Tool'; // Capitalize the first letter
			$data['contenttitle'] = 'Add a Tool';
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/tools-new', $data);
			$this->load->view('app/pylos/templates/footer', $data);
			
		} else {
			// Not adding a presentation, let's find a presentation or list them all
			if ($slug === false) {
				showAllPresentations:
				// looks like we are showing everything
				$data['pagetitle'] = 'Tools'; // Capitalize the first letter
				$data['contenttitle'] = 'Tools';
				$data['filter'] = true;
				$data['fullwidth'] = true;		
				$data['tools'] = $this->pylos_model->get_data2('pylos_taxonomy',false);
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view('app/pylos/tools-list', $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {

				// this will happen maybe. I am relying on the tags pages for dependencies for now until we find a better way to handle tools.


			}
		}
	}

	// Sudo-class invoked in routes for tags, phases, and dependencies. 
	// This lists all tagged content or a list of tags if unset.
	public function meta($slug='tag',$tag=false)
	{
		$data['section'] = array('library', 'meta');
		if ($tag === false || strtolower($tag) === 'all') {
			// showing all tags
			$data['pagetitle'] = ''; // Capitalize the first letter
			if (strtolower($slug) == 'project') {
				$data['contenttitle'] = 'Projects';
			} elseif (strtolower($slug) == 'dependency') {
				$data['contenttitle'] = 'All Dependencies';
			} else {
				$data['contenttitle'] = 'All '.ucfirst($slug).'s';
			}
			$data['slug'] = (strtolower($slug) == 'dependency') ? 'dependencies' : strtolower($slug).'s';
			$data['filter'] = true;
			$data['fullwidth'] = true;		
			$data['dependency'] = false;
			$_tags = $this->pylos_model->get_data2('pylos_tags',false,array('type'=>$slug),true);
			$data['tags'] = array();
			foreach ($_tags as $t) {
				$_t = urldecode(strtolower($t['title']));
				if (isset($data['tags'][$_t])) {
					// if we already saw this tag, increase it's count
					$data['tags'][$_t][1]++;
				} else {
					// add to our array and set count to 1
					$data['tags'][$_t] = array($_t,1);
				}
			}
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/tags-list', $data);
			$this->load->view('app/pylos/templates/footer', $data);
		} else {
			// looks like we are showing a tag or dependency
			$data['pagetitle'] = urldecode($tag); // Capitalize the first letter
			$data['contenttitle'] = "$slug &rarr; ".urldecode($tag);
			$data['filter'] = true;
			$data['slug'] = $slug;
			$data['tag'] = $tag;
			$data['fullwidth'] = true;		
			$tags = $this->pylos_model->get_data2('pylos_tags',false,array('type'=>$slug,'title'=>urldecode($tag)),true);
			if (count($tags) == 0) {
				$data['blocks'] = false;
				$data['guides'] = false;
				$data['presentations'] = false;
			} else {
				if (isset($tags) && !empty($tags)) {			
					foreach ($tags as $t) {
						switch ($t['parenttype']) {
							case "pylos_block":
								$data['blocks'][] = $this->pylos_model->get_data2('pylos_block',$t['parentid']);
								break;
							case "pylos_guides":
								$data['guides'][] = $this->pylos_model->get_data2('pylos_guides',$t['parentid']);
								break;
							case "pylos_presentations":
								$data['presentations'][] = $this->pylos_model->get_data2('pylos_presentations',$t['parentid']);
								break;
						}
					}
				} else {
					$data['blocks'] = false;
					$data['guides'] = false;
					$data['presentations'] = false;
				}
			}
			
			foreach (array('blocks','guides','presentations') as $t) if (!isset($data[$t])) $data[$t] = false;
			
			if ($slug == 'dependency') {
				$data['dependency'] = $this->pylos_model->get_data2('pylos_taxonomy',false,array('slug'=>urldecode($tag)));
				$data['dependency'] = $data['dependency'][0];
			} else {
				$data['dependency'] = false;
			}
			$data['fullwidth'] = true;		
			$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-default', $data);
			$this->load->view('app/pylos/combined-list', $data);
			$this->load->view('app/pylos/templates/footer', $data);
		}
	}

	public function tag_old($tag=false, $slug="tag")
	{
		$data['anon'] = ($this->ion_auth->logged_in()) ? false:true;

		$data['slug'] = $slug;
		$data['tag'] = $tag;
		if ($tag === false || empty($tag)) goto showAllTags;
		$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_block','type'=>$slug,'title'=>urldecode($tag)),true);
		if (count($tags) == 0) goto showAllTags;
		
		// looks like we are showing a tag or dependency
		$tags = $this->pylos_model->get_data2('pylos_tags',false,array('parenttype'=>'pylos_block','type'=>$slug,'title'=>urldecode($tag)),true);
		if (count($tags) == 0) {
			$data['blocks'] = false;
		} else {
			if (isset($tags) && !empty($tags)) {
				foreach ($tags as $t) {
					$data['blocks'][] = $this->pylos_model->get_data2('pylos_block',$t['parentid']);
				}
			} else {
				$data['blocks'] = false;
			}
		}
		
		$data['pagetitle'] = urldecode($tag); // Capitalize the first letter
		$data['contenttitle'] = "$slug &rarr; ".urldecode($tag);
		$data['section'] = 'pylos';
		$data['dependency'] = false;

		showAllTags:

		$data['filter'] = true;
		$data['fullwidth'] = true;		
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-default', $data);
		$this->load->view('app/pylos/blocks-list', $data);
		$this->load->view('app/pylos/templates/footer', $data);



	
	}

	public function test()
	{
		$this->load->view('app/pages/test');
	}

	public function api($method=null, $arg=null, $arg2=null, $arg3=null, $arg4=null)
	{
		// authenticated?
		if (!$this->ion_auth->logged_in() && $arg !== 'wizard') {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"Please sign in to make changes."));
			die;
		}
		// create method for blocks, templates, and vr views
		if ($method == 'create') {
			$success = false;
			// create a new block
			if ($arg == 'block') {
				$result = $this->pylos_model->create('pylos_block');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new guide
			elseif ($arg == 'guides') {
				$result = $this->pylos_model->create('pylos_guides');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new presentation
			elseif ($arg == 'presentations') {
				$result = $this->pylos_model->create('pylos_presentations');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new strategy
			elseif ($arg == 'taxonomy') {
				$result = $this->pylos_model->create('pylos_taxonomy');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new strategy
			elseif ($arg == 'strategies') {
				$result = $this->pylos_model->create('pylos_strategies');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new guide
			elseif ($arg == 'step') {
				$result = $this->pylos_model->create('pylos_steps');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new dependency
			elseif ($arg == 'dependency') {
				$result = $this->pylos_model->create('pylos_taxonomy');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new glossary
			elseif ($arg == 'terms') {
				$result = $this->pylos_model->create('pylos_terms');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new vr experience
			elseif ($arg == 'vrexperience') {
				$result = $this->pylos_model->create('pylos_vrexperience');
				if ($result['code'] == 200) $success = true;
			}
			// create block revision
			elseif ($arg == 'revision') {
				$result = $this->pylos_model->create('pylos_revisions');
				if ($result['code'] == 200) $success = true;
			}
			$result = (isset($result)) ? $result : array('message'=>'No error set');
			if ($success) {
				$this->output->set_status_header('200');
				print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
			} else {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			}
		}
		if ($method == 'remove') {
			$success = false;
			// authenticated?
			if (!$this->ion_auth->logged_in() && $arg !== 'wizard') {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>"I'm sorry but you can't create content unless you are signed in."));
				die;
			}
			// remove a dataset
			$result = $this->pylos_model->remove($arg,$arg2);
			if (is_array($result)) {
				if ($result['error'] == true) {
					$success = false;
				} else {
					$success = true;

				}
			} elseif ($result === true) { 
				$success = true;
			}
			if ($success) {
				switch ($arg) {
					case 'pylos_block': redirect('pylos/blocks?remove=true', 'refresh'); break;
					case 'pylos_guides': redirect('pylos/guides?remove=true', 'refresh'); break;
					case 'pylos_steps': redirect($_SERVER['HTTP_REFERER'], 'refresh'); break;
					case 'pylos_files': redirect($_SERVER['HTTP_REFERER'], 'refresh'); break;
					case 'pylos_presentations': redirect('pylos/presentations?remove=true', 'refresh'); break;
					default: 
						$this->output->set_status_header('200');
						print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
				}
			} else {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			}
		}
		if ($method == 'purgefiles') {
			$success = false;
			// authenticated?
			if (!$this->ion_auth->logged_in()) {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't run administrative tasks unless you are signed in."));
				die;
			}
			// update a dataset
			$debug = ($arg == 'debug') ? true : false;
			$result = $this->pylos_model->purgefiles($debug);
			if (is_array($result)) {
				if ($result['error'] == true) {
					$success = false;
				} else {
					$success = true;

				}
			} elseif ($result === true) { 
				$success = true;
			}
			if ($success) {
				redirect('pylos/admin?purgefiles=true', 'refresh'); 
			} else {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			}
		}

		if ($method == 'update') {
			$success = false;
			// authenticated?
			if (!$this->ion_auth->logged_in() && $arg !== 'wizard') {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>"I'm sorry but you can't modify content unless you are signed in."));
				die;
			}
			// update a dataset
			$result = $this->pylos_model->update($arg, $arg2, $arg3, $arg4);
			if (is_array($result)) {
				if ($result['error'] == true) {
					$success = false;
				} else {
					$success = true;

				}
			} elseif ($result === true) { 
				$success = true;
			}
			if ($success) {
				switch ($arg) {
					//case 'pylos_block': redirect('pylos/blocks?update=true', 'refresh'); break;
					//case 'pylos_guides': redirect('pylos/guides?update=true', 'refresh'); break;
					default: 
						$this->output->set_status_header('200');
						print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
				}
			} else {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			}
		}

		// upload image - image functions should handle success and error responses
		if ($method == 'uploadimage') {
			$return = false; // not using this here.
			// store anonymous image
			if ($arg == null) {
				$this->pylos_model->storeimage($return);
			}
			// store associated image
			else {
				$this->pylos_model->storeimage($return, $arg, $arg2, $arg3, $arg4);		
			}
		}
	}
}
