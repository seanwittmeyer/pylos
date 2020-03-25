<?php
	$thisseason = (date('n') > 7) ? date('Y').'/'.(1+date('y')) : (date('Y')-1).'/'.date('y');
?>	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-md-6">
				<div class="bs-component">
					<div class="jumbotron jumbotron-home windowheight" id="imgheader" style="background-image: url('/includes/img/blackcomb.jpg');">
						<div class="container">
							<h1 class="light-text">ski<!-- (3)--></h1>
							<p class="light-text">These are the people who established the fundamentals of complexity theory or are voices promoting the theory and understanding of Complex Adaptive Systems.</p>
							<div class="row">
								<div class="col-sm-4 ski-stat"><h3>45 mph</h3><p>Runs</p></div>
								<div class="col-sm-4 ski-stat"><h3>45 mph</h3><p>Vertical Descent</p></div>
								<div class="col-sm-4 ski-stat"><h3>45 mph</h3><p>Downhill Distance</p></div>
							</div>
							<div class="row">
								<div class="col-sm-4 ski-stat"><h3>45 mph</h3><p>Max Speed</p></div>
								<div class="col-sm-4 ski-stat"><h3>45 mph</h3><p>Tracked Time</p></div>
								<div class="col-sm-4 ski-stat"><h3>45 mph</h3><p>Tracked Days</p></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="bs-component">
					<div class="windowheight" id="imgheader" style="background-color: #fafafa;">
						<div class="container-fluid">
							<h3 class="light-text"><?php 
								$this->db->like('season', $thisseason);
								$this->db->from('build_ski_days');
								echo $this->db->count_all_results();
								?> Days This Season<!-- (3)--></h1>
							<p class="light-text">These are the days collected so far for the <?php echo $thisseason; ?> season. <a href="/ski/create">Add a track!</a></p>
							<?php 
								$i = false;
								$j = $thisseason;
								$k = 1;
								$resortdays = array();
								foreach ($tracks as $track) { 
								if ($j != $track['season']) $i = true;
								$resortdays[$track['resort']] = (isset($resortdays[$track['resort']])) ? $resortdays[$track['resort']]+1 : 1;
								
								if ($i && $k === 1) { ?> 
							<p>No snow days yet for this season. Get out there and <a href="/ski/create">add a track!</a></p>
							<p><h3><?php echo $track['season']; ?></h3></p>
							<?php } elseif ($i) { ?> 
							<p><h3><?php echo $track['season']; ?></h3></p>
							<?php } ?> 
							
							<p>
								<a href="/ski/days/<?php echo $track['dayid']; ?>"><strong><?php echo $track['description']; ?></strong></a><br />
								<i class="fa fa-map-signs" aria-hidden="true"></i> <strong><?php echo $track['descents']; ?></strong> runs &nbsp; 
								<i class="fa fa-arrows-h" aria-hidden="true"></i> <strong><?php echo round($track['descentdistance']/5280,0); ?></strong> mi &nbsp; 
								<i class="fa fa-rocket" aria-hidden="true"></i> <strong><?php echo round($track['maxspeed'],1); ?></strong> mph &nbsp; 
								<!--<i class="fa fa-rocket" aria-hidden="true"></i> <strong><?php echo round($track['totaldescent']/1000,1); ?>k </strong>ft &nbsp; 
								<!--<i class="fa fa-rocket" aria-hidden="true"></i> <strong><?php echo $this->shared->niceduration($track['duration']); ?></strong>-->
							</p>
							<?php 
									$i = false;
									$j = $track['season'];
									$k++;
									if ($k == 20) break;
								} ?> 
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="bs-component">
					<div class=" windowheight" id="imgheader" style="background-color: #fafafa;">
						<div class="container-fluid">
							<h3 class="light-text">Favorite Ski Areas<!-- (3)--></h1>
							<p class="light-text">All of the resorts in the system with days and some weather. You can <a href="<?php echo site_url("ski/createresort"); ?>">add a resort</a> too.</p>
							<?php 
								$r = array();
								$s = arsort($resortdays);
								foreach ($resorts as $n) $r[$n['slug']] = $n;
								foreach ($resortdays as $key => $resort) { ?>
							<p>
								<a href="/ski/resorts/<?php echo $r[$key]['slug']; ?>"><strong><?php echo $r[$key]['name']; ?></strong></a><br />
								<i class="fa fa-map-signs" aria-hidden="true"></i> <strong><?php echo $resortdays[$key]; ?></strong> days &nbsp; 
								<i class="fa fa-arrows-h" aria-hidden="true"></i> <strong><?php echo $r[$key]['downhilltrails']; ?></strong> runs &nbsp; 
								<i class="fa fa-rocket" aria-hidden="true"></i> <strong><?php echo $r[$key]['downhillacres']; ?></strong> acres &nbsp; 
								<!--<i class="fa fa-rocket" aria-hidden="true"></i> <strong><?php echo round($track['totaldescent']/1000,1); ?>k </strong>ft &nbsp; 
								<!--<i class="fa fa-rocket" aria-hidden="true"></i> <strong><?php echo $this->shared->niceduration($track['duration']); ?></strong>-->
							</p>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
