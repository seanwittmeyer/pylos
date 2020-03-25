<!-- Content Area -->
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-mortar-board"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Guides</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">Tutorials with easy to follow steps that take you from question to answer for a variety of tasks and analysis.</h3>
			</div>
			<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
				<span style="padding-left: 0;">Popular Themes: </span>
				<a href="<?php echo site_url('pylos/tags/energy'); ?>">energy</a>
				<a href="<?php echo site_url('pylos/tags/daylighting'); ?>">daylighting</a>
				<a href="<?php echo site_url('pylos/tags/materials'); ?>">materials</a>
				<a href="<?php echo site_url('pylos/tags/climate'); ?>">climate</a>
				<span>or</span>
				<a href="<?php echo site_url('pylos/guides/create'); ?>"><span class="fa fa-plus"></span>Create a Guide</a>
			</ul>
		</div>
		<!-- / Content Header --> 
	
		<!-- Resources Grid -->
		<?php $this->load->view('app/pylos/templates/grid-combined', array('single'=>'guides')); ?>
		<!-- Resources Grid -->
<!-- End Content Area -->
					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->

