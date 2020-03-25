<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Design Explorer Controller
 *
 * This controller adds design explorer to builder as an app.
 *
 * Version 1.0 (2018.07.23.1020$2)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Designexplorer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('designexplorer_model');
		
	}

	public function create()
	{
		$data['pagetitle'] = 'Upload Design Explorer Data Set'; // Capitalize the first letter
		$data['contenttitle'] = 'Add a Design Explorer Dataset';
		$data['section'] = array('apps', 'designexplorer');
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-default', $data);
		$this->load->view('app/designexplorer/designexplorer-create', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	public function view()
	{
		$data['pagetitle'] = 'Design Explorer View'; // Capitalize the first letter
		$data['contenttitle'] = 'Design Explorer View';
		$data['section'] = array('apps', 'designexplorer');
		$this->load->view('app/designexplorer/designexplorer-view', $data);
	}

	public function index()
	{
		$data['pagetitle'] = 'Design Explorer'; // Capitalize the first letter
		$data['contenttitle'] = 'Design Explorer Datasets';
		$data['section'] = array('apps', 'designexplorer');
		$data['filter'] = true;
		if ($this->ion_auth->logged_in()) {
			$data['blocks'] = $this->designexplorer_model->get_data2('pylos_designexplorer',false,array('user'=>$this->ion_auth->user()->row()->id));
		} else {
			$data['blocks'] = false;
		}
		$data['fullwidth'] = true;
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-default', $data);
		$this->load->view('app/designexplorer/designexplorer-list', $data);
		$this->load->view('app/pylos/templates/footer', $data);
	}

	public function shared()
	{
		$data['pagetitle'] = 'Shared Design Explorer'; // Capitalize the first letter
		$data['contenttitle'] = 'Shared Design Explorer Datasets';
		$data['section'] = array('apps', 'designexplorer');
		$data['filter'] = true;
		$data['fullwidth'] = true;
		if ($this->ion_auth->logged_in()) {
			$data['blocks'] = $this->designexplorer_model->get_data2('pylos_designexplorer',false,array('private'=>0));
		} else {
			$data['blocks'] = false;
		}
		$this->load->view('app/pylos/templates/header', $data);
		$this->load->view('app/pylos/templates/frontmatter-default', $data);
		$this->load->view('app/designexplorer/designexplorer-list', $data);
		$this->load->view('app/pylos/templates/footer', $data);
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
			if ($arg == 'data') {
				$result = $this->designexplorer_model->create('pylos_designexplorer');
				if ($result['code'] == 200) $success = true;
			}			
			// create a new vr experience
			elseif ($arg == 'vrexperience') {
				$result = $this->pylos_model->create('pylos_vrexperience');
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
			$result = $this->designexplorer_model->remove('pylos_designexplorer',$arg);
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
				$this->designexplorer_model->storedata($return);
			}			
			// store associated image
			else {
				$this->designexplorer_model->storedata($return, $arg, $arg2);		
			}			
		}
	}
		



}
