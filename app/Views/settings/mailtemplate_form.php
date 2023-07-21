<script type="text/javascript" src="<?= base_url() ?>/assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: 'textarea',
		height: 500,
		theme: 'silver',
		plugins: [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			"table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
		],
		toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor | print preview code | fontselect fontsizeselect",
		image_advtab: true,
		external_filemanager_path: "<?= base_url() ?>/assets/filemanager/",
		filemanager_title: "Responsive Filemanager",
		filemanager_access_key: "<?php //= config_item('file_manager') 
									?>",
		external_plugins: {
			"filemanager": "<?= base_url() ?>/assets/filemanager/plugin.min.js"
		},
		templates: [{
				title: 'Test template 1',
				content: 'Test 1'
			},
			{
				title: 'Test template 2',
				content: 'Test 2'
			}
		],
		content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css'
		],
		relative_urls: false,
		remove_script_host: false
	});
</script>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Form Artikel / Blog
		</h1>

	</section>



	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<!-- /.box -->
				<div class="box box-primary">
					<div class="box-body">
						<div class="row">
							<div class="box-header with-border">

							</div>

							<form id="validasi" action="<?= $form_action; ?>" method="post">
								<div class="row">

									<div class="col-sm-12">
										<div class="form-group">
											<label for="subject_email" class="control-label small">Subject</label>
											<input type="text" class="form-control input-sm" name="subject_email" id="subject_email" placeholder="Subject" value="<?= $subject ?>" />
											<span class="help-block"></span>
										</div>
									</div>


									<div class="col-sm-12">
										<div class="form-group">
											<label for="blog_content" class="control-label small">Body Email <code> (Template) </code></label>
											<textarea rows="25" class="form-control input-sm" name="body_email" id="body_email" placeholder="Detail Artikel">
											<?= $mail_body; ?>
											</textarea>
											<span class="help-block"><?php //= $validation->getError('blog_content'); 
																		?></span>
										</div>
									</div>

									<div class="col-sm-12">
										<pre>
Tinymce
</pre>
									</div>

								</div>
								<div class="box-footer">
									<div class="col-xs-12 col-md-12">
										<input type="hidden" name="filepath" value="<?= trim($filepath) ?>" />
										<input type="hidden" name="tmpltuniq" value="<?= $tmpltuniq ?>" />

										<a href="#" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-times'></i>Batal</a>
										<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class='fa fa-check'></i>Save</button>
									</div>
								</div>


							</form>


						</div>
					</div>
				</div>
	</section>

</div>