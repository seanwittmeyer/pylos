<?php //setup
	if (!isset($loadjs)) $loadjs = array();
	?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="Sean likes to ski and collect information and photos of the things he does.">
	<meta name="author" content="Sean Wittmeyer">
	<link rel="icon" href="/favicon.ico">
	
	<title>Sean's Website - <?php echo $pagetitle; ?></title>
	
	<!-- Bootstrap core CSS -->
	<script src="/includes/js/pace.min.js"></script>
	<link href="/includes/css/bootstrap.min.css" rel="stylesheet">
	<link href="/includes/css/font-awesome.min.css" rel="stylesheet">
	<link href="/includes/css/summernote.css" rel="stylesheet" />
	<link href="/includes/css/bootstrap-select.min.css" rel="stylesheet" />
	<?php if (isset($loadjs['rsvp'])) { ?> 
	<link rel="stylesheet" href="https://use.typekit.net/ant3tll.css">
	<?php } ?> 
	<link href="/includes/css/base.css?20191105" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/includes/js/jquery.min.js"><\/script>')</script>
	<!--<script src="/includes/js/typeahead.bundle.min.js"></script>-->
	<script src="/includes/js/fileinput.min.js"></script>
	<?php if (isset($loadjs['mapbox'])) { ?> 
	<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.css' rel='stylesheet' />
	<?php } ?> 
	<?php if (isset($loadjs['nanogallery'])) { ?> 
	<script src='/includes/js/jquery.nanogallery2.min.js'></script>
	<link href='/includes/css/nanogallery2.min.css' rel='stylesheet' />
	<?php } ?> 
	<?php if (isset($loadjs['masonry'])) { ?>
	<script>
	$( document ).ready(function() {
		function masonrygo() {
			$('.masonrygrid').masonry({
				itemSelector: '.masonryblock'
			});
		}
	});
	//	Pace.done(alert('done'));
	Pace.on('done', masonrygo());
	</script>
	<?php } ?> 

	<?php if (in_array('masonry', $loadjs)) { ?> 
	<script src="/includes/js/masonry.min.js"></script>
	<script src="/includes/js/imagesloaded.min.js"></script>
	<?php } ?> 
	<?php if (in_array('chartsjs', $loadjs)) { ?> 
	<script src="/includes/js/chart/Chart.bundle.2.7.3.js"></script>
	<script src="/includes/js/chart/utils.js"></script>
	<?php } ?> 
	<?php if (in_array('sparkline', $loadjs)) { ?> 
	<script src="/includes/js/jquery.sparkline.js"></script>
	<?php } ?> 
	<?php if (in_array('nvd3', $loadjs)) { ?> 
	<link href='/includes/css/nvd3.css?20181203' rel='stylesheet' />
	<script src="/includes/js/d3.v3.min.fixed.js"></script>
	<script src="/includes/js/nvd3-1.8.4.js"></script>
	<?php } ?> 
	<script type="text/javascript">
		function herowindowheight() {
			$('.windowminheight').css({'min-height': window.innerHeight-110});
			$('.windowheight').css({'min-height': window.innerHeight-110,'height': window.innerHeight-110});
			<?php if (isset($loadjs['skimaponload'])) { ?> 
			map.resize();
			map.fitBounds(bounds);
			<?php } ?> 
		}
		$(document).ready(herowindowheight);
	</script>
