<!-- Content Area -->
		<?php if (is_array($dependency)) { ?> 
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-magic"></i></h1>
			</div>
			<div class="col-lg-8">
				<h3 style="font-weight: 600;/* text-transform: none; */"><?php echo $dependency['title']; ?></h3>
				<h3 style="font-weight: 600;/* text-transform: none; */"><?php echo $dependency['excerpt']; ?></h3>
				<div class="smallchildren" style="display: none;"><?php echo $dependency['description']; ?><p>Version in Pylos last updated <?php echo $this->pylos_model->twitterdate($dependency['timestamp']); ?></p></div>
				<ul class="pylos-section-tags" style="padding:0;">
					<a href="#" onclick="$('.smallchildren').show(); return false;"><span class="fa fa-question-circle"></span>Learn More about <?php echo $dependency['title']; ?></a>
					<a href="<?php echo site_url('pylos/blocks/create'); ?>"><span class="fa fa-download"></span>Get <?php echo $dependency['title']; ?></a>
					<span>or</span>
					<a href="#" onclick="$('#pylos-edit-dependency').show('fast'); return false;"><span class="fa fa-pencil"></span>Help Update this Page about <?php echo $dependency['title']; ?></a>
				</ul>
			</div>
			<div class="col-lg-8 col-lg-offset-2">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Blocks</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">
					These are the building blocks that use <a href="<?php echo site_url('pylos/dependency/'.$tag); ?>"><?php echo urldecode($tag); ?></a> which you can plug into your tool, analysis, or design process already made.   
					<br />&nbsp;
				</h3>
			</div>
		</div>
		<!-- / Content Header --> 
		<div class="row" style="display:none;">
			<div class="col-lg-8">
				<h3 style="margin-top: 10px;"><?php echo $dependency['title']; ?> <span class="label label-dependency">Dependency</span></h3>
				<h3 style="font-weight: 600; text-transform: none;"><?php echo $dependency['excerpt']; ?><a href="#" onclick="$('.smallchildren').show(); $(this).hide(); return false;"> Learn more about <?php echo $dependency['title']; ?> &rarr;</a>
				</h3>
				<div class="smallchildren" style="display: none;"><?php echo $dependency['description']; ?><p>Version in Pylos last updated <?php echo $this->pylos_model->twitterdate($dependency['timestamp']); ?></p></div>
				<p style="font-size: 100%;"><a href="<?php echo $dependency['url']; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-arrow-down" aria-hidden="true"></i> Get <?php echo $dependency['title']; ?></a></p>
			</div>
		</div>
		<?php } 
			if (strtolower($slug) == 'dependency') { ?>
		<div id="pylos-edit-dependency" class="row" style="display: none;">
			<div class="col-sm-12">
				<div class="pylos-new-step" style="margin-bottom: 20px;">
				<form id="formeditdependency" class="form-horizontal" enctype="multipart/form-data">
					<?php if ($dependency) { ?><input id="pylos-edit-dependency-id" type="hidden" name="payload[dependencyid]" value="<?php echo $dependency['id']; ?>" /><?php } ?> 
					<input id="pylos-edit-dependency-slug" type="hidden" name="payload[slug]" value="<?php echo urldecode($tag); ?>" />
					<h3 style="margin-top: 0;">Build out this dependency!</h3>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to edit this. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
					<div class="row">
						<div class="col-sm-6">
							<label for="payload[title]" class="label-floating">Dependency Title</label>
							<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-dependency-title" name="payload[title]" placeholder="<?php echo urldecode($tag); ?>" value="<?php echo ($dependency) ? $dependency['title'] : urldecode($tag); ?>">
						</div>
						<div class="col-sm-6">
							<label for="payload[url]" class="label-floating">Download File URL (or link to Software Center)</label>
							<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-dependency-url" name="payload[url]" placeholder="<?php $this->pylos_model->echovar($dependency['url']); ?>" value="<?php $this->pylos_model->echovar($dependency['url']); ?>">
						</div>
						<div class="col-sm-12">
							<label for="payload[excerpt]" class="label-floating">Short and to the point, what does this do?</label>
							<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-dependency-excerpt" name="payload[excerpt]" placeholder="<?php $this->pylos_model->echovar($dependency['excerpt']); ?>" value="<?php $this->pylos_model->echovar($dependency['excerpt']); ?>">
							<label for="payload[description]" class="label-floating">Longer Description</label>
							<textarea type="text" class="pylos-summernote" id="pylos-edit-dependency-description" name="payload[description]" placeholder="Describe the step in simple and clear language, use bullet points too!"><?php $this->pylos_model->echovar($dependency['description']); ?></textarea>
						</div>
					</div>
					<div class="row" style="padding-top: 20px;">	
						<div class="col-sm-12">
							<div id="editdependencyfail" class="alert alert-danger " style="display: none;" role="alert">Make sure you are signed in and help us by supplying a short description...</div>
							<div id="editdependencysuccess" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
							<div id="editdependencyloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">depending...</div>
							<div id="editdependencybuttons">
								<div class="pull-right">
									<button type="reset" class="btn btn-default" onclick="$('#pylos-edit-dependency').hide('fast');">Reset and Close</button>
									<button type="button" class="btn btn-success" id="submitdependencyguide">Save changes</button>
								</div>
								<a href="/pylos/api/remove/pylos_guides/" class="btn btn-danger">Delete</a>
							</div>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
		<?php } ?> 

		<?php if (!is_array($dependency)) { ?>
		<!-- Hero Content Header --> 
		<div class="row hideonsearch pylos-section-hero" style="min-height: unset;">
			<div class="col-lg-2">
				<h1><i class="fa fa-puzzle-piece"></i></h1>
			</div>
			<div class="col-lg-8">
				<!--<h3 style="font-weight: 600;/* text-transform: none; */">Blocks</h3>-->
				<h3 style="font-weight: 600;/* text-transform: none; */">
					<?php if (strtolower($slug) == 'dependency') { ?>
						<a href="<?php echo site_url('pylos/dependency/'.$tag); ?>"><?php echo urldecode($tag); ?></a> is a tool.</h3><h3 style="font-weight: 600;/* text-transform: none; */">These are blocks that use and possibly require <a href="<?php echo site_url('pylos/dependency/'.$tag); ?>"><?php echo urldecode($tag); ?></a>. <a href="#" onclick="$('#pylos-edit-dependency').show('fast'); return false;">Help us by building out the Pylos record</a> if you are a power user and can share a description and install files for this dependency. 
					<?php } else { ?>These are the building blocks of computational design that you can plug into your tool, analysis, or design process already made.<?php } ?>
				</h3>
			</div>
			<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
				<span style="padding-left: 0;">Popular Themes: </span>
				<a href="<?php echo site_url('pylos/tags/energy'); ?>">energy</a>
				<a href="<?php echo site_url('pylos/tags/daylighting'); ?>">daylighting</a>
				<a href="<?php echo site_url('pylos/tags/materials'); ?>">materials</a>
				<a href="<?php echo site_url('pylos/tags/climate'); ?>">climate</a>
				<span>or</span>
				<a href="<?php echo site_url('pylos/blocks/create'); ?>"><span class="fa fa-plus"></span>Create a Block</a>
			</ul>
		</div>
		<!-- / Content Header --> 
		<?php } ?>
		<!-- Resources Grid -->
		<?php $this->load->view('app/pylos/templates/grid-combined', array('single'=>'blocks')); ?>
		<!-- Resources Grid -->

<!-- End Content Area -->



					</div><!--
					<div class="col-lg-3 col-md-12">
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

<script>
		function initsummernote(selector) {
			$(selector).summernote({
			  toolbar: [
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow']],
			    ['code', ['codeview']],
			  ]
			});
		};
		$('#submitdependencyguide').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editdependencybuttons').hide(); 
					$('#editdependencyfail').hide(); 
					$('#editdependencyloading').show();
				},
				url: "/pylos/api/update/pylos_dependency/<?php echo urldecode($tag) ?>",
				data: $("#formeditdependency").serialize(),
				statusCode: {
					200: function(data) {
						$('#editdependencyloading').hide(); 
						$('#editdependencysuccess').show();
						var response = JSON.parse(data); 
						$('#editdependencybuttons').show(); 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editdependencyloading').hide(); 
						$('#editdependencyfail').show();
						$('#editdependencybuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editdependencyloading').hide(); 
						$('#editdependencyfail').show();
						$('#editdependencybuttons').show(); 
					}
				}
			});
		});

</script>