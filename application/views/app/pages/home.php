	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="bs-component">
					<div class="jumbotron jumbotron-home windowheight" id="imgheader" style="background-image: url('<?php echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: '/includes/test/assets/Moofushi_Kandu_fish.jpg'; ?>');">
						<div class="col-md-6">
							<p>
								<a href="//kapalicarsi.wittmeyer.io/" class="links text-center">complexity</a>
								<a href="/ski" class="links text-center">ski</a>
								<a href="/pylos" class="links text-center">pylos</a>
								<a href="https://are.na/sean-wittmeyer" class="links text-center">are.na</a> 
								<a href="http://seanwittmeyer.com/img/" class="links text-center">photos</a>
							</p>
						</div>
						<div class="col-md-6" style="margin-top: 30px;">
							<?php $weather = $this->shared->weather($location=false,$source='ip',$formatted=false); ?> 
							<script type="text/javascript" src="https://darksky.net/widget/graph-bar/<?php echo $weather['lat'].','.$weather['lon']; ?>/us12/en.js?width=undefined&title=Full Forecast&textColor=ffffff&bgColor=transparent&skyColor=undefined&fontFamily=-apple-system,BlinkMacSystemFont,Segoe UI&customFont=&units=us&timeColor=ffffff&tempColor=ffffff&currentDetailsOption=true"></script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid build-wrapper" style="">
		<div class="row">
			<div class="col-sm-6 col-md-7" style="margin-top: -40px;"><h3 class="text-left"><a href="https://docs.google.com/spreadsheets/u/0/d/1_vxTr5ze9Po3noASrmc3075x9EMsKZQczUe-QHsWADQ/htmlview#" target="_blank" style="color:#3e3f3a !important;"><i class="fa fa-warning" style="color: orange;"></i> COVID-19 Official PDX Relief Resource List &rarr;</a>, &nbsp; &nbsp; &nbsp;<a href="https://www.arcgis.com/apps/opsdashboard/index.html#/bda7594740fd40299423467b48e9ecf6" target="_blank" style="color:#3e3f3a !important;"><i class="fa fa-dashboard" ></i> JHU Dashboard &rarr;</a></h3></div>
			<div class="col-sm-3 tablet-hide hidden-xs hide" style="margin-top: -40px;"><h3 class="text-center">snow api error</h3></div>
			<div class="col-sm-3 tablet-hide hidden-xs" style="margin-top: -40px;"><h3 class="text-center">model waiting... (CD6F)</h3></div>
			<div class="col-sm-2 col-md-2" style="margin-top: -40px;"><h3 class="text-right"><a href="/trains" style="color:#3e3f3a !important;">trains online</a></h3></div>
		</div>	</div>
	<!-- /Jumbotron -->

	
