<!-- Content Area -->

	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1">
				<h3 style="margin-top: 0;">Add a ski day</h3>

				<div>
				<form id="formnewblock" class="form-horizontal" enctype="multipart/form-data">
					<?php $newunique = sha1('pylos-'.microtime()); ?> 
					<input id="pylos-block-unique" type="hidden" name="payload[unique]" value="<?php echo $newunique; ?>" />
					<p>This page will accept your ski tracks file (.skiz) from Ski Tracks or Slopes tracking apps. Maybe (.gpx) files soon, since they record data in a similar fashion. </p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create your dataset. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<!-- dont think we need any title, we'll pull it from the file.
					<div class="form-group">
						<label for="payload[title]" class="col-sm-2 control-label">Give it a title</label>
						<div class="col-sm-10"><input type="text" class="form-control" id="pylos_newdata_title" name="payload[title]" placeholder="Simple and clear!"></div>
					</div>
					-->
					<div class="form-group">
						<div class="col-xs-4">
							<label for="ski-new-season" class="label-floating">Season</label>
							<select class="form-control" id="ski-new-season" name="payload[season]" >
								<option value="2015/16">2015/16</option>
								<option value="2016/17">2016/17</option>
								<option value="2017/18">2017/18</option>
								<option value="2018/19">2018/19</option>
								<option value="2019/20" selected="selected">2019/20</option>
								<option value="2020/21">2020/21</option>
								<option value="2021/22">2021/22</option>
							</select>
						</div>
						<div class="col-xs-8">
							<label for="ski-new-resort" class="label-floating">Resort</label>
							<select class="form-control" id="ski-new-resort" name="payload[resort]" >
								<option value="timberline" selected="selected">Timberline Ski Area</option>
								<option value="meadows">Mt Hood Meadows, OR</option>
								<option value="skibowl">Hood Ski Bowl, OR</option>
								<option value="bachelor">Mount Bachelor, OR</option>
								<option value="hoodoo">HooDoo, OR</option>
								<option value="stevenspass">Stevens Pass, WA</option>
								<option value="monarch">Monarch Mountain, CO</option>
								<option value="winterpark">Winter Park, CO</option>
								<option value="loveland">Loveland Ski Area, CO</option>
								<option value="whistlerblackcomb">Whistler Blackcomb, BC</option>
								<option value="alta">Alta Ski Area, UT</option>
								<option value="snowbird">Snowbird Ski Area, UT</option>
								<option value="steamboat">Steamboat Resort, CO</option>
								<option value="ajax">Ajax, CO</option>
								<option value="snowmass">Snowmass, CO</option>
								<option value="aspenhighlands">Aspen Highlands, CO</option>
								<option value="copper">Copper Mountain, CO</option>
								<option value="breckenridge">Breckenridge, CO</option>
								<option value="bormio">Ski Bormio, Italy</option>
								<option value="livigno">Livigno, Italy</option>
								<option value="copenhill">CopenHill, Denmark</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<input type="file" id="pylos-data-file" name="userfile" value="">
							<input type="hidden" id="pylos_newdata_file" name="payload[file]">
						</div>
					</div>

					<div class="form-group" style="padding-top: 30px;">
						<div class="col-sm-12">
							<div id="newblockfail" class="alert alert-danger " style="display: none;" role="alert">You already uploaded this day, boom.</div>
							<div id="newblocksuccess" class="alert alert-success " style="display: none;" role="alert">Ski day done!</div>
							<div id="newblockloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">skiing...</div>
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
	</div>

<!-- End Content Area -->
	<!-- /File Uploader -->
	<script>
		$('#pylos-data-file').fileinput({
		    uploadUrl: "/ski/api/uploaddata/<?php echo $newunique; ?>/file", // server upload action
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
				url: "/ski/api/create/days",
				data: $("#formnewblock").serialize(),
				statusCode: {
					200: function(data) {
						$('#newblockloading').hide(); 
						$('#newblocksuccess').show();
						var response = JSON.parse(data); 
						$('#newblockbuttons').show(); 
						window.location.assign(response.result.url);
					},
					403: function(data) {
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


	<!-- /Top -->
	<!-- Panels -->

