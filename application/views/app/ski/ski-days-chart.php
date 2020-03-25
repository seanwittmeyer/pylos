country	year	percent
<?php
	// get speed, lat and lon maxes and mins for color scale and map bounds
	$i=1;
	foreach ($nodes as $n) {
		echo "elevation	".date("gis",$n['time'])."	".floor($n['elev'])."\n";
		$i++;
		if ($i > 1000) break;
	}
	//foreach ($nodes as $n) echo "speed	".date("g:i:s",$n['time'])."	".floor($n['speed'])."\n";

