<!-- Content Area -->
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-trophy"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Tools</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">The secret sauce to pushing project performance is engaging with a variety of different design and performance strategies.</h3>
			</div>
			<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
				<span style="padding-left: 0;">Popular Themes: </span>
				<a href="<?php echo site_url('pylos/themes/leed'); ?>">LEED</a>
				<a href="<?php echo site_url('pylos/themes/daylighting'); ?>">daylighting</a>
				<a href="<?php echo site_url('pylos/themes/materials'); ?>">materials</a>
				<a href="<?php echo site_url('pylos/themes/climate'); ?>">climate</a>
				<a href="<?php echo site_url('pylos/themes/all'); ?>">all themes &rarr;</a>
				<span>or</span>
				<a href="<?php echo site_url('pylos/taxonomy/create'); ?>"><span class="fa fa-plus"></span>Create a theme</a>
			</ul>
		</div>
		<div class="row" style="display: none;">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Themes</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">
				This is a list of strategies we can take advantage of from certification credits and optimizing MEP to innovating in ways projects engage with wellness, biophilia, and occupant health.
			</p></div>
		</div>		
		<!-- / Content Header --> 
	
		<?php if ($taxonomies === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷</h1>
			<h3>Shoot. Doesn't look like we have any <?php echo $pagetitle; ?> theme.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/taxonomy'); ?>">looking at the themes we do have</a> or <a href="<?php echo site_url('pylos/taxonomy/create'); ?>">add the first <?php echo $pagetitle; ?> theme</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php foreach ($taxonomies as $taxonomy) : ?> 
			<div class="col-sm-12 grid-filter-single">
				<div class="row">
					<div class="col-sm-4 col-lg-3">
						<h3 class="no-margin"><a class="grid-tools" href="/pylos/taxonomy/<?php echo $taxonomy['slug']; ?>"><?php echo $taxonomy['title']; ?></a></h3>
					</div>
					<div class="col-sm-7 col-lg-8">
						<p>
							<strong><?php echo $taxonomy['excerpt']; ?></strong>
						</p>
						<p>
							<a href="/pylos/taxonomy/<?php echo $taxonomy['slug']; ?>">Dive into <?php echo $taxonomy['title']; ?> &rarr;</a>
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

