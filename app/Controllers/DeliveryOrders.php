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
use App\Models\Deliveryorders_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class DeliveryOrders extends BaseController
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
        $this->DeliveryordersModel = new Deliveryorders_model();
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
                $activenavd = 'deliveryorders';
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
        $deliverydata = $this->DeliveryordersModel->get_gr_pending_to_dn();

        $data = array(
            'delivery_data' => $deliverydata,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_gr_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('deliveryorders'));
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
        return redirect()->to(base_url('deliveryorders/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $deliverydata = $this->DeliveryordersModel->get_gr_pending_to_dn();
        } else {
            $deliverydata = $this->DeliveryordersModel->get_gr_pending_to_dn_search($keyword);
        }
        $data = array(
            'delivery_data' => $deliverydata,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_gr_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update($rcpuniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getshidata = $this->DeliveryordersModel->get_rcp_pending_by_rcpuniq($rcpuniq);
        $reqdate = substr($getshidata['CRMREQDATE'], 4, 2) . '/' . substr($getshidata['CRMREQDATE'], 6, 2) . '/' . substr($getshidata['CRMREQDATE'], 0, 4);
        $podate = substr($getshidata['PODATE'], 4, 2) . '/' . substr($getshidata['PODATE'], 6, 2) . '/' . substr($getshidata['PODATE'], 0, 4);
        $rcpdate = substr($getshidata['RECPDATE'], 4, 2) . '/' . substr($getshidata['RECPDATE'], 6, 2) . '/' . substr($getshidata['RECPDATE'], 0, 4);

        if ($getshidata['SHIUNIQ'] == NULL) {
            $button_text = 'Save';
            $act = 'deliveryorders/insert_action';


            $data = array(
                'csr_uniq' => $getshidata['CSRUNIQ'],
                'ct_no' => $getshidata['CONTRACT'],
                'ct_desc' => $getshidata['CTDESC'],
                'prj_no' => $getshidata['PROJECT'],
                'ct_custno' => $getshidata['CUSTOMER'],
                'ct_custname' => $getshidata['NAMECUST'],
                'crm_no' => $getshidata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => trim($getshidata['ITEMNO']),
                'csr_material_no' => trim($getshidata['MATERIALNO']),
                'csr_item_desc' => trim($getshidata['ITEMDESC']),
                'csr_srvtype' => $getshidata['SERVICETYPE'],
                'csr_qty' => $getshidata['QTY'],
                'csr_uom' => $getshidata['STOCKUNIT'],
                'po_number' => $getshidata['PONUMBER'],
                'po_date' => $podate,
                'rcp_uniq' => $getshidata['RCPRCPUNIQ'],
                'rcp_number' => $getshidata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'rcp_desc' => $getshidata['DESCRIPTIO'],
                'item_no' => $getshidata['RECPITEMNO'],
                'rcp_qty' => $getshidata['RECPQTY'],
                'rcp_unit' => $getshidata['RECPUNIT'],
                'gr_status' => $getshidata['GRSTATUS'],
                'shi_uniq' => '',
                'doc_uniq' => '',
                'sage_shi_number' => '',
                'shi_date' => '',
                'cust_rcp_date' => '',
                'shi_number' => '',
                'shi_itemno' => '',
                'shi_materialno' =>  '',
                'shi_itemdesc' => '',
                'shi_qty' => 0,
                'shi_unit' => '',
                'shi_qty_outs' => 0,
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        } else {
            $button_text = 'Update';
            $act = 'deliveryorders/update_action';
            $shi_date = substr($getshidata['SHIDATE'], 4, 2) . '/' . substr($getshidata['SHIDATE'], 6, 2) . '/' . substr($getshidata['SHIDATE'], 0, 4);
            $cust_rcp_date = substr($getshidata['CUSTRCPDATE'], 4, 2) . '/' . substr($getshidata['CUSTRCPDATE'], 6, 2) . '/' . substr($getshidata['CUSTRCPDATE'], 0, 4);

            $data = array(
                'csr_uniq' => $getshidata['CSRUNIQ'],
                'ct_no' => $getshidata['CONTRACT'],
                'ct_desc' => $getshidata['CTDESC'],
                'prj_no' => $getshidata['PROJECT'],
                'ct_custno' => $getshidata['CUSTOMER'],
                'ct_custname' => $getshidata['NAMECUST'],
                'crm_no' => $getshidata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => trim($getshidata['ITEMNO']),
                'csr_material_no' => trim($getshidata['MATERIALNO']),
                'csr_item_desc' => trim($getshidata['ITEMDESC']),
                'csr_srvtype' => $getshidata['SERVICETYPE'],
                'csr_qty' => $getshidata['QTY'],
                'csr_uom' => $getshidata['STOCKUNIT'],
                'po_number' => $getshidata['PONUMBER'],
                'po_date' => $podate,
                'rcp_uniq' => $getshidata['RCPRCPUNIQ'],
                'rcp_number' => $getshidata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'rcp_desc' => $getshidata['DESCRIPTIO'],
                'item_no' => $getshidata['RECPITEMNO'],
                'rcp_qty' => $getshidata['RECPQTY'],
                'rcp_unit' => $getshidata['RECPUNIT'],
                'gr_status' => $getshidata['GRSTATUS'],
                'shi_uniq' => $getshidata['SHIUNIQ'],
                'doc_uniq' => $getshidata['DOCUNIQ'],
                'sage_shi_number' => $getshidata['DOCNUMBER'],
                'shi_date' => $shi_date,
                'cust_rcp_date' => $cust_rcp_date,
                'shi_number' => $getshidata['SHINUMBER'],
                'shi_itemno' => $getshidata['SHIITEMNO'],
                'shi_materialno' =>  $getshidata['MATERIALNO'],
                'shi_itemdesc' => $getshidata['ITEMDESC'],
                'shi_qty' => $getshidata['SHIQTY'],
                'shi_unit' => $getshidata['SHIUNIT'],
                'shi_qty_outs' => $getshidata['SHIQTYOUTSTANDING'],
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/delivery_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function form_select_sage_shipment($rcp_uniq)
    {
        $getshidata = $this->DeliveryordersModel->get_rcp_pending_by_rcpuniq($rcp_uniq);
        $data = array(
            'sage_shi_number' => $this->DeliveryordersModel->list_sage_shi(),
            'form_action' => base_url("deliveryorders/chooseshipments"),
            'rcp_uniq' => $rcp_uniq,
            'csr_uniq' => $getshidata['CSRUNIQ'],
            'po_number' => $getshidata['PONUMBER'],
            'shi_itemno' => $getshidata['ITEMNO'],
            'shi_materialno' => $getshidata['MATERIALNO'],
            'shi_itemdesc' => $getshidata['ITEMDESC'],
            'shi_unit' => $getshidata['STOCKUNIT'],
        );
        echo view('delivery/ajax_add_sage_shi_number', $data);
    }


    public function chooseshipments()
    {
        if (null == ($this->request->getPost('docuniq'))) {
            $sage_shidocuniq = "shipment number not found";
        } else {
            $sage_shidocuniq = $this->request->getPost('docuniq');
            $rcpuniq = $this->request->getPost('rcp_uniq');
        }

        return redirect()->to(base_url('deliveryorders/selectshipments/' . $rcpuniq . '/' . $sage_shidocuniq));
    }


    public function selectshipments($rcpuniq, $sage_shidocuniq = 0)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getshidata = $this->DeliveryordersModel->get_rcp_pending_by_rcpuniq($rcpuniq);
        $getshisage = $this->DeliveryordersModel->list_sage_shi_by_id($sage_shidocuniq);
        $reqdate = substr($getshidata['CRMREQDATE'], 4, 2) . '/' . substr($getshidata['CRMREQDATE'], 6, 2) . '/' . substr($getshidata['CRMREQDATE'], 0, 4);
        $podate = substr($getshidata['PODATE'], 4, 2) . '/' . substr($getshidata['PODATE'], 6, 2) . '/' . substr($getshidata['PODATE'], 0, 4);
        $rcpdate = substr($getshidata['RECPDATE'], 4, 2) . '/' . substr($getshidata['RECPDATE'], 6, 2) . '/' . substr($getshidata['RECPDATE'], 0, 4);
        $shidate = substr($getshisage['TRANSDATE'], 4, 2) . '/' . substr($getshisage['TRANSDATE'], 6, 2) . '/' . substr($getshisage['TRANSDATE'], 0, 4);

        if ($getshidata['SHIUNIQ'] == NULL) {
            $button_text = 'Save';
            $act = 'deliveryorders/insert_action';


            $data = array(
                'csr_uniq' => $getshidata['CSRUNIQ'],
                'ct_no' => $getshidata['CONTRACT'],
                'ct_desc' => $getshidata['CTDESC'],
                'prj_no' => $getshidata['PROJECT'],
                'ct_custno' => $getshidata['CUSTOMER'],
                'ct_custname' => $getshidata['NAMECUST'],
                'crm_no' => $getshidata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => trim($getshidata['ITEMNO']),
                'csr_material_no' => trim($getshidata['MATERIALNO']),
                'csr_item_desc' => trim($getshidata['ITEMDESC']),
                'csr_srvtype' => $getshidata['SERVICETYPE'],
                'csr_qty' => $getshidata['QTY'],
                'csr_uom' => $getshidata['STOCKUNIT'],
                'po_number' => $getshidata['PONUMBER'],
                'po_date' => $podate,
                'rcp_uniq' => $getshidata['RCPRCPUNIQ'],
                'rcp_number' => $getshidata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'rcp_desc' => $getshidata['DESCRIPTIO'],
                'item_no' => $getshidata['RECPITEMNO'],
                'rcp_qty' => $getshidata['RECPQTY'],
                'rcp_unit' => $getshidata['RECPUNIT'],
                'gr_status' => $getshidata['GRSTATUS'],
                'shi_uniq' => '',
                'doc_uniq' => $getshisage['DOCUNIQ'],
                'sage_shi_number' => $getshisage['DOCNUM'],
                'shi_date' => $shidate,
                'cust_rcp_date' => '',
                'shi_number' => '',
                'shi_itemno' => $getshidata['ITEMNO'],
                'shi_materialno' =>  $getshidata['MATERIALNO'],
                'shi_itemdesc' => $getshidata['ITEMDESC'],
                'shi_qty' => 0,
                'shi_unit' => $getshidata['STOCKUNIT'],
                'shi_qty_outs' => 0,
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        } else {

            $button_text = 'Update';
            $act = 'deliveryorders/update_action';


            $data = array(
                'csr_uniq' => $getshidata['CSRUNIQ'],
                'ct_no' => $getshidata['CONTRACT'],
                'ct_desc' => $getshidata['CTDESC'],
                'prj_no' => $getshidata['PROJECT'],
                'ct_custno' => $getshidata['CUSTOMER'],
                'ct_custname' => $getshidata['NAMECUST'],
                'crm_no' => $getshidata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => trim($getshidata['ITEMNO']),
                'csr_material_no' => trim($getshidata['MATERIALNO']),
                'csr_item_desc' => trim($getshidata['ITEMDESC']),
                'csr_srvtype' => $getshidata['SERVICETYPE'],
                'csr_qty' => $getshidata['QTY'],
                'csr_uom' => $getshidata['STOCKUNIT'],
                'po_number' => $getshidata['PONUMBER'],
                'po_date' => $podate,
                'rcp_uniq' => $getshidata['RCPRCPUNIQ'],
                'rcp_number' => $getshidata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'rcp_desc' => $getshidata['DESCRIPTIO'],
                'item_no' => $getshidata['RECPITEMNO'],
                'rcp_qty' => $getshidata['RECPQTY'],
                'rcp_unit' => $getshidata['RECPUNIT'],
                'gr_status' => $getshidata['GRSTATUS'],
                'shi_uniq' => $getshidata['SHIUNIQ'],
                'doc_uniq' => $getshisage['DOCUNIQ'],
                'sage_shi_number' => $getshisage['DOCNUM'],
                'shi_date' => $shidate,
                'cust_rcp_date' => '',
                'shi_number' => '',
                'shi_itemno' => trim($getshidata['SHIITEMNO']),
                'shi_materialno' => trim($getshidata['MATERIALNO']),
                'shi_itemdesc' => trim($getshidata['ITEMDESC']),
                'shi_qty' => 0,
                'shi_unit' => trim($getshidata['SHIUNIT']),
                'shi_qty_outs' => 0,
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/delivery_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function insert_action()
    {
        if (!$this->validate([
            'sage_shi_number' => 'required',
            'shi_date' => 'required',
            'cust_rcp_date' => 'required',
            'shi_number' => 'required',
            'shi_itemno' => 'required',
            'shi_qty' => 'required|numeric|greater_than[0]',

        ])) {
            $rcp_uniq = $this->request->getPost('rcp_uniq');
            $doc_uniq = $this->request->getPost('doc_uniq');

            session()->set('success', '-1');
            return redirect()->to(base_url('/deliveryorders/selectshipments/' . $rcp_uniq . '/' . $doc_uniq))->withInput();
        } else {
            $csruniq = $this->request->getPost('csr_uniq');
            $rcpuniq = $this->request->getPost('rcp_uniq');
            $docuniq = $this->request->getPost('doc_uniq');
            $shi_date = $this->request->getPost('shi_date');
            $cust_rcp_date = $this->request->getPost('cust_rcp_date');
            $shi_date = substr($shi_date, 6, 4)  . "" . substr($shi_date, 0, 2) . "" . substr($shi_date, 3, 2);
            $cust_rcp_date = substr($cust_rcp_date, 6, 4)  . "" . substr($cust_rcp_date, 0, 2) . "" . substr($cust_rcp_date, 3, 2);
            if ($this->request->getPost('shi_qty') == $this->request->getPost('csr_qty')) {
                $pocust_status = 1;
                $shi_qtyoutstanding = 0;
            } else {
                $pocust_status = 0;
                $shi_qtyoutstanding = ($this->request->getPost('shi_qty')) - ($this->request->getPost('csr_qty'));
            }

            $groupuser = 7;

            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $this->request->getPost('csr_uniq'),
                'RCPUNIQ' => $this->request->getPost('rcp_uniq'),
                'DOCUNIQ' => $this->request->getPost('doc_uniq'),
                'DOCNUMBER' => $this->request->getPost('sage_shi_number'),
                'SHINUMBER' => $this->request->getPost('shi_number'),
                'SHIDATE' => $shi_date,
                'CUSTRCPDATE' => $cust_rcp_date,
                'CUSTOMER' => $this->request->getPost('csr_custno'),
                'CONTRACT' => $this->request->getPost('csr_contract'),
                'PROJECT' => $this->request->getPost('csr_project'),
                'SHIITEMNO' => $this->request->getPost('shi_itemno'),
                'SHIQTY' => $this->request->getPost('shi_qty'),
                'SHIQTYOUTSTANDING' => $shi_qtyoutstanding,
                'SHIUNIT' => $this->request->getPost('shi_unit'),
                'POCUSTSTATUS' => $pocust_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );

            //print_r($data_notif);
            $deliveryorders_insert = $this->DeliveryordersModel->deliveryorders_insert($data);

            if ($deliveryorders_insert) {

                $getshiuniq = $this->DeliveryordersModel->get_shiuniq_open($csruniq, $rcpuniq, $docuniq);
                //session()->setFlashdata('messageerror', 'Create Record Failed');
                session()->set('success', '1');
                return redirect()->to(base_url('/deliveryorders/shipmentopenview/' . $getshiuniq['SHIUNIQ']));
                session()->remove('success');
            }
        }
    }


    public function update_action()
    {
        if (!$this->validate([
            'sage_shi_number' => 'required',
            'shi_date' => 'required',
            'cust_rcp_date' => 'required',
            'shi_number' => 'required',
            'shi_itemno' => 'required',
            'shi_qty' => 'required|numeric|greater_than[0]',

        ])) {
            $rcp_uniq = $this->request->getPost('rcp_uniq');
            $doc_uniq = $this->request->getPost('doc_uniq');

            session()->set('success', '-1');
            return redirect()->to(base_url('/deliveryorders/selectshipments/' . $rcp_uniq . '/' . $doc_uniq))->withInput();
        } else {

            $csruniq = $this->request->getPost('csr_uniq');
            $rcpuniq = $this->request->getPost('rcp_uniq');
            $docuniq = $this->request->getPost('doc_uniq');
            $shiuniq = $this->request->getPost('shi_uniq');
            $shi_date = $this->request->getPost('shi_date');
            $cust_rcp_date = $this->request->getPost('cust_rcp_date');
            $shi_date = substr($shi_date, 6, 4)  . "" . substr($shi_date, 0, 2) . "" . substr($shi_date, 3, 2);
            $cust_rcp_date = substr($cust_rcp_date, 6, 4)  . "" . substr($cust_rcp_date, 0, 2) . "" . substr($cust_rcp_date, 3, 2);
            if ($this->request->getPost('shi_qty') == $this->request->getPost('csr_qty')) {
                $pocust_status = 1;
                $shi_qtyoutstanding = 0;
            } else {
                $pocust_status = 0;
                $shi_qtyoutstanding = ($this->request->getPost('shi_qty')) - ($this->request->getPost('csr_qty'));
            }

            $groupuser = 7;

            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $this->request->getPost('csr_uniq'),
                'RCPUNIQ' => $this->request->getPost('rcp_uniq'),
                'DOCUNIQ' => $this->request->getPost('doc_uniq'),
                'DOCNUMBER' => $this->request->getPost('sage_shi_number'),
                'SHINUMBER' => $this->request->getPost('shi_number'),
                'SHIDATE' => $shi_date,
                'CUSTRCPDATE' => $cust_rcp_date,
                'CUSTOMER' => $this->request->getPost('csr_custno'),
                'CONTRACT' => $this->request->getPost('csr_contract'),
                'PROJECT' => $this->request->getPost('csr_project'),
                'SHIITEMNO' => $this->request->getPost('shi_itemno'),
                'SHIQTY' => $this->request->getPost('shi_qty'),
                'SHIQTYOUTSTANDING' => $shi_qtyoutstanding,
                'SHIUNIT' => $this->request->getPost('shi_unit'),
                'POCUSTSTATUS' => $pocust_status,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );

            //print_r($data_notif);
            $deliveryorders_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);

            if ($deliveryorders_update) {

                $getshiuniq = $this->DeliveryordersModel->get_shiuniq_open($csruniq, $rcpuniq, $docuniq);
                //session()->setFlashdata('messageerror', 'Create Record Failed');
                session()->set('success', '1');
                return redirect()->to(base_url('/deliveryorders/shipmentopenview/' . $getshiuniq['SHIUNIQ']));
                session()->remove('success');
            }
        }
    }


    public function shipmentopenview($shiuniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getshiopen = $this->DeliveryordersModel->get_shipment_open($shiuniq);

        /*if (empty($getshiopen['POSTINGSTAT']) and empty($getshiopen['EDNFILENAME'])) {
            return redirect()->to(base_url('/deliveryorders/'));
            session()->remove('success');
        } else 
        */
        if ($getshiopen['POSTINGSTAT'] == 0 and empty($getshiopen['EDNFILENAME'])) {
            $data = array(
                'shiopen_data' =>  $getshiopen,
                'link_action' => 'deliveryorders/posting/',
                'btn_color' => 'bg-blue',
                'button' => 'Posting',
            );
        } else if ($getshiopen['POSTINGSTAT'] == 1) {

            $data = array(
                'shiopen_data' =>  $getshiopen,
                'link_action' => 'deliveryorders/sendnotif/',
                'btn_color' => 'bg-orange',
                'button' => 'Send Notification Manually',
            );
        }



        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_delivery_view', $data);
        echo view('view_footer', $this->footer_data);
    }




    public function posting($shiuniq)
    {
        $getshiopen = $this->DeliveryordersModel->get_shipment_open($shiuniq);
        $id_so = $getshiopen['CSRUNIQ'];
        $id_rcp = $getshiopen['RCPUNIQ'];


        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 7;
        $data = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'POSTINGSTAT' => 1,
            'OFFLINESTAT' => $sender['OFFLINESTAT'],

        );
        $shi_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);

        if ($shi_update) {
            $pocust_date = date_create(substr($getshiopen['PODATECUST'], 4, 2) . "/" . substr($getshiopen['PODATECUST'], 6, 2) . "/" .  substr($getshiopen['PODATECUST'], 0, 4));
            $crmreqdate = date_create(substr($getshiopen['CRMREQDATE'], 4, 2) . "/" . substr($getshiopen['CRMREQDATE'], 6, 2) . "/" . substr($getshiopen['CRMREQDATE'], 0, 4));
            $shi_date = date_create(substr($getshiopen['SHIDATE'], 4, 2) . "/" . substr($getshiopen['SHIDATE'], 6, 2) . "/" .  substr($getshiopen['SHIDATE'], 0, 4));
            $ontimedeldiff = date_diff($shi_date, $crmreqdate);
            $ontimedeldiff = $ontimedeldiff->format("%a");
            $potodndiff = date_diff($shi_date, $pocust_date);
            $potodndiff = $potodndiff->format("%a");
            $data2 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'SHIDOCNUMBER' => $getshiopen['DOCNUMBER'],
                'SHINUMBER' => $getshiopen['SHINUMBER'],
                'SHIDATE' => $getshiopen['SHIDATE'],
                'CUSTRCPDATE' => $getshiopen['CUSTRCPDATE'],
                'SHIQTY' => $getshiopen['SHIQTY'],
                'SHIQTYOUTSTANDING' => $getshiopen['SHIQTYOUTSTANDING'],
                'SHIUNIT' => $getshiopen['SHIUNIT'],
                'POCUSTSTATUS' => $getshiopen['POCUSTSTATUS'],
                'ONTIMEDELDAYS' => $ontimedeldiff,
                'POTODNDAYS' => $potodndiff,

            );
            $this->DeliveryordersModel->ot_deliveryorders_update($id_so, $data2);
        }

        session()->set('success', '1');
        return redirect()->to(base_url('/deliveryorders'));
        session()->remove('success');
    }


    public function edn_upload_action()
    {
        $shiuniq = $this->request->getPost('shiuniq');
        if (!$this->validate([
            'edn_file' => 'uploaded[edn_file]|max_size[edn_file,2048]|mime_in[edn_file,image/jpg,image/jpeg,application/pdf]',
        ])) {
            return redirect()->to(base_url('/deliveryorders/shipmentopenview/' . $shiuniq));
        } else {
            $shidate = $this->request->getPost('shidate');
            $shidocnum = trim($this->request->getPost('shidocnum'));

            //Ambil Path sumber Gambar
            $edn_filepath = $this->request->getFile('edn_file');
            //generate file name & nama icon
            $filename = 'e-dn_' . $shidocnum . '_' . $shidate . '.' . $edn_filepath->getExtension();
            //pindahkan file ke folder img\icon di public
            $edn_filepath->move('assets/files/edn_attached/', $filename);
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'EDNFILENAME' => $filename,
                'EDNFILEPATH' => 'assets/files/edn_attached/' . $filename,

            );

            $deliveryorders_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);

            session()->set('success', '1');
            return redirect()->to(base_url('/deliveryorders/shipmentopenview/' . $shiuniq));
            session()->remove('success');
        }
    }




    public function sendnotif($shiuniq)
    {
        //check dari sini
        $get_shi = $this->DeliveryordersModel->get_shipment_post($shiuniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $id_so = $get_shi['CSRUNIQ'];
        $rcpuniq = $get_shi['RCPUNIQ'];
        $shiuniq = $get_shi['SHIUNIQ'];
        $shi_date = substr($get_shi['SHIDATE'], 4, 2) . '/' . substr($get_shi['SHIDATE'], 6, 2) . '/' . substr($get_shi['SHIDATE'], 0, 4);
        $pocuststatus = $get_shi['POCUSTSTATUS'];
        switch ($pocuststatus) {
            case "0":
                $pocuststatus = "Outstanding";
                break;
            case "1":
                $pocuststatus = "Completed";
                break;
            default:
                $pocuststatus = "";
        }

        if (!empty($get_shi['EDNFILENAME'])) {

            $is_attachment = 1;
        } else {
            $is_attachment = 0;
        }



        $groupuser = 7;
        //inisiasi proses kirim ke group
        $data2 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'OFFLINESTAT' => 0,
        );

        // Khusus untuk PROSES Delivery Note Model nya berbeda karena harus kirim ke customer juga
        $notiftouser_data = $this->NotifModel->get_edn_sendto_user($groupuser, $id_so);

        foreach ($notiftouser_data as $sendto_user) {
            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' => 'Pending Good Receipts Allert. Delivery Note :' . $get_shi['DOCNUMBER'] . '/' . $get_shi['SHINUMBER'],
                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                Please to follow up Delivery Note Number :' . $get_shi['SHINUMBER'] . 'is pending for you to process Sales Admin Team.
    <br><br>
    Delivery Number :' . $get_shi['DOCNUMBER'] . '<br>
    Shipment Number :' . $get_shi['SHINUMBER'] . '<br>
    Shipment Date :' . $shi_date . '<br>
    PO Customer Status :' . $pocuststatus . '<br>
    <hr>
    You can access Order Tracking System Portal via the URL below:
    <br>
    Http://jktsms025:...
    <br>
    Thanks for your cooperation. 
    <br><br>
    Order Tracking Administrator',

                //TAMBAHAN EMAIL ATTACHMENT
                'attachment_filepath' => $get_shi['EDNFILEPATH'],
                'attachment_filename' => $get_shi['EDNFILENAME'],
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
                    'SUBJECT' => 'Pending Good Receipts Allert. Delivery Note :' . $get_shi['DOCNUMBER'] . '/' . $get_shi['SHINUMBER'],
                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

                    Please to follow up Delivery Note Number :' . $get_shi['SHINUMBER'] . 'is pending for you to process Sales Admin Team.
        <br><br>
        Delivery Number :' . $get_shi['DOCNUMBER'] . '<br>
        Shipment Number :' . $get_shi['SHINUMBER'] . '<br>
        Shipment Date :' . $shi_date . '<br>
        PO Customer Status :' . $pocuststatus . '<br>
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
                    'IS_ATTACHED' => $is_attachment,
                    'IS_STAR' => 0,
                    'IS_READSENDER' => 1,
                    'IS_ARCHIVEDSENDER' => 0,
                    'IS_TRASHEDSENDER' => 0,
                    'IS_DELETEDSENDER' => 0,
                    'SENDING_STATUS' => 1,
                    //TAMBAHAN EMAIL ATTACHMENT
                    'ATTACHMENT_FILENAME' => $get_shi['EDNFILENAME'],
                    'ATTACHMENT_FILEPATH' => $get_shi['EDNFILEPATH'],
                    'OTPROCESS' => $groupuser,
                    'UNIQPROCESS' => $id_so,
                );

                $this->NotifModel->mailbox_insert($data_notif);
                $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data2);
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/deliveryorders'));
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
        $attachment_filepath = $data_email['attachment_filepath'];
        $attachment_filename = $data_email['attachment_filename'];

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
            if (!empty($attachment_filename)) {
                $mail->AddAttachment($attachment_filepath, $attachment_filename);   // I took this from the phpmailer example on github but I'm not sure if I have it right.      
            }
            $mail->send();
            session()->setFlashdata('success', 'Send Email successfully');
            return redirect()->to(base_url('/Deliveryorders'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/Deliveryorders'));
        }
    }
}
