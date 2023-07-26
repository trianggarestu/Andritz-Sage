<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>

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
									<input type="text" class="form-control input-sm required" name="subject_email" id="subject_email" placeholder="Subject" value="<?= $subject ?>" />
									<span class="help-block"></span>
								</div>
							</div>


							<div class="col-sm-12">
								<div class="form-group">
									<label for="blog_content" class="control-label small">Body Email <code> (Template) </code></label>
									<textarea rows="25" class="form-control input-sm required" name="body_email" id="body_email" placeholder="Detail Artikel">
											<?= $mail_body; ?>
											</textarea>
									<span class="help-block"><?php //= $validation->getError('blog_content'); 
																?></span>
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

							<div class="col-sm-12">
								<pre>* Click Button Copied to copy text (available variable) to clipboard and in the textarea press shortcut keyboard Ctrl + V to paste variable to body e-mail.</pre>
							</div>
							<div class="col-sm-12">
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>USER Data :</strong></label>
								</div>
							</div>
							<!-- ROW USER DATA -->
							<div class="col-sm-12">
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="fromname" name="fromname" placeholder="" value="[ $FROMNAME ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="fromnameFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="toname" name="toname" placeholder="" value="[ $TONAME ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="tonameFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>

							</div>
							<div class="col-sm-12">
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>CRM Data :</strong></label>
								</div>
							</div>
							<!-- ROW CSR DATA -->
							<div class="col-sm-12">

								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="contract" name="contract" placeholder="" value="[ $CONTRACT ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="contractFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ctdesc" name="ctdesc" placeholder="" value="[ $CTDESC ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="ctdescFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>

								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="project" name="project" placeholder="" value="[ $PROJECT ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="projectFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>

								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="prjdesc" name="prjdesc" placeholder="" value="[ $PRJDESC ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="prjdescFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="customer" name="customer" placeholder="" value="[ $CUSTOMER ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="customerFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="namecust" name="namecust" placeholder="" value="[ $NAMECUST ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="namecustFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="email1cust" name="email1cust" placeholder="" value="[ $EMAIL1CUST ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="email1custFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ponumbercust" name="ponumbercust" placeholder="" value="[ $PONUMBERCUST ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="ponumbercustFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="podatecust" name="podatecust" placeholder="" value="[ $PODATECUST ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="podatecustFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="crmno" name="crmno" placeholder="" value="[ $CRMNO ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="crmnoFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="reqdate" name="reqdate" placeholder="" value="[ $REQDATE ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="reqdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>

								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="orderdesc" name="orderdesc" placeholder="" value="[ $ORDERDESC ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="orderdescFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="remarks" name="remarks" placeholder="" value="[ $REMARKS ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="remarksFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>

								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="salescode" name="salescode" placeholder="" value="[ $SALESCODE ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="salescodeFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="salesperson" name="salesperson" placeholder="" value="[ $SALESPERSON ]" readonly />

											<span class="input-group-btn">
												<div class="tooltip">
													<span class="tooltiptext" id="myTooltip"></span>
												</div>
												<a href="#" onclick="salespersonFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
													<i class=" fa fa-copy"></i>
												</a>
											</span>
										</div>
									</div>
								</div>

								<!-- END ROW CSR DATA -->
							</div>

							<?php if ($groupuser >= 3) : ?>
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>REQUISITION Data :</strong></label>
									</div>
								</div>
								<!-- ROW REQUISITION DATA -->
								<div class="col-sm-12">
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="rqndate" name="rqndate" placeholder="" value="[ $RQNDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="rqndateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="rqnnumber" name="rqnnumber" placeholder="" value="[ $RQNNUMBER ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="rqnnumberFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>

								</div>
							<?php endif; ?>

							<?php if ($groupuser >= 4) : ?>
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>PROCUREMENT Data :</strong></label>
									</div>
								</div>
								<!-- ROW PROCUREMENT DATA -->
								<div class="col-sm-12">
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="podate" name="podate" placeholder="" value="[ $PODATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="podateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="ponumber" name="ponumber" placeholder="" value="[ $PONUMBER ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="ponumberFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="etddate" name="etddate" placeholder="" value="[ $ETDDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="etddateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="cargoreadinessdate" name="cargoreadinessdate" placeholder="" value="[ $CARGOREADINESSDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="cargoreadinessdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="origincountry" name="origincountry" placeholder="" value="[ $ORIGINCOUNTRY ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="origincountryFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="poremarks" name="poremarks" placeholder="" value="[ $POREMARKS ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="poremarksFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>

								</div>
							<?php endif; ?>

							<?php if ($groupuser >= 5) : ?>
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>LOGISTICS Data :</strong></label>
									</div>
								</div>
								<!-- ROW LOGISTICS DATA -->
								<div class="col-sm-12">
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="etdorigindate" name="etdorigindate" placeholder="" value="[ $ETDORIGINDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="etdorigindateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="atdorigindate" name="atdorigindate" placeholder="" value="[ $ATDORIGINDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="atdorigindateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="etaportdate" name="etaportdate" placeholder="" value="[ $ETAPORTDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="etaportdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="pibdate" name="pibdate" placeholder="" value="[ $PIBDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="pibdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="vendshistatus" name="vendshistatus" placeholder="" value="[ $VENDSHISTATUS ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="vendshistatusFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>

								</div>
							<?php endif; ?>

							<?php if ($groupuser == 6) : ?>
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>INVENTORY Data :</strong></label>
									</div>
								</div>
								<!-- ROW LOGISTICS DATA -->
								<div class="col-sm-12">
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="recpnumber" name="recpnumber" placeholder="" value="[ $RECPNUMBER ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="recpnumberFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="recpdate" name="recpdate" placeholder="" value="[ $RECPDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="recpdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="vdname" name="vdname" placeholder="" value="[ $VDNAME ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="vdnameFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="descriptio" name="descriptio" placeholder="" value="[ $DESCRIPTIO ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="descriptioFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>

								</div>
							<?php endif; ?>

							<?php if ($groupuser >= 7) : ?>
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>DELIVERY Data :</strong></label>
									</div>
								</div>
								<!-- ROW LOGISTICS DATA -->
								<div class="col-sm-12">
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="docnumber" name="docnumber" placeholder="" value="[ $DOCNUMBER ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="docnumberFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="shinumber" name="shinumber" placeholder="" value="[ $SHINUMBER ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="shinumberFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="shidate" name="shidate" placeholder="" value="[ $SHIDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="shidateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="custrcpdate" name="custrcpdate" placeholder="" value="[ $CUSTRCPDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="custrcpdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>

									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="origdnrcpshidate" name="origdnrcpshidate" placeholder="" value="[ $ORIGDNRCPSHIDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="origdnrcpshidateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>

								</div>
							<?php endif; ?>

							<?php if ($groupuser >= 8) : ?>
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>SALES ADMIN Data :</strong></label>
									</div>
								</div>
								<!-- ROW LOGISTICS DATA -->
								<div class="col-sm-12">
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="dnstatus" name="dnstatus" placeholder="" value="[ $DNSTATUS ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="dnstatusFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" id="origdnrcpslsdate" name="origdnrcpslsdate" placeholder="" value="[ $ORIGDNRCPSLSDATE ]" readonly />

												<span class="input-group-btn">
													<div class="tooltip">
														<span class="tooltiptext" id="myTooltip"></span>
													</div>
													<a href="#" onclick="origdnrcpslsdateFunction()" onmouseout="outFunc()" class="btn btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
														<i class=" fa fa-copy"></i>
													</a>
												</span>
											</div>
										</div>
									</div>
								<?php endif; ?>
						</form>


					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<script>
	// User Data
	function fromnameFunction() {
		// Get the text field
		var copyText = document.getElementById("fromname");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function tonameFunction() {
		// Get the text field
		var copyText = document.getElementById("toname");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	// CSR DATA
	function contractFunction() {
		// Get the text field
		var copyText = document.getElementById("contract");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function ctdescFunction() {
		// Get the text field
		var copyText = document.getElementById("ctdesc");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function projectFunction() {
		// Get the text field
		var copyText = document.getElementById("project");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function prjdescFunction() {
		// Get the text field
		var copyText = document.getElementById("prjdesc");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function customerFunction() {
		// Get the text field
		var copyText = document.getElementById("customer");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function namecustFunction() {
		// Get the text field
		var copyText = document.getElementById("namecust");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function email1custFunction() {
		// Get the text field
		var copyText = document.getElementById("email1cust");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function ponumbercustFunction() {
		// Get the text field
		var copyText = document.getElementById("ponumbercust");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function podatecustFunction() {
		// Get the text field
		var copyText = document.getElementById("podatecust");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function crmnoFunction() {
		// Get the text field
		var copyText = document.getElementById("crmno");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function reqdateFunction() {
		// Get the text field
		var copyText = document.getElementById("reqdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function orderdescFunction() {
		// Get the text field
		var copyText = document.getElementById("orderdesc");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function remarksFunction() {
		// Get the text field
		var copyText = document.getElementById("remarks");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function salescodeFunction() {
		// Get the text field
		var copyText = document.getElementById("salescode");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function salespersonFunction() {
		// Get the text field
		var copyText = document.getElementById("salesperson");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	//REQUISITION DATA
	function rqndateFunction() {
		// Get the text field
		var copyText = document.getElementById("rqndate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function rqnnumberFunction() {
		// Get the text field
		var copyText = document.getElementById("rqnnumber");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	//PROCUREMENT DATA
	function podateFunction() {
		// Get the text field
		var copyText = document.getElementById("podate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function ponumberFunction() {
		// Get the text field
		var copyText = document.getElementById("ponumber");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function etddateFunction() {
		// Get the text field
		var copyText = document.getElementById("etddate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function cargoreadinessdateFunction() {
		// Get the text field
		var copyText = document.getElementById("cargoreadinessdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function origincountryFunction() {
		// Get the text field
		var copyText = document.getElementById("origincountry");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function poremarksFunction() {
		// Get the text field
		var copyText = document.getElementById("poremarks");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	//LOGISTICS DATA
	function etdorigindateFunction() {
		// Get the text field
		var copyText = document.getElementById("etdorigindate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function atdorigindateFunction() {
		// Get the text field
		var copyText = document.getElementById("atdorigindate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function etaportdateFunction() {
		// Get the text field
		var copyText = document.getElementById("etaportdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function pibdateFunction() {
		// Get the text field
		var copyText = document.getElementById("pibdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function vendshistatusFunction() {
		// Get the text field
		var copyText = document.getElementById("vendshistatus");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	//INVENTORY DATA
	function recpnumberFunction() {
		// Get the text field
		var copyText = document.getElementById("recpnumber");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function recpdateFunction() {
		// Get the text field
		var copyText = document.getElementById("recpdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function vdnameFunction() {
		// Get the text field
		var copyText = document.getElementById("vdname");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function descriptioFunction() {
		// Get the text field
		var copyText = document.getElementById("descriptio");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	//DELIVERY DATA
	function docnumberFunction() {
		// Get the text field
		var copyText = document.getElementById("docnumber");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function shinumberFunction() {
		// Get the text field
		var copyText = document.getElementById("shinumber");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function shidateFunction() {
		// Get the text field
		var copyText = document.getElementById("shidate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function custrcpdateFunction() {
		// Get the text field
		var copyText = document.getElementById("custrcpdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function origdnrcpshidateFunction() {
		// Get the text field
		var copyText = document.getElementById("origdnrcpshidate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	//SALESADMIN DATA
	function dnstatusFunction() {
		// Get the text field
		var copyText = document.getElementById("dnstatus");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function origdnrcpslsdateFunction() {
		// Get the text field
		var copyText = document.getElementById("origdnrcpslsdate");

		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		//alert("Copied the text: " + copyText.value);
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied: " + copyText.value;
	}

	function outFunc() {
		var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copy to clipboard";
	}
</script>