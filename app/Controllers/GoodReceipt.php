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
    private $cart;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->db_name = \Config\Database::connect();
        $this->cart = \Config\Services::cart();

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
            if (session()->get('keylog') == $infouser['passlgn'] and session()->get('userhash') == $infouser['userhashlgn']) {


                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'photolgn' => $infouser['photolgn'],
                    'userhashlgn' => $infouser['userhashlgn'],
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
                    'NAMELGN' => $infouser['namalgn'],

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
        session()->remove('sage_rcphseq');
        $this->cart->destroy();
        $receiptdata = $this->GoodreceiptModel->get_po_pending_to_gr();
        $grlist_data = $this->GoodreceiptModel->get_grlist_on_gropen();
        $po_l_open_data = $this->GoodreceiptModel->get_pol_list_post();

        $data = array(
            'receipt_data' => $receiptdata,
            'grlist_data' => $grlist_data,
            'po_l_data' => $po_l_open_data,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/data_po_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('goodreceipt'));
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
        return redirect()->to(base_url('goodreceipt/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $receiptdata = $this->GoodreceiptModel->get_po_pending_to_gr();
        } else {
            $receiptdata = $this->GoodreceiptModel->get_po_pending_to_gr_search($keyword);
        }
        $grlist_data = $this->GoodreceiptModel->get_grlist_on_gropen();
        $po_l_open_data = $this->GoodreceiptModel->get_pol_list_post();
        $data = array(
            'receipt_data' => $receiptdata,
            'grlist_data' => $grlist_data,
            'po_l_data' => $po_l_open_data,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/data_po_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function view_gr_number($pouniq, $itemno)
    {
        $data = array(
            'grlist_by_po_data' => $this->GoodreceiptModel->get_grlist_posting($pouniq, $itemno),

        );
        echo view('goodreceipt/ajax_view_grlist', $data);
    }


    public function add($pouniq, $post_stat, $delgrline)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($pouniq);
        $get_po_l = $this->GoodreceiptModel->get_po_l_by_id($pouniq);
        $reqdate = substr($getpodata['CRMREQDATE'], 4, 2) . '/' . substr($getpodata['CRMREQDATE'], 6, 2) . '/' . substr($getpodata['CRMREQDATE'], 0, 4);
        $podate = substr($getpodata['PODATE'], 4, 2) . '/' . substr($getpodata['PODATE'], 6, 2) . '/' . substr($getpodata['PODATE'], 0, 4);
        $etaportdate = substr($getpodata['ETAPORTDATE'], 4, 2) . '/' . substr($getpodata['ETAPORTDATE'], 6, 2) . '/' . substr($getpodata['ETAPORTDATE'], 0, 4);

        $act = 'goodreceipt/insert_action';
        if ($post_stat == 0) {
            $button_text = 'Save';
        } else {
            $button_text = 'Save & Posting';
        }


        if (!empty(session()->get('sage_rcphseq'))) {
            $getrcpdata = $this->GoodreceiptModel->get_receipt_sage_by_id(session()->get('sage_rcphseq'));
            $rcpdate = substr($getrcpdata['RCPDATE'], 4, 2) . '/' . substr($getrcpdata['RCPDATE'], 6, 2) . '/' . substr($getrcpdata['RCPDATE'], 0, 4);
            $rcphseq = session()->get('sage_rcphseq');
            $rcp_number = trim($getrcpdata['RCPNUMBER']);
            $rcp_date = $rcpdate;
            $vd_name = $getrcpdata['VDNAME'];
            $rcp_desc = $getrcpdata['DESCRIPTIO'];
        } else {
            $rcphseq = '';
            $rcp_number = '';
            $rcp_date = '';
            $vd_name = '';
            $rcp_desc = '';
        }
        if ($delgrline == 0) {
            foreach ($get_po_l as $items) :
                $this->cart->insert(array(
                    'id'      => trim($items['ITEMNO']),
                    'qty'     => $items['QTYRCP_OUTS'],
                    'price'   => '1',
                    'name'    => 'Item Description Sage',
                    'options' => array('so_service' => $items['SERVICETYPE'], 'material_no' => $items['MATERIALNO'], 'itemdesc' => $items['ITEMDESC'], 'so_qty' => $items['QTYRCP_OUTS'], 'gr_qty' => 0, 'so_uom' => $items['STOCKUNIT'], 'csruniq' => $items['CSRUNIQ'], 'csrluniq' => $items['CSRLUNIQ'], 'pouniq' => $items['POUNIQ'], 'poluniq' => $items['POLUNIQ'])
                ));
            endforeach;
        }

        if ($this->cart->totalItems() == 0) {
            return redirect()->to(base_url('goodreceipt'));
        }

        $data = array(
            'csr_uniq' => $getpodata['CSRUNIQ'],
            'ct_no' => $getpodata['CONTRACT'],
            'ct_desc' => $getpodata['CTDESC'],
            'prj_no' => $getpodata['PROJECT'],
            'ct_custname' => $getpodata['NAMECUST'],
            'ct_email1' => $getpodata['EMAIL1CUST'],
            'crm_no' => $getpodata['CRMNO'],
            'req_date' => $reqdate,
            'po_uniq' => $getpodata['POUNIQ'],
            'po_number' => $getpodata['PONUMBER'],
            'po_date' => $podate,
            'etaport_date' => $etaportdate,
            'origin_country' => $getpodata['ORIGINCOUNTRY'],
            'vendorshi_status' => $getpodata['VENDSHISTATUS'],
            'rcpuniq' => '',
            'rcphseq' => $rcphseq,
            'rcp_number' => $rcp_number,
            'rcp_date' => $rcp_date,
            'vd_name' => $vd_name,
            'rcp_desc' => $rcp_desc,
            'button_text' => $button_text,
            'post_stat' => $post_stat,
            'delgrline' => $delgrline,
            'form_action' => base_url($act),
            'polforrcp_data' => $this->cart->contents(),
            'cart' => $this->cart,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('goodreceipt/goodreceipt_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function form_select_goodreceipt($po_uniq, $post_stat, $delgrline)
    {
        $getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($po_uniq);
        $data = array(
            'gr_by_po' => $this->GoodreceiptModel->list_gr_by_po($getpodata['PONUMBER']),
            'form_action' => base_url("goodreceipt/choosegoodreceipt"),
            'po_uniq' => $po_uniq,
            'post_stat' => $post_stat,
            'delgrline' => $delgrline,
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


    public function choosegoodreceipt()
    {
        if (null == ($this->request->getPost('rcph_seq'))) {
            $sage_rcphseq = "receipt number not found";
        } else {
            $sage_rcphseq = $this->request->getPost('rcph_seq');
            $po_uniq = $this->request->getPost('po_uniq');
            $post_stat = $this->request->getPost('post_stat');
            $delgrline = $this->request->getPost('delgrline');
            session()->set('sage_rcphseq', $sage_rcphseq);
        }

        return redirect()->to(base_url('goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline));
    }


    public function form_update_item($po_uniq, $post_stat, $rowid, $itemno, $delgrline)
    {
        //$getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($po_uniq);
        $poitem = $this->GoodreceiptModel->get_po_l_item($po_uniq, $itemno);
        $data = array(
            'form_action' => base_url("goodreceipt/chooseitem"),
            'po_uniq' => $po_uniq,
            'post_stat' => $post_stat,
            'delgrline' => $delgrline,
            'rcphseq' => '',
            'rowid' => $rowid,
            'csr_uniq' => $poitem['CSRUNIQ'],
            'csrl_uniq' => $poitem['CSRLUNIQ'],
            'pol_uniq' => $poitem['POLUNIQ'],
            'itemno' => $itemno,
            'material_no' => $poitem['MATERIALNO'],
            'itemdesc' => $poitem['ITEMDESC'],
            'service_type' => $poitem['SERVICETYPE'],
            'uom' => $poitem['STOCKUNIT'],
            'gr_qty' => number_format($poitem['QTYRCP_OUTS'], 0, ",", "."),
            'select_item' => '',
        );

        echo view('goodreceipt/ajax_input_item_gr', $data);
    }


    public function chooseitem()
    {
        if (null == ($this->request->getPost('row_id'))) {
            $row_id = "Item not found";
        } else {
            $row_id = $this->request->getPost('row_id');
            $service_type = $this->request->getPost('service_type');
            $inventory_no = $this->request->getPost('inventory_no');
            $material_no = $this->request->getPost('material_no');
            $itemdesc = $this->request->getPost('itemdesc');
            $uom = $this->request->getPost('uom');
            $so_qty = $this->request->getPost('so_qty');
            $gr_qty = $this->request->getPost('gr_qty');
            $csr_uniq = $this->request->getPost('csr_uniq');
            $csrl_uniq = $this->request->getPost('csrl_uniq');
            $po_uniq = $this->request->getPost('po_uniq');
            $pol_uniq = $this->request->getPost('pol_uniq');
            $post_stat = $this->request->getPost('post_stat');
            $delgrline = $this->request->getPost('delgrline');
            // data option harus di bawa
            $this->cart->update(array(
                'rowid'   => $row_id,
                'id'      => $inventory_no,
                'qty'     => '1',
                'price'   => '1',
                'name'    => 'Item Description Sage',
                'options' => array('so_service' => $service_type, 'material_no' => $material_no, 'itemdesc' => $itemdesc, 'so_qty' => $so_qty, 'gr_qty' => $gr_qty, 'so_uom' => $uom, 'csruniq' => $csr_uniq, 'csrluniq' => $csrl_uniq, 'pouniq' => $po_uniq, 'poluniq' => $pol_uniq)
            ));
        }

        return redirect()->to(base_url('goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline));
    }

    // delete rcp open
    public function delete($rcpuniq)
    {
        $chk_rcp = $this->GoodreceiptModel->get_goodreceipt_open($rcpuniq);
        if ($chk_rcp['POSTINGSTAT'] == 1) {
            session()->set('success', '-1');
            return redirect()->to(base_url('goodreceipt'));
            session()->remove('success');
        } else {
            // Remove an PO Open
            $del_rcp_open = $this->GoodreceiptModel->delete_rcp_open($rcpuniq);
            if ($del_rcp_open) {
                $this->GoodreceiptModel->delete_rcpl_open($rcpuniq);
            }

            session()->set('success', '1');
            return redirect()->to(base_url('goodreceipt'));
            session()->remove('success');
        }
    }

    // delete item Cart
    public function delete_item_cart($po_uniq, $post_stat, $rowid, $delgrline)
    {
        // Remove an item using its `rowid`
        $this->cart->remove($rowid);
        return redirect()->to(base_url('goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline));
    }


    public function insert_action()
    {
        $po_uniq = $this->request->getPost('po_uniq');
        $rcp_number = $this->request->getPost('rcp_number');
        $post_stat = $this->request->getPost('post_stat');
        $delgrline = $this->request->getPost('delgrline');
        $gr_total = $this->request->getPost('gr_total');
        if (!$this->validate([
            'rcp_number' => 'required',
            'rcp_date' => 'required',
            'vd_name' => 'required',
            'rcp_desc' => 'required',
            'gr_total' => 'required|greater_than[0]',

        ])) {


            if (($rcp_number == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline))->withInput();
            } else if (($rcp_number <> "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline))->withInput();
            } else if (($rcp_number <> "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline))->withInput();
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
            $po_number = $this->request->getPost('po_number');
            $rcp_number = $this->request->getPost('rcp_number');
            $groupuser = 6;

            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CSRUNIQ' => $csruniq,
                'POUNIQ' => $this->request->getPost('po_uniq'),
                'PONUMBER' => $po_number,
                'RCPKEY' => $csruniq . '-' . trim($po_number) . '-' . trim($rcp_number),
                'RCPHSEQ' => $this->request->getPost('rcph_seq'),
                'RECPNUMBER' => $this->request->getPost('rcp_number'),
                'RECPDATE' => $rcp_date,
                'VDNAME' => $this->request->getPost('vd_name'),
                'DESCRIPTIO' => $this->request->getPost('rcp_desc'),
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );



            $getrcpuniq = $this->GoodreceiptModel->get_rcpuniq_open($csruniq, $po_number, $rcp_number);
            if (!empty($getrcpuniq['RCPKEY']) and $getrcpuniq['CHKRCPL'] > 0 and $getrcpuniq['RCPKEY'] == $csruniq . '-' . $po_number . '-' . $rcp_number) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt/add/' . $po_uniq . '/' . $post_stat . '/' . $delgrline));
                session()->remove('success');
            } else if (!empty($getrcpuniq['RCPKEY']) and $getrcpuniq['CHKRCPL'] == 0 and $getrcpuniq['RCPKEY'] == $csruniq . '-' . $po_number . '-' . $rcp_number) {

                foreach ($this->cart->contents() as $items) :
                    if ($items['options']['gr_qty'] > 0) {
                        $datal = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                            'AUDTORG' => trim($this->audtuser['AUDTORG']),
                            'RCPUNIQ' => $getrcpuniq['RCPUNIQ'],
                            'CSRUNIQ' => $items['options']['csruniq'],
                            'CSRLUNIQ' => $items['options']['csrluniq'],
                            'POUNIQ' => $items['options']['pouniq'],
                            'POLUNIQ' => $items['options']['poluniq'],
                            'SERVICETYPE' => $items['options']['so_service'],
                            'ITEMNO' => $items['id'],
                            'MATERIALNO' => $items['options']['material_no'],
                            'ITEMDESC' => $items['options']['itemdesc'],
                            'STOCKUNIT' => $items['options']['so_uom'],
                            'QTY' => $items['options']['gr_qty'],
                        );
                        $rcpl_insert = $this->GoodreceiptModel->rcpline_insert($datal);
                    }
                endforeach;
                if ($rcpl_insert) {
                    $this->cart->destroy();
                    session()->set('success', '1');
                    return redirect()->to(base_url('goodreceipt'));
                    session()->remove('success');
                }
            } else if (empty($getrcpuniq['RCPKEY'])) {
                $receipt_insert = $this->GoodreceiptModel->goodreceipt_insert($data);
                if ($receipt_insert) {
                    $getrcpuniq = $this->GoodreceiptModel->get_rcpuniq_open($csruniq, $po_number, $rcp_number);
                    $rcpuniq = $getrcpuniq['RCPUNIQ'];
                    foreach ($this->cart->contents() as $items) :
                        if ($items['options']['gr_qty'] > 0) {
                            $datal = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                                'AUDTORG' => trim($this->audtuser['AUDTORG']),
                                'RCPUNIQ' => $getrcpuniq['RCPUNIQ'],
                                'CSRUNIQ' => $items['options']['csruniq'],
                                'CSRLUNIQ' => $items['options']['csrluniq'],
                                'POUNIQ' => $items['options']['pouniq'],
                                'POLUNIQ' => $items['options']['poluniq'],
                                'SERVICETYPE' => $items['options']['so_service'],
                                'ITEMNO' => $items['id'],
                                'MATERIALNO' => $items['options']['material_no'],
                                'ITEMDESC' => $items['options']['itemdesc'],
                                'STOCKUNIT' => $items['options']['so_uom'],
                                'QTY' => $items['options']['gr_qty'],
                            );

                            $rcpl_insert = $this->GoodreceiptModel->rcpline_insert($datal);
                        }
                    endforeach;

                    $this->cart->destroy();


                    // Jika Posting
                    if ($post_stat == 1) {
                        $rcp_to_ot = $this->GoodreceiptModel->get_rcp_open_by_id($rcpuniq, $csruniq);
                        $sender = $this->AdministrationModel->get_mailsender();
                        $groupuser = 6;

                        $data = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 1,

                        );
                        $gr_update = $this->GoodreceiptModel->goodreceipt_update($rcpuniq, $data);

                        if ($gr_update) {
                            $get_rcp = $this->GoodreceiptModel->get_goodreceipt_post($po_uniq);
                            foreach ($rcp_to_ot as $data_rcpl) :
                                $csruniq = $data_rcpl['CSRUNIQ'];
                                $csrluniq = $data_rcpl['CSRLUNIQ'];
                                if ($data_rcpl['QTY'] == $data_rcpl['S_QTYRCP']) {
                                    $grstatus = 1;
                                } else if ($data_rcpl['QTY'] > $data_rcpl['S_QTYRCP']) {
                                    $grstatus = 0;
                                }

                                $data2 = array(
                                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                    'AUDTORG' => $this->audtuser['AUDTORG'],
                                    'RECPDATE' => $get_rcp['RECPDATE'],
                                    'RECPQTY' => $data_rcpl['S_QTYRCP'],
                                    'GRSTATUS' => $grstatus,
                                );
                                $this->GoodreceiptModel->ot_goodreceipt_update($csruniq, $csrluniq, $data2);
                            endforeach;


                            if ($sender['OFFLINESTAT'] == 0) {
                                //Untuk Update Status Posting CSR
                                $data3 = array(
                                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                    'AUDTORG' => $this->audtuser['AUDTORG'],
                                    'POSTINGSTAT' => 1,
                                    'OFFLINESTAT' => 0,
                                );
                                //inisiasi proses kirim ke group
                                $get_rcp_data = $this->GoodreceiptModel->get_rcpjoincsr_by_rcp($rcpuniq);
                                $crmpodate = substr($get_rcp_data['PODATECUST'], 4, 2) . "/" . substr($get_rcp_data['PODATECUST'], 6, 2) . "/" .  substr($get_rcp_data['PODATECUST'], 0, 4);
                                $crmreqdate = substr($get_rcp_data['CRMREQDATE'], 4, 2) . '/' . substr($get_rcp_data['CRMREQDATE'], 6, 2) . '/' . substr($get_rcp_data['CRMREQDATE'], 0, 4);
                                $rqndate = substr($get_rcp_data['RQNDATE'], 4, 2) . "/" . substr($get_rcp_data['RQNDATE'], 6, 2) . "/" .  substr($get_rcp_data['RQNDATE'], 0, 4);
                                $povendordate = substr($get_rcp_data['PODATE'], 4, 2) . "/" . substr($get_rcp_data['PODATE'], 6, 2) . "/" .  substr($get_rcp_data['PODATE'], 0, 4);
                                $etddate = substr($get_rcp_data['ETDDATE'], 4, 2) . "/" . substr($get_rcp_data['ETDDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETDDATE'], 0, 4);
                                $cargoreadinessdate = substr($get_rcp_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_rcp_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_rcp_data['CARGOREADINESSDATE'], 0, 4);
                                $etdorigindate = substr($get_rcp_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_rcp_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETDORIGINDATE'], 0, 4);
                                $atdorigindate = substr($get_rcp_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_rcp_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_rcp_data['ATDORIGINDATE'], 0, 4);
                                $etaportdate = substr($get_rcp_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_rcp_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETAPORTDATE'], 0, 4);
                                $pibdate = substr($get_rcp_data['PIBDATE'], 4, 2) . "/" . substr($get_rcp_data['PIBDATE'], 6, 2) . "/" .  substr($get_rcp_data['PIBDATE'], 0, 4);
                                $rcpdate = substr($get_rcp_data['RECPDATE'], 4, 2) . "/" . substr($get_rcp_data['RECPDATE'], 6, 2) . "/" .  substr($get_rcp_data['RECPDATE'], 0, 4);

                                $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                                $mail_tmpl = $this->NotifModel->get_template($groupuser);

                                foreach ($notiftouser_data as $sendto_user) :
                                    $var_email = array(
                                        'TONAME' => $sendto_user['NAME'],
                                        'FROMNAME' => $this->audtuser['NAMELGN'],
                                        'CONTRACT' => $get_rcp_data['CONTRACT'],
                                        'CTDESC' => $get_rcp_data['CTDESC'],
                                        'PROJECT' => $get_rcp_data['PROJECT'],
                                        'PRJDESC' => $get_rcp_data['PRJDESC'],
                                        'CUSTOMER' => $get_rcp_data['CUSTOMER'],
                                        'NAMECUST' => $get_rcp_data['NAMECUST'],
                                        'PONUMBERCUST' => $get_rcp_data['PONUMBERCUST'],
                                        'PODATECUST' => $crmpodate,
                                        'CRMNO' => $get_rcp_data['CRMNO'],
                                        'REQDATE' => $crmreqdate,
                                        'ORDERDESC' => $get_rcp_data['ORDERDESC'],
                                        'REMARKS' => $get_rcp_data['CRMREMARKS'],
                                        'SALESCODE' => $get_rcp_data['MANAGER'],
                                        'SALESPERSON' => $get_rcp_data['SALESNAME'],
                                        'RQNDATE' => $rqndate,
                                        'RQNNUMBER' => $get_rcp_data['RQNNUMBER'],
                                        //DATA VARIABLE PO
                                        'PODATE' => $povendordate,
                                        'PONUMBER' => $get_rcp_data['PONUMBER'],
                                        'ETDDATE' => $etddate,
                                        'CARGOREADINESSDATE' => $cargoreadinessdate,
                                        'ORIGINCOUNTRY' => $get_rcp_data['ORIGINCOUNTRY'],
                                        'POREMARKS' => $get_rcp_data['POREMARKS'],
                                        //DATA VARIABLE LOGISTICS
                                        'ETDORIGINDATE' => $etdorigindate,
                                        'ATDORIGINDATE' => $atdorigindate,
                                        'ETAPORTDATE' => $etaportdate,
                                        'PIBDATE' => $pibdate,
                                        'VENDSHISTATUS' => $get_rcp_data['VENDSHISTATUS'],
                                        //DATA VARIABLE RECEIPTS
                                        'RECPNUMBER' => $get_rcp_data['RECPNUMBER'],
                                        'RECPDATE' => $rcpdate,
                                        'VDNAME' => $get_rcp_data['VDNAME'],
                                        'DESCRIPTIO' => $get_rcp_data['DESCRIPTIO'],

                                    );
                                    $subject = $mail_tmpl['SUBJECT_MAIL'];
                                    $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                                    $data_email = array(
                                        'hostname'       => $sender['HOSTNAME'],
                                        'sendername'       => $sender['SENDERNAME'],
                                        'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                        'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                        'smtpauth'       => $sender['SMTPAUTH'],
                                        'ssl'       => $sender['SSL'],
                                        'smtpport'       => $sender['SMTPPORT'],
                                        'to_email' => $sendto_user['EMAIL'],
                                        'subject' =>  $subject,
                                        'message' => $message,
                                    );


                                    $data_notif = array(
                                        'MAILKEY' => $groupuser . '-' . $get_rcp_data['RCPUNIQ'] . '-' . trim($sendto_user['USERNAME']),
                                        'FROM_USER' => $this->header_data['usernamelgn'],
                                        'FROM_EMAIL' => $this->header_data['emaillgn'],
                                        'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                                        'TO_USER' => $sendto_user['USERNAME'],
                                        'TO_EMAIL' => $sendto_user['EMAIL'],
                                        'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                                        'SUBJECT' => $subject,
                                        'MESSAGE' => $message,
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
                                        'UNIQPROCESS' => $get_rcp_data['RCPUNIQ'],
                                    );

                                    //Check Duplicate Entry & Sending Mail
                                    $touser = trim($sendto_user['USERNAME']);
                                    $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_rcp_data['RCPUNIQ'], $touser);
                                    if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_rcp_data['RCPUNIQ'] . '-' . $touser) {
                                        session()->set('success', '-1');
                                        return redirect()->to(base_url('/goodreceipt'));
                                        session()->remove('success');
                                    } else if (empty($getmailuniq['MAILKEY'])) {
                                        $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                        if ($post_email) {
                                            $sending_mail = $this->send($data_email);
                                        }
                                    }

                                endforeach;
                                $this->GoodreceiptModel->goodreceipt_update($get_rcp_data['RCPUNIQ'], $data3);
                            }
                        }
                    }
                }
            }
            session()->set('success', '1');
            return redirect()->to(base_url('/goodreceipt'));
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

    public function posting($rcpuniq, $pouniq, $csruniq)
    {
        $rcp_to_ot = $this->GoodreceiptModel->get_rcp_open_by_id($rcpuniq, $csruniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 6;

        $data = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'POSTINGSTAT' => 1,
            'OFFLINESTAT' => 1,

        );
        $gr_update = $this->GoodreceiptModel->goodreceipt_update($rcpuniq, $data);

        if ($gr_update) {
            $get_rcp = $this->GoodreceiptModel->get_goodreceipt_post($pouniq);
            foreach ($rcp_to_ot as $data_rcpl) :
                $csruniq = $data_rcpl['CSRUNIQ'];
                $csrluniq = $data_rcpl['CSRLUNIQ'];
                if ($data_rcpl['QTY'] == $data_rcpl['S_QTYRCP']) {
                    $grstatus = 1;
                } else if ($data_rcpl['QTY'] > $data_rcpl['S_QTYRCP']) {
                    $grstatus = 0;
                }

                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'RECPDATE' => $get_rcp['RECPDATE'],
                    'RECPQTY' => $data_rcpl['S_QTYRCP'],
                    'GRSTATUS' => $grstatus,
                );
                $this->GoodreceiptModel->ot_goodreceipt_update($csruniq, $csrluniq, $data2);
            endforeach;

            if ($sender['OFFLINESTAT'] == 0) {
                //Untuk Update Status Posting CSR
                $data3 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'POSTINGSTAT' => 1,
                    'OFFLINESTAT' => 0,
                );
                //inisiasi proses kirim ke group
                $get_rcp_data = $this->GoodreceiptModel->get_rcpjoincsr_by_rcp($rcpuniq);
                $crmpodate = substr($get_rcp_data['PODATECUST'], 4, 2) . "/" . substr($get_rcp_data['PODATECUST'], 6, 2) . "/" .  substr($get_rcp_data['PODATECUST'], 0, 4);
                $crmreqdate = substr($get_rcp_data['CRMREQDATE'], 4, 2) . '/' . substr($get_rcp_data['CRMREQDATE'], 6, 2) . '/' . substr($get_rcp_data['CRMREQDATE'], 0, 4);
                $rqndate = substr($get_rcp_data['RQNDATE'], 4, 2) . "/" . substr($get_rcp_data['RQNDATE'], 6, 2) . "/" .  substr($get_rcp_data['RQNDATE'], 0, 4);
                $povendordate = substr($get_rcp_data['PODATE'], 4, 2) . "/" . substr($get_rcp_data['PODATE'], 6, 2) . "/" .  substr($get_rcp_data['PODATE'], 0, 4);
                $etddate = substr($get_rcp_data['ETDDATE'], 4, 2) . "/" . substr($get_rcp_data['ETDDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETDDATE'], 0, 4);
                $cargoreadinessdate = substr($get_rcp_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_rcp_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_rcp_data['CARGOREADINESSDATE'], 0, 4);
                $etdorigindate = substr($get_rcp_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_rcp_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETDORIGINDATE'], 0, 4);
                $atdorigindate = substr($get_rcp_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_rcp_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_rcp_data['ATDORIGINDATE'], 0, 4);
                $etaportdate = substr($get_rcp_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_rcp_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETAPORTDATE'], 0, 4);
                $pibdate = substr($get_rcp_data['PIBDATE'], 4, 2) . "/" . substr($get_rcp_data['PIBDATE'], 6, 2) . "/" .  substr($get_rcp_data['PIBDATE'], 0, 4);
                $rcpdate = substr($get_rcp_data['RECPDATE'], 4, 2) . "/" . substr($get_rcp_data['RECPDATE'], 6, 2) . "/" .  substr($get_rcp_data['RECPDATE'], 0, 4);

                $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                $mail_tmpl = $this->NotifModel->get_template($groupuser);

                foreach ($notiftouser_data as $sendto_user) :
                    $var_email = array(
                        'TONAME' => $sendto_user['NAME'],
                        'FROMNAME' => $this->audtuser['NAMELGN'],
                        'CONTRACT' => $get_rcp_data['CONTRACT'],
                        'CTDESC' => $get_rcp_data['CTDESC'],
                        'PROJECT' => $get_rcp_data['PROJECT'],
                        'PRJDESC' => $get_rcp_data['PRJDESC'],
                        'CUSTOMER' => $get_rcp_data['CUSTOMER'],
                        'NAMECUST' => $get_rcp_data['NAMECUST'],
                        'PONUMBERCUST' => $get_rcp_data['PONUMBERCUST'],
                        'PODATECUST' => $crmpodate,
                        'CRMNO' => $get_rcp_data['CRMNO'],
                        'REQDATE' => $crmreqdate,
                        'ORDERDESC' => $get_rcp_data['ORDERDESC'],
                        'REMARKS' => $get_rcp_data['CRMREMARKS'],
                        'SALESCODE' => $get_rcp_data['MANAGER'],
                        'SALESPERSON' => $get_rcp_data['SALESNAME'],
                        'RQNDATE' => $rqndate,
                        'RQNNUMBER' => $get_rcp_data['RQNNUMBER'],
                        //DATA VARIABLE PO
                        'PODATE' => $povendordate,
                        'PONUMBER' => $get_rcp_data['PONUMBER'],
                        'ETDDATE' => $etddate,
                        'CARGOREADINESSDATE' => $cargoreadinessdate,
                        'ORIGINCOUNTRY' => $get_rcp_data['ORIGINCOUNTRY'],
                        'POREMARKS' => $get_rcp_data['POREMARKS'],
                        //DATA VARIABLE LOGISTICS
                        'ETDORIGINDATE' => $etdorigindate,
                        'ATDORIGINDATE' => $atdorigindate,
                        'ETAPORTDATE' => $etaportdate,
                        'PIBDATE' => $pibdate,
                        'VENDSHISTATUS' => $get_rcp_data['VENDSHISTATUS'],
                        //DATA VARIABLE RECEIPTS
                        'RECPNUMBER' => $get_rcp_data['RECPNUMBER'],
                        'RECPDATE' => $rcpdate,
                        'VDNAME' => $get_rcp_data['VDNAME'],
                        'DESCRIPTIO' => $get_rcp_data['DESCRIPTIO'],

                    );
                    $subject = $mail_tmpl['SUBJECT_MAIL'];
                    $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                    $data_email = array(
                        'hostname'       => $sender['HOSTNAME'],
                        'sendername'       => $sender['SENDERNAME'],
                        'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                        'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                        'smtpauth'       => $sender['SMTPAUTH'],
                        'ssl'       => $sender['SSL'],
                        'smtpport'       => $sender['SMTPPORT'],
                        'to_email' => $sendto_user['EMAIL'],
                        'subject' =>  $subject,
                        'message' => $message,
                    );


                    $data_notif = array(
                        'MAILKEY' => $groupuser . '-' . $get_rcp_data['RCPUNIQ'] . '-' . trim($sendto_user['USERNAME']),
                        'FROM_USER' => $this->header_data['usernamelgn'],
                        'FROM_EMAIL' => $this->header_data['emaillgn'],
                        'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                        'TO_USER' => $sendto_user['USERNAME'],
                        'TO_EMAIL' => $sendto_user['EMAIL'],
                        'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                        'SUBJECT' => $subject,
                        'MESSAGE' => $message,
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
                        'UNIQPROCESS' => $get_rcp_data['RCPUNIQ'],
                    );

                    //Check Duplicate Entry & Sending Mail
                    $touser = trim($sendto_user['USERNAME']);
                    $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_rcp_data['RCPUNIQ'], $touser);
                    if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_rcp_data['RCPUNIQ'] . '-' . $touser) {
                        session()->set('success', '-1');
                        return redirect()->to(base_url('/goodreceipt'));
                        session()->remove('success');
                    } else if (empty($getmailuniq['MAILKEY'])) {
                        $post_email = $this->NotifModel->mailbox_insert($data_notif);
                        if ($post_email) {
                            $sending_mail = $this->send($data_email);
                        }
                    }

                endforeach;
                $this->GoodreceiptModel->goodreceipt_update($get_rcp_data['RCPUNIQ'], $data3);
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/goodreceipt'));
        session()->remove('success');
    }


    public function sendnotif($rcpuniq)
    {
        //inisiasi proses kirim ke group
        $get_rcp_data = $this->GoodreceiptModel->get_rcpjoincsr_by_rcp($rcpuniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 6;
        //Untuk Update Status Posting G/R
        $data3 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'POSTINGSTAT' => 1,
            'OFFLINESTAT' => 0,
        );

        $crmpodate = substr($get_rcp_data['PODATECUST'], 4, 2) . "/" . substr($get_rcp_data['PODATECUST'], 6, 2) . "/" .  substr($get_rcp_data['PODATECUST'], 0, 4);
        $crmreqdate = substr($get_rcp_data['CRMREQDATE'], 4, 2) . '/' . substr($get_rcp_data['CRMREQDATE'], 6, 2) . '/' . substr($get_rcp_data['CRMREQDATE'], 0, 4);
        $rqndate = substr($get_rcp_data['RQNDATE'], 4, 2) . "/" . substr($get_rcp_data['RQNDATE'], 6, 2) . "/" .  substr($get_rcp_data['RQNDATE'], 0, 4);
        $povendordate = substr($get_rcp_data['PODATE'], 4, 2) . "/" . substr($get_rcp_data['PODATE'], 6, 2) . "/" .  substr($get_rcp_data['PODATE'], 0, 4);
        $etddate = substr($get_rcp_data['ETDDATE'], 4, 2) . "/" . substr($get_rcp_data['ETDDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETDDATE'], 0, 4);
        $cargoreadinessdate = substr($get_rcp_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_rcp_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_rcp_data['CARGOREADINESSDATE'], 0, 4);
        $etdorigindate = substr($get_rcp_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_rcp_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETDORIGINDATE'], 0, 4);
        $atdorigindate = substr($get_rcp_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_rcp_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_rcp_data['ATDORIGINDATE'], 0, 4);
        $etaportdate = substr($get_rcp_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_rcp_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_rcp_data['ETAPORTDATE'], 0, 4);
        $pibdate = substr($get_rcp_data['PIBDATE'], 4, 2) . "/" . substr($get_rcp_data['PIBDATE'], 6, 2) . "/" .  substr($get_rcp_data['PIBDATE'], 0, 4);
        $rcpdate = substr($get_rcp_data['RECPDATE'], 4, 2) . "/" . substr($get_rcp_data['RECPDATE'], 6, 2) . "/" .  substr($get_rcp_data['RECPDATE'], 0, 4);

        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        $mail_tmpl = $this->NotifModel->get_template($groupuser);

        foreach ($notiftouser_data as $sendto_user) :
            $var_email = array(
                'TONAME' => $sendto_user['NAME'],
                'FROMNAME' => $this->audtuser['NAMELGN'],
                'CONTRACT' => $get_rcp_data['CONTRACT'],
                'CTDESC' => $get_rcp_data['CTDESC'],
                'PROJECT' => $get_rcp_data['PROJECT'],
                'PRJDESC' => $get_rcp_data['PRJDESC'],
                'CUSTOMER' => $get_rcp_data['CUSTOMER'],
                'NAMECUST' => $get_rcp_data['NAMECUST'],
                'PONUMBERCUST' => $get_rcp_data['PONUMBERCUST'],
                'PODATECUST' => $crmpodate,
                'CRMNO' => $get_rcp_data['CRMNO'],
                'REQDATE' => $crmreqdate,
                'ORDERDESC' => $get_rcp_data['ORDERDESC'],
                'REMARKS' => $get_rcp_data['CRMREMARKS'],
                'SALESCODE' => $get_rcp_data['MANAGER'],
                'SALESPERSON' => $get_rcp_data['SALESNAME'],
                'RQNDATE' => $rqndate,
                'RQNNUMBER' => $get_rcp_data['RQNNUMBER'],
                //DATA VARIABLE PO
                'PODATE' => $povendordate,
                'PONUMBER' => $get_rcp_data['PONUMBER'],
                'ETDDATE' => $etddate,
                'CARGOREADINESSDATE' => $cargoreadinessdate,
                'ORIGINCOUNTRY' => $get_rcp_data['ORIGINCOUNTRY'],
                'POREMARKS' => $get_rcp_data['POREMARKS'],
                //DATA VARIABLE LOGISTICS
                'ETDORIGINDATE' => $etdorigindate,
                'ATDORIGINDATE' => $atdorigindate,
                'ETAPORTDATE' => $etaportdate,
                'PIBDATE' => $pibdate,
                'VENDSHISTATUS' => $get_rcp_data['VENDSHISTATUS'],
                //DATA VARIABLE RECEIPTS
                'RECPNUMBER' => $get_rcp_data['RECPNUMBER'],
                'RECPDATE' => $rcpdate,
                'VDNAME' => $get_rcp_data['VDNAME'],
                'DESCRIPTIO' => $get_rcp_data['DESCRIPTIO'],

            );
            $subject = $mail_tmpl['SUBJECT_MAIL'];
            $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'smtpauth'       => $sender['SMTPAUTH'],
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' =>  $subject,
                'message' => $message,
            );


            $data_notif = array(
                'MAILKEY' => $groupuser . '-' . $get_rcp_data['RCPUNIQ'] . '-' . trim($sendto_user['USERNAME']),
                'FROM_USER' => $this->header_data['usernamelgn'],
                'FROM_EMAIL' => $this->header_data['emaillgn'],
                'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                'TO_USER' => $sendto_user['USERNAME'],
                'TO_EMAIL' => $sendto_user['EMAIL'],
                'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                'SUBJECT' => $subject,
                'MESSAGE' => $message,
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
                'UNIQPROCESS' => $get_rcp_data['RCPUNIQ'],
            );

            //Check Duplicate Entry & Sending Mail
            $touser = trim($sendto_user['USERNAME']);
            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_rcp_data['RCPUNIQ'], $touser);
            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_rcp_data['RCPUNIQ'] . '-' . $touser) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/goodreceipt'));
                session()->remove('success');
            } else if (empty($getmailuniq['MAILKEY'])) {
                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                if ($post_email) {
                    $sending_mail = $this->send($data_email);
                }
            }

        endforeach;
        $this->GoodreceiptModel->goodreceipt_update($get_rcp_data['RCPUNIQ'], $data3);

        session()->set('success', '1');
        return redirect()->to(base_url('/goodreceipt'));
        session()->remove('success');
    }


    private function send($data_email)
    {
        $hostname           = $data_email['hostname'];
        $sendername         = $data_email['sendername'];
        $senderemail        = $data_email['senderemail'];
        $passwordemail      = $data_email['passwordemail'];
        $chksmtpauth           = $data_email['smtpauth'];
        $ssl                = $data_email['ssl'];
        $smtpport           = $data_email['smtpport'];
        $to                 = $data_email['to_email'];
        $subject             = $data_email['subject'];
        $message             = $data_email['message'];
        if ($data_email['smtpauth'] == 1) {
            $smtpauth = 'TRUE';
        } else {
            $smtpauth = 'FALSE';
        }


        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $hostname;
            $mail->SMTPAuth   = $smtpauth;
            $mail->Username   = $senderemail; // silahkan ganti dengan alamat email Anda
            if ($chksmtpauth == TRUE) :
                $mail->Password   = $passwordemail; // silahkan ganti dengan password email Anda
            endif;
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
            return redirect()->to(base_url('/goodreceipt'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/goodreceipt'));
        }
    }
}
