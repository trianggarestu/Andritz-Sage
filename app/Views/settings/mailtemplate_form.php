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
	<section class="content-header">
		<h1>E-mail Template Setup</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="">E-mail Settings</li>
			<li class="active">E-mail Template Setup</li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<!-- /.box -->
				<div class="box box-primary">
					<div class="box-body">

						<div class="box-header with-border">

						</div>

						<form id="validasi" action="<?= $form_action; ?>" method="post">


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
								<pre>Tinymce</pre>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ct_salesperson" name="ct_salesperson" placeholder="" value="[ $CONTRACT ]" readonly />

											<span class="input-group-btn">
												<a href="#" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ct_salesperson" name="ct_salesperson" placeholder="" value="[ $CTDESC ]" readonly />

											<span class="input-group-btn">
												<a href="#" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ct_salesperson" name="ct_salesperson" placeholder="" value="[ $MANAGER ]" readonly />

											<span class="input-group-btn">
												<a href="#" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
							</div>

							<div class="box-footer">
								<div class="col-xs-12 col-md-12">
									<input type="hidden" name="filepath" value="<?= trim($filepath) ?>" />
									<input type="hidden" name="tmpltuniq" value="<?= $tmpltuniq ?>" />

									<a href="<?= base_url('mailtemplatesetup'); ?>" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-times'></i>Batal</a>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class='fa fa-check'></i>Save</button>
								</div>
							</div>


						</form>


					</div>
				</div>
			</div>
		</div>
	</section>

</div>