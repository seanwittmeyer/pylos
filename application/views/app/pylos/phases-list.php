<!-- Content Area -->
		<div class="row hideonsearch pylos-section-phase">
			<div class="col-xs-4" style="align-items: center; display: flex;">
				<div>
					<h2 style="margin-top: 0;">For <a href="<?php echo site_url("pylos/phases"); ?>">PM's</a> and <a href="<?php echo site_url("pylos/phases"); ?>">PA's</a></h2>
					<h3>Understand the what, when, and how of pushing your projects with resources sorted by phase.</h3>
				</div>
			</div>
			<ul class="col-lg-8 pylos-section-menu" style="display: block;">
				<div style="display: flex; margin-bottom: 5px;">
					<a href="<?php echo site_url("pylos/phases/programming"); ?>"><li><i class="fa fa-sitemap"></i>Programming</li></a>
					<a href="<?php echo site_url("pylos/phases/pre-design"); ?>"><li><i class="fa fa-cubes"></i>Pre Design</li></a>
					<a href="<?php echo site_url("pylos/phases/schematic-design"); ?>"><li><i class="fa fa-paint-brush"></i>Schematic Design</li></a>
					<a href="<?php echo site_url("pylos/phases/design-development"); ?>"><li><i class="fa fa-pencil"></i>Design Development</li></a>
				</div>
				<div style="display: flex;">
					<a href="<?php echo site_url("pylos/phases/construction-documents"); ?>"><li><i class="fa fa-newspaper-o"></i>Construction Documents</li></a>
					<a href="<?php echo site_url("pylos/phases/construction-administration"); ?>"><li><i class="fa fa-life-saver"></i>Construction Administration</li></a>
					<a href="<?php echo site_url("pylos/phases/procurement"); ?>"><li><i class="fa fa-shopping-cart"></i>Procurement</li></a>
					<a href="<?php echo site_url("pylos/phases/post-construction"); ?>"><li><i class="fa fa-key"></i>Post Construction</li></a>						
				</div>
			</ul>
		</div>
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset; display: none;">
			<div class="col-lg-2">
				<h1><i class="fa fa-magic"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Phases</h3>-->
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
			<div class="col-lg-2"><h3 style="margin-top: 0;">Phases</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">
				It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
			</p></div>
		</div>		
		<!-- / Content Header --> 
	
		<?php if ($phases === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷</h1>
			<h3>Shoot. Doesn't look like we have any phases.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/taxonomy/create'); ?>">adding the first phase</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php foreach ($phases as $phase) : if ($phase['type'] !== "phase") continue; ?> 
			<div class="col-sm-12 grid-filter-single">
				<div class="row">
					<div class="col-sm-4 col-lg-3">
						<h3 class="no-margin"><a class="grid-tools" href="/pylos/phases/<?php echo $phase['slug']; ?>"><?php echo $phase['title']; ?></a></h3>
					</div>
					<div class="col-sm-7 col-lg-8">
						<p>
							<strong><?php echo $phase['excerpt']; ?></strong>
						</p>
						<p>
							<a href="/pylos/phases/<?php echo $phase['slug']; ?>">Learn more about <?php echo $phase['title']; ?> &rarr;</a>
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

