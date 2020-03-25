<?php defined('BASEPATH') OR exit('No direct script access allowed');


/* 
 * Content Controller
 *
 * This script is the controller for the static pages on the site. Based on the CI tutorial.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (theseanwitt@gmail.com)
 * 
 */

class Content extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		//$this->load->library('ion_auth');
	}

	public function definition($slug)
	{
		$data = $this->shared->get_byslug('definition',$slug);
		if (!isset($data['title'])) show_404();
		$data['related'] = $this->shared->get_related('definition',$data['id']);
		if ($data['payload'] != '') $data['payload'] = unserialize($data['payload']);
		$data['type'] = 'definition';
		$data['pagetitle'] = $data['title'];
		$data['section'] = 'musings';
		$data['loadjs'][] = 'blank';
		//print_r($data);die;
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/cas/definition', $data);
		$this->load->view('app/builder/templates/footer', $data);
	
	}
	public function taxonomy($slug)
	{
		$data = $this->shared->get_byslug('taxonomy',$slug);
		if (!isset($data['title'])) show_404();
		$data['related'] = $this->shared->get_related('taxonomy',$data['id']);
		$data['type'] = 'taxonomy';
		if ($data['img'] != '') $data['img'] = unserialize($data['img']);
		if ($data['payload'] != '') $data['payload'] = unserialize($data['payload']);
		$data['pagetitle'] = $data['title'];
		$data['section'] = 'musings';
		$data['loadjs'][] = 'blank';
		//print_r($data);die;
		$this->load->view('app/builder/templates/header', $data);
		$path = ($data['template'] == 'default') ? 'app/cas/taxonomy': "app/cas/taxonomy/{$data['template']}";
		$this->load->view($path, $data);
		$this->load->view('app/builder/templates/footer', $data);
	
	}
	public function paper($slug)
	{
		$data = $this->shared->get_byslug('paper',$slug);
		if (!isset($data['title'])) show_404();
		$data['related'] = $this->shared->get_related('paper',$data['id']);
		$data['type'] = 'paper';
		$data['pagetitle'] = $data['title'];
		$data['section'] = 'musings';
		$data['loadjs'][] = 'blank';

		//print_r($data);die;
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/cas/paper', $data);
		$this->load->view('app/builder/templates/footer', $data);
	
	}
	public function feed($type)
	{
		$data['type'] = $type;
		$data['pagetitle'] = ucfirst($type).' Feed';
		$data['section'] = 'musings';
		$data['loadjs'][] = 'masonry';
		//print_r($data);die;
		$this->load->view('app/builder/templates/header', $data);
		$this->load->view('app/cas/feed', $data);
		$this->load->view('app/builder/templates/footer', $data);
	
	}
	/* dev use only
	public function preload($go)
	{
		$terms = array('Parametric Urbanism,Urban Computational Modeling,Evolutionary Economic Modeling,Generative/Iterative Urbanism,Landscape Urbanism,Resilient Urbanism,Informal Urbanism,Tactical Urbanism,Communicative/Strategic Navigation,Urban Datascape,Relational Geography,Assemblage Geography,');
		$user = $this->ion_auth->user()->row();
		foreach ($terms as $term) {
			$insert = array(
				'slug' => $this->shared->slug($term,'taxonomy','slug'),
				'timestamp' => time()-604800,
				'unique' => sha1('cas-'.microtime()),
				'body' => "Sweet grinder java, id milk single shot bar robusta milk, cream, beans as cultivar café au lait aftertaste saucer. Dark, cortado, est, coffee fair trade extra cortado turkish, variety, eu extraction crema french press robusta extra est shop trifecta aftertaste siphon. Variety, dripper coffee bar robusta americano cream carajillo lungo café au lait cinnamon grounds to go. So, aromatic black pumpkin spice and roast, as variety extraction aftertaste americano, aromatic turkish brewed breve brewed ristretto. Crema irish eu breve viennese, arabica white iced barista mocha single origin strong shop robust, café au lait, fair trade kopi-luwak shop macchiato extra arabica macchiato. Wings, carajillo medium, rich, java americano grounds, viennese, cinnamon, caramelization java dark con panna iced, et, ut americano whipped and affogato. Bar aftertaste, galão, espresso that, a, crema, espresso skinny acerbic, iced aged dripper french press macchiato, latte pumpkin spice spoon cup pumpkin spice single shot rich aromatic iced. Et half and half cappuccino aged dripper, half and half, grinder et, white, coffee body viennese, milk shop viennese, barista in plunger pot macchiato that black. Seasonal extraction organic, black, single shot crema roast black galão latte, saucer plunger pot qui redeye coffee.",
				'title' => $term,
				'excerpt' => "Caramelization half and half robust kopi-luwak, brewed, foam affogato grounds extraction plunger pot, bar single shot froth eu shop latte et, chicory, steamed seasonal grounds dark organic.",
				'user' => $user->id,
			);
			print("slug \n");
			$result = $this->db->insert('build_definition',$insert);
		}

		print_r('done');
	}
	*/
}
