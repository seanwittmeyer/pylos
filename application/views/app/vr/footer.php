	<!-- Create Element Popup -->
	<?php // setup
		if (isset($id)) {
			$hosttype = $type; 
			$hostid = $id;
		}
		/*	asset types
			
			cube: 6 images
			cubestereo: 12 images
			revitcloud: single linear cube map
			revitcloudstereo: two linear cube maps, separate images
			cubemap: linear cube map, normal image set
			cubemapstereo: 12 cubes linear in one image, gearvr/vray
		x	equirectangular: single image, 2:1 ratio
			stereoequirectangular: stacked equirectangular, google webvr standard.
			
		*/
			
		foreach (array('equirectangular') as $type) { ?> 
	<div class="modal fade" id="asset_<?php echo $type; ?>" tabindex="-1" role="dialog" aria-labelledby="create<?php echo $type; ?>" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">VR / Add Asset</h4>
					<p>
						<?php if ($type == 'equirectangular') { ?>Adding a equirectuangular panorama image? Great, start by giving your image a name and tag it if you so choose. You can choose which folder to place it in the next window.
						<?php } elseif ($type == 'cube') { ?>Some sample text to be replaced. 
						<?php } ?>
					</p>
				</div>
				<div class="modal-body">
				<form id="form<?php echo $type; ?>" >
				<?php if ($type == 'equirectangular') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Asset Title</label>
						<div class=""><input type="text" class="form-control" id="cas-def-title" name="payload[title]" placeholder="The Grand Portico"></div>
					</div>
					<div class="form-group">
						<label for="payload[description]" class="">Description (optional)</label>
						<div class=""><textarea type="text" class="form-control" id="vr-def-excerpt" name="payload[description]" placeholder="Brief description if you so choose..."></textarea></div>
					</div>
					<div class="form-group">
						<label for="upload" class="">Image - <?php echo $type; ?></label>
						<div class=""><input type="file" id="vr-asset" name="userfile" value=""></div>
						<input type="hidden" id="cas-tax-fileurl" name="payload[img][header][url]" value="<?php echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
					</div>
					<h4>Add to a folder?</h4>
					<p>Select folders you want to add this sucker to.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Folder</label>
								<div class="col-sm-10">
									<select name="payload[folder][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List taxonomy
										$list = $this->shared->list_bytype('folder');
										if ($list === false) { echo '<option disabled>No folder to display.</option>'; } else {
										foreach ($list as $a) { echo '<option value="'.$a['id'].'">'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				</div><!-- /modal body -->
				<div class="modal-footer">
					<div id="<?php echo $type; ?>fail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the asset didn't save, make sure everything above is filled and try again.</div>
					<div id="<?php echo $type; ?>success" class="alert alert-success " style="display: none;" role="alert">Great success, the asset has been posted, here we go...</div>
					<div id="<?php echo $type; ?>loading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">doing things...</div>
					<div id="<?php echo $type; ?>buttons">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submit<?php echo $type; ?>" data-toggle="tooltip" title="This is ">Add this asset!</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<?php } //end foreach ?>
	<!-- End New Popup -->
	
	<!-- /File Uploader -->
	<script>
		$('#pageeditor').on('shown.bs.modal', function () {
			$('.vr-summernote').summernote({
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
		function vrSendFile(file, el, welEditable) {
            data = new FormData();
            data.append("userfile", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "/app/vr/uploadimage",
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
		$('#pageupload').on('shown.bs.modal', function () {
		    $("#cas-tax-file").fileinput({
		        uploadAsync: false,
				url: "/app/vr/uploadimage", // remove X or it wont work...
		        uploadExtraData: function() {
		            return {
		                id: '<?php echo $id; ?>',
		                type: 'page'
		            };
		        }
		    });
		});

		$('#cas-tax-file').fileinput({
		    uploadUrl: "/app/vr/uploadimage", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 1
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#cas-tax-file').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    $('#imgheader').css('background-image','url('+response.filename+')');
		    $('#cas-tax-fileurl').val(response.filename);
		});
		$('#submiteditor').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editorbuttons').hide(); 
					$('#editorfail').hide(); 
					$('#editorloading').show();
				},
				url: "/api/update/page/<?php echo $id; ?>",
				data: $("#formeditor").serialize(),
				statusCode: {
					200: function(data) {
						$('#editorloading').hide(); 
						$('#editorsuccess').show();
						var response = JSON.parse(data); 
						$('#editorbuttons').show(); 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editorloading').hide(); 
						$('#editorfail').show();
						$('#editorbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editorloading').hide(); 
						$('#editorfail').show();
						$('#editorbuttons').show(); 
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
				url: "/api/update/page/<?php echo $id; ?>/header", 
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
