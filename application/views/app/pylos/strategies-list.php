<!-- Content Area -->
		<div class="row hideonsearch pylos-section-phase">
			<div class="col-xs-4" style="align-items: center; display: flex;">
				<div>
					<h2 style="margin-top: 0;">For <a href="<?php echo site_url("pylos/phases"); ?>">PM's</a> and <a href="<?php echo site_url("pylos/phases"); ?>">PA's</a></h2>
					<h3>Explore strategies by phase and see when and how to best engage with your projects.</h3>
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
		<hr />
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-trophy"></i></h1>
			</div>
			<div class="col-lg-8">
				<h3 style="font-weight: 600;/* text-transform: none; */">Strategies</h3>
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
				<a href="<?php echo site_url('pylos/strategies/create'); ?>"><span class="fa fa-plus"></span>Create a strategy</a>
			</ul>
		</div>
		<div class="row" style="display: none;">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Strategies</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">
				This is a list of strategies we can take advantage of from certification credits and optimizing MEP to innovating in ways projects engage with wellness, biophilia, and occupant health.
			</p></div>
		</div>		
		<!-- / Content Header --> 
	
		<?php if ($strategies === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷</h1>
			<h3>Shoot. Doesn't look like we have any <?php echo $pagetitle; ?> strategies.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/strategies'); ?>">looking at the guides we do have</a> or <a href="<?php echo site_url('pylos/strategies/create'); ?>">add the first <?php echo $pagetitle; ?> strategy</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php foreach ($strategies as $strategy) : ?> 
			<div class="col-lg-6 grid-filter-single">
				<a class="grid-link" href="<?php echo site_url('pylos/strategies/'.$strategy['slug']); ?>">
					<div class="bs-callout grid-block block-strategy">
						<div class="row">
							<div class="col-xs-8">
								<p class="title">
									<span class="label label-default label-small">Strategy</span><br />
									<span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $strategy['title']; ?></span>
								</p>
								<p class="small" style="text-shadow: 1px 1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, -1px -1px 0 #fff; color: #333 !important; margin-top: -9px;">
									<?php echo $strategy['excerpt']; ?>
								</p>
							</div>
							<div class="col-xs-4">
								<div class="pylos-presentation-preview<?php if (!isset($strategy['thumbnail']) || empty($strategy['thumbnail'])) echo ' grid-nothumb'; ?>" <?php if (isset($strategy['thumbnail']) && !empty($strategy['thumbnail'])) { ?>style="background-image: url('<?php echo $strategy['thumbnail']; ?>');" <?php } ?>></div>
							</div>
						</div>
					</div>
				</a>
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

