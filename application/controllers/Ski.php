<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Ski Controller
 *
 * This is where the ski nonsense starts.
 *
 * Version 2.0 (2018.03.25.1340$2)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Ski extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('shared');
		$this->load->model('ski_model');
	}

	public function index()
	{
		$data['section'] = 'pylos';
		$data['filter'] = true;
		if ($this->ion_auth->logged_in()) {
			$data['tracks'] = $this->shared->get_data2('ski_days',false,false,array("start", "desc"));
		} else {
			$data['tracks'] = $this->shared->get_data2('ski_days',false,false,array("start", "desc"));
		}
		$data['resorts'] = $this->shared->get_data2('ski_resorts');
		$data['pagetitle'] = 'Ski'; // Capitalize the first letter
		$data['section'] = 'ski';
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/ski/index', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}


	public function test($dayid=false)
	{
		$data['pagetitle'] = 'Trips and Photos'; // Capitalize the first letter
		$data['section'] = 'trip';
		$data['loadjs']['mapbox'] = true;
		$data['dayid'] = $dayid;

		$data['track'] = $this->shared->get_data2('ski_days',false,array('dayid'=>$dayid));
		$data['nodes'] = $this->shared->get_data2('ski_nodes',false,array('parentid'=>$dayid),array("time","asc"));
		
		$this->load->view('app/ski/test', $data);
	}

	public function data($dayid=false)
	{
		$data['pagetitle'] = 'Trips and Photos'; // Capitalize the first letter
		$data['section'] = 'trip';
		$data['loadjs']['mapbox'] = true;
		$data['dayid'] = $dayid;

		$data['track'] = $this->shared->get_data2('ski_days',false,array('dayid'=>$dayid));
		$data['nodes'] = $this->shared->get_data2('ski_nodes',false,array('parentid'=>$dayid),array("time","asc"));
		
		$this->load->view('app/ski/data', $data);
	}

	public function redirect()
	{
		redirect('app/vr/tours', 'refresh');
	}

	public function create()
	{
		$data['pagetitle'] = 'Upload Design Explorer Data Set'; // Capitalize the first letter
		$data['contenttitle'] = 'Add a Design Explorer Dataset';
		$data['section'] = 'pylos';
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/ski/ski-create', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}

	public function createresort($snocountryid=false,$slug='notitle')
	{
		$data['pagetitle'] = 'Add a Resort'; // Capitalize the first letter
		$data['contenttitle'] = 'Add a Ski Resort to Builder';
		$data['section'] = 'ski';
		if (!$snocountryid) {
			// silence
		} else {
			$data['resort'] = $this->shared->get_data2('ski_resorts',false,array('snocountry'=>$snocountryid));
			if (is_array($data['resort'])) {
				redirect('ski/resorts/'.$data['resort'][0]['slug'], 'refresh');
			} else {
				$this->ski_model->create('resorts',array($snocountryid,$slug));
				redirect('ski/resorts/'.$slug.'?import=true', 'refresh');
			}
		}
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/ski/ski-create-resort', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}

	public function days($dayid=false)
	{
		$data['pagetitle'] = 'Ski Day'; // Capitalize the first letter
		$data['contenttitle'] = 'Ski Day';
		$data['section'] = 'ski';
		$data['loadjs']['mapbox'] = true;
		//$data['loadjs']['chartjs'] = true;
		$data['loadjs']['nvd3'] = true;
		$data['loadjs']['skimaponload'] = true;
		$data['track'] = $this->shared->get_data2('ski_days',false,array('dayid'=>$dayid));
		$data['nodes'] = $this->shared->get_data2('ski_nodes',false,array('parentid'=>$dayid),array("time","asc"));
		$this->load->view('app/builder/templates/header', $data);
		if ($dayid) {
			$this->load->view('app/ski/ski-view', $data);
		} else {
			$this->load->view('app/ski/ski-days', $data);
		}
		$this->load->view('app/builder/templates/footer', $data);
	}
	
	public function resorts($resortslug=false)
	{
		$data['pagetitle'] = 'Resorts'; // Capitalize the first letter
		$data['contenttitle'] = 'Resorts';
		$data['section'] = 'ski';
		$data['loadjs']['mapbox'] = true;
		$data['loadjs']['chartjs'] = true;
		$data['loadjs']['sparkline'] = true;
		$data['loadjs']['skimaponload'] = true;
		$data['resort'] = $this->shared->get_data2('ski_resorts',false,array('slug'=>$resortslug));
		//$data['nodes'] = $this->shared->get_data2('ski_nodes',false,array('parentid'=>$id),array("time","asc"));
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/ski/ski-resorts-single', $data);
		$this->load->view('app/builder/templates/footer', $data);
	}

	public function days_chart($dayid=false)
	{
		$data['pagetitle'] = 'Trips and Photos'; // Capitalize the first letter
		$data['dayid'] = $dayid;

		$data['track'] = $this->shared->get_data2('ski_days',false,array('dayid'=>$dayid));
		$data['nodes'] = $this->shared->get_data2('ski_nodes',false,array('parentid'=>$dayid),array("time","asc"));

		$this->load->view('app/ski/ski-days-chart', $data);
	}

	public function api($method=null, $arg=null, $arg2=null)
	{
		// authenticated?
		if (!$this->ion_auth->logged_in() && $arg !== 'wizard') {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'403','type'=>'error','message'=>"I'm sorry but you can't contribute and curate content unless you are signed in."));
			die;
		}
		// create method for blocks, templates, and vr views
		if ($method == 'create') {
			$success = false;
			// create a new block
			if ($arg == 'days') {
				$result = $this->ski_model->create('days');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new vr experience
			elseif ($arg == 'vrexperience') {
				$result = $this->ski_model->create('pylos_vrexperience');
				if ($result['code'] == 200) $success = true;
			}
			
			if ($success) {
				$this->output->set_status_header('200');
				print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
			} else {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			}
		}
		// remove method for datasets
		if ($method == 'remove') {
			$success = false;
			// remove a dataset
			$result = $this->ski_model->remove('pylos_designexplorer',$arg);
			if ($result) $success = true;
			
			redirect('designexplorer', 'refresh');

			if ($success) {
				$this->output->set_status_header('200');
				print json_encode(array('result'=>'200','type'=>'success','message'=>"All done!",'result'=>$result));
			} else {
				$this->output->set_status_header('403');
				print json_encode(array('result'=>'403','type'=>'error','message'=>$result['message']));
			}
		}
		// upload image - image functions should handle success and error responses
		if ($method == 'uploaddata') {
			$return = false; // not using this here.
			// store anonymous image
			if ($arg == null) {
				$this->ski_model->storedata($return);
			}			
			// store associated image
			else {
				$this->ski_model->storedata($return, $arg, $arg2);		
			}			
		}
	}

}
