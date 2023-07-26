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
    private $cart;
    private $db_name;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->db_name = \Config\Database::connect();
        $this->cart = \Config\Services::cart();

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
        session()->remove('sage_shidoc');
        session()->remove('sage_dnnumber');
        session()->remove('sage_rcpdate');
        session()->remove('sage_shidate');
        session()->remove('sage_shiref');
        session()->remove('sage_shidesc');

        $this->cart->destroy();
        $deliverydata = $this->DeliveryordersModel->get_gr_pending_to_dn();
        $shilist_data = $this->DeliveryordersModel->get_shilist_on_shiopen();
        $csrl_to_ship_data = $this->DeliveryordersModel->get_csrl_list_to_ship_post();

        $data = array(
            'delivery_data' => $deliverydata,
            'shilist_data' => $shilist_data,
            'shi_l_data' => $csrl_to_ship_data,
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
        $shilist_data = $this->DeliveryordersModel->get_shilist_on_shiopen();
        $csrl_to_ship_data = $this->DeliveryordersModel->get_csrl_list_to_ship_post();
        $data = array(
            'delivery_data' => $deliverydata,
            'shilist_data' => $shilist_data,
            'shi_l_data' => $csrl_to_ship_data,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_gr_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function view_gr_number($pouniq, $itemno)
    {
        $data = array(
            'grlist_by_po_data' => $this->DeliveryordersModel->get_grlist_posting($pouniq, $itemno),

        );
        echo view('delivery/ajax_view_grlist', $data);
    }

    public function view_shi_number($pouniq, $itemno)
    {
        $data = array(
            'shilist_by_po_data' => $this->DeliveryordersModel->get_shilist_posting($pouniq, $itemno),

        );
        echo view('delivery/ajax_view_shilist', $data);
    }


    public function add($csruniq, $post_stat, $delshiline)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getshidata = $this->DeliveryordersModel->get_csr_outstanding_shi($csruniq);
        $get_rcp_l = $this->DeliveryordersModel->get_rcp_l_by_id($csruniq);
        $reqdate = substr($getshidata['CRMREQDATE'], 4, 2) . '/' . substr($getshidata['CRMREQDATE'], 6, 2) . '/' . substr($getshidata['CRMREQDATE'], 0, 4);
        $pocustdate = substr($getshidata['PODATECUST'], 4, 2) . '/' . substr($getshidata['PODATECUST'], 6, 2) . '/' . substr($getshidata['PODATECUST'], 0, 4);

        $act = 'deliveryorders/insert_action';
        if ($post_stat == 0) {
            $button_text = 'Save';
        } else {
            $button_text = 'Save & Posting';
        }


        if ($delshiline == 0) {
            foreach ($get_rcp_l as $items) :
                $this->cart->insert(array(
                    'id'      => trim($items['ITEMNO']),
                    'qty'     => $items['SHIQTY_OUTS'],
                    'price'   => '1',
                    'name'    => 'Item Description Sage',
                    'options' => array('so_service' => $items['SERVICETYPE'], 'material_no' => $items['MATERIALNO'], 'itemdesc' => $items['ITEMDESC'], 'so_qty' => $items['QTY'], 'gr_qty' => $items['S_RCPQTY'], 'shi_qty' => 0, 'shi_qty_outs' => $items['SHIQTY_OUTS'], 'so_uom' => $items['STOCKUNIT'], 'csruniq' => $items['CSRUNIQ'], 'csrluniq' => $items['CSRLUNIQ'], 'ponumber' => $items['PONUMBER'], 'pouniq' => $items['POUNIQ'], 'poluniq' => $items['POLUNIQ'])
                ));
            endforeach;
        }

        if ($this->cart->totalItems() == 0) {
            return redirect()->to(base_url('deliveryorders'));
        }

        $data = array(
            'csr_uniq' => $getshidata['CSRUNIQ'],
            'ct_no' => $getshidata['CONTRACT'],
            'ct_desc' => $getshidata['CTDESC'],
            'prj_no' => $getshidata['PROJECT'],
            'prj_desc' => $getshidata['PRJDESC'],
            'ct_custno' => $getshidata['CUSTOMER'],
            'ct_custname' => $getshidata['NAMECUST'],
            'ct_email1' => $getshidata['EMAIL1CUST'],
            'crm_no' => $getshidata['CRMNO'],
            'ponumbercust' => $getshidata['PONUMBERCUST'],
            'manager' => $getshidata['MANAGER'],
            'salesperson' => $getshidata['SALESNAME'],
            'pocustdate' => $pocustdate,
            'uf_pocustdate' => $getshidata['PODATECUST'],
            'req_date' => $reqdate,
            'button_text' => $button_text,
            'post_stat' => $post_stat,
            'delshiline' => $delshiline,
            'form_action' => base_url($act),
            'rcplforshi_data' => $this->cart->contents(),
            'cart' => $this->cart,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/delivery_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function form_select_sage_shipment($csr_uniq, $post_stat, $uf_pocustdate)
    {
        session()->remove('success');
        $nav['act_tab'] = 1;
        $nav['act_tab2'] = 1;
        $nav['form_action'] = base_url("deliveryorders/chooseshipments");
        $getcsrdata = $this->DeliveryordersModel->get_csr_uniq($csr_uniq);
        $ct_no = $getcsrdata['CONTRACT'];
        $data = array(
            'sage_shi_number_tf' => $this->DeliveryordersModel->list_sage_shi_tf($uf_pocustdate),
            'sage_shi_number_pm' => $this->DeliveryordersModel->list_sage_shi_pm($ct_no, $uf_pocustdate),
            'csr_uniq' => $csr_uniq,
            'post_stat' => $post_stat,
            'pocustdate' => $uf_pocustdate,
            //'sage_shi_number' => $this->DeliveryordersModel->list_sage_shi(),
            'form_action' => base_url("deliveryorders/chooseshipments"),
            'form2_action' => base_url("deliveryorders/chooseshipmentsx"),

        );
        echo view('delivery/delivery_tab_menu', $nav);
        echo view('delivery/ajax_add_sage_shi_number', $data);
    }


    public function chooseshipments()
    {
        $delshiline = 1;
        $sage_tfdocuniq = $this->request->getPost('tfdocuniq');
        $sage_pjcdocuniq = $this->request->getPost('pjcdocuniq');

        if (null == ($sage_tfdocuniq) and null == ($sage_pjcdocuniq)) {
            session()->set('success', '-1');
            $sage_tfdocuniq = "shipment number not found";
            $sage_pjcdocuniq = "shipment number not found";
            $csruniq = $this->request->getPost('csr_uniq');
            $post_stat = $this->request->getPost('post_stat');
            //return redirect()->to(base_url('deliveryorders/add/' . $csruniq . '/' . $post_stat . '/' . $delshiline));
        } else if (!empty($sage_tfdocuniq) and !empty($sage_pjcdocuniq)) {
            session()->set('success', '-1');
            $sage_tfdocuniq = "shipment number not found";
            $sage_pjcdocuniq = "shipment number not found";
            $csruniq = $this->request->getPost('csr_uniq');
            $post_stat = $this->request->getPost('post_stat');
            //return redirect()->to(base_url('deliveryorders/add/' . $csruniq . '/' . $post_stat . '/' . $delshiline));
        } else {
            if (!empty($sage_tfdocuniq)) :
                $dn_number = $this->request->getPost('tf_dn_number');
                $rcp_date = $this->request->getPost('tf_received_date');
                $getshidocfrtf = $this->DeliveryordersModel->get_ic_transfer_sage_by_doc($sage_tfdocuniq);
                $shidate = substr($getshidocfrtf['TRANSDATE'], 4, 2) . '/' . substr($getshidocfrtf['TRANSDATE'], 6, 2) . '/' . substr($getshidocfrtf['TRANSDATE'], 0, 4);

                session()->set('sage_shidoc', $sage_tfdocuniq);
                session()->set('sage_dnnumber', $dn_number);
                session()->set('sage_rcpdate', $rcp_date);
                session()->set('sage_shidate', $shidate);
                session()->set('sage_shiref', $getshidocfrtf['REFERENCE']);
                session()->set('sage_shidesc', $getshidocfrtf['HDRDESC']);
            endif;
            if (!empty($sage_pjcdocuniq)) :
                $dn_number = $this->request->getPost('pjc_dn_number');
                $rcp_date = $this->request->getPost('pjc_received_date');
                $getshidocfrpjc = $this->DeliveryordersModel->get_pjc_material_sage_by_doc($sage_pjcdocuniq);
                $shidate = substr($getshidocfrpjc['TRANSDATE'], 4, 2) . '/' . substr($getshidocfrpjc['TRANSDATE'], 6, 2) . '/' . substr($getshidocfrpjc['TRANSDATE'], 0, 4);

                session()->set('sage_shidoc', $sage_pjcdocuniq);
                session()->set('sage_dnnumber', $dn_number);
                session()->set('sage_rcpdate', $rcp_date);
                session()->set('sage_shidate', $shidate);
                session()->set('sage_shiref', $getshidocfrpjc['REFERENCE']);
                session()->set('sage_shidesc', $getshidocfrpjc['HDRDESC']);
            endif;
            session()->set('success', '1');
            $csruniq = $this->request->getPost('csr_uniq');
            $post_stat = $this->request->getPost('post_stat');
        }


        return redirect()->to(base_url('deliveryorders/add/' . $csruniq . '/' . $post_stat . '/' . $delshiline));
    }

    public function form_update_item($csr_uniq, $post_stat, $rowid, $itemno, $delshiline)
    {
        //$getpodata = $this->GoodreceiptModel->get_po_pending_by_pouniq($po_uniq);
        $poitem = $this->DeliveryordersModel->get_po_l_item($csr_uniq, $itemno);
        $data = array(
            'form_action' => base_url("deliveryorders/chooseitem"),
            'post_stat' => $post_stat,
            'delshiline' => $delshiline,
            'rcphseq' => '',
            'rowid' => $rowid,
            'csr_uniq' => $poitem['CSRUNIQ'],
            'csrl_uniq' => $poitem['CSRLUNIQ'],
            'po_number' => $poitem['PONUMBER'],
            'po_uniq' => $poitem['POUNIQ'],
            'pol_uniq' => $poitem['POLUNIQ'],
            'itemno' => $itemno,
            'material_no' => $poitem['MATERIALNO'],
            'itemdesc' => $poitem['ITEMDESC'],
            'service_type' => $poitem['SERVICETYPE'],
            'uom' => $poitem['STOCKUNIT'],
            'so_qty' => number_format($poitem['QTY'], 0, ",", "."),
            'gr_qty' => number_format($poitem['S_RCPQTY'], 0, ",", "."),
            'shi_qty' => number_format($poitem['S_SHIQTY'], 0, ",", "."),
            'shi_qty_outs' => number_format($poitem['SHIQTY_OUTS'], 0, ",", "."),
            'select_item' => '',
        );

        echo view('delivery/ajax_input_item_shi', $data);
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
            $shi_qty = $this->request->getPost('shi_qty');
            $l_shi_qty = $this->request->getPost('l_shi_qty');
            $shi_qty_outs = ($gr_qty - $l_shi_qty - $shi_qty);
            $csr_uniq = $this->request->getPost('csr_uniq');
            $csrl_uniq = $this->request->getPost('csrl_uniq');
            $ponumber = $this->request->getPost('ponumber');
            $po_uniq = $this->request->getPost('po_uniq');
            $pol_uniq = $this->request->getPost('pol_uniq');
            $post_stat = $this->request->getPost('post_stat');
            $delshiline = $this->request->getPost('delshiline');
            // data option harus di bawa
            $this->cart->update(array(
                'rowid'   => $row_id,
                'id'      => $inventory_no,
                'qty'     => '1',
                'price'   => '1',
                'name'    => 'Item Description Sage',
                'options' => array('so_service' => $service_type, 'material_no' => $material_no, 'itemdesc' => $itemdesc, 'so_qty' => $so_qty, 'gr_qty' => $gr_qty, 'shi_qty' => $shi_qty, 'shi_qty_outs' => $shi_qty_outs, 'so_uom' => $uom, 'csruniq' => $csr_uniq, 'csrluniq' => $csrl_uniq, 'ponumber' => $ponumber, 'pouniq' => $po_uniq, 'poluniq' => $pol_uniq)
            ));
        }

        return redirect()->to(base_url('deliveryorders/add/' . $csr_uniq . '/' . $post_stat . '/' . $delshiline));
    }


    // delete Shi open
    public function delete($shiuniq)
    {
        $chk_shi = $this->DeliveryordersModel->get_shipment_open($shiuniq);
        if ($chk_shi['POSTINGSTAT'] == 1) {
            session()->set('success', '-1');
            return redirect()->to(base_url('deliveryorders'));
            session()->remove('success');
        } else {
            // Remove an PO Open
            $del_shi_open = $this->DeliveryordersModel->delete_shi_open($shiuniq);
            if ($del_shi_open) {
                $this->DeliveryordersModel->delete_shil_open($shiuniq);
            }

            session()->set('success', '1');
            return redirect()->to(base_url('deliveryorders'));
            session()->remove('success');
        }
    }


    // delete item Cart
    public function delete_item_cart($csr_uniq, $post_stat, $rowid, $delshiline)
    {
        // Remove an item using its `rowid`
        $this->cart->remove($rowid);
        return redirect()->to(base_url('deliveryorders/add/' . $csr_uniq . '/' . $post_stat . '/' . $delshiline));
    }

    public function deleteedn($shiuniq)
    {
        $chk_shi = $this->DeliveryordersModel->get_shipment_open($shiuniq);
        if ($chk_shi['EDNPOSTINGSTAT'] == 1) {
            session()->set('success', '-1');
            return redirect()->to(base_url('deliveryorders/shipmentopenview/' . $shiuniq));
            session()->remove('success');
        } else {
            // Remove an EDN
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'SHIATTACHED' => 0,
                'EDNFILENAME' => NULL,
                'EDNFILEPATH' => NULL,
                'ORIGDNRCPSHIDATE' => NULL,
                'EDNPOSTINGSTAT' => NULL,

            );
            if (is_file('assets/files/edn_attached/' . trim($chk_shi['EDNFILENAME']))) {
                unlink('assets/files/edn_attached/' . trim($chk_shi['EDNFILENAME']));
            }

            $shi_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);

            session()->set('success', '1');
            return redirect()->to(base_url('deliveryorders/shipmentopenview/' . $shiuniq));
            session()->remove('success');
        }
    }



    public function insert_action()
    {
        if (!$this->validate([
            'shi_number' => 'required',
            'dn_number' => 'required',
            'shi_date' => 'required',
            'received_date' => 'required',
            'shi_ref' => 'required',
            'shi_desc' => 'required',
            'shi_total' => 'required|greater_than[0]',

        ])) {
            $csr_uniq = $this->request->getPost('csr_uniq');
            $post_stat = $this->request->getPost('post_stat');
            $delshiline = $this->request->getPost('delshiline');

            session()->set('success', '-1');
            return redirect()->to(base_url('/deliveryorders/add/' . $csr_uniq . '/' . $post_stat . '/' . $delshiline))->withInput();
        } else {
            $csruniq = $this->request->getPost('csr_uniq');
            $post_stat = $this->request->getPost('post_stat');
            $delshiline = $this->request->getPost('delshiline');
            $shinumber = $this->request->getPost('shi_number');
            $custno = $this->request->getPost('cust_no');
            $dnnumber = $this->request->getPost('dn_number');
            $shi_date = $this->request->getPost('shi_date');
            $received_date = $this->request->getPost('received_date');
            $shi_ref = $this->request->getPost('shi_ref');
            $shi_desc = $this->request->getPost('shi_desc');
            $shi_date = substr($shi_date, 6, 4)  . "" . substr($shi_date, 0, 2) . "" . substr($shi_date, 3, 2);
            $received_date  = substr($received_date, 6, 4)  . "" . substr($received_date, 0, 2) . "" . substr($received_date, 3, 2);


            $groupuser = 7;

            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'SHIKEY' => $csruniq . '-' . trim($shinumber),
                'CSRUNIQ' => $csruniq,
                'DOCNUMBER' => $shinumber,
                'SHINUMBER' => $dnnumber,
                'SHIDATE' => $shi_date,
                'CUSTRCPDATE' => $received_date,
                'CUSTOMER' => $custno,
                'SHIREFERENCE' => $shi_ref,
                'SHIDESC' => $shi_desc,
                'SHIATTACHED' => 0,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => 1,
            );


            $getshiuniq = $this->DeliveryordersModel->get_shiuniq_open($csruniq, $shinumber);
            if (!empty($getshiuniq['SHIKEY']) and $getshiuniq['CHKSHIL'] > 0 and $getshiuniq['SHIKEY'] == $csruniq . '-' . $shinumber) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/deliveryprders/add/' . $csruniq . '/' . $post_stat . '/' . $delshiline));
                session()->remove('success');
            } else if (!empty($getshiuniq['SHIKEY']) and $getshiuniq['CHKSHIL']  == 0 and $getshiuniq['SHIKEY']  == $csruniq . '-' . $shinumber) {

                foreach ($this->cart->contents() as $items) :
                    if ($items['options']['shi_qty'] > 0) {
                        if (($items['options']['so_qty'] - $items['options']['shi_qty']) == 0) {
                            $pocuststatus = 1;
                        } else {
                            $pocuststatus = 0;
                        }
                        $datal = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                            'AUDTORG' => trim($this->audtuser['AUDTORG']),
                            'SHIUNIQ' => $getshiuniq['SHIUNIQ'],
                            'CSRUNIQ' => $items['options']['csruniq'],
                            'CSRLUNIQ' => $items['options']['csrluniq'],
                            'POUNIQ' => $items['options']['pouniq'],
                            'POLUNIQ' => $items['options']['poluniq'],
                            'SERVICETYPE' => $items['options']['so_service'],
                            'ITEMNO' => $items['id'],
                            'MATERIALNO' => $items['options']['material_no'],
                            'ITEMDESC' => $items['options']['itemdesc'],
                            'STOCKUNIT' => $items['options']['so_uom'],
                            'QTY' => $items['options']['shi_qty'],
                            'SHIQTYOUTSTANDING' => $items['options']['shi_qty_outs'],
                            'POCUSTSTATUS' => $pocuststatus,
                        );
                        $shil_insert = $this->DeliveryordersModel->shiline_insert($datal);
                    }
                endforeach;
                if ($shil_insert) {
                    $this->cart->destroy();
                    session()->set('success', '1');
                    return redirect()->to(base_url('deliveryorders'));
                    session()->remove('success');
                }
            } else if (empty($getshiuniq['SHIKEY'])) {
                $deliveryorders_insert = $this->DeliveryordersModel->deliveryorders_insert($data);
                if ($deliveryorders_insert) {
                    $getshiuniq = $this->DeliveryordersModel->get_shiuniq_open($csruniq, $shinumber);
                    $shiuniq = $getshiuniq['SHIUNIQ'];
                    foreach ($this->cart->contents() as $items) :
                        if ($items['options']['shi_qty'] > 0) {
                            if (($items['options']['so_qty'] - $items['options']['shi_qty']) == 0) {
                                $pocuststatus = 1;
                            } else {
                                $pocuststatus = 0;
                            }

                            $datal = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                                'AUDTORG' => trim($this->audtuser['AUDTORG']),
                                'SHIUNIQ' => $shiuniq,
                                'CSRUNIQ' => $items['options']['csruniq'],
                                'CSRLUNIQ' => $items['options']['csrluniq'],
                                'POUNIQ' => $items['options']['pouniq'],
                                'POLUNIQ' => $items['options']['poluniq'],
                                'SERVICETYPE' => $items['options']['so_service'],
                                'ITEMNO' => $items['id'],
                                'MATERIALNO' => $items['options']['material_no'],
                                'ITEMDESC' => $items['options']['itemdesc'],
                                'STOCKUNIT' => $items['options']['so_uom'],
                                'QTY' => $items['options']['shi_qty'],
                                'SHIQTYOUTSTANDING' => $items['options']['shi_qty_outs'],
                                'POCUSTSTATUS' => $pocuststatus,
                            );

                            $shil_insert = $this->DeliveryordersModel->shiline_insert($datal);
                        }
                    endforeach;

                    $this->cart->destroy();


                    // Jika Posting (Check Sampai Sini)
                    if ($post_stat == 1) {
                        $shi_to_ot = $this->DeliveryordersModel->get_shi_open_by_id($shiuniq, $csruniq);
                        $sender = $this->AdministrationModel->get_mailsender();

                        $data = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 1,

                        );
                        $shi_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);
                        $get_shi = $this->DeliveryordersModel->get_delivery_post($csruniq, $shiuniq);

                        if ($shi_update) {
                            foreach ($shi_to_ot as $data_shil) :
                                $csruniq = $data_shil['CSRUNIQ'];
                                $csrluniq = $data_shil['CSRLUNIQ'];

                                if ($data_shil['QTY'] == $data_shil['S_SHIQTY']) {
                                    $shistatus = 1;
                                } else if ($data_shil['QTY'] > $data_shil['S_SHIQTY']) {
                                    $shistatus = 0;
                                }

                                $pocust_date = date_create(substr($get_shi['PODATECUST'], 4, 2) . "/" . substr($get_shi['PODATECUST'], 6, 2) . "/" .  substr($get_shi['PODATECUST'], 0, 4));
                                $crmreqdate = date_create(substr($get_shi['CRMREQDATE'], 4, 2) . "/" . substr($get_shi['CRMREQDATE'], 6, 2) . "/" . substr($get_shi['CRMREQDATE'], 0, 4));
                                $shi_date = date_create(substr($get_shi['SHIDATE'], 4, 2) . "/" . substr($get_shi['SHIDATE'], 6, 2) . "/" .  substr($get_shi['SHIDATE'], 0, 4));
                                $ontimedeldiff = date_diff($shi_date, $crmreqdate);
                                $ontimedeldiff = $ontimedeldiff->format("%a");
                                $potodndiff = date_diff($shi_date, $pocust_date);
                                $potodndiff = $potodndiff->format("%a");

                                $data2 = array(
                                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                    'AUDTORG' => $this->audtuser['AUDTORG'],
                                    'SHIDOCNUMBER' => $get_shi['DOCNUMBER'],
                                    'SHINUMBER' => $get_shi['SHINUMBER'],
                                    'SHIDATE' => $get_shi['SHIDATE'],
                                    'CUSTRCPDATE' => $get_shi['CUSTRCPDATE'],
                                    'SHIQTY' => $data_shil['S_SHIQTY'],
                                    'SHIQTYOUTSTANDING' => ($data_shil['QTY'] - $data_shil['S_SHIQTY']),
                                    'POCUSTSTATUS' => $shistatus,
                                    'ONTIMEDELDAYS' => $ontimedeldiff,
                                    'POTODNDAYS' => $potodndiff,
                                );
                                $this->DeliveryordersModel->ot_deliveryorders_update($csruniq, $csrluniq, $data2);
                            endforeach;

                            if ($sender['OFFLINESTAT'] == 0) {

                                $get_shi_data = $this->DeliveryordersModel->get_shijoincsr_by_shi($shiuniq);
                                if (!empty($get_shi_data['EDNFILENAME'])) {
                                    //Untuk Update Status Posting CSR
                                    $data3 = array(
                                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                        'AUDTORG' => $this->audtuser['AUDTORG'],
                                        'POSTINGSTAT' => 1,
                                        'OFFLINESTAT' => 0,
                                    );


                                    $crmpodate = substr($get_shi_data['PODATECUST'], 4, 2) . "/" . substr($get_shi_data['PODATECUST'], 6, 2) . "/" .  substr($get_shi_data['PODATECUST'], 0, 4);
                                    $crmreqdate = substr($get_shi_data['CRMREQDATE'], 4, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 6, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 0, 4);
                                    $rqndate = substr($get_shi_data['RQNDATE'], 4, 2) . "/" . substr($get_shi_data['RQNDATE'], 6, 2) . "/" .  substr($get_shi_data['RQNDATE'], 0, 4);
                                    $povendordate = substr($get_shi_data['PODATE'], 4, 2) . "/" . substr($get_shi_data['PODATE'], 6, 2) . "/" .  substr($get_shi_data['PODATE'], 0, 4);
                                    $etddate = substr($get_shi_data['ETDDATE'], 4, 2) . "/" . substr($get_shi_data['ETDDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDDATE'], 0, 4);
                                    $cargoreadinessdate = substr($get_shi_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_shi_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_shi_data['CARGOREADINESSDATE'], 0, 4);
                                    $etdorigindate = substr($get_shi_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDORIGINDATE'], 0, 4);
                                    $atdorigindate = substr($get_shi_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ATDORIGINDATE'], 0, 4);
                                    $etaportdate = substr($get_shi_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_shi_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_shi_data['ETAPORTDATE'], 0, 4);
                                    $pibdate = substr($get_shi_data['PIBDATE'], 4, 2) . "/" . substr($get_shi_data['PIBDATE'], 6, 2) . "/" .  substr($get_shi_data['PIBDATE'], 0, 4);
                                    $shidate = substr($get_shi_data['SHIDATE'], 4, 2) . "/" . substr($get_shi_data['SHIDATE'], 6, 2) . "/" .  substr($get_shi_data['SHIDATE'], 0, 4);
                                    $custrcpdate = substr($get_shi_data['CUSTRCPDATE'], 4, 2) . "/" . substr($get_shi_data['CUSTRCPDATE'], 6, 2) . "/" .  substr($get_shi_data['CUSTRCPDATE'], 0, 4);
                                    if (!empty($get_shi_data['EDNFILENAME'])) {

                                        $is_attachment = 1;
                                        $origdnrcpshidate = substr($get_shi_data['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSHIDATE'], 0, 4);
                                    } else {
                                        $is_attachment = 0;
                                    }
                                    // Khusus untuk PROSES Delivery Note Model nya berbeda karena harus kirim ke customer juga
                                    $notiftouser_data = $this->NotifModel->get_edn_sendto_user($groupuser, $csruniq);
                                    $mail_tmpl = $this->NotifModel->get_template($groupuser);

                                    foreach ($notiftouser_data as $sendto_user) :
                                        $var_email = array(
                                            'TONAME' => $sendto_user['NAME'],
                                            'FROMNAME' => $this->audtuser['NAMELGN'],
                                            'CONTRACT' => $get_shi_data['CONTRACT'],
                                            'CTDESC' => $get_shi_data['CTDESC'],
                                            'PROJECT' => $get_shi_data['PROJECT'],
                                            'PRJDESC' => $get_shi_data['PRJDESC'],
                                            'CUSTOMER' => $get_shi_data['CUSTOMER'],
                                            'NAMECUST' => $get_shi_data['NAMECUST'],
                                            'EMAIL1CUST' => $get_shi_data['EMAIL1CUST'],
                                            'PONUMBERCUST' => $get_shi_data['PONUMBERCUST'],
                                            'PODATECUST' => $crmpodate,
                                            'CRMNO' => $get_shi_data['CRMNO'],
                                            'REQDATE' => $crmreqdate,
                                            'ORDERDESC' => $get_shi_data['ORDERDESC'],
                                            'REMARKS' => $get_shi_data['CRMREMARKS'],
                                            'SALESCODE' => $get_shi_data['MANAGER'],
                                            'SALESPERSON' => $get_shi_data['SALESNAME'],
                                            'RQNDATE' => $rqndate,
                                            'RQNNUMBER' => $get_shi_data['RQNNUMBER'],
                                            //DATA VARIABLE PO
                                            'PODATE' => $povendordate,
                                            'PONUMBER' => $get_shi_data['PONUMBER'],
                                            'ETDDATE' => $etddate,
                                            'CARGOREADINESSDATE' => $cargoreadinessdate,
                                            'ORIGINCOUNTRY' => $get_shi_data['ORIGINCOUNTRY'],
                                            'POREMARKS' => $get_shi_data['POREMARKS'],
                                            //DATA VARIABLE LOGISTICS
                                            'ETDORIGINDATE' => $etdorigindate,
                                            'ATDORIGINDATE' => $atdorigindate,
                                            'ETAPORTDATE' => $etaportdate,
                                            'PIBDATE' => $pibdate,
                                            'VENDSHISTATUS' => $get_shi_data['VENDSHISTATUS'],
                                            //DATA VARIABLE RECEIPTS
                                            //'RECPNUMBER' => $get_shi_data['RECPNUMBER'],
                                            //'RECPDATE' => $rcpdate,
                                            //'VDNAME' => $get_shi_data['VDNAME'],
                                            //'DESCRIPTIO' => $get_shi_data['DESCRIPTIO'],
                                            //DATA VARIABLE SHIPMENTS
                                            'DOCNUMBER' => $get_shi_data['DOCNUMBER'],
                                            'SHINUMBER' => $get_shi_data['SHINUMBER'],
                                            'SHIDATE' => $shidate,
                                            'CUSTRCPDATE' => $custrcpdate,
                                            'ORIGDNRCPSHIDATE' => $origdnrcpshidate,

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
                                            //TAMBAHAN EMAIL ATTACHMENT
                                            'attachment_filepath' => $get_shi_data['EDNFILEPATH'],
                                            'attachment_filename' => $get_shi_data['EDNFILENAME'],
                                        );


                                        $data_notif = array(
                                            'MAILKEY' => $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                                            'IS_ATTACHED' => $is_attachment,
                                            'IS_STAR' => 0,
                                            'IS_READSENDER' => 1,
                                            'IS_ARCHIVEDSENDER' => 0,
                                            'IS_TRASHEDSENDER' => 0,
                                            'IS_DELETEDSENDER' => 0,
                                            'SENDING_STATUS' => 1,
                                            //TAMBAHAN EMAIL ATTACHMENT
                                            'ATTACHMENT_FILENAME' => $get_shi_data['EDNFILENAME'],
                                            'ATTACHMENT_FILEPATH' => $get_shi_data['EDNFILEPATH'],
                                            'OTPROCESS' => $groupuser,
                                            'UNIQPROCESS' => $get_shi_data['SHIUNIQ'],
                                        );

                                        //Check Duplicate Entry & Sending Mail
                                        $touser = trim($sendto_user['USERNAME']);
                                        $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_shi_data['SHIUNIQ'], $touser);
                                        if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . $touser) {
                                            session()->set('success', '-1');
                                            return redirect()->to(base_url('/deliveryorders'));
                                            session()->remove('success');
                                        } else if (empty($getmailuniq['MAILKEY'])) {
                                            $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                            if ($post_email) {
                                                $sending_mail = $this->send($data_email);
                                            }
                                        }

                                    endforeach;
                                    $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data3);
                                }
                            }
                        }
                    }
                }
            }

            session()->set('success', '1');
            return redirect()->to(base_url('/deliveryorders'));
            session()->remove('success');
        }
    }


    /*public function update_action()
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
    */


    public function shipmentopenview($shiuniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getshiopen = $this->DeliveryordersModel->get_shipment_open($shiuniq);
        $get_shi_l = $this->DeliveryordersModel->get_shi_l_by_id($shiuniq);

        /*if (empty($getshiopen['POSTINGSTAT']) and empty($getshiopen['EDNFILENAME'])) {
            return redirect()->to(base_url('/deliveryorders/'));
            session()->remove('success');
        } else 
        */
        $ufmt_today = $this->audtuser['AUDTDATE'];
        $today = substr($ufmt_today, 4, 2) . "/" . substr($ufmt_today, 6, 2) . "/" .  substr($ufmt_today, 0, 4);

        if (!empty($getshiopen['EDNFILENAME']) and $getshiopen['EDNPOSTINGSTAT'] == 1 and $getshiopen['OFFLINESTAT'] == 1) {
            $data = array(
                'shiopen_data' =>  $getshiopen,
                'shi_l_open_data' =>  $get_shi_l,
                'todaydate' => $today,
                'link_action' => 'deliveryorders/sendnotif/',
                'btn_color' => 'bg-blue',
                'button' => 'Send Notification Manually',
            );
        } else {
            $data = array(
                'shiopen_data' =>  $getshiopen,
                'shi_l_open_data' =>  $get_shi_l,
                'todaydate' => $today,
                'link_action' => 'deliveryorders/posting/',
                'btn_color' => 'bg-blue',
                'button' => 'Posting e-D/N',
            );
        }



        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_delivery_view', $data);
        echo view('view_footer', $this->footer_data);
    }




    public function posting($shiuniq, $csruniq)
    {
        $shi_to_ot = $this->DeliveryordersModel->get_shi_open_by_id($shiuniq, $csruniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $get_shi = $this->DeliveryordersModel->get_delivery_post($csruniq, $shiuniq);
        $groupuser = 7;

        if (!empty($get_shi['EDNFILENAME'])) {
            //Untuk Update Status Posting Shipment
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'EDNPOSTINGSTAT' => 1,
                'POSTINGSTAT' => 1,
                'OFFLINESTAT' => 1,
            );
        } else {
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'EDNPOSTINGSTAT' => 0,
                'POSTINGSTAT' => 1,
                'OFFLINESTAT' => 1,

            );
        }
        $shi_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);


        if ($shi_update) {
            $get_shi = $this->DeliveryordersModel->get_delivery_post($csruniq, $shiuniq);

            foreach ($shi_to_ot as $data_shil) :
                $csruniq = $data_shil['CSRUNIQ'];
                $csrluniq = $data_shil['CSRLUNIQ'];

                if ($data_shil['QTY'] == $data_shil['S_SHIQTY']) {
                    $shistatus = 1;
                } else if ($data_shil['QTY'] > $data_shil['S_SHIQTY']) {
                    $shistatus = 0;
                }

                $pocust_date = date_create(substr($get_shi['PODATECUST'], 4, 2) . "/" . substr($get_shi['PODATECUST'], 6, 2) . "/" .  substr($get_shi['PODATECUST'], 0, 4));
                $crmreqdate = date_create(substr($get_shi['CRMREQDATE'], 4, 2) . "/" . substr($get_shi['CRMREQDATE'], 6, 2) . "/" . substr($get_shi['CRMREQDATE'], 0, 4));
                $shi_date = date_create(substr($get_shi['SHIDATE'], 4, 2) . "/" . substr($get_shi['SHIDATE'], 6, 2) . "/" .  substr($get_shi['SHIDATE'], 0, 4));
                $ontimedeldiff = date_diff($shi_date, $crmreqdate);
                $ontimedeldiff = $ontimedeldiff->format("%a");
                $potodndiff = date_diff($shi_date, $pocust_date);
                $potodndiff = $potodndiff->format("%a");

                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'SHIDOCNUMBER' => $get_shi['DOCNUMBER'],
                    'SHINUMBER' => $get_shi['SHINUMBER'],
                    'SHIDATE' => $get_shi['SHIDATE'],
                    'CUSTRCPDATE' => $get_shi['CUSTRCPDATE'],
                    'ORIGDNRCPSHIDATE' => $get_shi['ORIGDNRCPSHIDATE'],
                    'SHIQTY' => $data_shil['S_SHIQTY'],
                    'SHIQTYOUTSTANDING' => ($data_shil['QTY'] - $data_shil['S_SHIQTY']),
                    'POCUSTSTATUS' => $shistatus,
                    'ONTIMEDELDAYS' => $ontimedeldiff,
                    'POTODNDAYS' => $potodndiff,
                );
                $this->DeliveryordersModel->ot_deliveryorders_update($csruniq, $csrluniq, $data2);
            endforeach;


            if ($sender['OFFLINESTAT'] == 0) {
                $get_shi_data = $this->DeliveryordersModel->get_shijoincsr_by_shi($shiuniq);
                if (!empty($get_shi_data['EDNFILENAME'])) {
                    //Untuk Update Status Posting CSR
                    $data3 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'EDNPOSTINGSTAT' => 1,
                        'POSTINGSTAT' => 1,
                        'OFFLINESTAT' => 0,
                    );


                    $crmpodate = substr($get_shi_data['PODATECUST'], 4, 2) . "/" . substr($get_shi_data['PODATECUST'], 6, 2) . "/" .  substr($get_shi_data['PODATECUST'], 0, 4);
                    $crmreqdate = substr($get_shi_data['CRMREQDATE'], 4, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 6, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 0, 4);
                    $rqndate = substr($get_shi_data['RQNDATE'], 4, 2) . "/" . substr($get_shi_data['RQNDATE'], 6, 2) . "/" .  substr($get_shi_data['RQNDATE'], 0, 4);
                    $povendordate = substr($get_shi_data['PODATE'], 4, 2) . "/" . substr($get_shi_data['PODATE'], 6, 2) . "/" .  substr($get_shi_data['PODATE'], 0, 4);
                    $etddate = substr($get_shi_data['ETDDATE'], 4, 2) . "/" . substr($get_shi_data['ETDDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDDATE'], 0, 4);
                    $cargoreadinessdate = substr($get_shi_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_shi_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_shi_data['CARGOREADINESSDATE'], 0, 4);
                    $etdorigindate = substr($get_shi_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDORIGINDATE'], 0, 4);
                    $atdorigindate = substr($get_shi_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ATDORIGINDATE'], 0, 4);
                    $etaportdate = substr($get_shi_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_shi_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_shi_data['ETAPORTDATE'], 0, 4);
                    $pibdate = substr($get_shi_data['PIBDATE'], 4, 2) . "/" . substr($get_shi_data['PIBDATE'], 6, 2) . "/" .  substr($get_shi_data['PIBDATE'], 0, 4);
                    $shidate = substr($get_shi_data['SHIDATE'], 4, 2) . "/" . substr($get_shi_data['SHIDATE'], 6, 2) . "/" .  substr($get_shi_data['SHIDATE'], 0, 4);
                    $custrcpdate = substr($get_shi_data['CUSTRCPDATE'], 4, 2) . "/" . substr($get_shi_data['CUSTRCPDATE'], 6, 2) . "/" .  substr($get_shi_data['CUSTRCPDATE'], 0, 4);



                    if (!empty($get_shi_data['EDNFILENAME'])) {

                        $is_attachment = 1;
                        $origdnrcpshidate = substr($get_shi_data['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSHIDATE'], 0, 4);
                    } else {
                        $is_attachment = 0;
                    }
                    // Khusus untuk PROSES Delivery Note Model nya berbeda karena harus kirim ke customer juga
                    $notiftouser_data = $this->NotifModel->get_edn_sendto_user($groupuser, $csruniq);
                    $mail_tmpl = $this->NotifModel->get_template($groupuser);

                    foreach ($notiftouser_data as $sendto_user) :
                        $var_email = array(
                            'TONAME' => $sendto_user['NAME'],
                            'FROMNAME' => $this->audtuser['NAMELGN'],
                            'CONTRACT' => $get_shi_data['CONTRACT'],
                            'CTDESC' => $get_shi_data['CTDESC'],
                            'PROJECT' => $get_shi_data['PROJECT'],
                            'PRJDESC' => $get_shi_data['PRJDESC'],
                            'CUSTOMER' => $get_shi_data['CUSTOMER'],
                            'NAMECUST' => $get_shi_data['NAMECUST'],
                            'EMAIL1CUST' => $get_shi_data['EMAIL1CUST'],
                            'PONUMBERCUST' => $get_shi_data['PONUMBERCUST'],
                            'PODATECUST' => $crmpodate,
                            'CRMNO' => $get_shi_data['CRMNO'],
                            'REQDATE' => $crmreqdate,
                            'ORDERDESC' => $get_shi_data['ORDERDESC'],
                            'REMARKS' => $get_shi_data['CRMREMARKS'],
                            'SALESCODE' => $get_shi_data['MANAGER'],
                            'SALESPERSON' => $get_shi_data['SALESNAME'],
                            'RQNDATE' => $rqndate,
                            'RQNNUMBER' => $get_shi_data['RQNNUMBER'],
                            //DATA VARIABLE PO
                            'PODATE' => $povendordate,
                            'PONUMBER' => $get_shi_data['PONUMBER'],
                            'ETDDATE' => $etddate,
                            'CARGOREADINESSDATE' => $cargoreadinessdate,
                            'ORIGINCOUNTRY' => $get_shi_data['ORIGINCOUNTRY'],
                            'POREMARKS' => $get_shi_data['POREMARKS'],
                            //DATA VARIABLE LOGISTICS
                            'ETDORIGINDATE' => $etdorigindate,
                            'ATDORIGINDATE' => $atdorigindate,
                            'ETAPORTDATE' => $etaportdate,
                            'PIBDATE' => $pibdate,
                            'VENDSHISTATUS' => $get_shi_data['VENDSHISTATUS'],
                            //DATA VARIABLE RECEIPTS
                            //'RECPNUMBER' => $get_shi_data['RECPNUMBER'],
                            //'RECPDATE' => $rcpdate,
                            //'VDNAME' => $get_shi_data['VDNAME'],
                            //'DESCRIPTIO' => $get_shi_data['DESCRIPTIO'],
                            //DATA VARIABLE SHIPMENTS
                            'DOCNUMBER' => $get_shi_data['DOCNUMBER'],
                            'SHINUMBER' => $get_shi_data['SHINUMBER'],
                            'SHIDATE' => $shidate,
                            'CUSTRCPDATE' => $custrcpdate,
                            'ORIGDNRCPSHIDATE' => $origdnrcpshidate,

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
                            //TAMBAHAN EMAIL ATTACHMENT
                            'attachment_filepath' => $get_shi_data['EDNFILEPATH'],
                            'attachment_filename' => $get_shi_data['EDNFILENAME'],
                        );


                        $data_notif = array(
                            'MAILKEY' => $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                            'IS_ATTACHED' => $is_attachment,
                            'IS_STAR' => 0,
                            'IS_READSENDER' => 1,
                            'IS_ARCHIVEDSENDER' => 0,
                            'IS_TRASHEDSENDER' => 0,
                            'IS_DELETEDSENDER' => 0,
                            'SENDING_STATUS' => 1,
                            //TAMBAHAN EMAIL ATTACHMENT
                            'ATTACHMENT_FILENAME' => $get_shi_data['EDNFILENAME'],
                            'ATTACHMENT_FILEPATH' => $get_shi_data['EDNFILEPATH'],
                            'OTPROCESS' => $groupuser,
                            'UNIQPROCESS' => $get_shi_data['SHIUNIQ'],
                        );

                        //Check Duplicate Entry & Sending Mail
                        $touser = trim($sendto_user['USERNAME']);
                        $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_shi_data['SHIUNIQ'], $touser);
                        if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . $touser) {
                            session()->set('success', '-1');
                            return redirect()->to(base_url('/deliveryorders'));
                            session()->remove('success');
                        } else if (empty($getmailuniq['MAILKEY'])) {
                            $post_email = $this->NotifModel->mailbox_insert($data_notif);
                            if ($post_email) {
                                $sending_mail = $this->send($data_email);
                            }
                        }

                    endforeach;
                    $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data3);
                }
            }
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
                'SHIATTACHED' => 1,
                'EDNFILENAME' => $filename,
                'EDNFILEPATH' => 'assets/files/edn_attached/' . $filename,
                'ORIGDNRCPSHIDATE' => $this->audtuser['AUDTDATE'],
                'EDNPOSTINGSTAT' => 0,

            );

            $deliveryorders_update = $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data);

            session()->set('success', '1');
            return redirect()->to(base_url('/deliveryorders/shipmentopenview/' . $shiuniq));
            session()->remove('success');
        }
    }




    public function sendnotif($shiuniq)
    {

        //Untuk Update Status Posting CSR
        $data3 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'POSTINGSTAT' => 1,
            'OFFLINESTAT' => 0,
        );
        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 7;
        $get_shi_data = $this->DeliveryordersModel->get_shijoincsr_by_shi($shiuniq);
        $csruniq = $get_shi_data['CSRUNIQ'];
        $crmpodate = substr($get_shi_data['PODATECUST'], 4, 2) . "/" . substr($get_shi_data['PODATECUST'], 6, 2) . "/" .  substr($get_shi_data['PODATECUST'], 0, 4);
        $crmreqdate = substr($get_shi_data['CRMREQDATE'], 4, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 6, 2) . '/' . substr($get_shi_data['CRMREQDATE'], 0, 4);
        $rqndate = substr($get_shi_data['RQNDATE'], 4, 2) . "/" . substr($get_shi_data['RQNDATE'], 6, 2) . "/" .  substr($get_shi_data['RQNDATE'], 0, 4);
        $povendordate = substr($get_shi_data['PODATE'], 4, 2) . "/" . substr($get_shi_data['PODATE'], 6, 2) . "/" .  substr($get_shi_data['PODATE'], 0, 4);
        $etddate = substr($get_shi_data['ETDDATE'], 4, 2) . "/" . substr($get_shi_data['ETDDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDDATE'], 0, 4);
        $cargoreadinessdate = substr($get_shi_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_shi_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_shi_data['CARGOREADINESSDATE'], 0, 4);
        $etdorigindate = substr($get_shi_data['ETDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ETDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ETDORIGINDATE'], 0, 4);
        $atdorigindate = substr($get_shi_data['ATDORIGINDATE'], 4, 2) . "/" . substr($get_shi_data['ATDORIGINDATE'], 6, 2) . "/" .  substr($get_shi_data['ATDORIGINDATE'], 0, 4);
        $etaportdate = substr($get_shi_data['ETAPORTDATE'], 4, 2) . "/" . substr($get_shi_data['ETAPORTDATE'], 6, 2) . "/" .  substr($get_shi_data['ETAPORTDATE'], 0, 4);
        $pibdate = substr($get_shi_data['PIBDATE'], 4, 2) . "/" . substr($get_shi_data['PIBDATE'], 6, 2) . "/" .  substr($get_shi_data['PIBDATE'], 0, 4);
        $shidate = substr($get_shi_data['SHIDATE'], 4, 2) . "/" . substr($get_shi_data['SHIDATE'], 6, 2) . "/" .  substr($get_shi_data['SHIDATE'], 0, 4);
        $custrcpdate = substr($get_shi_data['CUSTRCPDATE'], 4, 2) . "/" . substr($get_shi_data['CUSTRCPDATE'], 6, 2) . "/" .  substr($get_shi_data['CUSTRCPDATE'], 0, 4);
        if (!empty($get_shi_data['EDNFILENAME'])) {

            $is_attachment = 1;
            $origdnrcpshidate = substr($get_shi_data['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($get_shi_data['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($get_shi_data['ORIGDNRCPSHIDATE'], 0, 4);
        } else {
            $is_attachment = 0;
        }
        // Khusus untuk PROSES Delivery Note Model nya berbeda karena harus kirim ke customer juga
        $notiftouser_data = $this->NotifModel->get_edn_sendto_user($groupuser, $csruniq);
        $mail_tmpl = $this->NotifModel->get_template($groupuser);

        foreach ($notiftouser_data as $sendto_user) :
            $var_email = array(
                'TONAME' => $sendto_user['NAME'],
                'FROMNAME' => $this->audtuser['NAMELGN'],
                'CONTRACT' => $get_shi_data['CONTRACT'],
                'CTDESC' => $get_shi_data['CTDESC'],
                'PROJECT' => $get_shi_data['PROJECT'],
                'PRJDESC' => $get_shi_data['PRJDESC'],
                'CUSTOMER' => $get_shi_data['CUSTOMER'],
                'NAMECUST' => $get_shi_data['NAMECUST'],
                'EMAIL1CUST' => $get_shi_data['EMAIL1CUST'],
                'PONUMBERCUST' => $get_shi_data['PONUMBERCUST'],
                'PODATECUST' => $crmpodate,
                'CRMNO' => $get_shi_data['CRMNO'],
                'REQDATE' => $crmreqdate,
                'ORDERDESC' => $get_shi_data['ORDERDESC'],
                'REMARKS' => $get_shi_data['CRMREMARKS'],
                'SALESCODE' => $get_shi_data['MANAGER'],
                'SALESPERSON' => $get_shi_data['SALESNAME'],
                'RQNDATE' => $rqndate,
                'RQNNUMBER' => $get_shi_data['RQNNUMBER'],
                //DATA VARIABLE PO
                'PODATE' => $povendordate,
                'PONUMBER' => $get_shi_data['PONUMBER'],
                'ETDDATE' => $etddate,
                'CARGOREADINESSDATE' => $cargoreadinessdate,
                'ORIGINCOUNTRY' => $get_shi_data['ORIGINCOUNTRY'],
                'POREMARKS' => $get_shi_data['POREMARKS'],
                //DATA VARIABLE LOGISTICS
                'ETDORIGINDATE' => $etdorigindate,
                'ATDORIGINDATE' => $atdorigindate,
                'ETAPORTDATE' => $etaportdate,
                'PIBDATE' => $pibdate,
                'VENDSHISTATUS' => $get_shi_data['VENDSHISTATUS'],
                //DATA VARIABLE RECEIPTS
                //'RECPNUMBER' => $get_shi_data['RECPNUMBER'],
                //'RECPDATE' => $rcpdate,
                //'VDNAME' => $get_shi_data['VDNAME'],
                //'DESCRIPTIO' => $get_shi_data['DESCRIPTIO'],
                //DATA VARIABLE SHIPMENTS
                'DOCNUMBER' => $get_shi_data['DOCNUMBER'],
                'SHINUMBER' => $get_shi_data['SHINUMBER'],
                'SHIDATE' => $shidate,
                'CUSTRCPDATE' => $custrcpdate,
                'ORIGDNRCPSHIDATE' => $origdnrcpshidate,

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
                //TAMBAHAN EMAIL ATTACHMENT
                'attachment_filepath' => $get_shi_data['EDNFILEPATH'],
                'attachment_filename' => $get_shi_data['EDNFILENAME'],
            );


            $data_notif = array(
                'MAILKEY' => $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                'IS_ATTACHED' => $is_attachment,
                'IS_STAR' => 0,
                'IS_READSENDER' => 1,
                'IS_ARCHIVEDSENDER' => 0,
                'IS_TRASHEDSENDER' => 0,
                'IS_DELETEDSENDER' => 0,
                'SENDING_STATUS' => 1,
                //TAMBAHAN EMAIL ATTACHMENT
                'ATTACHMENT_FILENAME' => $get_shi_data['EDNFILENAME'],
                'ATTACHMENT_FILEPATH' => $get_shi_data['EDNFILEPATH'],
                'OTPROCESS' => $groupuser,
                'UNIQPROCESS' => $get_shi_data['SHIUNIQ'],
            );

            //Check Duplicate Entry & Sending Mail
            $touser = trim($sendto_user['USERNAME']);
            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_shi_data['SHIUNIQ'], $touser);
            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_shi_data['SHIUNIQ'] . '-' . $touser) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/deliveryorders'));
                session()->remove('success');
            } else if (empty($getmailuniq['MAILKEY'])) {
                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                if ($post_email) {
                    $sending_mail = $this->send($data_email);
                }
            }

        endforeach;
        $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data3);


        session()->set('success', '1');
        return redirect()->to(base_url('/deliveryorders'));
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
        $attachment_filepath = $data_email['attachment_filepath'];
        $attachment_filename = $data_email['attachment_filename'];


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
            if (!empty($attachment_filename)) {
                $mail->AddAttachment($attachment_filepath, $attachment_filename);   // I took this from the phpmailer example on github but I'm not sure if I have it right.      
            }

            $mail->send();
            session()->setFlashdata('success', 'Send Email successfully');
            return redirect()->to(base_url('/deliveryorders'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/deliveryorders'));
        }
    }
}
