<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>

<div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
    <h4 class='modal-title' id='myModalLabel'> Read Message Notification</h4>
</div>


<div class="box box-primary">
    <div class="box-header with-border">
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <div class="mailbox-read-info">

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3 mailbox-read-time">
                        <span class="mailbox-read-time pull-right">From :</span>
                    </div>
                    <div class="col-md-9 mailbox-read-time">
                        <span><?php echo ucwords(strtolower($from_name)) . ' &lt; ' . $from_email . ' &gt; '; ?></span>
                    </div>
                    <div class="col-md-3 mailbox-read-time">
                        <span class="mailbox-read-time pull-right">To :</span>
                    </div>
                    <div class="col-md-9 mailbox-read-time">
                        <span><?php echo ucwords(strtolower($to_name)) . ' &lt; ' . $to_email . ' &gt; '; ?></span>
                    </div>
                    <div class="col-md-3 mailbox-read-time">
                        <span class="mailbox-read-time pull-right">Date :</span>
                    </div>
                    <div class="col-md-9 mailbox-read-time">
                        <span><?php echo $sending_date . ', ' . $sending_time; ?></span>
                    </div>

                    <div class="col-md-3 mailbox-read-time">
                        <span class="mailbox-read-time pull-right">Subject :</span>
                    </div>
                    <div class="col-md-9 mailbox-read-time">
                        <span><?= $subject; ?></span>
                    </div>
                </div>
            </div>


        </div>

        <!-- /.mailbox-controls -->
        <div class="mailbox-read-message">
            <h3><?= $subject; ?></h3>
            <p><?= $message; ?></p>
        </div>


        <!-- /.mailbox-read-message -->

        <div class="modal-footer">
            <!-- untuk Attachment-->
            <?php if ($is_attached == 1) { ?>
                <ul class="mailbox-attachments clearfix">
                    <li class="pull-left">
                        <span class="mailbox-attachment-icon btn-xs"><i class="fa fa-file-pdf-o"></i></span>

                        <div class="mailbox-attachment-info"><small>
                                <a href="<?= base_url($attachment_filepath); ?>" download class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> <?= $attachment_filename; ?></a>
                                <span class="mailbox-attachment-size">
                                    <?= number_format((filesize($attachment_filepath) / 1000), 0, ",", ".") . ' KB'; ?>
                                    <a href="<?= base_url($attachment_filepath); ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                </span>
                            </small>
                        </div>
                    </li>
                </ul>
            <?php } ?>


            <a href="<?= header("Refresh:0"); ?>"><button type="button" class="btn bg-olive margin"><i class='fa fa-check'></i> Close</button></a>
        </div>
    </div>
</div>