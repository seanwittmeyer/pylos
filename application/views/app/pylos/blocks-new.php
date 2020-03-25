<!-- Content Area -->


		<div class="row">
			<div class="col-lg-11">

				<div>
				<form id="formnewblock" class="form-horizontal" enctype="multipart/form-data">
					<?php $newunique = sha1('pylos-'.microtime()); ?> 
					<input id="pylos-block-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
					<p>You made something and want to share it, now let's get a good title so people can find your resource. <strong>If your block is code/script based</strong>, <a href="<?php echo site_url('pylos/blocks/create/code'); ?>">try creating it here instead</a></p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create your block. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<div class="form-group">
						<label for="payload[title]" class="col-md-3 control-label">Give it a title</label>
						<div class="col-md-9"><input type="text" class="form-control" id="pylos_newblock_title" name="payload[title]" placeholder="Simple and clear!"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="col-md-3 control-label">One liner</label>
						<div class="col-md-9"><input type="text" class="form-control" id="pylos_newblock_excerpt" name="payload[excerpt]" placeholder="One sentence summary that tells us what is does"></div>
					</div>
					<div class="form-group">
						<label for="payload[description]" class="col-md-3 control-label">Tell us all about it, what it does, and how to use it.</label>
						<div class="col-md-9"><textarea type="text" class="pylos-summernote" id="pylos_newblock_description" name="payload[description]" placeholder="Describe this and include how it works and any information people will need to use it..."></textarea></div>
					</div>

					<div class="form-group">
						<label for="upload" class="col-md-3 control-label">Images</label>
						<div class="col-md-9">
							<p class="small">Add some images to help illustrate what your resource does. If you are uploading a grasshopper or dynamo definition, add a screenshot so people can get a preview of how the resource works. We will use the <strong>first image you upload</strong> as the thumbnail on Pylos.</p>
							<input type="file" id="pylos-block-images" name="userfile" value="">
							<input type="hidden" id="pylos_newblock_thumbnail" name="payload[thumbnail]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
						</div>
					</div>
					<div class="alert alert-heading" role="heading">Does this resource need to be kept private? We want to open up and share resources we make but sometimes the content needs to stay within the firm. Check the box if we should limit access to folks at ZGF.</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-md-9">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="pylos_newblock_private" checked="checked" name="payload[private]"> Limit access to this resource
								</label>
							</div>
						</div>
					</div>
					<div class="alert alert-heading" role="heading">Help others find your block by tagging it and telling us if there are any dependencies (such as a plugin like TT Toolbox, Diva, or Ladybug/Honeybee).</div>
					<div class="form-group">
						<label for="payload[tags]" class="col-md-3 control-label">Tags</label>
						<div class="col-md-9"><input type="text" class="form-control" name="payload[tags]" id="pylos_newblock_tags" placeholder="search or add your own tags..."><span class="small">If you don't see the tags you want in the list above, you can add them here.</span></div>
					</div>
					<div class="form-group">
						<label for="payload[dependencies]" class="col-md-3 control-label">Dependencies</label>
						<div class="col-md-9"><input type="text" class="form-control" name="payload[dependencies]" id="pylos_newblock_dependencies" placeholder="search or add your own dependencies..."><span class="small">If you don't see the tags you want in the list above, you can add them here.</span></div>
					</div>
					<div class="form-group">
						<label for="payload[project]" class="col-md-3 control-label">Project Number (Vision record)</label>
						<div class="col-md-9"><input type="text" class="form-control" name="payload[project]" id="pylos_newblock_project" placeholder="P23796.00"><span class="small">Adding a project helps folks learn how certain things were done if they know a project. Include the office designation and phase .00. Leave this blank if the resource was intended to be generic.</span></div>
					</div>
					<div class="alert alert-heading" role="heading">Does the block relate to any strategies (daylight analysis) or certifications (LEED)?</div>
					<div class="form-group">
						<label for="payload[strategies]" class="col-md-3 control-label">Strategies</label>
						<div class="col-md-9"><input type="text" class="form-control" name="payload[strategies]" id="pylos_newblock_strategies" placeholder="search strategies..."><span class="small">If you don't see the strategies you want in the list above, <a target="_blank" href="<?php echo site_url('pylos/strategies/create'); ?>">you can add them here</a>.</span></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" style="padding-top: 16px;">Block Type</label>
						<div class="col-md-9" style="padding: 0 5px;">
							<select name="payload[type]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
								<option value="grasshopper">Grasshopper</option>
								<option value="dynamo">Dynamo</option>
								<option value="excel">Excel</option>
								<option value="script">Script/Code</option>
								<option value="maxscript">Maxscript</option>
								<option value="python">Python Script</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label" style="padding-top: 16px;">Block Phase</label>
						<div class="col-md-9" style="padding: 0 5px;">
							<select name="payload[phase][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" multiple data-size="5">
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
						<label for="upload" class="col-md-3 control-label">File</label>
						<div class="col-md-9">
							<p class="small">You need to share a zip file of the resource you are sharing. Please format your file/resource so that it can be used on a variety of projects and avoid uploading large files with unnecessary geometry.</p>
							<input type="file" id="pylos-block-file" name="userfile" value="">
							<input type="hidden" id="pylos_newblock_file" name="payload[file]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
						</div>
					</div>

					<div class="alert alert-heading" role="heading">Was this made by someone else? We'd like to give attribution to any folks that have contributed work, albeit indirectly.</div>
					<div class="form-group">
						<label for="payload[author]" class="col-md-3 control-label">Author</label>
						<div class="col-md-9"><input type="email" class="form-control" id="pylos_newblock_author" name="payload[author]" placeholder="Leave this empty if it was you"></div>
					</div>
					<div class="form-group">
						<label for="payload[source]" class="col-md-3 control-label">Source Link</label>
						<div class="col-md-9">
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-globe" aria-hidden="true"></i></div>
								<input type="url" class="form-control" id="pylos_newblock_source" name="payload[source]" placeholder="https://">
							</div>
						</div>
					</div>

					<div class="form-group" style="padding-bottom: 30px;">
						<div class="col-md-9 col-sm-offset-3">
							<div id="newblockfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the block didn't save, make sure everything above is filled and try again.</div>
							<div id="newblocksuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
							<div id="newblockloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">working...</div>
							<div id="newblockbuttons">
								<button type="reset" class="btn btn-default" >Reset</button>
								<button type="button" class="btn btn-success tt" id="submitnewblock">Save changes</button>
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
		$('#pylos_newblock_tags').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); asort($tags['tag']); foreach ($tags['tag'] as $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
			
		});
		$('#pylos_newblock_dependencies').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); foreach ($tags['dependency'] as $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
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
		$('#blockupload').on('shown.bs.modal', function () {
		    $("#pylos-block-images").fileinput({
		        uploadAsync: false,
				url: "/pylos/api/uploadimage/<?php echo $newunique; ?>", 
		        uploadExtraData: function() {
		            return {
		                id: '<?php //echo $id; ?>',
		                type: 'block',
		                unique: '<?php echo $newunique; ?>'
		            };
		        }
		    });
		});

		$('#pylos-block-images').fileinput({
		    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/thumbnail", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 10
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-block-images').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    //$('#imgheader').css('background-image','url('+response.filename+')');
		    $('#pylos_newblock_thumbnail').val(response.filename);
		});

		$('#pylos-block-file').fileinput({
		    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/file", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 1
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-block-file').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    $('#pylos_newblock_file').val(response.filename);
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
		$('#submitnewblock').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#newblockbuttons').hide(); 
					$('#newblockfail').hide(); 
					$('#newblockloading').show();
				},
				url: "/pylos/api/create/block",
				data: $("#formnewblock").serialize(),
				statusCode: {
					200: function(data) {
						$('#newblockloading').hide(); 
						$('#newblocksuccess').show();
						var response = JSON.parse(data); 
						$('#newblockbuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.assign(response.result.url + '?firstrun=true');
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#newblockloading').hide(); 
						$('#newblockfail').show();
						$('#newblockbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#newblockloading').hide(); 
						$('#newblockfail').show();
						$('#newblockbuttons').show(); 
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

