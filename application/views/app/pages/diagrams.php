<?php 
	$attributes = $this->shared->get_attributes();
	if ($this->ion_auth->logged_in()) { 
		$user = $this->ion_auth->user()->row(); 
		$ratings = $this->shared->get_ratings($user->id,'attribute');
	}
	$rawcomments = $this->shared->get_data2('comment', false, array('type'=>'attribute','value'=>'flag'));
	$attcomments = array();
	foreach ($rawcomments as $c) {
		$attcomments[$c['typeid']][] = $c;
	}

?>	<!-- General Content Block -->
	<div class="container top-nospace">
		<div class="row top-space">
			<div class="col-sm-9">
				<h1><?php echo $title; ?><!-- (<?php echo $id; ?>)--></h1>
				<p class="lead"><?php echo $this->shared->handlebar_links($excerpt); ?></p>
			</div>
		</div>
	</div>
	<!-- /General Content Block -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-sm-12">
				<div class="bodytext">
					<p><?php echo $this->shared->handlebar_links($body); ?></p>
				</div>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#attributes" data-toggle="tab" aria-expanded="true">Fitness Parameters</a></li>
					<li class=""><a href="#diagrams" data-toggle="tab" aria-expanded="false">Rate Diagrams</a></li>
				</ul>
				<div id="diagramstabset" class="tab-content">
					<!-- /diagrams tab -->
					<div class="tab-pane fade" id="diagrams">
						<div class="row">
							<div class="col-md-8">
								<div class="col-md-12">
									<h3>Diagrams</h3>
									<p>While you can submit and rate diagrams for any concept or topic in the CAS Explorer, this page focuses on a <a href="/taxonomy/rated-diagrams">specific set</a> of topics. <strong>The goal of this page is to let you order the diagrams in your preference, from your favorite to least favorite.</strong> This will help promote a more unified and cohesive series of diagrams to help illustrate complex adaptive systems theory and its related topics.</p>
								</div>
								<?php $i = 1; 
									$set = $this->shared->get_related('taxonomy',27,true); 
									if ($set !== false) :   
										$sortedicons = array();
										$sortedtaxon = array();
										
										foreach ($set as $single) { 
											//$d_ratings = $this->shared->get_ratings($user->id,'diagram','');
											//$a_ratings = $this->shared->get_ratings($user->id,'diagram','attribute');
											$diagrams = $this->shared->get_diagrams('diagram','definition',$single['id']);
											foreach ($diagrams['sorted'] as $diagram) {
												if (isset($diagram['url'])) {
													$sortedicons[$diagram['id']] = $diagram;
													break;
												}
											}
											$sortedtaxon[$single['id']] = $single;
										} // end first foreach
										// lets sort our diagrams by ratings 
										$diagrams = $this->shared->get_diagrams('diagram','definition',false,'diagramapp',$sortedicons);
										$z = 1; 
										
										// top badge
										foreach ($diagrams['sorted'] as $diagram) {
											if ($z === 2) break;
											$single = $sortedtaxon[$diagram['typeid']];
											$topdiagram = $diagram;
											$topuser = $this->ion_auth->user($diagram['user'])->row();
											$z++;
											break; 
										}
										
										// most diagrams badge
										$alldiagrams = $this->shared->get_data2('diagram');
										$alldiagramsusers = array();
										foreach ($alldiagrams as $z) $alldiagramsusers[$z['user']][] = $z['id'];
										$alldiagramscounts = array();
										/* ?><pre><?php print_r($alldiagrams); ?></pre><?php /* */
										foreach ($alldiagramsusers as $y => $z) $alldiagramscounts[$y] = count($z);
										// exclude sean
										$alldiagramscounts[1] = 0;
										arsort($alldiagramscounts);
										foreach ($alldiagramscounts as $y => $z) {
											$y = $this->ion_auth->user($y)->row();
											$mostdiagrams = $y->first_name.' '.$y->last_name;
											break;
										}
	
								?>
								<form id="diagramsorder">
								<div class="col-md-12 o-sortable">
									<?php
										// lets sort our diagrams by ratings 
										$diagrams = $this->shared->get_diagrams('diagram','definition',false,'diagramapp',$sortedicons);
										$hasdiagrams = array();
										foreach ($diagrams['sorted'] as $diagram) {
											$single = $sortedtaxon[$diagram['typeid']];
											$icon = $diagram;
											$hasdiagrams[$single['id']] = true;
										
									?>
									<!-- add array to $icon -->
										<div class="row">
											<?php if (isset($icon['url'])) { ?><input type="hidden" name="payload[order][]" value="<?php echo $icon['id']; ?>" /><?php } ?>
											<div class="col-sm-2 "><img class="cas-def-diagram-preview sortable-handle col-xs-12" data-toggle="tooltip" data-placement="right" title="Click and drag to reorder!" src="<?php echo (isset($icon['url'])) ? $icon['url']: '/upload/img/defdefault.png'; ?>"  alt="Diagram: <?php echo $single['title']; ?>" /></div>
											<div class="col-sm-1 "><h3><span data-toggle="tooltip" data-placement="right" title="<?php if (isset($icon['score'])) echo floor(100*($icon['score'])); ?>%">#<?php echo $i; ?></span></h3></div>
											<div class="col-sm-8"><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>" ><h3><?php echo $single['title']; if ($this->shared->has_new_diagrams($single['id'])) { ?> <span class="glyphicon glyphicon-asterisk" data-toggle="tooltip" data-placement="right" title="New diagrams in last 2 days!!!!" style="color:red;" aria-hidden="true"></span><?php } ?></h3></a></div>
										</div>
									<?php unset($icon); $i++; } ?>
										<div class="row">
											<div class="col-sm-9">
												<h4>Save the order</h4>
												<p>Drag and drop the list of diagrams above so that the images you prefer the most are on top, then hit 'Save order' to help us determine which images are best for the site.</p>
												<button type="button" class="btn btn-primary tt" id="cas-app-diagramssort-submit">Save order</button>
												<h3>Concepts without Diagrams</h3>
											</div>
										</div>
									<?php foreach ($sortedtaxon as $single) { if (!isset($hasdiagrams[$single['id']])) { ?>
										<div class="row">
											<div class="col-sm-2"><img class="cas-def-diagram-preview col-sm-11 sortable-handle" data-toggle="tooltip" data-placement="right" title="Click and drag to reorder!" src="/upload/img/defdefault.png"  alt="Diagram: <?php echo $single['title']; ?>" /></div>
											<div class="col-sm-8"><p><strong><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></strong><br /><?php echo $single['excerpt']; ?></p></div>
										</div>
									<?php } $i++; } else : ?><blockquote>The chosen category has no concept relationships. You should set some so that concepts will show here.</blockquote><?php endif; ?>
									
									<script>
										$( document ).ready(function() {
											$('.o-sortable').sortable({
												forcePlaceholderSize: true,
												//handle: '.sortable-handle',
												items: 'div',
												placeholderClass: 'dragvoid'
											});
										});
									</script>
								</div>
								</form>
							</div>
							<div class="col-md-4">
								<h3>Badges</h3>
								<div>
									<div class="pull-left" style="width: 130px; text-align: center" /><img src="/upload/img/topdia2.png" style="width: 120px" /><br><strong><?php echo $topuser->first_name.' '.$topuser->last_name; ?></strong></div>
									<div class="pull-left" style="width: 130px; text-align: center" /><img src="/upload/img/mostdia2.png" style="width: 120px" /><br><strong><?php echo $mostdiagrams; ?></strong></div>
								</div>
							</div>
						</div>
						
					</div>
				
					<!-- /diagrams tab -->
					<!-- attributes tab -->
					<div class="tab-pane fade active in" id="attributes">
						<div class="row">
							<div class="col-sm-8">
								<h3>Fitness Parameters</h3>
								<p>These are the parameters that are being used to evaluate the fitness of topic diagrams. They are sorted by their rating and change as new parameters are suggested and rated. You can view and rate all of the parameters here, and soon will be able to see how they fit within what is seen as the ideal or most 'fit' diagram not only for a topic but as a cohesive family of diagrams for the CAS Explorer.</p>
								<div class="panel panel-default top-space">
								<?php $i = 1; 
								$flaglimit = 3;
								$attributes['flagged'] = array();
								foreach ($attributes['sorted'] as $attribute) { 
									if (isset($attcomments[$attribute['id']]) && count($attcomments[$attribute['id']]) >= $flaglimit) {
										$attributes['flagged'][] = $attribute;
										continue;
									} 
									if ($i === 1) { ?><div class="panel-heading"><strong>Active Fitness Parameters</strong><br />These are the parameters currently being used to evaluate topic images. You can rate parameters to help choose which parameters are more important.</div>
									<ul class="list-group"><?php } ?>
									<?php if ($i === 9) { ?></ul>
									<div class="panel-heading"><strong>Suggested Fitness Parameters</strong><br />These are the parameters that have been suggested but aren't rated high enough to be active. Mark parameters below as important to help them rise in the list.</div>
									<ul class="list-group"><?php } ?>
										<li class="list-group-item">
											<button type="button" id="commenttrigger-attribute-<?php echo $attribute['id']; ?>" class="pull-right btn btn-link btn-xs" data-toggle="popover" data-container="body" title="Flag this!" data-html="true" 
												data-content="<?php if (isset($attcomments[$attribute['id']])) { ?>Flagged <?php echo count($attcomments[$attribute['id']]); ?> times!<hr><?php foreach ($attcomments[$attribute['id']] as $attcomment) { unset($att_user_flag); if ($attcomment['content'] == '') { if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->id == $attcomment['user']) $att_user_flag = true; } else { if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->id == $attcomment['user']) $att_user_flag = true; echo str_replace('"', "'", $attcomment['content']).' <i>- '.$this->ion_auth->user($attcomment['user'])->row()->first_name.'</i><hr>'; } } } else { ?>No flags!<br><?php } ?><?php if (isset($att_user_flag)) { unset($att_user_flag); } else { ?>You can flag this and comment why, It will fall to the bottom with <?php echo $flaglimit; ?> flags. <br><input id='commentvalue-attribute-<?php echo $attribute['id']; ?>'>	<button id='comment-attribute-<?php echo $attribute['id']; ?>' class='btn btn-sm btn-warning' onclick='flagatt(<?php echo $attribute['id']; ?>);'>Flag</button><?php } ?>"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span><?php if (isset($attcomments[$attribute['id']])) { ?> x <?php echo count($attcomments[$attribute['id']]); } ?> </button>
											<?php /*
											<?php if ($this->ion_auth->logged_in() && isset($ratings[$attribute['id']]) && is_array($ratings[$attribute['id']])) { ?><span class="label label-default pull-right">Rated as <?php echo ($ratings[$attribute['id']][0]['value'] == '.9') ? 'important': 'not important'; ?>.</span><?php } elseif ($this->ion_auth->logged_in()) { ?>
											<button type="button" class="pull-right btn btn-danger btn-xs" data-toggle="tooltip" title="Set as NOT important?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,.1);"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
											<button type="button" class="pull-right btn btn-success btn-xs" data-toggle="tooltip" title="Set as important?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,.9);"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
											*/ ?>
											<?php if ($this->ion_auth->logged_in() && isset($ratings[$attribute['id']]) && is_array($ratings[$attribute['id']])) { ?><span class="label label-default pull-right">Rated <?php echo ($ratings[$attribute['id']][0]['value'] >= '.5') ? 'highly': 'not so highly'; ?>.</span><?php } elseif ($this->ion_auth->logged_in()) { ?>
											<button type="button" id="att-submit-<?php echo $attribute['id']; ?>" class="pull-right btn btn-primary btn-xs" data-toggle="tooltip" title="Save this rating?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,$('#att-range-<?php echo $attribute['id']; ?>').val());">Rate!</button>
											<div class="col-sm-3 pull-right">
												<input type="range" id="att-range-<?php echo $attribute['id']; ?>" value="<?php echo (isset($ratings[$attribute['id']][0]['value'])) ? $ratings[$attribute['id']][0]['value']: '.5'; ?>" min="0" max="1.0" step=".1">
											</div>
											<?php } ?> <?php echo $attribute['title']; ?> <?php /*(<?php echo $attribute['score']; ?>) (<?php echo (isset($ratings[$attribute['id']])) ? 'rated': 'not rated'; ?>) */ ?>
										</li>
								<?php $i++; } ?>
									</ul>
								<?php if (count($attributes['flagged']) > 0) { ?>
									<div class="panel-heading"><strong>Flagged Fitness Parameters</strong><br />These guys have been flagged <?php echo $flaglimit; ?> or more times and no longer get evaluated.</div>
									<ul class="list-group">
									<?php foreach ($attributes['flagged'] as $attribute) { ?> 
										<li class="list-group-item">
											<button type="button" id="commenttrigger-attribute-<?php echo $attribute['id']; ?>" class="pull-right btn btn-link btn-xs" data-toggle="popover" data-container="body" title="Flag this!" data-html="true" 
												data-content="<?php if (isset($attcomments[$attribute['id']])) { ?>Flagged <?php echo count($attcomments[$attribute['id']]); ?> times!<hr><?php foreach ($attcomments[$attribute['id']] as $attcomment) { unset($att_user_flag); if ($attcomment['content'] == '') { if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->id == $attcomment['user']) $att_user_flag = true; } else { if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->id == $attcomment['user']) $att_user_flag = true; echo str_replace('"', "'", $attcomment['content']).' <i>- '.$this->ion_auth->user($attcomment['user'])->row()->first_name.'</i><hr>'; } } } else { ?>No flags!<br><?php } ?><?php if (isset($att_user_flag)) { unset($att_user_flag); } else { ?>You can flag this and comment why, It will fall to the bottom with <?php echo $flaglimit; ?> flags. <br><input id='commentvalue-attribute-<?php echo $attribute['id']; ?>'>	<button id='comment-attribute-<?php echo $attribute['id']; ?>' class='btn btn-sm btn-warning' onclick='flagatt(<?php echo $attribute['id']; ?>);'>Flag</button><?php } ?>"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span><?php if (isset($attcomments[$attribute['id']])) { ?> x <?php echo count($attcomments[$attribute['id']]); } ?>  </button>
											<?php /*
											<?php if ($this->ion_auth->logged_in() && isset($ratings[$attribute['id']]) && is_array($ratings[$attribute['id']])) { ?><span class="label label-default pull-right">Rated as <?php echo ($ratings[$attribute['id']][0]['value'] == '.9') ? 'important': 'not important'; ?>.</span><?php } elseif ($this->ion_auth->logged_in()) { ?>
											<button type="button" class="pull-right btn btn-danger btn-xs" data-toggle="tooltip" title="Set as NOT important?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,.1);"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
											<button type="button" class="pull-right btn btn-success btn-xs" data-toggle="tooltip" title="Set as important?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,.9);"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
											*/ ?>
											<?php if ($this->ion_auth->logged_in() && isset($ratings[$attribute['id']]) && is_array($ratings[$attribute['id']])) { ?><span class="label label-default pull-right">Rated <?php echo ($ratings[$attribute['id']][0]['value'] >= '.5') ? 'highly': 'not so highly'; ?>.</span><?php } elseif ($this->ion_auth->logged_in()) { ?>
											<button type="button" id="att-submit-<?php echo $attribute['id']; ?>" class="pull-right btn btn-default btn-xs" data-toggle="tooltip" title="Save this rating?" onclick="rate('attribute',<?php echo $attribute['id']; ?>,$('#att-range-<?php echo $attribute['id']; ?>').val());">Rate!</button>
											<div class="col-sm-3 pull-right">
												<input type="range" id="att-range-<?php echo $attribute['id']; ?>" value="<?php echo (isset($ratings[$attribute['id']][0]['value'])) ? $ratings[$attribute['id']][0]['value']: '.5'; ?>" min="0" max="1.0" step=".1">
											</div>
											<?php } ?> <?php echo $attribute['title']; ?> <?php /*(<?php echo $attribute['score']; ?>) (<?php echo (isset($ratings[$attribute['id']])) ? 'rated': 'not rated'; ?>) */ ?>
										</li>
									<?php } ?>
									</ul>
								<?php } ?>
								</div>
								<?php if ($this->ion_auth->logged_in()) { ?> 
								<h3>Suggest a Fitness Parameter</h3>
								<p>Help us define what makes the best diagrams. You can suggest an attribute here, it will automatically be marked as important by you. Don't add one that is already listed or goofy things will occur.</p>
								<form id="formaddattribute" >
								<div class="form-group">
									<label for="payload[title]" class="">Parameter</label>
									<div class="input-group">
										<input type="text" class="form-control" id="cas-addatt-title" name="payload[title]" placeholder="Clarity of Idea"><span class="input-group-btn"><a id="cas-addatt-trigger" data-loading-text="Here we go..." class="btn btn-default">Suggest it!</a></span>
									</div>
									<div id="attributefail" class="alert alert-danger " style="display: none;" role="alert">Blast... make sure you are signed in and you didn't leave the box blank.</div>
								</div>
								</form>
								<?php } ?>
							</div>
						</div>
					</div>
					<!-- /attributes tab -->
				</div>
				
				<ul class="breadcrumb top-space">
					<li class="active"><?php echo $title; ?> was updated <?php echo date('F jS, Y', $timestamp); ?>.</li>
				</ul>

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
						<div class=""><input type="text" class="form-control" id="cas-def-title" name="payload[title]" value="<?php echo $title; ?>"></div>
					</div>
					<div class="form-group">
						<label for="payload[excerpt]" class="">Excerpt</label>
						<div class=""><textarea type="text" class="form-control" id="cas-def-excerpt" name="payload[excerpt]"><?php echo $excerpt; ?></textarea></div>
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
					<div id="editorloading" class="alert alert-info progress-bar progress-bar-striped active" style="display: block; width: 100%; display: none;" role="alert">hiking...</div>
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
		function rate(rt,ri,rr) {
			rr = $('#att-range-'+ri).val();
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#att-submit-'+ri).text('hmmmmmmm...');
				},
				url: "/api/rate",
				data: {'payload[type]':'attribute', 'payload[typeid]':ri, 'payload[value]':rr},
				statusCode: {
					200: function(data) {
						//alert('rated!');
						$('#att-submit-'+ri).text('rated!').addClass('btn-success');
						window.location.reload();
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
		// New comment or flag
		function comment(ct,ci,cv) {
			cc = $('#commentvalue-'+ct+'-'+ci).val();
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#comment-'+ct+'-'+ci).text('flagging...');
				},
				url: "/api/create/comment",
				data: {'payload[type]':ct, 'payload[typeid]':ci, 'payload[value]':cv, 'payload[content]':cc},
				statusCode: {
					200: function(data) {
						//alert('rated!');
						$('#comment-'+ct+'-'+ci).text('the deed is done!').addClass('btn-success');
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						alert('hmmm, are you signed in?');
					},
					404: function(data) {
						//var response = JSON.parse(data);
						alert("things aren't looking so great...");
					}
				}
			});
		}
		// New comment or flag
		function flagatt(ci) {
			ct = 'attribute';
			cv = 'flag';
			cc = $('#commentvalue-'+ct+'-'+ci).val();
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#comment-'+ct+'-'+ci).text('flagging...');
				},
				url: "/api/create/comment",
				data: {'payload[type]':ct, 'payload[typeid]':ci, 'payload[value]':cv, 'payload[content]':cc},
				statusCode: {
					200: function(data) {
						//alert('rated!');
						$('#comment-'+ct+'-'+ci).text('ka-ching!').addClass('btn-success');
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						alert('hmmm, are you signed in?');
					},
					404: function(data) {
						//var response = JSON.parse(data);
						alert("things aren't looking so great...");
					}
				}
			});
		}
		$('#cas-app-diagramssort-submit').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#cas-app-diagramssort-submit').text('saving order, one sec...'); 
				},
				url: "/api/sortdiagrams",
				data: $("#diagramsorder").serialize(),
				statusCode: {
					200: function(data) {
						$('#cas-app-diagramssort-submit').addClass('btn-success').text('Success! Reloading page...'); 
						window.location.reload();
					},
					304: function(data) {
						//var response = JSON.parse(data);
						alert('Sort failed, nothing to sort I guess...');
						$('#cas-app-diagramssort-submit').text('Save order'); 
						//$('#attributefail').show();
						//$('#cas-addatt-trigger').button('reset'); 
					},
					401: function(data) {
						//var response = JSON.parse(data);
						alert('You need to be signed in to do this.');
						$('#cas-app-diagramssort-submit').text('Save order'); 
						//$('#attributefail').show();
						//$('#cas-addatt-trigger').button('reset'); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						alert('You can only rate once every 12 hours.');
						$('#cas-app-diagramssort-submit').text('Save order'); 
						//$('#attributefail').show();
						//$('#cas-addatt-trigger').button('reset'); 
					}
				}
			});
		});
		$('#cas-addatt-trigger').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#cas-addatt-trigger').button('loading'); 
					$('#attributefail').hide();
				},
				url: "/api/create/attribute",
				data: $("#formaddattribute").serialize(),
				statusCode: {
					200: function(data) {
						window.location.reload();
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#attributefail').show();
						$('#cas-addatt-trigger').button('reset'); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#attributefail').show();
						$('#cas-addatt-trigger').button('reset'); 
					}
				}
			});
		});
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
		    console.log(response.filename + 'has been uploaded');
		    $('#imgheader').css('background-image','url('+response.filename+')');
		    $('#cas-tax-fileurl').val(response.filename);
		});

		$('#submitupload').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#uploadbuttons').hide(); 
					$('#uploadfail').hide(); 
					$('#uploadloading').show();
				},
				url: "/api/update/diagram/<?php echo $id; ?>", 
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
