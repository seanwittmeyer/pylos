	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="bs-component">
			<div class="jumbotron jumbotron-home" id="imgheader" style="background-image: url('<?php echo (isset($img['header']) && !empty($img['header'])) ? $img['header']['url']: '/includes/test/assets/Moofushi_Kandu_fish.jpg'; ?>');">
				<div class="container">
					<div style="position: relative; top: -80px; left: 0px;">
						<?php if ($this->ion_auth->logged_in()) { ?><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#pageupload"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit Image</button><?php } ?>
						<button type="button" class="btn btn-default btn-xs" data-toggle="popover" data-trigger="hover" title="Image Credit" data-content="<?php echo (isset($img['header']['caption']) && !empty($img['header']['caption'])) ? $img['header']['caption']: 'Shoot, no caption listed...'; ?>" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Credit</button>
					</div>
					<h1 class="light-text"><?php echo $title; ?><!-- (<?php echo $id; ?>)--></h1>
					<p class="light-text"><?php echo $excerpt; ?></p>
				</div>
			</div>
		</div>
	</div>
	<!-- /Jumbotron -->
	<!--<pre><?php print_r($img); ?></pre>-->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-lg-8">
				<p><?php echo $body; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12" id="graphcontainer">
				<div id="graph"></div>
				<div id="graph-info"></div>
				<!--
				<div id="graph-info"><button id="save">Save Image</button></div>
				<div id="svgdataurl"></div>
				<div id="pngdataurl"></div>
				<canvas width="960" height="500" style="display:none"></canvas
				-->
			</div>
			<hr />
		</div>


		<div class="row">
			<div class="col-lg-7">
				<div class="page-header">
					<?php $set = $this->shared->get_related($type,$id,true); ?>
					<?php if ($set !== false) : ?>
					<!--
					<hr />
					<h3>Related elements and topics</h3>
					<p>These are elements and topics related to <?php echo $title; ?>. You can also <a data-toggle="modal" data-target="#pageeditor">modify relationships &rarr;</a></p>
					-->
					<?php foreach ($set as $single) { ?><blockquote><h4><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>"><span class="glyphicon glyphicon-<?php if($single['type'] == "taxonomy") { print('list'); } elseif ($single['type'] == "paper") { print('file'); } elseif ($single['type'] == "definition") { print('education'); } ?>" aria-hidden="true"></span> <?php echo $single['title']; ?></a></h4>
						<p style="font-size:15px;"><?php echo $single['excerpt']; ?> <a href="/<?php echo $single['type']; ?>/<?php echo $single['slug']; ?>">Learn More about <?php echo $single['title']; ?> &rarr;</a></p></blockquote>
					<?php } ?> <?php elseif ($this->ion_auth->is_admin()): ?><blockquote>No connected topics...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#pageeditor">Add Relationships</button></blockquote><?php endif; ?>

					<ul class="breadcrumb">
						<li class="active"><?php echo $title; ?> was updated <?php echo date('F jS, Y', $timestamp); ?>.</li>
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
					<?php foreach ($links as $link) { ?><blockquote class="embedly-card" data-card-key="74435e49e8fa468eb2602ea062017ceb" data-card-controls="0"><h4><a href="<?php echo $link['uri']; ?>"><?php echo $link['title']; ?></a></h4><p><?php echo $link['excerpt']; ?></p></blockquote><?php } ?> 
				</div><!-- /CAS Embed -->
				<?php } ?>
			</div>
		</div>
	</div>
	
	<!-- /Panels -->
	<!-- Page Editor -->
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
						<!--<div class="cas-summernote">Hello Summernote</div>-->
						<div class=""><textarea class="cas-summernote" id="cas-def-body" name="payload[body]"><?php echo $body; ?></textarea></div>
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
	<!-- /Page Editor -->

<!-- d3.js visualization modeled off of the Concept Map at http://www.findtheconversation.com/concept-map/ by Chris Willard (http://wllrd.com/). -->
<script src="/includes/js/d3.v3.min.js"></script>
<script>

