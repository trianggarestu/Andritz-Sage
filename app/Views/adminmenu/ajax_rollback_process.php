<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
<script>
    $(function() {
        $('.select2').select2()
    })
</script>
<div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
    <h4 class='modal-title' id='myModalLabel'> Cancel the process to roll back</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">S/O Process : </label>
                                    <?php if (!empty($csrheader_data['POSTINGSTAT'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['POSTINGSTAT'])) { ?>
                                        <button type="submit" name="rollbackprocess" value="so_process" id="so_process" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">Requisition Process : </label>
                                    <?php if (!empty($csrheader_data['RQNSTAT'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['RQNSTAT'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="rqn_process" id="rqn_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">P/O Process : </label>
                                    <?php if (!empty($csrheader_data['CTPO'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTPO'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="po_process" id="po_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">Logistics Process : </label>
                                    <?php if (!empty($csrheader_data['CTLOG'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTLOG'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="log_process" id="log_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">G/R Process : </label>
                                    <?php if (!empty($csrheader_data['CTGR'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTGR'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="gr_process" id="gr_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">D/N Process : </label>
                                    <?php if (!empty($csrheader_data['CTDN'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTDN'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="dn_process" id="dn_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">Conf. e-D/N Process : </label>
                                    <?php if (!empty($csrheader_data['CTEDN'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTEDN'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="edn_process" id="edn_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">A/R Inv Process : </label>
                                    <?php if (!empty($csrheader_data['CTFIN'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTFIN'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="fin_process" id="fin_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origin_country">RR Process : </label>
                                    <?php if (!empty($csrheader_data['CTRR'])) { ?>
                                        <span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>
                                    <?php }
                                    ?>
                                </div>

                                <div class="col-sm-8">
                                    <?php if (!empty($csrheader_data['CTRR'])) { ?>
                                        <button type="submit" class="btn btn-social btn-flat btn-danger btn-sm" name="rollbackprocess" value="rr_process" id="rr_process"><i class='fa fa-undo'></i> <?= $button ?></button>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>

        </div>
    </div>
</form>