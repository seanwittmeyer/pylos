<?php 
	$heromenu = (isset($heromenu)) ? $heromenu : false; 
	$filter = (isset($filter)) ? $filter : false; 
	$fullwidth = (isset($fullwidth)) ? $fullwidth : false; 	
?>	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php $this->load->view('app/pylos/templates/menu-beta'); ?>
			</div>
			<!-- /sidebar -->
			<!-- Sidebar --
			<div class="col-sm-3">
							
				<h3>For PM's and PA's</h3>
				<span>Dive into how workshops, charettes, analysis, and computational design at the right times can push performance while enhancing design. Explore strategies and resources sorted by phase as well as our glossary to keep you up to speed with terminology.</span>
				
				<h3 class="headline">Phases</h3>
				<ul class="pylos-nav-phases nav nav-pills nav-stacked">
					<?php foreach ($phases as $i) { ?><li<?php if ($i['slug'] == $phase['slug']) { ?> class="active" style="margin-right: -40px;"<?php } ?>><a href="<?php echo site_url('pylos/phases/'.$i['slug']); ?>"><i class="fa fa-<?php echo $i['image']; ?>"></i> <?php echo $i['title']; ?></a></li><?php } ?>
				</ul>
				
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<!-- Page Editor --> 
				<div id="pylos-edit-phase" class="row" style="display: none;">
					<div class="col-sm-12">
						<div class="pylos-new-step" style="margin-bottom: 20px;">
						<form id="formeditphase" class="form-horizontal" enctype="multipart/form-data">
							<input id="pylos-edit-phase-id" type="hidden" name="payload[phaseid]" value="<?php echo $phase['id']; ?>" />
							<input id="pylos-edit-phase-slug" type="hidden" name="payload[slug]" value="<?php echo $phase['slug']; ?>" />
							<h3 style="margin-top: 0;">Build out this phase!</h3>
							<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to edit this. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
							<div class="row">
								<div class="col-sm-5">
									<label for="payload[title]" class="label-floating">Phase Title</label>
									<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-phase-title" name="payload[title]" placeholder="<?php echo $phase['slug']; ?>" value="<?php echo ($phase) ? $phase['title'] : $phase['slug']; ?>">
								</div>
								<div class="col-sm-5">
									<label for="payload[image]" class="label-floating"><a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">Font Awesome icon</a></label>
									<input type="text" class="form-control" style="margin-bottom: 10px;" data-toggle="tooltip" data-placement="left" title="excluding 'fa-' prefix" id="pylos-edit-phase-image" name="payload[image]" placeholder="<?php $this->pylos_model->echovar($phase['image']); ?>" value="<?php $this->pylos_model->echovar($phase['image']); ?>">
								</div>
								<div class="col-sm-2">
									<label for="payload[order]" class="label-floating">Order</label>
									<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-phase-order" name="payload[order]" placeholder="<?php $this->pylos_model->echovar($phase['order']); ?>" value="<?php $this->pylos_model->echovar($phase['order']); ?>">
								</div>
								<div class="col-sm-12">
									<label for="payload[excerpt]" class="label-floating">Short and to the point, what does this do?</label>
									<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-phase-excerpt" name="payload[excerpt]" placeholder="<?php $this->pylos_model->echovar($phase['excerpt']); ?>" value="<?php $this->pylos_model->echovar($phase['excerpt']); ?>">
									<label for="payload[description]" class="label-floating">Longer Description</label>
									<textarea type="text" class="pylos-summernote" id="pylos-edit-phase-description" name="payload[description]" placeholder="Describe the phase in simple and clear language, use bullet points too!"><?php $this->pylos_model->echovar($phase['description']); ?></textarea>
								</div>
							</div>
							<div class="row" style="padding-top: 20px;">	
								<div class="col-sm-12">
									<div id="editphasefail" class="alert alert-danger " style="display: none;" role="alert">Make sure you are signed in and help us by supplying a short description...</div>
									<div id="editphasesuccess" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
									<div id="editphaseloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">depending...</div>
									<div id="editphasebuttons">
										<div class="pull-right">
											<button type="reset" class="btn btn-default" onclick="$('#pylos-edit-phase').hide('fast');">Reset and Close</button>
											<button type="button" class="btn btn-success" id="submitphase">Save changes</button>
										</div>
										<a href="/pylos/api/remove/pylos_taxonomy/" class="btn btn-danger">Delete</a>
									</div>
								</div>
							</div>
						</form>
						</div>
					</div>
				</div>
				<!-- / Editor -->

				<!-- Pylos Home Category Feature -->
				<div class="row hideonsearch pylos-content-phase" style="min-height: unset;">
					<div class="col-md-2">
						<h1><i class="fa fa-<?php echo $phase['image']; ?>"></i></h1>
					</div>
					<div class="col-md-8">
						<h3><?php echo $phase['title']; ?></h3>
						<h3 style="font-weight: 600;"><?php echo $phase['excerpt']; ?></h3>
					</div>
					<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
						<span style="padding-left: 0;">See all:</span>
						<a href="#blocks"><i class="fa fa-puzzle-piece"></i>blocks</a>
						<a href="#guides"><i class="fa fa-mortar-board"></i>guides</a>
						<a href="#presentations"><i class="fa fa-television"></i>presentations</a>
						<a href="#datasets"><i class="fa fa-bar-chart"></i>datasets</a>
						<span>or</span>
						<a href="#" onclick="$('#pylos-edit-phase').show('fast'); return false;"><i class="fa fa-pencil"></i>edit this page</a>
					</ul>
					<div class="col-md-2">
						<h1><i class="fa fa-trophy"></i></h1>
						<h3 style="margin-top: .2em;">Strategies</h3>
					</div>
					<div class="col-md-10">
						<div class="row pylos-phase-strategies">
							<div class="col-md-12 panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php $i = 0; if (isset($themes) and is_array($themes)) { foreach ($themes as $theme) { ?> 
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="heading<?php echo $i; ?>">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapseOne" class="<?php if ($i>0) echo 'collapsed';?>">
											<i class="fa fa-plus pull-right"></i><i class="fa fa-minus pull-right"></i><?php echo $theme['title']; ?>
											</a>
										</h4>
									</div>
									<div id="collapse<?php echo $i; ?>" class="panel-collapse collapse<?php if ($i===0) echo ' in';?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" aria-expanded="true" style="">
										<div class="panel-body">
											<!-- Accordion Strategy Theme Body -->
											<div class="row" style="margin-left: -7px;">
												<span class="col-md-8"><?php echo $theme['excerpt']; ?></span>
											</div>
											<table class="table table-hover">
												<thead style="">
													<tr>
														<th>Strategy</th><th>Summary</th><th>Duration</th><th>Time</th><th>Link</th>
													</tr>
												</thead>
												<tbody><?php foreach ($theme['strategies'] as $strategy) { ?> 
													<tr><th scope="row"><a href="<?php echo site_url('pylos/strategies/'.$strategy['slug']); ?>"><?php echo $strategy['title']; ?></a></th><td><?php echo $strategy['excerpt']; ?></td><td>~<?php echo $strategy['duration']; ?></td><td>~<?php echo $strategy['time']; ?></td><td class="text-right"><a href="<?php echo site_url('pylos/strategies/'.$strategy['slug']); ?>"><i class="fa fa-arrow-right"></i></a></td></tr>
												<?php } ?></tbody>
											</table>
											<!-- / Accordion Strategy Theme Body -->
										</div>
									</div>
								</div>
								<?php $i++; }} ?>
							</div>
    
						</div>
					</div>
				</div>





				<!-- / Category Feature -->
				<div class="row">
					<div class="col-sm-<?php echo ($fullwidth) ? 12 : 9; ?>  col-sm-12">
						<div class="headline-parent"><?php if ($filter) { ?><input id="livesearch" class="text-left headline" data-toggle="tooltip" data-placement="bottom" title="Type to filter this page or 'tab' to search all of Pylos..." onclick="this.select();" placeholder="<?php echo $contenttitle; ?>" value="<?php echo $contenttitle; ?>" /><?php } else { ?><h1 class="headline"><?php echo $contenttitle; ?></h1><?php } ?></div>
						
						<!-- Resources Grid -->
						<?php $this->load->view('app/pylos/templates/grid-combined'); ?>
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

<script>
		$('#pageeditor').on('shown.bs.modal', function () {
			$('.cas-summernote').summernote({
			  toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['style']],
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow', 'picture']],
			    ['code', ['codeview']],
			    ['fullscreen', ['fullscreen']],
			  ],
			  callbacks: {
				  onImageUpload: function(files, editor, welEditable) {
				  	sendFile(files[0], this, welEditable);
				  }
			  }
			});
		});
		$('#submitphase').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editphasebuttons').hide(); 
					$('#editphasefail').hide(); 
					$('#editphaseloading').show();
				},
				url: "/pylos/api/update/pylos_taxonomy/<?php echo $phase['id']; ?>",
				data: $("#formeditphase").serialize(),
				statusCode: {
					200: function(data) {
						$('#editphaseloading').hide(); 
						$('#editphasesuccess').show();
						var response = JSON.parse(data); 
						$('#editphasebuttons').show(); 
						window.location.reload();
						//window.location.assign(response.result.url);
						//console.log(response.result.url);
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editphaseloading').hide(); 
						$('#editphasefail').show();
						$('#editphasebuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editphaseloading').hide(); 
						$('#editphasefail').show();
						$('#editphasebuttons').show(); 
					}
				}
			});
		});

</script>