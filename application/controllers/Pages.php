<?php defined('BASEPATH') OR exit('No direct script access allowed');


/* 
 * Pages Controller
 *
 * This script is the controller for the static pages on the site. Based on the CI tutorial.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Pages extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		//$this->load->library('ion_auth');
	}

	public function view($slug = 'home')
	{
	
		if ( ! file_exists(APPPATH.'/views/app/content/'.$slug.'.php'))
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
			$data['section'] = '';
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
				$this->load->view('app/pylos/templates/frontmatter-default', $data);
				$this->load->view("app/pages/$template", $data);
				$this->load->view('app/pylos/templates/footer', $data);
			} else {
				if ($template == 'rsvp') {
					$data['loadjs']['rsvp'] = true;
				}
				$this->load->view('app/builder/templates/header', $data);
				$this->load->view("app/pages/$template", $data);
				$this->load->view('app/builder/templates/footer', $data);
			}
		} else {
			$data['pagetitle'] = ucfirst($slug); // Capitalize the first letter
			$data['section'] = '';
			$this->load->view('app/builder/templates/header', $data);
			$this->load->view('app/content/'.$slug, $data);
			$this->load->view('app/builder/templates/footer', $data);
		}
	}
}
?>