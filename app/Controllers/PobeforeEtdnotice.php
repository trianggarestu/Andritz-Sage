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
use App\Models\PurchaseOrder_model;

//use App\Controllers\AdminController;

class PobeforeEtdnotice extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $audtuser;
    private $db_name;
    private $cart;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->db_name = \Config\Database::connect();
        $this->cart = \Config\Services::cart();

        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->PurchaseorderModel = new Purchaseorder_model();

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
                $activenavd = 'pobeforeetdnotice';
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

        $pobeforeetd_data = $this->PurchaseorderModel->get_pobeforeetd();

        $data = array(
            'purchaseOrder_data' => $pobeforeetd_data,
            //'ct_po_beforeetd' => $this->PurchaseorderModel->count_po_beforeetd(),
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        //echo view('purchaseorder/data_po_beforeetd.php', $data);
        echo view('purchaseorder/data_po_beforeetd', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('pobeforeetdnotice'));
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
        return redirect()->to(base_url('pobeforeetdnotice/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $pobeforeetd_data = $this->PurchaseorderModel->get_pobeforeetd();
        } else {
            $pobeforeetd_data = $this->PurchaseorderModel->get_pobeforeetd_search($keyword);
        }
        $data = array(
            'purchaseOrder_data' => $pobeforeetd_data,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('purchaseorder/data_po_beforeetd', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update_cargoreadiness($pouniq, $post_stat)
    {

        $get_po = $this->PurchaseorderModel->get_po_by_pouniq($pouniq);

        if ($get_po) {
            $get_pr = $this->PurchaseorderModel->get_requisition_by_id($get_po['RQNUNIQ']);
            $act = 'purchaseorder/update_cargoreadiness_action';
            $id_po = $get_po['POUNIQ'];
            $ponumber = $get_po['PONUMBER'];
            $origincountry = trim($get_po['ORIGINCOUNTRY']);
            $poremarks = trim($get_po['POREMARKS']);
            $cargoreadinessdate = '';
            $etddate = substr($get_po['ETDDATE'], 4, 2) . "/" . substr($get_po['ETDDATE'], 6, 2) . "/" . substr($get_po['ETDDATE'], 0, 4);
            if ($post_stat == 0) {
                $button = 'Update & Save';
            } else {
                $button = 'Update & Posting';
            }

            $data = array(
                'csruniq' => $get_pr['CSRUNIQ'],
                'rqnuniq' => $get_pr['RQNUNIQ'],
                'pouniq' => $id_po,
                'po_number' => $ponumber,
                'etd_date' => $etddate,
                'cargoreadiness_date' => $cargoreadinessdate,
                'origin_country' => $origincountry,
                'po_remarks' => $poremarks,
                'post_stat' => $post_stat,
                'posage_list' => $this->PurchaseorderModel->get_po_list_sage_by_rqn($get_po['RQNNUMBER']),
                'form_action' => base_url($act),
                'button' => $button,

            );
        }
        echo view('purchaseorder/ajax_add_po_cargoreadiness', $data);
    }


    public function update_cargoreadiness_action()
    {
        $id_so = $this->request->getPost('csruniq');
        $id_pr = $this->request->getPost('id_pr');
        $id_po = $this->request->getPost('id_po');
        $cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
        if (null == $cargoreadiness_date) {
            session()->set('success', '-1');
            return redirect()->to(base_url('/pobeforeetdnotice'));
            session()->remove('success');
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            //$ponumber = $this->request->getPost('po_number');
            $post_stat = $this->request->getPost('post_stat');
            $get_pr = $this->PurchaseorderModel->get_requisition_by_id($id_pr);

            $n_cargoreadiness_date = substr($cargoreadiness_date, 6, 4) . substr($cargoreadiness_date, 0, 2) . substr($cargoreadiness_date, 3, 2);

            $n_cargoreadiness_date  = empty($n_cargoreadiness_date) ? NULL : $n_cargoreadiness_date;


            $groupuser = 4;

            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                'OTPROCESS' => $groupuser,
                'OFFLINESTAT' => 1,
            );
            $this->PurchaseorderModel->purchaseorder_update($id_po, $data1);

            if ($post_stat == 1) {
                $get_po = $this->PurchaseorderModel->get_purchaseorder_post($id_po);
                $getpouniq = $this->PurchaseorderModel->get_pouniq_open($id_so, $get_pr['RQNNUMBER'], $get_po["PONUMBER"]);
                $pouniq = $getpouniq['POUNIQ'];
                $po_to_ot = $this->PurchaseorderModel->get_po_open_by_id($pouniq);
                foreach ($po_to_ot as $data_pol) :
                    $csrluniq = $data_pol['CSRLUNIQ'];

                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                    );

                    $this->PurchaseorderModel->ot_purchaseorder_update($id_so, $csrluniq, $data2);

                endforeach;

                // for check complete input

                $chk_cargoreadiness_date = $this->request->getPost('cargoreadiness_date');

                if (!empty($chk_cargoreadiness_date)) {

                    $get_po_data = $this->PurchaseorderModel->get_pojoincsr_by_po($pouniq);
                    $crmpodate = substr($get_po_data['PODATECUST'], 4, 2) . "/" . substr($get_po_data['PODATECUST'], 6, 2) . "/" .  substr($get_po_data['PODATECUST'], 0, 4);
                    $crmreqdate = substr($get_po_data['CRMREQDATE'], 4, 2) . '/' . substr($get_po_data['CRMREQDATE'], 6, 2) . '/' . substr($get_po_data['CRMREQDATE'], 0, 4);
                    $rqndate = substr($get_po_data['RQNDATE'], 4, 2) . "/" . substr($get_po_data['RQNDATE'], 6, 2) . "/" .  substr($get_po_data['RQNDATE'], 0, 4);
                    $povendordate = substr($get_po_data['PODATE'], 4, 2) . "/" . substr($get_po_data['PODATE'], 6, 2) . "/" .  substr($get_po_data['PODATE'], 0, 4);
                    $etddate = substr($get_po_data['ETDDATE'], 4, 2) . "/" . substr($get_po_data['ETDDATE'], 6, 2) . "/" .  substr($get_po_data['ETDDATE'], 0, 4);
                    $cargoreadinessdate = substr($get_po_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_po_data['CARGOREADINESSDATE'], 0, 4);

                    if ($sender['OFFLINESTAT'] == 0) {
                        //Untuk Update Status Posting PO
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 0,
                        );
                        //inisiasi proses kirim ke group
                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                        $mail_tmpl = $this->NotifModel->get_template($groupuser);
                        foreach ($notiftouser_data as $sendto_user) :
                            $var_email = array(
                                'TONAME' => $sendto_user['NAME'],
                                'FROMNAME' => $this->audtuser['NAMELGN'],
                                'CONTRACT' => $get_po_data['CONTRACT'],
                                'CTDESC' => $get_po_data['CTDESC'],
                                'PROJECT' => $get_po_data['PROJECT'],
                                'PRJDESC' => $get_po_data['PRJDESC'],
                                'CUSTOMER' => $get_po_data['CUSTOMER'],
                                'NAMECUST' => $get_po_data['NAMECUST'],
                                'PONUMBERCUST' => $get_po_data['PONUMBERCUST'],
                                'PODATECUST' => $crmpodate,
                                'CRMNO' => $get_po_data['CRMNO'],
                                'REQDATE' => $crmreqdate,
                                'ORDERDESC' => $get_po_data['ORDERDESC'],
                                'REMARKS' => $get_po_data['CRMREMARKS'],
                                'SALESCODE' => $get_po_data['MANAGER'],
                                'SALESPERSON' => $get_po_data['SALESNAME'],
                                'RQNDATE' => $rqndate,
                                'RQNNUMBER' => $get_po_data['RQNNUMBER'],
                                //DATA VARIABLE PO
                                'PODATE' => $povendordate,
                                'PONUMBER' => $get_po_data['PONUMBER'],
                                'ETDDATE' => $etddate,
                                'CARGOREADINESSDATE' => $cargoreadinessdate,
                                'ORIGINCOUNTRY' => $get_po_data['ORIGINCOUNTRY'],
                                'POREMARKS' => $get_po_data['POREMARKS'],
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
                                'MAILKEY' => $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                                'UNIQPROCESS' => $get_po_data['POUNIQ'],
                            );

                            //Check Duplicate Entry & Sending Mail
                            $touser = trim($sendto_user['USERNAME']);
                            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_po_data['POUNIQ'], $touser);
                            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . $touser) {
                                session()->set('success', '-1');
                                return redirect()->to(base_url('/pobeforeetdnotice'));
                                session()->remove('success');
                            } else if (empty($getmailuniq['MAILKEY'])) {
                                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                if ($post_email) {
                                    $sending_mail = $this->send($data_email);
                                }
                            }

                        endforeach;

                        $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                        session()->set('success', '1');
                        return redirect()->to(base_url('/pobeforeetdnotice'));
                        session()->remove('success');
                    } else {
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 1,
                        );
                        $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                        //session()->setFlashdata('messageerror', 'Create Record Failed');
                        session()->set('success', '1');
                        return redirect()->to(base_url('/pobeforeetdnotice'));
                        session()->remove('success');
                    }
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/pobeforeetdnotice'));
        session()->remove('success');
    }


    public function sendnotif($pouniq)
    {
        $get_po_data = $this->PurchaseorderModel->get_pojoincsr_by_po($pouniq);
        if (!empty($get_po_data['CARGOREADINESSDATE'])) {
            //Set Format Date for variable Mail Notif
            $crmpodate = substr($get_po_data['PODATECUST'], 4, 2) . "/" . substr($get_po_data['PODATECUST'], 6, 2) . "/" .  substr($get_po_data['PODATECUST'], 0, 4);
            $crmreqdate = substr($get_po_data['CRMREQDATE'], 4, 2) . '/' . substr($get_po_data['CRMREQDATE'], 6, 2) . '/' . substr($get_po_data['CRMREQDATE'], 0, 4);
            $rqndate = substr($get_po_data['RQNDATE'], 4, 2) . "/" . substr($get_po_data['RQNDATE'], 6, 2) . "/" .  substr($get_po_data['RQNDATE'], 0, 4);
            $povendordate = substr($get_po_data['PODATE'], 4, 2) . "/" . substr($get_po_data['PODATE'], 6, 2) . "/" .  substr($get_po_data['PODATE'], 0, 4);
            $etddate = substr($get_po_data['ETDDATE'], 4, 2) . "/" . substr($get_po_data['ETDDATE'], 6, 2) . "/" .  substr($get_po_data['ETDDATE'], 0, 4);
            $cargoreadinessdate = substr($get_po_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_po_data['CARGOREADINESSDATE'], 0, 4);
            $sender = $this->AdministrationModel->get_mailsender();
            $groupuser = 4;

            //Untuk Update Status Posting PO
            $data3 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'POSTINGSTAT' => 1,
                'OFFLINESTAT' => 0,
            );
            //inisiasi proses kirim ke group
            $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
            $mail_tmpl = $this->NotifModel->get_template($groupuser);
            foreach ($notiftouser_data as $sendto_user) :
                $var_email = array(
                    'TONAME' => $sendto_user['NAME'],
                    'FROMNAME' => $this->audtuser['NAMELGN'],
                    'CONTRACT' => $get_po_data['CONTRACT'],
                    'CTDESC' => $get_po_data['CTDESC'],
                    'PROJECT' => $get_po_data['PROJECT'],
                    'PRJDESC' => $get_po_data['PRJDESC'],
                    'CUSTOMER' => $get_po_data['CUSTOMER'],
                    'NAMECUST' => $get_po_data['NAMECUST'],
                    'PONUMBERCUST' => $get_po_data['PONUMBERCUST'],
                    'PODATECUST' => $crmpodate,
                    'CRMNO' => $get_po_data['CRMNO'],
                    'REQDATE' => $crmreqdate,
                    'ORDERDESC' => $get_po_data['ORDERDESC'],
                    'REMARKS' => $get_po_data['CRMREMARKS'],
                    'SALESCODE' => $get_po_data['MANAGER'],
                    'SALESPERSON' => $get_po_data['SALESNAME'],
                    'RQNDATE' => $rqndate,
                    'RQNNUMBER' => $get_po_data['RQNNUMBER'],
                    //DATA VARIABLE PO
                    'PODATE' => $povendordate,
                    'PONUMBER' => $get_po_data['PONUMBER'],
                    'ETDDATE' => $etddate,
                    'CARGOREADINESSDATE' => $cargoreadinessdate,
                    'ORIGINCOUNTRY' => $get_po_data['ORIGINCOUNTRY'],
                    'POREMARKS' => $get_po_data['POREMARKS'],
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
                    'MAILKEY' => $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                    'UNIQPROCESS' => $get_po_data['POUNIQ'],
                );

                //Check Duplicate Entry & Sending Mail
                $touser = trim($sendto_user['USERNAME']);
                $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_po_data['POUNIQ'], $touser);
                if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . $touser) {
                    session()->set('success', '-1');
                    return redirect()->to(base_url('/pobeforeetdnotice'));
                    session()->remove('success');
                } else if (empty($getmailuniq['MAILKEY'])) {
                    $post_email = $this->NotifModel->mailbox_insert($data_notif);
                    if ($post_email) {
                        $sending_mail = $this->send($data_email);
                    }
                }

            endforeach;

            $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
            session()->set('success', '9');
            return redirect()->to(base_url('/pobeforeetdnotice'));
            session()->remove('success');
        } else {
            session()->set('success', '-9');
            return redirect()->to(base_url('/pobeforeetdnotice'));
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
            return redirect()->to(base_url('/pobeforeetdnotice'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/pobeforeetdnotice'));
        }
    }
}
