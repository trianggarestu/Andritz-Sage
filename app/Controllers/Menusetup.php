<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Menusetup_model;
//use App\Models\Settingnavheader_model;


class Menusetup extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $db;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->MenusetupModel = new Menusetup_model();
        $this->db = \Config\Database::connect();
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
            $activenavd = 'menusetup';
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
        $menuh_data = $this->MenusetupModel->get_menuheader();
        $data = array(
            'menuheader_data' => $menuh_data,
            'audtorg' => $this->db->database,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/menuheader_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function sub($idnav)
    {
        $submenu_data = $this->MenusetupModel->get_submenu($idnav);
        $data = array(
            'submenu_data' => $submenu_data,
            'audtorg' => $this->db->database,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/submenu_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function form($id = '')
    {
        $data['list_icon'] = $this->MenusetupModel->list_icon();
        if ($id) {
            $data['menuh'] = $this->MenusetupModel->get_data($id);
            $data['form_action'] = base_url("menusetup/save_menuh");
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/menu_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function subform($id = '')
    {
        $data['list_icon'] = $this->MenusetupModel->list_icon();
        if ($id) {
            $data['submenu'] = $this->MenusetupModel->get_submenu_data($id);
            $data['form_action'] = base_url("menusetup/save_submenu");
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/submenu_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function save_menuh()
    {
        if (!$this->validate([
            'navname' => 'required',
            'comment' => 'required',
            'icon' => 'required',

        ])) {
            $idnav = $this->request->getPost('idnav');
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');

            return redirect()->to(base_url('/menusetup/form/' . $idnav))->withInput();
        } else {
            $today = date("d/m/Y");
            $audtdate = substr($today, 6, 4) . "" . substr($today, 3, 2) . "" . substr($today, 0, 2);
            $idnav = $this->request->getPost('idnav');
            $data = array(
                'NAVL1NAME' => $this->request->getPost('navname'),
                'COMMENT' => $this->request->getPost('comment'),
                'ICONDESC' => $this->request->getPost('icon'),
                'NAVL1SORTING' => $this->request->getPost('navsorting'),
                'INACTIVE' => 0,
            );

            //print_r($data_notif);
            $this->MenusetupModel->menuh_update($idnav, $data);
            session()->setFlashdata('messagesuccess', 'Update Data Success');
            return redirect()->to(base_url('/menusetup'));
        }
    }

    public function save_submenu()
    {
        $idnav = $this->request->getPost('idnav');
        $idnavdl1 = $this->request->getPost('idnavdl1');
        if (!$this->validate([
            'navdname' => 'required',
            'comment' => 'required',
            'icon' => 'required',
        ])) {
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');

            return redirect()->to(base_url('/menusetup/subform/' . $idnav))->withInput();
        } else {
            $today = date("d/m/Y");
            $audtdate = substr($today, 6, 4) . "" . substr($today, 3, 2) . "" . substr($today, 0, 2);
            //$idnav = $this->request->getPost('idnav');
            $data = array(
                'NAVDL1NAME' => $this->request->getPost('navdname'),
                'COMMENT' => $this->request->getPost('comment'),
                'fa_icon' => $this->request->getPost('icon'),
            );

            //print_r($data_notif);
            $this->MenusetupModel->submenu_update($idnavdl1, $data);
            session()->setFlashdata('messagesuccess', 'Update Data Success');
            return redirect()->to(base_url('/menusetup/sub/' . $idnav));
        }
    }


    public function sort($menuh = 1, $id = 0, $arah = 0)
    {
        $this->MenusetupModel->sort($menuh, $id, $arah);
        return redirect()->to(base_url("menusetup/sub/$menuh"));
    }
}
