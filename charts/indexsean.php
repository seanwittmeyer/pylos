<!doctype html>
<html lang="en" ng-app="raw">
<head>
  <title>Charts - Sean Wittmeyer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="Keywords" content="RAW,visualization,data,design,spreadsheet,interface,vector graphics,SVG,PNG,JSON">
  <meta name="Description" content="RAW, missing link between spreadsheets and data visualization, by DensityDesign and Calibro, Giorgio Caviglia, Matteo Azzi, Michele Mauri, Giorgio Uboldi, Tommaso Elli">
  <meta name="author" content="DensityDesign Research Lab and Studio Calibro">

  <!-- Social Networks meta tags -->
  <meta property="og:title" content="RAWGraphs">
  <meta property="og:description" content="The missing link between spreadsheets and data visualization.">
  <meta property="og:image" content="//app.rawgraphs.io/imgs/cover-for-socials.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:url" content="//app.rawgraphs.io/">
  <meta property="og:type" content="article">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@rawgraphs">

  <script>
    document.write('<base href="' + document.location + '" />');
  </script>

  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Karla:400,400italic,700,700italic|Roboto:400,700italic,900italic,500italic,400italic,100italic,300italic,900,700,500,300,100|Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,800,700|Chivo:400,900,400italic,900italic|Pacifico|Dancing+Script:400,700|Cousine:400,700|Arimo:300,400,300italic,400italic,700|Nunito:400,300,700|Oxygen:400,700,300|Lato:100,200,400,700,900&subset=latin,latin-ext"/>

  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="bower_components/angular-bootstrap-colorpicker/css/colorpicker.css">
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="bower_components/codemirror/lib/codemirror.css">

  <link rel="stylesheet" href="css/raw.css"/>
  <link rel="icon" href="favicon.ico?v=2" type="image/x-icon">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link href="/includes/css/bootstrap.min.css" rel="stylesheet">
	<link href="/includes/css/bootstrap-select.min.css" rel="stylesheet" />
  <link href="/includes/css/pylos.css" rel="stylesheet">

</head>

<body data-spy="scroll" data-target="#raw-nav">
	<!-- Header -->
