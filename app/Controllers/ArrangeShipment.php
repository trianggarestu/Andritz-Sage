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
            if (session()->get('keylog') == $infouser['passlgn'] and session()->get('userhash') == $infouser['userhashlgn']) {


                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'photolgn' => $infouser['photolgn'],
                    'userhashlgn' => $infouser['userhashlgn'],
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
                    'NAMELGN' => $infouser['namalgn'],

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
        session()->remove('cari');
        $logisticsdata = $this->LogisticsModel->get_po_pending_to_arrangeshipment();

        $data = array(
            'logistics_data' => $logisticsdata,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('logistics/data_po_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('arrangeshipment'));
    }


    public function search()
    {

        session()->remove('success');
        session()->set('success', '0');
        $cari = $this->request->getPost('cari');
        if ($cari != '') {
            session()->set('cari', $cari);
        } else {
            session()->remove('cari');
        }
        return redirect()->to(base_url('arrangeshipment/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $logisticsdata = $this->LogisticsModel->get_po_pending_to_arrangeshipment();
        } else {
            $logisticsdata = $this->LogisticsModel->get_po_pending_to_arrangeshipment_search($keyword);
        }
        $data = array(
            'logistics_data' => $logisticsdata,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('logistics/data_po_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update($pouniq, $postingstat)
    {
        session()->remove('success');
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
        if (null == $id_po and null == $etdorigin_date) {
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
            $n_vendorshi_status  = empty($vendorshi_status) ? NULL : $vendorshi_status;
            $groupuser = 5;

            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'LOGKEY' => $id_so . '-' . trim($po_number),
                'CSRUNIQ' => $id_so,
                'POUNIQ' => $id_po,
                'PONUMBER' => trim($po_number),
                'ETDORIGINDATE' => $n_etdorigin_date,
                'ATDORIGINDATE' => $n_atdorigin_date,
                'ETAPORTDATE' => $n_etaport_date,
                'PIBDATE' => $n_pib_date,
                'VENDSHISTATUS' => $n_vendorshi_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => 1,
            );
            //print_r($data1);
            $getloguniq = $this->LogisticsModel->get_loguniq_open($id_so, $id_po);
            if (!empty($getloguniq['LOGKEY'])) {
                session()->set('success', '-1');
                return redirect()->to(base_url('arrangeshipment'));
                session()->remove('success');
            } else if (empty($getloguniq['LOGKEY'])) {
                // Insert webot_LOGistics
                $this->LogisticsModel->arrangeshipment_insert($data1);
                // Jika Posting
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

                    $this->LogisticsModel->ot_logistics_update($id_so, $po_number, $data2);

                    if (!empty($n_etdorigin_date) and !empty($n_atdorigin_date) and !empty($n_etaport_date) and !empty($n_pib_date) and !empty($vendorshi_status)) {

                        if ($sender['OFFLINESTAT'] == 0) {
                            $getlog = $this->LogisticsModel->get_loguniq_open($id_so, $id_po);
                            $n_loguniq = $getlog['LOGUNIQ'];
                            $get_log_data = $this->LogisticsModel->get_logjoincsr_by_po($n_loguniq);
                            $crmpodate = substr($get_log_data['PODATECUST'], 4, 2) . "/" . substr($get_log_data['PODATECUST'], 6, 2) . "/" .  substr($get_log_data['PODATECUST'], 0, 4);
                            $crmreqdate = substr($get_log_data['CRMREQDATE'], 4, 2) . '/' . substr($get_log_data['CRMREQDATE'], 6, 2) . '/' . substr($get_log_data['CRMREQDATE'], 0, 4);
                            $rqndate = substr($get_log_data['RQNDATE'], 4, 2) . "/" . substr($get_log_data['RQNDATE'], 6, 2) . "/" .  substr($get_log_data['RQNDATE'], 0, 4);
                            $povendordate = substr($get_log_data['PODATE'], 4, 2) . "/" . substr($get_log_data['PODATE'], 6, 2) . "/" .  substr($get_log_data['PODATE'], 0, 4);
                            $etddate = substr($get_log_data['ETDDATE'], 4, 2) . "/" . substr($get_log_data['ETDDATE'], 6, 2) . "/" .  substr($get_log_data['ETDDATE'], 0, 4);
                            $cargoreadinessdate = substr($get_log_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_log_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_log_data['CARGOREADINESSDATE'], 0, 4);
                            $etdorigindate = substr($get_log_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_log_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_log_data['ETDORIGINDATE'], 0, 4);
                            $atdorigindate = substr($get_log_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_log_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_log_data['ATDORIGINDATE'], 0, 4);
                            $etaportdate = substr($get_log_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_log_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_log_data['ETAPORTDATE'], 0, 4);
                            $pibdate = substr($get_log_data['PIBDATE'], 4, 2) . "/" . substr($get_log_data['PIBDATE'], 6, 2) . "/" .  substr($get_log_data['PIBDATE'], 0, 4);

                            //Untuk Update Status Posting PO
                            $data3 = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                'AUDTORG' => $this->audtuser['AUDTORG'],
                                'POSTINGSTAT' => 1,
                                'OFFLINESTAT' => 0,
                            );

                            $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                            $mail_tmpl = $this->NotifModel->get_template($groupuser);

                            foreach ($notiftouser_data as $sendto_user) :
                                $var_email = array(
                                    'TONAME' => $sendto_user['NAME'],
                                    'FROMNAME' => $this->audtuser['NAMELGN'],
                                    'CONTRACT' => $get_log_data['CONTRACT'],
                                    'CTDESC' => $get_log_data['CTDESC'],
                                    'PROJECT' => $get_log_data['PROJECT'],
                                    'PRJDESC' => $get_log_data['PRJDESC'],
                                    'CUSTOMER' => $get_log_data['CUSTOMER'],
                                    'NAMECUST' => $get_log_data['NAMECUST'],
                                    'PONUMBERCUST' => $get_log_data['PONUMBERCUST'],
                                    'PODATECUST' => $crmpodate,
                                    'CRMNO' => $get_log_data['CRMNO'],
                                    'REQDATE' => $crmreqdate,
                                    'ORDERDESC' => $get_log_data['ORDERDESC'],
                                    'REMARKS' => $get_log_data['CRMREMARKS'],
                                    'SALESCODE' => $get_log_data['MANAGER'],
                                    'SALESPERSON' => $get_log_data['SALESNAME'],
                                    'RQNDATE' => $rqndate,
                                    'RQNNUMBER' => $get_log_data['RQNNUMBER'],
                                    //DATA VARIABLE PO
                                    'PODATE' => $povendordate,
                                    'PONUMBER' => $get_log_data['PONUMBER'],
                                    'ETDDATE' => $etddate,
                                    'CARGOREADINESSDATE' => $cargoreadinessdate,
                                    'ORIGINCOUNTRY' => $get_log_data['ORIGINCOUNTRY'],
                                    'POREMARKS' => $get_log_data['POREMARKS'],
                                    //DATA VARIABLE LOGISTICS
                                    'ETDORIGINDATE' => $etdorigindate,
                                    'ATDORIGINDATE' => $atdorigindate,
                                    'ETAPORTDATE' => $etaportdate,
                                    'PIBDATE' => $pibdate,
                                    'VENDSHISTATUS' => $get_log_data['VENDSHISTATUS'],
                                );
                                $subject = $mail_tmpl['SUBJECT_MAIL'];
                                $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                                $data_email = array(
                                    'hostname'       => $sender['HOSTNAME'],
                                    'sendername'       => $sender['SENDERNAME'],
                                    'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                    'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                    'smtpauth'       => $sender['SMTPAUTH'],
                                    'ssl'       => $sender['SSL'],
                                    'smtpport'       => $sender['SMTPPORT'],
                                    'to_email' => $sendto_user['EMAIL'],
                                    'subject' =>  $subject,
                                    'message' => $message,
                                );


                                $data_notif = array(
                                    'MAILKEY' => $groupuser . '-' . $get_log_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
                                    'FROM_USER' => $this->header_data['usernamelgn'],
                                    'FROM_EMAIL' => $this->header_data['emaillgn'],
                                    'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                                    'TO_USER' => $sendto_user['USERNAME'],
                                    'TO_EMAIL' => $sendto_user['EMAIL'],
                                    'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                                    'SUBJECT' => $subject,
                                    'MESSAGE' => $message,
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
                                    'UNIQPROCESS' => $get_log_data['LOGUNIQ'],
                                );

                                //Check Duplicate Entry & Sending Mail
                                $touser = trim($sendto_user['USERNAME']);
                                $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_log_data['LOGUNIQ'], $touser);
                                if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_log_data['POUNIQ'] . '-' . $touser) {
                                    session()->set('success', '-1');
                                    return redirect()->to(base_url('/arrangeshipment'));
                                    session()->remove('success');
                                } else if (empty($getmailuniq['MAILKEY'])) {
                                    $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                    if ($post_email) {
                                        $sending_mail = $this->send($data_email);
                                    }
                                }

                            endforeach;

                            $this->LogisticsModel->arrangeshipment_update($get_log_data['LOGUNIQ'], $data3);
                        }
                    }
                }
                session()->set('success', '1');
                return redirect()->to(base_url('/arrangeshipment'));
                session()->remove('success');
            }
        }
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
            $n_vendorshi_status  = empty($vendorshi_status) ? NULL : $vendorshi_status;
            $groupuser = 5;
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'ETDORIGINDATE' => $n_etdorigin_date,
                'ATDORIGINDATE' => $n_atdorigin_date,
                'ETAPORTDATE' => $n_etaport_date,
                'PIBDATE' => $n_pib_date,
                'VENDSHISTATUS' => $n_vendorshi_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => 1,
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
                    'VENDSHISTATUS' => $n_vendorshi_status,
                );
                $this->LogisticsModel->ot_logistics_update($id_so, $po_number, $data2);

                if (!empty($n_etdorigin_date) and !empty($n_atdorigin_date) and !empty($n_etaport_date) and !empty($n_pib_date) and !empty($n_vendorshi_status)) {
                    if ($sender['OFFLINESTAT'] == 0) {
                        $get_log_data = $this->LogisticsModel->get_logjoincsr_by_po($id_log);
                        $crmpodate = substr($get_log_data['PODATECUST'], 4, 2) . "/" . substr($get_log_data['PODATECUST'], 6, 2) . "/" .  substr($get_log_data['PODATECUST'], 0, 4);
                        $crmreqdate = substr($get_log_data['CRMREQDATE'], 4, 2) . '/' . substr($get_log_data['CRMREQDATE'], 6, 2) . '/' . substr($get_log_data['CRMREQDATE'], 0, 4);
                        $rqndate = substr($get_log_data['RQNDATE'], 4, 2) . "/" . substr($get_log_data['RQNDATE'], 6, 2) . "/" .  substr($get_log_data['RQNDATE'], 0, 4);
                        $povendordate = substr($get_log_data['PODATE'], 4, 2) . "/" . substr($get_log_data['PODATE'], 6, 2) . "/" .  substr($get_log_data['PODATE'], 0, 4);
                        $etddate = substr($get_log_data['ETDDATE'], 4, 2) . "/" . substr($get_log_data['ETDDATE'], 6, 2) . "/" .  substr($get_log_data['ETDDATE'], 0, 4);
                        $cargoreadinessdate = substr($get_log_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_log_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_log_data['CARGOREADINESSDATE'], 0, 4);
                        $etdorigindate = substr($get_log_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_log_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_log_data['ETDORIGINDATE'], 0, 4);
                        $atdorigindate = substr($get_log_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_log_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_log_data['ATDORIGINDATE'], 0, 4);
                        $etaportdate = substr($get_log_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_log_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_log_data['ETAPORTDATE'], 0, 4);
                        $pibdate = substr($get_log_data['PIBDATE'], 4, 2) . "/" . substr($get_log_data['PIBDATE'], 6, 2) . "/" .  substr($get_log_data['PIBDATE'], 0, 4);

                        //Untuk Update Status Posting PO
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 0,
                        );

                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                        $mail_tmpl = $this->NotifModel->get_template($groupuser);
                        foreach ($notiftouser_data as $sendto_user) :
                            $var_email = array(
                                'TONAME' => $sendto_user['NAME'],
                                'FROMNAME' => $this->audtuser['NAMELGN'],
                                'CONTRACT' => $get_log_data['CONTRACT'],
                                'CTDESC' => $get_log_data['CTDESC'],
                                'PROJECT' => $get_log_data['PROJECT'],
                                'PRJDESC' => $get_log_data['PRJDESC'],
                                'CUSTOMER' => $get_log_data['CUSTOMER'],
                                'NAMECUST' => $get_log_data['NAMECUST'],
                                'PONUMBERCUST' => $get_log_data['PONUMBERCUST'],
                                'PODATECUST' => $crmpodate,
                                'CRMNO' => $get_log_data['CRMNO'],
                                'REQDATE' => $crmreqdate,
                                'ORDERDESC' => $get_log_data['ORDERDESC'],
                                'REMARKS' => $get_log_data['CRMREMARKS'],
                                'SALESCODE' => $get_log_data['MANAGER'],
                                'SALESPERSON' => $get_log_data['SALESNAME'],
                                'RQNDATE' => $rqndate,
                                'RQNNUMBER' => $get_log_data['RQNNUMBER'],
                                //DATA VARIABLE PO
                                'PODATE' => $povendordate,
                                'PONUMBER' => $get_log_data['PONUMBER'],
                                'ETDDATE' => $etddate,
                                'CARGOREADINESSDATE' => $cargoreadinessdate,
                                'ORIGINCOUNTRY' => $get_log_data['ORIGINCOUNTRY'],
                                'POREMARKS' => $get_log_data['POREMARKS'],
                                //DATA VARIABLE LOGISTICS
                                'ETDORIGINDATE' => $etdorigindate,
                                'ATDORIGINDATE' => $atdorigindate,
                                'ETAPORTDATE' => $etaportdate,
                                'PIBDATE' => $pibdate,
                                'VENDSHISTATUS' => $get_log_data['VENDSHISTATUS'],
                            );
                            $subject = $mail_tmpl['SUBJECT_MAIL'];
                            $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                            $data_email = array(
                                'hostname'       => $sender['HOSTNAME'],
                                'sendername'       => $sender['SENDERNAME'],
                                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                'smtpauth'       => $sender['SMTPAUTH'],
                                'ssl'       => $sender['SSL'],
                                'smtpport'       => $sender['SMTPPORT'],
                                'to_email' => $sendto_user['EMAIL'],
                                'subject' =>  $subject,
                                'message' => $message,
                            );


                            $data_notif = array(
                                'MAILKEY' => $groupuser . '-' . $get_log_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
                                'FROM_USER' => $this->header_data['usernamelgn'],
                                'FROM_EMAIL' => $this->header_data['emaillgn'],
                                'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                                'TO_USER' => $sendto_user['USERNAME'],
                                'TO_EMAIL' => $sendto_user['EMAIL'],
                                'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                                'SUBJECT' => $subject,
                                'MESSAGE' => $message,
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
                                'UNIQPROCESS' => $get_log_data['LOGUNIQ'],
                            );

                            //Check Duplicate Entry & Sending Mail
                            $touser = trim($sendto_user['USERNAME']);
                            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_log_data['LOGUNIQ'], $touser);
                            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_log_data['POUNIQ'] . '-' . $touser) {
                                session()->set('success', '-1');
                                return redirect()->to(base_url('/arrangeshipment'));
                                session()->remove('success');
                            } else if (empty($getmailuniq['MAILKEY'])) {
                                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                if ($post_email) {
                                    $sending_mail = $this->send($data_email);
                                }
                            }

                        endforeach;
                        $this->LogisticsModel->arrangeshipment_update($get_log_data['LOGUNIQ'], $data3);
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

        $get_log_data = $this->LogisticsModel->get_logjoincsr_by_po($loguniq);
        if (!empty($get_log_data['LOGUNIQ'])) {
            $crmpodate = substr($get_log_data['PODATECUST'], 4, 2) . "/" . substr($get_log_data['PODATECUST'], 6, 2) . "/" .  substr($get_log_data['PODATECUST'], 0, 4);
            $crmreqdate = substr($get_log_data['CRMREQDATE'], 4, 2) . '/' . substr($get_log_data['CRMREQDATE'], 6, 2) . '/' . substr($get_log_data['CRMREQDATE'], 0, 4);
            $rqndate = substr($get_log_data['RQNDATE'], 4, 2) . "/" . substr($get_log_data['RQNDATE'], 6, 2) . "/" .  substr($get_log_data['RQNDATE'], 0, 4);
            $povendordate = substr($get_log_data['PODATE'], 4, 2) . "/" . substr($get_log_data['PODATE'], 6, 2) . "/" .  substr($get_log_data['PODATE'], 0, 4);
            $etddate = substr($get_log_data['ETDDATE'], 4, 2) . "/" . substr($get_log_data['ETDDATE'], 6, 2) . "/" .  substr($get_log_data['ETDDATE'], 0, 4);
            $cargoreadinessdate = substr($get_log_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_log_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_log_data['CARGOREADINESSDATE'], 0, 4);
            if (null == $get_log_data['ETDORIGINDATE']) {
                $etdorigindate = '';
            } else {
                $etdorigindate = substr($get_log_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_log_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_log_data['ETDORIGINDATE'], 0, 4);
            }
            if (null == $get_log_data['ATDORIGINDATE']) {
                $atdorigindate = '';
            } else {
                $atdorigindate = substr($get_log_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_log_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_log_data['ATDORIGINDATE'], 0, 4);
            }
            if (null == $get_log_data['ETAPORTDATE']) {
                $etaportdate = '';
            } else {
                $etaportdate = substr($get_log_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_log_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_log_data['ETAPORTDATE'], 0, 4);
            }
            if (null == $get_log_data['PIBDATE']) {
                $pibdate = '';
            } else {
                $pibdate = substr($get_log_data['PIBDATE'], 4, 2) . "/" . substr($get_log_data['PIBDATE'], 6, 2) . "/" .  substr($get_log_data['PIBDATE'], 0, 4);
            }

            $n_etdorigin_date  = empty($etdorigindate) ? NULL : $etdorigindate;
            $n_atdorigin_date  = empty($atdorigindate) ? NULL : $atdorigindate;
            $n_etaport_date  = empty($etaportdate) ? NULL : $etaportdate;
            $n_pib_date  = empty($pibdate) ? NULL : $pibdate;
            $n_vendorshi_status  = empty($get_log_data['VENDSHISTATUS']) ? NULL : $get_log_data['VENDSHISTATUS'];
            if (!empty($n_etdorigin_date) and !empty($n_atdorigin_date) and !empty($n_etaport_date) and !empty($n_pib_date) and !empty($n_vendorshi_status)) {
                $sender = $this->AdministrationModel->get_mailsender();
                $groupuser = 5;

                //Untuk Update Status Posting PO
                $data3 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'POSTINGSTAT' => 1,
                    'OFFLINESTAT' => 0,
                );

                $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                $mail_tmpl = $this->NotifModel->get_template($groupuser);
                foreach ($notiftouser_data as $sendto_user) :
                    $var_email = array(
                        'TONAME' => $sendto_user['NAME'],
                        'FROMNAME' => $this->audtuser['NAMELGN'],
                        'CONTRACT' => $get_log_data['CONTRACT'],
                        'CTDESC' => $get_log_data['CTDESC'],
                        'PROJECT' => $get_log_data['PROJECT'],
                        'PRJDESC' => $get_log_data['PRJDESC'],
                        'CUSTOMER' => $get_log_data['CUSTOMER'],
                        'NAMECUST' => $get_log_data['NAMECUST'],
                        'PONUMBERCUST' => $get_log_data['PONUMBERCUST'],
                        'PODATECUST' => $crmpodate,
                        'CRMNO' => $get_log_data['CRMNO'],
                        'REQDATE' => $crmreqdate,
                        'ORDERDESC' => $get_log_data['ORDERDESC'],
                        'REMARKS' => $get_log_data['CRMREMARKS'],
                        'SALESCODE' => $get_log_data['MANAGER'],
                        'SALESPERSON' => $get_log_data['SALESNAME'],
                        'RQNDATE' => $rqndate,
                        'RQNNUMBER' => $get_log_data['RQNNUMBER'],
                        //DATA VARIABLE PO
                        'PODATE' => $povendordate,
                        'PONUMBER' => $get_log_data['PONUMBER'],
                        'ETDDATE' => $etddate,
                        'CARGOREADINESSDATE' => $cargoreadinessdate,
                        'ORIGINCOUNTRY' => $get_log_data['ORIGINCOUNTRY'],
                        'POREMARKS' => $get_log_data['POREMARKS'],
                        //DATA VARIABLE LOGISTICS
                        'ETDORIGINDATE' => $etdorigindate,
                        'ATDORIGINDATE' => $atdorigindate,
                        'ETAPORTDATE' => $etaportdate,
                        'PIBDATE' => $pibdate,
                        'VENDSHISTATUS' => $get_log_data['VENDSHISTATUS'],
                    );
                    $subject = $mail_tmpl['SUBJECT_MAIL'];
                    $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                    $data_email = array(
                        'hostname'       => $sender['HOSTNAME'],
                        'sendername'       => $sender['SENDERNAME'],
                        'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                        'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                        'smtpauth'       => $sender['SMTPAUTH'],
                        'ssl'       => $sender['SSL'],
                        'smtpport'       => $sender['SMTPPORT'],
                        'to_email' => $sendto_user['EMAIL'],
                        'subject' =>  $subject,
                        'message' => $message,
                    );


                    $data_notif = array(
                        'MAILKEY' => $groupuser . '-' . $get_log_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
                        'FROM_USER' => $this->header_data['usernamelgn'],
                        'FROM_EMAIL' => $this->header_data['emaillgn'],
                        'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                        'TO_USER' => $sendto_user['USERNAME'],
                        'TO_EMAIL' => $sendto_user['EMAIL'],
                        'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                        'SUBJECT' => $subject,
                        'MESSAGE' => $message,
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
                        'UNIQPROCESS' => $get_log_data['LOGUNIQ'],
                    );

                    //Check Duplicate Entry & Sending Mail
                    $touser = trim($sendto_user['USERNAME']);
                    $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_log_data['LOGUNIQ'], $touser);
                    if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_log_data['POUNIQ'] . '-' . $touser) {
                        session()->set('success', '-1');
                        return redirect()->to(base_url('/arrangeshipment'));
                        session()->remove('success');
                    } else if (empty($getmailuniq['MAILKEY'])) {
                        $post_email = $this->NotifModel->mailbox_insert($data_notif);
                        if ($post_email) {
                            $sending_mail = $this->send($data_email);
                        }
                    }

                endforeach;

                $this->LogisticsModel->arrangeshipment_update($get_log_data['LOGUNIQ'], $data3);

                session()->set('success', '9');
                return redirect()->to(base_url('/arrangeshipment'));
                session()->remove('success');
            } else {
                session()->set('success', '-9');
                return redirect()->to(base_url('/arrangeshipment'));
                session()->remove('success');
            }
        } else {
            session()->set('success', '-9');
            return redirect()->to(base_url('/arrangeshipment'));
            session()->remove('success');
        }
    }


    private function send($data_email)
    {
        $hostname           = $data_email['hostname'];
        $sendername         = $data_email['sendername'];
        $senderemail        = $data_email['senderemail'];
        $passwordemail      = $data_email['passwordemail'];
        $smtpauth           = $data_email['smtpauth'];
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
            $mail->SMTPAuth   = $smtpauth;
            $mail->Username   = $senderemail; // silahkan ganti dengan alamat email Anda
            if ($smtpauth == TRUE) :
                $mail->Password   = $passwordemail; // silahkan ganti dengan password email Anda
            endif;
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
            return redirect()->to(base_url('/arrangeshipment'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/arrangeshipment'));
        }
    }
}
