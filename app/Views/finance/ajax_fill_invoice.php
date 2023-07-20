<!-- Bootstrap Date time Picker -->
<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-datetimepicker.min.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-datepicker.min.css">

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
    <h4 class='modal-title' id='myModalLabel'> Fill A/R Invoice by Finance </h4>
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
                                    <label for="customer">Customer :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="customer" name="customer" placeholder="" value="<?= $customer . '-' . $cust_name; ?>" readonly />

                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="idinvc">Fill A/R Invoice :</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm select2 required" id="idinvc" name="idinvc" style="width:100%;">
                                        <option option value="">___ Invoice Number - Inv. Date - Description ___</option>
                                        <?php foreach ($arinvoice_list as $data) :
                                            $inv_date = substr($data['DATEINVC'], 4, 2) . "/" . substr($data['DATEINVC'], 6, 2) . "/" . substr($data['DATEINVC'], 0, 4);
                                        ?>
                                            <option value="<?= trim($data['IDINVC'])
                                                            ?>" data-idinvc="<?= $data['IDINVC']; ?>" data-dateinvc="<?= $inv_date; ?>" data-invcdesc="<?= $data['INVCDESC']; ?>" data-dppamt="<?= number_format($data['AMTINVCTOT'], 0, ",", "."); ?>"><?= trim($data['IDINVC'])
                                                                                                                                                                                                                                                        ?> - <?= $inv_date . " - " . $data['INVCDESC']
                                                                                                                                                                                                                                                                ?>
                                            </option>

                                        <?php endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="ar_inv">Inv. Number :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="ar_inv" name="ar_inv" value="" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="dateinvc">Inv. Date :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="dateinvc" name="dateinvc" value="" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="invcdesc">Desc. :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="invcdesc" name="invcdesc" value="" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="dppamt">DPP Amount :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="dppamt" name="dppamt" value="" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="rcporigdn_date">Rcp. Orig D/N : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control input-sm pull-right datepicker required" id="rcporigdn_date" name="rcporigdn_date" type="text" value="" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="finstatus">Status :</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm select2 required" id="finstatus" name="finstatus" style="width:100%;">
                                        <option option value="">-- Select Status --
                                        </option>

                                        <option value="1">Partial
                                        </option>
                                        <option value="2">Completed
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable table-hover nowrap">
                                    <thead class="bg-gray disabled color-palette">
                                        <tr>

                                            <th class="padat">No</th>

                                            <th style="text-align: center;"><input type="checkbox" id="checkall" /></th>
                                            <th>Shi. Doc. Number</th>
                                            <th>Shi. Number</th>
                                            <th>Shi. Date</th>
                                            <th>Cust. Rcp. Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        foreach ($shi_by_csr_list as $shi_list) :
                                            $shidate = substr($shi_list['SHIDATE'], 4, 2) . "/" . substr($shi_list['SHIDATE'], 6, 2) . "/" . substr($shi_list['SHIDATE'], 0, 4);
                                            $custrcpdate = substr($shi_list['CUSTRCPDATE'], 4, 2) . "/" . substr($shi_list['CUSTRCPDATE'], 6, 2) . "/" . substr($shi_list['CUSTRCPDATE'], 0, 4);
                                        ?>
                                            <tr>

                                                <td class="text-center"><?= ++$no ?></td>
                                                <td style="text-align: center;">
                                                    <input type="hidden" name="shichecked[<?php echo $shi_list['SHIUNIQ']; ?>]" value="0" />
                                                    <input type="checkbox" name="shichecked[<?php echo $shi_list['SHIUNIQ']; ?>]" value="1" class="required" checked />
                                                </td>

                                                <td nowrap><?= $shi_list['DOCNUMBER']
                                                            ?></td>
                                                <td nowrap><?= $shi_list['SHINUMBER']
                                                            ?></td>

                                                <td nowrap><?= $shidate
                                                            ?></td>
                                                <td nowrap><?= $custrcpdate
                                                            ?></td>

                                            </tr>
                                            <input type="hidden" name="SHIUNIQ[<?php echo $shi_list['SHIUNIQ']; ?>]" value="<?php echo $shi_list['SHIUNIQ']; ?>" />
                                            <input type="hidden" name="SHIDOCNUMBER[<?php echo $shi_list['SHIUNIQ']; ?>]" value="<?= $shi_list['DOCNUMBER']; ?>" />
                                        <?php endforeach;
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">

            <input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
            <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm post-terpilih" id="ok"><i class='fa fa-check'></i> <?= $button ?></button>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
        $('#idinvc').change(function() {
            $('#ar_inv').val($(this).find('option:selected').data('idinvc'));
            $('#dateinvc').val($(this).find('option:selected').data('dateinvc'));
            $('#invcdesc').val($(this).find('option:selected').data('invcdesc'));
            $('#dppamt').val($(this).find('option:selected').data('dppamt'));
        });


    });
</script>

<!-- bootstrap Date picker -->
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>

<!-- Script-->
<script src="<?= base_url() ?>assets/js/script.js"></script>