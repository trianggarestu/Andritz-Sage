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
    <h4 class='modal-title' id='myModalLabel'>Fill RR Status by Finance </h4>
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
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="idinvc">Invoice Number : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="idinvc" name="idinvc" placeholder="" value="<?= $idinvc; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="invdate">Invoice Date : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="invdate" name="invdate" placeholder="" value="<?= $invdate; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="tcurcosthm">Current Est. Cost : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="vtcurcosthm" name="vtcurcosthm" placeholder="" value="<?= $vtcurcosthm; ?>" readonly />
                                    <input type="hidden" id="tcurcosthm" name="tcurcosthm" value="<?= $tcurcosthm; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="tactcosthm">Current Act. Cost : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="vtactcosthm" name="vtactcosthm" placeholder="" value="<?= $vtactcosthm; ?>" readonly />
                                    <input type="hidden" id="tactcosthm" name="tactcosthm" value="<?= $tactcosthm; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="finstatus">Status :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" style="width:100%;" class="form-control input-sm required" id="finstatus" name="finstatus" placeholder="" value="<?php

                                                                                                                                                                        switch ($finstatus) {
                                                                                                                                                                            case "1":
                                                                                                                                                                                echo "Partial";
                                                                                                                                                                                break;
                                                                                                                                                                            case "2":
                                                                                                                                                                                echo "Completed";
                                                                                                                                                                                break;
                                                                                                                                                                            default:
                                                                                                                                                                                echo "";
                                                                                                                                                                        }

                                                                                                                                                                        ?>" readonly />

                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="rrstatus">RR Status :</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm select2 required" id="rrstatus" name="rrstatus" style="width:100%;">
                                        <option value="1" <?php if ($rrstatus == 1) {
                                                                echo "selected";
                                                            } ?>>OPEN
                                        </option>
                                        <option value="2" <?php if ($rrstatus == 2) {
                                                                echo "selected";
                                                            } ?>>DONE
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
            <input type="hidden" id="finuniq" name="finuniq" value="<?= $finuniq ?>">
            <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> <?= $button ?></button>
        </div>
    </div>
</form>