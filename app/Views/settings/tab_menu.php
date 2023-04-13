<div class="content-wrapper">
    <section class="content-header">
        <h1>E-mail Sender Setup</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="">E-mail Settings</li>
            <li class="active">E-mail Sender Setup</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li <?php if ($act_tab == 1) : ?>class="active" <?php endif ?>><a href="<?= base_url('mailsendersetup') ?>">E-Mail Sender Setup</a></li>
                                        <li <?php if ($act_tab == 2) : ?>class="active" <?php endif ?>><a href="<?= base_url('mailsendersetup/notifsetup') ?>">Enabled / Disabled E-mail Notification</a></li>

                                    </ul>
                                    <div class="tab-content">