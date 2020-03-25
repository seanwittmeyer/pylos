<?php $payload = unserialize($payload); ?>	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="bs-component">
			<div class="jumbotron jumbotron-home" style="background-image: url('/includes/test/assets/Moofushi_Kandu_fish.jpg');">
				<div class="container">
					<h1 class="light-text"><?php echo $title; ?><!-- (<?php echo $id; ?>)--></h1>
				</div>
			</div>
		</div>
	</div>
	<!-- /Jumbotron -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-lg-8">
				<p class="light-text"><?php echo $excerpt; ?></p>
				<p><?php echo $body; ?></p>
			<!-- Visualization -->
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
						$tax = $this->shared->get_data2('taxonomy');
						$taxs = array();
						foreach ($tax as $i) $taxs[$i['id']] = $i;
						
						$def = $this->shared->get_data2('definition');
						$defs = array();
						foreach ($def as $i) $defs[$i['id']] = $i;
						
						$query = $this->db->get("cas_relationship");
						$rels = $query->result_array();

						//var_dump($rels);
						
						$links = array();
						foreach ($rels as $i) {
							$i['__type'] = ($i['type'] == 'taxonomy') ? 'taxs':'defs';
							$i['__targettype'] = ($i['taxonomy'] == 0) ? 'defs':'taxs';
							$i['__targetid'] = ($i['taxonomy'] == 0) ? $i['definition']:$i['taxonomy'];
							if ( isset(${$i['__type']}[$i['primary']]) && isset(${$i['__targettype']}[$i['__targetid']]) ) {
								$links[] = array(
									'source' => ${$i['__type']}[$i['primary']]['title'],
									'target' => ${$i['__targettype']}[$i['__targetid']]['title'],
									'type' => $i['type']
								);
							}
						}
						print json_encode($links);
						//{source: "Nokia", target: "Qualcomm", type: "suit"}
						?>;
					
					var nodes = {};
					
					// Compute the distinct nodes from the links.
					links.forEach(function(link) {
					  link.source = nodes[link.source] || (nodes[link.source] = {name: link.source});
					  link.target = nodes[link.target] || (nodes[link.target] = {name: link.target});
					});
					
					var width = $('#viz-test').width(),
					    height = width;
					
					var force = d3.layout.force()
					    .nodes(d3.values(nodes))
					    .links(links)
					    .size([width, height])
					    .linkDistance(60)
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
					
					</script>

				</div>
			<!-- /Visualization -->
		</div>
			<div class="col-lg-4">
				<?php $set = $this->shared->get_related($type,$id,true); ?>
				<?php if ($set !== false) : ?>
					<h3>Keep Exploring</h3>
					<p>These are elements and topics related to <?php echo $title; ?>. You can also <a data-toggle="modal" data-target="#pageeditor">modify relationships &rarr;</a></p>
					<?php foreach ($set as $single) { ?>
						<blockquote>
							<h4>
								<a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>">
									<span class="glyphicon glyphicon-<?php if($single['type'] == "taxonomy") { print('list'); } elseif ($single['type'] == "paper") { print('file'); } elseif ($single['type'] == "definition") { print('education'); } ?>" aria-hidden="true"></span> 
									<?php echo $single['title']; ?>
								</a>
							</h4>
							<p style="font-size:15px;"><?php echo $single['excerpt']; ?> <a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>">Learn More about <?php echo $single['title']; ?> &rarr;</a></p>
						</blockquote>
					<?php } ?> <?php elseif ($this->ion_auth->is_admin()): ?><blockquote>No connected topics...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#pageeditor">Add Relationships</button></blockquote><?php endif; ?>
			</div>

		</div>
	</div>
	
	<!-- /Panels -->
	<div class="modal fade" id="pageeditor" tabindex="-1" role="dialog" aria-labelledby="pageeditor" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Edit <?php echo $title; ?></h4>
					<p>Fill in all boxes here, each piece of information has a role in making pages, links, and visualizations in the site work well.</p>
				</div>
				<form id="formeditor" >
				<div class="modal-body">
					<div class="form-group">
						<label for="payload[title]" class="">Title</label>
						<div class=""><input type="text" class="form-control" id="cas-page-title" name="payload[title]" value="<?php echo $title; ?>"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-page-excerpt" name="payload[excerpt]"><?php echo $excerpt; ?></textarea></div>
					</div>
					<div class="form-group">
						<label for="payload[author]" class="">Author</label>
						<div class=""><input type="text" class="form-control" id="cas-page-author" name="payload[author]" <?php if (empty($author)) { ?>placeholder="Bill Nye the Science Guy" <?php } else { ?>value="<?php echo $author; ?>"<?php } ?>></div>
					</div>
					<div class="form-group">
						<label for="payload[template]" class="">Template <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="popover" data-trigger="hover" title="Page Templates"  data-content="Select the template to use for this page. By changing this, you may lose some settings when you save the page."> </i></label>
						<div class="">
							<select id="cas-page-template" name="payload[template]" class="form-control">
								<?php foreach (get_filenames("./application/views/app/pages") as $pagetemplate) { $pagetemplate = str_replace('.php', '', $pagetemplate); ?>
								<option value="<?php echo $pagetemplate; ?>"<?php if ($pagetemplate == $template) { ?> selected="selected"<?php } ?>><?php echo ucfirst($pagetemplate); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="payload[body]" class="">Body</label>
						<!--<div class="cas-summernote">Hello Summernote</div>-->
						<div class=""><textarea type="text" class="cas-summernote" id="cas-def-body" name="payload[body]"><?php echo $body; ?></textarea></div>
					</div>
					
					
					
					
					<h4>Metadata</h4>
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
				</div>
				<div class="modal-footer">
					<div id="editorfail" class="alert alert-danger " style="display: none;" role="alert">Uh oh, the <?php echo $type; ?> didn't save, make sure everything above is filled and try again.</div>
					<div id="editorsuccess" class="alert alert-success " style="display: none;" role="alert">Great success, content posted.</div>
					<div id="editorloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">working...</div>
					<div id="editorbuttons">
						<a class="btn btn-danger pull-left" href="/api/remove/page/<?php echo $id; ?>/home" data-toggle="tooltip" data-title="Are you sure? No undo...">Delete this Page</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="reset" class="btn btn-default" >Reset</button>
						<button type="button" class="btn btn-primary tt" id="submiteditor">Save changes</button>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>

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
		})
		
		$('#submiteditor').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#editorbuttons').hide(); 
					$('#editorfail').hide(); 
					$('#editorloading').show();
				},
				url: "/api/update/page/<?php echo $id; ?>",
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