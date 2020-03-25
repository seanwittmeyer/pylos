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
	
	
	$url = "https://content-vision.googleapis.com/v1/images:annotate?alt=json&key=AIzaSyAqBd6IL-U_nr0faGLCIyzZA5-MLOM0BkU";
	$image = "http://photos.seanwittmeyer.com/trips/Belgium/2015%20Brussels/Up%20to%20the%20Atomium/SWP_5874.jpg";
	$payload = '{"requests": [{"features": [{"type": "LABEL_DETECTION"},{"type": "IMAGE_PROPERTIES"},{"type": "LANDMARK_DETECTION"}],"image": {"source": {"imageUri": "'.$image.'"}}}]}';
	$result = $this->shared->post_curl($url,$payload,true);

	echo "test parse\n\n";
	echo "\nlandmarks\n\n";
	if (isset($result['responses'][0]['landmarkAnnotations']) && count($result['responses'][0]['landmarkAnnotations']) > 0) 
		foreach ($result['responses'][0]['landmarkAnnotations'] as $i) 
			echo "- ".$i['description']." (".floor(100*$i['score'])."%) ".$i['locations'][0]['latLng']['latitude'].", ".$i['locations'][0]['latLng']['longitude']."\n";
	echo "\nlabels\n\n";
	if (isset($result['responses'][0]['labelAnnotations']) && count($result['responses'][0]['labelAnnotations']) > 0) 
		foreach ($result['responses'][0]['labelAnnotations'] as $i) 
			echo "- ".$i['description']." (".floor(100*$i['score'])."%)\n";
	echo "\ncolors\n\n";
	if (isset($result['responses'][0]['imagePropertiesAnnotation']['dominantColors']['colors']) && count($result['responses'][0]['imagePropertiesAnnotation']['dominantColors']['colors']) > 0) 
		foreach ($result['responses'][0]['imagePropertiesAnnotation']['dominantColors']['colors'] as $i) 
			echo "- r".$i['color']['red']." g".$i['color']['green']." b".$i['color']['blue']." (".floor(100*$i['pixelFraction'])."%)\n";
var_dump($result);