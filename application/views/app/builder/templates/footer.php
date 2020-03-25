	<!-- Create Element Popup -->
	<?php // setup
		if (isset($id)) {
			$hosttype = $type; 
			$hostid = $id;
		}
		
		foreach (array('taxonomy','definition','page','paper','link','relationship') as $type) { ?> 
	<div class="modal fade" id="create<?php echo $type; ?>" tabindex="-1" role="dialog" aria-labelledby="create<?php echo $type; ?>" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Add a <?php echo ucfirst($type); ?></h3>
					<p>
						<?php if ($type == 'definition') { ?>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.
						<?php } elseif ($type == 'taxonomy') { ?>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.
						<?php } elseif ($type == 'page') { ?>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.
						<?php } elseif ($type == 'paper') { ?>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.
						<?php } elseif ($type == 'link') { ?>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.
						<?php } elseif ($type == 'relationship') { ?>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well. <?php } ?>
					</p>
				</div>
				<div class="modal-body">
				<form id="form<?php echo $type; ?>" >
				<?php if ($type == 'definition') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Definition Title</label>
						<div class=""><input type="text" class="form-control" id="cas-def-title" name="payload[title]" placeholder="Non Linearity"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-def-excerpt" name="payload[excerpt]" placeholder="This should be a brief definition in a sentence or two..."></textarea></div>
					</div>
					
					<div class="form-group">
						<label for="payload[body]" class="">Body</label>
						<p class="small"><span class="glyphicon glyphicon-exclamation-sign" style="color:red;" aria-hidden="true"></span> This has issues when copying-and-pasting from websites and Microsoft Word. It is best if you write the content here in this text-box.<br>Make links by using {{handlebars}} in your text!</p>
						<!--<div class="cas-summernote">Hello Summernote</div>-->
						<div class=""><textarea type="text" class="cas-summernote" id="cas-def-body" name="payload[body]" placeholder="The body of the page..."></textarea></div>
					</div>
					<h3>Metadata</h3>
					<p>The richness of the site comes in its content relationships and connections. Select relationships this definition has with other content in the website.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Definitions</label>
								<div class="col-sm-10">
									<select name="payload[relationships][definition][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List definitions
										$list = $this->shared->list_bytype('definition');
										if ($list === false) { echo '<option disabled>No definitions to display.</option>'; } else {
										foreach ($list as $a) { echo '<option value="'.$a['id'].'">'.$a['title']."</option>\n"; }} ?> 
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
										foreach ($list as $a) { echo '<option value="'.$a['id'].'">'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
						</div>
					</div>
				<?php } elseif ($type == 'taxonomy') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Taxonomy/Category Title</label>
						<div class=""><input type="text" class="form-control" id="cas-tax-title" name="payload[title]" placeholder="Non Linearity"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-tax-excerpt" name="payload[excerpt]" placeholder="This should be a brief summary of this taxonomy in a sentence or two..."></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[body]" class="">Body </label>
						<p class="small"><span class="glyphicon glyphicon-exclamation-sign" style="color:red;" aria-hidden="true"></span> This has issues when copying-and-pasting from websites and Microsoft Word. It is best if you write the content here in this text-box.<br>Make links by using {{handlebars}} in your text!</p>
						<div class=""><textarea type="text" class="cas-summernote" id="cas-tax-body" name="payload[body]" placeholder="The body of the page..."></textarea></div>
					</div>
					<h3>Metadata</h3>
					<p>The richness of the site comes in its content relationships and connections. Select relationships this definition has with other content in the website.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Definitions</label>
								<div class="col-sm-10">
									<select name="payload[relationships][definition][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List definitions
										$list = $this->shared->list_bytype('definition');
										if ($list === false) { echo '<option disabled>No definitions to display.</option>'; } else {
										foreach ($list as $a) { echo '<option value="'.$a['id'].'">'.$a['title']."</option>\n"; }} ?> 
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
										foreach ($list as $a) { echo '<option value="'.$a['id'].'">'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
						</div>
					</div>
				<?php } elseif ($type == 'page') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Page Title</label>
						<div class=""><input type="text" class="form-control" id="cas-page-title" name="payload[title]" placeholder="Non Linearity"></div>
					</div>
					<div class="form-group">
						<label for="payload[author]" class="">Author</label>
						<div class=""><input type="text" class="form-control" id="cas-page-author" name="payload[author]" placeholder="Bill Nye the Science Guy"></div>
					</div>
					<div class="form-group">
						<label for="payload[type]" class="">Blog Post or Page?</label>
						<div class=""><select id="cas-page-type" name="payload[type]" class="form-control"><option value="blog" selected="selected">Blog Post</option><option value="page">Page</option></select></div>
					</div>
					<div class="form-group">
						<label for="payload[template]" class="">Template <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="popover" data-trigger="hover" title="Page Templates"  data-content="Select the template to use for this page. By changing this, you may lose some settings when you save the page."> </i></label>
						<div class="">
							<select id="cas-page-template" name="payload[template]" class="form-control">
								<?php foreach (get_filenames("./application/views/app/pages") as $pagetemplate) { $pagetemplate = str_replace('.php', '', $pagetemplate); ?>
								<option value="<?php echo $pagetemplate; ?>" <?php if ($pagetemplate == 'default') echo 'selected'; ?>><?php echo ucfirst($pagetemplate); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-page-excerpt" name="payload[excerpt]" placeholder="This should be a brief definition in a sentence or two..."></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[body]" class="">Body</label>
						<p class="small"><span class="glyphicon glyphicon-exclamation-sign" style="color:red;" aria-hidden="true"></span> This has issues when copying-and-pasting from websites and Microsoft Word. It is best if you write the content here in this text-box.<br>Make links by using {{handlebars}} in your text!</p>
						<div class=""><textarea type="text" class="form-control cas-summernote" id="cas-page-body" name="payload[body]" placeholder="The body of the page..."></textarea></div>
					</div>
					<!--
						<h3>Metadata</h3>
					<p>The richness of the site comes in its content relationships and connections.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							Making relationships when creating elements is coming soon. You can do this with the edit link once the definition has been added.
						</div>
					</div>
					-->

				<?php } elseif ($type == 'paper') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Definition Title</label>
						<div class=""><input type="text" class="form-control" id="cas-paper-title" name="payload[title]" placeholder="Non Linearity"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-paper-excerpt" name="payload[excerpt]" placeholder="This should be a brief definition in a sentence or two..."></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[body]" class="">Body <em>(this will be updated with formatting options soon)</em></label>
						<div class=""><textarea type="text" class="form-control" id="cas-paper-body" name="payload[body]" placeholder="The body of the page..."></textarea></div>
					</div>
					<!--
						<h3>Metadata</h3>
					<p>The richness of the site comes in its content relationships and connections.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							Making relationships when creating elements is coming soon. You can do this with the edit link once the definition has been added.
						</div>
					</div>
					-->
				
				<?php } elseif ($type == 'link') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Link URI <i>start here... <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="popover" data-trigger="hover" title="Creating Links"  data-content="Start by entering the URL for the website or element you want to embed/add to this page. When you are done, click go and we will get details which you can edit."> </i> </em></label>
						<div class="input-group">
							<input type="text" class="form-control" id="cas-link-uri" name="payload[uri]" placeholder="http://youtube.com/watch?v=123abc456"><span class="input-group-btn"><a id="cas-link-embed-trigger" class="btn btn-default">Go!</a></span>
						</div>
					</div>
					<div class="form-group">
						<label class="">Preview</label>
						<a id="cas-link-embed-preview"></a>
					</div>
					<div class="form-group">
						<label for="payload[title]" class="">Title</label>
						<div class=""><input type="text" class="form-control" id="cas-link-title" name="payload[title]" placeholder="Non Linearity"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-link-excerpt" name="payload[excerpt]" placeholder="This should be a brief definition in a sentence or two..."></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[type]" class="">Link Type</label>
						<div class="">
							<select id="cas-link-type" name="payload[type]" class="form-control">
								<option selected="selected" disabled="disabled">Select a type...</option>
								<option value="html" selected="selected">Webpage</option>
								<option value="video">Video</option>
								<option value="file">File</option>
								<option value="paper">Paper</option>
								<option value="book">Book</option>
								<option value="profile">Profile</option>
								<option value="other">Other</option>
							</select>
						</div>
					</div>
					<?php if (isset($hostid)) { ?>
					<input type="hidden" id="cas-link-hostid" name="payload[hosttype]" value="<?php echo $hosttype; ?>" />
					<input type="hidden" id="cas-link-hosttype" name="payload[hostid]" value="<?php echo $hostid; ?>" />
					<?php } ?>
				<?php } elseif ($type == 'relationship') { ?>
					<div class="form-group">
						<label for="payload[title]" class="">Definition Title</label>
						<div class=""><input type="text" class="form-control" id="cas-rel-title" name="payload[title]" placeholder="Non Linearity"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-rel-excerpt" name="payload[excerpt]" placeholder="This should be a brief definition in a sentence or two..."></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[body]" class="">Body <em>(this will be updated with formatting options soon)</em></label>
						<div class=""><textarea type="text" class="form-control" id="cas-rel-body" name="payload[body]" placeholder="The body of the page..."></textarea></div>
					</div>
					<!--
						<h3>Metadata</h3>
					<p>The richness of the site comes in its content relationships and connections.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							Making relationships when creating elements is coming soon. You can do this with the edit link once the definition has been added.
						</div>
					</div>
					-->

				<?php } ?>
				</div><!-- /modal body -->
				<div class="modal-footer">
					<div id="<?php echo $type; ?>fail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="<?php echo $type; ?>success" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="<?php echo $type; ?>loading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">dancing...</div>
					<div id="<?php echo $type; ?>buttons">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submit<?php echo $type; ?>" data-toggle="tooltip" title="This is ">Add <?php echo ucfirst($type); ?>!</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<?php } //end foreach ?>
	<!-- End New Popup -->

	<!-- Login Popup -->
	<div class="modal fade" id="loginmodal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-login">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Please sign in</h3>
				</div>
				<div class="modal-body">
					<?php echo form_open("auth/login");?>
					<div class="panel panel-default">
						<div class="panel-body">
							<a href="/auth/facebook" class="btn btn-lg btn-primary btn-block" onclick="$(this).text('signing you in...');">Log in / Sign up with Facebook &rarr;</a> <br />
							<a href="/auth/saml/login/?return=pylos" class="btn btn-lg btn-primary btn-block" onclick="$(this).text('signing you in...');">Log in with OneLogin &rarr;</a>
						</div>
					</div>
					<h3>or...</h3>
					<div class="panel panel-default">
						<div class="panel-body">
							<label for="identity" class="sr-only">Email address</label>
							<input type="email" id="identity" name="identity" class="form-control" placeholder="Email address" required autofocus>
							<label for="inputPassword" class="sr-only">Password</label>
							<input type="password" name="password" class="form-control" placeholder="Password" required>
							<label><a href="/auth/forgot_password">Forgot your password?</a></label>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember" value="1"> Remember me
								</label>
							</div>
							<div class="btn-group btn-block" role="group" aria-label="logincreateaccount">
								<button class="btn btn-lg btn-info btn-block" style="margin-top:0;" type="submit" data-loading-text="checking...">Log in &rarr;</button>
								<!--<a href="#" class="btn btn-lg btn-default btn-block hidden-xs" style="width:10%;margin-top:0;" >or</a>
								<button class="btn btn-lg btn-success btn-block tt" style="width:45%;margin-top:0;"  data-toggle="tooltip" title="By doing this, you are saying you'll be nice." type="submit">Sign up &rarr;</button>-->
							</div>
						</div>
					</div>
					</form>
				</div>
				<!--<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
				</div>-->
			</div>
		</div>
	</div>
	<!-- End Login Popup -->

	<!--<footer class="footer">
		<div class="container">
			<p class="text-muted"><i>Builder</i> is a platform playground for trying new things while infusing technology into architecture, by Sean Wittmeyer. </p>
		</div>
	</footer>-->
	<!-- Javascript -->
	<script src="/includes/js/bootstrap.min.js"></script>
	<script src="/includes/js/bootstrap-select.min.js"></script>
	<script src="/includes/js/sortable.js"></script>
	<script src="/includes/js/summernote.min.js"></script>
	<script async src="//cdn.embedly.com/widgets/platform.js"></script>
	<script src="//cdn.embed.ly/jquery.embedly-3.1.1.min.js"></script>
	<script>
		$.embedly.defaults.key = '74435e49e8fa468eb2602ea062017ceb';
		$('#cas-link-embed-trigger').click(function() {
			var originaluri = $('#cas-link-uri').val();
			$('#cas-link-embed-preview').attr("href", originaluri).text(originaluri);
			$('#cas-link-embed-preview').embedly({query: { maxwidth: '550' }});
			$.embedly.extract(originaluri).progress(function(data){
			  $('#cas-link-title').val(data.title);
			  $('#cas-link-excerpt').val(data.description);
			  $('#cas-link-uri').val(data.url);
			  //$('#cas-link-type').val(data.type);
			  
			}).done(function(results){
			  // Even though there was only one url, this will still be a list of
			  // results.
			  //var data = results[0];
			  //alert(data);
			});
			return false;
		});
		/*
		$.embedly.oembed('http://embed.ly').progress(function(data){
		// Will only be called once in this case.
		console.log(data.url, data.title);
		}).done(function(results){
		// Even though there was only one url, this will still be a list of
		// results.
		var data = results[0];
		});
		*/

		$(function () { $('[data-toggle="popover"]').popover() });
		$(function () { $('[data-toggle="tooltip"]').tooltip() });
	</script>
	<!-- Still fixing Microsoft products -->
	<script src="/includes/js/wittmeyer.bugfix.1.0.1.js"></script>
	<!-- Make the site interactive -->
	<script type="text/javascript">
		$(function () { $('[data-toggle="popover"]').popover() });
		$(function () { $('.selectpicker').selectpicker() });
		/*$('#loginmodal').on('shown.bs.modal', function () {
			$('#identity').focus();
		})*/
		function postOrder() {
			$('#definitionbuttons').hide(); 
			$('#definitionloading').show();  
			// get vars and ajax post to api
			// wait for reply
			$('#definitionloading').hide(); 
			$('#definitionsuccess').show();
		}
		$('#createdefinition').on('shown.bs.modal', function () {
			$('.cas-summernote').summernote({
			  toolbar: [
			    ['style', ['style']],
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow']],
			    ['code', ['codeview']],
			    ['fullscreen', ['fullscreen']],
			  ],
			  placeholder: 'once upon a time...',
			});
		})
		$('#createpage').on('shown.bs.modal', function () {
			$('.cas-summernote').summernote({
			  toolbar: [
			    ['style', ['style']],
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow']],
			    ['code', ['codeview']],
			    ['fullscreen', ['fullscreen']],
			  ],
			  placeholder: 'once upon a time...',
			});
		})
		$('#createtaxonomy').on('shown.bs.modal', function () {
			$('.cas-summernote').summernote({
			  toolbar: [
			    ['style', ['style']],
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow']],
			    ['code', ['codeview']],
			    ['fullscreen', ['fullscreen']],
			  ],
			  placeholder: 'once upon a time...',
			});
		})

		<?php foreach (array('taxonomy','definition','page','paper','link','relationship') as $type) { ?> 
		// New Content
		$('#submit<?php echo $type; ?>').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					<?php if ($type == 'definition') { ?> 
					<?php } ?> 
					$('#<?php echo $type; ?>buttons').hide(); 
					$('#<?php echo $type; ?>fail').hide(); 
					$('#<?php echo $type; ?>loading').show();
				},
				url: "/api/create/<?php echo $type; ?>",
				data: $("#form<?php echo $type; ?>").serialize(),
				statusCode: {
					200: function(data) {
						$('#<?php echo $type; ?>loading').hide(); 
						$('#<?php echo $type; ?>success').show();
						var response = JSON.parse(data);
						<?php echo ($type == 'link') ? "window.location.reload();" : "window.location.assign(response.result.url)"; ?> 
						$('#<?php echo $type; ?>buttons').show(); 
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#<?php echo $type; ?>loading').hide(); 
						$('#<?php echo $type; ?>fail').show();
						$('#<?php echo $type; ?>buttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#<?php echo $type; ?>loading').hide(); 
						$('#<?php echo $type; ?>fail').show();
						$('#<?php echo $type; ?>buttons').show(); 
					}
				}
			});
		});
		<?php } ?>
	</script>	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-336608-26', 'auto');
	  ga('send', 'pageview');
	</script>
</body>
</html>