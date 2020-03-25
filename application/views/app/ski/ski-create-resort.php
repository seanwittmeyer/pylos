<!-- Content Area -->

	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1">
				<h3 style="margin-top: 0;">Add a ski resort</h3>

				<div>
				<form id="formnewblock" class="form-horizontal" >
					<p>This page is pretty dumb. Basically, you give the desired slug and the snocountry resort id and I go in and import the resort into our database for our nonsense. You can go <a href="http://feeds.snocountry.net/getResortList.php?apiKey=SnoCountry.example&states=co" target="_blank">here for a state's listing of resorts</a> or <a href="http://feeds.snocountry.net/getResortList.php?regions=europe&apiKey=SnoCountry.example&intl=complete" target="_blank">here for the list of international resorts</a>.</p>
					<?php if (!$this->ion_auth->logged_in()) { ?><div class="alert alert-info" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <strong>Woah there!</strong> You'll need to be signed in to create your dataset. <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" style="text-decoration: underline;" onclick="$(this).text('One moment, please...');">Care to sign in? &rarr;</a></div><?php } ?>

					<div class="form-group">
						<div class="col-xs-4">
							<label for="ski-new-id" class="label-floating">ID</label>
							<input class="form-control" id="ski-new-id" name="payload[id]" />
						</div>
						<div class="col-xs-8">
							<label for="ski-new-slug" class="label-floating">Resort Slug</label>
							<input class="form-control" id="ski-new-slug" name="payload[id]" />
						</div>
					</div>
					<div class="form-group" style="padding-top: 30px;">
						<div class="col-sm-12">
							<div id="newblockbuttons">
								<button type="reset" class="btn btn-default" >Start Over</button>
								<button type="button" class="btn btn-success tt" id="submitnewblock">Give it a go!</button>
							</div>
						</div>
					</div>
				</form>
				</div>

				<script>
					$('#submitnewblock').click(function() {
						location.assign("<?php echo site_url("ski/createresort"); ?>/" + $("#ski-new-id").val() + "/" + $("#ski-new-slug").val());
					});
				</script>

			</div>
		</div>
	</div>

<!-- End Content Area -->
