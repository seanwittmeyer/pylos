<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Pylos Controller
 *
 * The pylos controller takes in URL requests and calls relevant models then diverts them to views in the site.
 *
 * Version 2.0 (2018.03.25.1340$2)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Ppt extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->config->load('pylos');
		$this->load->model('pylos_model');
		
	}

	// show a specific page for the home page
	public function index()
	{
		$data['pagetitle'] = 'PPT'; // Capitalize the first letter
		$data['contenttitle'] = 'Project Performance Team';
		$data['section'] = array('about', 'ppt');
		$data['filter'] = true;
		$data['fullwidth'] = true;
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-ppt', $data);
		$this->load->view('app/ppt/index', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	// show a page from the 
	public function view($slug = 'home')
	{
	
		if ( ! file_exists(APPPATH.'/views/app/ppt/'.$slug.'.php'))
		{
			// Whoops, we don't have a page for that!
			//show_404();
			$this->load->helper('file');
			$data = $this->shared->get_byslug('page',$slug);
			if (!isset($data['title'])) show_404();
			$data['related'] = $this->shared->get_related('page',$data['id']);
			if ($data['img'] != '') $data['img'] = unserialize($data['img']);
			if (!empty($data['payload'])) $data['payload'] = unserialize($data['payload']);
			$data['type'] = 'page';
			$template = (empty($data['template'])) ? 'default': $data['template'];
			//print_r($data);die;
			$data['pagetitle'] = $data['title'];
			$data['contenttitle'] = $data['title'];
			$data['section'] = array('about', 'ppt');
			if (strpos($template, 'pylos') !== false) {
				if ($template == 'pyloshome') {
					$this->load->model('pylos_model');
					$data['blocks'] = $this->pylos_model->get_data2('pylos_block',false);
					$data['guides'] = $this->pylos_model->get_data2('pylos_guides',false);
					$data['presentations'] = $this->pylos_model->get_data2('pylos_presentations',false);
					$data['filter'] = true;
				}
				$this->load->view('app/pylos/templates/header', $data);
				$data['fullwidth'] = true;
				$this->load->view('app/pylos/templates/frontmatter-ppt', $data);
				$this->load->view("app/ppt/$template", $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {
				$this->load->view('app/pylos/templates/header', $data);
				$this->load->view('app/pylos/templates/frontmatter-ppt', $data);
				$this->load->view("app/ppt/$template", $data);
				$this->load->view('app/pylos/templates/footer', $data);
			}
		} else {
			$data['pagetitle'] = ucfirst($slug); // Capitalize the first letter
			$data['section'] = array('about', 'ppt');
			$this->load->view('app/pylos/templates/header', $data);
			$this->load->view('app/pylos/templates/frontmatter-ppt', $data);
			$this->load->view('app/ppt/'.$slug, $data);
			$this->load->view('app/pylos/templates/footer', $data);
		}
	}

}
