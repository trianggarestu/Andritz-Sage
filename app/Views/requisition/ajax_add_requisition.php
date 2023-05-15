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
                                    <label for="ct_no">Contract No. : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="ct_no" name="ct_no" placeholder="" value="<?= $ct_no; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="prj_no">Project No. : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="prj_no" name="prj_no" placeholder="" value="<?= $prj_no; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="crm_no">CRM No. : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="crm_no" name="crm_no" placeholder="" value="<?= $crm_no; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="req_date"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="req_date" name="req_date" placeholder="" value="<?= $req_date; ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="cust_no">Customer :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="cust_no" name="cust_no" placeholder="" value="<?= $cust_no . '-' . $cust_name; ?>" readonly />

                                </div>
                            </div>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="cust_email"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm ">@</span>
                                        <input type="text" class="form-control input-sm required" id="cust_email" name="cust_email" placeholder="" value="&lt;&nbsp;<?= $cust_email; ?>&nbsp;&gt;" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="rqnnumber">Po Customer :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="cust_po" name="cust_po" placeholder="" value="<?= $cust_po ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="po_date"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="po_date" name="po_date" placeholder="" value="<?= $po_date ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="salesperson">Sales Person :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="salesperson" name="salesperson" placeholder="" value="<?= $salesperson ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="inventory_no">Item :</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            Inventory No. :
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="inventory_no" name="inventory_no" placeholder="" value="<?= $inventory_no ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="material_no"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            Material No. :
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="material_no" name="material_no" placeholder="" value="<?= $material_no ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="inventory_desc"></label>
                                </div>
                                <div class="col-sm-9">

                                    <input type="text" class="form-control input-sm required" id="inventory_desc" name="inventory_desc" placeholder="" value="<?= $inventory_desc ?>" readonly />

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="qty"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            Qty. :
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="qty" name="qty" placeholder="" value="<?= number_format($qty, 0, ",", ".") ?> ( <?= $uom ?> )" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                            ?>" <?php if ($rqn_number == $data['RQNNUMBER']) {
                                                                    echo "selected";
                                                                } ?>><?= trim($data['RQNNUMBER'])
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

            <input type="hidden" id="id_so" name="id_so" value="<?= $id_so ?>">
            <input type="hidden" id="id_pr" name="id_pr" value="<?= $id_pr ?>">
            <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Save</button>
        </div>
    </div>
</form>