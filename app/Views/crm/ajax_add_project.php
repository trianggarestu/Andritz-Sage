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
    <h4 class='modal-title' id='myModalLabel'> Select Project by Contract </h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="contract">Select Project</label>
                            <select class="form-control input-sm select2 required" id="project" name="project" style="width:100%;">
                                <option option value="">-- Select Project--</option>
                                <?php foreach ($projectbycontract as $data) :
                                ?>
                                    <option value="<?= trim($data['PROJECT'])
                                                    ?>">(<?= trim($data['PROJECT'])
                                                            ?>) - <?= $data['Prj_Desc']
                                                                    ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>

                            <?php //echo form_dropdown('contract', $contractopen, isset($contract) ? $contract : '', 'class="form-control input-sm select2 required"', 'style="width:100%;'); 
                            ?>

                        </div>
                        <!--<div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered dataTable table-striped table-hover">
                                        <thead class="bg-gray disabled color-palette">
                                            <tr>
                                                <th>Contract</th>
                                                <th>Description</th>
                                                <th>Customer</th>
                                                <th>Select</th>
                                            </tr>
                                            <?php //foreach ($contractopen as $data) :
                                            ?>
                                                <tr>
                                                    <td><? //= $data['CONTRACT'] 
                                                        ?></td>
                                                    <td><? //= $data['DESC'] 
                                                        ?></td>
                                                    <td><? //= $data['NAMECUST'] 
                                                        ?></td>
                                                    <td>
                                                        <form action="<? //= $form_action
                                                                        ?>" method="post" id="validasi">
                                                            <input type="hidden" id="contract" name="contract" value="<? //= $data['CONTRACT'] 
                                                                                                                        ?>">
                                                            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i>&nbsp;</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php //endforeach;
                                            ?>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                                            -->
                        <input type="hidden" id="contract" name="contract" value="<?= $ct_no ?>">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            only viewed Project in contract <strong><?= $ct_no; ?></strong> with open status
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