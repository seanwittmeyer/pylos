<!-- Content Area -->
<?php
	$thisseason = (date('n') > 7) ? date('Y').'/'.(1+date('y')) : (date('Y')-1).'/'.date('y');
	$track = $track[0];
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
	$bounds = array(
		'min' => min($lons).', '.min($lats),
		'max' => max($lons).', '.max($lats)
	);


?>	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-md-4">
				<div class="bs-component">
					<div class=" windowheight" id="imgheader" style="">
						<div class="">
							<div class="skisubnav">
								<a href="/ski"><i class="fa fa-arrow-left" aria-hidden="true"></i> Ski</a> <span> -or- </span>  <a href="/ski/days">All Days</a>
							</div>
							<img class="skiviewresortlogo" src="/includes/img/resorts/<?php echo $track['resort']; ?>.png" />
							<h3 style="padding: 0; margin: 0;">24 runs on <?php echo date("M j",$track['start']); ?> at</h3>
							<h1 style="padding: 0 0 20px; margin: 0;"><?php echo $track['description']; ?></h1>
							<p style="padding: 0 0 20px; margin: 0;"><?php echo $track['season']; ?> season | <?php echo date("g:i a",$track['start']); ?> &rarr; <?php echo date("g:i a",$track['finish']); ?></p>
							<div class="row">
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo $track['descents']; ?> <span>runs</span></h3><p><?php echo $this->shared->niceduration($track['duration']); ?></p></div>
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo round($track['distance']/5280,1); ?> <span>mi</span></h3><p>Total</p></div>
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo round($track['descentdistance']/5280,1); ?> <span>mi</span></h3><p>Downhill</p></div>
							</div>
							<div class="row">
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo number_format($track['totaldescent']); ?> <span>ft</span></h3><p>Vertical</p></div>
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo number_format($track['minaltitude']); ?> <span>ft</span></h3><p>Base Elev.</p></div>
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo number_format($track['maxaltitude']); ?> <span>ft</span></h3><p>Track High Elev.</p></div>
							</div>
							<div class="row">
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo round($track['maxspeed'],1); ?> <span>mph</span></h3><p>Downhill</p></div>
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo round($track['maxascentspeed'],1); ?> <span>mph</span></h3><p>Uphill</p></div>
								<div class="col-xs-4 ski-stat-dark"><h3><?php echo round($track['averagespeed'],1); ?> <span>mi</span></h3><p>Down Avg</p></div>
							</div>
							<div class="row">
								<style>
									text {
										font: 12px sans-serif;
									}
									svg {
										display: block;
									}
									svg.sparkline {
										width: 100%;
										height: 80px;
										font-size: 14px;
										margin-top: -6px;
									}
								</style>
								<div class="col-xs-11">
									<div><svg id="chart2" class="sparkline"></svg></div>
									<p>Elevation</p>
									<div><svg id="chart3" class="sparkline"></svg></div>
									<p>Speed</p>

<script>
	var dataelev = [<?php $i=1; $p = count($nodes); $q = ','; foreach ($nodes as $n) { if ($p===$i) $q=''; echo '{x:"'.date("g:i a",$n['time']).'",y:'.floor($n['elev']).'}'.$q; $i++; } ?>];
	var dataspeed = [<?php $i=1; $p = count($nodes); $q = ','; foreach ($nodes as $n) { if ($p===$i) $q=''; echo '{x:"'.date("g:i a",$n['time']).'",y:'.round($n['speed'],2).'}'.$q; $i++; } ?>];
    function defaultChartConfig(containerId, data, format) {
        nv.addGraph(function() {

            var chart = nv.models.sparklinePlus();
            chart.margin({"left":3,"right":70,"top":0,"bottom":10})
            	.x(function(d,i) { return i })
                .showLastValue(false)
                .xTickFormat(function(d) {
                    return data[d].x + ' (' + format + ')'
                });

            d3.select(containerId)
                    .datum(data)
                    .call(chart);

            return chart;
        });
    }
	defaultChartConfig("#chart2",dataelev,"ft");
	defaultChartConfig("#chart3",dataspeed,"mph");

