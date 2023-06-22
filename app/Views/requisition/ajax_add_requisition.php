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
    <h4 class='modal-title' id='myModalLabel'> Add Requisition Number to Sales Order</h4>
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
                                    <label for="rqnnumber">Choose Requisition :</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm select2 required" id="rqnnumber" name="rqnnumber" style="width:100%;">
                                        <option option value="">Requisition Number-Requisition Date-Description</option>
                                        <?php foreach ($requisition_list as $data) :
                                            $rqn_date = substr($data['DATE'], 6, 2) . "/" . substr($data['DATE'], 4, 2) . "/" . substr($data['DATE'], 0, 4);
                                        ?>
                                            <option value="<?= trim($data['RQNNUMBER'])
                                                            ?>"><?= trim($data['RQNNUMBER'])
                                                                ?> - <?= $rqn_date . " - " . $data['DESCRIPTIO']
                                                                        ?>
                                            </option>
                                        <?php endforeach;
                                        ?>
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
            <input type="hidden" id="csrluniq" name="csrluniq" value="<?= $csrluniq ?>">
            <input type="hidden" id="postingstat" name="postingstat" value="<?= $post_stat ?>">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
        </div>
    </div>
</form>