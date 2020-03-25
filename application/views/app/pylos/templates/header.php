<?php //setup
	if (!isset($loadjs)) $loadjs = array();
	?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="Pylos is a repository for computational and analytical design tools.">
	<meta name="author" content="Sean Wittmeyer">
	<link rel="icon" href="/includes/img/favicon.jpg">
	
	<title>Pylos - <?php echo $pagetitle; ?></title>
	
	<!-- Bootstrap core CSS -->
	<script src="/includes/js/pace.min.js"></script>
	<link href="/includes/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/includes/css/font-awesome.min.css" rel="stylesheet" />
	<link href="/includes/css/summernote.css" rel="stylesheet" />
	<link href="/includes/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/includes/css/pylos.css?r20200225a" rel="stylesheet" />
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/includes/js/jquery.min.js"><\/script>')</script>
	<!--<script src="/includes/js/typeahead.bundle.min.js"></script>-->
	<script src="/includes/js/fileinput.min.js"></script>
	<script src='/includes/js/selectize.min.0.12.4.js'></script>
	<link href='/includes/css/selectize.css' rel='stylesheet' />
	<?php if (isset($loadjs['mapbox'])) { ?> 
	<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.css' rel='stylesheet' />
	<?php } ?> 
	<?php if (isset($loadjs['hydrashare'])) { ?>
    <script src="/includes/app/pylos/d3.min.js"></script>
    <script src="/includes/app/pylos/highlight.min.js"></script>
    <script src="/includes/app/pylos/pylos.js"></script>
	<?php } ?> 
	<?php if (isset($loadjs['codeeditor'])) { ?>
	<script src="/includes/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
	<?php } ?> 
	<?php if (isset($loadjs['panzoom'])) { ?>
    <script src="/includes/js/jquery.panzoom.js"></script>
    <script src="/includes/js/jquery.mousewheel.js"></script>

	<?php } ?> 
	<?php if (isset($loadjs['charts'])) { ?> 
		<script>
		    document.write('<base href="' + document.location + '" />');
		</script>
		
		<!--
		  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Karla:400,400italic,700,700italic|Roboto:400,700italic,900italic,500italic,400italic,100italic,300italic,900,700,500,300,100|Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,800,700|Chivo:400,900,400italic,900italic|Pacifico|Dancing+Script:400,700|Cousine:400,700|Arimo:300,400,300italic,400italic,700|Nunito:400,300,700|Oxygen:400,700,300|Lato:100,200,400,700,900&subset=latin,latin-ext"/>
		-->
		
		<link rel="stylesheet" type="text/css" href="/includes/app/raw/bower_components/angular-bootstrap-colorpicker/css/colorpicker.css">
		<link rel="stylesheet" type="text/css" href="/includes/app/raw/bower_components/codemirror/lib/codemirror.css">
		
		<link rel="stylesheet" href="/includes/app/raw/css/raw.css"/>
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
	<script src="/includes/js/masonry.min.js"></script>
	<script src="/includes/js/imagesloaded.min.js"></script>
	<?php } ?> 
	<script type="text/javascript">
		function herowindowheight() {
			$('.windowheight').css({'min-height': window.innerHeight-140});
		}
		$(document).ready(herowindowheight);
	</script>
	 
</head>
