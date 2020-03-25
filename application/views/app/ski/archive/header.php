<?php //setup
	if (!isset($loadjs)) $loadjs = array();
	?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="Sean Wittmeyer">
	<link rel="icon" href="/favicon.ico">
	
	<title>Builder - <?php echo $pagetitle; ?></title>
	
	<!-- Bootstrap core CSS -->
	<script src="/includes/js/pace.min.js"></script>
	<link href="/includes/css/bootstrap.min.css" rel="stylesheet">
	<link href="/includes/css/summernote.css" rel="stylesheet" />
	<link href="/includes/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/includes/css/base.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/includes/js/jquery.min.js"><\/script>')</script>
	<!--<script src="/includes/js/typeahead.bundle.min.js"></script>-->
	<script src="/includes/js/fileinput.min.js"></script>
	<?php if (isset($loadjs['masonry'])) { ?>
	<script>
	$( document ).ready(function() {
		function masonrygo() {
			$('.masonrygrid').masonry({
				itemSelector: '.masonryblock'
			});
		}
	});
//		Pace.done(alert('done'));
	Pace.on('done', masonrygo());
	</script>
	<?php } ?>

	<?php if (in_array('masonry', $loadjs)) { ?>
	<script src="/includes/js/masonry.min.js"></script>
	<script src="/includes/js/imagesloaded.min.js"></script>
	<?php } ?> 
</head>
<body>
	<!-- Navigation -->
	<div class="container">
		<nav class="navbar navbar-fixed-top navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span></a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Themes <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Focus</li>
								<li><a href="/theme/design-build">Community Building Architecture</a></li>
								<li><a href="/theme/urbanism">Complexity Theory</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">General Themes</li>
								<?php foreach ($this->shared->get_related('taxonomy','34') as $i) { ?><li><a href="/theme/<?php echo $i['slug']; ?>"><?php echo $i['title']; ?></a></li><?php } ?> 
							</ul>
						</li>
						<li class="dropdown<?php if ($section == 'complexity') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Complexity Theory <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">The CAS Explorer is an interpretation of complexity <br />theory and how it is applied to urbanism, <br />based on research by Sharon Wohl.</li>
								<li><a href="/theme/urbanism">Overview & Cartograph</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/topic/attributes">Attributes</a></li>
								<li><a href="/topic/key-thinkers">Key Thinkers</a></li>
								<li><a href="/topic/defining-texts">Defining Texts</a></li>
								<li><a href="/topic/defining-features">Defining Features</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/taxonomy">View all Taxonomy, Categories, and Collections</a></li>
								<li><a href="/definition">View all Definitions, Thinkers, and Attributes</a></li>
							</ul>
						</li>
						<li class="dropdown<?php if ($section == 'app') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Playground <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Online platform for sharing <br />panos and VR experiences</li>
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
						<li class="dropdown<?php if ($section == 'engage') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">The Archives <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Feeds</li>
								<li><a href="/feed/video">Videos</a></li>
								<li><a href="/feed/html">Webpages</a></li>
								<li><a href="/feed/file">Files</a></li>
								<li><a href="/feed/paper">Papers</a></li>
								<li><a href="/feed/book">Books</a></li>
								<li><a href="/feed/profile">Profiles</a></li>
								<li><a href="/feed/other">Other</a></li>
								<li role="separator" class="divider"></li>
								<?php if($this->ion_auth->logged_in()) { ?>
								<li class="dropdown-header">Signed in as <?php $user = $this->ion_auth->user()->row(); echo $user->first_name.' '.$user->last_name; ?></li>
								<li><a href="/help">Help</a></li>
								<li><a href="/auth/logout">Sign Out</a></li>
								<?php } else { ?>
								<li class="dropdown-header">Sign in to engage with the Builder</li>
								<li><a data-toggle="modal" data-target="#loginmodal" class="btn btn-success btn-md">Sign In / Get Started</a></li>
								<li><a href="/help" class="btn btn-successs btn-md">Help</a></li>
								<?php } ?>
							</ul>
						</li>
	
					</ul>
					<!--
					<form class="navbar-form navbar-left" role="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Find topics...">
						</div>
						<button type="submit" class="btn btn-default">Go!</button>
					</form>
					-->
					<ul class="nav navbar-nav navbar-right">
						<?php if($this->ion_auth->logged_in()) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Page Editor</li>
								<li><a data-toggle="modal" data-target="#pageeditor"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this page</a></li>
								<li<?php if (!isset($id)) echo ' class="disabled"'; ?>><a <?php if (isset($id)) echo 'data-toggle="modal" data-target="#createlink" '; ?>href="#"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Add a feed item</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Add Content</li>
								<li><a data-toggle="modal" data-target="#createdefinition"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> New Definition</a></li>
								<li class="disabled"><a data-toggle="modal" data-target="#createpaper" href="#"><span class="glyphicon glyphicon-education" aria-hidden="true"></span> New Paper</a></li>
								<li><a data-toggle="modal" data-target="#createtaxonomy" href="#"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> New Taxonomy (category or collection)</a></li>
								<li role="separator" class="divider"></li>
								<li><a data-toggle="modal" data-target="#createpage" href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Page</a></li>
								<li class="disabled"><a data-toggle="modal" data-target="#createpost" href="#"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> New Blog Post</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Signed in as <?php $user = $this->ion_auth->user()->row(); echo $user->first_name.' '.$user->last_name; ?></li>
								<li><a href="/auth">User Administration</a></li>
								<li><a href="/admin">Site Administration</a></li>
								<li><a href="/help">Help</a></li>
								<li><a href="/auth/logout">Sign Out</a></li>
							</ul>
						</li>
						<?php } ?>
						<li class="dropdown<?php if ($section == 'research') { ?> active<?php } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">This is <i>Builder</i> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header"><i>Builder</i> is a platform for trying<br />new things while infusing <br />technology into architecture, <br />by Sean Wittmeyer.</li>
								<li><a href="/about">About Sean</a></li>
								<li><a href="/blog">Blog</a></li>
								<li><a href="/contact">Contact</a></li>
								<li><a <?php echo ($this->ion_auth->logged_in()) ? 'href="/auth/logout" onclick="$(this).text(\'See ya later...\');"' : 'data-toggle="modal" data-target="#loginmodal"'; ?>><?php echo ($this->ion_auth->logged_in()) ? 'Sign Out':'Sign In'; ?></a></li>
							</ul>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
	</div>
	<!-- /Navigation -->
