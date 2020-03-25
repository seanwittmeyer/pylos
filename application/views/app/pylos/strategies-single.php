<!-- Content Area -->

	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php $this->load->view('app/pylos/templates/menu-beta');?>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-12">
						<div class="headline-parent"><h1 class="headline headline-title">Strategies</h1></div>
						<!-- Page Editor --> 
						<div id="pylos-edit-strategy" class="row" style="display: none;">
							<div class="col-sm-12">
								<div class="pylos-new-step" style="margin-bottom: 20px;">
								<form id="formeditstrategy" class="form-horizontal" enctype="multipart/form-data">
									<input id="pylos-edit-strategy-id" type="hidden" name="payload[strategyid]" value="<?php echo $strategy['id']; ?>" />
									<input id="pylos-edit-strategy-slug" type="hidden" name="payload[slug]" value="<?php echo $strategy['slug']; ?>" />
									<h3 style="margin-top: 0;">Build out this strategy!</h3>
									<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to edit this. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
									<div class="row">
										<div class="col-sm-6" style="margin-top: 10px;">
											<label for="payload[title]" class="label-floating">Strategy Title</label>
											<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-strategy-title" name="payload[title]" placeholder="<?php echo $strategy['slug']; ?>" value="<?php echo ($strategy) ? $strategy['title'] : $strategy['slug']; ?>">
										</div>
										<div class="col-sm-2">
											<label for="payload[duration]" class="label-floating">Duration</label>
											<div class="" style="margin: 4px -10px;" data-toggle="tooltip" data-placement="left" title="approximately how many billable hours would a PM expect?" >
												<select name="payload[duration]" class="selectpicker btn-sm" data-width="100%" data-size="5">
													<option value="1 day"<?php if ($strategy['duration'] == "") echo " selected"; ?>>1 day</option>
													<option value="2 days"<?php if ($strategy['duration'] == "2 days") echo " selected"; ?>>2 days</option>
													<option value="3 days"<?php if ($strategy['duration'] == "3 days") echo " selected"; ?>>3 days</option>
													<option value="1 week"<?php if ($strategy['duration'] == "1 week") echo " selected"; ?>>1 week</option>
													<option value="2 weeks"<?php if ($strategy['duration'] == "2 weeks") echo " selected"; ?>>2 weeks</option>
													<option value="3 weeks"<?php if ($strategy['duration'] == "3 weeks") echo " selected"; ?>>3 weeks</option>
													<option value="1 month"<?php if ($strategy['duration'] == "1 month") echo " selected"; ?>>1 month</option>
													<option value="2 months"<?php if ($strategy['duration'] == "2 months") echo " selected"; ?>>2 months</option>
													<option value="ongoing"<?php if ($strategy['duration'] == "ongoing") echo " selected"; ?>>ongoing</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<label for="payload[time]" class="label-floating">Time</label>
											<div class="" style="margin: 4px -10px;" data-toggle="tooltip" data-placement="left" title="approximately how long would the whole process take?" >
												<select name="payload[time]" class="selectpicker btn-sm" data-width="100%" data-size="5">
													<option value="4 hours"<?php if ($strategy['time'] == "4 hours") echo " selected"; ?>>4 hours</option>
													<option value="8 hours"<?php if ($strategy['time'] == "8 hours") echo " selected"; ?>>8 hours</option>
													<option value="20 hours"<?php if ($strategy['time'] == "20 hours") echo " selected"; ?>>20 hours</option>
													<option value="40 hours"<?php if ($strategy['time'] == "40 hours") echo " selected"; ?>>40 hours</option>
													<option value="60 hours"<?php if ($strategy['time'] == "60 hours") echo " selected"; ?>>60 hours</option>
													<option value="80 hours"<?php if ($strategy['time'] == "80 hours") echo " selected"; ?>>80 hours</option>
													<option value="1 hour/week"<?php if ($strategy['time'] == "1 hour/week") echo " selected"; ?>>1 hour/week</option>
													<option value="4 hours/week"<?php if ($strategy['time'] == "4 hours/week") echo " selected"; ?>>4 hours/week</option>
													<option value="8 hours/week"<?php if ($strategy['time'] == "8 hours/week") echo " selected"; ?>>8 hours/week</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<label for="payload[difficulty]" class="label-floating">Difficulty</label>
											<div class="" style="margin: 4px -10px;" data-toggle="tooltip" data-placement="left" title="who generally does this type of study?" >
												<select name="payload[difficulty]" class="selectpicker btn-sm" data-width="100%" data-size="5">
													<option value="Anyone"<?php if ($strategy['difficulty'] == "Anyone") echo " selected"; ?>>Easy, Anyone</option>
													<option value="Design Team"<?php if ($strategy['difficulty'] == "Design Team") echo " selected"; ?>>Easy, Design/Project Team</option>
													<option value="Client"<?php if ($strategy['difficulty'] == "Client") echo " selected"; ?>>Easy, Client</option>
													<option value="In-house"<?php if ($strategy['difficulty'] == "In-House") echo " selected"; ?>>Medium, PPT / In-house Consultant</option>
													<option value="Consultant"<?php if ($strategy['difficulty'] == "Consultant") echo " selected"; ?>>Difficult, Consultant</option>
													<option value="Specialty"<?php if ($strategy['difficulty'] == "Specialty") echo " selected"; ?>>Difficult, Specialst Needed</option>
												</select>
											</div>
										</div>
										<div class="col-sm-12">
											<label for="payload[excerpt]" class="label-floating">Short and to the point, what is this strategy?</label>
											<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-strategy-excerpt" name="payload[excerpt]" placeholder="<?php $this->pylos_model->echovar($strategy['excerpt']); ?>" value="<?php $this->pylos_model->echovar($strategy['excerpt']); ?>">
											<label for="payload[staff]" class="label-floating">Who are our in-house experts or consultants?</label>
											<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-edit-strategy-staff" name="payload[staff]" placeholder="<?php $this->pylos_model->echovar($strategy['staff']); ?>" value="<?php $this->pylos_model->echovar($strategy['staff']); ?>">
											<label for="payload[description]" class="label-floating">Longer Description</label>
											<textarea type="text" class="pylos-summernote" id="pylos-edit-strategy-description" name="payload[description]" placeholder="Describe the phase in simple and clear language, use bullet points too!"><?php $this->pylos_model->echovar($strategy['description']); ?></textarea>
											<label for="payload[questions]" class="label-floating">Core Questions</label>
											<textarea type="text" class="pylos-summernote" id="pylos-edit-strategy-questions" name="payload[questions]" placeholder="Using bullet points, what are the core questions that this strategy or type of study strive to address?"><?php $this->pylos_model->echovar($strategy['questions']); ?></textarea>
										</div>
									</div>
									<div class="row" style="padding-top: 20px;">	
										<div class="col-sm-12">
											<div id="editstrategyfail" class="alert alert-danger " style="display: none;" role="alert">Make sure you are signed in and help us by supplying a short description...</div>
											<div id="editstrategysuccess" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
											<div id="editstrategyloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">strategizing...</div>
											<div id="editstrategybuttons">
												<div class="pull-right">
													<button type="reset" class="btn btn-default" onclick="$('#pylos-edit-strategy').hide('fast');">Reset and Close</button>
													<button type="button" class="btn btn-success" id="submitstrategy">Save changes</button>
												</div>
												<a href="/pylos/api/remove/pylos_strategies/<?php echo $strategy['id']; ?>" class="btn btn-danger">Delete</a>
											</div>
										</div>
									</div>
								</form>
								</div>
							</div>
						</div>
						<!-- / Editor -->
						<!-- strategy info -->
						<div class="row pylos-section-strategy-masthead">
							<div class="col-sm-7"><h1 style="font-size: 4em;margin-top: 0;line-height: .8em;"><?php echo $strategy['title']; ?></h1></div>
							<div class="col-sm-5 hide">
								<div class="row">
									<div class="col-sm-4"><div class="strategy-image"></div></div>
									<div class="col-sm-8"><div class="strategy-image"></div></div>
								</div>
							</div>
						</div>
						<div class="row pylos-section-strategy-description">
							<div class="col-sm-7">
							<h2 style="margin: 13px 0;"><?php echo $strategy['excerpt']; ?> <a href="#" onclick="$('#pylos-edit-strategy').show('fast'); return false;"><span class="fa fa-pencil"></span> </a></h2>
							<div class="clear"></div>
							<li><i class="fa fa-money"></i> ~<?php echo $strategy['time']; ?></li>
							<li><i class="fa fa-calendar-check-o"></i> ~<?php echo $strategy['duration']; ?></li>
							<li><i class="fa fa-hand-o-right"></i> <?php echo $strategy['difficulty']; ?></li>
							<div class="clear"></div>
							<li><i class="fa fa-user"></i> <?php echo $strategy['staff']; ?></li>

						</div>
							<div class="col-sm-5 hide">
								<div class="row">
									<div class="col-sm-4" style="visibility: hidden;"><div class="strategy-image"></div></div>
									<div class="col-sm-4"><div class="strategy-image"></div></div>
									<div class="col-sm-4"><div class="strategy-image"></div></div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row pylos-section-strategy-context">
							<div class="col-md-6 keyterms">
								<h1 style="margin: 0;"><i class="fa fa-crosshairs"></i></h1>
								<h3 style="margin-top: 0;">Key Terms &amp; Metrics</h3>
								<div class="row">
									<div class="col-sm-4"><a><span>LCA</span>Life Cycle Assessment</a></div>
									<div class="col-sm-4"><a><span>GWP</span>Global Warming Potential</a></div>
									<div class="col-sm-4"><a><span>SCM</span>Supplementary Cementitious Materials</a></div>
									<div class="col-sm-4"><a><span>EPD</span>Environmental Product Declarations</a></div>
								</div>
							</div>
							<div class="col-md-6 questions">
								<h1 style="margin: 0;"><i class="fa fa-lightbulb-o"></i></h1>
								<h3 style="margin-top: 0;">Core Questions</h3>
								<?php echo $strategy['questions']; ?>
							</div>
						</div>
						<hr>
						<div class="row pylos-section-strategy-tools"></div>
						<hr>
						<!-- end strategy info -->
						<div class="row pylos-section-hero pylos-section-strategy" style="min-height: unset; display: none;">
							<div class="col-lg-2">
								<h1><i class="fa fa-trophy"></i></h1>
							</div>
							<div class="col-lg-8">
								<h3 style="font-weight: 600;/* text-transform: none; */"><?php echo $strategy['title']; ?></h3>
								<h3 style="font-weight: 600;/* text-transform: none; */"><?php echo $strategy['excerpt']; ?></h3>
								<ul>
									<li>Time: ~<?php echo $strategy['time']; ?></li>
									<li>Duration: ~<?php echo $strategy['duration']; ?></li>
								</ul>
							</div>
							<div class="col-lg-10 col-lg-offset-2 smallchildren" style=""><div><?php echo $strategy['description']; ?></div><p>This was last updated <?php echo $this->pylos_model->twitterdate($strategy['timestamp']); ?></p></div>
							<ul class="col-lg-10 col-lg-offset-2 pylos-section-tags">
								<a href="#" onclick="$('#pylos-edit-strategy').show('fast'); return false;"><span class="fa fa-pencil"></span>Help Update this Page</a>
							</ul>
						</div>
					</div>
				</div>
				<!-- Resources Grid -->
				<?php $this->load->view('app/pylos/templates/grid-combined'); ?>
				<!-- Resources Grid -->



			</div>
		</div>
		
	</div>

