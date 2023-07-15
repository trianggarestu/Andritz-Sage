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
    <h4 class='modal-title' id='myModalLabel'> Select Sage Shipment Number</h4>
</div>
<section class="content" id="maincontent">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li <?php if ($act_tab == 1) : ?>class="active" <?php endif ?>><a href="#tab_1" data-toggle="tab">Shipment Process from I/C Transfer</a></li>
                                    <li <?php if ($act_tab2 == 2) : ?>class="active" <?php endif ?>><a href="#tab_2" data-toggle="tab">Shipment Process from Material Usage</a></li>

                                </ul>
                                <form action="<?= $form_action;
                                                ?>" method="post" id="validasi">
                                    <div class="tab-content">