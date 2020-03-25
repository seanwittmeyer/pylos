<!-- Content Area -->

	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php $this->load->view('app/pylos/templates/menu-beta'); ?>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-12">
						<div class="headline-parent"><h1 class="headline headline-title"><?php echo $contenttitle; ?></h1></div>
						<div class="row">
							<div class="col-lg-11">
								<div>
								<form id="formnewelement" class="form-horizontal" enctype="multipart/form-data">
									<?php $newunique = sha1('pylos-'.microtime()); ?> 
									<input id="pylos-element-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
									<input id="pylos-element-type" type="hidden" name="payload[type]" value="strategies" />
									<div class="row"><div class="col-md-10">
										<p>Strategies are ways we impact performance and affect designs as they develop. They range from credits for certification programs like WELL and LEED to general sustainability themes.</p>
									</div></div>
									<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create strategies. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>
				
									<div class="form-group">
										<label for="payload[title]" class="col-md-3 control-label">Give it a title</label>
										<div class="col-md-9"><input type="text" class="form-control" id="pylos_newelement_title" name="payload[title]" placeholder="Simple and clear!"></div>
									</div>
									<div class="form-group">
										<label for="payload[excerpt]" class="col-md-3 control-label">One liner description</label>
										<div class="col-md-9"><input type="text" class="form-control" id="pylos_newelement_excerpt" name="payload[excerpt]" placeholder="Placeholder for excerpt"></div>
									</div>
									<div class="form-group">
										<label for="payload[description]" class="col-md-3 control-label">Tell us all about it, what it does, and how to use it.</label>
										<div class="col-md-9"><textarea type="text" class="pylos-summernote" id="pylos_newelement_description" name="payload[description]" placeholder="Describe this and include how it works and any information people will need to use it..."></textarea></div>
									</div>
									<div class="form-group">
										<label for="payload[questions]" class="col-md-3 control-label">Core questions this strategy or type of study address.</label>
										<div class="col-md-9"><textarea type="text" class="pylos-summernote" id="pylos_newelement_questions" name="payload[questions]" placeholder="Using bullet points, what are the core questions that this strategy or type of study strive to address?"></textarea></div>
									</div>
									<div class="form-group">
										<label for="themes" class="col-md-3 control-label">Themes</label>
										<div class="col-md-9">
											<p class="small">What themes does this strategy relate to? Examples include performance themes like energy and biophilia or certification categories like LEED's materials credits.</p>
											<input type="text" class="form-control" name="payload[themes]" id="pylos_newelement_tags" placeholder="analysis, energyplus, etc...">
											<span class="small">If you don't see the tags you want in the list above, you can add them here. Separate by commas.</span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" style="padding-top: 16px;">Duration</label>
										<div class="col-sm-3" style="padding: 0 5px;">
											<select name="payload[duration]" class="selectpicker btn-sm" data-width="100%" data-size="5">
												<option value="1 day">1 day</option>
												<option value="2 days">2 days</option>
												<option value="3 days">3 days</option>
												<option value="1 week">1 week</option>
												<option value="2 weeks">2 weeks</option>
												<option value="3 weeks">3 weeks</option>
												<option value="1 month">1 month</option>
												<option value="2 months">2 months</option>
												<option value="ongoing">Ongoing</option>
											</select>
											<p class="small  col-sm-12">How long would this take to complete?</p>
										</div>
										<label class="col-md-1 control-label" style="padding-top: 16px;">Time</label>
										<div class="col-sm-3" style="padding: 0 5px;">
											<select name="payload[time]" class="selectpicker btn-sm" data-width="100%" data-size="5">
												<option value="4 hours">4 hours</option>
												<option value="8 hours">8 hours</option>
												<option value="20 hours">20 hours</option>
												<option value="40 hours">40 hours</option>
												<option value="60 hours">60 hours</option>
												<option value="80 hours">80 hours</option>
												<option value="1 hour/week">1 hour/week</option>
												<option value="4 hours/week">4 hours/week</option>
												<option value="8 hours/week">8 hours/week</option>
											</select>
											<p class="small col-sm-12">About how many hours would someone need?</p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" style="padding-top: 16px;">Phases</label>
										<div class="col-md-9" style="padding: 0 5px;">
											<select name="payload[phases][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" multiple data-size="5">
												<option value="programming">Programming</option>
												<option value="pre-design">Pre Design</option>
												<option value="schematic-design">Schematic / Concepts</option>
												<option value="design-development">Design Development</option>
												<option value="construction-documents">Construction Docs</option>
												<option value="construction-administration">Construction Admin</option>
												<option value="procurement">Procurement</option>
												<option value="post-construction">Post Construction</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" style="padding-top: 16px;">Difficulty</label>
										<div class="col-sm-3" style="padding: 0 5px;">
											<select name="payload[difficulty]" class="selectpicker btn-sm" data-width="100%" data-size="5">
												<option value="Anyone">Easy, Anyone</option>
												<option value="Design Team">Easy, Design/Project Team</option>
												<option value="Client">Easy, Client</option>
												<option value="In-house">Medium, PPT / In-house Consultant</option>
												<option value="Consultant">Difficult, Consultant</option>
												<option value="Specialty">>Difficult, Specialst Needed</option>
											</select>
											<p class="small  col-sm-12">How long would this take to complete?</p>
										</div>
										<label class="col-md-1 control-label" for="payload[staff]" style="padding-top: 16px;">Staff</label>
										<div class="col-sm-5" style="padding: 0 5px;">
											<div class="col-md-12"><input type="text" class="form-control" id="pylos_newelement_staff" name="payload[staff]" placeholder=""></div>
											<p class="small  col-sm-12">Who are our in-house resources?</p>
										</div>
									</div>
									
									<div class="form-group">
										<label for="payload[url]" class="col-md-3 control-label">Related Link</label>
										<div class="col-md-9"><input type="text" class="form-control" id="pylos_newelement_source" name="payload[source]" placeholder="http://"></div>
									</div>
									
									<div class="form-group">
										<label for="upload" class="col-md-3 control-label">Image</label>
										<div class="col-md-9">
											<p class="small">Add some images to help illustrate what your resource does. If you are uploading a grasshopper or dynamo definition, add a screenshot so people can get a preview of how the resource works. We will use the <strong>first image you upload</strong> as the thumbnail on Pylos.</p>
											<input type="file" id="pylos-element-images" name="userfile" value="">
											<input type="hidden" id="pylos_newelement_thumbnail" name="payload[image]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 30px;">
										<div class="col-md-9 col-sm-offset-2">
											<div id="newelementfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, this didn't save, make sure everything above is filled and try again.</div>
											<div id="newelementsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
											<div id="newelementloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">strategizing...</div>
											<div id="newelementbuttons">
												<button type="reset" class="btn btn-default" >Reset</button>
												<button type="button" class="btn btn-success tt" id="submitnewelement">Create it!</button>
											</div>
										</div>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>

