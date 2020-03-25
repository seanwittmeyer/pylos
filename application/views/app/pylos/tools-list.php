<!-- Content Area -->
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-magic"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Tools</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">These are some of the tools we use to help create high performance designs, systems, and buildings. </h3>
			</div>
			<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
				<span style="padding-left: 0;">ZGF Apps: </span>
				<a href="softwarecenter:SoftwareID=" target="_blank">Software Center</a>
				<span> or elsewhere: </span>
				<a href="https://www.food4rhino.com/" target="_blank">Food 4 Rhino</a>
				<a href="https://dynamopackages.com/">Dynamo Packages</a>
				<a href="https://apps.autodesk.com/RVT/en/Home/Index">Revit Addins</a>
			</ul>
		</div>
		<div class="row" style="display: none;">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Guides</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">
				It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
			</p></div>
		</div>		
		<!-- / Content Header --> 
	
		<?php if ($tools === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷</h1>
			<h3>Shoot. Doesn't look like we have any <?php echo $pagetitle; ?> tools.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/guides'); ?>">looking at the guides we do have</a> or <a href="<?php echo site_url('pylos/guides/create'); ?>">add the first <?php echo $pagetitle; ?> guide</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php foreach ($tools as $tool) : if ($tool['type'] !== "dependency") continue; ?> 
			<div class="col-sm-12 grid-filter-single">
				<div class="row">
					<div class="col-sm-4 col-lg-3">
						<h3 class="no-margin"><a class="grid-tools" href="/pylos/dependencies/<?php echo $tool['slug']; ?>"><?php echo $tool['title']; ?></a></h3>
					</div>
					<div class="col-sm-7 col-lg-8">
						<p>
							<strong><?php echo $tool['excerpt']; ?></strong>
						</p>
						<p>
							<a href="/pylos/dependencies/<?php echo $tool['slug']; ?>">Learn more and get <?php echo $tool['title']; ?> &rarr;</a>
						</p>
						<hr />
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php } ?> 
<!-- End Content Area -->
					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->

