										<div class="tab-pane <?php if ($act_tab == 1) : ?>active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>E-mail Sender </strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-md-8">

																<form id="validasi" action="<?= $form_action ?>" method="POST" class="form-horizontal">
																	<table class="table table-striped">
																		<tr>
																			<td class="col-sm-6 text-right">Server Hostname : </td>
																			<td class="col-sm-6">
																				<div class="input-group">
																					<span class="input-group-addon input-sm "><i class="fa fa-globe"></i></span>
																					<input id="sender_hostname" name="sender_hostname" class="form-control input-sm required" type="text" placeholder="Navigation Name" value="<?= ($mailsender_data['HOSTNAME']); ?>" maxlength="50"></input>
																				</div>
																			</td>
																		</tr>
																		<tr>
																			<td class="col-sm-6 text-right">E-mail Name : </td>
																			<td class="col-sm-6">
																				<div class="input-group">
																					<span class="input-group-addon input-sm "><i class="fa fa-user"></i></span>
																					<input id="sender_name" name="sender_name" class="form-control input-sm required" type="text" placeholder="Navigation Name" value="<?= ($mailsender_data['SENDERNAME'])
																																																						?>" maxlength="50"></input>
																				</div>
																			</td>
																		</tr>
																		<tr>
																			<td class="col-sm-6 text-right">E-mail : </td>
																			<td class="col-sm-6">
																				<div class="input-group">
																					<span class="input-group-addon input-sm ">@</span>
																					<input id="sender_email" name="sender_email" class="form-control input-sm required" type="text" placeholder="Navigation Name" value="<?= ($mailsender_data['SENDEREMAIL'])
																																																							?>" maxlength="50"></input>
																			</td>
																		</tr>
																		<tr>
																			<td class="col-sm-6 text-right"></td>
																			<td class="col-sm-6">
																				<div class="checkbox">
																					<label>
																						<input type="hidden" id="smtpauth" name="smtpauth" value="0" />
																						<input type="checkbox" id="smtpauth" name="smtpauth" value="1" <?php if ($mailsender_data['SMTPAUTH'] == 1) {
																																							echo 'checked';
																																						} else if ($mailsender_data['SMTPAUTH'] == 0) {
																																							echo '';
																																						} ?> /> Authentication Required
																					</label>
																				</div>

																			</td>
																		</tr>

																		<tr>
																			<td class="col-sm-6 text-right">Password : </td>
																			<td class="col-sm-6">
																				<div class="input-group">
																					<span class="input-group-addon input-sm "><i class="fa fa-lock"></i></span>
																					<input id="sender_password" name="sender_password" class="form-control input-sm" type="text" placeholder="Password Email Sender" value="<?= ($mailsender_data['PASSWORDEMAIL']) ?>" maxlength="50"></input>
																					<span class="input-group-addon input-sm "><i class="fa fa-eye"></i></span>
																				</div>

																			</td>
																		</tr>
																		<tr>
																			<td class="col-sm-6 text-right">Encryption Method : </td>
																			<td class="col-sm-6">
																				<div class="input-group">
																					<span class="input-group-addon input-sm "><i class="fa fa-expeditedssl"></i></span>
																					<input id="sender_ssl" name="sender_ssl" class="form-control input-sm" type="text" placeholder="SSL/Non SSL" value="<?= ($mailsender_data['SSL']) ?>" maxlength="10"></input>
																				</div>
																			</td>
																		</tr>

																		<tr>
																			<td class="col-sm-6 text-right">SMTP Port : </td>
																			<td class="col-sm-6">
																				<div class="input-group">
																					<span class="input-group-addon input-sm "><i class="fa fa-expeditedssl"></i></span>
																					<input id="sender_smtpport" name="sender_smtpport" class="form-control input-sm required" type="text" placeholder="SMTP Port" value="<?= ($mailsender_data['SMTPPORT']) ?>" maxlength="4"></input>
																				</div>
																			</td>
																		</tr>
																		<tr>

																			<td colspan="2" class="col-sm-12 text-center"><code>This E-mail will be the master e-mail to sending all notification from this apps.</code>
																			</td>
																		</tr>
																		<tr>

																			<td colspan="2" class="col-sm-12">
																				<?= validation_list_errors() ?>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2" class="col-sm-12 text-right">
																				<input type="hidden" id="id" name="id" value="<?= $mailsender_data['ID'] ?>">
																				<button type='submit' class='btn btn-social btn-flat btn-success btn-sm confirm'><i class='fa fa-check'></i> Update Settings</button>
																			</td>
																		</tr>
																	</table>
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
										</div>
										</div>
										</div>
										</div>
										</section>
										</div>