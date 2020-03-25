	<!-- Jumbotron --
	<div class="container-jumbotron">
		<div class="bs-component">
			<div class="jumbotron jumbotron-home" style="background-image: url('/includes/test/assets/Moofushi_Kandu_fish.jpg');">
				<div class="container">
					<h1 class="light-text"><?php echo $title; ?> (<?php echo $id; ?>)</h1>
				</div>
			</div>
		</div>
	</div>
	<!-- /Jumbotron -->
	<!-- General Content Block -->
	<div class="container-fluid build-wrapper">
		<div class="row topbottom-somespace">
			<div class="col-md-8">
				<h1><?php echo ucfirst($type); ?> Feed</h2></h1>
				<p class="lead">This is a collection of <?php echo $type; ?> feed items posted through out the site.</p>
			</div>
		</div>
	</div>
	<!-- /General Content Block -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-sm-12 masonrygrid">
				<?php $feeditems = $this->shared->get_data2('link',false,array('type'=>$type));
					foreach ($feeditems as $link) { ?>
				<div class="cas-embed col-sm-4 masonryblock">
					<blockquote class="embedly-card" data-card-key="74435e49e8fa468eb2602ea062017ceb" data-card-controls="0"><h4><a href="<?php echo $link['uri']; ?>"><?php echo $link['title']; ?></a></h4><p><?php echo $link['excerpt']; ?></p></blockquote><div class="feed-footer"><address data-toggle="tooltip" data-title="<?php echo $link['excerpt']; ?>">Description</address> | This is linked to <a href="/<?php echo $link['hosttype']; ?>/<?php $__host = $this->shared->get_data($link['hosttype'],$link['hostid']); echo $__host['slug']; ?>"><?php echo $__host['title']; ?></a>.<?php if ($this->ion_auth->is_admin()) { ?> | <a href="/api/remove/link/<?php echo $link['id']; ?>/refresh" data-toggle="tooltip" data-title="Are you sure?">Delete</a><?php } ?></div>
				</div><!-- /CAS Embed -->

				<?php } ?>
			</div>
			<div class="col-lg-4">
			</div>
		</div>
	</div>
	<script>
		$('.embedly-card').imagesLoaded().progress( function() {
			$('.embedly-card').masonry();
		});
	</script>
