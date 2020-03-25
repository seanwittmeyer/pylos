<!-- Content Area -->
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-bar-chart"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Datasets</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">Design Explorer is a great way to evaluate and filter large sets of options and designs that come from Grasshopper and Dynamo using Colibri.</h3>
			</div>
			<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
				<a href="<?php echo site_url('designexplorer'); ?>">Your Datasets</a>
				<a href="<?php echo site_url('designexplorer/shared'); ?>">Shared Datasets</a>
				<span>or</span>
				<a href="<?php echo site_url('designexplorer/create'); ?>"><span class="fa fa-plus"></span>Create a Dataset</a>
			</ul>
		</div>
		<div class="row" style="display: none;">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Datasets</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">These are the building blocks of computational design that you can plug into your tool, analysis, or design process already made.</p></div>
		</div>			
		<?php if (!$this->ion_auth->logged_in()) { ?> 
		<div class="pylos-no-results">
			<h1>🔑</h1>
			<h3>You'll need to sign in to see your datasets</h3>
			<p><a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></p>
		</div>
		<?php } elseif ($blocks === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷</h1>
			<h3>Shoot. Doesn't look like we have any datasets for design explorer.</h3>
			<p>Not to worry, would you like to <a href="<?php echo site_url('designexplorer/create'); ?>">add your first dataset</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php foreach ($blocks as $block) : ?> 
			<div class="col-md-10 grid-filter-single">
				<a class="grid-link" target="_blank" href="<?php 
					$url = base64_encode(site_url(str_replace('/data.csv', '', $block['url'])));
					$return['url'] = site_url("designexplorer/view?Title=".htmlspecialchars($block['title'])."&ID=$url");
						echo $return['url']; ?>">
					<div class="bs-callout grid-de block-de" style="margin-bottom: 0;">
						<div class="row">
							<div class="col-xs-6"><p class="title"><?php echo $block['title']; ?></p><p class="small">Posted <?php
								
								echo $this->designexplorer_model->twitterdate($block['timestamp']); 
								echo ', ';
								list($path) = get_included_files(); 
								$path = str_replace('/index.php', $block['url'], $path);
								$file = file($path);
								echo count($file)-1;
								echo ' items';
								$previewpath = str_replace('data.csv', '', $block['url']);
								
								?></p></div>
							<div class="col-xs-2">
								<div class="pylos-de-preview" style="background-image: url('<?php 
									echo $previewpath;
									$previewimage = explode(',', $file[1]);
									if (substr(preg_replace( "/\r|\n/", "", end($previewimage)), -4) == 'json') {
										echo $previewimage[count($previewimage)-2];
									} else {
										echo preg_replace( "/\r|\n/", "", end($previewimage));	
									}
									?>')"></div>
							</div>
							<div class="col-xs-2">
								<div class="pylos-de-preview" style="background-image: url('<?php 
									echo $previewpath;
									$previewimage = explode(',', $file[2]);
									if (substr(preg_replace( "/\r|\n/", "", end($previewimage)), -4) == 'json') {
										echo $previewimage[count($previewimage)-2];
									} else {
										echo preg_replace( "/\r|\n/", "", end($previewimage));	
									}
									?>')"></div>
							</div>
							<div class="col-xs-2">
								<div class="pylos-de-preview" style="background-image: url('<?php 
									echo $previewpath;
									$previewimage = explode(',', $file[3]);
									if (substr(preg_replace( "/\r|\n/", "", end($previewimage)), -4) == 'json') {
										echo $previewimage[count($previewimage)-2];
									} else {
										echo preg_replace( "/\r|\n/", "", end($previewimage));	
									}
									?>')"></div>
							</div>
						</div>
					</div>
				</a>
				<?php if ($block['user'] == $this->ion_auth->user()->row()->id) { ?><a style="display: inline-block; position: absolute; top: 25px; right: 25px;" href="/designexplorer/api/remove/<?php echo $block['id']; ?>">
					<p><span class="label label-danger">Delete</span></p>
				</a><?php } ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php } ?> 
		<hr />
<!-- End Content Area -->



					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->

