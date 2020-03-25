<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * API Controller
 *
 * This is the controller that handles all of the API calls. It handles calls 
 * authenticated by key, session user, or the public..
 *
 * Version 1.4.5 (2017 09 17 1738)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Vr extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('vr_model');
	}

	public function index()
	{
		redirect('app/vr/tours', 'refresh');
	}

	public function tours()
	{
		$data['pagetitle'] = 'VR - Tours'; // Capitalize the first letter
		$data['section'] = 'apps';
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/vr/tours', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}

	public function assets()
	{
		$data['pagetitle'] = 'VR - Assets'; // Capitalize the first letter
		$data['section'] = 'apps';
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/vr/tours', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}

	public function view()
	{
		$data['pagetitle'] = 'VR - View'; // Capitalize the first letter
		$data['section'] = 'apps';
		
		//$tour = $this->vr_model->get_tour();
		$tour = array('moments'=> 2);
		// display content 
		if (count($tour['moments']) > 1) {
			$this->load->view('app/vr/pano_single', $data);
		} else if (count($tour['moments']) == 1) {
			$this->load->view('app/vr/pano_tour', $data);
		} else {
			$data['message'] = "No moments in this tour to show. You may want to add some.";
			$this->load->view('app/vr/uhoh', $data);
		}
	}

	public function create()
	{
		$data['pagetitle'] = 'Tours'; // Capitalize the first letter
		$data['section'] = 'apps';
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/vr/create', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}

	public function api($id=false)
	{
		if ($id===false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>"You forgot to specify a valid order ID (usually a number)."));
			die;
		}
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't claim orders unless you are logged in."));
			die;
		}
		//$result = $this->shared->update_status($id, 'complete');
		$this->output->set_status_header('200');
		print json_encode(array('result'=>'200','type'=>'success','message'=>"Order marked as delivered!"));

	}
}
