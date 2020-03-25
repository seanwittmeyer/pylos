<!-- Content Area -->


		<div class="row">
			<div class="col-md-11">

				<div>
				<form id="formnewguide" class="form-horizontal" enctype="multipart/form-data">
					<?php $newunique = sha1('pylos-'.microtime()); ?> 
					<input id="pylos-guide-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
					<p>You made something and want to share it, now let's get a good title so people can find your resource.</p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create your guide. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<div class="form-group">
						<label for="payload[title]" class="col-sm-3 control-label">Give it a title</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="pylos_newguide_title" name="payload[title]" placeholder="Simple and clear!"></div>
					</div>
					<div class="form-group">
						<label for="payload[question]" class="col-sm-3 control-label">At the end of this guide, you will know...</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="pylos_newguide_question" name="payload[question]" placeholder="How do I run and interpret daylight analysis for a space?"></div>
					</div>
					<div class="form-group">
						<label for="payload[why]" class="col-sm-3 control-label">What makes this important/useful?</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="pylos_newguide_why" name="payload[why]" placeholder="Knowing the ASE andn sDA for a space can tell us how much glare we need to address and how much useable daylight is coming into our space."></div>
					</div>
					<div class="form-group">
						<label for="payload[conclusion]" class="col-sm-3 control-label">Conclusion</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="pylos_newguide_conclusion" name="payload[conclusion]" placeholder="Nice work! You now know how to run basic daylighting analysis and use some simple metrics to improve the spaces you are working on."></div>
					</div>
					<!--
					<div class="form-group">
						<label for="payload[description]" class="col-sm-3 control-label">Tell us all about it, what it does, and how to use it.</label>
						<div class="col-sm-9"><textarea type="text" class="pylos-summernote" id="pylos_newguide_description" name="payload[description]" placeholder="Describe this and include how it works and any information people will need to use it..."></textarea></div>
					</div>
					-->
					<div class="form-group">
						<label for="payload[video]" class="col-sm-3 control-label">Vimeo/YouTube Video URL</label>
						<div class="col-sm-9"><input type="text" class="form-control" id="pylos_newguide_video" name="payload[video]" placeholder="https://vimeo.com/288393864"></div>
					</div>
					<div class="form-group">
						<label for="upload" class="col-sm-3 control-label">Images</label>
						<div class="col-sm-9">
							<p class="small">Add some images to help illustrate what your resource does. If you are uploading a grasshopper or dynamo definition, add a screenshot so people can get a preview of how the resource works. We will use the <strong>first image you upload</strong> as the thumbnail on Pylos.</p>
							<input type="file" id="pylos-guide-images" name="userfile" value="">
							<input type="hidden" id="pylos_newguide_thumbnail" name="payload[thumbnail]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
						</div>
					</div>
					<div class="alert alert-heading" role="heading">Help others find your guide by tagging it and telling us if there are any dependencies (such as a plugin like TT Toolbox, Diva, or Ladybug/Honeybee).</div>
					<div class="form-group">
						<label for="payload[tags]" class="col-sm-3 control-label">Tags</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="payload[tags]" id="pylos_newguide_tags" placeholder="analysis, energyplus, etc..."><span class="small">If you don't see the tags you want in the list above, you can add them here.</span></div>
					</div>
					<div class="form-group">
						<label for="payload[tags]" class="col-sm-3 control-label">Related Dependencies and Tools</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="payload[dependencies]" id="pylos_newguide_dependencies" placeholder="analysis, energyplus, etc..."><span class="small">Tell us the tools your guide relates to so people can find it!</span></div>
					</div>
					<div class="form-group">
						<label for="payload[project]" class="col-sm-3 control-label">Project Number</label>
						<div class="col-sm-9"><input type="text" class="form-control" onchange="function(){this.value = parseFloat(this.value).toFixed(2);" name="payload[project]" id="pylos_newguide_project" placeholder="23796.udan"><span class="small">Adding a project helps folks learn how certain things were done if they know a project. Leave this blank if the resource was intended to be generic.</span></div>
					</div>
					<div class="alert alert-heading" role="heading">Does the block relate to any strategies (daylight analysis) or certifications (LEED)?</div>
					<div class="form-group">
						<label for="payload[strategies]" class="col-sm-3 control-label">Strategies</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="payload[strategies]" id="pylos_newblock_strategies" placeholder="search strategies..."><span class="small">If you don't see the strategies you want in the list above, <a target="_blank" href="<?php echo site_url('pylos/strategies/create'); ?>">you can add them here</a>.</span></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" style="padding-top: 16px;">Difficulty Level</label>
						<div class="col-sm-9" style="padding: 0 5px;">
							<select name="payload[difficulty]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
								<option value="Easy">Basic/Easy</option>
								<option value="Moderate">Moderate</option>
								<option value="Advanced">Advanced</option>
								<option value="Expert">Expert</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" style="padding-top: 16px;">How much time will this take?</label>
						<div class="col-sm-9" style="padding: 0 5px;">
							<select name="payload[time]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
								<option value="less than 30 minutes">Less than 30 minutes</option>
								<option value="30 min to an hour">30 min to an hour</option>
								<option value="1-2 hours">1-2 hours</option>
								<option value="a day">A day</option>
								<option value="more than a day">More than a day</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" style="padding-top: 16px;">Related Blocks</label>
						<div class="col-sm-9" style="padding: 0 5px;">
							<select name="payload[blocks][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" multiple data-size="10">
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
					<div class="alert alert-heading" role="heading">Does this guide need to be kept private? We want to open up and share resources we make but sometimes the content needs to stay within the firm. Check the box if we should limit access to folks at ZGF.</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="pylos_newguide_private" name="payload[private]"> Limit access to this resource
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="upload" class="col-sm-3 control-label">File</label>
						<div class="col-sm-9">
							<p class="small">You need to share a zip file of the resource you are sharing. Please format your file/resource so that it can be used on a variety of projects and avoid uploading large files with unnecessary geometry.</p>
							<input type="file" id="pylos-guide-file" name="userfile" value="">
							<input type="hidden" id="pylos_newguide_file" name="payload[file]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
						</div>
					</div>

					<div class="form-group" style="padding-bottom: 30px;">
						<div class="col-sm-9 col-sm-offset-3">
							<div id="newguidefail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the guide didn't save, make sure everything above is filled and try again.</div>
							<div id="newguidesuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
							<div id="newguideloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">learning about your guide...</div>
							<div id="newguidebuttons">
								<button type="reset" class="btn btn-default" >Reset</button>
								<button type="button" class="btn btn-success tt" id="submitnewguide">Save changes</button>
							</div>
						</div>
					</div>
				</form>
				</div>



			</div>
		</div>

