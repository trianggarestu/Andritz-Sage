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
//use App\Models\Settingnavheader_model;
use App\Models\Notif_model;
use App\Models\PurchaseOrder_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class PurchaseOrder extends BaseController
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
        $this->PurchaseorderModel = new Purchaseorder_model();
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
                $activenavd = 'purchaseorder';
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
        session()->remove('cari');
        // Delete Session P/O Form
        session()->remove('po_number');
        session()->remove('po_date');
        session()->remove('etd_date');
        session()->remove('cargoreadiness_date');
        session()->remove('origin_country');
        session()->remove('po_remarks');
        $this->cart->destroy();
        $purchaseorderdata = $this->PurchaseorderModel->get_requisition_pending();
        $polist_data = $this->PurchaseorderModel->get_polist_on_poopen();
        $so_l_open_data = $this->PurchaseorderModel->get_csrl_list_post();

        $data = array(
            'purchaseOrder_data' => $purchaseorderdata,
            'polist_data' => $polist_data,
            'so_l_data' => $so_l_open_data,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('purchaseorder/data_pr_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('purchaseorder'));
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
        return redirect()->to(base_url('purchaseorder/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $purchaseorderdata = $this->PurchaseorderModel->get_requisition_pending();
            $polist_data = $this->PurchaseorderModel->get_polist_on_poopen();
            $so_l_open_data = $this->PurchaseorderModel->get_csrl_list_post();
        } else {
            $purchaseorderdata = $this->PurchaseorderModel->get_requisition_pending_search($keyword);
            $polist_data = $this->PurchaseorderModel->get_polist_on_poopen();
            $so_l_open_data = $this->PurchaseorderModel->get_csrl_list_post();
        }
        $data = array(
            'purchaseOrder_data' => $purchaseorderdata,
            'polist_data' => $polist_data,
            'so_l_data' => $so_l_open_data,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('purchaseorder/data_pr_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function add($rqnuniq, $postingstat, $delpoline = 0)
    {
        $get_pr = $this->PurchaseorderModel->get_requisition_by_id($rqnuniq);
        $get_so_l = $this->PurchaseorderModel->get_so_l_by_id($rqnuniq);
        $get_po = $this->PurchaseorderModel->get_po_by_requisition($rqnuniq);

        if ($get_pr) {
            $act = 'purchaseorder/insert_action';
            if ($postingstat == 0) {
                $button = 'Save';
            } else {
                $button = 'Save & Posting';
            }
            $id_po = '';
            $rqnnumber = $get_pr['RQNNUMBER'];
            $rqndate = substr($get_pr['RQNDATE'], 4, 2) . "/" . substr($get_pr['RQNDATE'], 6, 2) . "/" . substr($get_pr['RQNDATE'], 0, 4);
            $ponumber = '';
            //$podate = '';
            $origincountry = '';
            $poremarks = '';
            //$povendordate = '';
            $etddate = '';
            $cargoreadinessdate = '';
            $cargoreadinessdate = '';
            $posting_status = $postingstat;
            if ($delpoline == 0) {
                foreach ($get_so_l as $items) :
                    $this->cart->insert(array(
                        'id'      => trim($items['ITEMNO']),
                        'qty'     => $items['QTY'],
                        'price'   => '1',
                        'name'    => 'Item Description Sage',
                        'options' => array('so_service' => $items['SERVICETYPE'], 'material_no' => $items['MATERIALNO'], 'itemdesc' => $items['ITEMDESC'], 'so_uom' => $items['STOCKUNIT'], 'csruniq' => $items['CSRUNIQ'], 'csrluniq' => $items['CSRLUNIQ'])
                    ));
                endforeach;
            }

            if ($this->cart->totalItems() == 0) {
                return redirect()->to(base_url('purchaseorder'));
            }


            $data = array(
                'rqnopen_data' => $get_pr,
                'csrlforpo_data' => $this->cart->contents(),
                'csrlforpo_edit_data' => $this->PurchaseorderModel->get_po_l_by_id($id_po),
                'csruniq' => trim($get_pr['CSRUNIQ']),
                'rqnuniq' => trim($get_pr['RQNUNIQ']),
                'po_number' => $ponumber,
                //'po_date' => $podate,
                'etd_date' => $etddate,
                'cargoreadiness_date' => $cargoreadinessdate,
                'origin_country' => $origincountry,
                'po_remarks' => $poremarks,
                'posage_list' => $this->PurchaseorderModel->get_po_list_sage_by_rqn($rqnnumber),
                'form_action' => base_url($act),
                'button' => $button,
                'post_stat' => $postingstat,
                'post_stat_data' => $posting_status,
                'pouniq' => $id_po,
                'cart' => $this->cart,
            );
        }
        //echo view('purchaseorder/ajax_add_purchaseorder', $data);
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('purchaseorder/data_po_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    function form_select_po($rqnuniq, $rqnnumber, $post_stat)
    {

        $ponumber = '';
        $etddate = '';
        $cargoreadinessdate = '';
        $origincountry = '';
        $poremarks = '';




        $data = array(
            'rqnuniq' => $rqnuniq,
            'po_number' => $ponumber,
            //'povendor_date' => $povendordate,
            'etd_date' => $etddate,
            'cargoreadiness_date' => $cargoreadinessdate,
            'origin_country' => $origincountry,
            'po_remarks' => $poremarks,
            'post_stat' => $post_stat,
            'posage_list' => $this->PurchaseorderModel->get_po_list_sage_by_rqn($rqnnumber),
            'form_action' => base_url('purchaseorder/choosepo'),
            'button' => 'Save',
        );
        echo view('purchaseorder/ajax_add_purchaseorder', $data);
    }


    public function choosepo()
    {
        session()->remove('success');
        if (null == ($this->request->getPost('id_pr'))) {
            $rqnuniq = "Requisition Number Not Found";
        } else {
            $rqnuniq = $this->request->getPost('id_pr');
            $ponumber = $this->request->getPost('po_number');
            $etd_date = $this->request->getPost('etd_date');
            $cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
            $origin_country = $this->request->getPost('origin_country');
            $po_remarks = $this->request->getPost('po_remarks');
            $post_stat = $this->request->getPost('post_stat');
            $del_poline = $this->request->getPost('del_poline');
            $posage_data = $this->PurchaseorderModel->get_posage_by_id($ponumber);
            $podate = substr($posage_data['PODATE'], 4, 2) . "/" . substr($posage_data['PODATE'], 6, 2) . "/" . substr($posage_data['PODATE'], 0, 4);
            session()->set('po_number', trim($ponumber));
            session()->set('po_date', trim($podate));
            session()->set('etd_date', trim($etd_date));
            session()->set('cargoreadiness_date', trim($cargoreadiness_date));
            session()->set('origin_country', trim($origin_country));
            session()->set('po_remarks', trim($po_remarks));
        }

        return redirect()->to(base_url('purchaseorder/add/' . $rqnuniq . '/' . $post_stat . '/' . $del_poline));
    }


    public function resetpo($rqnuniq, $post_stat, $del_poline = 0)
    {
        session()->remove('po_number');
        session()->remove('po_date');
        session()->remove('etd_date');
        session()->remove('cargoreadiness_date');
        session()->remove('origin_country');
        session()->remove('po_remarks');
        return redirect()->to(base_url('purchaseorder/add/' . $rqnuniq . '/' . $post_stat . '/' . $del_poline));
    }

    // delete po open
    public function delete($pouniq)
    {
        $chk_po = $this->PurchaseorderModel->get_po_by_pouniq($pouniq);
        if ($chk_po['POSTINGSTAT'] == 1) {
            session()->set('success', '-1');
            return redirect()->to(base_url('purchaseorder'));
            session()->remove('success');
        } else {
            // Remove an PO Open
            $del_po_open = $this->PurchaseorderModel->delete_po_open($pouniq);
            if ($del_po_open) {
                $this->PurchaseorderModel->delete_pol_open($pouniq);
            }

            session()->set('success', '1');
            return redirect()->to(base_url('purchaseorder'));
            session()->remove('success');
        }
    }


    // delete item Cart
    public function delete_item_cart($rqnuniq = 0, $post_stat = 0, $rowid, $delpoline = 0)
    {
        // Remove an item using its `rowid`
        $this->cart->remove($rowid);
        session()->remove('success');
        return redirect()->to(base_url('purchaseorder/add/' . $rqnuniq . '/' . $post_stat . '/' . $delpoline));
    }

    public function update($pouniq, $postingstat)
    {
        session()->remove('success');
        $get_po = $this->PurchaseorderModel->get_po_by_pouniq($pouniq);
        $get_pr = $this->PurchaseorderModel->get_requisition_by_id($get_po['RQNUNIQ']);
        if ($get_po) {

            if (!empty($get_po['RQNUNIQ']) and $get_po['POSTINGSTAT'] == 0) {
                $act = 'purchaseorder/update_action';
                $id_po = $get_po['POUNIQ'];
                $rqnnumber = trim($get_pr['RQNNUMBER']);
                $ponumber = trim($get_po['PONUMBER']);
                $origincountry = trim($get_po['ORIGINCOUNTRY']);
                $poremarks = trim($get_po['POREMARKS']);
                //$povendordate = substr($get_po['PODATE'], 6, 2) . "/" . substr($get_po['PODATE'], 4, 2) . "/" . substr($get_po['PODATE'], 0, 4);
                $etddate = substr($get_po['ETDDATE'], 4, 2) . "/" . substr($get_po['ETDDATE'], 6, 2) . "/" . substr($get_po['ETDDATE'], 0, 4);
                $posting_status = $get_po['POSTINGSTAT'];
                if ($get_po['CARGOREADINESSDATE'] == null) {
                    $cargoreadinessdate = '';
                } else {
                    $cargoreadinessdate = substr($get_po['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po['CARGOREADINESSDATE'], 6, 2) . "/" . substr($get_po['CARGOREADINESSDATE'], 0, 4);
                }

                if ($postingstat == 0) {
                    $button = 'Update & Save Only';
                } else {
                    $button = 'Update & Posting';
                }
            } else {
                return redirect()->to(base_url('purchaseorder'));
            }
            $data = array(
                'csruniq' => trim($get_po['CSRUNIQ']),
                'rqnuniq' => trim($get_po['RQNUNIQ']),
                'po_number' => $ponumber,
                'etd_date' => $etddate,
                'cargoreadiness_date' => $cargoreadinessdate,
                'origin_country' => $origincountry,
                'po_remarks' => $poremarks,
                'posage_list' => $this->PurchaseorderModel->get_po_list_sage_by_rqn($rqnnumber),
                'form_action' => base_url($act),
                'button' => $button,
                'post_stat' => $postingstat,
                'post_stat_data' => $posting_status,
                'pouniq' => $id_po,
            );
        }
        echo view('purchaseorder/ajax_add_purchaseorder', $data);
    }


    public function update_cargoreadiness($pouniq, $post_stat)
    {

        $get_po = $this->PurchaseorderModel->get_po_by_pouniq($pouniq);

        if ($get_po) {
            $get_pr = $this->PurchaseorderModel->get_requisition_by_id($get_po['RQNUNIQ']);
            $act = 'purchaseorder/update_cargoreadiness_action';
            $id_po = $get_po['POUNIQ'];
            $ponumber = $get_po['PONUMBER'];
            $origincountry = trim($get_po['ORIGINCOUNTRY']);
            $poremarks = trim($get_po['POREMARKS']);
            $cargoreadinessdate = '';
            $etddate = substr($get_po['ETDDATE'], 4, 2) . "/" . substr($get_po['ETDDATE'], 6, 2) . "/" . substr($get_po['ETDDATE'], 0, 4);


            $data = array(
                'csruniq' => $get_pr['CSRUNIQ'],
                'rqnuniq' => $get_pr['RQNUNIQ'],
                'pouniq' => $id_po,
                'po_number' => $ponumber,
                'etd_date' => $etddate,
                'cargoreadiness_date' => $cargoreadinessdate,
                'origin_country' => $origincountry,
                'po_remarks' => $poremarks,
                'post_stat' => $post_stat,
                'posage_list' => $this->PurchaseorderModel->get_po_list_sage_by_rqn($get_po['RQNNUMBER']),
                'form_action' => base_url($act),
                'button' => 'Update & Posting',

            );
        }
        echo view('purchaseorder/ajax_add_po_cargoreadiness', $data);
    }

    public function insert_action()
    {
        $id_so = $this->request->getPost('csruniq');
        $id_pr = $this->request->getPost('rqnuniq');
        $ponumber = $this->request->getPost('po_number');
        $delpoline = $this->request->getPost('del_poline');
        $etd_date = $this->request->getPost('etd_date');
        $cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
        $origin_country = $this->request->getPost('origin_country');
        $po_remarks = $this->request->getPost('po_remarks');
        $post_stat = $this->request->getPost('post_stat');
        if (null == $id_pr and null == $ponumber and null == $etd_date and null == $origin_country and null == $po_remarks) {
            session()->set('success', '-1');
            return redirect()->to(base_url('/purchaseorder/add/' . $id_pr . '/' . $post_stat . '/' . $delpoline))->withInput();
            session()->remove('success');
        } else if (!$this->validate(['chk_item' => 'greater_than[0]'])) {

            session()->set('success', '-1');
            return redirect()->to(base_url('/purchaseorder/add/' . $id_pr . '/' . $post_stat . '/' . $delpoline))->withInput();
            session()->remove('success');
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $ponumber = $ponumber;
            $post_stat = $post_stat;
            $get_pr = $this->PurchaseorderModel->get_requisition_by_id($id_pr);
            $choose_po = $this->PurchaseorderModel->get_posage_by_id($ponumber);

            $n_etd_date = substr($etd_date, 6, 4) . substr($etd_date, 0, 2) . substr($etd_date, 3, 2);
            if (null == $cargoreadiness_date) {
                $n_cargoreadiness_date = '';
            } else {
                $n_cargoreadiness_date = substr($cargoreadiness_date, 6, 4) . substr($cargoreadiness_date, 0, 2) . substr($cargoreadiness_date, 3, 2);
            }
            $n_cargoreadiness_date  = empty($n_cargoreadiness_date) ? NULL : $n_cargoreadiness_date;

            if (!empty($po_number) and !empty($n_etd_date) and !empty($n_cargoreadiness_date) and !empty($origin_country) and !empty($po_remarks) and $post_stat == 1) {
                $offline_stat = $sender['OFFLINESTAT'];
            } else {
                $offline_stat = 1;
            }

            $groupuser = 4;

            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'POKEY' => $id_so . '-' . trim($get_pr['RQNNUMBER']) . '-' . $ponumber,
                'CSRUNIQ' => $id_so,
                'RQNUNIQ' => $id_pr,
                'RQNNUMBER' => trim($get_pr['RQNNUMBER']),
                'PODATE' => $choose_po["PODATE"],
                'PONUMBER' => trim($choose_po["PONUMBER"]),
                'ETDDATE' => $n_etd_date,
                'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                'ORIGINCOUNTRY' => $origin_country,
                'POREMARKS' => $po_remarks,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => $offline_stat,
            );

            $getpouniq = $this->PurchaseorderModel->get_pouniq_open($id_so, $get_pr['RQNNUMBER'], $choose_po["PONUMBER"]);
            if (!empty($getpouniq['POKEY']) and $getpouniq['CHKPOL'] > 0 and $getpouniq['POKEY'] == $id_so . '-' . trim($get_pr['RQNNUMBER']) . '-' . trim($choose_po["PONUMBER"])) {
                session()->set('success', '-1');
                return redirect()->to(base_url('purchaseorder/add/' . $get_pr['RQNUNIQ'] . '/' . $post_stat . '/' . $delpoline));
                session()->remove('success');
            } else if (!empty($getpouniq['POKEY']) and $getpouniq['CHKPOL'] == 0 and $getpouniq['POKEY'] == $id_so . '-' . trim($get_pr['RQNNUMBER']) . '-' . trim($choose_po["PONUMBER"])) {

                foreach ($this->cart->contents() as $items) :
                    $datal = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                        'AUDTORG' => trim($this->audtuser['AUDTORG']),
                        'POUNIQ' => $getpouniq['POUNIQ'],
                        'CSRUNIQ' => $items['options']['csruniq'],
                        'CSRLUNIQ' => $items['options']['csrluniq'],
                        'SERVICETYPE' => $items['options']['so_service'],
                        'ITEMNO' => $items['id'],
                        'STOCKUNIT' => $items['options']['so_uom'],
                        'QTY' => $items['qty'],
                    );
                    $pol_insert = $this->PurchaseorderModel->poline_insert($datal);
                endforeach;
                if ($pol_insert) {
                    $this->cart->destroy();
                    $bysetting = 1;
                    session()->set('success', '1');
                    return redirect()->to(base_url('purchaseorder'));
                    session()->remove('success');
                }
            } else if (empty($getpouniq['POKEY'])) {
                $po_insert = $this->PurchaseorderModel->purchaseorder_insert($data1);
                if ($po_insert) {
                    $getpouniq = $this->PurchaseorderModel->get_pouniq_open($id_so, $get_pr['RQNNUMBER'], $choose_po["PONUMBER"]);
                    $pouniq = $getpouniq['POUNIQ'];
                    foreach ($this->cart->contents() as $items) :
                        $datal = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                            'AUDTORG' => trim($this->audtuser['AUDTORG']),
                            'POUNIQ' => $getpouniq['POUNIQ'],
                            'CSRUNIQ' => $items['options']['csruniq'],
                            'CSRLUNIQ' => $items['options']['csrluniq'],
                            'SERVICETYPE' => $items['options']['so_service'],
                            'ITEMNO' => $items['id'],
                            'STOCKUNIT' => $items['options']['so_uom'],
                            'QTY' => $items['qty'],
                        );

                        $pol_insert = $this->PurchaseorderModel->poline_insert($datal);
                    endforeach;

                    $this->cart->destroy();


                    // Jika Posting
                    if ($post_stat == 1) {
                        $getpouniq = $this->PurchaseorderModel->get_pouniq_open($id_so, $get_pr['RQNNUMBER'], $choose_po["PONUMBER"]);
                        $pouniq = $getpouniq['POUNIQ'];
                        $po_to_ot = $this->PurchaseorderModel->get_po_open_by_id($pouniq);
                        foreach ($po_to_ot as $data_pol) :
                            $csrluniq = $data_pol['CSRLUNIQ'];

                            $podate = substr($data_pol['PODATE'], 4, 2) . "/" . substr($data_pol['PODATE'], 6, 2) . "/" . substr($data_pol['PODATE'], 0, 4);
                            $podate2 = date_create(substr($data_pol['PODATE'], 4, 2) . "/" . substr($data_pol['PODATE'], 6, 2) . "/" . substr($data_pol['PODATE'], 0, 4));
                            $pocust_date = date_create(substr($get_pr['PODATECUST'], 4, 2) . "/" . substr($get_pr['PODATECUST'], 6, 2) . "/" .  substr($get_pr['PODATECUST'], 0, 4));
                            $pocusttopodiff = date_diff($podate2, $pocust_date);
                            $pocusttopodiff = $pocusttopodiff->format("%a");
                            $data2 = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                'AUDTORG' => $this->audtuser['AUDTORG'],
                                'PODATE' => $data_pol["PODATE"],
                                'PONUMBER' => $data_pol["PONUMBER"],
                                'ETDDATE' => $data_pol["ETDDATE"],
                                'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                                'ORIGINCOUNTRY' => $data_pol["ORIGINCOUNTRY"],
                                'POREMARKS' => $data_pol["POREMARKS"],
                                'POTOPODAYS' => $pocusttopodiff,
                            );

                            $this->PurchaseorderModel->ot_purchaseorder_update($id_so, $csrluniq, $data2);

                        endforeach;

                        // for check complete input
                        $chk_ponumber = $this->request->getPost('po_number');
                        $chk_etd_date = $this->request->getPost('etd_date');
                        $chk_cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
                        $chk_origin_country = $this->request->getPost('origin_country');
                        $chk_po_remarks = $this->request->getPost('po_remarks');
                        if (!empty($chk_ponumber) and !empty($chk_etd_date) and !empty($chk_cargoreadiness_date) and !empty($chk_origin_country) and !empty($chk_po_remarks)) {

                            $get_po_data = $this->PurchaseorderModel->get_pojoincsr_by_po($pouniq);
                            $crmpodate = substr($get_po_data['PODATECUST'], 4, 2) . "/" . substr($get_po_data['PODATECUST'], 6, 2) . "/" .  substr($get_po_data['PODATECUST'], 0, 4);
                            $crmreqdate = substr($get_po_data['CRMREQDATE'], 4, 2) . '/' . substr($get_po_data['CRMREQDATE'], 6, 2) . '/' . substr($get_po_data['CRMREQDATE'], 0, 4);
                            $rqndate = substr($get_po_data['RQNDATE'], 4, 2) . "/" . substr($get_po_data['RQNDATE'], 6, 2) . "/" .  substr($get_po_data['RQNDATE'], 0, 4);
                            $povendordate = substr($get_po_data['PODATE'], 4, 2) . "/" . substr($get_po_data['PODATE'], 6, 2) . "/" .  substr($get_po_data['PODATE'], 0, 4);
                            $etddate = substr($get_po_data['ETDDATE'], 4, 2) . "/" . substr($get_po_data['ETDDATE'], 6, 2) . "/" .  substr($get_po_data['ETDDATE'], 0, 4);
                            $cargoreadinessdate = substr($get_po_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_po_data['CARGOREADINESSDATE'], 0, 4);

                            if ($sender['OFFLINESTAT'] == 0) {
                                //Untuk Update Status Posting PO
                                $data3 = array(
                                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                    'AUDTORG' => $this->audtuser['AUDTORG'],
                                    'POSTINGSTAT' => 1,
                                    'OFFLINESTAT' => 0,
                                );
                                //inisiasi proses kirim ke group
                                $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                                $mail_tmpl = $this->NotifModel->get_template($groupuser);
                                foreach ($notiftouser_data as $sendto_user) :
                                    $var_email = array(
                                        'TONAME' => $sendto_user['NAME'],
                                        'FROMNAME' => $this->audtuser['NAMELGN'],
                                        'CONTRACT' => $get_po_data['CONTRACT'],
                                        'CTDESC' => $get_po_data['CTDESC'],
                                        'PROJECT' => $get_po_data['PROJECT'],
                                        'PRJDESC' => $get_po_data['PRJDESC'],
                                        'CUSTOMER' => $get_po_data['CUSTOMER'],
                                        'NAMECUST' => $get_po_data['NAMECUST'],
                                        'PONUMBERCUST' => $get_po_data['PONUMBERCUST'],
                                        'PODATECUST' => $crmpodate,
                                        'CRMNO' => $get_po_data['CRMNO'],
                                        'REQDATE' => $crmreqdate,
                                        'ORDERDESC' => $get_po_data['ORDERDESC'],
                                        'REMARKS' => $get_po_data['CRMREMARKS'],
                                        'SALESCODE' => $get_po_data['MANAGER'],
                                        'SALESPERSON' => $get_po_data['SALESNAME'],
                                        'RQNDATE' => $rqndate,
                                        'RQNNUMBER' => $get_po_data['RQNNUMBER'],
                                        //DATA VARIABLE PO
                                        'PODATE' => $povendordate,
                                        'PONUMBER' => $get_po_data['PONUMBER'],
                                        'ETDDATE' => $etddate,
                                        'CARGOREADINESSDATE' => $cargoreadinessdate,
                                        'ORIGINCOUNTRY' => $get_po_data['ORIGINCOUNTRY'],
                                        'POREMARKS' => $get_po_data['POREMARKS'],
                                    );
                                    $subject = $mail_tmpl['SUBJECT_MAIL'];
                                    $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                                    $data_email = array(
                                        'hostname'       => $sender['HOSTNAME'],
                                        'sendername'       => $sender['SENDERNAME'],
                                        'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                        'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                        'ssl'       => $sender['SSL'],
                                        'smtpport'       => $sender['SMTPPORT'],
                                        'to_email' => $sendto_user['EMAIL'],
                                        'subject' =>  $subject,
                                        'message' => $message,
                                    );


                                    $data_notif = array(
                                        'MAILKEY' => $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                                        'UNIQPROCESS' => $get_po_data['POUNIQ'],
                                    );

                                    //Check Duplicate Entry & Sending Mail
                                    $touser = trim($sendto_user['USERNAME']);
                                    $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_po_data['POUNIQ'], $touser);
                                    if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . $touser) {
                                        session()->set('success', '-1');
                                        return redirect()->to(base_url('/purchaseorder'));
                                        session()->remove('success');
                                    } else if (empty($getmailuniq['MAILKEY'])) {
                                        $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                        if ($post_email) {
                                            $sending_mail = $this->send($data_email);
                                        }
                                    }

                                endforeach;

                                $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                                session()->set('success', '1');
                                return redirect()->to(base_url('/purchaseorderlist'));
                                session()->remove('success');
                            } else {
                                $data3 = array(
                                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                    'AUDTORG' => $this->audtuser['AUDTORG'],
                                    'POSTINGSTAT' => 1,
                                    'OFFLINESTAT' => 1,
                                );
                                $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                                //session()->setFlashdata('messageerror', 'Create Record Failed');
                                session()->set('success', '1');
                                return redirect()->to(base_url('/purchaseorderlist'));
                                session()->remove('success');
                            }
                        }
                    }
                }
            }
            session()->set('success', '1');
            return redirect()->to(base_url('/purchaseorder'));
            session()->remove('success');
        }
    }


    public function update_action()
    {
        $id_so = $this->request->getPost('csruniq');
        $id_pr = $this->request->getPost('id_pr');
        $id_po = $this->request->getPost('id_po');
        $etd_date = $this->request->getPost('etd_date');
        $cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
        $origin_country = $this->request->getPost('origin_country');
        $po_remarks = $this->request->getPost('po_remarks');
        if (null == $id_pr and null == $etd_date and null == $origin_country and null == $po_remarks) {
            session()->set('success', '-1');
            return redirect()->to(base_url('/purchaseorder'));
            session()->remove('success');
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            //$ponumber = $this->request->getPost('po_number');
            $post_stat = $this->request->getPost('post_stat');
            $get_pr = $this->PurchaseorderModel->get_requisition_by_id($id_pr);

            $n_etd_date = substr($etd_date, 6, 4) . substr($etd_date, 0, 2) . substr($etd_date, 3, 2);
            if (null == $cargoreadiness_date) {
                $n_cargoreadiness_date = '';
            } else {
                $n_cargoreadiness_date = substr($cargoreadiness_date, 6, 4) . substr($cargoreadiness_date, 0, 2) . substr($cargoreadiness_date, 3, 2);
            }
            $n_cargoreadiness_date  = empty($n_cargoreadiness_date) ? NULL : $n_cargoreadiness_date;

            if (!empty($po_number) and !empty($n_etd_date) and !empty($n_cargoreadiness_date) and !empty($origin_country) and !empty($po_remarks) and $post_stat == 1) {
                $offline_stat = $sender['OFFLINESTAT'];
            } else {
                $offline_stat = 1;
            }

            $groupuser = 4;

            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'ETDDATE' => $n_etd_date,
                'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                'ORIGINCOUNTRY' => $origin_country,
                'POREMARKS' => $po_remarks,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => $offline_stat,
            );
            $this->PurchaseorderModel->purchaseorder_update($id_po, $data1);

            if ($post_stat == 1) {
                $get_po = $this->PurchaseorderModel->get_purchaseorder_post($id_po);
                $getpouniq = $this->PurchaseorderModel->get_pouniq_open($id_so, $get_pr['RQNNUMBER'], $get_po["PONUMBER"]);
                $pouniq = $getpouniq['POUNIQ'];
                $po_to_ot = $this->PurchaseorderModel->get_po_open_by_id($pouniq);
                foreach ($po_to_ot as $data_pol) :
                    $csrluniq = $data_pol['CSRLUNIQ'];

                    $podate = substr($data_pol['PODATE'], 4, 2) . "/" . substr($data_pol['PODATE'], 6, 2) . "/" . substr($data_pol['PODATE'], 0, 4);
                    $podate2 = date_create(substr($data_pol['PODATE'], 4, 2) . "/" . substr($data_pol['PODATE'], 6, 2) . "/" . substr($data_pol['PODATE'], 0, 4));
                    $pocust_date = date_create(substr($get_pr['PODATECUST'], 4, 2) . "/" . substr($get_pr['PODATECUST'], 6, 2) . "/" .  substr($get_pr['PODATECUST'], 0, 4));
                    $pocusttopodiff = date_diff($podate2, $pocust_date);
                    $pocusttopodiff = $pocusttopodiff->format("%a");
                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'PODATE' => $data_pol["PODATE"],
                        'PONUMBER' => $data_pol["PONUMBER"],
                        'ETDDATE' => $data_pol["ETDDATE"],
                        'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                        'ORIGINCOUNTRY' => $data_pol["ORIGINCOUNTRY"],
                        'POREMARKS' => $data_pol["POREMARKS"],
                        'POTOPODAYS' => $pocusttopodiff,
                    );

                    $this->PurchaseorderModel->ot_purchaseorder_update($id_so, $csrluniq, $data2);

                endforeach;

                // for check complete input
                //$chk_ponumber = $this->request->getPost('po_number');
                $chk_etd_date = $this->request->getPost('etd_date');
                $chk_cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
                $chk_origin_country = $this->request->getPost('origin_country');
                $chk_po_remarks = $this->request->getPost('po_remarks');
                if (!empty($chk_etd_date) and !empty($chk_cargoreadiness_date) and !empty($chk_origin_country) and !empty($chk_po_remarks)) {

                    $get_po_data = $this->PurchaseorderModel->get_pojoincsr_by_po($pouniq);
                    $crmpodate = substr($get_po_data['PODATECUST'], 4, 2) . "/" . substr($get_po_data['PODATECUST'], 6, 2) . "/" .  substr($get_po_data['PODATECUST'], 0, 4);
                    $crmreqdate = substr($get_po_data['CRMREQDATE'], 4, 2) . '/' . substr($get_po_data['CRMREQDATE'], 6, 2) . '/' . substr($get_po_data['CRMREQDATE'], 0, 4);
                    $rqndate = substr($get_po_data['RQNDATE'], 4, 2) . "/" . substr($get_po_data['RQNDATE'], 6, 2) . "/" .  substr($get_po_data['RQNDATE'], 0, 4);
                    $povendordate = substr($get_po_data['PODATE'], 4, 2) . "/" . substr($get_po_data['PODATE'], 6, 2) . "/" .  substr($get_po_data['PODATE'], 0, 4);
                    $etddate = substr($get_po_data['ETDDATE'], 4, 2) . "/" . substr($get_po_data['ETDDATE'], 6, 2) . "/" .  substr($get_po_data['ETDDATE'], 0, 4);
                    $cargoreadinessdate = substr($get_po_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_po_data['CARGOREADINESSDATE'], 0, 4);

                    if ($sender['OFFLINESTAT'] == 0) {
                        //Untuk Update Status Posting PO
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 0,
                        );
                        //inisiasi proses kirim ke group
                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                        $mail_tmpl = $this->NotifModel->get_template($groupuser);
                        foreach ($notiftouser_data as $sendto_user) :
                            $var_email = array(
                                'TONAME' => $sendto_user['NAME'],
                                'FROMNAME' => $this->audtuser['NAMELGN'],
                                'CONTRACT' => $get_po_data['CONTRACT'],
                                'CTDESC' => $get_po_data['CTDESC'],
                                'PROJECT' => $get_po_data['PROJECT'],
                                'PRJDESC' => $get_po_data['PRJDESC'],
                                'CUSTOMER' => $get_po_data['CUSTOMER'],
                                'NAMECUST' => $get_po_data['NAMECUST'],
                                'PONUMBERCUST' => $get_po_data['PONUMBERCUST'],
                                'PODATECUST' => $crmpodate,
                                'CRMNO' => $get_po_data['CRMNO'],
                                'REQDATE' => $crmreqdate,
                                'ORDERDESC' => $get_po_data['ORDERDESC'],
                                'REMARKS' => $get_po_data['CRMREMARKS'],
                                'SALESCODE' => $get_po_data['MANAGER'],
                                'SALESPERSON' => $get_po_data['SALESNAME'],
                                'RQNDATE' => $rqndate,
                                'RQNNUMBER' => $get_po_data['RQNNUMBER'],
                                //DATA VARIABLE PO
                                'PODATE' => $povendordate,
                                'PONUMBER' => $get_po_data['PONUMBER'],
                                'ETDDATE' => $etddate,
                                'CARGOREADINESSDATE' => $cargoreadinessdate,
                                'ORIGINCOUNTRY' => $get_po_data['ORIGINCOUNTRY'],
                                'POREMARKS' => $get_po_data['POREMARKS'],
                            );
                            $subject = $mail_tmpl['SUBJECT_MAIL'];
                            $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                            $data_email = array(
                                'hostname'       => $sender['HOSTNAME'],
                                'sendername'       => $sender['SENDERNAME'],
                                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                'ssl'       => $sender['SSL'],
                                'smtpport'       => $sender['SMTPPORT'],
                                'to_email' => $sendto_user['EMAIL'],
                                'subject' =>  $subject,
                                'message' => $message,
                            );


                            $data_notif = array(
                                'MAILKEY' => $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                                'UNIQPROCESS' => $get_po_data['POUNIQ'],
                            );

                            //Check Duplicate Entry & Sending Mail
                            $touser = trim($sendto_user['USERNAME']);
                            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_po_data['POUNIQ'], $touser);
                            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . $touser) {
                                session()->set('success', '-1');
                                return redirect()->to(base_url('/purchaseorder'));
                                session()->remove('success');
                            } else if (empty($getmailuniq['MAILKEY'])) {
                                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                if ($post_email) {
                                    $sending_mail = $this->send($data_email);
                                }
                            }

                        endforeach;

                        $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                        session()->set('success', '1');
                        return redirect()->to(base_url('/purchaseorderlist'));
                        session()->remove('success');
                    } else {
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 1,
                        );
                        $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                        //session()->setFlashdata('messageerror', 'Create Record Failed');
                        session()->set('success', '1');
                        return redirect()->to(base_url('/purchaseorderlist'));
                        session()->remove('success');
                    }
                }
            }
        }

        session()->set('success', '1');
        return redirect()->to(base_url('/purchaseorder'));
        session()->remove('success');
    }


    public function update_cargoreadiness_action()
    {
        $id_so = $this->request->getPost('csruniq');
        $id_pr = $this->request->getPost('id_pr');
        $id_po = $this->request->getPost('id_po');
        $cargoreadiness_date = $this->request->getPost('cargoreadiness_date');
        if (null == $cargoreadiness_date) {
            session()->set('success', '-1');
            return redirect()->to(base_url('/purchaseorder'));
            session()->remove('success');
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            //$ponumber = $this->request->getPost('po_number');
            $post_stat = $this->request->getPost('post_stat');
            $get_pr = $this->PurchaseorderModel->get_requisition_by_id($id_pr);

            $n_cargoreadiness_date = substr($cargoreadiness_date, 6, 4) . substr($cargoreadiness_date, 0, 2) . substr($cargoreadiness_date, 3, 2);

            $n_cargoreadiness_date  = empty($n_cargoreadiness_date) ? NULL : $n_cargoreadiness_date;

            if (!empty($n_cargoreadiness_date) and $post_stat == 1) {
                $offline_stat = $sender['OFFLINESTAT'];
            } else {
                $offline_stat = 1;
            }

            $groupuser = 4;

            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                'OTPROCESS' => $groupuser,
                'OFFLINESTAT' => $offline_stat,
            );
            $this->PurchaseorderModel->purchaseorder_update($id_po, $data1);

            if ($post_stat == 1) {
                $get_po = $this->PurchaseorderModel->get_purchaseorder_post($id_po);
                $getpouniq = $this->PurchaseorderModel->get_pouniq_open($id_so, $get_pr['RQNNUMBER'], $get_po["PONUMBER"]);
                $pouniq = $getpouniq['POUNIQ'];
                $po_to_ot = $this->PurchaseorderModel->get_po_open_by_id($pouniq);
                foreach ($po_to_ot as $data_pol) :
                    $csrluniq = $data_pol['CSRLUNIQ'];

                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'CARGOREADINESSDATE' => $n_cargoreadiness_date,
                    );

                    $this->PurchaseorderModel->ot_purchaseorder_update($id_so, $csrluniq, $data2);

                endforeach;

                // for check complete input

                $chk_cargoreadiness_date = $this->request->getPost('cargoreadiness_date');

                if (!empty($chk_cargoreadiness_date)) {

                    $get_po_data = $this->PurchaseorderModel->get_pojoincsr_by_po($pouniq);
                    $crmpodate = substr($get_po_data['PODATECUST'], 4, 2) . "/" . substr($get_po_data['PODATECUST'], 6, 2) . "/" .  substr($get_po_data['PODATECUST'], 0, 4);
                    $crmreqdate = substr($get_po_data['CRMREQDATE'], 4, 2) . '/' . substr($get_po_data['CRMREQDATE'], 6, 2) . '/' . substr($get_po_data['CRMREQDATE'], 0, 4);
                    $rqndate = substr($get_po_data['RQNDATE'], 4, 2) . "/" . substr($get_po_data['RQNDATE'], 6, 2) . "/" .  substr($get_po_data['RQNDATE'], 0, 4);
                    $povendordate = substr($get_po_data['PODATE'], 4, 2) . "/" . substr($get_po_data['PODATE'], 6, 2) . "/" .  substr($get_po_data['PODATE'], 0, 4);
                    $etddate = substr($get_po_data['ETDDATE'], 4, 2) . "/" . substr($get_po_data['ETDDATE'], 6, 2) . "/" .  substr($get_po_data['ETDDATE'], 0, 4);
                    $cargoreadinessdate = substr($get_po_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_po_data['CARGOREADINESSDATE'], 0, 4);

                    if ($sender['OFFLINESTAT'] == 0) {
                        //Untuk Update Status Posting PO
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 0,
                        );
                        //inisiasi proses kirim ke group
                        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
                        $mail_tmpl = $this->NotifModel->get_template($groupuser);
                        foreach ($notiftouser_data as $sendto_user) :
                            $var_email = array(
                                'TONAME' => $sendto_user['NAME'],
                                'FROMNAME' => $this->audtuser['NAMELGN'],
                                'CONTRACT' => $get_po_data['CONTRACT'],
                                'CTDESC' => $get_po_data['CTDESC'],
                                'PROJECT' => $get_po_data['PROJECT'],
                                'PRJDESC' => $get_po_data['PRJDESC'],
                                'CUSTOMER' => $get_po_data['CUSTOMER'],
                                'NAMECUST' => $get_po_data['NAMECUST'],
                                'PONUMBERCUST' => $get_po_data['PONUMBERCUST'],
                                'PODATECUST' => $crmpodate,
                                'CRMNO' => $get_po_data['CRMNO'],
                                'REQDATE' => $crmreqdate,
                                'ORDERDESC' => $get_po_data['ORDERDESC'],
                                'REMARKS' => $get_po_data['CRMREMARKS'],
                                'SALESCODE' => $get_po_data['MANAGER'],
                                'SALESPERSON' => $get_po_data['SALESNAME'],
                                'RQNDATE' => $rqndate,
                                'RQNNUMBER' => $get_po_data['RQNNUMBER'],
                                //DATA VARIABLE PO
                                'PODATE' => $povendordate,
                                'PONUMBER' => $get_po_data['PONUMBER'],
                                'ETDDATE' => $etddate,
                                'CARGOREADINESSDATE' => $cargoreadinessdate,
                                'ORIGINCOUNTRY' => $get_po_data['ORIGINCOUNTRY'],
                                'POREMARKS' => $get_po_data['POREMARKS'],
                            );
                            $subject = $mail_tmpl['SUBJECT_MAIL'];
                            $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                            $data_email = array(
                                'hostname'       => $sender['HOSTNAME'],
                                'sendername'       => $sender['SENDERNAME'],
                                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                                'ssl'       => $sender['SSL'],
                                'smtpport'       => $sender['SMTPPORT'],
                                'to_email' => $sendto_user['EMAIL'],
                                'subject' =>  $subject,
                                'message' => $message,
                            );


                            $data_notif = array(
                                'MAILKEY' => $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                                'UNIQPROCESS' => $get_po_data['POUNIQ'],
                            );

                            //Check Duplicate Entry & Sending Mail
                            $touser = trim($sendto_user['USERNAME']);
                            $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_po_data['POUNIQ'], $touser);
                            if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . $touser) {
                                session()->set('success', '-1');
                                return redirect()->to(base_url('/purchaseorder'));
                                session()->remove('success');
                            } else if (empty($getmailuniq['MAILKEY'])) {
                                $post_email = $this->NotifModel->mailbox_insert($data_notif);
                                if ($post_email) {
                                    $sending_mail = $this->send($data_email);
                                }
                            }

                        endforeach;

                        $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                        session()->set('success', '1');
                        return redirect()->to(base_url('/purchaseorderlist'));
                        session()->remove('success');
                    } else {
                        $data3 = array(
                            'AUDTDATE' => $this->audtuser['AUDTDATE'],
                            'AUDTTIME' => $this->audtuser['AUDTTIME'],
                            'AUDTUSER' => $this->audtuser['AUDTUSER'],
                            'AUDTORG' => $this->audtuser['AUDTORG'],
                            'POSTINGSTAT' => 1,
                            'OFFLINESTAT' => 1,
                        );
                        $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
                        //session()->setFlashdata('messageerror', 'Create Record Failed');
                        session()->set('success', '1');
                        return redirect()->to(base_url('/purchaseorder'));
                        session()->remove('success');
                    }
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/purchaseorder'));
        session()->remove('success');
    }


    public function sendnotif($pouniq)
    {
        $get_po_data = $this->PurchaseorderModel->get_pojoincsr_by_po($pouniq);
        if (!empty($get_po_data['CARGOREADINESSDATE'])) {
            //Set Format Date for variable Mail Notif
            $crmpodate = substr($get_po_data['PODATECUST'], 4, 2) . "/" . substr($get_po_data['PODATECUST'], 6, 2) . "/" .  substr($get_po_data['PODATECUST'], 0, 4);
            $crmreqdate = substr($get_po_data['CRMREQDATE'], 4, 2) . '/' . substr($get_po_data['CRMREQDATE'], 6, 2) . '/' . substr($get_po_data['CRMREQDATE'], 0, 4);
            $rqndate = substr($get_po_data['RQNDATE'], 4, 2) . "/" . substr($get_po_data['RQNDATE'], 6, 2) . "/" .  substr($get_po_data['RQNDATE'], 0, 4);
            $povendordate = substr($get_po_data['PODATE'], 4, 2) . "/" . substr($get_po_data['PODATE'], 6, 2) . "/" .  substr($get_po_data['PODATE'], 0, 4);
            $etddate = substr($get_po_data['ETDDATE'], 4, 2) . "/" . substr($get_po_data['ETDDATE'], 6, 2) . "/" .  substr($get_po_data['ETDDATE'], 0, 4);
            $cargoreadinessdate = substr($get_po_data['CARGOREADINESSDATE'], 4, 2) . "/" . substr($get_po_data['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($get_po_data['CARGOREADINESSDATE'], 0, 4);
            $sender = $this->AdministrationModel->get_mailsender();
            $groupuser = 4;

            //Untuk Update Status Posting PO
            $data3 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'POSTINGSTAT' => 1,
                'OFFLINESTAT' => 0,
            );
            //inisiasi proses kirim ke group
            $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
            $mail_tmpl = $this->NotifModel->get_template($groupuser);
            foreach ($notiftouser_data as $sendto_user) :
                $var_email = array(
                    'TONAME' => $sendto_user['NAME'],
                    'FROMNAME' => $this->audtuser['NAMELGN'],
                    'CONTRACT' => $get_po_data['CONTRACT'],
                    'CTDESC' => $get_po_data['CTDESC'],
                    'PROJECT' => $get_po_data['PROJECT'],
                    'PRJDESC' => $get_po_data['PRJDESC'],
                    'CUSTOMER' => $get_po_data['CUSTOMER'],
                    'NAMECUST' => $get_po_data['NAMECUST'],
                    'PONUMBERCUST' => $get_po_data['PONUMBERCUST'],
                    'PODATECUST' => $crmpodate,
                    'CRMNO' => $get_po_data['CRMNO'],
                    'REQDATE' => $crmreqdate,
                    'ORDERDESC' => $get_po_data['ORDERDESC'],
                    'REMARKS' => $get_po_data['CRMREMARKS'],
                    'SALESCODE' => $get_po_data['MANAGER'],
                    'SALESPERSON' => $get_po_data['SALESNAME'],
                    'RQNDATE' => $rqndate,
                    'RQNNUMBER' => $get_po_data['RQNNUMBER'],
                    //DATA VARIABLE PO
                    'PODATE' => $povendordate,
                    'PONUMBER' => $get_po_data['PONUMBER'],
                    'ETDDATE' => $etddate,
                    'CARGOREADINESSDATE' => $cargoreadinessdate,
                    'ORIGINCOUNTRY' => $get_po_data['ORIGINCOUNTRY'],
                    'POREMARKS' => $get_po_data['POREMARKS'],
                );
                $subject = $mail_tmpl['SUBJECT_MAIL'];
                $message = view(trim($mail_tmpl['PATH_TEMPLATE']), $var_email);

                $data_email = array(
                    'hostname'       => $sender['HOSTNAME'],
                    'sendername'       => $sender['SENDERNAME'],
                    'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                    'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                    'ssl'       => $sender['SSL'],
                    'smtpport'       => $sender['SMTPPORT'],
                    'to_email' => $sendto_user['EMAIL'],
                    'subject' =>  $subject,
                    'message' => $message,
                );


                $data_notif = array(
                    'MAILKEY' => $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . trim($sendto_user['USERNAME']),
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
                    'UNIQPROCESS' => $get_po_data['POUNIQ'],
                );

                //Check Duplicate Entry & Sending Mail
                $touser = trim($sendto_user['USERNAME']);
                $getmailuniq = $this->NotifModel->get_mail_key($groupuser, $get_po_data['POUNIQ'], $touser);
                if (!empty($getmailuniq['MAILKEY']) and $getmailuniq['MAILKEY'] == $groupuser . '-' . $get_po_data['POUNIQ'] . '-' . $touser) {
                    session()->set('success', '-1');
                    return redirect()->to(base_url('/purchaseorder'));
                    session()->remove('success');
                } else if (empty($getmailuniq['MAILKEY'])) {
                    $post_email = $this->NotifModel->mailbox_insert($data_notif);
                    if ($post_email) {
                        $sending_mail = $this->send($data_email);
                    }
                }

            endforeach;

            $this->PurchaseorderModel->po_post_update($get_po_data['POUNIQ'], $data3);
            session()->set('success', '9');
            return redirect()->to(base_url('/purchaseorder'));
            session()->remove('success');
        } else {
            session()->set('success', '-9');
            return redirect()->to(base_url('/purchaseorder'));
            session()->remove('success');
        }
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
            return redirect()->to(base_url('/purchaseorder'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/purchaseorder'));
        }
    }
}
