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
									<div class="btn-group">
										<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
										<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
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
													<td class="mailbox-star">
														<?php if ($mailbox_active != 'Trash') { ?>
															<a href="<?php if ($data['IS_STAR'] == 0) {
																			echo base_url("mailbox/mark_star/" . $data['MAILSEQ']);
																		} else {
																			echo base_url("mailbox/mark_unstar/" . $data['MAILSEQ']);
																		} ?>"><?php if ($data['IS_STAR'] == 0) { ?><i class="fa fa-star-o text-yellow"></i><?php } else { ?><i class="fa fa-star text-yellow"></i><?php } ?>
															</a>
														<?php } ?>
													</td>
													<td class="mailbox-name <?php if ($data['IS_READ'] == 0) {
																				echo 'unread-b';
																			}; ?>">

														<?= ucwords(strtolower($data['FROM_NAME'])); ?>


													</td>
													<td class="mailbox-name <?php if ($data['IS_READ'] == 0) {
																				echo 'unread-b';
																			}; ?>">
														<?= $data['FROM_EMAIL'] ?>
													</td>
													<td class="mailbox-subject <?php if ($data['IS_READ'] == 0) {
																					echo 'unread-b';
																				}; ?>">
														<a href="<?= base_url(); ?>mailbox/view_messages/<?= $data['MAILSEQ'] ?>" data-remote="false" data-toggle="modal" data-tittle="View Messages" data-target="#modalBox">
															<?= $data['SUBJECT'] ?> ...
														</a>
													</td>
													<td class="mailbox-attachment"><?php if ($data['IS_ATTACHED'] == 1) { ?><i class="fa fa-paperclip"></i><?php } ?></td>
													<td class="mailbox-date <?php if ($data['IS_READ'] == 0) {
																				echo 'unread-b';
																			}; ?>"><?php
																					$sending_date = substr($data['SENDING_DATE'], 6, 2) . "/" . substr($data['SENDING_DATE'], 4, 2) . "/" . substr($data['SENDING_DATE'], 0, 4);
																					echo $sending_date;
																					?></td>
													<td>
														<div class="btn-group">

															<?php if ($mailbox_active == 'Trash') {
																echo '';
															} else {
																if ($data['IS_READ'] == 0) { ?>
																	<a href="<?= base_url("mailbox/mark_read/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Mark as read">
																		<i class="fa fa-envelope-open-o"></i>
																	</a>
																<?php } else { ?>
																	<a href="<?= base_url("mailbox/mark_unread/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Mark as unread">
																		<i class="fa fa-envelope-o"></i>
																	</a> <?php }
																	} ?>

															<?php
															if ($mailbox_active == 'Trash') {
																echo '';
															} else {
																if ($data['IS_ARCHIVED'] == 0) { ?>
																	<a href="<?= base_url("mailbox/mark_archive/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Archive"><i class="fa fa-archive"></i></a>
																<?php } else { ?>
																	<a href="<?= base_url("mailbox/mark_atoinbox/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Move to inbox"><i class="fa fa-inbox"></i></a>
															<?php }
															}
															?>

															<?php
															if ($data['IS_TRASHED'] == 0) { ?>
																<a href="<?= base_url("mailbox/mark_trash/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Move to trash"><i class="fa fa-trash-o"></i></a>
															<?php } else { ?>
																<a href="<?= base_url("mailbox/mark_ttoinbox/" . $data['MAILSEQ']) ?>" class="btn btn-default btn-sm" title="Move to inbox"><i class="fa fa-inbox"></i></a>
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
									<div class="btn-group">
										<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
										<button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
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