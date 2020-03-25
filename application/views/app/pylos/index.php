<?php $anon = ($this->ion_auth->logged_in()) ? false:true; ?>
<?php 
	$heromenu = (isset($heromenu)) ? $heromenu : false; 
	$filter = (isset($filter)) ? $filter : false; 
	$fullwidth = (isset($fullwidth)) ? $fullwidth : false; 	
?>	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php if ($heromenu) { ?><!-- Pylos Home Search Feature -->
				<div class="row pylos-section-hero">
					<div class="col-lg-12">






						<h4 style="font-weight: 600;text-transform: none;padding-top: 12px;" class="text-center">
							<?php if($this->ion_auth->logged_in()) { ?>
								Hey <?php $user = $this->ion_auth->user()->row(); echo $user->first_name; ?>, welcome back.</li>
							<?php } else { ?>
								Hi there, <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" onclick="$(this).text('working...');" class="">let's sign you in</a> to get started with Pylos.
							<?php } ?>
						</h4>
						<p style="font-size: .9em;" class="text-center">Search all of Pylos, from strategies and case studies to tutorials and and blocks.</p>
						<input class="form-control" placeholder="biophilia..." id="livesearch" onclick="this.select();" />
						<p style="font-size: .9em; padding-top: 20px;" class="text-center">This is a place you can learn more about how to make your projects perform better and find the resources to get started.</p>
					</div>
				</div>
				<!-- / Search Feature --><?php } ?>
				<?php $this->load->view('app/pylos/templates/menu-beta'); ?>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<?php if ($heromenu) { ?><!-- Pylos Home Category Feature -->
				<div class="row hideonsearch pylos-section-hero">
					<div class="col-lg-7">
						<h3 style="font-weight: 600; text-transform: none;">Pylos is a resource library made by the PPT to help you design high performance buildings.</h3>
					</div>
					<ul class="col-lg-12 pylos-section-menu">
						<a href="<?php echo site_url("pylos/strategies"); ?>"><li><i class="fa fa-trophy"></i>Strategies</li></a>
						<a href="<?php echo site_url("pylos/tools"); ?>"><li><i class="fa fa-magic"></i>Tools</li></a>
						<a href="<?php echo site_url("pylos/guides"); ?>"><li><i class="fa fa-mortar-board"></i>Guides</li></a>
						<a href="<?php echo site_url("pylos/blocks"); ?>"><li><i class="fa fa-puzzle-piece"></i>Blocks</li></a>
						<a href="<?php echo site_url("designexplorer"); ?>"><li><i class="fa fa-bar-chart"></i>Datasets</li></a>
						<a href="<?php echo site_url("pylos/projects"); ?>"><li><i class="fa fa-university"></i>Projects</li></a>
						<a href="<?php echo site_url("pylos/presentations"); ?>"><li><i class="fa fa-television"></i>Presentations</li></a>
					</ul>
				</div>
				<!-- / Category Feature -->

				<div class="row hideonsearch pylos-section-phase">
					<div class="col-xs-4" style="align-items: center; display: flex;">
						<div>
							<h2 style="margin-top: 0;">For <a href="<?php echo site_url("pylos/phases"); ?>">PM's</a> and <a href="<?php echo site_url("pylos/phases"); ?>">PA's</a></h2>
							<h3>Understand the what, when, and how of pushing your projects with resources sorted by phase.</h3>
						</div>
					</div>
					<ul class="col-lg-8 pylos-section-menu" style="display: block;">
						<div style="display: flex; margin-bottom: 5px;">
							<a href="<?php echo site_url("pylos/phases/programming"); ?>"><li><i class="fa fa-sitemap"></i>Programming</li></a>
							<a href="<?php echo site_url("pylos/phases/pre-design"); ?>"><li><i class="fa fa-cubes"></i>Pre Design</li></a>
							<a href="<?php echo site_url("pylos/phases/schematic-design"); ?>"><li><i class="fa fa-paint-brush"></i>Schematic Design</li></a>
							<a href="<?php echo site_url("pylos/phases/design-development"); ?>"><li><i class="fa fa-pencil"></i>Design Development</li></a>
						</div>
						<div style="display: flex;">
							<a href="<?php echo site_url("pylos/phases/construction-documents"); ?>"><li><i class="fa fa-newspaper-o"></i>Construction Documents</li></a>
							<a href="<?php echo site_url("pylos/phases/construction-administration"); ?>"><li><i class="fa fa-life-saver"></i>Construction Administration</li></a>
							<a href="<?php echo site_url("pylos/phases/procurement"); ?>"><li><i class="fa fa-shopping-cart"></i>Procurement</li></a>
							<a href="<?php echo site_url("pylos/phases/post-construction"); ?>"><li><i class="fa fa-key"></i>Post Construction</li></a>						
						</div>
					</ul>
				</div>
				<?php } ?>
				<div class="row">
					<div class="col-sm-<?php echo ($fullwidth) ? 12 : 9; ?>  col-sm-12">
						<div class="headline-parent"><?php if ($filter) { ?><input id="livesearch" class="text-left headline" data-toggle="tooltip" data-placement="bottom" title="Type to filter this page or 'tab' to search all of Pylos..." onclick="this.select();" placeholder="<?php echo $contenttitle; ?>" value="<?php echo $contenttitle; ?>" /><?php } else { ?><h1 class="headline"><?php echo $contenttitle; ?></h1><?php } ?></div>
<!-- Content Area -->
<!-- End Content Area -->

						<!-- Resources Grid -->
						<?php $this->load->view('app/pylos/templates/grid-combined',array('limit'=>true)); ?>
						<!-- Resources Grid -->

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