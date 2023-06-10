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

        if (empty(session()->get('keylog'))) {
            //Tidak Bisa menggunakan redirect kalau condition session di construct
            //return redirect()->to(base_url('/login'));
            //session()->destroy();
            //header('Location: ' . base_url());
            throw new \CodeIgniter\Router\Exceptions\RedirectException('login');

            exit();
        } else {
            $user = session()->get('username');
            $chkuser = $this->LoginModel->datapengguna($user);
            if (session()->get('keylog') == $chkuser['passlgn'] and session()->get('userhash') == $chkuser['userhashlgn']) {


                $infouser = $this->LoginModel->datapengguna($user);
                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'photolgn' => $infouser['photolgn'],
                    'userhashlgn' => $infouser['userhashlgn'],
                    'usergrplgn' => $infouser['usergrplgn'],
                    'notif_messages' => $mailbox_unread,
                    'success_code' => session()->get('success'),
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavh = 'Administration';
                $activenavd = 'Administration';
                $this->nav_data = [
                    'active_navh' => $activenavh,
                    'active_navd' => $activenavd,
                    'menu_nav' => $this->AdministrationModel->get_navigation($user),
                    //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
                    //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
                    //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
                ];
            } else {
                header('Location: ' . base_url());
                exit();
            }
        }
    }


    public function index()
    {

        $user = session()->get('username');
        $keylog = session()->GET('keylog');
        $latest_mailbox_in = $this->AdministrationModel->get_mailbox_in($user);
        $latest_csr = $this->AdministrationModel->get_latest_csr();
        $latest_requisition = $this->AdministrationModel->get_latest_requisition();
        $latest_po = $this->AdministrationModel->get_latest_po();
        $latest_logistics = $this->AdministrationModel->get_latest_logistics();
        $latest_gr = $this->AdministrationModel->get_latest_gr();
        $latest_shipments = $this->AdministrationModel->get_latest_shipments();
        $latest_finance = $this->AdministrationModel->get_latest_finance();
        $data = array(
            'mailbox_in_data' => $latest_mailbox_in,
            'csr_data' => $latest_csr,
            'rqn_data' => $latest_requisition,
            'po_data' => $latest_po,
            'logistics_data' => $latest_logistics,
            'gr_data' => $latest_gr,
            'shi_data' => $latest_shipments,
            'fin_data' => $latest_finance,
            'keyword' => '',
        );

        //return view('/admin/template',$data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('home/view_homepage', $data);
        echo view('view_footer', $this->footer_data);

        session()->remove('success');
        session()->set('success', '0');
    }

    /*public function testdata()
    {
        $groupuser = 2;
        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        print_r($notiftouser_data);
    }*/
}
