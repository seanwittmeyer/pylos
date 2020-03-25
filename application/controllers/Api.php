<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * CAS API Controller
 *
 * This is the controller that handles all of the API calls. It handles calls 
 * authenticated by key, session user, or the public..
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('api_model');
	}

	public function index()
	{
		redirect('help', 'refresh');
	}

	public function update($type=false,$id=false,$file=false)
	{
		if ($type===false || $id===false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>"Blast, don't forget to specify the type and ID. Do not pass go."));
			die;
		}
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't update ".$type."s unless you are logged in."));
			die;
		}
		$result = $this->shared->update($type,$id,$file);
		if ($result['error'] === true) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			//print_r($post = $this->input->post('payload'));
			die;
		}
		$this->output->set_status_header('200');
		print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
		
	}

	public function remove($type=false,$id=false,$refresh=false)
	{
		if ($type===false || $id===false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>"You may want to specify the type and ID. Do not pass go. Do not collect $200."));
			die;
		}
		if (!$this->ion_auth->is_admin()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't delete ".$type."s unless you are an admin and logged in."));
			die;
		}
		$result = $this->shared->remove($type,$id);
		if ($result['error'] === true) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			//print_r($post = $this->input->post('payload'));
			die;
		}
		if ($refresh === false) {
			$this->output->set_status_header('200');
			print json_encode(array('result'=>'200','type'=>'success','message'=>"The deed has been done!",'result'=>$result));
		} elseif ($refresh === 'home') {
			redirect('/?removed', 'refresh');
			exit;
		} else {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	public function create($type=false)
	{
		if ($type===false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>"Blast, you forgot to set the type. Do not pass go."));
			die;
		}
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't create ".$type."s unless you are logged in."));
			die;
		}
		$result = $this->shared->create($type);
		if ($result['error'] === true) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			die;
			//print_r($post = $this->input->post('payload'));
		}
		$this->output->set_status_header('200');
		print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
		
	}

	public function definition($id=false)
	{
		$result = $this->shared->list_bytype('definition',false,true);
		if ($result === false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>'No results. Sorry.'));
			die;
			//print_r($post = $this->input->post('payload'));
		}
		$this->output->set_status_header('200');
		
		print json_encode($result);
		
	}

	public function taxonomy($id=false)
	{
		$result = $this->shared->list_bytype('taxonomy',false,true);
		if ($result === false || empty($result)) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>'No results. Sorry.'));
			die;
			//print_r($post = $this->input->post('payload'));
		}
		$this->output->set_status_header('200');
		
		print json_encode($result);
		
	}

	public function paper($id=false)
	{
		$result = $this->shared->list_bytype('paper',false,true);
		if ($result === false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>'No results. Sorry.'));
			die;
			//print_r($post = $this->input->post('payload'));
		}
		$this->output->set_status_header('200');
		
		print json_encode($result);
		
	}
	public function visualization($slug,$id1,$id2=false)
	{
		$data['id1'] = $id1;
		$data['id2'] = $id2;
		
		$this->load->view("/app/visualizations/$slug",$data);
		
	}

	public function rate()
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't rate unless you are signed in."));
			die;
		}
		$result = $this->shared->rate();
		if ($result === false) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>'404','type'=>'error','message'=>'Rating failed. Crap!'));
			die;
		}
		print json_encode(array('result'=>'200','type'=>'success','message'=>"Rated!",'result'=>$result));
	}

	public function sortdiagrams()
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('401');
			print json_encode(array('result'=>'401','type'=>'error','message'=>"You can't rate unless you are signed in."));
			die;
		}
		$result = $this->shared->sortdiagrams();
		if ($result['code'] == 404) {
			$this->output->set_status_header('404');
			print json_encode(array('result'=>$result['code'],'type'=>'error','message'=>$result['message']));
			die;
		}
		if ($result['code'] === 304) {
			$this->output->set_status_header('304');
			print json_encode(array('result'=>$result['code'],'type'=>'error','message'=>$result['message']));
			die;
		}
		print json_encode(array('result'=>'200','type'=>'success','message'=>"Rated!",'result'=>$result));
	}

	public function uploadimage($return=false)
	{
		if (!$this->ion_auth->logged_in()) {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"You can't create ".$type."s unless you are logged in."));
			die;
		}
		$this->shared->storeimage($return);		
	}	
	public function weather($method='default',$arg=false) {
		switch ($method) {
			case 'weather':
				$this->load->model('shared');
				// do the weather
				$weather = $this->shared->weather('73.164.149.181', 'ip', false);
				$temp = floor($weather['weather']['currently']['apparentTemperature']);
				print_r($temp);
				break;
			default:
				print json_encode(array('message'=>'Hello there :)'));
		}
	}
	public function rsvp($method='default',$arg=false) {
		print json_encode($this->shared->get_data2('rsvp'));
	}
}
