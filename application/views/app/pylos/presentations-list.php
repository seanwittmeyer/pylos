<!-- Content Area -->
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-television"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Presentations</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">This is a collection of presentations, workshops, and lunch &amp; learns that can help you boost your knowledge about high performance design.</h3>
			</div>
			<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
				<span style="padding-left: 0;">Popular Themes: </span>
				<a href="<?php echo site_url('pylos/tags/lunch and learn'); ?>">lunch and learn</a>
				<a href="<?php echo site_url('pylos/tags/workshop'); ?>">workshop</a>
				<span>or</span>
				<a href="<?php echo site_url('pylos/presentations/create'); ?>"><span class="fa fa-plus"></span>Share a Presentation</a>
			</ul>
		</div>
		<!-- / Content Header --> 


		<!-- Resources Grid -->
		<?php $this->load->view('app/pylos/templates/grid-combined', array('single'=>'presentations')); ?>
		<!-- Resources Grid -->


<!-- End Content Area -->
					</div><!--
					<div class="col-sm-3">
						<div class="headline-parent"><h3 class="text-left headline">Tools</h3></div>
						<h5><a href="<?php echo site_url('pylos/blocks/create'); ?>">Add a block</a></h5>
						<h5><a href="<?php echo site_url('pylos/guides/create'); ?>">Make a guide</a></h5>
						<h5><a><i>Edit this category</i></a></h5>
						<p><span id="filter-count"></span></p>
					</div>-->
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->

