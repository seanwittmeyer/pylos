<!-- Content Area -->
		<?php if (strtolower($slug) == 'projects') { ?> 
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-university"></i></h1>
			</div>
			<div class="col-lg-8">
				<h3 style="font-weight: 600;">Projects are coming soon!</h3>
				<h3 style="font-weight: 600;">We are still finding the best way for Pylos to connect to project information. Until then, you'll see a list of all projects tagged below.</h3>
			</div>
			<div class="col-lg-8 col-lg-offset-2">
				<!--<h3 style="font-weight: 600;">Blocks</h3>-->
				<h3 style="font-weight: 600;">
					These are resources associated with projects which you can plug into your tool, analysis, or design process already made.   
					<br />&nbsp;
				</h3>
			</div>
		</div>
		<!-- / Content Header --> 
		<?php } ?>
		<!-- Hero Content Header --> 
		<div class="row">
			<div class="col-lg-2"><h3 style="margin-top: 0;"><?php echo ucfirst($slug); ?> Listing</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">This is a list of every <?php echo urldecode($slug); ?></strong> in Pylos, use the search box above to make this page useful and narrow what you are looking for. </p></div>
		</div>	
		<hr>		
		<!-- / Content Header --> 
	
		<div class="row grid-filter">
			<div class="col-lg-10 col-lg-offset-1 tag-cloud">
			<?php foreach ($tags as $tag) : ?> 
				<a class="grid-filter-single" href="/pylos/<?php echo $slug; ?>/<?php echo $tag[0]; ?>"><?php echo $tag[0]; ?> (x<?php echo $tag[1]; ?>)</a>
			<?php endforeach; ?>
			</div>
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

