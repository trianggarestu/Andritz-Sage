<?php

namespace App\Controllers;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Settingnavheader_model;

class Home2 extends BaseController
{
    private $nav_data;
    private $header_data;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->SettingnavheaderModel = new Settingnavheader_model();

        // Assign the model result to the badly named Class Property
        $activenavd = 'Settingnavheader';
        $this->nav_data = [
            'active_navd' => $activenavd,
            'menu_nav' => $this->AdministrationModel->get_navigation(),
            //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
            //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
            //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
        ];
    }

    public function index()
    {
        //return view('welcome_message');
        echo view('view_header');
        echo view('view_nav', $this->nav_data);
        echo view('home/view_homepage');
        echo view('view_footer');
    }
}
