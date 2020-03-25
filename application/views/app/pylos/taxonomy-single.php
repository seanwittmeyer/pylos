<!-- Content Area -->

	<div class="container-fluid build-wrapper">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-sm-3">
				<?php $this->load->view('app/pylos/templates/menu-beta'); ?>
			</div>
			<!-- /sidebar -->
			<!-- Main -->
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-12">
						<div class="headline-parent"><h1 class="headline headline-title"><?php echo $taxonomy['title']; ?></h1></div>
						<div class="row">
							<div class="col-lg-11">
								<h3><?php echo $taxonomy['excerpt']; ?></h3>
								<p><?php echo $taxonomy['description']; ?></p>
								<p><a href="<?php echo site_url("pylos/taxonomy/create"); ?>">Create a taxonomy &rarr;</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>

<!-- End Content Area -->
	<!-- /File Uploader -->
	<script>
		$('#pylos_newelement_tags').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); foreach ($tags['theme'] as $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
			
		});
		$('#pylos_newelement_dependencies').selectize({
			persist: false,
			createOnBlur: true,
			create: true,
			options: <?php $taglist = array(); foreach ($tags['dependency'] as $t) $taglist[] = array('value'=>$t,'text'=>$t); echo json_encode($taglist); ?>
		});
		$('#pageeditor').on('shown.bs.modal', function () {
			$('.cas-summernote').summernote({
			  toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['style']],
			    ['simple', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['link', ['linkDialogShow', 'picture']],
			    ['code', ['codeview']],
			    ['fullscreen', ['fullscreen']],
			  ],
			  callbacks: {
				  onImageUpload: function(files, editor, welEditable) {
				  	sendFile(files[0], this, welEditable);
				  }
			  }
			});
		});
		$('#pylos-element-images').fileinput({
		    uploadUrl: "/pylos/api/uploadimage/<?php echo $newunique; ?>/thumbnail", // server upload action
		    uploadAsync: true,
		    showUpload: false, // hide upload button
		    showRemove: false, // hide remove button
		    minFileCount: 1,
		    maxFileCount: 10
		}).on("filebatchselected", function(event, files) {
		    // trigger upload method immediately after files are selected
		    $('#pylos-element-images').fileinput("upload");
		}).on('fileuploaded', function(event, data, previewId, index) {
		    var form = data.form, files = data.files, extra = data.extra,
		        response = data.response, reader = data.reader;
		    console.log(response.filename + ' has been uploaded');
		    //$('#imgheader').css('background-image','url('+response.filename+')');
		    $('#pylos_newelement_thumbnail').val(response.filename);
		});

		function sendFile(file, el, welEditable) {
            data = new FormData();
            data.append("userfile", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "/pylos/api/uploadimage/<?php echo $newunique; ?>/image",
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
	                //var url = JSON.parse(data);
	                alert(url);
                    //editor.insertImage(welEditable, url);
                    $(el).summernote('editor.insertImage', url);
                    console.log(url+' has been uploaded and inserted into summernote. bam!');
                }
            });
        }

		$('#submitnewelement').click(function() {
			$.ajax({
				type: "POST",
				beforeSend: function() {
					$('#newelementbuttons').hide(); 
					$('#newelementfail').hide(); 
					$('#newelementloading').show();
				},
				url: "/pylos/api/create/strategies",
				data: $("#formnewelement").serialize(),
				statusCode: {
					200: function(data) {
						$('#newelementloading').hide(); 
						$('#newelementsuccess').show();
						var response = JSON.parse(data); 
						$('#newelementbuttons').show(); 
						//window.location.reload();
						// <?php echo $newunique; ?> 
						window.location.assign(response.result.url + '?firstrun=true');
						//console.log(response.result.url + '?firstrun=true');
					},
					403: function(data) {
						//var response = JSON.parse(data);
						$('#newelementloading').hide(); 
						$('#newelementfail').show();
						$('#newelementbuttons').show(); 
					},
					404: function(data) {
						//var response = JSON.parse(data);
						$('#newelementloading').hide(); 
						$('#newelementfail').show();
						$('#newelementbuttons').show(); 
					}
				}
			});
		});
	</script>