</head>
<body onresize="herowindowheight()">
	<!-- Navigation -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-sm-6 col-md-4"><h3 class="text-left"><a href="/" style="color:#3e3f3a !important;"><?php echo $this->config->item('site_name'); ?></a></h3></div>
			<div class="col-sm-4 tablet-hide hidden-xs"><h3 class="text-center"><?php echo $this->shared->weather(); ?></h3></div>
			<div class="col-sm-6 col-md-4">
				<nav class="text-right">
					<ul id="navbar" class="nav-list" style="margin-bottom: 0px;">
						<li class="dropdown<?php if ($section == 'ski') { ?> active<?php } ?>">
							<a href="/ski" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-snowflake-o" data-toggle="tooltip" data-placement="bottom" title="Ski"></i></h3></a>
						</li>
						<li class="dropdown<?php if ($section == 'football') { ?> active<?php } ?>">
							<a href="/football" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-futbol-o" data-toggle="tooltip" data-placement="bottom" title="Football"></i></h3></a>
						</li>
						<li class="dropdown<?php if ($section == 'trains') { ?> active<?php } ?>">
							<a href="/trains" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-train" data-toggle="tooltip" data-placement="bottom" title="Trains"></i></h3></a>
						</li>
						<li class="dropdown<?php if ($section == 'code') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-code" data-toggle="tooltip" data-placement="bottom" title="Code"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header">These are some of the digital tools and <br />musings I've put time into over the years.<br />Online platform for sharing <br />panos and VR experiences</li>
								<li><a href="/app/vr">VR Platform</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Repository for Computational and <br />Environmental Design Tools</li>
								<li><a href="/app/pylos">Pylos</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Self Organized Lunch Delivery</li>
								<li><a href="//lunch.zilifone.net">Time for Lunch</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">CAS Explorer in Action</li>
								<li><a href="/diagrams">Crowdsourcing CAS Diagrams</a></li>
								<li><a href="/collection/urbanism">CAS and Urbanism Visualization</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Visualizations</li>
								<li><a href="/collection/urbanism">CAS and Urbanism Diagram</a></li>
								<li class="disabled"><a href="#">Associations Network Graph</a></li>
								<li class="disabled"><a href="#">More Soon!</a></li>
							</ul>
						</li>
						<li class="dropdown<?php if ($section == 'trip') { ?> active<?php } ?>">
							<a href="/trips" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-binoculars" data-toggle="tooltip" data-placement="bottom" title="Trips"></i></h3></a>
						</li>
						<li class="dropdown<?php if ($section == 'portfolio') { ?> active<?php } ?>">
							<a href="http://yup.seanwittmeyer.com/seanwittmeyer_portfolio.pdf" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-book" data-toggle="tooltip" data-placement="bottom" title="Portfolio"></i></h3></a>
						</li>
						<li class="dropdown<?php if ($section == 'resume') { ?> active<?php } ?>">
							<a href="http://yup.seanwittmeyer.com/resume_seanwittmeyer.pdf" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-file-text-o" data-toggle="tooltip" data-placement="bottom" title="Resume/CV"></i></h3></a>
						</li>
						<li class="dropdown<?php if ($section == 'musings') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-comments" data-toggle="tooltip" data-placement="bottom" title="Musings"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header">The CAS Explorer is an interpretation of complexity <br />theory and how it is applied to urbanism, <br />based on research by Sharon Wohl.</li>
								<li><a href="/theme/urbanism">Overview & Cartograph</a></li>
								<li class="dropdown-header">Focus</li>
								<li><a href="/theme/design-build">Community Building Architecture</a></li>
								<li><a href="/theme/urbanism">Complexity Theory</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">General Themes</li>
								<?php foreach ($this->shared->get_related('taxonomy','34') as $i) { ?><li><a href="/theme/<?php echo $i['slug']; ?>"><?php echo $i['title']; ?></a></li><?php } ?> 
								<li role="separator" class="divider"></li>
								<li><a href="/topic/attributes">Attributes</a></li>
								<li><a href="/topic/key-thinkers">Key Thinkers</a></li>
								<li><a href="/topic/defining-texts">Defining Texts</a></li>
								<li><a href="/topic/defining-features">Defining Features</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/taxonomy">View all Taxonomy, Categories, and Collections</a></li>
								<li><a href="/definition">View all Definitions, Thinkers, and Attributes</a></li>
								<li class="dropdown-header">Feeds</li>
								<li><a href="/feed/video">Videos</a></li>
								<li><a href="/feed/html">Webpages</a></li>
								<li><a href="/feed/file">Files</a></li>
								<li><a href="/feed/paper">Papers</a></li>
								<li><a href="/feed/book">Books</a></li>
								<li><a href="/feed/profile">Profiles</a></li>
								<li><a href="/feed/other">Other</a></li>
							</ul>
						</li>
						<li class="dropdown<?php if ($section == 'about') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-cog" data-toggle="tooltip" data-placement="bottom" title="Manage"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header"><i>This is Builder</i>, my platform for trying new <br />things while infusing technology into <br />architecture, by Sean Wittmeyer.</li>
								<li><a href="/about">Who is Sean?</a></li>
								<li><a href="/dev-timeline">Dev Timeline</a></li>
								<li><a href="/contact">Contact</a></li>
								<li role="separator" class="divider"></li>
								<?php if($this->ion_auth->logged_in()) { ?>
								<li class="dropdown-header">Hey <?php $user = $this->ion_auth->user()->row(); echo $user->first_name; ?>, feel free to edit and add <br />to the site with these tools</li>
								<li><a data-toggle="modal" data-target="#pageeditor"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this page</a></li>
								<li><a data-toggle="modal" data-target="#pageupload"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Edit the header image</a></li>
								<li<?php if (!isset($id)) echo ' class="disabled"'; ?>><a <?php if (isset($id)) echo 'data-toggle="modal" data-target="#createlink" '; ?>href="#"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Add a feed item</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Add Content</li>
								<li><a data-toggle="modal" data-target="#createdefinition"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> New Definition</a></li>
								<li><a data-toggle="modal" data-target="#createtaxonomy" href="#"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> New Taxonomy (or collection)</a></li>
								<li><a data-toggle="modal" data-target="#createtrippast"><span class="glyphicon glyphicon-road" aria-hidden="true"></span> New Trip (past from photos)</a></li>
								<li><a data-toggle="modal" data-target="#createtripfuture"><span class="glyphicon glyphicon-road" aria-hidden="true"></span> New Trip (future)</a></li>

								<li role="separator" class="divider"></li>
								<li><a data-toggle="modal" data-target="#createpage" href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Page</a></li>
								<li class="disabled"><a data-toggle="modal" data-target="#createpost" href="#"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> New Blog Post</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Platform Management</li>
								<li><a href="/auth">User Administration</a></li>
								<li><a href="/admin">Site Administration</a></li>
								<li><a href="/api">API Details</a></li>
								<li><a <?php echo ($this->ion_auth->logged_in()) ? 'href="/auth/logout" onclick="$(this).text(\'See ya later...\');"' : 'data-toggle="modal" data-target="#loginmodal"'; ?>><?php echo ($this->ion_auth->logged_in()) ? 'I\'m done, Sign Out &rarr;':'Sign In'; ?></a></li>
								<?php } else { ?>
								<li class="dropdown-header">Sign in to engage with Builder</li>
								<li style="margin: 0 20px;"><a data-toggle="modal" data-target="#loginmodal" class="btn btn-success btn-md">Sign In / Get Started</a></li>
								<?php } ?>
							</ul>
						</li>
						<?php if($this->ion_auth->logged_in()) { ?>
						<li class="dropdown">
							<a data-toggle="modal" data-target="#pageeditor"><h3 aria-hidden="true"><i class="fa fa-pencil text-danger" data-toggle="tooltip" data-placement="bottom" title="Edit"></i></h3></a>
						</li>
						<?php } ?>
						</li>
					</ul><!--/.nav-collapse -->
				</nav>
			</div>
		</div>
	</div>
	<!-- /Navigation -->
