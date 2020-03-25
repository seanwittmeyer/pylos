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
						<div class="headline-parent"><input id="livesearch" class="text-left headline" data-toggle="tooltip" data-placement="bottom" title="Type to filter this page or 'tab' to search all of Pylos..." onclick="this.select();" placeholder="<?php echo $contenttitle; ?>" value="<?php echo $contenttitle; ?>" /></div>
						<!-- Content Area -->
						<div class="well" style="display: none;"><a data-toggle="modal" data-target="#loginmodal" class="btn btn-success btn-md">Sign In / Get Started</a></div>
						<div class="row">
							<div class="col-md-9">
								<p>
									This is a collection of tools that you can use Revit in the cloud with Autodesk Forge to automate design and housekeeping tasks. Think about it like cloud rendering for repetitive tasks in Revit.
								</p>
							</div>
							<ul class="col-sm-12 pylos-section-links">
								<span style="padding-left: 0;">Forge Tools: </span>
								<a href="<?php echo site_url("app/lemur/status"); ?>">Platform Health</a>
								<a href="<?php echo site_url("app/lemur/nickname"); ?>">Reset Nickname</a>
								<a href="<?php echo site_url("app/lemur/workitems"); ?>">See Workitems</a>
								<span>or create</span>
								<a href="<?php echo site_url("app/lemur/create_appbundle"); ?>"><span class="fa fa-plus"></span> App Bundle</a>
								<a href="<?php echo site_url("app/lemur/create_activity"); ?>"><span class="fa fa-plus"></span> Activity</a>
							</ul>
						</div>
						<hr />

					<!-- Message Service -->
						<?php if (isset($success)) { ?> 
						<div class="alert alert-info alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <?php echo $success; ?> 
						</div>
						<?php } ?> 
					<!-- End Message Service -->

					<!-- Health Service -->
						<div class="row">
							<div class="col-md-3"><h3 style="margin-top: 0;">SketchIt</h3></div>
							<div class="col-md-8">
								<p style="font-size: 100%;">
									SketchIt creates a new revit file with input data.
								</p>
								<div class="row">
									<div class="col-md-3"><h3 style="margin-top: 0;">Ready</h3><p style="font-size: 100%;">App bundle</p></div>
									<div class="col-md-3"><h3 style="margin-top: 0;">12</h3><p style="font-size: 100%;">Version</p></div>
									<div class="col-md-3"><h3 style="margin-top: 0;">19</h3><p style="font-size: 100%;">Runs</p></div>
									<div class="clear"></div>
									<div class="col-md-3">
										<h3 style="margin-top: 0;">Actions</h3>
										<p style="font-size: 100%;">
											<a href="https://seanwittmeyer.com/app/lemur/run_app" data-toggle="tooltip" data-placement="top" title="" class="btn btn-sm btn-danger" data-original-title="Remove datasets older than 6 months">Run the App</a>
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12"><hr></div>
						</div>
					<!-- End Health Service -->

						<div class="row">
							<div class="col-md-3"><h3 style="margin-top: 0;">HarvestProjectData</h3></div>
							<div class="col-md-8">
								<p style="font-size: 100%;">
									Create and update a dashboard with health data for your Revit project.
								</p>
								<div class="row">
									<div class="col-md-3"><h3 style="margin-top: 0;">Ready</h3><p style="font-size: 100%;">App bundle</p></div>
									<div class="col-md-3"><h3 style="margin-top: 0;">2</h3><p style="font-size: 100%;">Version</p></div>
									<div class="col-md-3"><h3 style="margin-top: 0;">0</h3><p style="font-size: 100%;">Runs</p></div>
									<div class="clear"></div>
									<div class="col-md-3">
										<h3 style="margin-top: 0;">Actions</h3>
										<p style="font-size: 100%;">
											<a href="https://seanwittmeyer.com/app/lemur/run_app" data-toggle="tooltip" data-placement="top" title="" class="btn btn-sm btn-danger" data-original-title="Remove datasets older than 6 months">Run the App</a>
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-12"><hr></div>
						</div>
						<!-- End Content Area -->
					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->
