<body data-spy="scroll" data-target="#raw-nav">
	<!-- Header -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-sm-3"><h3 class="text-left hidden-lg"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Pylos</a></h3></div>
			<div class="col-sm-9 hidden-xs"></div>
		</div>
	</div>
	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<div class="row">
					<div class="col-sm-5 hidden-xs">
						<div class="headline-parent"><h3 class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Pylos</a></h3></div>
					</div>
					<?php $this->load->view('pylos/templates/menu'); ?>
				</div>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-12">
						<div class="headline-parent"><h1 class="text-left headline" id="hydratitle">Charts</h1></div>
						<!-- Content Area -->
						<div class="row">
							<div class="col-lg-12">
								<div ng-view class="wrap"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-10"><p>The content and tools of the Pylos charts page comes from the RAW open source project by the talented DensityDesign Lab and Calibro Giorgio Caviglia, Michele Mauri, Giorgio Uboldi, Matteo Azzi. © 2013-2017 (Apache License 2.0)</p></div>
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

  <!-- jquery -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/jquery-ui/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js"></script>
  <!-- bootstrap -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
  <!-- d3 -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/d3/d3.min.js"></script>
  <!-- d3 plugins -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/d3-voronoi/d3-voronoi.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/d3-hexbin/d3-hexbin.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/d3-sankey/d3-sankey.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/d3-contour/d3-contour.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/d3-box/index.js"></script>
  <!-- codemirror -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/codemirror/lib/codemirror.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/codemirror/addon/display/placeholder.js"></script>
  <!-- canvastoblob -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/canvas-toBlob.js/canvas-toBlob.js"></script>
  <!-- filesaver -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/FileSaver/index.js"></script>
  <!-- zeroclipboard -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/zeroclipboard/dist/ZeroClipboard.min.js"></script>
  <!-- raw -->
  <script type="text/javascript" src="/includes/app/raw/lib/raw.js"></script>

  <!-- charts -->
  <script src="/includes/app/raw/charts/treemap.js"></script>
  <script src="/includes/app/raw/charts/streamgraph.js"></script>
  <script src="/includes/app/raw/charts/scatterPlot.js"></script>
  <script src="/includes/app/raw/charts/packing.js"></script>
  <script src="/includes/app/raw/charts/clusterDendrogram.js"></script>
  <script src="/includes/app/raw/charts/voronoi.js"></script>
  <script src="/includes/app/raw/charts/alluvial.js"></script>
  <script src="/includes/app/raw/charts/convexHullMultiple.js"></script>
  <script src="/includes/app/raw/charts/hexagonalBinning.js"></script>
  <script src="/includes/app/raw/charts/parallelCoordinates.js"></script>
  <script src="/includes/app/raw/charts/circularDendrogram.js"></script>
  <script src="/includes/app/raw/charts/smallMultiplesArea.js"></script>
  <script src="/includes/app/raw/charts/bumpChart.js"></script>
  <script src="/includes/app/raw/charts/sunburst.js"></script>
  <script src="/includes/app/raw/charts/gantt.js"></script>
  <script src="/includes/app/raw/charts/horizonChart.js"></script>
  <script src="/includes/app/raw/charts/barChart.js"></script>
  <script src="/includes/app/raw/charts/pieChart.js"></script>
  <script src="/includes/app/raw/charts/contourPlot.js"></script>
  <script src="/includes/app/raw/charts/beeswarmPlot.js"></script>
  <script src="/includes/app/raw/charts/boxPlot.js"></script>

  <!-- angular -->
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular/angular.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-route/angular-route.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-animate/angular-animate.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-strap/dist/angular-strap.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-strap/dist/angular-strap.tpl.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-ui/build/angular-ui.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/ng-file-upload/ng-file-upload.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/is_js/is.min.js"></script>
  <script type="text/javascript" src="/includes/app/raw/bower_components/js-xlsx/dist/xlsx.full.min.js"></script>


  <script src="/includes/app/raw/js/app.js"></script>
  <script src="/includes/app/raw/js/services.js"></script>
  <script src="/includes/app/raw/js/controllers.js"></script>
  <script src="/includes/app/raw/js/filters.js"></script>
  <script src="/includes/app/raw/js/directives.js"></script>

  <!-- google analytics -->
  <script src="/includes/app/raw/js/analytics.js"></script>
</body>
</html>
