<?php
	// get speed, lat and lon maxes and mins for color scale and map bounds
	$speeds = array();
	$lats = array();
	$lons = array();
	foreach ($nodes as $n) {
		$speeds[] = $n['speed'];
		$lats[] = $n['lat'];
		$lons[] = $n['lon'];
	}
	$coloroptions = array(max($speeds),min($speeds));
	function speedcolor($speed,$c) {
		$colors = array('#ffd700','#ffbf1e','#ffa636','#fb8c46','#f4734f','#e95c50','#db444c','#ca2f43','#b81a33','#a2071f','#8b0000');
		$level = round((($speed-$c[1])/($c[0]-$c[1]))*10,0);
		return $colors[$level];
	}
	$bounds = array(
		'min' => min($lons).', '.min($lats),
		'max' => max($lons).', '.max($lats)
	);

	//$result = $this->shared->get_curl("https://build.seanwittmeyer.com/ski/data",true);
	
	
	$url = "https://build.seanwittmeyer.com/includes/test/snow";
	//$image = "http://photos.seanwittmeyer.com/trips/Belgium/2015%20Brussels/Up%20to%20the%20Atomium/SWP_5874.jpg";
	//$payload = '{"requests": [{"features": [{"type": "LABEL_DETECTION"},{"type": "IMAGE_PROPERTIES"},{"type": "LANDMARK_DETECTION"}],"image": {"source": {"imageUri": "'.$image.'"}}}]}';
	$result = $this->shared->get_curl($url,true);

	echo "test parse\n\n";
	print_r($result);