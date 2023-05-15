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
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->db_name = \Config\Database::connect();

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
                    'AUDTUSER' => $infouser['usernamelgn'],
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
        $purchaseorderdata = $this->PurchaseorderModel->get_requisition_pending();


        $data = array(
            'purchaseOrder_data' => $purchaseorderdata,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('purchaseorder/data_pr_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($rqnuniq, $postingstat)
    {
        $get_pr = $this->PurchaseorderModel->get_requisition_by_id($rqnuniq);
        $get_po = $this->PurchaseorderModel->get_po_by_requisition($rqnuniq);

        if ($get_pr) {
            if (!empty($get_po['RQNUNIQ']) and $get_po['POSTINGSTAT'] == 0) {
                $act = 'purchaseorder/update_action';
                $id_po = $get_pr['POUNIQ'];
                $rqnnumber = $get_pr['RQNNUMBER'];
                $povendordate = substr($get_po['PODATE'], 6, 2) . "/" . substr($get_po['PODATE'], 4, 2) . "/" . substr($get_po['PODATE'], 0, 4);
                $etddate = substr($get_po['ETDDATE'], 6, 2) . "/" . substr($get_po['ETDDATE'], 4, 2) . "/" . substr($get_po['ETDDATE'], 0, 4);
            } else {
                $act = 'purchaseorder/insert_action';
                $id_po = '';
                $rqnnumber = $get_pr['RQNNUMBER'];
                $povendordate = '';
                $etddate = '';
            }

            $data = array(
                'rqnuniq' => trim($get_pr['RQNUNIQ']),
                'ct_no' => trim($get_pr['CONTRACT']),
                'prj_no' => trim($get_pr['PROJECT']),
                'cust_no' => trim($get_pr['CUSTOMER']),
                'rqn_date' => trim($get_pr['RQNDATE']),
                'po_number' => '',
                'etd_date' => $etddate,
                'origin_country' => '',
                'po_remarks' => '',
                'posage_list' => $this->PurchaseorderModel->get_po_list__sage_by_rqn($rqnnumber),
                'form_action' => base_url($act),
                'post_stat' => $postingstat,
                'pouniq' => $id_po,
            );
        }
        echo view('purchaseorder/ajax_add_purchaseorder', $data);
    }



    public function sending_notif($id_so)
    {
        //inisiasi proses kirim ke group
        $groupuser = 3;
        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        $get_so = $this->PurchaseOrderModel->get_so_by_id($id_so);
        $today = date("d/m/Y");
        $audtdate = substr($today, 6, 4) . "" . substr($today, 3, 2) . "" . substr($today, 0, 2);
        foreach ($notiftouser_data as $sendto_user) {

            $data_email = array(
                'hostname' => $sendto_user['HOSTNAME'],
                'sendername' => $sendto_user['SENDERNAME'],
                'senderemail' => $sendto_user['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail' => $sendto_user['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl' => $sendto_user['SSL'],
                'smtpport' => $sendto_user['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' => 'Pending PurchaseOrder Allert. PurchaseOrder No : ' . $get_so['PrNumber'],
                'message' =>    'Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

Please to follow up PurchaseOrder Number :' . $get_so['PrNumber'] . ' / Contract : ' . $get_so['ContractNo'] . ' is pending for you to process Purchase Order Vendor.
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
                    'contract' =>  $get_so['ContractNo'],
                    'from_user' => $this->header_data['usernamelgn'],
                    'from_email' => $this->header_data['emaillgn'],
                    'from_name' => ucwords(strtolower($this->header_data['namalgn'])),
                    'subject' => 'Pending PurchaseOrder Allert. PurchaseOrder No : ' . $get_so['PrNumber'],
                    'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                    Please to follow up PurchaseOrder Number :' . $get_so['PrNumber'] . ' / Contract : ' . $get_so['ContractNo'] . ' is pending for you to process Purchase Order Vendor.
        <br><br>
        You can access Order Tracking System Portal via the URL below:
        <br>
        Http://jktsms025:...
        <br>
        Thanks for your cooperation. 
        <br><br>
        Order Tracking Administrator',

                    'sending_date' => $audtdate,
                    'is_read' => 0,
                    'updated_at' => $audtdate,
                    'is_archived' => 0,
                    'to_user' => $sendto_user['USERNAME'],
                    'to_email' => $sendto_user['EMAIL'],
                    'to_name' => ucwords(strtolower($sendto_user['NAME'])),
                    'is_trashed' => 0,
                    'is_deleted' => 0,
                    'is_attached' => 0,
                    'is_star' => 0,
                    'sending_status' => 1,
                );
                $this->NotifModel->mailbox_insert($data_notif);
            } else {
                return redirect()->to(base_url('/PurchaseOrder'));
            }
        }

        session()->setFlashdata('messagesuccess', 'Create Record Success');
        return redirect()->to(base_url('/PurchaseOrder'));
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