</script>



								</div>
								
								<?php /* CHART USING CHARTJS - Too slow to use with large datasets.
								<div class="col-xs-11"><canvas id="canvas"></canvas></div>
								<script>
									var lineChartData = {
										labels: [<?php $i=1; foreach ($nodes as $n) { echo "'".date("g:i",$n['time'])."',"; $i++; } ?>],
										datasets: [{
											label: 'Elevation',
											borderColor: window.chartColors.red,
											backgroundColor: window.chartColors.red,
											fill: false,
											data: [<?php $i=1; foreach ($nodes as $n) { ?><?php echo floor($n['elev']).",\n"; $i++; } ?>],
											yAxisID: 'y-axis-1',
										}, {
											label: 'Speed',
											borderColor: window.chartColors.blue,
											backgroundColor: window.chartColors.blue,
											fill: false,
											data: [<?php $i=1; foreach ($nodes as $n) { ?><?php echo round($n['speed'],2).",\n"; $i++; } ?>],
											yAxisID: 'y-axis-2'
										}]
									};
								
									window.onload = function() {
										var ctx = document.getElementById('canvas').getContext('2d');
										window.myLine = Chart.Line(ctx, {
											data: lineChartData,
											options: {
												responsive: true,
												hoverMode: 'index',
												stacked: false,
												title: {
													display: false,
												},
												legend: {
													position: 'bottom',
												},
												scales: {
													xAxes: [{
														gridLines: {
															display: false,
															drawBorder: false
														}
													}],
													yAxes: [{
														type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
														display: false,
														position: 'left',
														id: 'y-axis-1',
														gridLines: {
															display: false,
															drawBorder: false
														},
													}, {
														type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
														display: false,
														position: 'right',
														id: 'y-axis-2',
								
														// grid line settings
														gridLines: {
															display: false,
															drawBorder: false
														},
													}],
												}
											}
										});
									};
								
								</script>
								<?php */ ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="bs-component">
					<div class="windowheight" id="imgheader">
						<div id="map" class="windowheight" style="position: relative; width: 100%; height: 100%; border-radius: 8px;"></div>
						<script>
						mapboxgl.accessToken = 'pk.eyJ1Ijoic2VhbndpdHRtZXllciIsImEiOiJjamhiaWlmZ2MwMm4yM2RxbzNxcmJwdTB4In0.h6Gl0fZHDBPfNqD6Ltw_Bw';
						var map = new mapboxgl.Map({
						    container: 'map',
						    style: 'mapbox://styles/seanwittmeyer/cjhcertet11702rs4tqcg54r4',
						    center: [<?php echo min($lons).', '.min($lats); ?>],
						    zoom: 13.5
						});
						var bounds = new mapboxgl.LngLatBounds([<?php echo $bounds['min']; ?>], [<?php echo $bounds['max']; ?>])
						map.on('load', function() {
						    var skigeojson = {
						        'type': 'FeatureCollection',
						        'features': [<?php 
						            $p=false;
						            foreach ($nodes as $n ) { 
						            	if (is_array($p)) { ?>{
						            'type': 'Feature',
						            'properties': {
						                'color': '<?php echo $this->shared->speedcolor($p['speed'], $coloroptions); ?> ' // red
						            },
						            'geometry': {
						                'type': 'LineString',
						                'coordinates': [
						                    [<?php echo $p['lon']; ?>, <?php echo $p['lat']; ?>],
						                    [<?php echo $n['lon']; ?>, <?php echo $n['lat']; ?>]
						                ]
						            }
						        },<?php } $p = $n; } ?> 
						        ]
						    };
						    map.addLayer({
						        'id': 'lines',
						        'type': 'line',
						        'source': {
						            'type': 'geojson',
						            'data': skigeojson
						        },
						        "layout": {
						            "line-join": "round",
						            "line-cap": "round"
						        },
						        'paint': {
						            'line-width': 3,
						            // Use a get expression (https://www.mapbox.com/mapbox-gl-js/style-spec/#expressions-get)
						            // to set the line-color to a feature property value.
						            'line-color': ['get', 'color']
						        }
						    });
						    
						    map.fitBounds(bounds, {
						        padding: 100
						    });
						});
						
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>







	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1">
				<h3 style="margin-top: 0;">this day was awesome</h3>
				<pre><?php print_r($track); ?></pre>
			</div>
		</div>
	</div>

<!-- End Content Area -->
