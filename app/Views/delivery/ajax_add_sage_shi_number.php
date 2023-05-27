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
    <h4 class='modal-title' id='myModalLabel'> Select Sage Shipment Number</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="docuniq">Select Shipment Number</label>
                            <select class="form-control input-sm select2 required" id="docuniq" name="docuniq" style="width:100%;">
                                <option option value="">-- Select Shipment Number --</option>
                                <?php foreach ($sage_shi_number as $data) :
                                    $shi_date = substr($data['TRANSDATE'], 4, 2) . '/' . substr($data['TRANSDATE'], 6, 2) . '/' . substr($data['TRANSDATE'], 0, 4);
                                ?>
                                    <option value="<?= trim($data['DOCUNIQ'])
                                                    ?>"><?= trim($data['DOCNUM']) . ' - ' . trim($data['HDRDESC']) . ' - ' . $shi_date
                                                        ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="csr_uniq" name="csr_uniq" value="<?= $csr_uniq ?>">
                        <input type="hidden" id="rcp_uniq" name="rcp_uniq" value="<?= $rcp_uniq ?>">
                        <input type="hidden" id="shi_itemno" name="shi_itemno" value="<?= $shi_itemno ?>">
                        <input type="hidden" id="shi_materialno" name="shi_materialno" value="<?= $shi_materialno ?>">
                        <input type="hidden" id="shi_itemdesc" name="shi_itemdesc" value="<?= $shi_itemdesc ?>">
                        <input type="hidden" id="shi_unit" name="shi_unit" value="<?= $shi_unit ?>">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            <small> Viewed Delivery Orders from Sage { IC Transfer }</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
        </div>
    </div>
</form>