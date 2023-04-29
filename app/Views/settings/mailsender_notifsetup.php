										<div class="tab-pane <?php if ($act_tab == 2) : ?> active<?php endif ?>">
											<div class="row">
												<form id="validasi" action="<?= $form_action ?>" method="POST" class="form-horizontal">
													<div class="col-md-12">
														<div class="box-header with-border">
															<h3 class="box-title"><strong>E-Mail Notification Setup</strong></h3>
														</div>
														<div class="box-body">
															<div class="row">

																<div class="col-sm-12">
																	<form class="form-horizontal">
																		<table class="table table-bordered">
																			<tbody>
																				<tr>
																					<td style="padding-top:20px;padding-bottom:10px;">
																						<div class="form-group">
																							<label for="file" class="col-md-4 col-lg-3 control-label">Enabled / Disabled E-mail Notification</label>
																							<div class="btn-group col-xs-12 col-sm-7" data-toggle="buttons">
																								<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php ($mailsender_data['OFFLINESTAT'] == '0') and print('active'); ?>">
																									<input id="g1" type="radio" name="offlinestat" class="form-check-input" type="radio" value="0" <?php ($mailsender_data['OFFLINESTAT'] == '0') and print('checked'); ?> autocomplete="off"> Enabled
																								</label>
																								<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php ($mailsender_data['OFFLINESTAT'] == '1') and print('active'); ?>">
																									<input id="g2" type="radio" name="offlinestat" class="form-check-input" type="radio" value="1" <?php ($mailsender_data['OFFLINESTAT'] == '1'); ?> autocomplete="off"> Disabled
																								</label>
																							</div>
																						</div>

																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</form>

																	<div class="row">
																		<ul>
																			<li> Click Enabled to enabled E-mail Notification. </li>
																			<li> Click Disabled to disabled E-mail Notification. </li>
																		</ul>
																	</div>
																	<p class="text-muted text-blue well well-sm no-shadow" style="margin-top: 10px;">
																		<small><strong><i class="fa fa-info-circle text-red"></i> This process will skip or run the email sender function.</strong></small>
																	</p>
																</div>

															</div>
														</div>
														<div class='box-footer'>
															<div class='col-xs-12'>
																<input type="hidden" id="id" name="id" value="<?= $mailsender_data['ID'] ?>">
																<button type='submit' class='btn btn-block btn-success btn-sm'><i class='fa fa-check'></i> Save</button>

															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</section>
										</div>