<!-- End Content Area -->
	<!-- /File Uploader -->
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

		function sendFile(file, el, welEditable) {
            data = new FormData();
            data.append("userfile", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "/pylos/api/uploadimage/0/image", // THIS NEEDS TO BE UPDATED WITH THE TOKEN AND CSRF KEY
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
	                //var url = JSON.parse(data);
	                alert(url);
                    //editor.insertImage(welEditable, url);
                    $(el).summernote('editor.insertImage', url);
                    console.log(url+' has been uploaded and inserted into summernote. bam!');
                }
            });
        }

		$('#submitstrategy').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editstrategybuttons').hide(); 
					$('#editstrategyfail').hide(); 
					$('#editstrategyloading').show();
				},
				url: "/pylos/api/update/pylos_strategies/<?php echo $strategy['id']; ?>",
				data: $("#formeditstrategy").serialize(),
				statusCode: {
					200: function(data) {
						$('#editstrategyloading').hide(); 
						$('#editstrategysuccess').show();
						var response = JSON.parse(data); 
						$('#editstrategybuttons').show(); 
						window.location.reload();
						//window.location.assign(response.result.url);
						//console.log(response.result.url);
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editstrategyloading').hide(); 
						$('#editstrategyfail').show();
						$('#editstrategybuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editstrategyloading').hide(); 
						$('#editstrategyfail').show();
						$('#editstrategybuttons').show(); 
					}
				}
			});
		});
	</script>


