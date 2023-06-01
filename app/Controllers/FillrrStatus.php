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
use App\Models\Finance_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class FillrrStatus extends BaseController
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
        $this->FinanceModel = new Finance_model();
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
                $activenavd = 'fillrrstatus';
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
        $fin_data = $this->FinanceModel->get_fin_pending_to_rrstatus();

        $data = array(
            'finance_data' => $fin_data,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('finance/data_fin_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($finuniq, $postingstat)
    {

        $get_fin = $this->FinanceModel->get_fin_by_id($finuniq);
        if ($get_fin['RRPOSTINGSTAT'] == 1) {
            session()->set('success', '-1');
            return redirect()->to(base_url('fillrrstatus'));
        } else {
            //if (!empty($get_shi['EDNFILENAME']) and empty($get_shi['DNSTATUS'])) {
            $act = 'fillrrstatus/update_action';
            if ($postingstat == 0) {
                $button = 'Save';
            } else {
                $button = 'Posting';
            }

            $finuniq = $get_fin['FINUNIQ'];
            $data = array(
                'csruniq' => trim($get_fin['CSRUNIQ']),
                'finuniq' => trim($get_fin['FINUNIQ']),
                'idinvc' => trim($get_fin['IDINVC']),
                'invdate' => trim($get_fin['INVOICEDATE']),
                'finstatus' => trim($get_fin['FINSTATUS']),
                'rrstatus' => trim($get_fin['RRSTATUS']),
                'form_action' => base_url($act),
                'post_stat' => $postingstat,
                'button' => $button,
            );
            //}
            echo view('finance/ajax_fill_rrstatus', $data);
        }
    }


    public function update_action()
    {

        if (null == ($this->request->getPost('finuniq'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('fillrrstatus'));
        } else {
            //$sender = $this->AdministrationModel->get_mailsender();
            $finuniq = $this->request->getPost('finuniq');
            $csruniq = $this->request->getPost('csruniq');
            $post_stat = $this->request->getPost('post_stat');
            $groupuser = 9;
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'RRSTATUS' => $this->request->getPost('rrstatus'),
                'RRPOSTINGSTAT' => $post_stat,
            );
            $this->FinanceModel->finance_update($finuniq, $data1);

            if ($post_stat == 1) {

                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'RRSTATUS' => $this->request->getPost('rrstatus'),
                );

                $this->FinanceModel->ot_finance_update($csruniq, $data2);
            }
            session()->set('success', '1');
            return redirect()->to(base_url('/fillrrstatus'));
            session()->remove('success');
        }
    }


    public function sendnotif($shiuniq)
    {
        //check dari sini
        $sender = $this->AdministrationModel->get_mailsender();
        $choose_shi = $this->DeliveryordersModel->get_dn_by_id($shiuniq);
        $shidate = substr($choose_shi["SHIDATE"], 6, 2) . "/" . substr($choose_shi["SHIDATE"], 4, 2) . "/" . substr($choose_shi["SHIDATE"], 0, 4);
        $groupuser = 8;

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
                'subject' => 'Pending Requisition Allert. DN Number : ' . $choose_shi["SHINUMBER"],
                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                    Please to follow up DN Origin :' . $choose_shi["SHINUMBER"] . '(' . $shidate . ') is pending for you to process Finance.
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
                    'SUBJECT' => 'Pending Requisition Allert. DN Number : ' . $choose_shi["SHINUMBER"],
                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
                                    Please to follow up DN Origin :' . $choose_rqn["SHINUMBER"] . '(' . $shidate . ') is pending for you to process Finance.
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
                    'UNIQPROCESS' => $choose_shi['CSRUNIQ'],
                );

                $this->NotifModel->mailbox_insert($data_notif);
                $this->DeliveryordersModel->deliveryorders_update($shiuniq, $data2);
            }
        }


        session()->set('success', '1');
        return redirect()->to(base_url('/confirmdnorigin'));
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
