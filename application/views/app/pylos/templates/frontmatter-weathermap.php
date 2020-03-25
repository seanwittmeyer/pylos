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
				<div class="imageset" style="padding-top: 60px;">
					<script src="https://gist.github.com/seanwittmeyer/d5eba94ae598b6e275b2e30426137872.js"></script>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="headline-parent"><h1 class="text-left headline" id="hydratitle">EPW Weather Files</h1></div>
						<!-- Content Area -->
						<div class="row">
							<div class="col-lg-8"><h3 style="font-weight: 600; text-transform: none;">EPW formatted weather files are the backbone of any solid environmental analysis, prepared for EnergyPlus and other tools we use. </h3></div>
						</div>
						<div class="row">
							<div class="col-lg-10"><!-- Page title: <?php echo $title; ?>(ID: <?php echo $id; ?>)--><p>The map above shows weather files and links for over 2100 locations worldwide, organized by the World Meteorological Organization region and country. When choosing a location, select the "all" link for use in many of the tools on this site, especially the insect tools (Ladybug and Honeybee).</p><p>The weather files are hosted at the EnergyPlus website, you can learn more about them and see <a href="https://www.energyplus.net/weather" target="_blank">their file directory here</a>.</p><p>You can also see the great map interface by the Ladybug Tools team which shows additional data sources and a great interface for showing basic weather metrics by hovering over different locations. Check out the <a href="http://www.ladybug.tools/epwmap/" target="_blank">Ladybug Tools epwmap here</a>.</p></div>
						</div>			
						<!-- End Content Area -->

					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->
