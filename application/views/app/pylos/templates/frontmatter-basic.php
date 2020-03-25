<?php 
	$heromenu = (isset($heromenu)) ? $heromenu : false; 
	$filter = (isset($filter)) ? $filter : false; 
	$fullwidth = (isset($fullwidth)) ? $fullwidth : false; 	
?><body>
	<!-- Header -->

	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-sm-6 col-md-3"><h3 class="text-left"><a href="/" style="color:#3e3f3a !important;"><span class="firmlogotype">ZGF</span> Pylos</a></h3></div>
			<div class="col-sm-4 tablet-hide hidden-xs"><h3 class="text-left">Project Performance Library</h3></div>
			<div class="col-sm-6 col-md-5">
				<nav class="text-right">
					<ul id="navbar" class="nav-list" style="margin-bottom: 0px;">
						<li class="dropdown">
							<a href="<?php echo site_url("pylos/strategies"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-trophy" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Strategies"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo site_url("pylos/tools"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-magic" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Tools"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo site_url("pylos/guides"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-mortar-board" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Guides"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo site_url("pylos/blocks"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-puzzle-piece" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Blocks"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo site_url("designexplorer"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-bar-chart" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Datasets"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo site_url("pylos/projects"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-university" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Projects"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="<?php echo site_url("pylos/presentations"); ?>" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-television" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Presentations"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-bars" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Create and Manage"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header"><i>This is Pylos</i>, a platform for sharing <br />design tools, tutorials, and guides <br />intended to make computational and <br />high performance design more accessible. </li>
								<li role="separator" class="divider"></li>
								<li><a href="/get-started">Get Started with Pylos</a></li>
								<li><a href="/roadmap">Development Roadmap</a></li>
								<li><a href="/status">Project Status</a></li>
								<li role="separator" class="divider"></li>
								<?php if($this->ion_auth->logged_in()) { ?>
								<li class="dropdown-header">Hey <?php $user = $this->ion_auth->user()->row(); echo $user->first_name; ?>, feel free to edit and add <br />to the site with these tools</li>
								<li><a data-toggle="modal" data-target="#pageeditor"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this page</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Add your resources to Pylos</li>
								<li><a data-toggle="modal" data-target="#createpage" href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Page</a></li>
								<li><a href="/designexplorer/create"><span class="fa fa-bar-chart" aria-hidden="true"></span> New Design Explorer Dataset</a></li>
								<li><a href="/pylos/blocks/create"><span class="fa fa-puzzle-piece" aria-hidden="true"></span> New Block</a></li>
								<li><a href="/pylos/guides/create"><span class="fa fa-mortar-board" aria-hidden="true"></span> New Tutorial/Guide</a></li>
								<li><a href="/pylos/presentations/create"><span class="fa fa-television" aria-hidden="true"></span> New Presentation</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Platform Management</li>
								<li><a href="/auth">User Administration</a></li>
								<li><a href="/pylos/admin">Site Administration</a></li>
								<li><a href="/api">API Details</a></li>
								<li><a <?php echo ($this->ion_auth->logged_in()) ? 'href="/auth/saml/logout" onclick="$(this).text(\'See ya later...\');"' : 'data-toggle="modal" data-target="#loginmodal"'; ?>><?php echo ($this->ion_auth->logged_in()) ? 'I\'m done, Sign Out &rarr;':'Sign In'; ?></a></li>
								<?php } else { ?>
								<li class="dropdown-header">We are glad you are here, care to sign in?</li>
								<li style="margin: 0 20px;"><a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" class="btn btn-success btn-md">Sign In / Get Started</a></li>
								<?php } ?>
							</ul>
						</li>

					</ul><!--/.nav-collapse -->
				</nav>
			</div>
		</div>
	</div>

