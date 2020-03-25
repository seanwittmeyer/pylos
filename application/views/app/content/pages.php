	<!-- General Content Block -->
	<div class="container-fluid build-wrapper">
		<div class="row topbottom-space">
			<div class="col-md-9">
				<h1>Pages</h1>
				<p class="lead">This is a listing of all pages in the CAS Explorer. Much of the content in this site is also categorized as <a href="/definition">definitions (key thinkers, attributes, etc)</a> and <a href="/taxonomy">taxonomies (collections, applications, sets, etc.)</a>, both good places to find what you may be looking for.</p>
			</div>
		</div>
	</div>
	<!-- /General Content Block -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-sm-7">
			<?php $set = $this->shared->get_data2('page',false,false,true); ?>
			<?php foreach ($set as $single) { ?>
				<?php if ($this->ion_auth->is_admin()) { ?><a href="/api/remove/page/<?php echo $single['id']; ?>/refresh" class="btn btn-danger btn-xs pull-right" data-toggle="tooltip" data-title="Are you sure? No going back..."><span class=""></span>Delete</a><?php } ?>
				<h4><a class="t_list_<?php echo $single['id']; ?>" href="/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></h4>
				<hr>
			<?php } ?>
			</div>
		</div>
	</div>
	<!-- /Panels -->