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
use App\Models\Requisition_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class Requisition extends BaseController
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
        $this->RequisitionModel = new Requisition_model();
        $this->OrdertrackingModel = new Ordertracking_model();

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
                'success_code' => session()->get('success'),
            ];
            $this->footer_data = [
                'usernamelgn'   => $infouser['usernamelgn'],
            ];
            // Assign the model result to the badly named Class Property
            $activenavd = 'requisition';
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
                'AUDTUSER' => $infouser['usernamelgn'],
                'AUDTORG' => $this->db_name->database,

            ];
        }
    }


    public function index()
    {
        /*$paginateData = $this->RequisitionModel->select('webot_CSR.*,b.RQNDATE,b.RQNNUMBER')
            ->join('webot_REQUISITION b', 'b.CSRUNIQ = webot_CSR.CSRUNIQ', 'left')
            ->where('webot_CSR.POSTINGSTAT', 1)
            ->where('b.RQNNUMBER is NULL')
            ->orderBy('webot_CSR.CSRUNIQ', 'DESC')
            ->paginate(2);


        $data = array(
            'requisition_data' => $paginateData,
            'pager' => $this->RequisitionModel->pager,
        );
        */
        session()->remove('success');
        session()->set('success', '0');
        $requisitiondata = $this->RequisitionModel->get_requisition_open();
        $data = array(
            'requisition_data' => $requisitiondata,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_so_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($id_so, $postingstat)
    {
        $get_so = $this->RequisitionModel->get_so_by_id($id_so);
        $get_pr = $this->RequisitionModel->get_requisition_by_so($id_so);
        if ($get_so) {
            if (!empty($get_pr['CSRUNIQ']) and $get_pr['POSTINGSTAT'] == 0) {
                $act = 'requisition/update_action';
                $id_pr = $get_pr['RQNUNIQ'];
                $rqnnumber = $get_pr['RQNNUMBER'];
            } else {
                $act = 'requisition/insert_action';
                $id_pr = '';
                $rqnnumber = '';
            }

            $data = array(
                'id_so' => trim($get_so['CSRUNIQ']),
                'ct_no' => trim($get_so['CONTRACT']),
                'prj_no' => trim($get_so['PROJECT']),
                'crm_no' => trim($get_so['CRMNO']),
                'cust_no' => trim($get_so['CUSTOMER']),
                'cust_name' => trim($get_so['NAMECUST']),
                'cust_email' => trim($get_so['EMAIL1CUST']),
                'cust_po' => trim($get_so['PONUMBERCUST']),
                'po_date' => trim($get_so['PODATECUST']),
                'req_date' => trim($get_so['CRMREQDATE']),
                'salesperson' => trim($get_so['SALESNAME']),
                'inventory_no' => trim($get_so['ITEMNO']),
                'material_no' => trim($get_so['MATERIALNO']),
                'inventory_desc' => trim($get_so['ITEMDESC']),
                'order_desc' => trim($get_so['ORDERDESC']),
                'qty' => trim($get_so['QTY']),
                'uom' => trim($get_so['STOCKUNIT']),
                'requisition_list' => $this->RequisitionModel->get_requisition_sage(),
                'form_action' => base_url($act),
                'post_stat' => $postingstat,
                'id_pr' => $id_pr,
                'rqn_number' => $rqnnumber,
            );
        }
        echo view('requisition/ajax_add_requisition', $data);
    }



    public function insert_action()
    {
        $id_so = $this->request->getPost('id_so');
        $rqnuniq = $this->request->getPost('id_pr');
        if (null == ($this->request->getPost('id_so'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('requisition'));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $rqnnumber = $this->request->getPost('rqnnumber');
            $post_stat = $this->request->getPost('post_stat');
            $get_so = $this->RequisitionModel->get_so_by_id($id_so);
            $choose_rqn = $this->RequisitionModel->get_requisition_by_id($rqnnumber);

            $groupuser = 3;
            if ($choose_rqn) {
                $data1 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'CSRUNIQ' => $get_so['CSRUNIQ'],
                    'CONTRACT' => $get_so['CONTRACT'],
                    'PROJECT' => $get_so['PROJECT'],
                    'CUSTOMER' => $get_so['CUSTOMER'],
                    'ITEMNO' => $get_so['ITEMNO'],
                    'RQNDATE' => $choose_rqn["DATE"],
                    'RQNNUMBER' => $choose_rqn["RQNNUMBER"],
                    'OTPROCESS' => $groupuser,
                    'POSTINGSTAT' => $post_stat,
                    'OFFLINESTAT' => $sender['OFFLINESTAT'],
                );
                $this->RequisitionModel->requisition_insert($data1);

                if ($post_stat == 1) {

                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'RQNNUMBER' => $choose_rqn["RQNNUMBER"],
                        'RQNDATE' => $choose_rqn["DATE"],
                    );

                    $this->RequisitionModel->ot_requisition_update($id_so, $data2);

                    if ($sender['OFFLINESTAT'] == 0) {
                        $get_rqn = $this->RequisitionModel->get_requisition_post($rqnuniq);
                        $rqndate = substr($get_rqn['RQNDATE'], 6, 2) . "/" . substr($get_rqn['RQNDATE'], 4, 2) . "/" . substr($get_rqn['RQNDATE'], 0, 4);

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
                                'subject' => 'Pending Requisition Allert. Requisition No : ' . $get_rqn['RQNNUMBER'],
                                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                    Please to follow up Requisition Number :' . $get_rqn['RQNNUMBER'] . '(' . $rqndate . ') / Contract : ' . $get_rqn['CONTRACT'] . ' is pending for you to process Purchase Order Vendor.
        <br><br>
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
                                    'SUBJECT' => 'Pending Requisition Allert. Requisition No : ' . $get_rqn['RQNNUMBER'],
                                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                                    Please to follow up Requisition Number :' . $get_rqn['RQNNUMBER'] . '(' . $rqndate . ') / Contract : ' . $get_rqn['CONTRACT'] . ' is pending for you to process Purchase Order Vendor.
                        <br><br>
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
                                    'IS_READ' => 0,
                                    'IS_ARCHIVED' => 0,
                                    'IS_TRASHED' => 0,
                                    'IS_DELETED' => 0,
                                    'IS_ATTACHED' => 0,
                                    'IS_STAR' => 0,
                                    'IS_READSENDER' => 0,
                                    'SENDING_STATUS' => 1,
                                    'OTPROCESS' => $groupuser,
                                    'UNIQPROCESS' => $get_rqn['RQNUNIQ'],
                                );

                                $this->NotifModel->mailbox_insert($data_notif);
                            }
                        }
                    }
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/requisition'));
        session()->remove('success');
    }


    public function update_action()
    {
        $id_so = $this->request->getPost('id_so');
        $rqnuniq = $this->request->getPost('id_pr');
        if (null == ($this->request->getPost('id_so'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('requisition'));
        } else {
            $sender = $this->AdministrationModel->get_mailsender();
            $rqnnumber = $this->request->getPost('rqnnumber');
            $post_stat = $this->request->getPost('post_stat');
            $get_so = $this->RequisitionModel->get_so_by_id($id_so);
            $choose_rqn = $this->RequisitionModel->get_requisition_by_id($rqnnumber);

            $groupuser = 3;
            if ($choose_rqn) {
                $data1 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'CSRUNIQ' => $get_so['CSRUNIQ'],
                    'CONTRACT' => $get_so['CONTRACT'],
                    'PROJECT' => $get_so['PROJECT'],
                    'CUSTOMER' => $get_so['CUSTOMER'],
                    'ITEMNO' => $get_so['ITEMNO'],
                    'RQNDATE' => $choose_rqn["DATE"],
                    'RQNNUMBER' => $choose_rqn["RQNNUMBER"],
                    'OTPROCESS' => $groupuser,
                    'POSTINGSTAT' => $post_stat,
                    'OFFLINESTAT' => $sender['OFFLINESTAT'],
                );
                $this->RequisitionModel->requisition_update($rqnuniq, $data1);

                if ($post_stat == 1) {

                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'RQNNUMBER' => $choose_rqn["RQNNUMBER"],
                        'RQNDATE' => $choose_rqn["DATE"],
                    );

                    $this->RequisitionModel->ot_requisition_update($id_so, $data2);

                    if ($sender['OFFLINESTAT'] == 0) {
                        $get_rqn = $this->RequisitionModel->get_requisition_post($rqnuniq);
                        $rqndate = substr($get_rqn['RQNDATE'], 6, 2) . "/" . substr($get_rqn['RQNDATE'], 4, 2) . "/" . substr($get_rqn['RQNDATE'], 0, 4);

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
                                'subject' => 'Pending Requisition Allert. Requisition No : ' . $get_rqn['RQNNUMBER'],
                                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                    Please to follow up Requisition Number :' . $get_rqn['RQNNUMBER'] . '(' . $rqndate . ') / Contract : ' . $get_rqn['CONTRACT'] . ' is pending for you to process Purchase Order Vendor.
        <br><br>
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
                                    'SUBJECT' => 'Pending Requisition Allert. Requisition No : ' . $get_rqn['RQNNUMBER'],
                                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                                    Please to follow up Requisition Number :' . $get_rqn['RQNNUMBER'] . '(' . $rqndate . ') / Contract : ' . $get_rqn['CONTRACT'] . ' is pending for you to process Purchase Order Vendor.
                        <br><br>
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
                                    'IS_READ' => 0,
                                    'IS_ARCHIVED' => 0,
                                    'IS_TRASHED' => 0,
                                    'IS_DELETED' => 0,
                                    'IS_ATTACHED' => 0,
                                    'IS_STAR' => 0,
                                    'IS_READSENDER' => 0,
                                    'SENDING_STATUS' => 1,
                                    'OTPROCESS' => $groupuser,
                                    'UNIQPROCESS' => $get_rqn['RQNUNIQ'],
                                );

                                $this->NotifModel->mailbox_insert($data_notif);
                            }
                        }
                        //return redirect()->to(base_url('/salesorder'));
                        //}
                        //}

                        session()->set('success', '1');
                        return redirect()->to(base_url('/requisition'));
                        session()->remove('success');
                    }
                    session()->set('success', '1');
                    return redirect()->to(base_url('/requisition'));
                    session()->remove('success');
                }
            }
        }
    }


    public function sendnotif($rqnuniq)
    {

        $getreq = $this->RequisitionModel->get_requisition_post($rqnuniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 3;

        //inisiasi proses kirim ke group
        $data2 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'OFFLINESTAT' => 0,
        );
        //inisiasi proses kirim ke group
        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        $rqndate = substr($getreq['RQNDATE'], 6, 2) . "/" . substr($getreq['RQNDATE'], 4, 2) . "/" . substr($getreq['RQNDATE'], 0, 4);
        foreach ($notiftouser_data as $sendto_user) {

            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' => 'Pending Requisition Allert. Requisition No : ' . $getreq['RQNNUMBER'],
                'message' =>    'Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

Please to follow up Requisition Number :' . $getreq['RQNNUMBER'] . '(' . $rqndate . ') / Contract : ' . $getreq['CONTRACT'] . ' is pending for you to process Purchase Order Vendor.
<br><br>
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
                    'SUBJECT' => 'Pending Requisition Allert. Requisition No : ' . $getreq['RQNNUMBER'],
                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                    Please to follow up Requisition Number :' . $getreq['RQNNUMBER'] . '(' . $rqndate . ') / Contract : ' . $getreq['CONTRACT'] . ' is pending for you to process Purchase Order Vendor.
        <br><br>
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
                    'IS_READ' => 0,
                    'IS_ARCHIVED' => 0,
                    'IS_TRASHED' => 0,
                    'IS_DELETED' => 0,
                    'IS_ATTACHED' => 0,
                    'IS_STAR' => 0,
                    'IS_READSENDER' => 0,
                    'SENDING_STATUS' => 1,
                    'OTPROCESS' => $groupuser,
                    'UNIQPROCESS' => $getreq['RQNUNIQ'],
                );
                $this->NotifModel->mailbox_insert($data_notif);
                $this->RequisitionModel->rqn_post_update($rqnuniq, $data2);
            } else {
                return redirect()->to(base_url('/requisition'));
            }
        }

        session()->setFlashdata('messagesuccess', 'Create Record Success');
        return redirect()->to(base_url('/requisition'));
    }


    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $requisitiondata = $this->RequisitionModel->get_requisition_open();
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
        foreach ($requisitiondata as $data) {
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
            return redirect()->to(base_url('/requisition'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/requisition'));
        }
    }
}