<?php /*
	<!-- Panels -->
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<h3><?php echo $payload['panel1title']; ?></h3>
				<p><?php echo $payload['panel1text']; ?></p>
				<div class="bs-component">
				
					<div class="panel panel-primary">
						<?php $ss = array('taxonomy',$payload['panel1tax1']); $set = $this->shared->get_data($ss[0],$ss[1],false,true); ?>
						<div class="panel-heading"><a style="color: white" href="/<?php echo $set['type']; ?>/<?php echo $set['slug']; ?>"><?php echo $set['title']; ?></a></div>
						<div class="panel-body">
							<div class="list-group">
								<?php 
								$set = $this->shared->get_related($ss[0],$ss[1]); 
								foreach ($set as $single) { ?><a id="df_list_<?php echo $single['id']; ?>" class="list-group-item" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><!--<span class="badge">6</span>--><?php echo $single['title']; ?></a><?php } ?> 
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<?php $ss = array('taxonomy',$payload['panel1tax2']); $set = $this->shared->get_data($ss[0],$ss[1],false,true); ?>
						<div class="panel-heading"><a style="color: #333" href="/<?php echo $set['type']; ?>/<?php echo $set['slug']; ?>"><?php echo $set['title']; ?></a></div>
						<div class="panel-body">
							<ul class="nav nav-pills">
								<?php 
								$set = $this->shared->get_related($ss[0],$ss[1]); 
								//print('<code>');var_dump($set);print('</code>');
								foreach ($set as $single) { ?><li class="" id="kc_list_<?php echo $single['id']; ?>"><a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></li><?php } ?> 
							</ul>
						</div>
					</div>


				</div>
			</div>


			<div class="col-lg-4">
				<h3><?php echo $payload['panel2title']; ?></h3>
				<p><?php echo $payload['panel2text']; ?></p>
				<div class="bs-component">
				
					<div class="panel panel-success">
						<div class="panel-heading"><a style="color: white" href="/collection/urbanism">Urbanism and Planning</a></div>
						<div class="panel-body">
							<div class="list-group">
							<?php foreach ($this->shared->get_related('taxonomy',$payload['panel2tax1']) as $i) { ?><a href="/field/<?php echo $i['slug']; ?>" class="list-group-item"><?php echo $i['title']; ?></a><?php } ?> 
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Architecture</div>
						<div class="panel-body">
							Soon!
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Other Fields</div>
						<div class="panel-body">
							Soon!
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-4">
				<div class="bs-component">
					<h3><?php echo $payload['panel3title']; ?></h3>
					<p><?php echo $payload['panel3text']; ?></p>
					<?php $links = $this->shared->get_data('link',false,false,false,false,'5'); 
						if ($links === false) { ?> 
							<blockquote>Nothing in the feed...yet. Add links to the pages, definitions, and collections to see them here.</blockquote>
					<?php } else { ?> 
					<!-- CAS Embed -->
					<div class="cas-embed">
						<?php foreach ($links as $link) { ?><blockquote class="embedly-card" data-card-chrome="1" data-card-key="74435e49e8fa468eb2602ea062017ceb" data-card-controls="0"><h4><a href="<?php echo $link['uri']; ?>"><?php echo $link['title']; ?></a></h4><p><?php echo $link['excerpt']; ?></p></blockquote><div class="feed-footer"><address data-toggle="tooltip" data-title="<?php echo $link['excerpt']; ?>">Description</address> | This is linked to <a href="/<?php echo $link['hosttype']; ?>/<?php $__host = $this->shared->get_data($link['hosttype'],$link['hostid']); echo $__host['slug']; ?>"><?php echo $__host['title']; ?></a>.</div><?php } ?> 
					</div><!-- /CAS Embed -->
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- /Panels -->
<?php /* */ ?>



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
					<h4>Header</h4>
					<div class="form-group">
						<label for="payload[payload][herotitle]" class="">Title</label>
						<div class=""><input type="text" class="form-control" name="payload[payload][herotitle]" value="<?php echo $payload['herotitle']; ?>"></div>
					</div>
					<div class="form-group">
						<label for="payload[payload][herotext]" class="">Text</label>
						<div class=""><textarea type="text" class="form-control" id="cas-page-excerpt" name="payload[payload][herotext]"><?php echo $payload['herotext']; ?></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[payload][herolinktitle1]" class="">Link 1</label>
						<div class=""><input type="text" class="form-control" name="payload[payload][herolinktitle1]" value="<?php echo $payload['herolinktitle1']; ?>" placeholder="Link Title"></div>
						<div class=""><input type="text" class="form-control" name="payload[payload][herolinklink1]" value="<?php echo $payload['herolinklink1']; ?>" placeholder="http://" ></div>
					</div>
					<div class="form-group">
						<label for="payload[payload][herolinktitle1]" class="">Link 2</label>
						<div class=""><input type="text" class="form-control" name="payload[payload][herolinktitle2]" value="<?php echo $payload['herolinktitle2']; ?>" placeholder="Link Title"></div>
						<div class=""><input type="text" class="form-control" name="payload[payload][herolinklink2]" value="<?php echo $payload['herolinklink2']; ?>" placeholder="http://" ></div>
					</div>
					<hr class="clear">
					<h4>General</h4>
					<div class="form-group">
						<label for="payload[title]" class="">Title and Subtitle</label>
						<div class=""><input type="text" class="form-control" name="payload[title]" value="<?php echo $title; ?>"></div>
						<div class=""><input type="text" class="form-control" name="payload[payload][subtitle]" value="<?php echo $payload['subtitle']; ?>"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-page-excerpt" name="payload[excerpt]"><?php echo $excerpt; ?></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[template]" class="">Template <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="popover" data-trigger="hover" title="Page Templates"  data-content="Select the template to use for this page. By changing this, you may lose some settings when you save the page."> </i></label>
						<div class="">
							<select id="cas-page-template" name="payload[template]" class="form-control">
								<?php foreach (get_filenames("./application/views/app/pages") as $pagetemplate) { $pagetemplate = str_replace('.php', '', $pagetemplate); ?>
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
					<hr class="clear">
					<h4>Left Panel</h4>
					<?php $list_tax = $this->shared->list_bytype('taxonomy'); ?>
					<div class="form-group">
						<label for="payload[panel1title]" class="">Title and Text</label>
						<div class=""><input type="text" class="form-control" name="payload[payload][panel1title]" value="<?php echo $payload['panel1title']; ?>"></div>
						<div class=""><input type="text" class="form-control" name="payload[payload][panel1text]" value="<?php echo $payload['panel1text']; ?>"></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" style="padding-top: 16px;">Primary Taxonomy Host</label>
						<div class="col-sm-10">
							<select name="payload[payload][panel1tax1]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
							<?php // List taxonomy
								if ($list_tax === false) { echo '<option disabled>No taxonomy to display.</option>'; } else {
								foreach ($list_tax as $a) { $selected = ($a['id'] == $payload['panel1tax1']) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
							</select>
						</div>
					</div> 
					<div class="clear"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label" style="padding-top: 16px;">Features Taxonomy Host</label>
						<div class="col-sm-10">
							<select name="payload[payload][panel1tax2]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
							<?php // List taxonomy
								if ($list_tax === false) { echo '<option disabled>No taxonomy to display.</option>'; } else {
								foreach ($list_tax as $a) { $selected = ($a['id'] == $payload['panel1tax2']) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
							</select>
						</div>
					</div>
					<hr class="clear">
					<h4>Center Panel</h4>
					<div class="form-group">
						<label for="payload[panel2title]" class="">Title and Text</label>
						<div class=""><input type="text" class="form-control" name="payload[payload][panel2title]" value="<?php echo $payload['panel2title']; ?>"></div>
						<div class=""><input type="text" class="form-control" name="payload[payload][panel2text]" value="<?php echo $payload['panel2text']; ?>"></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" style="padding-top: 16px;">Primary Taxonomy Host</label>
						<div class="col-sm-10">
							<select name="payload[payload][panel2tax1]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5">
							<?php // List taxonomy
								if ($list_tax === false) { echo '<option disabled>No taxonomy to display.</option>'; } else {
								foreach ($list_tax as $a) { $selected = ($a['id'] == $payload['panel2tax1']) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
							</select>
						</div>
					</div>

					<hr class="clear">
					<h4>Right Panel</h4>
					<div class="form-group">
						<label for="payload[panel2title]" class="">Title and Text</label>
						<div class=""><input type="text" class="form-control" name="payload[payload][panel3title]" value="<?php echo $payload['panel3title']; ?>"></div>
						<div class=""><input type="text" class="form-control" name="payload[payload][panel3text]" value="<?php echo $payload['panel3text']; ?>"></div>
					</div>
					<hr class="clear">
				</div>
				<div class="modal-footer">
					<div id="editorfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="editorsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="editorloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">making this homepage happen...</div>
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
	<!-- Header Image File Uploader -->
	<div class="modal fade" id="pageupload" tabindex="-1" role="dialog" aria-labelledby="pageupload" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit <?php echo $title; ?></h4>
					<p>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.</p>
				</div>
				<form id="formupload" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<label for="upload" class="">Header Image</label>
						<div class=""><input type="file" id="cas-tax-file" name="userfile" value=""></div>
						<input type="hidden" id="cas-tax-fileurl" name="payload[img][header][url]" value="<?php echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
					</div>
					<div class="form-group">
						<label for="payload[title]" class="">Caption</label>
						<div class=""><input type="text" class="form-control" id="cas-def-title" name="payload[img][header][caption]" value="<?php echo (isset($img['header']['caption']) && !empty($img['header']['caption'])) ? $img['header']['caption']: $title; ?>"></div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="uploadfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="uploadsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="uploadloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">working...</div>
					<div id="uploadbuttons">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submitupload">Save changes</button>
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
						//alert('updated 200');
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