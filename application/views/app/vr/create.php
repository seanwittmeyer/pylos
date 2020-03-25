	<?php $type = 'equirectangular'; ?>
	
	<!-- General Content Block -->
	<div class="container">
		<div class="row">
			<!-- Left Nav -->
			<div class="col-md-8">
				<h1>VR / Add a Tour</h2></h1>
				<p class="lead">Adding a tour to Builder is pretty simple, all you need is your panorama and a title to get started.</p>
				<p class="top-nospace">Tours are VR experiences you can post and share. Whether you have single panorama or many, tours are designed to be a collection of panoramas, or moments, that you can share. Adding hotspots is easy and can allow you to add labels or links to connect your moments together. And tours are set up with WebVR so anyone can explore, from headsets like Gear VR and Daydream to simple Google Cardboard and even on your phone/computer.</p>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<!-- Form -->
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
										$list = $this->shared->list_bytype('vr_folders');
										if ($list === false) { echo '<option disabled>No foldersoo to display.</option>'; } else {
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
			<!-- /Form -->
		</div>
	</div>
	<!-- /General Content Block -->



	<script>
		$('.embedly-card').imagesLoaded().progress( function() {
			$('.embedly-card').masonry();
		});
	</script>
