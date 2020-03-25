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
				<div class="imageset" style="margin-right: -50px;">
					<iframe src="/includes/plugin/pdfjs/web/viewer.html?file=<?php echo $files[0]['url']; ?>" class="pdfviewer windowheight inthecorner" ></iframe>
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
										<li class="dropdown-header" style="font-weight: normal;"><?php echo ucfirst($presentation['type']); ?> presentation<br />Posted <?php echo date("F j, Y \a\\t g:i a", $presentation['timestamp']); ?></li>
										<li><a href="#" onclick="initedit(); return false;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this Presentation</a></li>
										<li><a href="#" data-toggle="modal" data-target="#newrevision"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Add a Revision</a></li>
										<li><a href="#revisions"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> View Revisions</a></li>
										<li role="separator" class="divider"></li>
										<li class="dropdown-header" style="font-weight: normal;">By clicking delete, you will <br />delete this presentation and all <br />revision files in Pylos. <br />No undo, use this wisely :)</li>
										<li><a href="/pylos/api/remove/pylos_presentations/<?php echo $presentation['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a></li>
									</ul>
								</div>

								<?php echo $contenttitle; ?>
							</h1>
						</div>