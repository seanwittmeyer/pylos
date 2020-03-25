<body>
	<!-- Header -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-sm-3"><h3 class="text-left hidden-lg"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Pylos</a></h3></div>
			<div class="col-sm-9 hidden-xs"></div>
		</div>
	</div>
	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php $this->load->view('app/pylos/templates/menu-beta'); ?>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="imageset">
					<?php if ($block['type'] == 'maxscript' || $block['type'] == 'python' || $block['type'] == 'script') { ?> 
					
					<textarea id="codeeditor" style=""><?php echo $block['code']; ?></textarea>

					<script>
					    var editor = ace.edit("codeeditor");
					    editor.setTheme("ace/theme/tomorrow");
					    editor.session.setMode("ace/mode/python");
					</script>

					<?php } else { ?> 
					<div id="imageContainer" style="text-align: center;">
						<button class="btn btn-info btn-xs panzoomreset pull-right" style="z-index: 1;position: relative;" type="button">Show All</button>
						<div class="panzoom">
							<?php
							$__images = array();
							if ($images) {
								foreach ($images as $image) {
									$__images[] = $image;
								}
							}
							?>
				        	<img id="panzoomactiveimg" src="<?php echo $__images[0]['url']; ?>" style="width:auto" />
						</div>
						<script>
							function panzoomcontainerheight() {	$('.panzoom>img').css({'height': window.innerHeight/1.7});	}
							$(document).ready(panzoomcontainerheight);
							(function() {
								var $section = $('#imageContainer');
								$section.find('.panzoom').panzoom({
									$reset: $('.imageset').find(".panzoomreset")
								});
								var $panzoom = $section.find('.panzoom').panzoom();
								$panzoom.parent().on('mousewheel.focal', function( e ) {
									e.preventDefault();
									var delta = e.delta || e.originalEvent.wheelDelta;
									var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
									$panzoom.panzoom('zoom', zoomOut, {
										animate: false,
										focal: e
									});
								});
							})();
						</script>
					</div>
					<div class="panzoomthumbs">
						<?php foreach ($__images as $i) { ?><a href="<?php echo $i['url']; ?>" onclick="$('#panzoomactiveimg').attr('src','<?php echo $i['url']; ?>'); $('.panzoom').panzoom('reset'); return false;"><img class="panzoomreset" src="<?php echo $i['url']; ?>" /></a><?php } ?> 
					</div>
					<?php } ?> 
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="headline-parent">
							<h1 class="text-left headline" id="hydratitle">
								<div class="btn-group pull-right" style="letter-spacing: normal; text-transform: none;">
									<a href="<?php echo $files[0]['url']; ?>" class="btn btn-primary" style="letter-spacing: normal;">Download</a>
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu">
										<li class="dropdown-header" style="font-weight: normal;"><?php echo ucfirst($block['type']); ?> Block<br />Posted <?php echo date("F j, Y \a\\t g:i a", $block['timestamp']); ?></li>
										<li><a href="#" onclick="initedit(); return false;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this Block</a></li>
										<li><a href="#" data-toggle="modal" data-target="#newrevision"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Add a Revision</a></li>
										<li><a href="#revisions"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> View Revisions</a></li>
										<li role="separator" class="divider"></li>
										<li class="dropdown-header" style="font-weight: normal;">By clicking delete, you will <br />delete this block and all <br />revision files in Pylos. <br />No undo, use this wisely :)</li>
										<li><a href="/pylos/api/remove/pylos_block/<?php echo $block['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a></li>
									</ul>
								</div>

								<?php echo $contenttitle; ?>
							</h1>
						</div>