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
                        <div class="table-responsive">
                            <table id="tabel2" class="table table-bordered dataTable table-hover nowrap">
                                <thead class="bg-info disabled color-palette">
                                    <tr>
                                        <th nowrap class="col-md-2">Project</th>
                                        <th>Data</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: right;" nowrap>Contract No. :</td>
                                        <td><?= $ct_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" nowrap>Project No. :</td>
                                        <td><?= $prj_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" nowrap>CRM No. :</td>
                                        <td><?= $crm_no; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;vertical-align: text-top;" nowrap>Customer :</td>
                                        <td><?= $cust_no . '-' . $cust_name; ?><br>
                                            <small>
                                                &lt;&nbsp;<?= $cust_email; ?>&nbsp;&gt;
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; vertical-align: text-top;" nowrap>Po Customer :</td>
                                        <td><?= $cust_po ?><br>
                                            PO Date :<?= $po_date ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" nowrap>Req. Date :</td>
                                        <td><?= $req_date ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" nowrap>Sales Person :</td>
                                        <td><?= $salesperson ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; vertical-align: text-top;" nowrap>Item :</td>
                                        <td>
                                            Inventory No. : <?= $inventory_no ?><br>
                                            Material No. : <?= $material_no ?><br>
                                            Description : <?= $inventory_desc ?> <br>
                                            Qty : <?= $qty ?><br>
                                            Uom : <?= $uom ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;" nowrap>Remarks :</td>
                                        <td><?= $order_desc ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: right;vertical-align: text-top;" nowrap>Requisition No. :</td>
                                        <td>
                                            <select class="form-control input-sm select2 required" id="rqnnumber" name="rqnnumber" style="width:100%;">
                                                <option option value="">__________ Requisition Number - Requisition Date - Description __________</option>
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
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
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