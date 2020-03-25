<?php 	
	//error_reporting(0);
	$return = array(
		'episodes' => array(),
		'themes' => array(),
		'perspectives' => array()
	);
	$definition = array();
	$taxonomy = array();

	foreach ($this->shared->get_related('taxonomy',$id1) as $i) {
		$k = array();
		foreach ($this->shared->get_related($i['type'],$i['id']) as $j) {
			${$j['type']}[$j['id']] = array(
				'type' => $j['type'],
				'name' => $j['title'],
				'description' => $j['excerpt'],
				'slug' => $j['slug'],
				'id' => $j['id'],
			);
			$k[] = $j['title'];
		}
		$return['episodes'][] = array(
			'type' => 'episode',
			'name' => $i['title'],
			'description' => $i['excerpt'],
			'date' => date("Y-m-d H:i:s",$i['timestamp']),
			'slug' => '/taxonomy/'.$i['slug'],
			'links' => $k,
		);
	}
	
	foreach ($definition as $l) {
		$return['themes'][] = array(
			'type' => 'theme',
			'name' => $l['name'],
			'description' => $l['description'],
			'slug' => '/definition/'.$l['slug'],
		);
	}
	//var_dump($taxonomy);die;
	foreach ($taxonomy as $t) {
		$return['perspectives'][] = array(
			'type' => 'perspective',
			'name' => $t['name'],
			'description' => $t['description'],
			'slug' => '/taxonomy/'.$t['slug'],
			'count' => 6,
			'group' => null,
		);
	}
	/* *
	foreach ($this->shared->get_related('taxonomy','9') as $l) {
		$return['perspectives'][] = array(
			'type' => 'perspective',
			'name' => $l['title'],
			'description' => $l['excerpt'],
			'slug' => '/taxonomy/'.$l['slug'],
			'count' => 6,
			'group' => null,
		);
	}
	/* */
	$json = json_encode($return);
	print $json;
