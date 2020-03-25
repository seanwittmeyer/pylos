	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<div class="row">
					<div class="col-lg-5 col-md-5 hidden-md hidden-sm hidden-xs">
						<div class="headline-parent"><h3 class="text-left headline"><a href="<?php echo site_url("app/lemur"); ?>" style="color:#3e3f3a;">Forge</a></h3></div>
					</div>
					<?php $this->load->view('menu'); ?>
				</div>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-12 col-sm-12">
						<div class="headline-parent"><input id="livesearch" class="text-left headline" data-toggle="tooltip" data-placement="bottom" title="Type to filter this page or 'tab' to search all of Pylos..." onclick="this.select();" placeholder="<?php echo $contenttitle; ?>" value="Create a new Forge App" /></div>
						<!-- Content Area -->


		<div class="row">
			<div class="col-lg-11">

				<div>
				<form id="formnewblock" class="form-horizontal" enctype="multipart/form-data">
					<?php $newunique = sha1('pylos-'.microtime()); ?> 
					<input id="pylos-block-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
					<p>Create an app and activity for Revit in the cloud.</p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create your dataset. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<div class="form-group">
						<label for="payload[title]" class="col-sm-2 control-label">Give it a title</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="pylos_newdata_title" name="payload[title]" placeholder="Simple and clear!" value="HarvestProjectData"></div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="pylos_newblock_private" name="payload[private]"> Enable external use?
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="upload" class="col-sm-2 control-label">Dataset<br />(Zip File)</label>
						<div class="col-sm-10">
							<p class="small" style="margin-top: 20px;"><i class="fa fa-hand-peace-o" data-toggle="tooltip" data-placement="bottom" title="Top tip!"></i> <strong>Top Tip:</strong> Zip/compress all of your files in a single zip file including the data.csv file and any images and 3d (json) files. This works best if you zip the files and not a folder of files.</p>
							<input type="file" id="pylos-data-file" name="userfile" value="">
							<input type="hidden" id="pylos_newdata_file" name="payload[file]">
						</div>
					</div>

					<div class="form-group" style="padding-bottom: 30px;">
						<div class="col-sm-10 col-sm-offset-2">
							<div id="newblockfail" class="alert alert-danger " style="display: none;" role="alert">Not so fast, make sure the title is set and the file has uploaded before trying again.</div>
							<div id="newblocksuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
							<div id="newblockloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">uploading...</div>
							<div id="newblockbuttons">
								<button type="reset" class="btn btn-default" >Start Over</button>
								<button type="button" class="btn btn-success tt" id="submitnewblock">Save and Go!</button>
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
		$('#pylos-data-file').fileinput({
		    uploadUrl: "/designexplorer/api/uploaddata/<?php echo $newunique; ?>/file", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 1
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-data-file').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    $('#pylos_newdata_file').val(response.filename);
		});

		$('#submitnewblock').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#newblockbuttons').hide(); 
					$('#newblockfail').hide(); 
					$('#newblockloading').show();
				},
				url: "/app/lemur/create_app",
				data: $("#formnewblock").serialize(),
				statusCode: {
					200: function(data) {
						$('#newblockloading').hide(); 
						$('#newblocksuccess').show();
						var response = JSON.parse(data); 
						$('#newblockbuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.assign(response.result.url);
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
	</script>


					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->

