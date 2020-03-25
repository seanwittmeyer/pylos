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
				<div class="">
					<div id="imageContainer"><button id="zoomtoextents" class="btn btn-info btn-xs" type="button">ZoomToExtent</button></div>
					<div id="slideshow">
						<!--
						<label><input type="radio" id="toggleView" name="viewMode" value="GH" title="image1" checked></label>
					    <label><input type="radio" id="toggleView" name="viewMode" value="RH"></label>
					    -->
					</div>
					<div id="videoContainer"></div>
				</div>
				<div class="row">
					<div class="col-sm-9">
						<div class="headline-parent"><h1 class="text-left headline" id="hydratitle"><?php echo $contenttitle; ?></h1></div>
