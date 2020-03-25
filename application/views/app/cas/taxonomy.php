	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="bs-component">
			<div class="jumbotron jumbotron-home" id="imgheader" style="background-image: url('<?php echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: '/includes/test/assets/Moofushi_Kandu_fish.jpg'; ?>');">
				<div class="container">
					<h1 class="light-text"><?php echo $title; ?><!-- (<?php echo $id; ?>)--></h1>
					<p class="light-text"><?php echo $this->shared->handlebar_links($excerpt); ?></p>
					<div style="position: relative; top: 0px; right: 0px; float:right;">
						<?php if ($this->ion_auth->logged_in()) { ?><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#pageupload"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Image</button><?php } ?>
						<button type="button" class="btn btn-default btn-xs" data-toggle="popover" data-placement="top" data-trigger="hover" title="Image Credit" data-content="<?php echo (isset($img['header']['caption']) && !empty($img['header']['caption'])) ? $img['header']['caption']: 'Shoot, no caption listed...'; ?>" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Jumbotron -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-lg-12">
				<?php // exclude the following for these taxonomy IDs
					$excludearray = array(
						'taxonomy' => array(),
						'definition' => array(),
					);
					if (!in_array($id, array(7,3,5,6))) : 
						
				?>

				<ul class="nav nav-pills">
					<!--<li class="active" ><a href="#"  data-toggle="popover" data-placement="right" data-content="This page is connected to these categories, concepts, people, and collections. Check out similar pages to learn more." data-original-title="" title="" data-container="body"><?php echo $title; ?> is connected to:</a></li>-->
					
					<li class="dropdown">
						<?php // setup for the related dropdown module 
							$__id = 3;
							$__type = 'taxonomy';
							$__list = $this->shared->get_related($__type,$__id); 
							$__host = $this->shared->get_data($__type,$__id);
						?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">Key Thinkers<span class="caret"></span></a>
						<ul class="dropdown-menu">
						<li class="dropdown-header">These are some of the key thinkers <br>looking at <?php echo $title; ?>:</li>
						<li class="divider"></li>
						<?php foreach ($__list as $__child) { if ($this->shared->is_parent($type, $id, $__child['type'], $__child['id'])) { $excludearray[$__child['type']][] = $__child['id']; ?><li><a href="/<?php echo $__child['type']; ?>/<?php echo $__child['slug']; ?>"><?php echo $__child['title']; ?></a></li><?php }} // end is_child and foreach ?>
						<li class="divider"></li>
						<li><a href="/<?php echo $__host['type']; ?>/<?php echo $__host['slug']; ?>">See all <em><?php echo $__host['title']; ?></em> &rarr;</a></li>
						</ul>
					</li>
					<?php if (!$this->shared->is_child('taxonomy', 6, $type, $id)) { ?>
					<li class="dropdown">
						<?php // setup for the related dropdown module 
							$__id = 6;
							$__type = 'taxonomy';
							$__list = $this->shared->get_related($__type,$__id); 
							$__host = $this->shared->get_data($__type,$__id);
						?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">Defining Features<span class="caret"></span></a>
						<ul class="dropdown-menu">
						<li class="dropdown-header">These are Complex Adaptive Systems features <?php echo $title; ?> is connected to these <br>applications and fields of study:</li>
						<li class="divider"></li>
						<?php foreach ($__list as $__child) { if ($this->shared->is_parent($type, $id, $__child['type'], $__child['id'])) { $excludearray[$__child['type']][] = $__child['id']; ?><li><a href="/<?php echo $__child['type']; ?>/<?php echo $__child['slug']; ?>"><?php echo $__child['title']; ?></a></li><?php }} // end is_child and foreach ?>
						<li class="divider"></li>
						<li><a href="/<?php echo $__host['type']; ?>/<?php echo $__host['slug']; ?>">See all <em><?php echo $__host['title']; ?></em> &rarr;</a></li>
						</ul>
					</li>
					<?php } ?>
					<?php if ($this->shared->is_child('taxonomy', 6, $type, $id)) { ?>
					<li class="dropdown">
						<?php // setup for the related dropdown module 
							$__id = 9;
							$__type = 'taxonomy';
							$__list = $this->shared->get_related($__type,$__id); 
							$__host = $this->shared->get_data($__type,$__id);
						?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">Urban Theories employing these concepts<span class="caret"></span></a>
						<ul class="dropdown-menu">
						<li class="dropdown-header"><?php echo $title; ?> is employed in the following concepts:</li>
						<li class="divider"></li>
						<?php foreach ($__list as $__child) { if ($this->shared->is_parent($type, $id, $__child['type'], $__child['id'])) { $excludearray[$__child['type']][] = $__child['id']; ?><li><a href="/<?php echo $__child['type']; ?>/<?php echo $__child['slug']; ?>"><?php echo $__child['title']; ?></a></li><?php }} // end is_child and foreach ?>
						<li class="divider"></li>
						<li><a href="/<?php echo $__host['type']; ?>/<?php echo $__host['slug']; ?>">See all <em><?php echo $__host['title']; ?></em> &rarr;</a></li>
						</ul>
					</li>
					<?php } ?>
					<?php if ($this->shared->is_child('taxonomy', 6, $type, $id) || $this->shared->is_child('taxonomy', 9, $type, $id)) { ?>
					<li class="dropdown">
						<?php // setup for the related dropdown module 
							$__id = 7;
							$__type = 'taxonomy';
							$__list = $this->shared->get_related($__type,$__id); 
							$__host = $this->shared->get_data($__type,$__id);
						?>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">Key Attributes<span class="caret"></span></a>
						<ul class="dropdown-menu">
						<li class="dropdown-header"><?php echo $title; ?> engages with the following key attributes:</li>
						<li class="divider"></li>
						<?php foreach ($__list as $__child) { if ($this->shared->is_parent($type, $id, $__child['type'], $__child['id'])) {  ?><li><a href="/<?php echo $__child['type']; ?>/<?php echo $__child['slug']; ?>"><?php echo $__child['title']; ?></a></li><?php }} // end is_child and foreach ?>
						<li class="divider"></li>
						<li><a href="/<?php echo $__host['type']; ?>/<?php echo $__host['slug']; ?>">See all <em><?php echo $__host['title']; ?></em> &rarr;</a></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
				<!-- -->
				<?php endif; // $footexempt == true ?>

			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="top-space">
					<p><?php echo $this->shared->handlebar_links($body); ?></p>
				</div>
				<!--<code><?php var_dump($payload); ?></code>-->
				<?php if (isset($payload['footer']) && in_array('parentchild', $payload['footer'])) : ?>
				<!-- Footer parentchild -->
						<hr>
						<div class="row">
							<div class="col-md-6">
								<h4><strong>Continue Exploring</strong></h4>
								<p>Explore <?php echo $title; ?> further in the topics and collections below.</p>
								<?php $set = $this->shared->get_related($type,$id,true); ?>
								<?php if ($set !== false) : ?>
								<?php foreach ($set as $single) { 
									if (in_array($single['id'], $excludearray[$single['type']])) continue; ?>
									<h4><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></h4>
									<!--<p><?php echo $single['excerpt']; ?> <a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>">Learn More about <?php echo $single['title']; ?> &rarr;</a></p>-->
								<?php } ?> 
								<?php elseif ($this->ion_auth->is_admin()): ?><blockquote>No connected topics...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#pageeditor">Add Relationships</button></blockquote><?php endif; ?>
							</div>
							<div class="col-md-6">
								<h4><strong>Parents</strong></h4>
								<p><?php echo $title; ?> is part of the following collections.</p>
								<?php $set = $this->shared->get_related_parents($type,$id,true); ?>
								<?php /* */ ?>
								<?php if ($set !== false) : ?>
								<?php foreach ($set as $single) { 
									if (!isset($single['id'])) continue; 
									if (in_array($single['id'], $excludearray[$single['type']])) continue; ?>
									<h4><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></h4>
									<!--<p><?php echo $single['excerpt']; ?> <a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>">Learn More about <?php echo $single['title']; ?> &rarr;</a></p>-->
								<?php } ?> 
								<?php elseif ($this->ion_auth->is_admin()): ?><blockquote>No connected topics...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#pageeditor">Add Relationships</button></blockquote><?php endif; ?>
								<?php /**/ ?>
							</div>
						</div>
				<!-- /Footer List -->
				<?php endif; ?>
				<?php if (isset($payload['footer']) && in_array('excerpts', $payload['footer'])) : ?>
				<!-- Footer List -->
					<?php $set = $this->shared->get_related($type,$id,true); ?>
					<?php if ($set !== false) : ?>
						<hr><h4><strong>Explore Further</strong></h4>
						<p>These are elements and topics related to <?php echo $title; ?>. <?php if ($this->ion_auth->is_admin()) { ?>You can also <a data-toggle="modal" data-target="#pageeditor">modify relationships &rarr;</a><?php } ?></p>
						<?php foreach ($set as $single) { 
							if (in_array($single['id'], $excludearray[$single['type']])) continue;
						?>
							<h4><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></h4>
							<p><?php echo $single['excerpt']; ?> <a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>">Learn More about <?php echo $single['title']; ?> &rarr;</a></p>
							<hr>
						<?php } ?> <?php elseif ($this->ion_auth->is_admin()): ?><blockquote>No connected topics...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#pageeditor">Add Relationships</button></blockquote><?php endif; ?>
				<!-- /Footer List -->
				<?php endif; ?>
				<?php if (isset($payload['footer']) && in_array('list', $payload['footer'])) : ?>
				<!-- Footer List -->
				<div class="page-header">
					<hr><h4><strong>Explore Further</strong></h4>
					<p>These are elements and topics related to <?php echo $title; ?>. <?php if ($this->ion_auth->is_admin()) { ?>You can also <a data-toggle="modal" data-target="#pageeditor">modify relationships &rarr;</a><?php } ?></p>
					<?php $set = $this->shared->get_related($type,$id,true); ?>
					<?php if ($set !== false) : ?>
					<!--
					<hr />
					<h3>Related elements and topics</h3>
					<p>These are elements and topics related to <?php echo $title; ?>. You can also <a data-toggle="modal" data-target="#pageeditor">modify relationships &rarr;</a></p>
					-->
					<?php foreach ($set as $single) { 
						if (in_array($single['id'], $excludearray[$single['type']])) continue;
					?>
						<h4><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></h4>
					<?php } ?> <?php elseif ($this->ion_auth->is_admin()): ?><blockquote>No connected topics...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#pageeditor">Add Relationships</button></blockquote><?php endif; ?>

				</div>
				<!-- /Footer List -->
				<?php endif; ?>
				<?php if (isset($payload['footer']) && in_array('networkdiagram', $payload['footer'])) : ?>
				<!-- Footer Visualization -->
				<hr><h4><strong>Connections</strong></h4>
				<div class="clear"></div>
				<div id="viz-test">
					<!-- https://bl.ocks.org/mbostock/1153292 sticky network diagram --
					<!-- http://bl.ocks.org/mbostock/4063550 radial network diagram -->
					<style>
					
					.link {
					  fill: none;
					  stroke: #666;
					  stroke-width: 1.5px;
					}
					
					#licensing {
					  fill: green;
					}
					
					.link.taxonomy {
					  stroke: green;
					}
					
					.link.definiton {
					  stroke-dasharray: 0,2 1;
					}
					
					circle {
					  fill: #ccc;
					  stroke: #333;
					  stroke-width: 1.5px;
					}
					
					text {
					  font: 10px sans-serif;
					  pointer-events: none;
					  text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, 0 -1px 0 #fff, -1px 0 0 #fff;
					}
					
					</style><script src="//d3js.org/d3.v3.min.js"></script>
					<script>
					
					// http://blog.thomsonreuters.com/index.php/mobile-patent-suits-graphic-of-the-day/
					var links = <?php
						
						$__diagramlinks = array();
						$set = $this->shared->get_related($type,$id,true);
						if ($set !== false) : 
							foreach ($set as $__diagrami) { 
								$__diagramlinks[] = array(
									'source' => $title,
									'target' => $__diagrami['title'],
									'type' => $__diagrami['type']
								);
							}
						print json_encode($__diagramlinks);
						//{source: "Nokia", target: "Qualcomm", type: "suit"}
						?>;
					
					var nodes = {};
					
					// Compute the distinct nodes from the links.
					links.forEach(function(link) {
					  link.source = nodes[link.source] || (nodes[link.source] = {name: link.source});
					  link.target = nodes[link.target] || (nodes[link.target] = {name: link.target});
					});
					
					var width = $('#viz-test').width(),
					    height = 210;
					
					var force = d3.layout.force()
					    .nodes(d3.values(nodes))
					    .links(links)
					    .size([width, height])
					    .linkDistance(70)
					    .charge(-200)
					    .on("tick", tick)
					    .start();
					
					var svg = d3.select("#viz-test").append("svg")
					    .attr("width", width)
					    .attr("height", height);
					
					// Per-type markers, as they don't inherit styles.
					svg.append("defs").selectAll("marker")
					    .data(["suit", "licensing", "resolved"])
					  .enter().append("marker")
					    .attr("id", function(d) { return d; })
					    .attr("viewBox", "0 -5 10 10")
					    .attr("refX", 15)
					    .attr("refY", -1.5)
					    .attr("markerWidth", 6)
					    .attr("markerHeight", 6)
					    .attr("orient", "auto")
					  .append("path")
					    .attr("d", "M0,-5L10,0L0,5");
					
					var path = svg.append("g").selectAll("path")
					    .data(force.links())
					  .enter().append("path")
					    .attr("class", function(d) { return "link " + d.type; })
					    .attr("marker-end", function(d) { return "url(#" + d.type + ")"; });
					
					var circle = svg.append("g").selectAll("circle")
					    .data(force.nodes())
					  .enter().append("circle")
					    .attr("r", 6)
					    .call(force.drag);
					
					var text = svg.append("g").selectAll("text")
					    .data(force.nodes())
					  .enter().append("text")
					    .attr("x", 8)
					    .attr("y", ".31em")
					    .text(function(d) { return d.name; });
					
					// Use elliptical arc path segments to doubly-encode directionality.
					function tick() {
					  path.attr("d", linkArc);
					  circle.attr("transform", transform);
					  text.attr("transform", transform);
					}
					
					function linkArc(d) {
					  var dx = d.target.x - d.source.x,
					      dy = d.target.y - d.source.y,
					      dr = Math.sqrt(dx * dx + dy * dy);
					  return "M" + d.source.x + "," + d.source.y + "A" + dr + "," + dr + " 0 0,1 " + d.target.x + "," + d.target.y;
					}
					
					function transform(d) {
					  return "translate(" + d.x + "," + d.y + ")";
					}
					<?php endif; ?>
					</script>

				</div>
			<!-- /Visualization -->
			<?php endif; ?>
				<div class="page-header">
					<ul class="breadcrumb">
						<li class="active"><?php echo $title; ?> {{<?php echo $slug; ?>}} was updated <?php echo date('F jS, Y', $timestamp); ?>.</li>
					</ul>			
				</div>
			</div>


			<div class="col-lg-5">
				<h3>Feed </h3>
				<p>This is the feed, a series of things related to <?php echo $title; ?>. <a data-toggle="modal" data-target="#createlink">Add a link to the feed &rarr;</a></p>
				<?php $links = $this->shared->get_data('link',false,array('hosttype'=>$type,'hostid'=>$id)); 
					if ($links === false) { ?> 
						<blockquote>Nothing in the feed...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#createlink">Create a Link</button></blockquote>
				<?php } else { ?> 
				<!-- CAS Embed -->
				<div class="cas-embed">
					<?php foreach ($links as $link) { ?><blockquote class="embedly-card" data-card-key="74435e49e8fa468eb2602ea062017ceb" data-card-controls="0"><h4><a href="<?php echo $link['uri']; ?>"><?php echo $link['title']; ?></a></h4><p><?php echo $link['excerpt']; ?></p></blockquote><div class="feed-footer"><address data-toggle="tooltip" data-title="<?php echo $link['excerpt']; ?>">Description</address> | This is linked to <a href="/<?php echo $link['hosttype']; ?>/<?php $__host = $this->shared->get_data($link['hosttype'],$link['hostid']); echo $__host['slug']; ?>"><?php echo $__host['title']; ?></a>.<?php if ($this->ion_auth->is_admin()) { ?> | <a href="/api/remove/link/<?php echo $link['id']; ?>/refresh" data-toggle="tooltip" data-title="Are you sure?">Delete</a><?php } ?></div><?php } ?> 
				</div><!-- /CAS Embed -->
				<?php } ?>
			</div>

		</div>
	</div>
	
	<!-- /Panels -->
	<div class="modal fade" id="pageeditor" tabindex="-1" role="dialog" aria-labelledby="pageeditor" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit <?php echo $title; ?></h4>
					<p>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.</p>
				</div>
				<form id="formeditor" >
				<div class="modal-body">
					<div class="form-group">
						<label for="payload[title]" class="">Title</label>
						<div class=""><input type="text" class="form-control" id="cas-def-title" name="payload[title]" value="<?php echo $title; ?>"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea class="form-control" id="cas-def-excerpt" name="payload[excerpt]"><?php echo $excerpt; ?></textarea></div>
					</div>
					
					<div class="form-group">
						<label for="payload[body]" class="">Body</label>
						<p class="small"><span class="glyphicon glyphicon-exclamation-sign" style="color:red;" aria-hidden="true"></span> This has issues when copying-and-pasting from websites and Microsoft Word. It is best if you write the content here in this text-box.<br>Make links by using {{handlebars}} in your text!</p>
						<!--<div class="cas-summernote">Hello Summernote</div>-->
						<div class=""><textarea class="cas-summernote" id="cas-def-body" name="payload[body]"><?php echo $body; ?></textarea></div>
					</div>
					<h4><strong>Children</strong> of <?php echo $title; ?></h4>
					<?php $set = $this->shared->get_related($type,$id,true); ?>
					<p>The richness of the site comes in its content relationships and connections. Select relationships this definition has with other content in the website.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Definitions</label>
								<div class="col-sm-10">
									<select name="payload[relationships][definition][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List definitions
										$list = $this->shared->list_bytype('definition'); $relationships = array(); foreach ($set as $ss) $relationships[] = $ss['id'];
										if ($list === false) { echo '<option disabled>No definitions to display.</option>'; } else {
										foreach ($list as $a) { $selected = (in_array($a['id'],$relationships)) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Taxonomy</label>
								<div class="col-sm-10">
									<select name="payload[relationships][taxonomy][]" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
									<?php // List taxonomy
										$list = $this->shared->list_bytype('taxonomy');
										if ($list === false) { echo '<option disabled>No taxonomy to display.</option>'; } else {
										foreach ($list as $a) { $selected = (in_array($a['id'],$relationships)) ? ' selected' : ''; echo '<option value="'.$a['id'].'"'.$selected.'>'.$a['title']."</option>\n"; }} ?> 
									</select>
								</div>
							</div>
						</div>
					</div>
					<h4>Footer Content</h4>
					<p>Choose which blocks you want at the bottom of this page.</p>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<label class="col-sm-2 control-label" style="padding-top: 16px;">Blocks</label>
								<div class="col-sm-10">
									<select name="payload[payload][footer][]" class="selectpicker btn-sm dropup" data-width="100%" data-live-search="true" data-size="5" multiple data-selected-text-format="count > 4">
										<option value="attgrid"<?php if (isset($payload['footer']) && in_array('attgrid', $payload['footer'])) echo ' selected'; ?> >Attribute Grid</option>
										<option value="excerpts"<?php if (isset($payload['footer']) && in_array('excerpts', $payload['footer'])) echo ' selected'; ?> >List with Excerpts</option>
										<option value="networkdiagram"<?php if (isset($payload['footer']) && in_array('networkdiagram', $payload['footer'])) echo ' selected'; ?>>Network Diagram</option>
										<option value="parentchild"<?php if (isset($payload['footer']) && in_array('parentchild', $payload['footer'])) echo ' selected'; ?> >Parents/Children</option>
										<option value="list"<?php if (isset($payload['footer']) && in_array('list', $payload['footer'])) echo ' selected'; ?> >Simple List</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="editorfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="editorsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="editorloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">working...</div>
					<div id="editorbuttons">
						<a class="btn btn-danger pull-left" href="/api/remove/taxonomy/<?php echo $id; ?>/home" data-toggle="tooltip" data-title="Are you sure? No undo...">Delete this Page</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submiteditor">Save changes</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- /Page Editor -->
	<!-- Header Image File Uploader -->
	<div class="modal fade" id="pageupload" tabindex="-1" role="dialog" aria-labelledby="pageupload" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit <?php echo $title; ?></h4>
					<p>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.</p>
				</div>
				<form id="formupload" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<label for="upload" class="">Header Image</label>
						<div class=""><input type="file" id="cas-tax-file" name="userfile" value=""></div>
						<input type="hidden" id="cas-tax-fileurl" name="payload[img][header][url]" value="<?php echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: ''; ?>">
					</div>
					<div class="form-group">
						<label for="payload[title]" class="">Caption</label>
						<div class=""><input type="text" class="form-control" id="cas-def-title" name="payload[img][header][caption]" value="<?php echo $title; ?>"></div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="uploadfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="uploadsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="uploadloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">working...</div>
					<div id="uploadbuttons">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submitupload">Save changes</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /File Uploader -->
	<script>
		$('#pageeditor').on('shown.bs.modal', function () {
			$('.cas-summernote').summernote({
			  toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['style']],
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow']],
			    ['code', ['codeview']],
			    ['fullscreen', ['fullscreen']],
			    
			  ],
			});
			$('.note-editable.panel-body').attr("spellcheck","true");
		})


		$('#pageupload').on('shown.bs.modal', function () {
		    $("#cas-tax-file").fileinput({
		        uploadAsync: false,
				url: "/api/uploadimage", // remove X or it wont work...
		        uploadExtraData: function() {
		            return {
		                id: '<?php echo $id; ?>',
		                type: 'taxonomy'
		            };
		        }
		    });
		});

		$('#cas-tax-file').fileinput({
		    uploadUrl: "/api/uploadimage", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 1
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#cas-tax-file').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    $('#imgheader').css('background-image','url('+response.filename+')');
		    $('#cas-tax-fileurl').val(response.filename);
		});


		$('#submiteditor').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editorbuttons').hide(); 
					$('#editorfail').hide(); 
					$('#editorloading').show();
				},
				url: "/api/update/taxonomy/<?php echo $id; ?>",
				data: $("#formeditor").serialize(),
				statusCode: {
					200: function(data) {
						$('#editorloading').hide(); 
						$('#editorsuccess').show();
						var response = JSON.parse(data); 
						$('#editorbuttons').show(); 
						//alert('updated 202');
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#editorloading').hide(); 
						$('#editorfail').show();
						$('#editorbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#editorloading').hide(); 
						$('#editorfail').show();
						$('#editorbuttons').show(); 
					}
				}
			});
		});
		$('#submitupload').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#uploadbuttons').hide(); 
					$('#uploadfail').hide(); 
					$('#uploadloading').show();
				},
				url: "/api/update/taxonomy/<?php echo $id; ?>/header", 
				data: $("#formupload").serialize(),
				statusCode: {
					200: function(data) {
						$('#uploadloading').hide(); 
						$('#uploadsuccess').show();
						var response = JSON.parse(data); 
						$('#uploadbuttons').show(); 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#uploadloading').hide(); 
						$('#uploadfail').show();
						$('#uploadbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#uploadloading').hide(); 
						$('#uploadfail').show();
						$('#uploadbuttons').show(); 
					}
				}
			});
		});

	</script>