<?php 
	$filter = (isset($filter)) ? $filter : false; 
	$fullwidth = (isset($fullwidth)) ? $fullwidth : false; 	
?><body>
	<!-- Header -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-sm-3"><h3 class="text-left hidden-lg"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Pylos</a></h3></div>
			<div class="col-sm-5 col-xl-6"><h5 class="text-left">This is a repository of computational and analytical design tools used at ZGF, <a href="/get-started">learn more &rarr;</a></h5></div>
			<div class="col-sm-4 col-xl-3"><h5 class="text-right"><?php if($this->ion_auth->logged_in()) { ?>Good <?php echo (date('a') == 'am') ? 'morning ' : 'afternoon '; $user = $this->ion_auth->user()->row(); echo $user->first_name; ?>. 
				<?php if ($this->ion_auth->logged_in()) { ?> 
				<div style="display: inline-block">
					<a href="#" id="dropdownnew" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span aria-hidden="true">New</span></a>
					<ul id="dropdownnenwsmenu" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownnew">
						<?php if($this->ion_auth->logged_in()) { ?>
						<li class="dropdown-header">Hey <?php $user = $this->ion_auth->user()->row(); echo $user->first_name; ?>, feel free to edit and add <br />to the site with these tools</li>
						<li><a data-toggle="modal" data-target="#pageeditor"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this page</a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Add your resources to Pylos</li>
						<li><a data-toggle="modal" data-target="#createpage" href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Page</a></li>
						<li><a href="/designexplorer/create"><span class="fa fa-bar-chart" aria-hidden="true"></span> New Design Explorer Dataset</a></li>
						<li><a href="/pylos/blocks/create"><span class="fa fa-puzzle-piece" aria-hidden="true"></span> New Block</a></li>
						<li><a href="/pylos/guides/create"><span class="fa fa-th-list" aria-hidden="true"></span> New Tutorial/Guide</a></li>
						<li><a href="/pylos/presentations/create"><span class="fa fa-television" aria-hidden="true"></span> New Presentation</a></li>
					</ul>
				| 
				</div>
				<?php } ?>
				<div style="display: inline-block">
					<a href="#" id="dropdowntools" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span aria-hidden="true">Tools</span></a>
					<ul id="dropdowntoolsmenu"  class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdowntools">
						<li class="dropdown-header"><i>This is Pylos</i>, a platform for sharing <br />design tools, tutorials, and guides <br />intended to make computational and <br />high performance design more accessible. </li>
						<li><a href="/get-started">Get Started with Pylos</a></li>
						<li><a href="/roadmap">Development Roadmap</a></li>
						<li><a href="/status">Project Status</a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Platform Management</li>
						<li><a href="/auth">User Administration</a></li>
						<li><a href="/pylos/admin">Site Administration</a></li>
						<li><a href="/api">API Details</a></li>
						<li><a <?php echo ($this->ion_auth->logged_in()) ? 'href="/auth/saml/logout" onclick="$(this).text(\'See ya later...\');"' : 'data-toggle="modal" data-target="#loginmodal"'; ?>><?php echo ($this->ion_auth->logged_in()) ? 'I\'m done, Sign Out &rarr;':'Sign In'; ?></a></li>
						<?php } else { ?>
						<li class="dropdown-header">Sign in to engage with Pylos</li>
						<li style="margin: 0 20px;"><a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" class="btn btn-success btn-md">Sign In / Get Started</a></li>
						<?php } ?>
					</ul>
					| <a href="/auth/saml/logout" onclick="$(this).text('logging out...');">Logout &rarr;</a><?php } else { ?>Hello, care to <a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" onclick="$(this).text('signing you in...');">sign in?</a><?php } ?></h5></div>
				</div>
		</div>
	</div>
	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php $this->load->view('app/pylos/templates/menu-beta'); ?>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-<?php echo ($fullwidth) ? 12 : 9; ?>  col-sm-12">
						<div class="headline-parent">
							<h1 class="headline">
								<img src="https://build.seanwittmeyer.com/includes/img/ppt_icon.png" style="display: block; float: right; width: 50px; ">
								<span style=" display: block; font-weight: 300; letter-spacing: -.05em; font-size: 24px; font-style: italic;">ZGF Project Performance Team</span>
								<?php echo $contenttitle; ?>
							</h1>
						</div>