<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-sm-6 col-md-4"><h3 class="text-left">This is Sean's Website.</h3></div>
			<div class="col-sm-4 tablet-hide hidden-xs"><h3 class="text-center">68° and Clear in Portland</h3></div>
			<div class="col-sm-6 col-md-4">
				<nav class="text-right">
					<ul id="navbar" class="nav-list" style="margin-bottom: 0px;">
						<li class="dropdown">
							<a href="/ski" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-snowflake-o" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Ski"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-code" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Code"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header">These are some of the digital tools and <br>musings I've put time into over the years.<br>Online platform for sharing <br>panos and VR experiences</li>
								<li><a href="/app/vr">VR Platform</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Repository for Computational and <br>Environmental Design Tools</li>
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
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-binoculars" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Trips"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header">Trips and Photos</li>
								<li><a href="/trip">Trips</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">Photos from Past Trips</li>
								<li><a href="//photos.seanwittmeyer.com/">My Photos</a></li>
								<li><a href="//photos.seanwittmeyer.com/images">Places Archive</a></li>
								<li><a href="//photos.seanwittmeyer.com/images/Personal">Personal Archive</a></li>
															</ul>
						</li>
						<li class="dropdown">
							<a href="//yup.seanwittmeyer.com/seanwittmeyer_portfolio.pdf" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-book" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Portfolio"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="//yup.seanwittmeyer.com/resume_seanwittmeyer.pdf" class="dropdown-toggle" role="button"><h3 aria-hidden="true"><i class="fa fa-file-text-o" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Resume/CV"></i></h3></a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-comments" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Musings"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header">The CAS Explorer is an interpretation of complexity <br>theory and how it is applied to urbanism, <br>based on research by Sharon Wohl.</li>
								<li><a href="/theme/urbanism">Overview &amp; Cartograph</a></li>
								<li class="dropdown-header">Focus</li>
								<li><a href="/theme/design-build">Community Building Architecture</a></li>
								<li><a href="/theme/urbanism">Complexity Theory</a></li>
								<li role="separator" class="divider"></li>
								<li class="dropdown-header">General Themes</li>
								<li><a href="/theme/landscape-architecture">Landscape Architecture</a></li><li><a href="/theme/idioms-and-phrases">Idioms and Phrases</a></li><li><a href="/theme/web">Graphics and Web</a></li><li><a href="/theme/games">Games</a></li><li><a href="/theme/design-build">Design Build</a></li><li><a href="/theme/architecture">Architecture</a></li><li><a href="/theme/vrar">VR/AR</a></li> 
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
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><h3 aria-hidden="true"><i class="fa fa-cog" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Manage"></i></h3></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header"><i>This is Builder</i>, my platform for trying new <br>things while infusing technology into <br>architecture, by Sean Wittmeyer.</li>
								<li><a href="/about">Who is Sean?</a></li>
								<li><a href="/dev-timeline">Dev Timeline</a></li>
								<li><a href="/contact">Contact</a></li>
								<li role="separator" class="divider"></li>
																<li class="dropdown-header">Hey Sean, feel free to edit and add <br>to the site with these tools</li>
								<li><a data-toggle="modal" data-target="#pageeditor"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this page</a></li>
								<li><a data-toggle="modal" data-target="#createlink" href="#"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Add a feed item</a></li>
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
								<li><a href="/auth/logout" onclick="$(this).text('See ya later...');">I'm done, Sign Out →</a></li>
															</ul>
						</li>
												<li class="dropdown">
							<a data-toggle="modal" data-target="#pageeditor"><h3 aria-hidden="true"><i class="fa fa-pencil text-danger" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit"></i></h3></a>
						</li>
												
					</ul><!--/.nav-collapse -->
				</nav>
			</div>
		</div>
	</div>						<!-- Content Area -->
	<div ng-view class="wrap build-wrapper"></div>
	<div class="row build-wrapper">
		<div class="col-lg-10 col-lg-offset-2"><p>The content and tools of the Pylos charts page comes from the RAW open source project by the talented DensityDesign Lab and Calibro Giorgio Caviglia, Michele Mauri, Giorgio Uboldi, Matteo Azzi. © 2013-2017 (Apache License 2.0)</p></div>
	</div>			
						<!-- End Content Area -->

	<!-- /Top -->
	<!-- Panels -->

  <!-- jquery -->
  <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="bower_components/jquery-ui/jquery-ui.min.js"></script>
  <script type="text/javascript" src="bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js"></script>
  <!-- bootstrap -->
  <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
  <!-- d3 -->
  <script type="text/javascript" src="bower_components/d3/d3.min.js"></script>
  <!-- d3 plugins -->
  <script type="text/javascript" src="bower_components/d3-voronoi/d3-voronoi.min.js"></script>
  <script type="text/javascript" src="bower_components/d3-hexbin/d3-hexbin.min.js"></script>
  <script type="text/javascript" src="bower_components/d3-sankey/d3-sankey.min.js"></script>
  <script type="text/javascript" src="bower_components/d3-contour/d3-contour.min.js"></script>
  <script type="text/javascript" src="bower_components/d3-box/index.js"></script>
  <!-- codemirror -->
  <script type="text/javascript" src="bower_components/codemirror/lib/codemirror.js"></script>
  <script type="text/javascript" src="bower_components/codemirror/addon/display/placeholder.js"></script>
  <!-- canvastoblob -->
  <script type="text/javascript" src="bower_components/canvas-toBlob.js/canvas-toBlob.js"></script>
  <!-- filesaver -->
  <script type="text/javascript" src="bower_components/FileSaver/index.js"></script>
  <!-- zeroclipboard -->
  <script type="text/javascript" src="bower_components/zeroclipboard/dist/ZeroClipboard.min.js"></script>
  <!-- raw -->
  <script type="text/javascript" src="lib/raw.js"></script>

  <!-- charts -->
  <script src="charts/treemap.js"></script>
  <script src="charts/streamgraph.js"></script>
  <script src="charts/scatterPlot.js"></script>
  <script src="charts/packing.js"></script>
  <script src="charts/clusterDendrogram.js"></script>
  <script src="charts/voronoi.js"></script>
  <script src="charts/alluvial.js"></script>
  <script src="charts/convexHullMultiple.js"></script>
  <script src="charts/hexagonalBinning.js"></script>
  <script src="charts/parallelCoordinates.js"></script>
  <script src="charts/circularDendrogram.js"></script>
  <script src="charts/smallMultiplesArea.js"></script>
  <script src="charts/bumpChart.js"></script>
  <script src="charts/sunburst.js"></script>
  <script src="charts/gantt.js"></script>
  <script src="charts/horizonChart.js"></script>
  <script src="charts/barChart.js"></script>
  <script src="charts/pieChart.js"></script>
  <script src="charts/contourPlot.js"></script>
  <script src="charts/beeswarmPlot.js"></script>
  <script src="charts/boxPlot.js"></script>

  <!-- angular -->
  <script type="text/javascript" src="bower_components/angular/angular.min.js"></script>
  <script type="text/javascript" src="bower_components/angular-route/angular-route.min.js"></script>
  <script type="text/javascript" src="bower_components/angular-animate/angular-animate.min.js"></script>
  <script type="text/javascript" src="bower_components/angular-sanitize/angular-sanitize.min.js"></script>
  <script type="text/javascript" src="bower_components/angular-strap/dist/angular-strap.min.js"></script>
  <script type="text/javascript" src="bower_components/angular-strap/dist/angular-strap.tpl.min.js"></script>
  <script type="text/javascript" src="bower_components/angular-ui/build/angular-ui.js"></script>
  <script type="text/javascript" src="bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js"></script>
  <script type="text/javascript" src="bower_components/ng-file-upload/ng-file-upload.min.js"></script>
  <script type="text/javascript" src="bower_components/is_js/is.min.js"></script>
  <script type="text/javascript" src="bower_components/js-xlsx/dist/xlsx.full.min.js"></script>


  <script src="js/app.js"></script>
  <script src="js/services.js"></script>
  <script src="js/controllers.js"></script>
  <script src="js/filters.js"></script>
  <script src="js/directives.js"></script>

  <!-- google analytics --
  <script src="js/analytics.js"></script>
  <!-- -->

</body>
</html>
