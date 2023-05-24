<?php

namespace App\Controllers;

use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Logistics_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class ArrangeShipment extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $audtuser;
    private $db_name;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->db_name = \Config\Database::connect();

        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->LogisticsModel = new Logistics_model();
        $this->OrdertrackingModel = new Ordertracking_model();

        //$this->SettingnavheaderModel = new Settingnavheader_model();
        if (empty(session()->get('keylog'))) {
            //Tidak Bisa menggunakan redirect kalau condition session di construct
            //return redirect()->to(base_url('/login'));
            header('Location: ' . base_url());
            exit();
        } else {
            $user = session()->get('username');
            $infouser = $this->LoginModel->datapengguna($user);
            if (session()->get('keylog') == $infouser['passlgn']) {


                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'notif_messages' => $mailbox_unread,
                    'success_code' => session()->get('success'),
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavd = 'arrangeshipment';
                $activenavh = $this->AdministrationModel->get_activenavh($activenavd);
                $this->nav_data = [
                    'active_navd' => $activenavd,
                    'active_navh' => $activenavh,
                    'menu_nav' => $this->AdministrationModel->get_navigation($user),
                    //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
                    //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
                    //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
                ];

                date_default_timezone_set('Asia/Jakarta');
                $today = date("d/m/Y H:i:s");

                $this->audtuser = [
                    'TODAY' => $today,
                    'AUDTDATE' => substr($today, 6, 4) . "" . substr($today, 3, 2) . "" . substr($today, 0, 2),
                    'AUDTTIME' => substr($today, 11, 2) . "" . substr($today, 14, 2) . "" . substr($today, 17, 2),
                    'AUDTUSER' => trim($infouser['usernamelgn']),
                    'AUDTORG' => $this->db_name->database,

                ];
            } else {
                header('Location: ' . base_url());
                exit();
            }
        }
    }


    public function index()
    {
        session()->remove('success');
        session()->set('success', '0');
        $logisticsdata = $this->LogisticsModel->get_po_pending_to_arrangeshipment();

        $data = array(
            'logistics_data' => $logisticsdata,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('logistics/data_po_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($pouniq, $postingstat)
    {
        $get_po = $this->LogisticsModel->get_po_by_id($pouniq);
        $get_log = $this->LogisticsModel->get_log_by_po($pouniq);

        if ($get_po) {
            if (!empty($get_log['LOGUNIQ'])) {
                if ($postingstat == 0) {
                    $button_text = 'Save';
                } else {
                    $button_text = 'Save & Posting';
                }
                if ($get_log['POSTINGSTAT'] == 1) {
                    $posting_status = $get_log['POSTINGSTAT'];
                } else {
                    $posting_status = $postingstat;
                }
                $act = 'arrangeshipment/update_action';
                $button = $button_text;
                $posting_stat_data = $get_log['POSTINGSTAT'];

                $id_log = $get_log['LOGUNIQ'];
                $vendorshistatus = trim($get_log['VENDSHISTATUS']);
                $etdorigindate = substr($get_log['ETDORIGINDATE'], 4, 2) . "/" . substr($get_log['ETDORIGINDATE'], 6, 2) . "/" . substr($get_log['ETDORIGINDATE'], 0, 4);
                if ($get_log['ATDORIGINDATE'] == null) {
                    $atdorigindate = '';
                } else {
                    $atdorigindate = substr($get_log['ATDORIGINDATE'], 4, 2) . "/" . substr($get_log['ATDORIGINDATE'], 6, 2) . "/" . substr($get_log['ATDORIGINDATE'], 0, 4);
                }
                if ($get_log['ETAPORTDATE'] == null) {
                    $etaportdate = '';
                } else {
                    $etaportdate = substr($get_log['ETAPORTDATE'], 4, 2) . "/" . substr($get_log['ETAPORTDATE'], 6, 2) . "/" . substr($get_log['ETAPORTDATE'], 0, 4);
                }
                if ($get_log['PIBDATE'] == null) {
                    $pibdate = '';
                } else {
                    $pibdate = substr($get_log['PIBDATE'], 4, 2) . "/" . substr($get_log['PIBDATE'], 6, 2) . "/" . substr($get_log['PIBDATE'], 0, 4);
                }
            } else {
                if ($postingstat == 0) {
                    $button_text = 'Save';
                } else {
                    $button_text = 'Save & Posting';
                }

                $act = 'arrangeshipment/insert_action';
                $button = $button_text;
                $posting_status = $postingstat;
                $id_log = '';
                $vendorshistatus = '';
                $etdorigindate = '';
                $atdorigindate = '';
                $etaportdate = '';
                $pibdate = '';
                $posting_stat_data = 0;
            }
            $po_date = substr($get_po['PODATE'], 4, 2) . "/" . substr($get_po['PODATE'], 6, 2) . "/" . substr($get_po['PODATE'], 0, 4);
            $etd_date = substr($get_po['ETDDATE'], 4, 2) . "/" . substr($get_po['ETDDATE'], 6, 2) . "/" . substr($get_po['ETDDATE'], 0, 4);
            $cargoreadiness_date = substr($get_po['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po['CARGOREADINESSDATE'], 6, 2) . "/" . substr($get_po['CARGOREADINESSDATE'], 0, 4);
            $data = array(
                'loguniq' => $id_log,
                'csruniq' => trim($get_po['CSRUNIQ']),
                'pouniq' => trim($get_po['POUNIQ']),
                'po_number' => trim($get_po['PONUMBER']),
                'po_date' => $po_date,
                'etd_date' => $etd_date,
                'cargoreadiness_date' => $cargoreadiness_date,
                'po_remarks' => trim($get_po['POREMARKS']),
                'vendorshistatus' => $vendorshistatus,
                'etdorigin_date' => $etdorigindate,
                'atdorigin_date' => $atdorigindate,
                'etaport_date' => $etaportdate,
                'pib_date' => $pibdate,
                'form_action' => base_url($act),
                'button' => $button,
                'post_stat' => $posting_status,
                'post_stat_data' => $posting_stat_data,
            );
        }
        echo view('logistics/ajax_add_shipmentpo', $data);
    }


    public function insert_action()
    {
        $id_so = $this->request->getPost('id_so');
        $id_po = $this->request->getPost('id_po');
        $po_number = $this->request->getPost('po_number');
        $etdorigin_date = $this->request->getPost('etdorigin_date');
        $atdorigin_date = $this->request->getPost('atdorigin_date');
        $etaport_date = $this->request->getPost('etaport_date');
        $pib_date = $this->request->getPost('pib_date');
        $vendorshi_status = $this->request->getPost('vendorshi_status');
        $post_stat = $this->request->getPost('post_stat');
        if (null == $id_po and null == $etdorigin_date and null == $vendorshi_status) {
            session()->set('success', '-1');
            return redirect()->to(base_url('arrangeshipment'));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $n_etdorigin_date = substr($etdorigin_date, 6, 4) . substr($etdorigin_date, 0, 2) . substr($etdorigin_date, 3, 2);
            if (null == $atdorigin_date) {
                $n_atdorigin_date = '';
            } else {
                $n_atdorigin_date = substr($atdorigin_date, 6, 4) . substr($atdorigin_date, 0, 2) . substr($atdorigin_date, 3, 2);
            }
            if (null == $etaport_date) {
                $n_etaport_date = '';
            } else {
                $n_etaport_date = substr($etaport_date, 6, 4) . substr($etaport_date, 0, 2) . substr($etaport_date, 3, 2);
            }
            if (null == $pib_date) {
                $n_pib_date = '';
            } else {
                $n_pib_date = substr($pib_date, 6, 4) . substr($pib_date, 0, 2) . substr($pib_date, 3, 2);
            }
            $n_atdorigin_date  = empty($n_atdorigin_date) ? NULL : $n_atdorigin_date;
            $n_etaport_date  = empty($n_etaport_date) ? NULL : $n_etaport_date;
            $n_pib_date  = empty($n_pib_date) ? NULL : $n_pib_date;
            if (!empty($n_etdorigin_date) and !empty($n_atdorigin_date) and !empty($n_etaport_date) and !empty($n_pib_date) and !empty($vendorshi_status) and $post_stat == 1) {
                $offline_stat = $sender['OFFLINESTAT'];
            } else {
                $offline_stat = 1;
            }

            $groupuser = 5;

            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $id_so,
                'POUNIQ' => $id_po,
                'PONUMBER' => $po_number,
                'ETDORIGINDATE' => $n_etdorigin_date,
                'ATDORIGINDATE' => $n_atdorigin_date,
                'ETAPORTDATE' => $n_etaport_date,
                'PIBDATE' => $n_pib_date,
                'VENDSHISTATUS' => $vendorshi_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => $offline_stat,
            );
            print_r($data1);
            $this->LogisticsModel->arrangeshipment_insert($data1);

            if ($post_stat == 1) {

                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'ETDORIGINDATE' => $n_etdorigin_date,
                    'ATDORIGINDATE' => $n_atdorigin_date,
                    'ETAPORTDATE' => $n_etaport_date,
                    'PIBDATE' => $n_pib_date,
                    'VENDSHISTATUS' => $vendorshi_status,
                );

                $this->LogisticsModel->ot_logistics_update($id_so, $data2);

                if (!empty($n_etdorigin_date) and !empty($n_atdorigin_date) and !empty($n_etaport_date) and !empty($n_pib_date) and !empty($vendorshi_status)) {

                    if ($sender['OFFLINESTAT'] == 0) {

                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

                        foreach ($notiftouser_data as $sendto_user) {
                            $data_email = array(
                                'hostname'       => $sender['HOSTNAME'],
                                'sendername'       => $sender['SENDERNAME'],
                                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                'ssl'       => $sender['SSL'],
                                'smtpport'       => $sender['SMTPPORT'],
                                'to_email' => $sendto_user['EMAIL'],
                                'subject' => 'Pending Logistics Allert. PO Number : ' . $po_number,
                                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                Please to follow up PO Number :' . $po_number . ', ETD Origin Date :' . $n_etdorigin_date . ') is pending for you to process Inventory Team.
    <br><br>
    PO Number :' . $po_number . '<br>
    ETD Origin Date :' . $n_etdorigin_date . '<br>
    ATD Origin Date :' . $n_etdorigin_date . '<br>
    ETA Port Date :' . $n_etaport_date . '<br>
    PIB Date :' . $n_pib_date . '<br>
    Shipment Status :' . $vendorshi_status . '<br>
    <hr>
    You can access Order Tracking System Portal via the URL below:
    <br>
    Http://jktsms025:...
    <br>
    Thanks for your cooperation. 
    <br><br>
    Order Tracking Administrator',
                            );

                            $sending_mail = $this->send($data_email);

                            if ($sending_mail) {
                                $data_notif = array(
                                    'FROM_USER' => $this->header_data['usernamelgn'],
                                    'FROM_EMAIL' => $this->header_data['emaillgn'],
                                    'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                                    'TO_USER' => $sendto_user['USERNAME'],
                                    'TO_EMAIL' => $sendto_user['EMAIL'],
                                    'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                                    'SUBJECT' => 'Pending Logistics Allert. PO Number : ' . $po_number,
                                    'MESSAGE' => '  Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                                    Please to follow up PO Number :' . $po_number . ', ETD Origin Date :' . $n_etdorigin_date . ') is pending for you to process Inventory Team.
                        <br><br>
                        PO Number :' . $po_number . '<br>
                        ETD Origin Date :' . $n_etdorigin_date . '<br>
                        ATD Origin Date :' . $n_etdorigin_date . '<br>
                        ETA Port Date :' . $n_etaport_date . '<br>
                        PIB Date :' . $n_pib_date . '<br>
                        Shipment Status :' . $vendorshi_status . '<br>
                        <hr>
                        You can access Order Tracking System Portal via the URL below:
                        <br>
                        Http://jktsms025:...
                        <br>
                        Thanks for your cooperation. 
                        <br><br>
                        Order Tracking Administrator',

                                    'SENDING_DATE' => $this->audtuser['AUDTDATE'],
                                    'SENDING_TIME' => $this->audtuser['AUDTTIME'],
                                    'UPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                                    'UPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                                    'SENDERUPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                                    'SENDERUPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                                    'IS_READ' => 0,
                                    'IS_ARCHIVED' => 0,
                                    'IS_TRASHED' => 0,
                                    'IS_DELETED' => 0,
                                    'IS_ATTACHED' => 0,
                                    'IS_STAR' => 0,
                                    'IS_READSENDER' => 1,
                                    'IS_ARCHIVEDSENDER' => 0,
                                    'IS_TRASHEDSENDER' => 0,
                                    'IS_DELETEDSENDER' => 0,
                                    'SENDING_STATUS' => 1,
                                    'OTPROCESS' => $groupuser,
                                    'UNIQPROCESS' => $id_so,
                                );

                                $this->NotifModel->mailbox_insert($data_notif);
                            }
                        }
                    }
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/arrangeshipment'));
        session()->remove('success');
    }


    public function update_action()
    {
        $id_so = $this->request->getPost('id_so');
        $id_po = $this->request->getPost('id_po');
        $po_number = $this->request->getPost('po_number');
        $id_log = $this->request->getPost('id_log');
        $etdorigin_date = $this->request->getPost('etdorigin_date');
        $atdorigin_date = $this->request->getPost('atdorigin_date');
        $etaport_date = $this->request->getPost('etaport_date');
        $pib_date = $this->request->getPost('pib_date');
        $vendorshi_status = $this->request->getPost('vendorshi_status');
        $post_stat = $this->request->getPost('post_stat');
        $post_stat_data = $this->request->getPost('post_stat_data');
        if (null == $id_po and null == $etdorigin_date and null == $vendorshi_status) {
            session()->set('success', '-1');
            return redirect()->to(base_url('arrangeshipment'));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $n_etdorigin_date = substr($etdorigin_date, 6, 4) . substr($etdorigin_date, 0, 2) . substr($etdorigin_date, 3, 2);
            if (null == $atdorigin_date) {
                $n_atdorigin_date = '';
            } else {
                $n_atdorigin_date = substr($atdorigin_date, 6, 4) . substr($atdorigin_date, 0, 2) . substr($atdorigin_date, 3, 2);
            }
            if (null == $etaport_date) {
                $n_etaport_date = '';
            } else {
                $n_etaport_date = substr($etaport_date, 6, 4) . substr($etaport_date, 0, 2) . substr($etaport_date, 3, 2);
            }
            if (null == $pib_date) {
                $n_pib_date = '';
            } else {
                $n_pib_date = substr($pib_date, 6, 4) . substr($pib_date, 0, 2) . substr($pib_date, 3, 2);
            }
            $n_atdorigin_date  = empty($n_atdorigin_date) ? NULL : $n_atdorigin_date;
            $n_etaport_date  = empty($n_etaport_date) ? NULL : $n_etaport_date;
            $n_pib_date  = empty($n_pib_date) ? NULL : $n_pib_date;
            $groupuser = 5;
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $id_so,
                'POUNIQ' => $id_po,
                'PONUMBER' => $po_number,
                'ETDORIGINDATE' => $n_etdorigin_date,
                'ATDORIGINDATE' => $n_atdorigin_date,
                'ETAPORTDATE' => $n_etaport_date,
                'PIBDATE' => $n_pib_date,
                'VENDSHISTATUS' => $vendorshi_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => $sender['OFFLINESTAT'],
            );
            $this->LogisticsModel->arrangeshipment_update($id_log, $data1);

            if ($post_stat == 1) {

                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'ETDORIGINDATE' => $n_etdorigin_date,
                    'ATDORIGINDATE' => $n_atdorigin_date,
                    'ETAPORTDATE' => $n_etaport_date,
                    'PIBDATE' => $n_pib_date,
                    'VENDSHISTATUS' => $vendorshi_status,
                );
                $this->LogisticsModel->ot_logistics_update($id_so, $data2);

                if (!empty($n_etdorigin_date) and !empty($n_atdorigin_date) and !empty($n_etaport_date) and !empty($n_pib_date) and !empty($vendorshi_status)) {

                    if ($sender['OFFLINESTAT'] == 0) {

                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

                        foreach ($notiftouser_data as $sendto_user) {
                            $data_email = array(
                                'hostname'       => $sender['HOSTNAME'],
                                'sendername'       => $sender['SENDERNAME'],
                                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                'ssl'       => $sender['SSL'],
                                'smtpport'       => $sender['SMTPPORT'],
                                'to_email' => $sendto_user['EMAIL'],
                                'subject' => 'Pending Logistics Allert. PO Number : ' . $po_number,
                                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                Please to follow up PO Number :' . $po_number . ', ETD Origin Date :' . $n_etdorigin_date . ') is pending for you to process Inventory Team.
    <br><br>
    PO Number :' . $po_number . '<br>
    ETD Origin Date :' . $n_etdorigin_date . '<br>
    ATD Origin Date :' . $n_etdorigin_date . '<br>
    ETA Port Date :' . $n_etaport_date . '<br>
    PIB Date :' . $n_pib_date . '<br>
    Shipment Status :' . $vendorshi_status . '<br>
    <hr>
    You can access Order Tracking System Portal via the URL below:
    <br>
    Http://jktsms025:...
    <br>
    Thanks for your cooperation. 
    <br><br>
    Order Tracking Administrator',
                            );

                            $sending_mail = $this->send($data_email);

                            if ($sending_mail) {
                                $data_notif = array(
                                    'FROM_USER' => $this->header_data['usernamelgn'],
                                    'FROM_EMAIL' => $this->header_data['emaillgn'],
                                    'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                                    'TO_USER' => $sendto_user['USERNAME'],
                                    'TO_EMAIL' => $sendto_user['EMAIL'],
                                    'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                                    'SUBJECT' => 'Pending Logistics Allert. PO Number : ' . $po_number,
                                    'MESSAGE' => '  Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                                    Please to follow up PO Number :' . $po_number . ', ETD Origin Date :' . $n_etdorigin_date . ') is pending for you to process Inventory Team.
                        <br><br>
                        PO Number :' . $po_number . '<br>
                        ETD Origin Date :' . $n_etdorigin_date . '<br>
                        ATD Origin Date :' . $n_etdorigin_date . '<br>
                        ETA Port Date :' . $n_etaport_date . '<br>
                        PIB Date :' . $n_pib_date . '<br>
                        Shipment Status :' . $vendorshi_status . '<br>
                        <hr>
                        You can access Order Tracking System Portal via the URL below:
                        <br>
                        Http://jktsms025:...
                        <br>
                        Thanks for your cooperation. 
                        <br><br>
                        Order Tracking Administrator',

                                    'SENDING_DATE' => $this->audtuser['AUDTDATE'],
                                    'SENDING_TIME' => $this->audtuser['AUDTTIME'],
                                    'UPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                                    'UPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                                    'SENDERUPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                                    'SENDERUPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                                    'IS_READ' => 0,
                                    'IS_ARCHIVED' => 0,
                                    'IS_TRASHED' => 0,
                                    'IS_DELETED' => 0,
                                    'IS_ATTACHED' => 0,
                                    'IS_STAR' => 0,
                                    'IS_READSENDER' => 1,
                                    'IS_ARCHIVEDSENDER' => 0,
                                    'IS_TRASHEDSENDER' => 0,
                                    'IS_DELETEDSENDER' => 0,
                                    'SENDING_STATUS' => 1,
                                    'OTPROCESS' => $groupuser,
                                    'UNIQPROCESS' => $id_so,
                                );

                                $this->NotifModel->mailbox_insert($data_notif);
                            }
                        }
                    }
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/arrangeshipment'));
        session()->remove('success');
    }

    public function sendnotif($loguniq)
    {
        //check dari sini
        $get_log = $this->LogisticsModel->get_arrangeshipment_post($loguniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $id_so = $get_log['CSRUNIQ'];
        $po_number = $get_log['PONUMBER'];
        $vendorshi_status = $get_log['VENDSHISTATUS'];
        $n_etdorigin_date = substr($get_log['ETDORIGINDATE'], 6, 4) . substr($get_log['ETDORIGINDATE'], 0, 2) . substr($get_log['ETDORIGINDATE'], 3, 2);
        $n_atdorigin_date = substr($get_log['ATDORIGINDATE'], 6, 4) . substr($get_log['ATDORIGINDATE'], 0, 2) . substr($get_log['ATDORIGINDATE'], 3, 2);
        $n_etaport_date = substr($get_log['ETAPORTDATE'], 6, 4) . substr($get_log['ETAPORTDATE'], 0, 2) . substr($get_log['ETAPORTDATE'], 3, 2);
        $n_pib_date = substr($get_log['PIBDATE'], 6, 4) . substr($get_log['PIBDATE'], 0, 2) . substr($get_log['PIBDATE'], 3, 2);
        $n_atdorigin_date  = empty($n_atdorigin_date) ? NULL : $n_atdorigin_date;
        $n_etaport_date  = empty($n_etaport_date) ? NULL : $n_etaport_date;
        $n_pib_date  = empty($n_pib_date) ? NULL : $n_pib_date;
        $groupuser = 5;
        //inisiasi proses kirim ke group
        $data2 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'OFFLINESTAT' => 0,
        );

        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

        foreach ($notiftouser_data as $sendto_user) {
            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' => 'Pending Logistics Allert. PO Number : ' . $po_number,
                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                Please to follow up PO Number :' . $po_number . ', ETD Origin Date :' . $n_etdorigin_date . ') is pending for you to process Inventory Team.
    <br><br>
    PO Number :' . $po_number . '<br>
    ETD Origin Date :' . $n_etdorigin_date . '<br>
    ATD Origin Date :' . $n_etdorigin_date . '<br>
    ETA Port Date :' . $n_etaport_date . '<br>
    PIB Date :' . $n_pib_date . '<br>
    Shipment Status :' . $vendorshi_status . '<br>
    <hr>
    You can access Order Tracking System Portal via the URL below:
    <br>
    Http://jktsms025:...
    <br>
    Thanks for your cooperation. 
    <br><br>
    Order Tracking Administrator',
            );

            $sending_mail = $this->send($data_email);

            if ($sending_mail) {
                $data_notif = array(
                    'FROM_USER' => $this->header_data['usernamelgn'],
                    'FROM_EMAIL' => $this->header_data['emaillgn'],
                    'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                    'TO_USER' => $sendto_user['USERNAME'],
                    'TO_EMAIL' => $sendto_user['EMAIL'],
                    'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                    'SUBJECT' => 'Pending Logistics Allert. PO Number : ' . $po_number,
                    'MESSAGE' => '  Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                                    Please to follow up PO Number :' . $po_number . ', ETD Origin Date :' . $n_etdorigin_date . ') is pending for you to process Inventory Team.
                        <br><br>
                        PO Number :' . $po_number . '<br>
                        ETD Origin Date :' . $n_etdorigin_date . '<br>
                        ATD Origin Date :' . $n_etdorigin_date . '<br>
                        ETA Port Date :' . $n_etaport_date . '<br>
                        PIB Date :' . $n_pib_date . '<br>
                        Shipment Status :' . $vendorshi_status . '<br>
                        <hr>
                        You can access Order Tracking System Portal via the URL below:
                        <br>
                        Http://jktsms025:...
                        <br>
                        Thanks for your cooperation. 
                        <br><br>
                        Order Tracking Administrator',

                    'SENDING_DATE' => $this->audtuser['AUDTDATE'],
                    'SENDING_TIME' => $this->audtuser['AUDTTIME'],
                    'UPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                    'UPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                    'SENDERUPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                    'SENDERUPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                    'IS_READ' => 0,
                    'IS_ARCHIVED' => 0,
                    'IS_TRASHED' => 0,
                    'IS_DELETED' => 0,
                    'IS_ATTACHED' => 0,
                    'IS_STAR' => 0,
                    'IS_READSENDER' => 1,
                    'IS_ARCHIVEDSENDER' => 0,
                    'IS_TRASHEDSENDER' => 0,
                    'IS_DELETEDSENDER' => 0,
                    'SENDING_STATUS' => 1,
                    'OTPROCESS' => $groupuser,
                    'UNIQPROCESS' => $id_so,
                );

                $this->NotifModel->mailbox_insert($data_notif);
                $this->LogisticsModel->logistics_post_update($loguniq, $data2);
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/arrangeshipment'));
        session()->remove('success');
    }

    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $PurchaseOrderdata = $this->PurchaseOrderModel->get_PurchaseOrder_open();
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'ContractNo')
            ->setCellValue('C1', 'ProjectNo')
            ->setCellValue('D1', 'CustomerName')
            ->setCellValue('E1', 'CustomerEmail')
            ->setCellValue('F1', 'CrmNo')
            ->setCellValue('G1', 'PoCustomer')
            ->setCellValue('H1', 'InventoryNo')
            ->setCellValue('I1', 'MaterialNo')
            ->setCellValue('J1', 'PoDate')
            ->setCellValue('K1', 'ReqDate')
            ->setCellValue('L1', 'SalesPerson')
            ->setCellValue('M1', 'OrderDescription')
            ->setCellValue('N1', 'Qty')
            ->setCellValue('O1', 'Uom')
            ->setCellValue('P1', '')
            ->setCellValue('Q1', 'Pr Date')
            ->setCellValue('R1', 'PR Number')
            ->setCellValue('S1', '');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($PurchaseOrderdata as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['ContractNo'])
                ->setCellValue('C' . $rows, $data['ProjectNo'])
                ->setCellValue('D' . $rows, $data['CustomerName'])
                ->setCellValue('E' . $rows, $data['CustomerEmail'])
                ->setCellValue('F' . $rows, $data['CrmNo'])
                ->setCellValue('G' . $rows, $data['PoCustomer'])
                ->setCellValue('H' . $rows, $data['InventoryNo'])
                ->setCellValue('I' . $rows, $data['MaterialNo'])
                ->setCellValue('J' . $rows, $data['PoDate'])
                ->setCellValue('K' . $rows, $data['ReqDate'])
                ->setCellValue('L' . $rows, $data['SalesPerson'])
                ->setCellValue('M' . $rows, $data['OrderDesc'])
                ->setCellValue('N' . $rows, $data['Qty'])
                ->setCellValue('O' . $rows, $data['Uom'])
                ->setCellValue('P' . $rows, '')
                ->setCellValue('Q' . $rows, $data['PrDate'])
                ->setCellValue('R' . $rows, $data['PrNumber'])
                ->setCellValue('S' . $rows, '');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Ordertracking_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    private function send($data_email)
    {
        $hostname           = $data_email['hostname'];
        $sendername         = $data_email['sendername'];
        $senderemail        = $data_email['senderemail'];
        $passwordemail      = $data_email['passwordemail'];
        $ssl                = $data_email['ssl'];
        $smtpport           = $data_email['smtpport'];
        $to                 = $data_email['to_email'];
        $subject             = $data_email['subject'];
        $message             = $data_email['message'];

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $hostname;
            $mail->SMTPAuth   = true;
            $mail->Username   = $senderemail; // silahkan ganti dengan alamat email Anda
            $mail->Password   = $passwordemail; // silahkan ganti dengan password email Anda
            $mail->SMTPSecure = $ssl;
            $mail->Port       = $smtpport;

            $mail->setFrom($senderemail, $sendername); // silahkan ganti dengan alamat email Anda
            $mail->addAddress($to);
            $mail->addReplyTo($senderemail, $sendername); // silahkan ganti dengan alamat email Anda
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            session()->setFlashdata('success', 'Send Email successfully');
            return redirect()->to(base_url('/PurchaseOrder'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/PurchaseOrder'));
        }
    }
}
