<?php

namespace App\Controllers;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Settingnavheader_model;

//use App\Controllers\AdminController;

class SalesOrdercad extends BaseController
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
            $this->header_data = [
                'usernamelgn'   => $infouser['usernamelgn'],
                'namalgn' => $infouser['namalgn'],
                'emaillgn' => $infouser['emaillgn'],
                'issuperuserlgn' => $infouser['issuperuserlgn'],
            ];
            $this->footer_data = [
                'usernamelgn'   => $infouser['usernamelgn'],
            ];
            // Assign the model result to the badly named Class Property
            $activenavd = 'salesordercad';
            $activenavh = $this->AdministrationModel->get_activenavh($activenavd);
            $this->nav_data = [
                'active_navd' => $activenavd,
                'active_navh' => $activenavh,
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
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form2');
        echo view('view_footer', $this->footer_data);
    }

    public function details()
    {
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_details');
        echo view('view_footer', $this->footer_data);
    }

    public function soview($i)
    {
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_view');
        echo view('view_footer', $this->footer_data);
    }
}
