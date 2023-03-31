<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
//use App\Models\Settingnavheader_model;


class Administration extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        //$this->SettingnavheaderModel = new Settingnavheader_model();
        if (empty(session()->get('keylog'))) {
            //Tidak Bisa menggunakan redirect kalau condition session di construct
            //return redirect()->to(base_url('/login'));
            header('Location: ' . base_url());
            exit();
        } else {
            $user = session()->get('username');
            /*$chksu = $this->LoginModel->datalevel($user);
            if ($chksu == 0) {
                redirect('administration');
            } else {
                */
            $infouser = $this->LoginModel->datapengguna($user);
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
            $activenavd = 'Administration';
            $this->nav_data = [
                'active_navd' => $activenavd,
                'menu_nav' => $this->AdministrationModel->get_navigation(),
                //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
                //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
                //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
            ];
            //}
        }
    }


    public function index()
    {

        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        //$activenavd='';
        $data['chklgn'] = $keylog;
        $data['pengguna'] = $this->header_data;

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('home/view_homepage', $data);
        echo view('view_footer', $this->footer_data);
    }
}
