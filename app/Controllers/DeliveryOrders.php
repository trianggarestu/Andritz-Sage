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
        $rcpdate = substr($getshidata['RECPDATE'], 4, 2) . '/' . substr($getshidata['RECPDATE'], 6, 2) . '/' . substr($getshidata['RECPDATE'], 0, 4);

        if ($getshidata['SHIUNIQ'] == NULL) {
            $button_text = 'Save';
            $act = 'delivery/insert_action';


            $data = array(
                'csr_uniq' => $getshidata['CSRUNIQ'],
                'ct_no' => $getshidata['CONTRACT'],
                'ct_desc' => $getshidata['CTDESC'],
                'prj_no' => $getshidata['PROJECT'],
                'ct_custno' => $getshidata['CUSTOMER'],
                'ct_custname' => $getshidata['NAMECUST'],
                'crm_no' => $getshidata['CRMNO'],
                'req_date' => $reqdate,
                'csr_item_no' => $getshidata['ITEMNO'],
                'csr_material_no' => $getshidata['MATERIALNO'],
                'csr_item_desc' => $getshidata['ITEMDESC'],
                'csr_srvtype' => $getshidata['SERVICETYPE'],
                'csr_qty' => $getshidata['QTY'],
                'csr_uom' => $getshidata['STOCKUNIT'],
                'rcp_number' => $getshidata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'rcp_desc' => $getshidata['DESCRIPTIO'],
                'item_no' => $getshidata['RECPITEMNO'],
                'qty_rcp' => $getshidata['RECPQTY'],
                'rcp_unit' => $getshidata['RECPUNIT'],
                'gr_status' => $getshidata['GRSTATUS'],
                'button_text' => $button_text,
                //'grsage_data' => $this->GoodreceiptModel->get_receipt(),
                'form_action' => base_url($act),
            );
        } else {

            $shidate = substr($getshidata['SHIDATE'], 4, 2) . '/' . substr($getshidata['SHIDATE'], 6, 2) . '/' . substr($getshidata['SHIDATE'], 0, 4);
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
                'csr_item_no' => $getshidata['ITEMNO'],
                'csr_material_no' => $getshidata['MATERIALNO'],
                'csr_item_desc' => $getshidata['ITEMDESC'],
                'csr_srvtype' => $getshidata['SERVICETYPE'],
                'csr_qty' => $getshidata['QTY'],
                'csr_uom' => $getshidata['STOCKUNIT'],
                'rcp_number' => $getshidata['RECPNUMBER'],
                'rcp_date' => $rcpdate,
                'rcp_desc' => $getshidata['DESCRIPTIO'],
                'item_no' => $getshidata['RECPITEMNO'],
                'qty_rcp' => $getshidata['RECPQTY'],
                'rcp_unit' => $getshidata['RECPUNIT'],
                'gr_status' => $getshidata['GRSTATUS'],
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
