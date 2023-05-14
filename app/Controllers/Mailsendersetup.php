<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Setup_model;
//use App\Models\Settingnavheader_model;


class Mailsendersetup extends BaseController
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
        $this->SetupModel = new Setup_model();
        //$this->SettingnavheaderModel = new Settingnavheader_model();
        if (empty(session()->get('keylog'))) {
            //Tidak Bisa menggunakan redirect kalau condition session di construct
            //return redirect()->to(base_url('/login'));
            header('Location: ' . base_url());
            exit();
        } else {
            $user = session()->get('username');
            $infouser = $this->LoginModel->datapengguna($user);
            $chksu = $this->LoginModel->datalevel($user);
            if ($chksu == 0) {
                //header("HTTP/1.1 401 Unauthorized"); //Status Code 401 = Unauthorized
                header('Location: ' . base_url('administration'));
                exit();
            } else if (session()->get('keylog') != $infouser['passlgn']) {
                header('Location: ' . base_url('administration'));
                exit();
            } else {



                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'notif_messages' => $mailbox_unread,
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavh = 'mailsetup';
                $activenavd = 'mailsendersetup';

                $this->nav_data = [
                    'active_navh' => $activenavh,
                    'active_navd' => $activenavd,
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

                ];
            }
        }
    }


    public function index()
    {
        $mailsender_data = $this->SetupModel->get_mailsender();
        $nav['act_tab'] = 1;
        $data = array(
            'mailsender_data' => $mailsender_data,
            'form_action' => base_url("mailsendersetup/update_mailsender"),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/mailsender_tab_menu', $nav);
        echo view('settings/mailsender_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function notifsetup()
    {
        $mailsender_data = $this->SetupModel->get_mailsender();
        $nav['act_tab'] = 2;
        $data = array(
            'mailsender_data' => $mailsender_data,
            'form_action' => base_url("mailsendersetup/update_offlinestat"),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/mailsender_tab_menu', $nav);
        echo view('settings/mailsender_notifsetup', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update_mailsender()
    {
        if (!$this->validate([
            'sender_hostname' => 'required|valid_url',
            'sender_name' => 'required|alpha_space',
            'sender_email' => 'required|valid_email',
            'sender_password' => 'required',
            'sender_ssl' => 'required|alpha',
            'sender_smtpport' => 'required|numeric',

        ])) {
            $id = $this->request->getPost('id');
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');
            return redirect()->to(base_url('/mailsendersetup'))->withInput();
        } else {

            $id = $this->request->getPost('id');
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'HOSTNAME' => $this->request->getPost('sender_hostname'),
                'SENDERNAME' => $this->request->getPost('sender_name'),
                'SENDEREMAIL' => $this->request->getPost('sender_email'),
                'PASSWORDEMAIL' => $this->request->getPost('sender_password'),
                'SSL' => $this->request->getPost('sender_ssl'),
                'SMTPPORT' => $this->request->getPost('sender_smtpport'),
            );


            $this->SetupModel->mailsender_update($id, $data);
            session()->setFlashdata('messagesuccess', 'Update Data Success');
            return redirect()->to(base_url('/mailsendersetup'));
            //print_r($data);
        }
    }

    public function update_offlinestat()
    {
        if (!$this->validate([
            'offlinestat' => 'required',

        ])) {
            return redirect()->to(base_url('/mailsendersetup/notifsetup'))->withInput();
            session()->setFlashdata('messageerror', 'Update Data Failed');
        } else {
            $id = $this->request->getPost('id');
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'OFFLINESTAT' => $this->request->getPost('offlinestat'),
            );

            $this->SetupModel->mailsender_update($id, $data);
            session()->setFlashdata('messagesuccess', 'Update Data Success');
            return redirect()->to(base_url('/mailsendersetup/notifsetup'));
        }
    }
}
