<?php 
	$dir = FCPATH.'store/';
	function folderSize($dir) {
		// echo $dir;
		$size = 0;
	    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
	        $size += is_file($each) ? filesize($each) : folderSize($each);
	    }
	    return $size;
	}
	function echoSize($size) {
		$size = round($size,1);
		if ($size >= 1000000000) {
		    echo round($size/1000000000,2).' GB';
	    } elseif ($size >= 1000000) {
		    echo round($size/1000000).' MB';
	    } elseif ($size >= 1000) {
		    echo round($size/1000).' KB';
	    } else {
		    echo $size;
	    }
	    
	}
	
	    /* */
    ?><!-- Content Area -->
		<div class="row">
			<div class="col-lg-3"><h3 style="margin-top: 0;">Storage</h3></div>
			<div class="col-lg-8">
			<p style="font-size: 100%;">
				The platform hosts a variety of digital assets from images and thumbnails to block files and revisions. Content is split up by asset type below.
			</p>
				<div class="row">
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echoSize(folderSize(FCPATH.'store/')); ?></h3><p style="font-size: 100%;">Total Storage Assets</p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echoSize(folderSize(FCPATH.'store/pylosfiles/')); ?></h3><p style="font-size: 100%;">File Storage<br /></p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echoSize(folderSize(FCPATH.'store/pylosimg')); ?></h3><p style="font-size: 100%;">Image Storage</p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echoSize(folderSize(FCPATH.'store/pylosimport')); ?></h3><p style="font-size: 100%;">Import Holding Assets</p></div>
					<div class="clear"></div>
					<div class="col-lg-3">
						<h3 style="margin-top: 0;">Actions</h3>
						<p style="font-size: 100%;">
							<a href="<?php echo site_url("pylos/api/purgefiles"); ?>"  data-toggle="tooltip" data-placement="top" title="Remove files not associated with content" class="btn btn-sm btn-danger">Purge <?php 	
								$this->db->where('parenttype','');
								$this->db->where('parentid','');
								$this->db->from('build_pylos_files');
								echo $this->db->count_all_results();
							?> orphaned files</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-lg-12"><hr /></div>
		</div>			
		<div class="row">
			<div class="col-lg-3"><h3 style="margin-top: 0;">Blocks and Guides</h3></div>
			<div class="col-lg-8">
				<p style="font-size: 100%;">
					It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
				</p>
				<div class="row">
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echo $this->db->count_all('build_pylos_block'); ?></h3><p style="font-size: 100%;">Blocks</p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echo $this->db->count_all('build_pylos_guides'); ?></h3><p style="font-size: 100%;">Guides</p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echo $this->db->count_all('build_pylos_taxonomy'); ?></h3><p style="font-size: 100%;">Dependencies</p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echo $this->db->count_all('build_pylos_tags'); ?></h3><p style="font-size: 100%;">Tags</p></div>
				</div>
			</div>
			<div class="col-lg-12"><hr /></div>
		</div>			
		<div class="row">
			<div class="col-lg-3"><h3 style="margin-top: 0;">Design Explorer</h3></div>
			<div class="col-lg-8">
				<p style="font-size: 100%;">
					It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
				</p>
				<div class="row">
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echoSize(folderSize(FCPATH.'store/de/')); ?></h3><p style="font-size: 100%;">Design Explorer Assets</p></div>
					<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echo $this->db->count_all('build_pylos_designexplorer'); ?></h3><p style="font-size: 100%;">Datasets</p></div>
					<div class="clear"></div>
					<div class="col-lg-3">
						<h3 style="margin-top: 0;">Actions</h3>
						<p style="font-size: 100%;">
							<a href="<?php echo site_url("pylos/api/purgefiles"); ?>" data-toggle="tooltip" data-placement="top" title="Remove datasets older than 6 months" class="btn btn-sm btn-danger">Purge <?php 	
								$this->db->where('parenttype','');
								$this->db->where('parentid','');
								$this->db->from('build_pylos_files');
								echo $this->db->count_all_results();
							?> old datasets</a>
						</p>
					</div>
				</div>
			</div>
			<div class="col-lg-12"><hr /></div>
		</div>			
		<div class="row">
			<div class="col-lg-3"><h3 style="margin-top: 0;">Presentations</h3></div>
			<div class="col-lg-8">
				<p style="font-size: 100%;">
					It's easy to get lost in the weeds when trying to find useful computational design tools and scripts, we've curated the best tools into tutorials for solving design problems.
				</p>
			<div class="row">
				<div class="col-lg-3"><h3 style="margin-top: 0;"><?php echo $this->db->count_all('build_pylos_presentations'); ?></h3><p style="font-size: 100%;">Presentations</p></div>
			</div>
			</div>
			<div class="col-lg-12"><hr /></div>
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

