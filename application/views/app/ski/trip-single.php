	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="bs-component">
					<div class="" id="imgheader" style="padding-top: 50px; padding-left: 0px;">
						<div class="">
							<a class="gallery-hero-text" style="background-image: url('/img/Guatemala/Build/SWP_6276.jpeg');">Beach Day&nbsp;</a>
							<p class="trip-subtitle">This is a 13 day trip to the South of France with a variety of people spanning from June 13 through the 25th.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div id="ggwhat"></div>
				<script>
					jQuery(document).ready(function () {
						jQuery("#ggwhat").nanogallery2({
							thumbnailWidth:   'auto',
							thumbnailHeight:  200,
							album:            '',
							kind:             'nano_photos_provider2',
							dataProvider:     '<?php echo site_url(uri_string()."/api"); ?>',
							locationHash:     true,
							thumbnailBorderVertical: 0,
							thumbnailBorderHorizontal: 0,
							thumbnailLabel: {
								display: false
							},
							thumbnailL1Label: {
								display: true,
								align: 'left',

							},
							thumbnailSliderDelay: 2000,
							displayBreadcrumb: true,
							thumbnailAlignment: 'center',
							viewerToolbar:   {
								standard:   "minimizeButton, zoomButton",
								minimized:  "minimizeButton, zoomButton, linkOriginalButton, downloadButton, infoButton" 
							},
							viewerTools:    {
								topLeft:   "label",
								topRight:  "fullscreenButton, closeButton"
							},
							thumbnailToolbarAlbum: { 
								topLeft:'select', 
								topRight: 'counter', 
							},
							thumbnailLevelUp: true,
							galleryFilterTags: false
						});
					});
				</script>    
			</div>
		</div>

		
		
		
	</div>
	<!-- /Jumbotron -->
	<!-- Panels -->