(function() {
	var a = $("#graphcontainer").width(),
		c = a*.7,
		h = c,
		U = 200,
		K = 22,
		S = 20,
		s = 8,
		R = 110,
		J = 30,
		o = 15,
		t = 10,
		w = 1000,
		F = "elastic",
		N = "#2780e3";
	var T, q, x, j, H, A, P;
	var L = {},
		k = {};
	var i, y;
	var r = d3.layout.tree().size([360, h / 2 - R]).separation(function(Y, X) {
		return (Y.parent == X.parent ? 1 : 2) / Y.depth
	});
	var W = d3.svg.diagonal.radial().projection(function(X) {
		return [X.y, X.x / 180 * Math.PI]
	});
	var v = d3.svg.line().x(function(X) {
		return X[0]
	}).y(function(X) {
		return X[1]
	}).interpolate("bundle").tension(0.5);
	var d = d3.select("#graph").append("svg").attr("width", a).attr("height", c).append("g").attr("transform", "translate(" + a / 2 + "," + c / 2 + ")");
	var I = d.append("rect").attr("class", "bg").attr({
		x: a / -2,
		y: c / -2,
		width: a,
		height: c,
		fill: "transparent"
	}).on("click", O);
	var B = d.append("g").attr("class", "links"),
		f = d.append("g").attr("class", "episodes"),
		E = d.append("g").attr("class", "nodes");
	var Q = d3.select("#graph-info");
	d3.json("/api/visualization/conversation/6", function(X, Y) {
	//d3.json("/includes/test/vizdata.js", function(X, Y) {
		T = d3.map(Y);
		q = d3.merge(T.values());
		x = {};
		A = d3.map();
		q.forEach(function(aa) {
			aa.key = p(aa.name);
			aa.canonicalKey = aa.key;
			x[aa.key] = aa;
			if (aa.group) {
				if (!A.has(aa.group)) {
					A.set(aa.group, [])
				}
				A.get(aa.group).push(aa)
			}
		});
		j = d3.map();
		T.get("episodes").forEach(function(aa) {
			aa.links = aa.links.filter(function(ab) {
				return typeof x[p(ab)] !== "undefined" && ab.indexOf("r-") !== 0
			});
			j.set(aa.key, aa.links.map(function(ab) {
				var ac = p(ab);
				if (typeof j.get(ac) === "undefined") {
					j.set(ac, [])
				}
				j.get(ac).push(aa);
				return x[ac]
			}))
		});
		var Z = window.location.hash.substring(1);
		if (Z && x[Z]) {
			G(x[Z])
		} else {
			O();
			M()
		}
		window.onhashchange = function() {
			var aa = window.location.hash.substring(1);
			if (aa && x[aa]) {
				G(x[aa], true)
			}
		}
	});
	function O() {
		if (L.node === null) {
			return
		}
		L = {
			node: null,
			map: {}
		};
		i = Math.floor(c / T.get("episodes").length);
		y = Math.floor(T.get("episodes").length * i / 2);
		T.get("episodes").forEach(function(af, ae) {
			af.x = U / -2;
			af.y = ae * i - y
		});
		var ad = 180 + J,
			Z = 360 - J,
			ac = (Z - ad) / (T.get("themes").length - 1);
		T.get("themes").forEach(function(af, ae) {
			af.x = Z - ae * ac;
			af.y = h / 2 - R;
			af.xOffset = -S;
			af.depth = 1
		});
		ad = J;
		Z = 180 - J;
		ac = (Z - ad) / (T.get("perspectives").length - 1);
		T.get("perspectives").forEach(function(af, ae) {
			af.x = ae * ac + ad;
			af.y = h / 2 - R;
			af.xOffset = S;
			af.depth = 1
		});
		H = [];
		var ab, Y, aa, X = h / 2 - R;
		T.get("episodes").forEach(function(ae) {
			ae.links.forEach(function(af) {
				ab = x[p(af)];
				if (!ab || ab.type === "reference") {
					return
				}
				Y = (ab.x - 90) * Math.PI / 180;
				aa = ae.key + "-to-" + ab.key;
				H.push({
					source: ae,
					target: ab,
					key: aa,
					canonicalKey: aa,
					x1: ae.x + (ab.type === "theme" ? 0 : U),
					y1: ae.y + K / 2,
					x2: Math.cos(Y) * X + ab.xOffset,
					y2: Math.sin(Y) * X
				})
			})
		});
		P = [];
		A.forEach(function(af, ag) {
			var ae = (ag[0].x - 90) * Math.PI / 180;
			a2 = (ag[1].x - 90) * Math.PI / 180, bulge = 20;
			P.push({
				x1: Math.cos(ae) * X + ag[0].xOffset,
				y1: Math.sin(ae) * X,
				xx: Math.cos((ae + a2) / 2) * (X + bulge) + ag[0].xOffset,
				yy: Math.sin((ae + a2) / 2) * (X + bulge),
				x2: Math.cos(a2) * X + ag[1].xOffset,
				y2: Math.sin(a2) * X
			})
		});
		window.location.hash = "";
		M()
	}
	function G(Y, X) {
		if (L.node === Y && X !== true) {
			if (Y.type === "episode") {
				window.location.href = "/" + Y.slug;
				return
			}
			L.node.children.forEach(function(aa) {
				aa.children = aa._group
			});
			e();
			return
		}
		if (Y.isGroup) {
			L.node.children.forEach(function(aa) {
				aa.children = aa._group
			});
			Y.parent.children = Y.parent._children;
			e();
			return
		}
		Y = x[Y.canonicalKey];
		q.forEach(function(aa) {
			aa.parent = null;
			aa.children = [];
			aa._children = [];
			aa._group = [];
			aa.canonicalKey = aa.key;
			aa.xOffset = 0
		});
		L.node = Y;
		L.node.children = j.get(Y.canonicalKey);
		L.map = {};
		var Z = 0;
		L.node.children.forEach(function(ac) {
			L.map[ac.key] = true;
			ac._children = j.get(ac.key).filter(function(ad) {
				return ad.canonicalKey !== Y.canonicalKey
			});
			ac._children = JSON.parse(JSON.stringify(ac._children));
			ac._children.forEach(function(ad) {
				ad.canonicalKey = ad.key;
				ad.key = ac.key + "-" + ad.key;
				L.map[ad.key] = true
			});
			var aa = ac.key + "-group",
				ab = ac._children.length;
			ac._group = [{
				isGroup: true,
				key: aa + "-group-key",
				canonicalKey: aa,
				name: ab,
				count: ab,
				xOffset: 0
			}];
			L.map[aa] = true;
			Z += ab
		});
		L.node.children.forEach(function(aa) {
			aa.children = Z > 50 ? aa._group : aa._children
		});
		window.location.hash = L.node.key;
		e()
	}
	function n() {
		k = {
			node: null,
			map: {}
		};
		z()
	}
	function g(X) {
		if (k.node === X) {
			return
		}
		k.node = X;
		k.map = {};
		k.map[X.key] = true;
		if (X.key !== X.canonicalKey) {
			k.map[X.parent.canonicalKey] = true;
			k.map[X.parent.canonicalKey + "-to-" + X.canonicalKey] = true;
			k.map[X.canonicalKey + "-to-" + X.parent.canonicalKey] = true
		} else {
			j.get(X.canonicalKey).forEach(function(Y) {
				k.map[Y.canonicalKey] = true;
				k.map[X.canonicalKey + "-" + Y.canonicalKey] = true
			});
			H.forEach(function(Y) {
				if (k.map[Y.source.canonicalKey] && k.map[Y.target.canonicalKey]) {
					k.map[Y.canonicalKey] = true
				}
			})
		}
		z()
	}
	function M() {
		V();
		B.selectAll("path").attr("d", function(X) {
			return v([
				[X.x1, X.y1],
				[X.x1, X.y1],
				[X.x1, X.y1]
			])
		}).transition().duration(w).ease(F).attr("d", function(X) {
			return v([
				[X.x1, X.y1],
				[X.target.xOffset * s, 0],
				[X.x2, X.y2]
			])
		});
		D(T.get("episodes"));
		b(d3.merge([T.get("themes"), T.get("perspectives")]));
		C([]);
		m(P);
		Q.html('<!--<a href="/the-concept-map/">What\'s this?</a>-->');
		n();
		z()
	}
	function e() {
		var X = r.nodes(L.node);
		X.forEach(function(Z) {
			if (Z.depth === 1) {
				Z.y -= 20
			}
		});
		H = r.links(X);
		H.forEach(function(Z) {
			if (Z.source.type === "episode") {
				Z.key = Z.source.canonicalKey + "-to-" + Z.target.canonicalKey
			} else {
				Z.key = Z.target.canonicalKey + "-to-" + Z.source.canonicalKey
			}
			Z.canonicalKey = Z.key
		});
		V();
		B.selectAll("path").transition().duration(w).ease(F).attr("d", W);
		D([]);
		b(X);
		C([L.node]);
		m([]);
		var Y = "";
		if (L.node.description) {
			Y = L.node.description
		}
		Q.html(Y);
		n();
		z()
	}
	function b(X) {
		var X = E.selectAll(".node").data(X, u);
		var Y = X.enter().append("g").attr("transform", function(aa) {
			var Z = aa.parent ? aa.parent : {
				xOffset: 0,
				x: 0,
				y: 0
			};
			return "translate(" + Z.xOffset + ",0)rotate(" + (Z.x - 90) + ")translate(" + Z.y + ")"
		}).attr("class", "node").on("mouseover", g).on("mouseout", n).on("click", G);
		Y.append("circle").attr("r", 0);
		Y.append("text").attr("stroke", "#fff").attr("stroke-width", 4).attr("class", "label-stroke");
		Y.append("text").attr("font-size", 0).attr("class", "label");
		X.transition().duration(w).ease(F).attr("transform", function(Z) {
			if (Z === L.node) {
				return null
			}
			var aa = Z.isGroup ? Z.y + (7 + Z.count) : Z.y;
			return "translate(" + Z.xOffset + ",0)rotate(" + (Z.x - 90) + ")translate(" + aa + ")"
		});
		X.selectAll("circle").transition().duration(w).ease(F).attr("r", function(Z) {
			if (Z == L.node) {
				return 80
			} else {
				if (Z.isGroup) {
					return 7 + Z.count
				} else {
					return 4.5
				}
			}
		});
		X.selectAll("text").transition().duration(w).ease(F).attr("dy", ".3em").attr("font-size", function(Z) {
			if (Z.depth === 0) {
				return 20
			} else {
				return 15
			}
		}).text(function(Z) {
			return Z.name
		}).attr("text-anchor", function(Z) {
			if (Z === L.node || Z.isGroup) {
				return "middle"
			}
			return Z.x < 180 ? "start" : "end"
		}).attr("transform", function(Z) {
			if (Z === L.node) {
				return null
			} else {
				if (Z.isGroup) {
					return Z.x > 180 ? "rotate(180)" : null
				}
			}
			return Z.x < 180 ? "translate(" + t + ")" : "rotate(180)translate(-" + t + ")"
		});
		X.selectAll("text.label-stroke").attr("display", function(Z) {
			return Z.depth === 1 ? "block" : "none"
		});
		X.exit().remove()
	}
	function V() {
		var X = B.selectAll("path").data(H, u);
		X.enter().append("path").attr("d", function(Z) {
			var Y = Z.source ? {
				x: Z.source.x,
				y: Z.source.y
			} : {
				x: 0,
				y: 0
			};
			return W({
				source: Y,
				target: Y
			})
		}).attr("class", "link");
		X.exit().remove()
	}
	function C(Z) {
		var ac = d.selectAll(".detail").data(Z, u);
		var Y = ac.enter().append("g").attr("class", "detail");
		var ab = Z[0];
		if (ab && ab.type === "episode") {
			var aa = Y.append("a").attr("xlink:href", function(ae) {
				return ae.slug
			});
			Y.append("text").attr("fill", "#aaa").attr("text-anchor", "middle").attr("y", (o + t) * -1).text("FIELD")
			aa.append("text").attr("fill", N).attr("text-anchor", "middle").attr("y", (o + t)).text(function(ae) {
				//return "episode " + ae.episode
				return "Explore → "
			});
		} else {
			if (ab && ab.type === "theme") {
				Y.append("text").attr("fill", "#aaa").attr("text-anchor", "middle").attr("y", (o + t) * -1).text("CONCEPT")
				var aa = Y.append("a").attr("xlink:href", function(ae) {
					return ae.slug
				});
				aa.append("text").attr("fill", N).attr("text-anchor", "middle").attr("y", (o + t)).text(function(ae) {
					//return "episode " + ae.episode
					return "Explore → "
				});
			} else {
				if (ab && ab.type === "perspective") {
					/*
					var ad = ac.selectAll(".pair").data(A.get(ab.group).filter(function(ae) {
						return ae !== ab
					}), u);
					ad.enter().append("text").attr("fill", "#aaa").attr("text-anchor", "middle").attr("y", function(af, ae) {
						return (o + t) * 2 + (ae * (o + t))
					}).text(function(ae) {
						return "(vs. " + ae.name + ")"
					}).attr("class", "pair").on("click", G);
					*/
					Y.append("text").attr("fill", "#aaa").attr("text-anchor", "middle").attr("y", (o + t) * -1).text("FEATURE");
					var aa = Y.append("a").attr("xlink:href", function(ae) {
						return ae.slug
					});
					aa.append("text").attr("fill", N).attr("text-anchor", "middle").attr("y", (o + t)).text(function(ae) {
						//return "episode " + ae.episode
						return "Explore → "
					});


					ad.exit().remove()
				}
			}
		}
		ac.exit().remove();
		var X = d.selectAll(".all-episodes").data(Z);
		X.enter().append("text").attr("text-anchor", "start").attr("x", a / -2 + t).attr("y", c / 2 - t).text("Reset Visualization").attr("class", "all-episodes").on("click", O);
		X.exit().remove()
	}
	function D(Y) {
		var Y = f.selectAll(".episode").data(Y, u);
		var X = Y.enter().append("g").attr("class", "episode").on("mouseover", g).on("mouseout", n).on("click", G);
		X.append("rect").attr("x", U / -2).attr("y", K / -2).attr("width", U).attr("height", K).transition().duration(w).ease(F).attr("x", function(Z) {
			return Z.x
		}).attr("y", function(Z) {
			return Z.y
		});
		X.append("text").attr("x", function(Z) {
			return U / -2 + t
		}).attr("y", function(Z) {
			return K / -2 + o
		}).attr("fill", "#fff").text(function(Z) {
			return Z.name
		}).transition().duration(w).ease(F).attr("x", function(Z) {
			return Z.x + t
		}).attr("y", function(Z) {
			return Z.y + o
		});
		Y.exit().selectAll("rect").transition().duration(w).ease(F).attr("x", function(Z) {
			return U / -2
		}).attr("y", function(Z) {
			return K / -2
		});
		Y.exit().selectAll("text").transition().duration(w).ease(F).attr("x", function(Z) {
			return U / -2 + t
		}).attr("y", function(Z) {
			return K / -2 + o
		});
		Y.exit().transition().duration(w).remove()
	}
	function m(Y) {
		var X = f.selectAll("path").data(Y);
		X.enter().append("path").attr("d", function(Z) {
			return v([
				[Z.x1, Z.y1],
				[Z.x1, Z.y1],
				[Z.x1, Z.y1]
			])
		}).attr("stroke", "#000").attr("stroke-width", 1.5).transition().duration(w).ease(F).attr("d", function(Z) {
			return v([
				[Z.x1, Z.y1],
				[Z.xx, Z.yy],
				[Z.x2, Z.y2]
			])
		});
		X.exit().remove()
	}
	function z() {
		f.selectAll("rect").attr("fill", function(X) {
			return l(X, "#AAA", N, "#AAA")
		});
		B.selectAll("path").attr("stroke", function(X) {
			return l(X, "#aaa", N, "#aaa")
		}).attr("stroke-width", function(X) {
			return l(X, "1.5px", "2.5px", "1px")
		}).attr("opacity", function(X) {
			return l(X, 0.4, 0.75, 0.3)
		}).sort(function(Y, X) {
			if (!k.node) {
				return 0
			}
			var aa = k.map[Y.canonicalKey] ? 1 : 0,
				Z = k.map[X.canonicalKey] ? 1 : 0;
			return aa - Z
		});
		E.selectAll("circle").attr("fill", function(X) {
			if (X === L.node) {
				return "#000"
			} else {
				if (X.type === "theme") {
					return l(X, "#666", N, "#000")
				} else {
					if (X.type === "perspective") {
						return "#fff"
					}
				}
			}
			return l(X, "#000", N, "#999")
		}).attr("stroke", function(X) {
			if (X === L.node) {
				return l(X, null, N, null)
			} else {
				if (X.type === "theme") {
					return "#000"
				} else {
					if (X.type === "perspective") {
						return l(X, "#000", N, "#000")
					}
				}
			}
			return null
		}).attr("stroke-width", function(X) {
			if (X === L.node) {
				return l(X, null, 2.5, null)
			} else {
				if (X.type === "theme" || X.type === "perspective") {
					return 1.5
				}
			}
			return null
		});
		E.selectAll("text.label").attr("fill", function(X) {
			return (X === L.node || X.isGroup) ? "#fff" : l(X, "#000", N, "#999")
		})
	}
	function p(X) {
		return X.toLowerCase().replace(/[ .,()]/g, "-")
	}
	function u(X) {
		return X.key
	}
	function l(X, aa, Z, Y) {
		if (k.node === null) {
			return aa
		}
		return k.map[X.key] ? Z : aa
	}
	d3.select("#save").on("click", function(){
	  var html = d3.select("svg")
	        .attr("version", 1.1)
	        .attr("xmlns", "http://www.w3.org/2000/svg")
	        .node().parentNode.innerHTML;
	
	  //console.log(html);
	  var imgsrc = 'data:image/svg+xml;base64,'+ btoa(html);
	  var img = '<img src="'+imgsrc+'">'; 
	  d3.select("#svgdataurl").html(img);
	
	
	  var canvas = document.querySelector("canvas"),
		  context = canvas.getContext("2d");
	
	  var image = new Image;
	  image.src = imgsrc;
	  image.onload = function() {
		  context.drawImage(image, 0, 0);
	
		  var canvasdata = canvas.toDataURL("image/png");
	
		  var pngimg = '<img src="'+canvasdata+'">'; 
	  	  d3.select("#pngdataurl").html(pngimg);
	
		  var a = document.createElement("a");
		  a.download = "cas-explorer.png";
		  a.href = canvasdata;
		  a.click();
	  };
	
	});

})();
</script>