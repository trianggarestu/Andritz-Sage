<div class="content-wrapper">
	<section class="content-header">
		<h1>Mailbox<small><span id="e_inbox"></span> new messages</small></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Mailbox</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<?php //$this->load->view('mailbox/menu_mailbox') 
				?>
				<div class="col-md-3">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Menu</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<ul class="nav nav-pills nav-stacked">
								<li class="<?php if ($mailbox_active == 'Inbox (unread)') { ?>active<?php } ?>">
									<a href="<?= base_url() ?>mailbox/unread/"><i class="fa fa-inbox"></i>Inbox (Unread)<span class="label label-danger pull-right" id="d_inbox"></span></a>
								</li>
								<li class="<?php if ($mailbox_active == 'Inbox') { ?>active<?php } ?>">
									<a href="<?= base_url() ?>mailbox/inbox/"><i class="fa fa-inbox"></i>Inbox</a>
								</li>
								<li class="<?php if ($mailbox_active == 'Star') { ?>active<?php } ?>">
									<a href="<?= base_url() ?>mailbox/star/"><i class="fa fa-star text-yellow"></i>Star</a>
								</li>
								<li class="<?php if ($mailbox_active == 'Archive') { ?>active<?php } ?>">
									<a href="<?= base_url() ?>mailbox/archive/"><i class="fa fa-archive"></i>Archive</a>
								</li>
								<li class="<?php if ($mailbox_active == 'Sent') { ?>active<?php } ?>">
									<a href="<?= base_url() ?>mailbox/sent/"><i class="fa fa-paper-plane"></i>Sent</a>
								</li>
								<li class="<?php if ($mailbox_active == 'Trash') { ?>active<?php } ?>">
									<a href="<?= base_url() ?>mailbox/trash/"><i class="fa fa-trash-o"></i>Trash</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><?= $mailbox_active ?></h3>

							<div class="box-tools pull-right">
								<div class="has-feedback">
									<input type="text" class="form-control input-sm" placeholder="Search Mail">
									<span class="glyphicon glyphicon-search form-control-feedback"></span>
								</div>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body no-padding">
							<div class="mailbox-controls">
								<!-- Check all button -->
								<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
								</button>
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm"><i class="fa fa-archive"></i></button>
									<button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
								</div>
								<!-- /.btn-group -->
								<button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
								<div class="pull-right">
									<!--1-50/200-->
									<?php
									if ($ct_messages > 0) {

										if (($perpage * $currentpage) > $ct_messages) {
											echo (($perpage * ($currentpage - 1) + 1)) . '-' . $ct_messages . ' of ' . $ct_messages;
										} else {
											echo (($perpage * ($currentpage - 1) + 1)) . '-' . ($perpage * $currentpage) . ' of ' . $ct_messages;
										}
									}
									//$currentPage . ' of ' . $totalPages . ' pages '  //$ct_messages 
									?>
									<div class="btn-group">
										<?= $pager->links('mailbox_list', 'bootstrap_mailbox');
										?>
									</div>
									<!-- /.btn-group -->
								</div>
								<!-- /.pull-right -->
							</div>
							<div class="table-responsive mailbox-messages">
								<table class="table table-hover table-striped">
									<tbody>
										<?php
										if ($ct_messages == 0) { ?>
											<tr>
												<td colspan="8">
													"There are no messages found in this folder."
												</td>
											</tr>
											<?php } else {
											$no = 1;
											foreach ($mailbox_list as $data) :
											?>

												<tr>
													<td><input type="checkbox"></td>

													<td class="mailbox-name <?php if ($data['IS_READSENDER'] == 0) {
																				echo 'unread-b';
																			}; ?>">

														<?= ucwords(strtolower($data['FROM_NAME'])); ?>


													</td>
													<td class="mailbox-name <?php if ($data['IS_READSENDER'] == 0) {
																				echo 'unread-b';
																			}; ?>">
														<?= 'To : ' . $data['TO_EMAIL'] ?>
													</td>
													<td class="mailbox-subject <?php if ($data['IS_READSENDER'] == 0) {
																					echo 'unread-b';
																				}; ?>">
														<a href="<?= base_url(); ?>mailbox/view_messages/<?= $data['MAILSEQ'] ?>" data-remote="false" data-toggle="modal" data-tittle="View Messages" data-target="#modalBox">
															<?= substr(trim($data['SUBJECT']), 0, 70) . '...'; ?>
														</a>
													</td>
													<td class="mailbox-attachment"><?php if ($data['IS_ATTACHED'] == 1) { ?><i class="fa fa-paperclip"></i><?php } ?></td>
													<td class="mailbox-date <?php if ($data['IS_READSENDER'] == 0) {
																				echo 'unread-b';
																			}; ?>"><?php
																					$todaydate = date("M d Y");
																					$todayyear = date("Y");
																					$sending_date = substr($data['SENDING_DATE'], 4, 2) . "/" . substr($data['SENDING_DATE'], 6, 2) . "/" . substr($data['SENDING_DATE'], 0, 4);
																					$sending_date = date('M d Y', strtotime($sending_date));
																					$sending_date_y = date('Y', strtotime($sending_date));
																					$sending_date_thisyear = date('M d', strtotime($sending_date));
																					$time = strlen($data['SENDING_TIME']);
																					switch ($time) {
																						case "0":
																							$sending_time = '00:00';
																							break;
																						case "1":
																							$sending_time = '00:00';
																							break;
																						case "2":
																							$sending_time = '00:00';
																							break;
																						case "3":
																							$sending_time = '00:0' . substr($data['SENDING_TIME'], 0, 1);
																							break;
																						case "4":
																							$sending_time = '00:' . substr($data['SENDING_TIME'], 0, 2);
																							break;
																						case "5":
																							$sending_time = '0' . substr($data['SENDING_TIME'], 0, 1) . ':' . substr($data['SENDING_TIME'], 1, 2);
																							break;
																						case "6":
																							$sending_time = substr($data['SENDING_TIME'], 0, 2) . ":" . substr($data['SENDING_TIME'], 2, 2);
																							break;
																						default:
																							$sending_time = '00:00';
																					}

																					if ($todaydate == $sending_date) {
																						echo $sending_time;
																					} else {
																						if ($todayyear == $sending_date_y) {
																							echo $sending_date_thisyear;
																						} else {
																							echo $sending_date;
																						}
																					}
																					?></td>
													<td>
														<div class="btn-group">

															<?php if ($mailbox_active == 'Trash') {
																echo '';
															} else {
																if ($data['IS_READSENDER'] == 0) { ?>
																	<a href="<?= base_url("mailbox/mark_senderread/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Mark as read">
																		<i class="fa fa-envelope-open-o"></i>
																	</a>
																<?php } else { ?>
																	<a href="<?= base_url("mailbox/mark_senderunread/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Mark as unread">
																		<i class="fa fa-envelope-o"></i>
																	</a> <?php }
																	} ?>



															<?php
															if ($data['IS_TRASHED'] == 0) { ?>
																<a href="<?= base_url("mailbox/mark_sendertrash/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Move to trash"><i class="fa fa-trash-o"></i></a>
															<?php } else { ?>
																<a href="<?= base_url("mailbox/mark_ttosent/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Move to sent"><i class="fa fa-inbox"></i></a>
															<?php }
															?>
														</div>
													</td>

												</tr>

										<?php endforeach;
										} ?>
									</tbody>
								</table>
								<!-- /.table -->
							</div>
							<!-- /.mail-box-messages -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer no-padding">
							<div class="mailbox-controls">
								<!-- Check all button -->
								<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
								</button>
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
									<button type="button" class="btn btn-default btn-sm"><i class="fa fa-archive"></i></button>
								</div>
								<!-- /.btn-group -->
								<button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
								<div class="pull-right">
									<!--1-50/200-->
									<?php
									if ($ct_messages > 0) {

										if (($perpage * $currentpage) > $ct_messages) {
											echo (($perpage * ($currentpage - 1) + 1)) . '-' . $ct_messages . ' of ' . $ct_messages;
										} else {
											echo (($perpage * ($currentpage - 1) + 1)) . '-' . ($perpage * $currentpage) . ' of ' . $ct_messages;
										}
									}
									//$currentPage . ' of ' . $totalPages . ' pages '  //$ct_messages 
									?>
									<div class="btn-group">
										<?= $pager->links('mailbox_list', 'bootstrap_mailbox');
										?>
									</div>
									<!-- /.btn-group -->
								</div>
								<!-- /.pull-right -->
							</div>
						</div>
					</div>
					<!-- /. box -->
				</div>
			</div>
			<div class="row">


		</form>
	</section>
</div>

<script type="text/javascript">
	$('document').ready(function() {

		setTimeout(function() {

			if ($("#d_inbox").length) {
				$("#d_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
				var refreshInbox = setInterval(function() {
					$("#d_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
				}, 10000);
			}
		}, 500);

		notification(notify, notify_msg);
		$('#success-code').val('');
	});

	$('document').ready(function() {

		setTimeout(function() {

			if ($("#e_inbox").length) {
				$("#e_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
				var refreshInbox = setInterval(function() {
					$("#e_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
				}, 10000);
			}
		}, 500);

		notification(notify, notify_msg);
		$('#success-code').val('');
	});

	/*$(document).ready(function() {
		$('table tr').click(function() {
			window.location = $(this).attr('href');
			return false;
		});
	});*/
</script>