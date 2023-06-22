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
//use App\Models\Settingnavheader_model;
use App\Models\Notif_model;
use App\Models\Requisition_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class Requisition extends BaseController
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
        $this->RequisitionModel = new Requisition_model();
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
                $activenavd = 'requisition';
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
                    'AUDTUSER' => $infouser['usernamelgn'],
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
        session()->remove('rqnnumber_all');
        session()->remove('rqndate_all');
        session()->remove('rqndate_disp_all');

        $requisitiondata = $this->RequisitionModel->get_requisition_open();
        $so_l_open_data = $this->RequisitionModel->get_csrl_list_post();

        $data = array(
            'requisition_data' => $requisitiondata,
            'so_l_data' => $so_l_open_data,
            'keyword' => '',
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_so_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('requisition'));
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
        return redirect()->to(base_url('requisition/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $requisitiondata = $this->RequisitionModel->get_requisition_open();
            $so_l_open_data = $this->RequisitionModel->get_csrl_list_post();
        } else {
            $requisitiondata = $this->RequisitionModel->get_requisition_open_search($keyword);
            $so_l_open_data = $this->RequisitionModel->get_csrl_list_post();
        }
        $data = array(
            'requisition_data' => $requisitiondata,
            'so_l_data' => $so_l_open_data,
            'keyword' => $keyword,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_so_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($id_so, $postingstat)
    {
        $get_so = $this->RequisitionModel->get_so_by_id($id_so);
        $get_so_l = $this->RequisitionModel->get_so_l_by_id($id_so);
        $get_pr = $this->RequisitionModel->get_requisition_by_so($id_so);
        if ($get_so) {
            if (!empty($get_pr['CSRUNIQ']) and $get_pr['POSTINGSTAT'] == 0) {
                $act = 'requisition/update_action';
                if ($postingstat == 0) {
                    $button = 'Update & Save';
                } else {
                    $button = 'Update & Posting';
                }
                $id_pr = $get_pr['RQNUNIQ'];
                $rqnnumber = $get_pr['RQNNUMBER'];
                $rqndate = $get_pr['RQNDATE'];
                $rqnndate_disp = substr($get_pr['RQNDATE'], 4, 2) . "/" . substr($get_pr['RQNDATE'], 6, 2) . "/" . substr($get_pr['RQNDATE'], 0, 4);
                $podatecust = substr($get_so['PODATECUST'], 4, 2) . "/" . substr($get_so['PODATECUST'], 6, 2) . "/" . substr($get_so['PODATECUST'], 0, 4);
                $crmreqdate = substr($get_so['CRMREQDATE'], 4, 2) . "/" . substr($get_so['CRMREQDATE'], 6, 2) . "/" . substr($get_so['CRMREQDATE'], 0, 4);
            } else {
                $act = 'requisition/insert_action';
                if ($postingstat == 0) {
                    $button = 'Save';
                } else {
                    $button = 'Save & Posting';
                }
                $id_pr = '';
                $rqnnumber = '';
                $rqndate = '';
                $rqnndate_disp = '';
                $podatecust = substr($get_so['PODATECUST'], 4, 2) . "/" . substr($get_so['PODATECUST'], 6, 2) . "/" . substr($get_so['PODATECUST'], 0, 4);
                $crmreqdate = substr($get_so['CRMREQDATE'], 4, 2) . "/" . substr($get_so['CRMREQDATE'], 6, 2) . "/" . substr($get_so['CRMREQDATE'], 0, 4);
            }
            $contract = $get_so['CONTRACT'];
            $data = array(
                'csruniq' => $id_so,
                'csropen_data' => $get_so,
                'csrlopen_data' => $get_so_l,
                'form_action' => base_url($act),
                'button' => $button,
                'post_stat' => $postingstat,
                'id_pr' => $id_pr,
                'rqn_number' => $rqnnumber,
                'rqn_date' => $rqndate,
                'rqn_date_disp' => $rqnndate_disp,
            );
        }



        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_rqn_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    function form_select_rqn_all($csruniq, $post_stat)
    {


        $data = array(
            'csruniq' => $csruniq,
            'csrluniq' => '',
            'post_stat' => $post_stat,
            'requisition_list' => $this->RequisitionModel->get_requisition_sage(),
            'form_action' => base_url('requisition/çhooserqn'),
        );
        echo view('requisition/ajax_add_requisition', $data);
    }

    /*function form_select_rqn_det($csruniq, $csrluniq, $post_stat)
    {
        session()->remove("rqnnumber_$csruniq$csrluniq");
        session()->remove("rqndate_$csruniq$csrluniq");
        session()->remove("rqndate_disp_$csruniq$csrluniq");
        $data = array(
            'csruniq' => $csruniq,
            'csrluniq' => $csrluniq,
            'post_stat' => $post_stat,
            'requisition_list' => $this->RequisitionModel->get_requisition_sage(),
            'form_action' => base_url('requisition/çhooserqndet'),
        );
        echo view('requisition/ajax_add_requisition', $data);
    }*/

    public function çhooserqn()
    {
        session()->remove('success');
        if (null == ($this->request->getPost('csruniq'))) {
            $csruniq = "contract not found";
        } else {
            $csruniq = $this->request->getPost('csruniq');
            $postingstat = $this->request->getPost('postingstat');
            $rqnnumber = $this->request->getPost('rqnnumber');
            $rqn_data = $this->RequisitionModel->get_requisition_by_id($rqnnumber);
            $rqndate = substr($rqn_data['RQNDATE'], 6, 2) . "/" . substr($rqn_data['RQNDATE'], 4, 2) . "/" . substr($rqn_data['RQNDATE'], 0, 4);
            session()->set('rqnnumber_all', trim($rqn_data['RQNNUMBER']));
            session()->set('rqndate_all', trim($rqn_data['RQNDATE']));
            session()->set('rqndate_disp_all', trim($rqndate));
        }

        return redirect()->to(base_url('requisition/update/' . $csruniq . '/' . $postingstat));
    }

    /*public function çhooserqndet()
    {
        session()->remove('success');
        if (null == ($this->request->getPost('csruniq'))) {
            $csruniq = "contract not found";
        } else {
            $csruniq = $this->request->getPost('csruniq');
            $csrluniq = $this->request->getPost('csrluniq');
            $postingstat = $this->request->getPost('postingstat');
            $rqnnumber = $this->request->getPost('rqnnumber');
            $rqn_data = $this->RequisitionModel->get_requisition_by_id($rqnnumber);
            $rqndate = substr($rqn_data['RQNDATE'], 6, 2) . "/" . substr($rqn_data['RQNDATE'], 4, 2) . "/" . substr($rqn_data['RQNDATE'], 0, 4);
            session()->set("rqnnumber_$csruniq$csrluniq", trim($rqn_data['RQNNUMBER']));
            session()->set("rqndate_$csruniq$csrluniq", trim($rqn_data['RQNDATE']));
            session()->set("rqndate_disp_$csruniq$csrluniq", trim($rqndate));
        }

        return redirect()->to(base_url('requisition/update/' . $csruniq . '/' . $postingstat));
    }
    */


    public function resetrqn($csruniq, $post_stat)
    {
        session()->remove('rqnnumber_all');
        session()->remove('rqndate_all');
        session()->remove('rqndate_disp_all');
        return redirect()->to(base_url('requisition/update/' . $csruniq . '/' . $post_stat));
    }

    public function insert_action()
    {
        $id_so = $this->request->getPost('csruniq');
        $post_stat = $this->request->getPost('post_stat');
        //$rqnuniq = $this->request->getPost('id_pr');
        if (null == ($this->request->getPost('csruniq'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('requisition/update/' . $id_so . '/' . $post_stat));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $rqnnumber = $this->request->getPost('rqnnumber_all');
            $post_stat = $this->request->getPost('post_stat');
            $get_so = $this->RequisitionModel->get_so_detail_by_id($id_so);
            $choose_rqn = $this->RequisitionModel->get_requisition_by_id($rqnnumber);

            $groupuser = 3;
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'RQNKEY' => $id_so . '-' . $rqnnumber,
                'CSRUNIQ' => $id_so,
                'RQNDATE' => $choose_rqn['RQNDATE'],
                'RQNNUMBER' => $choose_rqn["RQNNUMBER"],
                'RQNREMARKS' => '',
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => $sender['OFFLINESTAT'],
            );
            $getrqnuniq = $this->RequisitionModel->get_rqnuniq_open($id_so, $rqnnumber);
            if (empty($getrqnuniq['RQNKEY'])) {
                $this->RequisitionModel->requisition_insert($data1);
                session()->set('success', '1');
                return redirect()->to(base_url('/requisition'));
                session()->remove('success');
            }
            if (!empty($getrqnuniq['RQNKEY']) and $getrqnuniq['RQNKEY'] == $id_so . '-' . $rqnnumber) {
                session()->set('success', '-1');
                return redirect()->to(base_url('requisition/update/' . $id_so . '/' . $post_stat));
                session()->remove('success');
            }

            if ($post_stat == 1) {
                $get_rqn_data = $this->RequisitionModel->get_rqnjoincsr_by_so($id_so);
                //Date for Variable Email
                $crmpodate = substr($get_rqn_data['PODATECUST'], 4, 2) . "/" . substr($get_rqn_data['PODATECUST'], 6, 2) . "/" .  substr($get_rqn_data['PODATECUST'], 0, 4);
                $crmreqdate = substr($get_rqn_data['CRMREQDATE'], 4, 2) . '/' . substr($get_rqn_data['CRMREQDATE'], 6, 2) . '/' . substr($get_rqn_data['CRMREQDATE'], 0, 4);
                $rqndate = substr($get_rqn_data['RQNDATE'], 4, 2) . "/" . substr($get_rqn_data['RQNDATE'], 6, 2) . "/" .  substr($get_rqn_data['RQNDATE'], 0, 4);
                $pocust_date = date_create(substr($get_so['PODATECUST'], 4, 2) . "/" . substr($get_so['PODATECUST'], 6, 2) . "/" .  substr($get_so['PODATECUST'], 0, 4));
                $requisitiondate = date_create(substr($get_rqn_data['RQNDATE'], 4, 2) . "/" . substr($get_rqn_data['RQNDATE'], 6, 2) . "/" .  substr($get_rqn_data['RQNDATE'], 0, 4));
                $pocusttoprdiff = date_diff($requisitiondate, $pocust_date);
                $pocusttoprdiff = $pocusttoprdiff->format("%a");
                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'RQNNUMBER' => $choose_rqn['RQNNUMBER'],
                    'RQNDATE' => $choose_rqn['RQNDATE'],
                    'POCUSTTOPRDAYS' => $pocusttoprdiff,
                );

                $this->RequisitionModel->ot_requisition_update($id_so, $data2);

                if ($sender['OFFLINESTAT'] == 0) {
                    //Untuk Update Status Posting REQUISITION
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
                            'CONTRACT' => $get_rqn_data['CONTRACT'],
                            'CTDESC' => $get_rqn_data['CTDESC'],
                            'PROJECT' => $get_rqn_data['PROJECT'],
                            'PRJDESC' => $get_rqn_data['PRJDESC'],
                            'CUSTOMER' => $get_rqn_data['CUSTOMER'],
                            'NAMECUST' => $get_rqn_data['NAMECUST'],
                            'PONUMBERCUST' => $get_rqn_data['PONUMBERCUST'],
                            'PODATECUST' => $crmpodate,
                            'CRMNO' => $get_rqn_data['CRMNO'],
                            'REQDATE' => $crmreqdate,
                            'ORDERDESC' => $get_rqn_data['ORDERDESC'],
                            'REMARKS' => $get_rqn_data['CRMREMARKS'],
                            'SALESCODE' => $get_rqn_data['MANAGER'],
                            'SALESPERSON' => $get_rqn_data['SALESNAME'],
                            'RQNDATE' => $rqndate,
                            'RQNNUMBER' => $get_rqn_data['RQNNUMBER'],
                        );
                        $subject = $mail_tmpl['SUBJECT_MAIL'];
                        $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                        $data_email = array(
                            'hostname'       => $sender['HOSTNAME'],
                            'sendername'       => $sender['SENDERNAME'],
                            'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                            'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                            'ssl'       => $sender['SSL'],
                            'smtpport'       => $sender['SMTPPORT'],
                            'to_email' => $sendto_user['EMAIL'],
                            'subject' =>  $subject,
                            'message' => $message,
                        );


                        $data_notif = array(
                            'MAILKEY' => $groupuser . '-' . $get_rqn_data['RQNUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                            'UNIQPROCESS' => $get_rqn_data['RQNUNIQ'],
                        );

                        //Check Duplicate Entry & Sending Mail
                        $touser = trim($sendto_user['USERNAME']);
                        $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_rqn_data['RQNUNIQ'], $touser);
                        if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_rqn_data['RQNUNIQ'] . '-' . $touser) {
                            session()->set('success', '-1');
                            return redirect()->to(base_url('/requisition'));
                            session()->remove('success');
                        } else if (empty($getmailuniq['MAILKEY'])) {
                            $post_email = $this->NotifModel->mailbox_insert($data_notif);
                            if ($post_email) {
                                $sending_mail = $this->send($data_email);
                            }
                        }

                    endforeach;

                    $this->RequisitionModel->rqn_post_update($get_rqn_data['RQNUNIQ'], $data3);
                    session()->set('success', '1');
                    return redirect()->to(base_url('/requisitionlist'));
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
                    $this->RequisitionModel->rqn_post_update($get_rqn_data['RQNUNIQ'], $data3);
                    //session()->setFlashdata('messageerror', 'Create Record Failed');
                    session()->set('success', '1');
                    return redirect()->to(base_url('/requisitionlist'));
                    session()->remove('success');
                }
            }
            session()->set('success', '1');
            return redirect()->to(base_url('/requisition'));
            session()->remove('success');
        }
    }


    public function update_action()
    {
        $id_so = $this->request->getPost('csruniq');
        $post_stat = $this->request->getPost('post_stat');
        $rqnuniq = $this->request->getPost('rqnuniq');
        if (null == ($this->request->getPost('csruniq'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('requisition'));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $rqnnumber = $this->request->getPost('rqnnumber_all');
            $post_stat = $this->request->getPost('post_stat');
            $get_so = $this->RequisitionModel->get_so_by_id($id_so);
            $choose_rqn = $this->RequisitionModel->get_requisition_by_id($rqnnumber);

            $groupuser = 3;
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'RQNKEY' => $id_so . '-' . $rqnnumber,
                'CSRUNIQ' => $id_so,
                'RQNDATE' => $choose_rqn['RQNDATE'],
                'RQNNUMBER' => $choose_rqn["RQNNUMBER"],
                'RQNREMARKS' => '',
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => $sender['OFFLINESTAT'],
            );
            $this->RequisitionModel->requisition_update($rqnuniq, $data1);

            if ($post_stat == 1) {
                $get_rqn_data = $this->RequisitionModel->get_rqnjoincsr_by_so($id_so);
                //Date for Variable Email
                $crmpodate = substr($get_rqn_data['PODATECUST'], 4, 2) . "/" . substr($get_rqn_data['PODATECUST'], 6, 2) . "/" .  substr($get_rqn_data['PODATECUST'], 0, 4);
                $crmreqdate = substr($get_rqn_data['CRMREQDATE'], 4, 2) . '/' . substr($get_rqn_data['CRMREQDATE'], 6, 2) . '/' . substr($get_rqn_data['CRMREQDATE'], 0, 4);
                $rqndate = substr($get_rqn_data['RQNDATE'], 4, 2) . "/" . substr($get_rqn_data['RQNDATE'], 6, 2) . "/" .  substr($get_rqn_data['RQNDATE'], 0, 4);
                $pocust_date = date_create(substr($get_so['PODATECUST'], 4, 2) . "/" . substr($get_so['PODATECUST'], 6, 2) . "/" .  substr($get_so['PODATECUST'], 0, 4));
                $requisitiondate = date_create(substr($get_rqn_data['RQNDATE'], 4, 2) . "/" . substr($get_rqn_data['RQNDATE'], 6, 2) . "/" .  substr($get_rqn_data['RQNDATE'], 0, 4));
                $pocusttoprdiff = date_diff($requisitiondate, $pocust_date);
                $pocusttoprdiff = $pocusttoprdiff->format("%a");
                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'RQNNUMBER' => $choose_rqn['RQNNUMBER'],
                    'RQNDATE' => $choose_rqn['RQNDATE'],
                    'POCUSTTOPRDAYS' => $pocusttoprdiff,
                );

                $this->RequisitionModel->ot_requisition_update($id_so, $data2);

                if ($sender['OFFLINESTAT'] == 0) {
                    //Untuk Update Status Posting REQUISITION
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
                            'CONTRACT' => $get_rqn_data['CONTRACT'],
                            'CTDESC' => $get_rqn_data['CTDESC'],
                            'PROJECT' => $get_rqn_data['PROJECT'],
                            'PRJDESC' => $get_rqn_data['PRJDESC'],
                            'CUSTOMER' => $get_rqn_data['CUSTOMER'],
                            'NAMECUST' => $get_rqn_data['NAMECUST'],
                            'PONUMBERCUST' => $get_rqn_data['PONUMBERCUST'],
                            'PODATECUST' => $crmpodate,
                            'CRMNO' => $get_rqn_data['CRMNO'],
                            'REQDATE' => $crmreqdate,
                            'ORDERDESC' => $get_rqn_data['ORDERDESC'],
                            'REMARKS' => $get_rqn_data['CRMREMARKS'],
                            'SALESCODE' => $get_rqn_data['MANAGER'],
                            'SALESPERSON' => $get_rqn_data['SALESNAME'],
                            'RQNDATE' => $rqndate,
                            'RQNNUMBER' => $get_rqn_data['RQNNUMBER'],
                        );
                        $subject = $mail_tmpl['SUBJECT_MAIL'];
                        $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                        $data_email = array(
                            'hostname'       => $sender['HOSTNAME'],
                            'sendername'       => $sender['SENDERNAME'],
                            'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                            'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                            'ssl'       => $sender['SSL'],
                            'smtpport'       => $sender['SMTPPORT'],
                            'to_email' => $sendto_user['EMAIL'],
                            'subject' =>  $subject,
                            'message' => $message,
                        );


                        $data_notif = array(
                            'MAILKEY' => $groupuser . '-' . $get_rqn_data['RQNUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                            'UNIQPROCESS' => $get_rqn_data['RQNUNIQ'],
                        );

                        //Check Duplicate Entry & Sending Mail
                        $touser = trim($sendto_user['USERNAME']);
                        $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_rqn_data['RQNUNIQ'], $touser);
                        if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_rqn_data['RQNUNIQ'] . '-' . $touser) {
                            session()->set('success', '-1');
                            return redirect()->to(base_url('/requisition'));
                            session()->remove('success');
                        } else if (empty($getmailuniq['MAILKEY'])) {
                            $post_email = $this->NotifModel->mailbox_insert($data_notif);
                            if ($post_email) {
                                $sending_mail = $this->send($data_email);
                            }
                        }

                    endforeach;

                    $this->RequisitionModel->rqn_post_update($get_rqn_data['RQNUNIQ'], $data3);
                    session()->set('success', '1');
                    return redirect()->to(base_url('/requisitionlist'));
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
                    $this->RequisitionModel->rqn_post_update($get_rqn_data['RQNUNIQ'], $data3);
                    //session()->setFlashdata('messageerror', 'Create Record Failed');
                    session()->set('success', '1');
                    return redirect()->to(base_url('/requisitionlist'));
                    session()->remove('success');
                }
            }

            session()->set('success', '1');
            return redirect()->to(base_url('/requisition'));
            session()->remove('success');
        }
    }


    public function sendnotif($rqnuniq)
    {

        $getreq = $this->RequisitionModel->get_requisition_post($rqnuniq);
        $sender = $this->AdministrationModel->get_mailsender();
        //Date for Variable Email
        $crmpodate = substr($getreq['PODATECUST'], 4, 2) . "/" . substr($getreq['PODATECUST'], 6, 2) . "/" .  substr($getreq['PODATECUST'], 0, 4);
        $crmreqdate = substr($getreq['CRMREQDATE'], 4, 2) . '/' . substr($getreq['CRMREQDATE'], 6, 2) . '/' . substr($getreq['CRMREQDATE'], 0, 4);
        $rqndate = substr($getreq['RQNDATE'], 4, 2) . "/" . substr($getreq['RQNDATE'], 6, 2) . "/" .  substr($getreq['RQNDATE'], 0, 4);
        $groupuser = 3;

        //inisiasi proses kirim ke group
        $data2 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'OFFLINESTAT' => 0,
        );
        //inisiasi proses kirim ke group
        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        $mail_tmpl = $this->NotifModel->get_template($groupuser);
        foreach ($notiftouser_data as $sendto_user) :
            $var_email = array(
                'TONAME' => $sendto_user['NAME'],
                'FROMNAME' => $this->audtuser['NAMELGN'],
                'CONTRACT' => $getreq['CONTRACT'],
                'CTDESC' => $getreq['CTDESC'],
                'PROJECT' => $getreq['PROJECT'],
                'PRJDESC' => $getreq['PRJDESC'],
                'CUSTOMER' => $getreq['CUSTOMER'],
                'NAMECUST' => $getreq['NAMECUST'],
                'PONUMBERCUST' => $getreq['PONUMBERCUST'],
                'PODATECUST' => $crmpodate,
                'CRMNO' => $getreq['CRMNO'],
                'REQDATE' => $crmreqdate,
                'ORDERDESC' => $getreq['ORDERDESC'],
                'REMARKS' => $getreq['CRMREMARKS'],
                'SALESCODE' => $getreq['MANAGER'],
                'SALESPERSON' => $getreq['SALESNAME'],
                'RQNDATE' => $rqndate,
                'RQNNUMBER' => $getreq['RQNNUMBER'],
            );
            $subject = $mail_tmpl['SUBJECT_MAIL'];
            $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' =>  $subject,
                'message' => $message,
            );


            $data_notif = array(
                'MAILKEY' => $groupuser . '-' . $getreq['RQNUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                'UNIQPROCESS' => $getreq['RQNUNIQ'],
            );

            //Check Duplicate Entry & Sending Mail
            $touser = trim($sendto_user['USERNAME']);
            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $getreq['RQNUNIQ'], $touser);
            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $getreq['RQNUNIQ'] . '-' . $touser) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/requisitionlist'));
                session()->remove('success');
            } else if (empty($getmailuniq['MAILKEY'])) {
                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                if ($post_email) {
                    $sending_mail = $this->send($data_email);
                }
            }

        endforeach;

        $this->RequisitionModel->rqn_post_update($getreq['RQNUNIQ'], $data2);
        session()->set('success', '1');
        return redirect()->to(base_url('/requisitionlist'));
        session()->remove('success');
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
            return redirect()->to(base_url('/requisition'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/requisition'));
        }
    }
}