<!-- End Content Area -->
	<!-- /File Uploader -->
	<script>
		$('#pylos_newguide_tags').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); asort($tags['tag']); foreach ($tags['tag'] as $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
		});
		$('#pylos_newguide_dependencies').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); asort($tags['dependency']); foreach ($tags['dependency'] as $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
		});
		$('#pylos_newblock_strategies').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); foreach ($strategies as $t) $taglist[] = array('value'=>$t['slug'],'text'=>$t['title']); echo json_encode($taglist); ?>
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
		$('#guideupload').on('shown.bs.modal', function () {
		    $("#pylos-guide-images").fileinput({
		        uploadAsync: false,
				url: "/pylos/api/uploadimage/<?php echo $newunique; ?>", 
		        uploadExtraData: function() {
		            return {
		                id: '<?php //echo $id; ?>',
		                type: 'guide',
		                unique: '<?php echo $newunique; ?>'
		            };
		        }
		    });
		});

		$('#pylos-guide-images').fileinput({
		    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/thumbnail", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 10
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-guide-images').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    //$('#imgheader').css('background-image','url('+response.filename+')');
		    $('#pylos_newguide_thumbnail').val(response.filename);
		});

		$('#pylos-guide-file').fileinput({
		    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/guide", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 1
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-guide-file').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    $('#pylos_newguide_file').val(response.filename);
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
		$('#submitnewguide').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#newguidebuttons').hide(); 
					$('#newguidefail').hide(); 
					$('#newguideloading').show();
				},
				url: "/pylos/api/create/guides",
				data: $("#formnewguide").serialize(),
				statusCode: {
					200: function(data) {
						$('#newguideloading').hide(); 
						$('#newguidesuccess').show();
						var response = JSON.parse(data); 
						$('#newguidebuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.assign(response.result.url + '?firstrun=true');
						//console.log(response.result.url + '?firstrun=true');
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#newguideloading').hide(); 
						$('#newguidefail').show();
						$('#newguidebuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#newguideloading').hide(); 
						$('#newguidefail').show();
						$('#newguidebuttons').show(); 
					}
				}
			});
		});
		$('#submitupload').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#uploadbuttons').hide(); 
					$('#uploadfail').hide(); 
					$('#uploadloading').show();
				},
				url: "/api/update/page/header", 
				data: $("#formupload").serialize(),
				statusCode: {
					200: function(data) {
						$('#uploadloading').hide(); 
						$('#uploadsuccess').show();
						var response = JSON.parse(data); 
						$('#uploadbuttons').show(); 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#uploadloading').hide(); 
						$('#uploadfail').show();
						$('#uploadbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#uploadloading').hide(); 
						$('#uploadfail').show();
						$('#uploadbuttons').show(); 
					}
				}
			});
		});
	</script>


					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->

