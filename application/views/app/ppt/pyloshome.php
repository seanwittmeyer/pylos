<?php $anon = ($this->ion_auth->logged_in()) ? false:true; ?><!-- Content Area -->
		<div class="row">
			<div class="col-lg-7"><h3 style="font-weight: 600; text-transform: none;"><?php echo $this->shared->handlebar_links($excerpt); ?></h3></div>
		</div>
		<div class="row">
			<div class="col-lg-9"><!-- Page title: <?php echo $title; ?>(ID: <?php echo $id; ?>)--><p><?php echo $this->shared->handlebar_links($body); ?></p></div>
		</div>			


<!-- End Content Area -->
		<div class="row">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Guides</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">
				It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
			</p></div>
		</div>			
		<?php if ($guides === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷‍♂️</h1>
			<h3>Shoot. Doesn't look like we have any <?php echo $pagetitle; ?> guides.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/guides'); ?>">looking at the guides we do have</a> or <a href="<?php echo site_url('pylos/guides/create'); ?>">add the first <?php echo $pagetitle; ?> guide</a>?</p>
		</div>
		<?php } else { ?> 

		<div class="row grid-filter">
			<?php $i = 1; foreach ($guides as $guide) : if ($anon && $guide['private'] == 1) continue; ?> 
			<div class="col-lg-4 col-md-6 col-sm-12 grid-filter-single"<?php if ($i > 6) { ?> style="display: none;"<?php } ?>>
				<a class="grid-link" href="/pylos/guides/<?php echo $guide['slug']; ?>">
					<div class="bs-callout grid-block block-guide<?php if (!isset($guide['thumbnail']) || empty($guide['thumbnail'])) echo ' grid-nothumb'; ?>" <?php if (isset($guide['thumbnail']) && !empty($guide['thumbnail'])) { ?>style="background-image: url('<?php echo $guide['thumbnail']; ?>');" <?php } ?>>
						<div class="row">
							<div class="col-sm-12"><p class="title"><span class="label label-default label-small">Guide</span><br /><span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $guide['title']; ?><span></p><p class="small" style="text-shadow: 1px 1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, -1px -1px 0 #fff; color: #333 !important; margin-top: -9px;">Posted <?php echo $this->pylos_model->twitterdate($guide['timestamp']); ?></p></div>
						</div>
					</div>
				</a>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<?php } ?> 
		<hr />
		<div class="row">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Blocks</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">These are the building blocks of computational design that you can plug into your tool, analysis, or design process already made.</p></div>
		</div>			
		<?php if ($blocks === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷‍♂️</h1>
			<h3>Shoot. Doesn't look like we have any <?php echo $pagetitle; ?> guides.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/guides'); ?>">looking at the guides we do have</a> or <a href="<?php echo site_url('pylos/guides/create'); ?>">add the first <?php echo $pagetitle; ?> guide</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php $i = 1; foreach ($blocks as $block) : if ($anon && $block['private'] == 1) continue; ?> 
			<div class="col-lg-3 col-md-4 col-sm-6 grid-filter-single"<?php if ($i > 8) { ?> style="display: none;"<?php } ?>>
				<a class="grid-link" href="/pylos/blocks/<?php echo $block['slug']; ?>">
					<div class="bs-callout grid-block block-<?php echo $block['type']; ?>" style="background-image: url('<?php echo $block['thumbnail']; ?>');">
						<div class="row">
							<div class="col-sm-12"><p class="title"><span class="label label-default label-small"><?php echo ucfirst($block['type']); ?></span><br /><span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $block['title']; ?></span></p></div>
						</div>
					</div>
				</a>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<?php } ?> 
		<hr />
		<div class="row">
			<div class="col-lg-2"><h3 style="margin-top: 0;">Presentations</h3></div>
			<div class="col-lg-8"><p style="font-size: 100%;">Pylos also has a variety of presentations and resources from past lunch and learns, meetings, workshops, and other events helping the firm share knowledge for high performance and computational design.</p></div>
		</div>			
		<?php if ($presentations === false) { ?> 
		<div class="pylos-no-results">
			<h1>🤷‍♂️</h1>
			<h3>Shoot. Doesn't look like we have any <?php echo $pagetitle; ?> guides.</h3>
			<p>Not to worry, how about <a href="<?php echo site_url('pylos/guides'); ?>">looking at the guides we do have</a> or <a href="<?php echo site_url('pylos/guides/create'); ?>">add the first <?php echo $pagetitle; ?> guide</a>?</p>
		</div>
		<?php } else { ?> 
		<div class="row grid-filter">
			<?php 
				$result = $this->pylos_model->get_data2('pylos_files',false,array('parenttype'=>'pylos_presentations'));
				if (count($result) < 1) {
					$p_thumbs = false;
				} else {
					$p_thumbs = array();
					foreach ($result as $i) {
						$p_thumbs[$i['parentid']] = $i['url'];
					}
				}
				$i = 1;
				foreach ($presentations as $presentation) : if ($anon && $presentation['private'] == 1) continue; ?> 
			<div class="col-lg-6 grid-filter-single"<?php if ($i > 4) { ?> style="display: none;"<?php } ?>>
				<a class="grid-link" href="/pylos/presentations/<?php echo $presentation['slug']; ?>">
					<div class="bs-callout grid-block block-guide">
						<div class="row">
							<div class="col-xs-8"><p class="title"><span class="label label-default label-small">Presentation</span><br /><span style="display: inline-block; font-weight: 700; letter-spacing: -0.05em; line-height: 1em;"><?php echo $presentation['title']; ?><span></p><p class="small" style="text-shadow: 1px 1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, -1px -1px 0 #fff; color: #333 !important; margin-top: -9px;">Posted <?php echo $this->pylos_model->twitterdate($presentation['timestamp']); ?></p></div>
							<div class="col-xs-4">
								<?php 
									if (isset($p_thumbs[$presentation['id']])) { 
										$presentation['url'] = $p_thumbs[$presentation['id']];
									} else {
										$presentation['url'] = '';
									}
								?>
								<div class="pylos-presentation-preview" style="background-image: url('<?php echo $presentation['url']; ?>');"></div>
							</div>

						</div>
					</div>
				</a>
			</div>
			<?php $i++; endforeach; ?>
		</div>
		<?php } ?> 

					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->













	<!-- /Panels -->
	<div class="modal fade" id="pageeditor" tabindex="-1" role="dialog" aria-labelledby="pageeditor" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Edit <?php echo $title; ?></h4>
					<p>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.</p>
				</div>
				<form id="formeditor" >
				<div class="modal-body">
					<div class="form-group">
						<label for="payload[title]" class="">Title</label>
						<div class=""><input type="text" class="form-control" id="cas-page-title" name="payload[title]" value="<?php echo $title; ?>"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-page-excerpt" name="payload[excerpt]"><?php echo $excerpt; ?></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[author]" class="">Author</label>
						<div class=""><input type="text" class="form-control" id="cas-page-author" name="payload[author]" <?php if (empty($author)) { ?>placeholder="Bill Nye the Science Guy" <?php } else { ?>value="<?php echo $author; ?>"<?php } ?>></div>
					</div>
					<div class="form-group">
						<label for="payload[template]" class="">Template <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="popover" data-trigger="hover" title="Page Templates"  data-content="Select the template to use for this page. By changing this, you may lose some settings when you save the page."> </i></label>
						<div class="">
							<select id="cas-page-template" name="payload[template]" class="form-control">
								<?php foreach (get_filenames("./application/views/content/pages") as $pagetemplate) { $pagetemplate = str_replace('.php', '', $pagetemplate); ?>
								<option value="<?php echo $pagetemplate; ?>"<?php if ($pagetemplate == $template) { ?> selected="selected"<?php } ?>><?php echo ucfirst($pagetemplate); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="payload[body]" class="">Body</label>
						<!--<div class="cas-summernote">Hello Summernote</div>-->
						<div class=""><textarea type="text" class="cas-summernote" id="cas-def-body" name="payload[body]"><?php echo $body; ?></textarea></div>
					</div>
					
					
					
					
					<h4>Metadata</h4>
					<p>The richness of the site comes in its content relationships and connections. Select relationships this definition has with other content in the website.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Definitions</label>
								<div class="col-sm-10">
									<select name="payload[relationships][definition][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List definitions
										$list = $this->shared->list_bytype('definition'); $relationships = array(); foreach ($set as $ss) $relationships[] = $ss['id'];
										if ($list === false) { echo '<option disabled>No definitions to display.</option>'; } else {
										foreach ($list as $a) { $selected = (in_array($a['id'],$relationships)) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Taxonomy</label>
								<div class="col-sm-10">
									<select name="payload[relationships][taxonomy][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List taxonomy
										$list = $this->shared->list_bytype('taxonomy');
										if ($list === false) { echo '<option disabled>No taxonomy to display.</option>'; } else {
										foreach ($list as $a) { $selected = (in_array($a['id'],$relationships)) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="editorfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="editorsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="editorloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">working...</div>
					<div id="editorbuttons">
						<a class="btn btn-danger pull-left" href="/api/remove/page/<?php echo $id; ?>/home" data-toggle="tooltip" data-title="Are you sure? No undo...">Delete this Page</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submiteditor">Save changes</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
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
                url: "/api/uploadimage/url",
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
				url: "/api/uploadimage", // remove X or it wont work...
		        uploadExtraData: function() {
		            return {
		                id: '<?php echo $id; ?>',
		                type: 'page'
		            };
		        }
		    });
		});

		$('#cas-tax-file').fileinput({
		    uploadUrl: "/api/uploadimage", // server upload action
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