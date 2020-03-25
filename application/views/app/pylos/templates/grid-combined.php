<?php // setup
	$single = (isset($single)) ? $single : false; 
	$guides = (isset($guides) && count($guides) > 0) ? $guides : false; 
	$presentations = (isset($presentations) && count($presentations) > 0) ? $presentations : false; 
	$blocks = (isset($blocks) && count($blocks) > 0) ? $blocks : false; 
	$limit = (isset($limit)) ? $limit : false; 
	$anon = ($this->ion_auth->logged_in()) ? false : true; 
	?> 
		<!--<div class="alert alert-warning">Start Fancy Listing</div><!---->
		<!-- Begin Resource Listing -->
		<?php if ($guides !== false) { ?> 
		<?php if (!$single) { ?>
		<div class="row" id="guides">
			<div class="col-md-3"><h1 style="margin: 0;"><i class="fa fa-mortar-board"></i></h1><h3 style="margin-top: 0;">Guides</h3></div>
			<div class="col-md-8"><p>
				It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
			</p></div>
		</div>
		<?php } ?>
		<div class="row grid-filter">
			<?php $i = 1; foreach ($guides as $guide) : if ($anon && $guide['private'] == 1) continue; ?> 
			<div class="col-lg-4 col-md-6 col-sm-12 grid-filter-single"<?php if ($limit and $i > 6) { ?> style="display: none;"<?php } ?>>
				<a class="grid-link" href="/pylos/guides/<?php echo $guide['slug']; ?>">
					<div class="bs-callout grid-block block-guide<?php if (!isset($guide['thumbnail']) || empty($guide['thumbnail'])) echo ' grid-nothumb'; ?>" <?php if (isset($guide['thumbnail']) && !empty($guide['thumbnail'])) { ?>style="background-image: url('<?php echo $guide['thumbnail']; ?>');" <?php } ?>>
						<div class="row">
							<div class="col-sm-12"><p class="title"><span class="label label-default label-small">Guide</span><br /><span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $guide['title']; ?></span></p><p class="small" style="text-shadow: 1px 1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, -1px -1px 0 #fff; color: #333 !important; margin-top: -9px;">Posted <?php echo $this->pylos_model->twitterdate($guide['timestamp']); ?></p></div>
						</div>
					</div>
				</a>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<hr />
		<?php } ?> 
		<?php if ($blocks !== false) { ?> 
		<?php if (!$single) { ?>
		<div class="row" id="blocks">
			<div class="col-md-3"><h1 style="margin: 0;"><i class="fa fa-puzzle-piece"></i></h1><h3 style="margin-top: 0;">Blocks</h3></div>
			<div class="col-md-8"><p>These are the building blocks of computational design that you can plug into your tool, analysis, or design process already made.</p></div>
		</div>			
		<?php } ?> 
		<div class="row grid-filter">
			<?php $i = 1; foreach ($blocks as $block) : if ($anon && $block['private'] == 1) continue; ?> 
			<div class="col-lg-3 col-md-4 col-sm-6 grid-filter-single"<?php if ($limit and $i > 8) { ?> style="display: none;"<?php } ?>>
				<a class="grid-link" href="/pylos/blocks/<?php echo $block['slug']; ?>">
					<div class="bs-callout grid-block block-<?php echo $block['type']; ?>" style="background-image: url('<?php echo $block['thumbnail']; ?>');">
						<div class="row">
							<div class="col-sm-12"><p class="title"><span class="label label-default label-small"><?php echo ucfirst($block['type']); ?></span><br /><span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $block['title']; ?></span></p></div>
						</div>
					</div>
				</a>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<hr />
		<?php } ?> 
		<?php if ($presentations !== false) { ?> 
		<?php if (!$single) { ?>
		<div class="row" id="presentations">
			<div class="col-md-3"><h1 style="margin: 0;"><i class="fa fa-television"></i></h1><h3 style="margin-top: 0;">Presentations</h3></div>
			<div class="col-md-8"><p>Pylos also has a variety of presentations and resources from past lunch and learns, meetings, workshops, and other events helping the firm share knowledge for high performance and computational design.</p></div>
		</div>			
		<?php } ?> 
		<div class="row grid-filter">
			<?php 
				$result = $this->pylos_model->get_data2('pylos_files',false,array('parenttype'=>'pylos_presentations'));
				if (count($result) < 1) {
					$p_thumbs = false;
				} else {
					$p_thumbs = array();
					foreach ($result as $i) {
						$p_thumbs[$i['parentid']] = $i['url'];
					}
				}
				$i = 1;
				foreach ($presentations as $presentation) : if ($anon && $presentation['private'] == 1) continue; ?> 
			<div class="col-lg-6 grid-filter-single"<?php if ($limit and $i > 4) { ?> style="display: none;"<?php } ?>>
				<a class="grid-link" href="/pylos/presentations/<?php echo $presentation['slug']; ?>">
					<div class="bs-callout grid-block block-presentation">
						<div class="row">
							<div class="col-xs-8"><p class="title"><span class="label label-default label-small">Presentation</span><br /><span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $presentation['title']; ?></span></p><p class="small" style="text-shadow: 1px 1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, -1px -1px 0 #fff; color: #333 !important; margin-top: -9px;">Posted <?php echo $this->pylos_model->twitterdate($presentation['timestamp']); ?></p></div>
							<div class="col-xs-4">
								<?php 
									if (isset($p_thumbs[$presentation['id']])) { 
										$presentation['url'] = $p_thumbs[$presentation['id']];
									} else {
										$presentation['url'] = '';
									}
								?>
								<div class="pylos-presentation-preview" style="background-image: url('<?php echo $presentation['url']; ?>');"></div>
							</div>

						</div>
					</div>
				</a>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<?php } ?> 
		<?php 
			if ($single === false) {
				if ($blocks === false || $guides === false || $presentations === false) { 
					$list = array('guides'=>$guides,'blocks'=>$blocks,'presentations'=>$presentations);
					foreach ($list as $k => $r) {
						if ($r !== false) unset($list[$k]);
						continue;
					}
					$statement = '';
					if (count($list) == 3) { 
						$statement = 'blocks, guides, or presentations';
					} elseif (count($list) == 1) { 
						$statement = $this->pylos_model->array_first($list);
					} else {
						$i = 0;
						foreach ($list as $k => $l) {
							$statement .= ($i == 0) ? $k.' or ': $k;
							$i++;
						}
					}
				}
			} else {
				if ($$single === false) $statement = $single;
			}
			if (isset($statement)) {
		?> 
		<div class="pylos-no-results">
			<h1>🤷</h1>
			<h3>We didn't find any <?php echo $statement; ?> to show.</h3>
			<p>
				<?php if ($anon) { ?>Some resources are not publicly available. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Getting to know you...');">Care to sign in? &rarr;</a></a>
				<?php } elseif ($single !== false) { ?>Your rotten luck is no cause for concern, how about looking at the <a href="<?php echo site_url('pylos/blocks'); ?>">blocks</a>, <a href="<?php echo site_url('pylos/guides'); ?>">guides</a>, or <a href="<?php echo site_url('pylos/presentations'); ?>">presentations</a> we do have or <a href="<?php echo site_url('pylos/blocks/create'); ?>">add the first block</a>?
				<?php } else { ?>Your rotten luck is no cause for concern, how about looking at the <a href="<?php echo site_url('pylos/blocks'); ?>">blocks</a>, <a href="<?php echo site_url('pylos/guides'); ?>">guides</a>, or <a href="<?php echo site_url('pylos/presentations'); ?>">presentations</a> we do have or <a href="<?php echo site_url('pylos/blocks/create'); ?>">add the first block</a>?<?php } ?>
			</p> 
		</div>
		<?php } ?>
		<!-- End Resource Listing -->
		<!--<div class="alert alert-warning">End Fancy Listing</div><!---->