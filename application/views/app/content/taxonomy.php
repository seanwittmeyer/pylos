	<!-- General Content Block -->
	<div class="container top-nospace">
		<div class="row topbottom-space">
			<div class="col-md-9">
				<h1>Taxonomy</h1>
				<p class="lead">Taxonomy are collections of CAS Explorer content, from the Urbanism Applications of Complex Adaptive Systems theory to the types of features and attributes. This is an alphabetical listing of all taxonomy/collections/sets in the CAS Explorer. <a href="/definition"> See all definitions in the site &rarr;</a></p>
			</div>
		</div>
	</div>
	<!-- /General Content Block -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-sm-7">
			<?php $set = $this->shared->get_data2('taxonomy',false,false,true); ?>
			<?php foreach ($set as $single) { ?>
					<?php if ($this->ion_auth->is_admin()) { ?><div class="pull-right"><a href="/taxonomy/<?php echo $single['slug']; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-title="Visit the category and make changes from the edit menu"><span class=""></span>View &amp; Edit</a> <a href="/api/remove/taxonomy/<?php echo $single['id']; ?>/refresh" class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Are you sure? No going back..."><span class=""></span>Delete</a></div><?php } ?>
					<h4><a class="t_list_<?php echo $single['id']; ?>" href="/taxonomy/<?php echo $single['slug']; ?>"><?php echo $single['title']; ?></a></h4>
					<p><?php echo $single['excerpt']; ?> <a href="/taxonomy/<?php echo $single['slug']; ?>">Learn More about <?php echo $single['title']; ?> &rarr;</a></p>
					<hr>
			<?php } ?>
			</div>
		</div>
	</div>
	
	<!-- /Panels -->
