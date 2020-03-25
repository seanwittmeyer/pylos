						<!-- Edit Area -->
	<div id="pylos-edit-presentation" class="row" style="display: none;">
		<div class="col-sm-12">
			<div class="pylos-new-step" style="margin-bottom: 20px;">
			<form id="formeditpresentation" class="form-horizontal" enctype="multipart/form-data">
				<input id="pylos-presentation-edit-id" type="hidden" name="payload[id]" value="<?php echo $presentation['id']; ?>" />
				<h3 style="margin-top: 0;">Edit this presentation</h3>
				<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to edit this. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in first? &rarr;</a></div><?php } ?>
				<div class="row">
					<div class="col-sm-6">
						<label for="payload[title]" class="label-floating">Title</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-presentation-edit-title" name="payload[title]" placeholder="<?php $this->pylos_model->echovar($presentation['title']); ?>" value="<?php $this->pylos_model->echovar($presentation['title']); ?>">
						<label for="payload[excerpt]" class="label-floating">Excerpt</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-presentation-edit-excerpt" name="payload[excerpt]" placeholder="<?php $this->pylos_model->echovar($presentation['excerpt']); ?>" value="<?php $this->pylos_model->echovar($presentation['excerpt']); ?>">
						<label for="payload[author]" class="label-floating">Author</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-presentation-edit-author" name="payload[author]" placeholder="<?php $this->pylos_model->echovar($presentation['author']); ?>" value="<?php $this->pylos_model->echovar($presentation['author']); ?>">
						<label for="payload[source]" class="label-floating">Source</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-presentation-edit-source" name="payload[source]" placeholder="<?php $this->pylos_model->echovar($presentation['source']); ?>" value="<?php $this->pylos_model->echovar($presentation['source']); ?>">
						<label for="payload[type]" class="label-floating">Presentation Type</label>
						<div class="" style="margin: 4px -10px;">
							<select name="payload[type]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
								<option value="Workshop"<?php if ($presentation['type'] == "Workshop") echo ' selected="selected"'; ?>>Workshop</option>
								<option value="Lunch and Learn"<?php if ($presentation['type'] == "Lunch and Learn") echo ' selected="selected"'; ?>>Lunch and Learn</option>
							</select>
						</div>
					</div>
					<div class="col-sm-6">
						<label class="label-floating">Manage Images</label>
						<div class="panzoomthumbs well well-image">
							<?php if (isset($images) && !empty($images)) { ?>
								<?php foreach ($images as $i) { ?><div style="display: inline-block;"><a href="/pylos/api/remove/pylos_files/<?php echo $i['id']; ?>" class="btn btn-danger btn-xs" style="position: absolute; margin: 5px;"><span class="fa fa-times" aria-hidden="true"></span> Remove</a><img class="panzoomreset" src="<?php echo $i['url']; ?>"></div><?php } ?> 
							<?php } ?>
							<div style="clear: both;"></div>
							<label for="userfile" class="label-floating" style="display: none;">Add New Images</label>
							<input type="file" id="pylos-presentation-edit-image" name="userfile" value="">
							<input type="hidden" id="pylos-presentation-edit-thumbnail" name="payload[thumbnail]" value="">
						</div>
						<label for="payload[video]" class="label-floating">Vimeo/YouTube Video URL</label>
						<input type="text" class="form-control" id="pylos-presentation-edit-video" name="payload[video]" placeholder="<?php $this->pylos_model->echovar($presentation['video']); ?>" value="<?php $this->pylos_model->echovar($presentation['video']); ?>">

	
					</div>
					
							<div class="col-sm-12">
								<label for="payload[description]" class="label-floating">Provide a great description of what your presentation is.</label>
								<textarea type="text" class="pylos-summernote" id="pylos-presentation-edit-description" name="payload[description]" placeholder="<?php $this->pylos_model->echovar(htmlentities($presentation['description'])); ?>"><?php $this->pylos_model->echovar(htmlentities($presentation['description'])); ?></textarea>
							</div>


				</div>
				<div class="row" style="padding-top: 20px;">	
					<div class="col-sm-12">
						<div id="editpresentationfail" class="alert alert-danger " style="display: none;" role="alert">Shoot, you aren't logged in or you have some blank fields...</div>
						<div id="editpresentationsuccess" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
						<div id="editpresentationloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">rewriting history...</div>
						<div id="editpresentationbuttons">
							<div class="pull-right">
								<button type="reset" class="btn btn-default" onclick="$('#pylos-edit-presentation').hide('fast');">Reset and Close</button>
								<button type="button" class="btn btn-success" id="submiteditpresentation>" onclick="submitedit('presentation','pylos_presentations');">Save changes</button>
							</div>
							<a href="/pylos/api/remove/pylos_presentations/<?php echo $presentation['id']; ?>" class="btn btn-danger">Delete</a>
						</div>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>


						<!-- End Edit Area -->
						<!-- Content Area -->
						<div class="row">
							<div class="col-lg-9 examples">
								<div class="meta meta-body">This <?php echo $presentation['type']; ?> resource <?php if ($presentation['author'] !== '') { ?>is by <?php echo $presentation['author']; ?> <?php if ($presentation['source'] !== '') { ?> (<a href="<?php echo $presentation['source']; ?>" target="_blank">source</a>) <?php } ?> and <?php } ?>was posted by <?php $presentation['__user'] = $this->ion_auth->user($presentation['user'])->row(); echo $presentation['__user']->first_name." ".$presentation['__user']->last_name; ?>, last updated <?php echo $this->pylos_model->twitterdate($presentation['timestamp']); ?>.</div>
								<h3 style="font-weight: 600; text-transform: none;"><?php echo $presentation['excerpt']; ?></h3>
								<?php echo $presentation['description']; ?>
								
								
								<!--
									<a id="revisions"></a>
								<h3>Revisions</h3>
								<p class="meta">We keep track of versions and changes so that you can ensure you have access to resources that work with your workflow as things change. </p>
								
								<?php if ($revisions) { 
									$revisions = array_reverse($revisions);
									$n = 2;
									?><li style="list-style: none;"><a href="<?php echo $presentation['file']; ?>"><span class="label label-primary">v1 - <?php echo date("F j", $presentation['timestamp']); ?></span></a> This is the original version of this resource.</li> 
									<?php foreach ($revisions as $r) {  
								?><li style="list-style: none;"><a href="<?php echo $r['url']; ?>"><span class="label label-primary">v<?php echo $n; ?> - <?php echo date("F j", $r['timestamp']); ?></span></a> <?php echo $r['description']; ?> <i>(by <?php $presentation['__user'] = $this->ion_auth->user($presentation['user'])->row(); echo $presentation['__user']->first_name." ".$presentation['__user']->last_name; ?>)</i></li>
								<?php $n++; } } else { ?>
								<li style="list-style: none;">
									<span class="label label-primary">v1</span> This is the only version of this resource. <a href="#" data-toggle="modal" data-target="#newrevision">Upload a new version?</a>
								</li>								
								<?php } ?> 
								<!-- -->
								<hr />
							</div>
							<div class="col-lg-3 ">
								<div class="meta meta-corner">
									<?php foreach (array('dependency'=>'dependencies','phase'=>'phases','stragegy'=>'strategies','tag'=>'tags','project'=>'projects') as $j => $k) { 
										foreach (${$k} as $tag) { ?>
										<a href="<?php echo site_url("pylos/$k/$tag"); ?>" class="<?php echo $k; ?>-corner" data-toggle="tooltip" data-placement="bottom" title="<?php echo ucfirst($j); ?>"><?php echo $tag; ?></a><?php } ?>
									<?php } ?> 
								</div>
							</div>
						</div>
		
						<!-- End Content Area -->
					</div>
				</div>

			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->


	<!-- New diagram modal -->
	<div class="modal fade" id="newrevision" tabindex="-1" role="dialog" aria-labelledby="newrevision" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Add a file revision for <?php echo $presentation['title']; ?></h4>
				</div>
				<div class="modal-body">
				<form id="formnewrevision" class="form-horizontal" enctype="multipart/form-data">
					<?php $newunique = sha1('pylos-'.microtime()); ?> 
					<input id="pylos-presentation-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
					<p>Revisions allow us to keep past versions of resources as an archive and update resources as software and workflows evolve. Thanks for helping keep Pylos up to date by making our resources better!</p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to add a revision. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<div class="form-group">
						<label for="payload[description]" class="col-sm-2 control-label">What's new?</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="pylos_newpresentation_description" name="payload[description]" placeholder="One sentence summary that tells us what changed"></div>
					</div>
					<div class="form-group">
						<label for="upload" class="col-sm-2 control-label">File</label>
						<div class="col-sm-10">
							<p class="small">You need to share a zip file of the resource you are sharing. Please format your file/resource so that it can be used on a variety of projects and avoid uploading large files with unnecessary geometry.</p>
							<input type="file" id="pylos-presentation-file" name="userfile" value="">
							<input type="hidden" id="pylos_newpresentation_file" name="payload[url]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
							<input type="hidden" name="payload[parenttype]" value="pylos_presentation">
							<input type="hidden" name="payload[parentid]" value="<?php echo $presentation['id']; ?>">
						</div>
					</div>
				</form>
				</div>
				<div class="modal-footer">
					<div id="revisionnewfail" class="alert alert-danger " style="display: none;" role="alert">Hmm, make sure the file uploaded and that your description is filled out...</div>
					<div id="revisionnewsuccess" class="alert alert-success " style="display: none;" role="alert">Well that was easy, content posted.</div>
					<div id="revisionnewloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">doing work...</div>
					<div id="revisionnewbuttons">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-success tt" id="submitnewrevision">Add revision</button>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /New diagram modal -->

	<script>
		function initedit() {
			$('#pylos-edit-presentation').show('fast');
			initfileinput('#pylos-presentation-edit-image','#pylos-presentation-edit-thumbnail','<?php echo $presentation['id']; ?>','pylos_presentations');
		}
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
		function initfileinput(selector,thumbselector,parentid=false,parenttype=false) {
			if (parentid === false) {
				apiUploadUrl = "/pylos/api/uploadimage/<?php echo $newunique; ?>/thumbnail";
			} else {
				apiUploadUrl = "/pylos/api/uploadimage/update/thumbnail/"+parentid+"/"+parenttype;
			}
			$(selector).fileinput({
			    uploadUrl: apiUploadUrl, // server upload action
			    uploadAsync: true,
			    showUpload: false, // hide upload button
			    showRemove: false, // hide remove button
			    minFileCount: 1,
			    maxFileCount: 1
			}).on("filebatchselected", function(event, files) {
			    // trigger upload method immediately after files are selected
			    $(selector).fileinput("upload");
			}).on('fileuploaded', function(event, data, previewId, index) {
			    var form = data.form, files = data.files, extra = data.extra,
			        response = data.response, reader = data.reader;
			    console.log(response.filename + ' has been uploaded');
			    $(thumbselector).val(response.filename);
			});
		}
		function submitedit(selector,targettype) {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#edit'+selector+'success').hide(); 
					$('#edit'+selector+'fail').hide(); 
					$('#edit'+selector+'loading').show();
				},
				url: '/pylos/api/update/'+targettype+'/<?php echo $presentation['id']; ?>',
				data: $('#formedit'+selector).serialize(),
				statusCode: {
					200: function(data) {
						$('#edit'+selector+'loading').hide(); 
						$('#edit'+selector+'success').show();
						var response = JSON.parse(data); 
						$('#edit'+selector+'buttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#edit'+selector+'loading').hide(); 
						$('#edit'+selector+'fail').show();
						$('#edit'+selector+'buttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#edit'+selector+'loading').hide(); 
						$('#edit'+selector+'fail').show();
						$('#edit'+selector+'buttons').show(); 
					}
				}
			});
		}

		$('#newrevision').on('shown.bs.modal', function () {
			$('#pylos-presentation-file').fileinput({
			    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/file", // server upload action
			    uploadAsync: true,
			    showUpload: false, // hide upload button
			    showRemove: false, // hide remove button
			    minFileCount: 1,
			    maxFileCount: 1
			}).on("filebatchselected", function(event, files) {
			    // trigger upload method immediately after files are selected
			    $('#pylos-presentation-file').fileinput("upload");
			}).on('fileuploaded', function(event, data, previewId, index) {
			    var form = data.form, files = data.files, extra = data.extra,
			        response = data.response, reader = data.reader;
			    console.log(response.filename + ' has been uploaded');
			    $('#pylos_newpresentation_file').val(response.filename);
			});
		});
		$('#submitnewrevision').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#revisionnewbuttons').hide(); 
					$('#revisionnewfail').hide(); 
					$('#revisionnewloading').show();
				},
				url: "/pylos/api/create/revision",
				data: $("#formnewrevision").serialize(),
				statusCode: {
					200: function(data) {
						$('#revisionnewloading').hide(); 
						$('#revisionnewsuccess').show();
						var response = JSON.parse(data); 
						$('#revisionnewbuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#revisionnewloading').hide(); 
						$('#revisionnewfail').show();
						$('#revisionnewbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#newpresentationloading').hide(); 
						$('#newpresentationfail').show();
						$('#newpresentationbuttons').show(); 
					}
				}
			});
		});

	</script>