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
use App\Models\Deliveryorders_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class ConfirmdnOrigin extends BaseController
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
        $this->DeliveryordersModel = new Deliveryorders_model();
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
                $activenavd = 'confirmdnorigin';
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
        $deliverydata = $this->DeliveryordersModel->get_shi_pending_to_dnorigin();

        $data = array(
            'delivery_data' => $deliverydata,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_shi_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('confirmdnorigin'));
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
        return redirect()->to(base_url('confirmdnorigin/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $deliverydata = $this->DeliveryordersModel->get_shi_pending_to_dnorigin();
        } else {
            $deliverydata = $this->DeliveryordersModel->get_shi_pending_to_dnorigin_search($keyword);
        }
        $data = array(
            'delivery_data' => $deliverydata,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_shi_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update($shiuniq, $postingstat)
    {
        session()->remove('success');
        $get_shi = $this->DeliveryordersModel->get_dn_by_id($shiuniq);
        $get_shi_l = $this->DeliveryordersModel->get_shi_l_by_id($shiuniq);
        if ($get_shi) {
            //if (!empty($get_shi['EDNFILENAME']) and empty($get_shi['DNSTATUS'])) {
            $shidate = substr($get_shi['SHIDATE'], 4, 2) . "/" . substr($get_shi['SHIDATE'], 6, 2) . "/" . substr($get_shi['SHIDATE'], 0, 4);
            $custrcpdate = substr($get_shi['CUSTRCPDATE'], 4, 2) . "/" . substr($get_shi['CUSTRCPDATE'], 6, 2) . "/" . substr($get_shi['CUSTRCPDATE'], 0, 4);
            $act = 'confirmdnorigin/update_action';
            if ($postingstat == 0) {
                $button = 'Save';
            } else {
                $button = 'Posting';
            }

            $ufmt_today = $this->audtuser['AUDTDATE'];
            $today = substr($ufmt_today, 4, 2) . "/" . substr($ufmt_today, 6, 2) . "/" .  substr($ufmt_today, 0, 4);

            if (empty($get_shi['ORIGDNRCPSLSDATE'])) {
                $origdnrcpslsdate = $today;
            } else {
                $origdnrcpslsdate = substr($get_shi['ORIGDNRCPSLSDATE'], 4, 2) . "/" . substr($get_shi['ORIGDNRCPSLSDATE'], 6, 2) . "/" .  substr($get_shi['ORIGDNRCPSLSDATE'], 0, 4);
            }


            $shiuniq = $get_shi['SHIUNIQ'];
            $data = array(
                'csruniq' => trim($get_shi['CSRUNIQ']),
                'shiuniq' => trim($get_shi['SHIUNIQ']),
                'docnumber' => trim($get_shi['DOCNUMBER']),
                'shinumber' => trim($get_shi['SHINUMBER']),
                'shidate' => $shidate,
                'custrcpdate' => $custrcpdate,
                'customer' => trim($get_shi['CUSTOMER']),
                'cust_name' => trim($get_shi['NAMECUST']),
                'ednfilename' => $get_shi['EDNFILENAME'],
                'ednfilepath' => $get_shi['EDNFILEPATH'],
                'shi_l' => $get_shi_l,
                'origdnrcpslsdate' => $origdnrcpslsdate,
                'dnstatus' => $get_shi['DNSTATUS'],
                'form_action' => base_url($act),
                'post_stat' => $postingstat,
                'button' => $button,
            );
            //}
            echo view('delivery/ajax_confirm_dnorigin', $data);
        }
    }


    public function update_action()
    {
        $shiuniq = $this->request->getPost('shiuniq');
        if (null == ($this->request->getPost('shiuniq'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('confirmdnorigin'));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $csruniq = $this->request->getPost('csruniq');
            $docnumber = $this->request->getPost('docnumber');
            $origdnrcpslsdate = $this->request->getPost('origdnrcpslsdate');
            $post_stat = $this->request->getPost('post_stat');
            $choose_shi = $this->DeliveryordersModel->get_dn_by_id($shiuniq);
            $n_origdnrcpslsdate = substr($origdnrcpslsdate, 6, 4) . substr($origdnrcpslsdate, 0, 2) . substr($origdnrcpslsdate, 3, 2);
            $groupuser = 8;
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'DNSTATUS' => $this->request->getPost('dnstatus'),
                'ORIGDNRCPSLSDATE' => $n_origdnrcpslsdate,
                'DNOTPROCESS' => $groupuser,
                'DNPOSTINGSTAT' => $post_stat,
                'DNOFFLINESTAT' => 1,
            );
            $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data1);

            if ($post_stat == 1) {
                $shi_to_ot = $this->DeliveryordersModel->get_shi_open_by_id($shiuniq, $csruniq);
                $sender = $this->AdministrationModel->get_mailsender();
                $data = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'ORIGDNRCPSLSDATE' => $n_origdnrcpslsdate,
                    'DNPOSTINGSTAT' => 1,
                    'DNOFFLINESTAT' => 1,

                );

                $dnorigin_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data1);
                if ($dnorigin_update) {
                    $get_shi = $this->DeliveryordersModel->get_delivery_post($csruniq, $shiuniq);
                    foreach ($shi_to_ot as $data_shil) :
                        $csruniq = $data_shil['CSRUNIQ'];
                        $csrluniq = $data_shil['CSRLUNIQ'];
                        $data2 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'DNSTATUS' => $this->request->getPost('dnstatus'),
                            'ORIGDNRCPSLSDATE' => $n_origdnrcpslsdate,
                        );

                        $this->DeliveryordersModel->ot_deliveryorders_update($csruniq, $csrluniq, $data2);
                    endforeach;

                    if ($sender['OFFLINESTAT'] == 0) {
                        $get_shi_data = $this->DeliveryordersModel->get_shijoincsr_by_shi($shiuniq);
                        //Untuk Update Status Posting CSR
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'DNPOSTINGSTAT' => 1,
                            'DNOFFLINESTAT' => 0,
                        );


                        $crmpodate = substr($get_shi_data['PODATECUST'], 4, 2) . "/" . substr($get_shi_data['PODATECUST'], 6, 2) . "/" .  substr($get_shi_data['PODATECUST'], 0, 4);
                        $crmreqdate = substr($get_shi_data['CRMREQDATE'], 4, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 6, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 0, 4);
                        $rqndate = substr($get_shi_data['RQNDATE'], 4, 2) . "/" . substr($get_shi_data['RQNDATE'], 6, 2) . "/" .  substr($get_shi_data['RQNDATE'], 0, 4);
                        $povendordate = substr($get_shi_data['PODATE'], 4, 2) . "/" . substr($get_shi_data['PODATE'], 6, 2) . "/" .  substr($get_shi_data['PODATE'], 0, 4);
                        $etddate = substr($get_shi_data['ETDDATE'], 4, 2) . "/" . substr($get_shi_data['ETDDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDDATE'], 0, 4);
                        $cargoreadinessdate = substr($get_shi_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_shi_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_shi_data['CARGOREADINESSDATE'], 0, 4);
                        $etdorigindate = substr($get_shi_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDORIGINDATE'], 0, 4);
                        $atdorigindate = substr($get_shi_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ATDORIGINDATE'], 0, 4);
                        $etaportdate = substr($get_shi_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_shi_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_shi_data['ETAPORTDATE'], 0, 4);
                        $pibdate = substr($get_shi_data['PIBDATE'], 4, 2) . "/" . substr($get_shi_data['PIBDATE'], 6, 2) . "/" .  substr($get_shi_data['PIBDATE'], 0, 4);
                        $shidate = substr($get_shi_data['SHIDATE'], 4, 2) . "/" . substr($get_shi_data['SHIDATE'], 6, 2) . "/" .  substr($get_shi_data['SHIDATE'], 0, 4);
                        $custrcpdate = substr($get_shi_data['CUSTRCPDATE'], 4, 2) . "/" . substr($get_shi_data['CUSTRCPDATE'], 6, 2) . "/" .  substr($get_shi_data['CUSTRCPDATE'], 0, 4);
                        $origdnrcpshidate = substr($get_shi_data['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSHIDATE'], 0, 4);
                        $origdnrcpslsdate = substr($get_shi_data['ORIGDNRCPSLSDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSLSDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSLSDATE'], 0, 4);
                        if ($get_shi_data['DNSTATUS'] == 1) {
                            $dnstatus = 'RECEIVED';
                        } else {
                            $dnstatus = NULL;
                        }

                        // Khusus untuk PROSES Delivery Note Model nya berbeda karena harus kirim ke customer juga
                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                        $mail_tmpl = $this->NotifModel->get_template($groupuser);

                        foreach ($notiftouser_data as $sendto_user) :
                            $var_email = array(
                                'TONAME' => $sendto_user['NAME'],
                                'FROMNAME' => $this->audtuser['NAMELGN'],
                                'CONTRACT' => $get_shi_data['CONTRACT'],
                                'CTDESC' => $get_shi_data['CTDESC'],
                                'PROJECT' => $get_shi_data['PROJECT'],
                                'PRJDESC' => $get_shi_data['PRJDESC'],
                                'CUSTOMER' => $get_shi_data['CUSTOMER'],
                                'NAMECUST' => $get_shi_data['NAMECUST'],
                                'EMAIL1CUST' => $get_shi_data['EMAIL1CUST'],
                                'PONUMBERCUST' => $get_shi_data['PONUMBERCUST'],
                                'PODATECUST' => $crmpodate,
                                'CRMNO' => $get_shi_data['CRMNO'],
                                'REQDATE' => $crmreqdate,
                                'ORDERDESC' => $get_shi_data['ORDERDESC'],
                                'REMARKS' => $get_shi_data['CRMREMARKS'],
                                'SALESCODE' => $get_shi_data['MANAGER'],
                                'SALESPERSON' => $get_shi_data['SALESNAME'],
                                'RQNDATE' => $rqndate,
                                'RQNNUMBER' => $get_shi_data['RQNNUMBER'],
                                //DATA VARIABLE PO
                                'PODATE' => $povendordate,
                                'PONUMBER' => $get_shi_data['PONUMBER'],
                                'ETDDATE' => $etddate,
                                'CARGOREADINESSDATE' => $cargoreadinessdate,
                                'ORIGINCOUNTRY' => $get_shi_data['ORIGINCOUNTRY'],
                                'POREMARKS' => $get_shi_data['POREMARKS'],
                                //DATA VARIABLE LOGISTICS
                                'ETDORIGINDATE' => $etdorigindate,
                                'ATDORIGINDATE' => $atdorigindate,
                                'ETAPORTDATE' => $etaportdate,
                                'PIBDATE' => $pibdate,
                                'VENDSHISTATUS' => $get_shi_data['VENDSHISTATUS'],
                                //DATA VARIABLE RECEIPTS
                                //'RECPNUMBER' => $get_shi_data['RECPNUMBER'],
                                //'RECPDATE' => $rcpdate,
                                //'VDNAME' => $get_shi_data['VDNAME'],
                                //'DESCRIPTIO' => $get_shi_data['DESCRIPTIO'],
                                //DATA VARIABLE SHIPMENTS
                                'DOCNUMBER' => $get_shi_data['DOCNUMBER'],
                                'SHINUMBER' => $get_shi_data['SHINUMBER'],
                                'SHIDATE' => $shidate,
                                'CUSTRCPDATE' => $custrcpdate,
                                'ORIGDNRCPSHIDATE' => $origdnrcpshidate,
                                //DATA VARIABLE SALESADMIN
                                'DNSTATUS' => $dnstatus,
                                'ORIGDNRCPSLSDATE' => $origdnrcpslsdate,

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
                                'MAILKEY' => $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                                'UNIQPROCESS' => $get_shi_data['SHIUNIQ'],
                            );

                            //Check Duplicate Entry & Sending Mail
                            $touser = trim($sendto_user['USERNAME']);
                            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_shi_data['SHIUNIQ'], $touser);
                            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . $touser) {
                                session()->set('success', '-1');
                                return redirect()->to(base_url('/confirmdnorigin'));
                                session()->remove('success');
                            } else if (empty($getmailuniq['MAILKEY'])) {
                                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                if ($post_email) {
                                    $sending_mail = $this->send($data_email);
                                }
                            }

                        endforeach;
                        $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data3);
                    }
                }
            }
            session()->set('success', '1');
            return redirect()->to(base_url('/confirmdnorigin'));
            session()->remove('success');
        }
    }


    public function sendnotif($shiuniq)
    {

        $groupuser = 8;
        $sender = $this->AdministrationModel->get_mailsender();
        $get_shi_data = $this->DeliveryordersModel->get_shijoincsr_by_shi($shiuniq);
        //Untuk Update Status Posting CSR
        $data3 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'DNPOSTINGSTAT' => 1,
            'DNOFFLINESTAT' => 0,
        );


        $crmpodate = substr($get_shi_data['PODATECUST'], 4, 2) . "/" . substr($get_shi_data['PODATECUST'], 6, 2) . "/" .  substr($get_shi_data['PODATECUST'], 0, 4);
        $crmreqdate = substr($get_shi_data['CRMREQDATE'], 4, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 6, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 0, 4);
        $rqndate = substr($get_shi_data['RQNDATE'], 4, 2) . "/" . substr($get_shi_data['RQNDATE'], 6, 2) . "/" .  substr($get_shi_data['RQNDATE'], 0, 4);
        $povendordate = substr($get_shi_data['PODATE'], 4, 2) . "/" . substr($get_shi_data['PODATE'], 6, 2) . "/" .  substr($get_shi_data['PODATE'], 0, 4);
        $etddate = substr($get_shi_data['ETDDATE'], 4, 2) . "/" . substr($get_shi_data['ETDDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDDATE'], 0, 4);
        $cargoreadinessdate = substr($get_shi_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_shi_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_shi_data['CARGOREADINESSDATE'], 0, 4);
        $etdorigindate = substr($get_shi_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDORIGINDATE'], 0, 4);
        $atdorigindate = substr($get_shi_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ATDORIGINDATE'], 0, 4);
        $etaportdate = substr($get_shi_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_shi_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_shi_data['ETAPORTDATE'], 0, 4);
        $pibdate = substr($get_shi_data['PIBDATE'], 4, 2) . "/" . substr($get_shi_data['PIBDATE'], 6, 2) . "/" .  substr($get_shi_data['PIBDATE'], 0, 4);
        $shidate = substr($get_shi_data['SHIDATE'], 4, 2) . "/" . substr($get_shi_data['SHIDATE'], 6, 2) . "/" .  substr($get_shi_data['SHIDATE'], 0, 4);
        $custrcpdate = substr($get_shi_data['CUSTRCPDATE'], 4, 2) . "/" . substr($get_shi_data['CUSTRCPDATE'], 6, 2) . "/" .  substr($get_shi_data['CUSTRCPDATE'], 0, 4);
        $origdnrcpshidate = substr($get_shi_data['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSHIDATE'], 0, 4);
        $origdnrcpslsdate = substr($get_shi_data['ORIGDNRCPSLSDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSLSDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSLSDATE'], 0, 4);

        if ($get_shi_data['DNSTATUS'] == 1) {
            $dnstatus = 'RECEIVED';
        } else {
            $dnstatus = NULL;
        }

        // Khusus untuk PROSES Delivery Note Model nya berbeda karena harus kirim ke customer juga
        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        $mail_tmpl = $this->NotifModel->get_template($groupuser);

        foreach ($notiftouser_data as $sendto_user) :
            $var_email = array(
                'TONAME' => $sendto_user['NAME'],
                'FROMNAME' => $this->audtuser['NAMELGN'],
                'CONTRACT' => $get_shi_data['CONTRACT'],
                'CTDESC' => $get_shi_data['CTDESC'],
                'PROJECT' => $get_shi_data['PROJECT'],
                'PRJDESC' => $get_shi_data['PRJDESC'],
                'CUSTOMER' => $get_shi_data['CUSTOMER'],
                'NAMECUST' => $get_shi_data['NAMECUST'],
                'EMAIL1CUST' => $get_shi_data['EMAIL1CUST'],
                'PONUMBERCUST' => $get_shi_data['PONUMBERCUST'],
                'PODATECUST' => $crmpodate,
                'CRMNO' => $get_shi_data['CRMNO'],
                'REQDATE' => $crmreqdate,
                'ORDERDESC' => $get_shi_data['ORDERDESC'],
                'REMARKS' => $get_shi_data['CRMREMARKS'],
                'SALESCODE' => $get_shi_data['MANAGER'],
                'SALESPERSON' => $get_shi_data['SALESNAME'],
                'RQNDATE' => $rqndate,
                'RQNNUMBER' => $get_shi_data['RQNNUMBER'],
                //DATA VARIABLE PO
                'PODATE' => $povendordate,
                'PONUMBER' => $get_shi_data['PONUMBER'],
                'ETDDATE' => $etddate,
                'CARGOREADINESSDATE' => $cargoreadinessdate,
                'ORIGINCOUNTRY' => $get_shi_data['ORIGINCOUNTRY'],
                'POREMARKS' => $get_shi_data['POREMARKS'],
                //DATA VARIABLE LOGISTICS
                'ETDORIGINDATE' => $etdorigindate,
                'ATDORIGINDATE' => $atdorigindate,
                'ETAPORTDATE' => $etaportdate,
                'PIBDATE' => $pibdate,
                'VENDSHISTATUS' => $get_shi_data['VENDSHISTATUS'],
                //DATA VARIABLE RECEIPTS
                //'RECPNUMBER' => $get_shi_data['RECPNUMBER'],
                //'RECPDATE' => $rcpdate,
                //'VDNAME' => $get_shi_data['VDNAME'],
                //'DESCRIPTIO' => $get_shi_data['DESCRIPTIO'],
                //DATA VARIABLE SHIPMENTS
                'DOCNUMBER' => $get_shi_data['DOCNUMBER'],
                'SHINUMBER' => $get_shi_data['SHINUMBER'],
                'SHIDATE' => $shidate,
                'CUSTRCPDATE' => $custrcpdate,
                'ORIGDNRCPSHIDATE' => $origdnrcpshidate,
                //DATA VARIABLE SALES ADMIN
                'DNSTATUS' => $dnstatus,
                'ORIGDNRCPSLSDATE' => $origdnrcpslsdate,

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
                'MAILKEY' => $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                'UNIQPROCESS' => $get_shi_data['SHIUNIQ'],
            );

            //Check Duplicate Entry & Sending Mail
            $touser = trim($sendto_user['USERNAME']);
            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_shi_data['SHIUNIQ'], $touser);
            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . $touser) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/confirmdnorigin'));
                session()->remove('success');
            } else if (empty($getmailuniq['MAILKEY'])) {
                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                if ($post_email) {
                    $sending_mail = $this->send($data_email);
                }
            }

        endforeach;
        $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data3);


        session()->set('success', '1');
        return redirect()->to(base_url('/confirmdnorigin'));
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
        $chksmtpauth           = $data_email['smtpauth'];
        $ssl                = $data_email['ssl'];
        $smtpport           = $data_email['smtpport'];
        $to                 = $data_email['to_email'];
        $subject             = $data_email['subject'];
        $message             = $data_email['message'];
        if ($data_email['smtpauth'] == 1) {
            $smtpauth = 'TRUE';
        } else {
            $smtpauth = 'FALSE';
        }
        $attachment_filepath = '';
        $attachment_filename = '';

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $hostname;
            $mail->SMTPAuth   = true;
            $mail->Username   = $senderemail; // silahkan ganti dengan alamat email Anda
            if ($chksmtpauth == TRUE) :
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
            if (!empty($attachment_filename)) {
                $mail->AddAttachment($attachment_filepath, $attachment_filename);   // I took this from the phpmailer example on github but I'm not sure if I have it right.      
            }
            $mail->send();
            session()->setFlashdata('success', 'Send Email successfully');
            return redirect()->to(base_url('/confirmdnorigin'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/confirmdnorigin'));
        }
    }
}
