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

        $contract_data = $this->AdminmenuModel->get_contract();
        $otheader_data = $this->AdminmenuModel->get_otheader($keyword = '');



        $data = array(
            'keyword' => '',
            'contract_data' => $contract_data,
            'otheader_data' => $otheader_data,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('adminmenu/rollbackordertracking_list', $data);
        echo view('view_footer', $this->footer_data);
        session()->remove('success');
    }

    public function search()
    {

        session()->remove('success');
        session()->set('success', '0');
        $cari = $this->request->getPost('contract_no');

        if ($cari != '') {
            session()->set('cari', $cari);
        } else {
            session()->remove('cari');
        }
        return redirect()->to(base_url('rollbackprocess/filter'));
    }

    public function filter()
    {

        $keyword = session()->get('cari');
        $contract_data = $this->AdminmenuModel->get_contract();
        if (empty($keyword)) {
            $otheader_data = $this->AdminmenuModel->get_otheader($keyword = '');
        } else {
            $otheader_data = $this->AdminmenuModel->get_otheader_search($keyword);
        }
        $data = array(
            'keyword' => $keyword,
            'contract_data' => $contract_data,
            'otheader_data' => $otheader_data,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('adminmenu/rollbackordertracking_list', $data);
        echo view('view_footer', $this->footer_data);
        session()->remove('success');
    }

    public function form($csruniq)
    {
        $otheader_data = $this->AdminmenuModel->get_otheader_by_csruniq($csruniq);
        $data = array(
            'csruniq' => $csruniq,
            'csrheader_data' => $otheader_data,
            'button' => 'Cancel from this process',
            'form_action' => base_url('rollbackprocess/rollback_action'),
        );

        echo view('adminmenu/ajax_rollback_process', $data);
    }

    public function rollback_action()
    {
        session()->remove('success');
        $csruniq = $this->request->getPost('csruniq');

        // Roll Back Logistics Process
        if ($_POST['rollbackprocess'] == 'so_process') {
            $dataso = array(
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );

            $this->AdminmenuModel->rollback_so($csruniq, $dataso);
            $this->AdminmenuModel->rollback_rqn($csruniq);
            $this->AdminmenuModel->rollback_po($csruniq);
            $this->AdminmenuModel->rollback_pol($csruniq);
            $this->AdminmenuModel->rollback_log($csruniq);
            $this->AdminmenuModel->rollback_gr($csruniq);
            $this->AdminmenuModel->rollback_grl($csruniq);
            $this->AdminmenuModel->rollback_dn($csruniq);
            $this->AdminmenuModel->rollback_dnl($csruniq);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->permanent_del_ot($csruniq);
        }




        // Roll Back PO Process
        if ($_POST['rollbackprocess'] == 'rqn_process') {

            $dataot = array(
                //RQN Data
                'RQNDATE' => NULL,
                'RQNNUMBER' => NULL,
                //PO Data
                'PODATE' => NULL,
                'PONUMBER' => NULL,
                'ETDDATE' => NULL,
                'CARGOREADINESSDATE' => NULL,
                'ORIGINCOUNTRY' => NULL,
                'POREMARKS' => NULL,
                //Logistics Data
                'ETDORIGINDATE' => NULL,
                'ATDORIGINDATE' => NULL,
                'ETAPORTDATE' => NULL,
                'PIBDATE' => NULL,
                'VENDSHISTATUS' => NULL,
                'LOGREMARKS' => NULL,
                //GR Data
                'RECPNUMBER' => NULL,
                'RECPDATE' => NULL,
                'RECPQTY' => NULL,
                'RECPUNIT' => NULL,
                'GRSTATUS' => NULL,
                //DN Data
                'SHIDOCNUMBER' => NULL,
                'SHINUMBER' => NULL,
                'SHIDATE' => NULL,
                'CUSTRCPDATE' => NULL,
                'ORIGDNRCPSHIDATE' => NULL,
                'SHIQTY' => NULL,
                'SHIQTYOUTSTANDING' => NULL,
                'SHIUNIT' => NULL,
                'POCUSTSTATUS' => NULL,
                'ONTIMEDELDAYS' =>  NULL,
                'POTODNDAYS' => NULL,
                //EDN Data
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                //Fin Data
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                //RR Data
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_rqn($csruniq);
            $this->AdminmenuModel->rollback_po($csruniq);
            $this->AdminmenuModel->rollback_pol($csruniq);
            $this->AdminmenuModel->rollback_log($csruniq);
            $this->AdminmenuModel->rollback_gr($csruniq);
            $this->AdminmenuModel->rollback_grl($csruniq);
            $this->AdminmenuModel->rollback_dn($csruniq);
            $this->AdminmenuModel->rollback_dnl($csruniq);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back PO Process
        if ($_POST['rollbackprocess'] == 'po_process') {

            $dataot = array(

                //PO Data
                'PODATE' => NULL,
                'PONUMBER' => NULL,
                'ETDDATE' => NULL,
                'CARGOREADINESSDATE' => NULL,
                'ORIGINCOUNTRY' => NULL,
                'POREMARKS' => NULL,
                //Logistics Data
                'ETDORIGINDATE' => NULL,
                'ATDORIGINDATE' => NULL,
                'ETAPORTDATE' => NULL,
                'PIBDATE' => NULL,
                'VENDSHISTATUS' => NULL,
                'LOGREMARKS' => NULL,
                //GR Data
                'RECPNUMBER' => NULL,
                'RECPDATE' => NULL,
                'RECPQTY' => NULL,
                'RECPUNIT' => NULL,
                'GRSTATUS' => NULL,
                //DN Data
                'SHIDOCNUMBER' => NULL,
                'SHINUMBER' => NULL,
                'SHIDATE' => NULL,
                'CUSTRCPDATE' => NULL,
                'ORIGDNRCPSHIDATE' => NULL,
                'SHIQTY' => NULL,
                'SHIQTYOUTSTANDING' => NULL,
                'SHIUNIT' => NULL,
                'POCUSTSTATUS' => NULL,
                'ONTIMEDELDAYS' =>  NULL,
                'POTODNDAYS' => NULL,
                //EDN Data
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                //Fin Data
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                //RR Data
                'RRSTATUS' => NULL,
            );

            $this->AdminmenuModel->rollback_po($csruniq);
            $this->AdminmenuModel->rollback_pol($csruniq);
            $this->AdminmenuModel->rollback_log($csruniq);
            $this->AdminmenuModel->rollback_gr($csruniq);
            $this->AdminmenuModel->rollback_grl($csruniq);
            $this->AdminmenuModel->rollback_dn($csruniq);
            $this->AdminmenuModel->rollback_dnl($csruniq);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back Logistics Process
        if ($_POST['rollbackprocess'] == 'log_process') {

            $dataot = array(
                //Logistics Data
                'ETDORIGINDATE' => NULL,
                'ATDORIGINDATE' => NULL,
                'ETAPORTDATE' => NULL,
                'PIBDATE' => NULL,
                'VENDSHISTATUS' => NULL,
                'LOGREMARKS' => NULL,
                //GR Data
                'RECPNUMBER' => NULL,
                'RECPDATE' => NULL,
                'RECPQTY' => NULL,
                'RECPUNIT' => NULL,
                'GRSTATUS' => NULL,
                //DN Data
                'SHIDOCNUMBER' => NULL,
                'SHINUMBER' => NULL,
                'SHIDATE' => NULL,
                'CUSTRCPDATE' => NULL,
                'ORIGDNRCPSHIDATE' => NULL,
                'SHIQTY' => NULL,
                'SHIQTYOUTSTANDING' => NULL,
                'SHIUNIT' => NULL,
                'POCUSTSTATUS' => NULL,
                'ONTIMEDELDAYS' =>  NULL,
                'POTODNDAYS' => NULL,
                //EDN Data
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                //Fin Data
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                //RR Data
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_log($csruniq);
            $this->AdminmenuModel->rollback_gr($csruniq);
            $this->AdminmenuModel->rollback_grl($csruniq);
            $this->AdminmenuModel->rollback_dn($csruniq);
            $this->AdminmenuModel->rollback_dnl($csruniq);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back GR Process
        if ($_POST['rollbackprocess'] == 'gr_process') {

            $dataot = array(
                //GR Data
                'RECPNUMBER' => NULL,
                'RECPDATE' => NULL,
                'RECPQTY' => NULL,
                'RECPUNIT' => NULL,
                'GRSTATUS' => NULL,
                //DN Data
                'SHIDOCNUMBER' => NULL,
                'SHINUMBER' => NULL,
                'SHIDATE' => NULL,
                'CUSTRCPDATE' => NULL,
                'ORIGDNRCPSHIDATE' => NULL,
                'SHIQTY' => NULL,
                'SHIQTYOUTSTANDING' => NULL,
                'SHIUNIT' => NULL,
                'POCUSTSTATUS' => NULL,
                'ONTIMEDELDAYS' =>  NULL,
                'POTODNDAYS' => NULL,
                //EDN Data
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                //Fin Data
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                //RR Data
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_gr($csruniq);
            $this->AdminmenuModel->rollback_grl($csruniq);
            $this->AdminmenuModel->rollback_dn($csruniq);
            $this->AdminmenuModel->rollback_dnl($csruniq);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back DN Process
        if ($_POST['rollbackprocess'] == 'dn_process') {
            //Script untuk hapus Attached Document
            $chk_shi = $this->AdminmenuModel->get_shi_data($csruniq);
            foreach ($chk_shi as $ednfile) :
                if (is_file('assets/files/edn_attached/' . trim($ednfile['EDNFILENAME']))) {
                    unlink('assets/files/edn_attached/' . trim($ednfile['EDNFILENAME']));
                }
            endforeach;
            $dataot = array(
                //DN Data
                'SHIDOCNUMBER' => NULL,
                'SHINUMBER' => NULL,
                'SHIDATE' => NULL,
                'CUSTRCPDATE' => NULL,
                'ORIGDNRCPSHIDATE' => NULL,
                'SHIQTY' => NULL,
                'SHIQTYOUTSTANDING' => NULL,
                'SHIUNIT' => NULL,
                'POCUSTSTATUS' => NULL,
                'ONTIMEDELDAYS' =>  NULL,
                'POTODNDAYS' => NULL,
                //EDN Data
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                //Fin Data
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                //RR Data
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_dn($csruniq);
            $this->AdminmenuModel->rollback_dnl($csruniq);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back Confirm e DN Process
        if ($_POST['rollbackprocess'] == 'edn_process') {
            $dataedn = array(
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                'DNOTPROCESS' => NULL,
                'DNPOSTINGSTAT' => NULL,
                'DNOFFLINESTAT' => NULL,
            );
            $dataot = array(
                'DNSTATUS' => NULL,
                'ORIGDNRCPSLSDATE' => NULL,
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_edn($csruniq, $dataedn);
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back Finance
        if ($_POST['rollbackprocess'] == 'fin_process') {

            $dataot = array(
                'IDINVC' => NULL,
                'DATEINVC' => NULL,
                'ORIGDNRCPFINDATE' => NULL,
                'FINSTATUS' => NULL,
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_fin($csruniq);
            $this->AdminmenuModel->rollback_finl($csruniq);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }

        // Roll Back RR
        if ($_POST['rollbackprocess'] == 'rr_process') {
            $datarr = array(
                'RRSTATUS' => NULL,
                'TCURCOSTHM' => NULL,
                'TACTCOSTHM' => NULL,
                'RRPOSTINGSTAT' => NULL,
            );
            $dataot = array(
                'RRSTATUS' => NULL,
            );
            $this->AdminmenuModel->rollback_rr($csruniq, $datarr);
            $this->AdminmenuModel->rollback_ot($csruniq, $dataot);
        }
        session()->set('success', '1');
        return redirect()->to(base_url('rollbackprocess'));
    }
}
