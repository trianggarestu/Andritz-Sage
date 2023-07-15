<div class="tab-pane <?php if ($act_tab == 1) : ?>active<?php endif ?>" id="tab_1">

    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <?php if (is_array($sage_shi_number_tf)) : ?>
                            <div class="form-group">
                                <label for="docuniq">Select Shipment Number</label>
                                <select class="form-control input-sm select2" id="tfdocuniq" name="tfdocuniq" style="width:100%;">
                                    <option option value="">-- Select Shipment Number --</option>
                                    <?php foreach ($sage_shi_number_tf as $data) :
                                        $shi_date = substr($data['TRANSDATE'], 4, 2) . '/' . substr($data['TRANSDATE'], 6, 2) . '/' . substr($data['TRANSDATE'], 0, 4);
                                    ?>
                                        <option value="<?= trim($data['DOCNUM'])
                                                        ?>"><?= trim($data['DOCNUM']) . ' - ' . trim($data['HDRDESC']) . ' - ' . $shi_date
                                                            ?>
                                        </option>
                                    <?php endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tf_dn_number">D/N Number : </label>
                                <input type="text" class="form-control input-sm" id="tf_dn_number" name="tf_dn_number" placeholder="" value="" />

                            </div>


                            <div class="form-group">

                                <label for="received_date">Received Date : </label>

                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="datepicker form-control input-sm pull-right" id="tf_received_date" name="tf_received_date" type="text" value="" readonly />
                                </div>

                            </div>
                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <small> Viewed Delivery Orders from Sage { IC Transfer }</small>
                                </p>
                            </div>

                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">

        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm reset" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
    </div>
</div>


<div class="tab-pane" id="tab_2">

    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <?php if (is_array($sage_shi_number_pm)) : ?>
                            <div class="form-group">
                                <label for="docuniq">Select Material Usage</label>
                                <select class="form-control input-sm select2" id="pjcdocuniq" name="pjcdocuniq" style="width:100%;">
                                    <option option value="">-- Select Material Usage Number --</option>
                                    <?php foreach ($sage_shi_number_pm as $data2) :
                                        $shi_date = substr($data2['TRANSDATE'], 4, 2) . '/' . substr($data2['TRANSDATE'], 6, 2) . '/' . substr($data2['TRANSDATE'], 0, 4);
                                    ?>
                                        <option value="<?= trim($data2['MATERIALNO'])
                                                        ?>"><?= trim($data2['MATERIALNO']) . ' - ' . trim($data2['HDRDESC']) . ' - ' . $shi_date
                                                            ?>
                                        </option>
                                    <?php endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pjc_dn_number">D/N Number : </label>
                                <input type="text" class="form-control input-sm" id="pjc_dn_number" name="pjc_dn_number" placeholder="" value="" />

                            </div>


                            <div class="form-group">

                                <label for="pjc_received_date">Received Date : </label>

                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="datepicker form-control input-sm pull-right" id="pjc_received_date" name="pjc_received_date" type="text" value="" readonly />
                                </div>

                            </div>

                            <div class="col-sm-12">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <small> Viewed Delivery Orders from Sage { PJC Material Usage }</small>
                                </p>
                            </div>
                        <?php endif ?>


                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="csruniq" name="csr_uniq" value="<?= $csr_uniq ?>">
            <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm reset" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
        </div>
    </div>

</div>


<!-- Closing Tab -->
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- bootstrap Date picker -->
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>
<!-- Script-->
<script src="<?= base_url() ?>assets/js/script.js"></script>

<script>
    $(".reset").click(function() {
        document.location.reload(true);
    });



    /*$(document).ready(function() {
        $('#tfdocuniq').change(function() {
            $('$tf_dn_number').addClass('required');

        });

    });*/
</script>