<!-- End Content Area -->
	<!-- /File Uploader -->
	<script>
		$('#pylos_newelement_tags').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); foreach ($tags['theme'] as $k => $t) $taglist[] = array('value'=>$k,'text'=>$t); echo json_encode($taglist); ?>
			
		});
		$('#pylos_newelement_dependencies').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); foreach ($tags['dependency'] as $k => $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
		});
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
		$('#pylos-element-images').fileinput({
		    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/thumbnail", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 10
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-element-images').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    //$('#imgheader').css('background-image','url('+response.filename+')');
		    $('#pylos_newelement_thumbnail').val(response.filename);
		});

		function sendFile(file, el, welEditable) {
            data = new FormData();
            data.append("userfile", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "/pylos/api/uploadimage/<?php echo $newunique; ?>/image",
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

		$('#submitnewelement').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#newelementbuttons').hide(); 
					$('#newelementfail').hide(); 
					$('#newelementloading').show();
				},
				url: "/pylos/api/create/strategies",
				data: $("#formnewelement").serialize(),
				statusCode: {
					200: function(data) {
						$('#newelementloading').hide(); 
						$('#newelementsuccess').show();
						var response = JSON.parse(data); 
						$('#newelementbuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.assign(response.result.url + '?firstrun=true');
						//console.log(response.result.url + '?firstrun=true');
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#newelementloading').hide(); 
						$('#newelementfail').show();
						$('#newelementbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#newelementloading').hide(); 
						$('#newelementfail').show();
						$('#newelementbuttons').show(); 
					}
				}
			});
		});
	</script>


