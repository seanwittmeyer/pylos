<?php $newunique = sha1('pylos-'.microtime()); ?> 
	<!-- Content Area -->
	<div class="row">
		<div class="col-sm-12 examples">
			<div class="meta meta-body" style="margin-bottom: 20px;">This guide was last updated by <?php $guide['__user'] = $this->ion_auth->user($guide['user'])->row(); echo $guide['__user']->first_name." ".$guide['__user']->last_name; ?>, last updated <?php echo $this->pylos_model->twitterdate($guide['timestamp']); ?>.</div>
		</div>
	</div>
	<div id="pylos-edit-guide" class="row" style="display: none;">
		<div class="col-sm-12">
			<div class="pylos-new-step" style="margin-bottom: 20px;">
			<form id="formeditguide" class="form-horizontal" enctype="multipart/form-data">
				<input id="pylos-guide-edit-id" type="hidden" name="payload[id]" value="<?php echo $guide['id']; ?>" />
				<h3 style="margin-top: 0;">Edit this guide</h3>
				<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to edit this. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
				<div class="row">
					<div class="col-sm-6">
						<label for="payload[title]" class="label-floating">Title</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-guide-edit-title" name="payload[title]" placeholder="<?php $this->pylos_model->echovar($guide['title']); ?>" value="<?php $this->pylos_model->echovar($guide['title']); ?>">
						<label for="payload[question]" class="label-floating">At the end of this guide, you will know...</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-guide-edit-question" name="payload[question]" placeholder="<?php $this->pylos_model->echovar($guide['question']); ?>" value="<?php $this->pylos_model->echovar($guide['question']); ?>">
						<label for="payload[why]" class="label-floating">What makes this important/useful?</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-guide-edit-why" name="payload[why]" placeholder="<?php $this->pylos_model->echovar($guide['why']); ?>" value="<?php $this->pylos_model->echovar($guide['why']); ?>">
						<label for="payload[conclusion]" class="label-floating">Conclusion</label>
						<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-guide-edit-conclusion" name="payload[conclusion]" placeholder="<?php $this->pylos_model->echovar($guide['conclusion']); ?>" value="<?php $this->pylos_model->echovar($guide['conclusion']); ?>">
						<label for="payload[difficulty]" class="label-floating">Difficulty</label>
						<div class="" style="margin: 4px -10px;">
							<select name="payload[difficulty]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
								<option value="Easy"<?php if ($guide['difficulty'] == "Easy") echo ' selected="selected"'; ?>>Basic/Easy Difficulty</option>
								<option value="Moderate"<?php if ($guide['difficulty'] == "Moderate") echo ' selected="selected"'; ?>>Moderate Difficulty</option>
								<option value="Advanced"<?php if ($guide['difficulty'] == "Advanced") echo ' selected="selected"'; ?>>Advanced Difficulty</option>
								<option value="Expert"<?php if ($guide['difficulty'] == "Expert") echo ' selected="selected"'; ?>>Expert Difficulty</option>
							</select>
						</div>
						<label for="payload[time]" class="label-floating">Time to complete?</label>
						<div class="" style="margin: 4px -10px;">
							<select name="payload[time]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
								<option value="less than 30 minutes"<?php if ($guide['time'] == "less than 30 minutes") echo ' selected="selected"'; ?>>Less than 30 minutes</option>
								<option value="30 min to an hour"<?php if ($guide['time'] == "30 min to an hour") echo ' selected="selected"'; ?>>30 min to an hour</option>
								<option value="1-2 hours"<?php if ($guide['time'] == "1-2 hours") echo ' selected="selected"'; ?>>1-2 hours</option>
								<option value="a day"<?php if ($guide['time'] == "a day") echo ' selected="selected"'; ?>>A day</option>
								<option value="more than a day"<?php if ($guide['time'] == "more than a day") echo ' selected="selected"'; ?>>More than a day</option>
							</select>
						</div>

					</div>
					<div class="col-sm-6">
						<label for="" class="label-floating">Manage Images</label>
						<div class="panzoomthumbs well well-image">
							<?php if (isset($images) && !empty($images)) { ?>
								<?php foreach ($images as $i) { ?><div style="display: inline-block;"><a href="/pylos/api/remove/pylos_files/<?php echo $i['id']; ?>" class="btn btn-danger btn-xs" style="position: absolute; margin: 5px;"><span class="fa fa-times" aria-hidden="true"></span> Remove</a><img class="panzoomreset" src="<?php echo $i['url']; ?>"></div><?php } ?> 
							<?php } ?>
							<input type="file" id="pylos-guide-edit-image" name="userfile" value="">
							<input type="hidden" id="pylos-guide-edit-thumbnail" name="payload[thumbnail]" value="">

						</div>
						<label for="payload[video]" class="label-floating">Vimeo/YouTube Video URL</label>
						<input type="text" class="form-control" id="pylos-guide-edit-video" name="payload[video]" placeholder="<?php $this->pylos_model->echovar($guide['video']); ?>" value="<?php $this->pylos_model->echovar($guide['video']); ?>">


					</div>
				</div>
				<div class="row" style="padding-top: 20px;">	
					<div class="col-sm-12">
						<div id="editguidefail" class="alert alert-danger " style="display: none;" role="alert">Shoot, the step text/description is required...</div>
						<div id="editguidesuccess" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
						<div id="editguideloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">rewriting history...</div>
						<div id="editguidebuttons">
							<div class="pull-right">
								<button type="reset" class="btn btn-default" onclick="$('#pylos-edit-guide').hide('fast');">Reset and Close</button>
								<button type="button" class="btn btn-success" id="submiteditguide>" onclick="submiteditguide();">Save changes</button>
							</div>
							<a href="/pylos/api/remove/pylos_guides/<?php echo $guide['id']; ?>" class="btn btn-danger">Delete</a>
						</div>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
	<div class="pylos-guides row">
		<div class="col-sm-9 examples">
			<!-- Video and Image Intro -->
			<?php if (isset($guide['video']) && !empty($guide['video'])) { ?> 
			<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; height: auto; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='<?php echo $this->pylos_model->videoembedurl($guide['video']); ?>' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
			<?php } elseif (isset($images) && !empty($images)) { ?><img src="<?php echo $images[0]['url']; ?>" class="guide-content-image" /><?php } ?> 
			<h3>At the end of this guide, you will know...</h3>
			<p class="guide-content"><?php echo $guide['question']; ?></p>
			
			<h3>What makes this important/useful?</h3>
			<p><?php echo $guide['why']; ?></p>
		</div>
		<div class="col-sm-3">
			<div class="panel panel-default"><div class="panel-body"><i class="fa fa-tachometer" aria-hidden="true"></i> &nbsp; <strong><?php echo $guide['difficulty']; ?></strong> difficulty</div></div>
			<div class="panel panel-default"><div class="panel-body"><i class="fa fa-th-list" aria-hidden="true"></i> &nbsp; This guide has <strong><?php echo count($steps); ?></strong> steps</div></div>
			<div class="panel panel-default"><div class="panel-body"><i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp; You'll need <strong><?php echo $guide['time']; ?></strong></div></div>
			<div class="btn-group" style="letter-spacing: normal; text-transform: none;">
				<?php if (isset($files[0]['url']) && !empty ($files[0]['url'])) { ?> 
				<a href="<?php echo $files[0]['url']; ?>" class="btn btn-primary" style="letter-spacing: normal;">Download</a>
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<?php } else { ?> 
				<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Guide Tools <span class="caret"></span>
				</button>
				<?php } ?> 
				<ul class="dropdown-menu">
					<li class="dropdown-header" style="font-weight: normal;">Guide<br />Posted <?php echo date("F j, Y \a\\t g:i a", $guide['timestamp']); ?></li>
					<li><a href="#" onclick="initedit(); return false;"><span class="fa fa-pencil" aria-hidden="true"></span> Edit this Guide</a></li>
					<li role="separator" class="divider"></li>
					<li class="dropdown-header" style="font-weight: normal;">By clicking delete, you will <br />delete this block and all <br />revision files in Pylos. <br />No undo, use this wisely :)</li>
					<li><a href="/pylos/api/remove/pylos_guides/<?php echo $guide['id']; ?>"><span class="fa fa-times" aria-hidden="true"></span> Delete</a></li>
				</ul>
			</div>
		</div>
		</form>
		<hr style="clear: both;" />
		<div class="col-sm-12">
			<!-- Start Steps -->
			<?php $n = 1; if (is_array($steps)) { foreach ($steps as $step) { ?>

			<!-- Start add step -->
			<div id="pylos-new-step-<?php echo $n; ?>" class="row" style="display: none;">
				<div class="col-sm-12">
					<div class="pylos-new-step">
					<form id="formnewstep<?php echo $n; ?>" class="form-horizontal" enctype="multipart/form-data">
						<input id="pylos-step-unique-<?php echo $n; ?>" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
						<input id="pylos-step-order-<?php echo $n; ?>" type="hidden" name="payload[order]" value="<?php echo $n; ?>" />
						<input id="pylos-step-parentid-<?php echo $n; ?>" type="hidden" name="payload[parentid]" value="<?php echo $guide['id']; ?>" />
						<input id="pylos-step-parenttype-<?php echo $n; ?>" type="hidden" name="payload[parenttype]" value="pylos_guides" />
						<h3 style="margin-top: 0;">Add a step</h3>
						<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create steps. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
						<div class="row">
							<div class="col-sm-6">
								<label for="payload[video]" class="label-floating">Add screenshots or images to this step</label>
								<div class="well well-image">
									<input type="file" id="pylos-step-image-<?php echo $n; ?>" name="userfile" value="">
									<input type="hidden" id="pylos-step-thumbnail-<?php echo $n; ?>" name="payload[thumbnail]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
								</div>
								<label for="payload[video]" class="label-floating">Use a Vimeo/YouTube video instead</label>
								<input type="text" class="form-control" id="pylos-step-video-<?php echo $n; ?>" name="payload[video]" placeholder="https://vimeo.com/288393864">

							</div>
							<div class="col-sm-6">
								<label for="payload[title]" class="label-floating">Optional Title</label>
								<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-step-title-<?php echo $n; ?>" name="payload[title]" placeholder="Simple title, optional">
								<label for="payload[text]" class="label-floating">Step text/description</label>
								<textarea type="text" class="pylos-summernote" id="pylos-step-text-<?php echo $n; ?>" name="payload[text]" placeholder="Describe the step in simple and clear language, use bullet points too!"></textarea>
							</div>
						</div>
						<div class="row" style="padding-top: 20px;">	
							<div class="col-sm-12">
								<div id="newstepfail<?php echo $n; ?>" class="alert alert-danger " style="display: none;" role="alert">Shoot, the step text/description is required...</div>
								<div id="newstepsuccess<?php echo $n; ?>" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
								<div id="newsteploading<?php echo $n; ?>" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">stepping it up...</div>
								<div id="newstepbuttons<?php echo $n; ?>">
									<button type="reset" class="btn btn-default" onclick="$('#pylos-new-step-<?php echo $n; ?>').hide('fast');">Reset and Close</button>
									<button type="button" class="btn btn-success tt" id="submitnewguide<?php echo $n; ?>" onclick="submitnewstep('<?php echo $n; ?>');">Save changes</button>
								</div>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
			<div id="pylos-edit-step-<?php echo $n; ?>" class="row" style="display: none;">
				<div class="col-sm-12">
					<div class="pylos-new-step">
					<form id="formeditstep<?php echo $n; ?>" class="form-horizontal" enctype="multipart/form-data">
						<h3 style="margin-top: 0;">Edit step</h3>
						<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to edit this. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
						<div class="row">
							<div class="col-sm-6">
								<label for="payload[video]" class="label-floating">Manage screenshots and images</label>
								<div class="panzoomthumbs well well-image">
									<?php if (isset($step['images']) && !empty($step['images'])) { ?>
										<?php foreach ($step['images'] as $i) { ?><div style="display: inline-block;"><a href="/pylos/api/remove/pylos_files/<?php echo $i['id']; ?>" class="btn btn-danger btn-xs" style="position: absolute; margin: 5px;"><span class="fa fa-times" aria-hidden="true"></span> Remove</a><img class="panzoomreset" src="<?php echo $i['url']; ?>"></div><?php } ?> 
									<?php } ?>
									<input type="file" id="pylos-step-edit-image-<?php echo $n; ?>" name="userfile" value="">
									<input type="hidden" id="pylos-step-edit-thumbnail-<?php echo $n; ?>" name="payload[thumbnail]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
								</div>
								<label for="payload[video]" class="label-floating">Vimeo/YouTube Video URL</label>
								<input type="text" class="form-control" id="pylos-step-video-<?php echo $n; ?>" name="payload[video]" placeholder="<?php $this->pylos_model->echovar($step['video']); ?>" value="<?php $this->pylos_model->echovar($step['video']); ?>">

							</div>
							<div class="col-sm-6">
								<label for="payload[title]" class="label-floating">Optional Title</label>
								<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-step-title-<?php echo $n; ?>" name="payload[title]" placeholder="<?php $this->pylos_model->echovar($step['title']); ?>" value="<?php $this->pylos_model->echovar($step['title']); ?>">
								<label for="payload[text]" class="label-floating">Step text/description</label>
								<textarea type="text" class="pylos-summernote" id="pylos-step-edit-text-<?php echo $n; ?>" name="payload[text]" placeholder="<?php $this->pylos_model->echovar(htmlentities($step['text'])); ?>"><?php $this->pylos_model->echovar(htmlentities($step['text'])); ?></textarea>
							</div>
						</div>
						<div class="row" style="padding-top: 20px;">	
							<div class="col-sm-12">
								<div id="editstepfail<?php echo $n; ?>" class="alert alert-danger " style="display: none;" role="alert">Shoot, the step text/description is required...</div>
								<div id="editstepsuccess<?php echo $n; ?>" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
								<div id="editsteploading<?php echo $n; ?>" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">rewriting history...</div>
								<div id="editstepbuttons<?php echo $n; ?>">
									<a href="/pylos/api/remove/pylos_steps/<?php echo $step['id']; ?>" class="btn btn-danger pull-right">Delete</a>
									<button type="reset" class="btn btn-default" onclick="$('#pylos-edit-step-<?php echo $n; ?>').hide('fast'); $('#pylos-step-<?php echo $n; ?>').show('fast'); ">Reset and Close</button>
									<button type="button" class="btn btn-success tt" id="submiteditguide<?php echo $n; ?>" onclick="submiteditstep('<?php echo $n; ?>','<?php echo $step['id']; ?>');">Save changes</button>
								</div>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
			<!-- End add step -->
			<div id="pylos-step-<?php echo $n; ?>" class="row step-row">
				<div class="col-sm-12">
					<p class="pull-right small step-edit-link" style="display: none; padding-top: 11px;">
						<a href="#" onclick="$('#pylos-edit-step-<?php echo $n; ?>').show('fast'); $('#pylos-step-<?php echo $n; ?>').hide('fast'); initfileinput('#pylos-step-edit-image-<?php echo $n; ?>','#pylos-step-edit-thumbnail-<?php echo $n; ?>','<?php echo $step['id']; ?>','pylos_steps'); initsummernote('#pylos-step-edit-text-<?php echo $n; ?>'); return false;">Edit this step</a> or 
						<a href="#" onclick="$('#pylos-new-step-<?php echo $n; ?>').show('fast'); initfileinput('#pylos-step-image-<?php echo $n; ?>','#pylos-step-thumbnail-<?php echo $n; ?>'); initsummernote('#pylos-step-text-<?php echo $n; ?>'); return false;">Add a step before</a>
					</p>
					<h3 style="margin: 1.5em 0;">Step <?php echo $n; ?><?php if (isset($step['title']) && !empty($step['title'])) echo ' - '.$step['title']; ?></h3></div>
				<?php if (isset($step['video']) && !empty($step['video'])) { ?> 
				<div class="col-sm-7">
					<!-- Image -->
					<style>.embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; height: auto; <?php if (strpos($step['video'], 'autodesk.com') !== false) { echo " min-height: 700px;"; } ?> } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='<?php echo $this->pylos_model->videoembedurl($step['video']); ?>' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>
				</div>

				<?php } elseif (isset($step['images'][0])) { ?> 
				<div class="col-sm-7">
					<!-- Image -->
					<img src="<?php echo $step['images'][0]['url']; ?>" id="step-<?php echo $n; ?>-image" class="guide-content-image" />
				</div>
				<?php } ?>
				<div class="col-sm-5">
					<!-- Text Content -->
					<?php if (isset($step['images']) && !empty($step['images']) && count($step['images']) > 1) { ?><div class="panzoomthumbs">
						<?php foreach ($step['images'] as $i) { ?><a href="<?php echo $i['url']; ?>" onmouseover="$('#step-<?php echo $n; ?>-image').attr('src','<?php echo $i['url']; ?>'); return false;"><img class="panzoomreset" src="<?php echo $i['url']; ?>"></a><?php } ?> 
					</div><?php } ?>
					<div class="guide-content-step"><?php echo $step['text']; ?></div>
				</div>
			</div>
			<?php $n++; }} ?> 
			<div class="row">
				<div class="col-sm-12">
					<?php if ($n > 1) { ?> 
					<a href="#" onclick="$('#pylos-new-step-<?php echo $n; ?>').show('fast'); initfileinput('#pylos-step-image-<?php echo $n; ?>','#pylos-step-thumbnail-<?php echo $n; ?>'); initsummernote('#pylos-step-text-<?php echo $n; ?>'); return false;">Add a step</a> to the end of the guide.
					<hr />
					<?php } ?> 
				</div>
			</div>

			<!-- Start add step -->
			<div id="pylos-new-step-<?php echo $n; ?>" class="row"<?php if ($n > 1) { ?> style="display: none;"<?php } ?>>
				<div class="col-sm-12">
					<div class="pylos-new-step">
					<form id="formnewstep<?php echo $n; ?>" class="form-horizontal" enctype="multipart/form-data">
						<input id="pylos-step-unique-<?php echo $n; ?>" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
						<input id="pylos-step-order-<?php echo $n; ?>" type="hidden" name="payload[order]" value="<?php echo $n; ?>" />
						<input id="pylos-step-parentid-<?php echo $n; ?>" type="hidden" name="payload[parentid]" value="<?php echo $guide['id']; ?>" />
						<input id="pylos-step-parenttype-<?php echo $n; ?>" type="hidden" name="payload[parenttype]" value="pylos_guides" />
						<h3 style="margin-top: 0;">Add <?php echo ($n > 1) ? 'a':'your first'; ?> step</h3>
						<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create steps. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('Here we go...');">Care to sign in? &rarr;</a></div><?php } ?>
						<div class="row">
							<div class="col-sm-6">
								<label for="payload[video]" class="label-floating">Add screenshots or images to this step</label>
								<div class="well well-image">
									<input type="file" id="pylos-step-image-<?php echo $n; ?>" name="userfile" value="">
									<input type="hidden" id="pylos-step-thumbnail-<?php echo $n; ?>" name="payload[thumbnail]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
								
								</div>
								<label for="payload[video]" class="label-floating">Use a Vimeo/YouTube video instead</label>
								<input type="text" class="form-control" id="pylos-step-video-<?php echo $n; ?>" name="payload[video]" placeholder="https://vimeo.com/288393864">

							</div>
							<div class="col-sm-6">
								<label for="payload[title]" class="label-floating">Optional Title</label>
								<input type="text" class="form-control" style="margin-bottom: 10px;" id="pylos-step-title-<?php echo $n; ?>" name="payload[title]" placeholder="Simple title, optional">
								<label for="payload[text]" class="label-floating">Step text/description</label>
								<textarea type="text" class="pylos-summernote" id="pylos-step-text-<?php echo $n; ?>" name="payload[text]" placeholder="Describe the step in simple and clear language, use bullet points too!"></textarea>
							</div>
						</div>
						<div class="row" style="padding-top: 20px;">	
							<div class="col-sm-12">
								<div id="newstepfail<?php echo $n; ?>" class="alert alert-danger " style="display: none;" role="alert">Shoot, the step text/description is required...</div>
								<div id="newstepsuccess<?php echo $n; ?>" class="alert alert-success " style="display: none;" role="alert">Done! Reloading...</div>
								<div id="newsteploading<?php echo $n; ?>" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">stepping it up...</div>
								<div id="newstepbuttons<?php echo $n; ?>">
									<button type="reset" class="btn btn-default">Reset</button>
									<button type="button" class="btn btn-success tt" id="submitnewguide<?php echo $n; ?>" onclick="submitnewstep('<?php echo $n; ?>');">Save changes</button>
								</div>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
			<!-- End add step -->
			<!-- End Steps -->
		</div>
		<div class="col-sm-9">
			<h3>Conclusion</h3>
			<p><?php echo $guide['conclusion']; ?></p>
		</div>
		<hr style="clear: both;" />
		<div class="col-lg-8 ">
			<h3>Connections</h3>
			<div class="meta meta-corner" style="text-align: left; margin-left: -5px;">
				<?php foreach (array('dependency'=>'dependencies','phase'=>'phases','tag'=>'tags','project'=>'projects') as $j => $k) { 
					foreach (${$k} as $tag) { ?>
					<a href="<?php echo site_url("pylos/$k/$tag"); ?>" class="<?php echo $k; ?>-corner" data-toggle="tooltip" data-placement="bottom" title="<?php echo ucfirst($j); ?>"><?php echo $tag; ?></a><?php } ?>
				<?php } ?> 
			</div>
		</div>
		<hr style="clear: both;" />

	</div>
	<!-- End Content Area -->
			<!-- /main -->
	</div>
	</div>
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
					<h4 class="modal-title">Add a file revision for <?php echo $guide['title']; ?></h4>
				</div>
				<div class="modal-body">
				<form id="formnewrevision" class="form-horizontal" enctype="multipart/form-data">
					<input id="pylos-block-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
					<p>Revisions allow us to keep past versions of resources as an archive and update resources as software and workflows evolve. Thanks for helping keep Pylos up to date by making our resources better!</p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to add a revision. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<div class="form-group">
						<label for="payload[description]" class="col-sm-2 control-label">What's new?</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="pylos_newblock_description" name="payload[description]" placeholder="One sentence summary that tells us what changed"></div>
					</div>
					<div class="form-group">
						<label for="upload" class="col-sm-2 control-label">File</label>
						<div class="col-sm-10">
							<p class="small">You need to share a zip file of the resource you are sharing. Please format your file/resource so that it can be used on a variety of projects and avoid uploading large files with unnecessary geometry.</p>
							<input type="file" id="pylos-block-file" name="userfile" value="">
							<input type="hidden" id="pylos_newblock_file" name="payload[url]" value="<?php //echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
							<input type="hidden" name="payload[parenttype]" value="pylos_block">
							<input type="hidden" name="payload[parentid]" value="<?php echo $guide['id']; ?>">
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
		$(function() {
			initfileinput('#pylos-step-image-<?php echo $n; ?>','#pylos-step-thumbnail-<?php echo $n; ?>');
			initsummernote('#pylos-step-text-<?php echo $n; ?>');
		});
		function initedit() {
			$('#pylos-edit-guide').show('fast');
			initfileinput('#pylos-guide-edit-image','#pylos-guide-edit-thumbnail','<?php echo $guide['id']; ?>','pylos_guides');
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
		function submitnewstep(iteration) {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#newstepsuccess'+iteration).hide(); 
					$('#newstepfail'+iteration).hide(); 
					$('#newsteploading'+iteration).show();
				},
				url: "/pylos/api/create/step",
				data: $("#formnewstep"+iteration).serialize(),
				statusCode: {
					200: function(data) {
						$('#newsteploading'+iteration).hide(); 
						$('#newstepsuccess'+iteration).show();
						var response = JSON.parse(data); 
						$('#newstepbuttons'+iteration).show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#newsteploading'+iteration).hide(); 
						$('#newstepfail'+iteration).show();
						$('#newstepbuttons'+iteration).show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#newsteploading'+iteration).hide(); 
						$('#newstepfail'+iteration).show();
						$('#newstepbuttons'+iteration).show(); 
					}
				}
			});
		}
		function submiteditstep(iteration,id) {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editstepsuccess'+iteration).hide(); 
					$('#editstepfail'+iteration).hide(); 
					$('#editsteploading'+iteration).show();
				},
				url: "/pylos/api/update/pylos_steps/"+id,
				data: $("#formeditstep"+iteration).serialize(),
				statusCode: {
					200: function(data) {
						$('#editsteploading'+iteration).hide(); 
						$('#editstepsuccess'+iteration).show();
						var response = JSON.parse(data); 
						$('#editstepbuttons'+iteration).show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editsteploading'+iteration).hide(); 
						$('#editstepfail'+iteration).show();
						$('#editstepbuttons'+iteration).show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editsteploading'+iteration).hide(); 
						$('#editstepfail'+iteration).show();
						$('#editstepbuttons'+iteration).show(); 
					}
				}
			});
		}
		function submiteditguide() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editguidesuccess').hide(); 
					$('#editguidefail').hide(); 
					$('#editguideloading').show();
				},
				url: "/pylos/api/update/pylos_guides/<?php echo $guide['id']; ?>",
				data: $("#formeditguide").serialize(),
				statusCode: {
					200: function(data) {
						$('#editguideloading').hide(); 
						$('#editguidesuccess').show();
						var response = JSON.parse(data); 
						$('#editguidebuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editguideloading').hide(); 
						$('#editguidefail').show();
						$('#editguidebuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editguideloading').hide(); 
						$('#editguidefail').show();
						$('#editguidebuttons').show(); 
					}
				}
			});
		}
		$('#newrevision').on('shown.bs.modal', function () {
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
						$('#newblockloading').hide(); 
						$('#newblockfail').show();
						$('#newblockbuttons').show(); 
					}
				}
			});
		});

	</script>