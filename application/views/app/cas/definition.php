<?php 
	$attributes = $this->shared->get_attributes();
	if ($this->ion_auth->logged_in()) {
		$user = $this->ion_auth->user()->row(); 
		$d_ratings = $this->shared->get_ratings($user->id,'diagram','');
		$a_ratings = $this->shared->get_ratings($user->id,'diagram','attribute');
	}
	$diagrams = $this->shared->get_diagrams('diagram','definition',$id);
	$k = 1;
	foreach ($diagrams['sorted'] as $diagram) {
		if (isset($diagram['url'])) {
			$icon = $diagram;
			break;
		}
	}
?>	<!-- General Content Block -->
	<div class="container top-nospace">
		<div class="row topbottom-space">
			<div class="col-md-3">
				<img class="cas-def-diagram-preview" data-toggle="modal" data-target="#<?php echo (empty($diagrams['sorted'])) ? 'diagramnew':'diagramrate'; ?>" src="<?php echo (isset($icon['url'])) ? $icon['url']: '/upload/img/defdefault.png'; ?>" width="200px" height="200px" alt="Diagram: <?php echo $title; ?>" />
				<?php if ($this->ion_auth->logged_in()) { ?><div style="position: absolute; left: 130px; top: 159px; width: 75px;">
					<button type="button" class="btn btn-fail btn-sm" data-toggle="modal" data-target="#diagramnew"><span class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Suggest new image/diagram" aria-hidden="true"></span></button>
					<button type="button" class="btn btn-fail btn-sm" data-toggle="modal" data-target="#diagramrate"><span class="glyphicon glyphicon-th-list" data-toggle="tooltip" title="Rate <?php echo lcfirst(str_replace('"',"'",$title)); ?> diagrams" aria-hidden="true"></span></button>
				</div><?php } ?>
			</div>
			<div class="col-md-9">
				<h1><?php echo $title; ?><!-- (<?php echo $id; ?>)--></h1>
				<p class="lead"><?php echo $this->shared->handlebar_links($excerpt); ?></p>
			</div>
		</div>
	</div>
	<!-- /General Content Block -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-sm-7">
				<div class="bodytext">
					<p><?php echo $this->shared->handlebar_links($body); ?></p>
					<p>&nbsp;</p>
				</div>
				<?php // exclude the following for these taxonomy IDs
					$excludearray = array(
						'taxonomy' => array(),
						'definition' => array(),
					);						
				?>


				<?php if (isset($payload['footer']) && in_array('parentchild', $payload['footer'])) : ?>
				<!-- Footer parentchild -->
						<div class="row">
							<div class="col-md-6">
								<h3>Continue Exploring</h3>
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
								<h3>Parents</h3>
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
						<h4><strong>Explore Further</strong></h4>
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
					<h4><strong>Explore Further</strong></h4>
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
				<h4>Connections</h4>
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






				<div class="row">
					<ul class="breadcrumb">
						<li class="active"><?php echo $title; ?> {{<?php echo $slug; ?>}} was updated <?php echo date('F jS, Y', $timestamp); ?>.</li>
					</ul>		
				</div>	
			</div>


			<div class="col-sm-5">
				<h3>Feed </h3>
				<p>This is the feed, a series of things related to <?php echo $title; ?>. <a data-toggle="modal" data-target="#createlink">Add a link to the feed &rarr;</a></p>
				<?php $links = $this->shared->get_data('link',false,array('hosttype'=>$type,'hostid'=>$id)); 
					if ($links === false) { ?> 
						<blockquote>Nothing in the feed...yet.<?php if ($this->ion_auth->logged_in()) { ?><br /><button class="btn btn-success" data-toggle="modal" data-target="#createlink">Create a Link</button><?php } ?></blockquote>
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
	<!-- Edit page modal -->
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
						<div class=""><textarea type="text" class="form-control" id="cas-def-excerpt" name="payload[excerpt]"><?php echo $excerpt; ?></textarea></div>
					</div>
					
					<div class="form-group">
						<label for="payload[body]" class="">Body</label>
						<p class="small"><span class="glyphicon glyphicon-exclamation-sign" style="color:red;" aria-hidden="true"></span> This has issues when copying-and-pasting from websites and Microsoft Word. It is best if you write the content here in this text-box.<br>Make links by using {{handlebars}} in your text!</p>
						<!--<div class="cas-summernote">Hello Summernote</div>-->
						<div class=""><textarea type="text" class="cas-summernote" id="cas-def-body" name="payload[body]"><?php echo $body; ?></textarea></div>
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
					<div id="editorloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">climbing...</div>
					<div id="editorbuttons">
						<a class="btn btn-danger pull-left" href="/api/remove/definition/<?php echo $id; ?>/home" data-toggle="tooltip" data-title="Are you sure? No undo...">Delete this Definition</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submiteditor">Save changes</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /Edit page modal -->
	<!-- New diagram modal -->
	<div class="modal fade" id="diagramnew" tabindex="-1" role="dialog" aria-labelledby="diagramnew" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Suggest a new diagram for <?php echo $title; ?></h4>
				</div>
				<div class="modal-body">
				<form id="formdiagramnew" enctype="multipart/form-data">
					<div class="form-group">
						<label for="cas-diagram-file" class="">Step 1: Upload your diagram</label>
						<div class=""><input type="file" id="cas-diagram-file" name="userfile" value=""></div>
						<input type="hidden" id="cas-diagram-fileurl" name="payload[url]" value="">
						<p><br /></p>
						<label class="">Step 2: Rate your diagram</label>
						<p>Awesome, now lets rate your diagram. User the slider to see how you feel it rates for each of the attributes. You can <a href="/diagrams/" target="_blank">rate attributes and suggest new ones over here (in a new window)</a>.</p>
					</div>
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-4 control-label top-nospace">&nbsp;</label><div class="col-sm-8"><div class="pull-left">Low</div><div class="pull-right">High</div>&nbsp;</div>
						</div>
						<?php // list top 5 attributes for evaluation
							$i = 1; 
							//$attributes = $this->shared->get_attributes();
							//$user = $this->ion_auth->user()->row(); 
							//$ratings = $this->shared->get_ratings($user->id,'attribute');
							foreach ($attributes['sorted'] as $attribute) { if ($i === 9) break; 
							/* if (isset($ratings[$attribute['id']]) && is_array($ratings[$attribute['id']])) { ?><span class="label label-default pull-right">Rated as <?php echo ($ratings[$attribute['id']][0]['value'] == '.9') ? 'important': 'not important'; ?>.</span><?php } else { ?><button type="button" class="pull-right btn btn-danger btn-xs" data-toggle="tooltip" title="Set as NOT important?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,.1);"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
							*/ 
							
							?> 
						<div class="form-group">
							<label class="col-sm-4 control-label top-nospace" for="payload[rating][<?php echo $attribute['id']; ?>" ><?php echo $attribute['title']; ?></label><div class="col-sm-8"><input type="range" for="cas-attribute-<?php echo $attribute['id']; ?>" name="payload[rating][<?php echo $attribute['id']; ?>]" value=".5" min="0" max="1.0" step=".1"></div>
						</div><?php $i++; } ?> 
						<div class="form-group">
							<input type="hidden" value="definition" name="payload[type]" id="cas-diagram-type" />
							<input type="hidden" value="<?php echo $id; ?>" name="payload[typeid]" id="cas-diagram-type" />
							<input type="hidden" value="attribute" name="payload[target]" id="cas-diagram-target" />
							<input type="hidden" value="<?php echo md5(microtime()+3); ?>" name="<?php echo md5(microtime()+2); ?>" id="cas-diagram-validate-submit" />
						</div>
					</div>
				</form>
				</div>
				<div class="modal-footer">
					<div id="diagramnewfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="diagramnewsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="diagramnewloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">climbing...</div>
					<div id="diagramnewbuttons">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submitdiagramnew">Save changes</button>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /New diagram modal -->
	<!-- Rate diagrams modal -->
	<div class="modal fade" id="diagramrate" tabindex="-1" role="dialog" aria-labelledby="diagramrate" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Rate diagrams for <?php echo $title; ?></h4>
				</div>
				<div class="modal-body">
					<div class="form-horizontal">
						<div class="form-group">
							<p class="col-sm-12">You can do two things here. First, you can influence the order of the diagrams for this topic by clicking on the (<span class="glyphicon glyphicon-plus"></span>) and (<span class="glyphicon glyphicon-minus"></span>) buttons. The diagrams with more plus votes will rise to the top. The second thing you can do here is rate the diagrams against the evolving set of attributes that you can <a href="/diagrams" target="_blank" >learn more about here</a>. You can only rate each thing once but as the attribute list changes, you can come back and update ratings.</p> 
							<label class="col-sm-12">Curent <?php echo $title; ?> Diagram</label>
						</div>
						<?php 
							//$attributes = $this->shared->get_attributes();
							//$user = $this->ion_auth->user()->row(); 
							//$d_ratings = $this->shared->get_ratings($user->id,'diagram','');
							//$a_ratings = $this->shared->get_ratings($user->id,'diagram','attribute');
							//$diagrams = $this->shared->get_diagrams('diagram','definition',$id);
							$k = 1;
							if (empty($diagrams['sorted'])) { ?><blockquote>No diagrams...yet.<!--<br /><button class="btn btn-success" data-toggle="modal" data-target="#diagramnew" onclick="$('#diagramrate').modal('hide'); return false;">Suggest the first diagram</button>--></blockquote><?php } 
							foreach ($diagrams['sorted'] as $diagram) { 
								$a_rated = array();
								if (isset($a_ratings[$diagram['id']])) foreach ($a_ratings[$diagram['id']] as $ar) $a_rated[$ar['targetid']] = $ar;

							?>
						<?php if ($k==2) { ?><div class="form-group">
							<label class="col-sm-12">Suggested <?php echo $title; ?> Diagrams</label>
						</div><?php } ?>
						<form id="formrate-<?php echo $diagram['id']; ?>">
						<div class="form-group">
							<img src="<?php echo $diagram['url']; ?>" class="col-xs-4" />
							<div class="col-sm-8 ">
								<div class="row raterow-<?php echo $diagram['id']; ?>">
									<label class="col-sm-5 control-label top-nospace">&nbsp;</label>
									<div class="col-sm-6"><small class="pull-left">Low</small><small class="pull-right">High</small>&nbsp;</div>
								</div>
								<?php 
									$rating_average = $this->shared->get_rating_average('diagram',$diagram['id'],'attribute'); 
									// list top 5 attributes for evaluation
									$i = 1; $j = 1; 
									foreach ($attributes['sorted'] as $attribute) { if ($i === 9) break; 
										$flag = false;
										if (isset($a_rated[$attribute['id']]) && is_array($a_rated[$attribute['id']])) { $flag = true; $j++; }


									?> 
								<div class="row raterow-<?php echo $diagram['id']; ?>"<?php if ($flag) { ?> style="background:#EEE;"<?php } ?>>
									<label class="col-sm-5 control-label top-nospace small" for="payload[rating][<?php echo $attribute['id']; ?>]" ><?php echo $attribute['title']; ?></label>
									<div class="col-sm-6"<?php if ($flag) { ?>data-toggle="tooltip" title="Feedback based on <?php echo $rating_average[$attribute['id']]['count']; ?> ratings"<?php } ?>><input type="range" <?php if (!$flag) { ?>name="payload[rating][<?php echo $attribute['id']; ?>]"<?php } ?> value="<?php echo ($flag) ? $rating_average[$attribute['id']]['value'].'" disabled="disabled' : '.5'; ?>" min="0" max="1.0" step=".1"></div>
								</div><?php $i++; } ?> 
								<?php if ($j > 1) { ?>
								<div class="row raterow-<?php echo $diagram['id']; ?>">
									<label class="col-sm-5 control-label top-nospace">&nbsp;</label>
									<div class="col-sm-6"><small class="pull-left"><em>*Ratings in grey are averages of all ratings for this diagram.</em></small></div>
								</div>
								<?php }	?>
								<hr />
								<div class="row">
									<div class="col-sm-6" id="formrateplusminus-<?php echo $diagram['id']; ?>">
										<?php if (isset($d_ratings[$diagram['id']]) && is_array($d_ratings[$diagram['id']])) { ?><span class="label label-default pull-right" data-toggle="tooltip" title="You rated this <?php echo $this->shared->twitterdate($d_ratings[$diagram['id']][0]['timestamp']); ?>">Rated as <?php echo ($d_ratings[$diagram['id']][0]['value'] == '.9') ? 'important': 'not important'; ?></span><?php } else { ?> 
										<div data-toggle="tooltip" title="Use these buttons to rate this diagram against the others">
											<div class="btn-group pull-right" data-toggle="buttons">
												<label class="btn btn-default btn-xs casradioplus" onclick="ratediagram('diagram',<?php echo $diagram['id']; ?>,.9,<?php echo $diagram['id']; ?>);"><input type="radio" autocomplete="off"><span class="glyphicon glyphicon-plus"></span></label> 
												<label class="btn btn-default btn-xs casradiominus" onclick="ratediagram('diagram',<?php echo $diagram['id']; ?>,.1,<?php echo $diagram['id']; ?>);"><input type="radio" autocomplete="off"><span class="glyphicon glyphicon-minus"></span></label>
											</div>
										</div><?php } ?>
									</div>
									<div class="col-sm-6" id="formrateplusminussuccess-<?php echo $diagram['id']; ?>" style="display: none;">
										<small>You've rated this diagram against the others, thanks!</small>
									</div>
									<div class="col-sm-6" id="formratebuttons-<?php echo $diagram['id']; ?>">
										<?php if ($j > 8) { ?><span class="label label-default ">You rated this diagram already!</span><?php } else { ?>
										<button type="button" class="btn btn-primary btn-xs tt" onclick="rate(<?php echo $diagram['id']; ?>);" id="submitrate-<?php echo $diagram['id']; ?>">Rate</button>
										<button type="reset" class="btn btn-default btn-xs" >Reset</button>
										<?php } ?>
									</div>
									<div class="col-sm-6" id="formratesuccess-<?php echo $diagram['id']; ?>" style="display: none;">
										<small>You've evaluated this diagram, thanks!</small>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" value="diagram" name="payload[type]" />
						<input type="hidden" value="<?php echo $diagram['id']; ?>" name="payload[typeid]" />
						<input type="hidden" value="attribute" name="payload[target]" />
						<input type="hidden" value="<?php echo md5(microtime()+3); ?>" name="<?php echo md5(microtime()+2); ?>" />
						</form>
						<hr />
						<?php $k++; } ?>
					</div>
				</form>
				</div>
				<div class="modal-footer">
					<div id="diagramnewfail" class="alert alert-danger " style="display: none;" role="alert">Crap. Make sure your jpg/png/gif image is a 2000px or less square, less than 10mb.</div>
					<div id="diagramnewsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="diagramnewloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">dancing...</div>
					<div id="diagramnewbuttons" style="display: none;">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submitdiagramnew">Suggest Diagram</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Rate diagrams modal -->

	<script>
		function rate(diagram) {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#submitrate-'+diagram).text('thinking...'); 
				},
				url: "/api/rate", 
				data: $("#formrate-"+diagram).serialize(),
				statusCode: {
					200: function(data) {
						$('#formratebuttons-'+diagram).hide(); 
						$('#formratesuccess-'+diagram).show(); 
						$('.raterow-'+diagram).css('opacity',.3); 
						///alert('ding ding ding!');
						//window.location.reload();
						
					},
					403: function(data) {
						alert('403 fail');
						//var response = JSON.parse(data);
					},
					404: function(data) {
						alert('404 fail');
						//var response = JSON.parse(data);
					}
				}
			});
			
		}
		function ratediagram(rt,ri,rr,di) {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$(this).prop("disabled",true).addClass('btn-fail'); 
				},
				url: "/api/rate",
				data: {'payload[type]':rt, 'payload[typeid]':ri, 'payload[value]':rr},
				statusCode: {
					200: function(data) {
						//alert('rated!');
						//window.location.reload();
						$('#formrateplusminus-'+di).hide(); 
						$('#formrateplusminussuccess-'+di).show(); 
					},
					403: function(data) {
						//var response = JSON.parse(data);
						alert('crap! Rating failed. Um, talk to Sean.');
					},
					404: function(data) {
						//var response = JSON.parse(data);
						alert('shoot! I think you already rated this.');
					}
				}
			});
			
		}
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
		})
		$('#diagramnew').on('shown.bs.modal', function () {
		    $("#cas-diagram-file").fileinput({
		        uploadAsync: false,
				url: "/api/uploadimage", // remove X or it wont work...
		        uploadExtraData: function() {
		            return {
		                id: '<?php echo $id; ?>',
		                type: 'definition'
		            };
		        }
		    });
		});

		$('#cas-diagram-file').fileinput({
		    uploadUrl: "/api/uploadimage", 
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 1
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#cas-diagram-file').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    //$('#imgheader').css('background-image','url('+response.filename+')');
		    $('#cas-diagram-fileurl').val(response.filename);
		});

		$('#submitdiagramnew').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#diagramnewbuttons').hide(); 
					$('#diagramnewfail').hide(); 
					$('#diagramnewloading').show();
				},
				url: "/api/create/diagram", 
				data: $("#formdiagramnew").serialize(),
				statusCode: {
					200: function(data) {
						$('#diagramnewloading').hide(); 
						$('#diagramnewsuccess').show();
						var response = JSON.parse(data); 
						$('#diagramnewbuttons').show(); 
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#diagramnewloading').hide(); 
						$('#diagramnewfail').show();
						$('#diagramnewbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#diagramnewloading').hide(); 
						$('#diagramnewfail').show();
						$('#diagramnewbuttons').show(); 
					}
				}
			});
		});

		$('#submiteditor').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editorbuttons').hide(); 
					$('#editorfail').hide(); 
					$('#editorloading').show();
				},
				url: "/api/update/definition/<?php echo $id; ?>",
				data: $("#formeditor").serialize(),
				statusCode: {
					200: function(data) {
						$('#editorloading').hide(); 
						$('#editorsuccess').show();
						var response = JSON.parse(data); 
						$('#editorbuttons').show(); 
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
	</script>

