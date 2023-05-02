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
use App\Models\Settingnavheader_model;
use App\Models\Notif_model;
use App\Models\Requisition_model;

//use App\Controllers\AdminController;

class RequisitionList extends BaseController
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
        $this->RequisitionModel = new Requisition_model();

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
            //}
        }
    }


    public function index()
    {
        $requisitiondata = $this->RequisitionModel->get_requisition_open();


        $data = array(
            'requisition_data' => $requisitiondata,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_pr_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($id_so)
    {
        $get_so = $this->RequisitionModel->get_so_by_id($id_so);
        if ($get_so) {
            $data = array(
                'id_so' => trim($get_so['ID_SO']),
                'ct_no' => trim($get_so['ContractNo']),
                'prj_no' => trim($get_so['ProjectNo']),
                'crm_no' => trim($get_so['CrmNo']),
                'cust_no' => trim($get_so['CustomerNo']),
                'cust_name' => trim($get_so['CustomerName']),
                'cust_email' => trim($get_so['CustomerEmail']),
                'cust_po' => trim($get_so['PoCustomer']),
                'po_date' => trim($get_so['PoDate']),
                'req_date' => trim($get_so['ReqDate']),
                'salesperson' => trim($get_so['SalesPerson']),
                'inventory_no' => trim($get_so['InventoryNo']),
                'material_no' => trim($get_so['MaterialNo']),
                'inventory_desc' => trim($get_so['InventoryDesc']),
                'order_desc' => trim($get_so['OrderDesc']),
                'qty' => trim($get_so['Qty']),
                'uom' => trim($get_so['Uom']),
                'requisition_list' => $this->RequisitionModel->get_requisition_sage(),
                'form_action' => base_url("requisition/update_action"),
            );
        }


        echo view('requisition/ajax_add_requisition', $data);
    }

    public function update_action()
    {
        if (null == ($this->request->getPost('id_so'))) {
            session()->setFlashdata('messagefailed', 'Data not found.');
            return redirect()->to(base_url('requisition'));
        } else {
            $id = $this->request->getPost('id_so');
            $rqnnumber = $this->request->getPost('rqnnumber');
            $choose_rqn = $this->RequisitionModel->get_requisition_by_id($rqnnumber);
            if ($choose_rqn) {
                $data = array(
                    'PrNumber' => $choose_rqn["RQNNUMBER"],
                    'PrDate' => $choose_rqn["DATE"],
                );

                $this->RequisitionModel->requisition_update($id, $data);
                session()->setFlashdata('messagesuccess', 'Update Record Success');
                return redirect()->to(base_url('requisition'));
            }
        }
    }

    public function sending_notif($id_so)
    {
        //inisiasi proses kirim ke group
        $groupuser = 3;
        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);
        $get_so = $this->RequisitionModel->get_so_by_id($id_so);
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
                'subject' => 'Pending Requisition Allert. Requisition No : ' . $get_so['PrNumber'],
                'message' =>    'Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

Please to follow up Requisition Number :' . $get_so['PrNumber'] . ' / Contract : ' . $get_so['ContractNo'] . ' is pending for you to process Purchase Order Vendor.
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
                    'subject' => 'Pending Requisition Allert. Requisition No : ' . $get_so['PrNumber'],
                    'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                    Please to follow up Requisition Number :' . $get_so['PrNumber'] . ' / Contract : ' . $get_so['ContractNo'] . ' is pending for you to process Purchase Order Vendor.
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
