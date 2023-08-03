<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Adminmenu_model;
//use App\Models\Settingnavheader_model;


class Rollbackprocess extends BaseController
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
        $this->AdminmenuModel = new Adminmenu_model();
        $this->db = \Config\Database::connect();
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
                    'photolgn' => $infouser['photolgn'],
                    'userhashlgn' => $infouser['userhashlgn'],
                    'notif_messages' => $mailbox_unread,
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavd = 'rollbackprocess';
                $activenavh = 'rollbackprocess';
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
            }
        }
    }


    public function index()
    {
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);

        $otheader_data = $this->AdminmenuModel->select('*')
            ->groupStart()
            ->where('PODATECUST >=', $fr_date)
            ->where('PODATECUST <=', $to_date)
            ->groupEnd()
            ->orderBy('PODATECUST', 'ASC')
            ->orderBy('CUSTOMER', 'ASC')
            ->orderBy('CONTRACT', 'ASC');
        $perpage = 20;

        $data = array(
            'keyword' => '',
            'otheader_data' => $otheader_data->paginate($perpage, 'csrposting_data'),
            'pager' => $otheader_data->pager,
            'success_code' => session()->get('success'),
            //'currentpage' => $currentpage,
            'perpage' => $perpage,
            'currentpage' => $otheader_data->pager->getCurrentPage('csrposting_data'),
            'def_fr_date' => $def_fr_date,
            'def_to_date' => $def_to_date,

        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('adminmenu/rollbackordertracking_list', $data);
        echo view('view_footer', $this->footer_data);
        session()->remove('success');
    }
}
