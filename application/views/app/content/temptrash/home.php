	<!-- Jumbotron -->
	<div class="container-jumbotron">
		<div class="bs-component">
			<div class="jumbotron jumbotron-home" style="background-image: url('/includes/test/assets/Moofushi_Kandu_fish.jpg');">
				<div class="container">
					<h1 class="light-text">Understanding Complexity </h1>
					<p class="light-text">Complexity theory helps us understand the changing world we live in, from the natural processes like ants finding food to the development of cities.</p>
					<p><a class="btn btn-primary btn-lg">Getting Started with Complexity</a> <a class="btn btn-success btn-lg" href="/includes/test/sitemap.txt">Show me topics</a></p>
				</div>
			</div>
		</div>
	</div>
	<!-- /Jumbotron -->
	<!-- General Content Block -->
	<div class="container top-nospace">
		<div class="page-header">
			<h1>Complex Adaptive Systems</h1>
			<h4>Theory Cartograph</h4>
		</div>
		<p class="lead">Paleo franzen farm-to-table, DIY next level chambray kickstarter. Meditation  deep v. Roof party yr chartreuse, <code>code highlight</code> flexitarian offal portland craft beer whatever wayfarers vegan. </p>
		<p>Mustache man braid migas, dreamcatcher normcore banjo everyday carry banh mi. Hammock intelligentsia banh mi blog. Chillwave banh mi XOXO knausgaard, put a bird on it ugh listicle. Tousled distillery truffaut mumblecore poutine intelligentsia irony squid.</p>
	</div>
	<!-- /General Content Block -->
	<!-- Panels -->
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<h3>Defining Features and Concepts</h3>
				<p>Explore the concepts and elements that make up complex adaptive systems (CAS) theory. </p>
				<div class="bs-component">
				
					<div class="panel panel-primary">
						<?php $ss = array('taxonomy',6); $set = $this->shared->get_data($ss[0],$ss[1],false,true); ?>
						<div class="panel-heading"><a style="color: white" href="/<?php echo $set['type']; ?>/<?php echo $set['slug']; ?>"><?php echo $set['title']; ?></a></div>
						<div class="panel-body">
							<div class="list-group">
								<?php 
								$set = $this->shared->get_related($ss[0],$ss[1]); 
								foreach ($set as $single) { ?><a id="df_list_<?php echo $single['id']; ?>" class="list-group-item" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><!--<span class="badge">6</span>--><?php echo $single['title']; ?></a><?php } ?> 
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<?php $ss = array('taxonomy',7); $set = $this->shared->get_data($ss[0],$ss[1],false,true); ?>
						<div class="panel-heading"><a style="color: #333" href="/<?php echo $set['type']; ?>/<?php echo $set['slug']; ?>"><?php echo $set['title']; ?></a></div>
						<div class="panel-body">
							<ul class="nav nav-pills">
								<?php 
								$set = $this->shared->get_related($ss[0],$ss[1]); 
								//print('<code>');var_dump($set);print('</code>');
								foreach ($set as $single) { ?><li class="" id="kc_list_<?php echo $single['id']; ?>"><a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></li><?php } ?> 
							</ul>
						</div>
					</div>


				</div>
			</div>


			<div class="col-lg-4">
				<h3>Applications &amp; Fields </h3>
				<p>Dive into complex adaptive systems theory and how is relates to the fields of architecture, planning, and urbanism.</p>
				<div class="bs-component">
				
					<div class="panel panel-success">
						<div class="panel-heading"><a style="color: white" href="/collection/urbanism">Urbanism and Planning</a></div>
						<div class="panel-body">
							<div class="list-group">
							<?php foreach ($this->shared->get_related('taxonomy','9') as $i) { ?><a href="/field/<?php echo $i['slug']; ?>" class="list-group-item"><?php echo $i['title']; ?></a><?php } ?> 
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Architecture</div>
						<div class="panel-body">
							Soon!
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">Other Fields</div>
						<div class="panel-body">
							Soon!
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-4">
				<div class="bs-component">
					<h3>The Feed</h3>
					<p>An evolving collection of examples and sharables relating to complex adaptive systems and its topics, definitions, thinkers, and applications.</p> 
					<?php $links = $this->shared->get_data('link',false,false,false,false,'5'); 
						if ($links === false) { ?> 
							<blockquote>Nothing in the feed...yet. Add links to the pages, definitions, and collections to see them here.</blockquote>
					<?php } else { ?> 
					<!-- CAS Embed -->
					<div class="cas-embed">
						<?php foreach ($links as $link) { ?><blockquote class="embedly-card" data-card-key="74435e49e8fa468eb2602ea062017ceb" data-card-controls="0"><h4><a href="<?php echo $link['uri']; ?>"><?php echo $link['title']; ?></a></h4><p><?php echo $link['excerpt']; ?></p></blockquote><?php } ?> 
					</div><!-- /CAS Embed -->
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- /Panels -->


