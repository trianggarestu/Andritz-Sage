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
use App\Models\Goodreceipt_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class GoodReceipt extends BaseController
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
        $this->GoodreceiptModel = new Goodreceipt_model();
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
            if (session()->get('keylog') == $infouser['passlgn']) {


                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'notif_messages' => $mailbox_unread,
                    'success_code' => session()->get('success'),
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavd = 'goodreceipt';
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
        $receiptdata = $this->GoodreceiptModel->get_po_pending_to_gr();

        $data = array(
            'receipt_data' => $receiptdata,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/data_po_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($pouniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($pouniq);
        $reqdate = substr($getpodata['CRMREQDATE'], 4, 2) . '/' . substr($getpodata['CRMREQDATE'], 6, 2) . '/' . substr($getpodata['CRMREQDATE'], 0, 4);
        $podate = substr($getpodata['PODATE'], 4, 2) . '/' . substr($getpodata['PODATE'], 6, 2) . '/' . substr($getpodata['PODATE'], 0, 4);
        $etaportdate = substr($getpodata['ETAPORTDATE'], 4, 2) . '/' . substr($getpodata['ETAPORTDATE'], 6, 2) . '/' . substr($getpodata['ETAPORTDATE'], 0, 4);
        if ($getpodata['RCPUNIQ'] == NULL) {
            $button_text = 'Save';
            $act = 'goodreceipt/insert_action';


            $data = array(
                'csr_uniq' => $getpodata['CSRUNIQ'],
                'ct_no' => $getpodata['CONTRACT'],
                'ct_desc' => $getpodata['CTDESC'],
                'prj_no' => $getpodata['PROJECT'],
                'ct_custname' => $getpodata['NAMECUST'],
                'crm_no' => $getpodata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getpodata['ITEMNO'],
                'csr_material_no' => $getpodata['MATERIALNO'],
                'csr_item_desc' => $getpodata['ITEMDESC'],
                'csr_srvtype' => $getpodata['SERVICETYPE'],
                'csr_qty' => $getpodata['QTY'],
                'csr_uom' => $getpodata['STOCKUNIT'],
                'po_uniq' => $getpodata['POUNIQ'],
                'po_number' => $getpodata['PONUMBER'],
                'po_date' => $podate,
                'etaport_date' => $etaportdate,
                'vendorshi_status' => $getpodata['VENDSHISTATUS'],
                'rcpuniq' => '',
                'rcphseq' => '',
                'rcp_number' => '',
                'rcp_date' => '',
                'vd_name' => '',
                'rcp_desc' => '',
                'item_no' => '',
                'material_no' => '',
                'item_desc' => '',
                'qty_rcp' => '',
                'rcp_unit' => '',
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        } else {

            $rcpdate = substr($getpodata['RECPDATE'], 4, 2) . '/' . substr($getpodata['RECPDATE'], 6, 2) . '/' . substr($getpodata['RECPDATE'], 0, 4);
            $etaportdate = substr($getpodata['ETAPORTDATE'], 4, 2) . '/' . substr($getpodata['ETAPORTDATE'], 6, 2) . '/' . substr($getpodata['ETAPORTDATE'], 0, 4);
            $button_text = 'Update';
            $act = 'goodreceipt/update_action';


            $data = array(
                'csr_uniq' => $getpodata['CSRUNIQ'],
                'ct_no' => $getpodata['CONTRACT'],
                'ct_desc' => $getpodata['CTDESC'],
                'prj_no' => $getpodata['PROJECT'],
                'ct_custname' => $getpodata['NAMECUST'],
                'crm_no' => $getpodata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getpodata['ITEMNO'],
                'csr_material_no' => $getpodata['MATERIALNO'],
                'csr_item_desc' => $getpodata['ITEMDESC'],
                'csr_srvtype' => $getpodata['SERVICETYPE'],
                'csr_qty' => $getpodata['QTY'],
                'csr_uom' => $getpodata['STOCKUNIT'],
                'po_uniq' => $getpodata['POUNIQ'],
                'po_number' => $getpodata['PONUMBER'],
                'po_date' => $podate,
                'etaport_date' => $etaportdate,
                'vendorshi_status' => $getpodata['VENDSHISTATUS'],
                'rcpuniq' => $getpodata['RCPUNIQ'],
                'rcphseq' => $getpodata['RCPHSEQ'],
                'rcp_number' => $getpodata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'vd_name' => $getpodata['VDNAME'],
                'rcp_desc' => $getpodata['DESCRIPTIO'],
                'item_no' => $getpodata['RECPITEMNO'],
                'material_no' => $getpodata['MATERIALNO'],
                'item_desc' => $getpodata['RECPITEMDESC'],
                'qty_rcp' => $getpodata['RECPQTY'],
                'rcp_unit' => $getpodata['RECPUNIT'],
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/goodreceipt_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function form_select_goodreceipt($po_uniq)
    {
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($po_uniq);
        $data = array(
            'gr_by_po' => $this->GoodreceiptModel->list_gr_by_po($getpodata['PONUMBER']),
            'form_action' => base_url("goodreceipt/choosegoodreceipt"),
            'po_uniq' => $po_uniq,
            'csr_uniq' => $getpodata['CSRUNIQ'],
            'po_number' => $getpodata['PONUMBER'],
            //'rcphseq' => $getpodata['RCPHSEQ'],
        );

        //$data['gr_by_po'] = $this->GoodreceiptModel->list_gr_by_po($ponumber);
        //$data['gr_by_po'] = $this->GoodreceiptModel->get_receipt();
        //$data['form_action'] = base_url("salesorder/choosegr");
        //echo view('crm/ajax_add_contract', $data);
        echo view('goodreceipt/ajax_add_goodreceipt', $data);
    }

    /*public function form_select_goodreceiptline($po_uniq, $rcphseq)
    {
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($po_uniq);
        $getrcpdata = $this->GoodreceiptModel->get_receipt_sage_by_id($rcphseq);
        $contract = trim($getpodata['CONTRACT']);
        $data = array(
            'getrcpldata' => $this->GoodreceiptModel->get_rcpl_by_receipt($rcphseq, $contract),
            'form_action' => base_url("goodreceipt/choosegoodreceiptline"),
            'po_uniq' => $po_uniq,
            'csr_uniq' => $getpodata['CSRUNIQ'],
            'po_number' => $getpodata['PONUMBER'],
            'ct_no' => $contract,
            'ct_desc' => $getpodata['CTDESC'],
            'rcp_number' => $getrcpdata['RCPNUMBER']
        );

        //$data['gr_by_po'] = $this->GoodreceiptModel->list_gr_by_po($ponumber);
        //$data['gr_by_po'] = $this->GoodreceiptModel->get_receipt();
        //$data['form_action'] = base_url("salesorder/choosegr");
        //echo view('crm/ajax_add_contract', $data);
        echo view('goodreceipt/ajax_add_goodreceiptline', $data);
    }*/


    public function choosegoodreceipt()
    {
        if (null == ($this->request->getPost('rcph_seq'))) {
            $sage_rcphseq = "receipt number not found";
        } else {
            $sage_rcphseq = $this->request->getPost('rcph_seq');
            $po_uniq = $this->request->getPost('po_uniq');
        }

        return redirect()->to(base_url('goodreceipt/selectgoodreceipt/' . $po_uniq . '/' . $sage_rcphseq));
    }


    /*public function choosegoodreceiptline()
    {
        if (null == ($this->request->getPost('sage_rcplseq'))) {
            $sage_rcplseq = "receipt number not found";
        } else {
            //$rcp_number = $this->request->getPost('rcp_number');
            $po_uniq = $this->request->getPost('po_uniq');
            $sage_rcphseq = $this->request->getPost('sage_rcphseq');
            $sage_rcplseq = $this->request->getPost('sage_rcplseq');
        }

        return redirect()->to(base_url('goodreceipt/selectgoodreceiptline/' . $po_uniq . '/' . $sage_rcphseq . '/' . $sage_rcplseq));
    }*/


    public function selectgoodreceipt($pouniq, $sage_rcphseq = 0, $postingstat = 0)
    {
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($pouniq);
        $reqdate = substr($getpodata['CRMREQDATE'], 4, 2) . '/' . substr($getpodata['CRMREQDATE'], 6, 2) . '/' . substr($getpodata['CRMREQDATE'], 0, 4);
        $podate = substr($getpodata['PODATE'], 4, 2) . '/' . substr($getpodata['PODATE'], 6, 2) . '/' . substr($getpodata['PODATE'], 0, 4);
        $getrcpdata = $this->GoodreceiptModel->get_receipt_sage_by_id($sage_rcphseq);
        if ($postingstat == 0) {
            $button_text = 'Save';
            $act = 'goodreceipt/insert_action';
        } else {
            $button_text = 'Save & Posting';
            $act = 'goodreceipt/update_action';
        }
        if ($sage_rcphseq == '') {
            $data = array(
                'csr_uniq' => $getpodata['CSRUNIQ'],
                'ct_no' => $getpodata['CONTRACT'],
                'ct_desc' => $getpodata['CTDESC'],
                'prj_no' => $getpodata['PROJECT'],
                'ct_custname' => $getpodata['NAMECUST'],
                'crm_no' => $getpodata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getpodata['ITEMNO'],
                'csr_material_no' => $getpodata['MATERIALNO'],
                'csr_item_desc' => $getpodata['ITEMDESC'],
                'csr_srvtype' => $getpodata['SERVICETYPE'],
                'csr_qty' => $getpodata['QTY'],
                'csr_uom' => $getpodata['STOCKUNIT'],
                'po_uniq' => $getpodata['POUNIQ'],
                'po_number' => $getpodata['PONUMBER'],
                'po_date' => $podate,
                'etaport_date' => $getpodata['ETAPORTDATE'],
                'vendorshi_status' => $getpodata['VENDSHISTATUS'],
                'rcpuniq' => $getpodata['RCPUNIQ'],
                'rcphseq' => '',
                'rcp_number' => '',
                'rcp_date' => '',
                'vd_name' => '',
                'rcp_desc' => '',
                'item_no' => '',
                'material_no' => '',
                'item_desc' => '',
                'qty_rcp' => '',
                'rcp_unit' => '',
                'button_text' => $button_text,
                'form_action' => base_url($act),
            );
        } else {

            $rcpdate = substr($getrcpdata['RCPDATE'], 4, 2) . '/' . substr($getrcpdata['RCPDATE'], 6, 2) . '/' . substr($getrcpdata['RCPDATE'], 0, 4);

            $data = array(

                'csr_uniq' => $getpodata['CSRUNIQ'],
                'ct_no' => $getpodata['CONTRACT'],
                'ct_desc' => $getpodata['CTDESC'],
                'prj_no' => $getpodata['PROJECT'],
                'ct_custname' => $getpodata['NAMECUST'],
                'crm_no' => $getpodata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getpodata['ITEMNO'],
                'csr_material_no' => $getpodata['MATERIALNO'],
                'csr_item_desc' => $getpodata['ITEMDESC'],
                'csr_srvtype' => $getpodata['SERVICETYPE'],
                'csr_qty' => $getpodata['QTY'],
                'csr_uom' => $getpodata['STOCKUNIT'],
                'po_uniq' => $getpodata['POUNIQ'],
                'po_number' => $getpodata['PONUMBER'],
                'po_date' => $podate,
                'etaport_date' => $getpodata['ETAPORTDATE'],
                'vendorshi_status' => $getpodata['VENDSHISTATUS'],
                'rcpuniq' => $getpodata['RCPUNIQ'],
                'rcphseq' => $sage_rcphseq,
                'rcp_number' => trim($getrcpdata['RCPNUMBER']),
                'rcp_date' => $rcpdate,
                'vd_name' => $getrcpdata['VDNAME'],
                'rcp_desc' => $getrcpdata['DESCRIPTIO'],
                'item_no' => $getpodata['ITEMNO'],
                'material_no' => $getpodata['MATERIALNO'],
                'item_desc' => $getpodata['ITEMDESC'],
                'qty_rcp' => 0,
                'rcp_unit' => $getpodata['STOCKUNIT'],
                'button_text' => $button_text,
                'form_action' => base_url($act),
            );
        }
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/goodreceipt_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    /*public function selectgoodreceiptline($pouniq, $sage_rcphseq = '', $sage_rcplseq = '', $postingstat = 0)
    {
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($pouniq);
        $reqdate = substr($getpodata['CRMREQDATE'], 4, 2) . '/' . substr($getpodata['CRMREQDATE'], 6, 2) . '/' . substr($getpodata['CRMREQDATE'], 0, 4);
        $podate = substr($getpodata['PODATE'], 4, 2) . '/' . substr($getpodata['PODATE'], 6, 2) . '/' . substr($getpodata['PODATE'], 0, 4);
        $getrcpldata = $this->GoodreceiptModel->get_receiptline_sage_by_id($sage_rcphseq, $sage_rcplseq);
        if ($postingstat == 0) {
            $button_text = 'Save';
            $act = 'goodreceipt/insert_action';
        } else {
            $button_text = 'Save & Posting';
            $act = 'goodreceipt/update_action';
        }
        if ($sage_rcphseq == '' and $sage_rcplseq == '') {
            $data = array(
                'csr_uniq' => $getpodata['CSRUNIQ'],
                'ct_no' => $getpodata['CONTRACT'],
                'ct_desc' => $getpodata['CTDESC'],
                'prj_no' => $getpodata['PROJECT'],
                'ct_custname' => $getpodata['NAMECUST'],
                'crm_no' => $getpodata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getpodata['ITEMNO'],
                'csr_item_desc' => $getpodata['ITEMDESC'],
                'csr_srvtype' => $getpodata['SERVICETYPE'],
                'csr_qty' => $getpodata['QTY'],
                'csr_uom' => $getpodata['STOCKUNIT'],
                'po_uniq' => $getpodata['POUNIQ'],
                'po_number' => $getpodata['PONUMBER'],
                'po_date' => $podate,
                'etaport_date' => $getpodata['ETAPORTDATE'],
                'vendorshi_status' => $getpodata['VENDSHISTATUS'],
                'rcpuniq' => $getpodata['RCPUNIQ'],
                'rcphseq' => '',
                'rcplseq' => '',
                'rcp_number' => '',
                'rcp_date' => '',
                'vd_name' => '',
                'rcp_desc' => '',
                'item_no' => '',
                'item_desc' => '',
                'qty_rcp' => '',
                'rcp_unit' => '',
                'button_text' => $button_text,
                'form_action' => base_url($act),
            );
        } else {

            $rcpdate = substr($getrcpldata['RCPDATE'], 4, 2) . '/' . substr($getrcpldata['RCPDATE'], 6, 2) . '/' . substr($getrcpldata['RCPDATE'], 0, 4);
            $data = array(
                'csr_uniq' => $getpodata['CSRUNIQ'],
                'ct_no' => $getpodata['CONTRACT'],
                'ct_desc' => $getpodata['CTDESC'],
                'prj_no' => $getpodata['PROJECT'],
                'ct_custname' => $getpodata['NAMECUST'],
                'crm_no' => $getpodata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getpodata['ITEMNO'],
                'csr_item_desc' => $getpodata['ITEMDESC'],
                'csr_srvtype' => $getpodata['SERVICETYPE'],
                'csr_qty' => $getpodata['QTY'],
                'csr_uom' => $getpodata['STOCKUNIT'],
                'po_uniq' => $getpodata['POUNIQ'],
                'po_number' => $getpodata['PONUMBER'],
                'po_date' => $podate,
                'etaport_date' => $getpodata['ETAPORTDATE'],
                'vendorshi_status' => $getpodata['VENDSHISTATUS'],
                'rcpuniq' => $getpodata['RCPUNIQ'],
                'rcphseq' => $sage_rcphseq,
                'rcplseq' => $sage_rcplseq,
                'rcp_number' => trim($getrcpldata['RCPNUMBER']),
                'rcp_date' => $rcpdate,
                'vd_name' => $getrcpldata['VDNAME'],
                'rcp_desc' => $getrcpldata['DESCRIPTIO'],
                'item_no' => $getrcpldata['ITEMNO'],
                'item_desc' => $getrcpldata['ITEMDESC'],
                'qty_rcp' => $getrcpldata['RQRECEIVED'],
                'rcp_unit' => $getrcpldata['RCPUNIT'],
                'button_text' => $button_text,
                'form_action' => base_url($act),
            );
        }
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/goodreceipt_form', $data);
        echo view('view_footer', $this->footer_data);
    }
*/


    public function insert_action()
    {
        if (!$this->validate([
            'rcp_number' => 'required',
            'rcp_date' => 'required',
            'vd_name' => 'required',
            'rcp_desc' => 'required',
            'rcp_item_no' => 'required',
            'item_desc' => 'required',
            'qty_rcp' => 'required|numeric|greater_than[0]',
            'rcp_unit' => 'required',

        ])) {
            $po_uniq = $this->request->getPost('po_uniq');
            $rcph_seq = $this->request->getPost('rcph_seq');
            $rcpl_seq = $this->request->getPost('rcpl_seq');
            $rcp_number = $this->request->getPost('rcp_number');
            $item_no = $this->request->getPost('rcp_item_no');
            if (($rcp_number == "") and ($item_no == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/update/' . $po_uniq))->withInput();
            } else if (($rcp_number <> "") and ($item_no == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/selectgoodreceipt/' . $po_uniq . '/' . $rcph_seq))->withInput();
            } else if (($rcp_number <> "") and ($item_no <> "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/selectgoodreceiptline/' . $po_uniq . '/' . $rcph_seq . '/' . $rcpl_seq))->withInput();
                //return redirect()->back()->withInput();
            }

            //echo $prj_no;
            //echo $this->validate;

        } else {
            // Check Status Mail Notification
            $csruniq = $this->request->getPost('csr_uniq');
            $rcph_seq = $this->request->getPost('rcph_seq');
            $rcpl_seq = $this->request->getPost('rcpl_seq');
            $rcp_date = $this->request->getPost('rcp_date');
            $rcp_date = substr($rcp_date, 6, 4)  . "" . substr($rcp_date, 0, 2) . "" . substr($rcp_date, 3, 2);
            if ($this->request->getPost('csr_qty') == $this->request->getPost('qty_rcp')) {
                $gr_status = 1;
            } else {
                $gr_status = 0;
            }

            $groupuser = 6;

            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $this->request->getPost('csr_uniq'),
                'POUNIQ' => $this->request->getPost('po_uniq'),
                'PONUMBER' => $this->request->getPost('po_number'),
                'RCPHSEQ' => $this->request->getPost('rcph_seq'),
                'RECPNUMBER' => $this->request->getPost('rcp_number'),
                'RECPDATE' => $rcp_date,
                'VDNAME' => $this->request->getPost('vd_name'),
                'DESCRIPTIO' => $this->request->getPost('rcp_desc'),
                'RECPITEMNO' => $this->request->getPost('rcp_item_no'),
                'ITEMDESC' => $this->request->getPost('item_desc'),
                'RECPQTY' => $this->request->getPost('qty_rcp'),
                'RECPUNIT' => $this->request->getPost('rcp_unit'),
                'GRSTATUS' => $gr_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );

            //print_r($data_notif);
            $receipt_insert = $this->GoodreceiptModel->goodreceipt_insert($data);

            $csruniq = $this->request->getPost('csr_uniq');
            $pouniq = $this->request->getPost('po_uniq');
            $rcpno = $this->request->getPost('rcp_number');
            $rcpl_seq = $this->request->getPost('rcpl_seq');
            $getrcpuniq = $this->GoodreceiptModel->get_rcpuniq_open($csruniq, $pouniq, $rcph_seq, $rcpl_seq);
            //session()->setFlashdata('messageerror', 'Create Record Failed');
            session()->set('success', '1');
            return redirect()->to(base_url('/goodreceipt/gropenview/' . $getrcpuniq['RCPUNIQ']));
            session()->remove('success');
        }
    }

    public function update_action()
    {
        if (!$this->validate([
            'rcp_number' => 'required',
            'rcp_date' => 'required',
            'vd_name' => 'required',
            'rcp_desc' => 'required',
            'rcp_item_no' => 'required',
            'item_desc' => 'required',
            'qty_rcp' => 'required|numeric|greater_than[0]',
            'rcp_unit' => 'required',

        ])) {
            $po_uniq = $this->request->getPost('po_uniq');
            $rcph_seq = $this->request->getPost('rcph_seq');
            $rcpl_seq = $this->request->getPost('rcpl_seq');
            $rcp_number = $this->request->getPost('rcp_number');
            $item_no = $this->request->getPost('rcp_item_no');
            if (($rcp_number == "") and ($item_no == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/update/' . $po_uniq))->withInput();
            } else if (($rcp_number <> "") and ($item_no == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/selectgoodreceipt/' . $po_uniq . '/' . $rcph_seq))->withInput();
            } else if (($rcp_number <> "") and ($item_no <> "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/selectgoodreceiptline/' . $po_uniq . '/' . $rcph_seq . '/' . $rcpl_seq))->withInput();
                //return redirect()->back()->withInput();
            }

            //echo $prj_no;
            //echo $this->validate;

        } else {
            // Check Status Mail Notification
            $csruniq = $this->request->getPost('csr_uniq');
            $rcpuniq = $this->request->getPost('rcp_uniq');
            $rcph_seq = $this->request->getPost('rcph_seq');
            $rcp_date = $this->request->getPost('rcp_date');
            $rcp_date = substr($rcp_date, 6, 4)  . "" . substr($rcp_date, 0, 2) . "" . substr($rcp_date, 3, 2);
            if ($this->request->getPost('csr_qty') == $this->request->getPost('qty_rcp')) {
                $gr_status = 1;
            } else {
                $gr_status = 0;
            }

            $groupuser = 6;

            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $this->request->getPost('csr_uniq'),
                'POUNIQ' => $this->request->getPost('po_uniq'),
                'PONUMBER' => $this->request->getPost('po_number'),
                'RCPHSEQ' => $this->request->getPost('rcph_seq'),
                'RECPNUMBER' => $this->request->getPost('rcp_number'),
                'RECPDATE' => $rcp_date,
                'VDNAME' => $this->request->getPost('vd_name'),
                'DESCRIPTIO' => $this->request->getPost('rcp_desc'),
                'RECPITEMNO' => $this->request->getPost('rcp_item_no'),
                'ITEMDESC' => $this->request->getPost('item_desc'),
                'RECPQTY' => $this->request->getPost('qty_rcp'),
                'RECPUNIT' => $this->request->getPost('rcp_unit'),
                'GRSTATUS' => $gr_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );

            //print_r($data_notif);
            $receipt_update = $this->GoodreceiptModel->goodreceipt_update($rcpuniq, $data);

            $csruniq = $this->request->getPost('csr_uniq');
            $pouniq = $this->request->getPost('po_uniq');
            $rcpno = $this->request->getPost('rcp_number');
            $rcpl_seq = $this->request->getPost('rcpl_seq');
            $getrcpuniq = $this->GoodreceiptModel->get_rcpuniq_open($csruniq, $pouniq, $rcph_seq, $rcpl_seq);
            //session()->setFlashdata('messageerror', 'Create Record Failed');
            session()->set('success', '1');
            return redirect()->to(base_url('/goodreceipt/gropenview/' . $getrcpuniq['RCPUNIQ']));
            session()->remove('success');
        }
    }


    public function gropenview($rcpuniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getrcpopen = $this->GoodreceiptModel->get_goodreceipt_open($rcpuniq);
        if ($getrcpopen['POSTINGSTAT'] == 0) {
            $data = array(
                'rcpopen_data' =>  $getrcpopen,
                'link_action' => 'goodreceipt/posting/',
                'btn_color' => 'bg-blue',
                'button' => 'Posting & Send Notification',
            );
        } else if ($getrcpopen['POSTINGSTAT'] == 1) {

            $data = array(
                'rcpopen_data' =>  $getrcpopen,
                'link_action' => 'goodreceipt/sendnotif/',
                'btn_color' => 'bg-orange',
                'button' => 'Send Notification Manually',
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/data_gr_view', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function posting($rcpuniq)
    {
        $getrcpopen = $this->GoodreceiptModel->get_goodreceipt_open($rcpuniq);
        $id_so = $getrcpopen['CSRUNIQ'];
        $po_number = $getrcpopen['PONUMBER'];
        $rcp_number = $getrcpopen['RECPNUMBER'];
        $rcp_date = substr($getrcpopen['RECPDATE'], 4, 2) . '/' . substr($getrcpopen['RECPDATE'], 6, 2) . '/' . substr($getrcpopen['RECPDATE'], 0, 4);
        if ($getrcpopen['GRSTATUS'] == 0) {
            $grstatus = 'Partial';
        } else {
            $grstatus = 'Completed';
        }

        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 6;
        $data = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'POSTINGSTAT' => 1,
            'OFFLINESTAT' => $sender['OFFLINESTAT'],

        );
        $gr_update = $this->GoodreceiptModel->goodreceipt_update($rcpuniq, $data);

        if ($gr_update) {
            $data2 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'RECPNUMBER' => $getrcpopen['RECPNUMBER'],
                'RECPDATE' => $getrcpopen['RECPDATE'],
                'RECPQTY' => $getrcpopen['RECPQTY'],
                'RECPUNIT' => $getrcpopen['RECPUNIT'],
                'GRSTATUS' => $getrcpopen['GRSTATUS'],
            );
            $this->GoodreceiptModel->ot_goodreceipt_update($id_so, $data2);

            if ($sender['OFFLINESTAT'] == 0) {
                //Untuk Update Status Posting CSR
                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'POSTINGSTAT' => 1,
                    'OFFLINESTAT' => 0,
                );
                //inisiasi proses kirim ke group

                $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

                foreach ($notiftouser_data as $sendto_user) {
                    $data_email = array(
                        'hostname'       => $sender['HOSTNAME'],
                        'sendername'       => $sender['SENDERNAME'],
                        'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                        'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                        'ssl'       => $sender['SSL'],
                        'smtpport'       => $sender['SMTPPORT'],
                        'to_email' => $sendto_user['EMAIL'],
                        'subject' => 'Pending Good Receipts Allert. Receipt Number :' . $rcp_number,
                        'message' =>    ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                        Please to follow up Good Receipt Number :' . $rcp_number . ', GR Date :' . $rcp_date . ') is pending for you to process Delivery Team.
            <br><br>
            PO Number :' . $po_number . '<br>
            Receipt Number :' . $rcp_number . '<br>
            Receipt Date :' . $rcp_date . '<br>
            GR Status :' . $grstatus . '<br>
            <hr>
            You can access Order Tracking System Portal via the URL below:
            <br>
            Http://jktsms025:...
            <br>
            Thanks for your cooperation. 
            <br><br>
            Order Tracking Administrator',
                    );

                    $sending_mail = $this->send($data_email);

                    if ($sending_mail) {
                        $data_notif = array(
                            'FROM_USER' => $this->header_data['usernamelgn'],
                            'FROM_EMAIL' => $this->header_data['emaillgn'],
                            'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                            'TO_USER' => $sendto_user['USERNAME'],
                            'TO_EMAIL' => $sendto_user['EMAIL'],
                            'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                            'SUBJECT' => 'Pending Good Receipts Allert. Receipt Number :' . $rcp_number,
                            'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                            Please to follow up Good Receipt Number :' . $rcp_number . ', GR Date :' . $rcp_date . ') is pending for you to process Delivery Team.
                <br><br>
                PO Number :' . $po_number . '<br>
                Receipt Number :' . $rcp_number . '<br>
                Receipt Date :' . $rcp_date . '<br>
                GR Status :' . $grstatus . '<br>
                <hr>
                You can access Order Tracking System Portal via the URL below:
                <br>
                Http://jktsms025:...
                <br>
                Thanks for your cooperation. 
                <br><br>
                Order Tracking Administrator',

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
                            'UNIQPROCESS' => $getrcpopen['CSRUNIQ'],
                        );
                        $this->NotifModel->mailbox_insert($data_notif);
                    }
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/goodreceipt'));
        session()->remove('success');
    }


    public function sendnotif($rcpuniq)
    {
        //check dari sini
        $get_rcp = $this->GoodreceiptModel->get_goodreceipt_post($rcpuniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $id_so = $get_rcp['CSRUNIQ'];
        $po_number = $get_rcp['PONUMBER'];
        $rcp_number = $get_rcp['RECPNUMBER'];
        $rcp_date = substr($get_rcp['RECPDATE'], 4, 2) . '/' . substr($get_rcp['RECPDATE'], 6, 2) . '/' . substr($get_rcp['RECPDATE'], 0, 4);
        if ($get_rcp['GRSTATUS'] == 0) {
            $grstatus = 'Partial';
        } else {
            $grstatus = 'Completed';
        }
        $groupuser = 6;
        //inisiasi proses kirim ke group
        $data2 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'OFFLINESTAT' => 0,
        );

        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

        foreach ($notiftouser_data as $sendto_user) {
            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' => 'Pending Good Receipts Allert. Receipt Number :' . $rcp_number,
                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                Please to follow up Good Receipt Number :' . $rcp_number . ', GR Date :' . $rcp_date . ') is pending for you to process Delivery Team.
    <br><br>
    PO Number :' . $po_number . '<br>
    Receipt Number :' . $rcp_number . '<br>
    Receipt Date :' . $rcp_date . '<br>
    GR Status :' . $grstatus . '<br>
    <hr>
    You can access Order Tracking System Portal via the URL below:
    <br>
    Http://jktsms025:...
    <br>
    Thanks for your cooperation. 
    <br><br>
    Order Tracking Administrator',
            );

            $sending_mail = $this->send($data_email);

            if ($sending_mail) {
                $data_notif = array(
                    'FROM_USER' => $this->header_data['usernamelgn'],
                    'FROM_EMAIL' => $this->header_data['emaillgn'],
                    'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                    'TO_USER' => $sendto_user['USERNAME'],
                    'TO_EMAIL' => $sendto_user['EMAIL'],
                    'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                    'SUBJECT' => 'Pending Good Receipts Allert. Receipt Number :' . $rcp_number,
                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                    Please to follow up Good Receipt Number :' . $rcp_number . ', GR Date :' . $rcp_date . ') is pending for you to process Delivery Team.
        <br><br>
        PO Number :' . $po_number . '<br>
        Receipt Number :' . $rcp_number . '<br>
        Receipt Date :' . $rcp_date . '<br>
        GR Status :' . $grstatus . '<br>
        <hr>
        You can access Order Tracking System Portal via the URL below:
        <br>
        Http://jktsms025:...
        <br>
        Thanks for your cooperation. 
        <br><br>
        Order Tracking Administrator',

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
                    'UNIQPROCESS' => $id_so,
                );

                $this->NotifModel->mailbox_insert($data_notif);
                $this->GoodreceiptModel->goodreceipt_update($rcpuniq, $data2);
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/goodreceipt'));
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
            return redirect()->to(base_url('/PurchaseOrder'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/PurchaseOrder'));
        }
    }
}
