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

class FillInvoice extends BaseController
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
                $activenavd = 'fillinvoice';
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
        $deliverydata = $this->FinanceModel->get_shi_pending_to_finance();

        $data = array(
            'delivery_data' => $deliverydata,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('finance/data_shi_pending_fin_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('fillinvoice'));
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
        return redirect()->to(base_url('fillinvoice/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $deliverydata = $this->FinanceModel->get_shi_pending_to_finance();
        } else {
            $deliverydata = $this->FinanceModel->get_shi_pending_to_finance_search($keyword);
        }
        $data = array(
            'delivery_data' => $deliverydata,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('finance/data_shi_pending_fin_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update($shiuniq, $postingstat)
    {

        $get_arinv = $this->FinanceModel->get_shi_by_id($shiuniq);
        if ($get_arinv) {
            if ($get_arinv['FINPOSTINGSTAT'] == 1) {
                session()->set('success', '-1');
                return redirect()->to(base_url('fillinvoice'));
            } else {
                if (!empty($get_arinv['FINUNIQ'])) {
                    $act = 'fillinvoice/update_action';
                } else {
                    $act = 'fillinvoice/insert_action';
                }
                if ($postingstat == 0) {
                    $button = 'Save';
                } else {
                    $button = 'Save & Posting';
                }

                $shiuniq = $get_arinv['SHIUNIQ'];
                $ct_no = $get_arinv['CONTRACT'];
                $data = array(
                    'finuniq' => $get_arinv['FINUNIQ'],
                    'csruniq' => trim($get_arinv['CSRUNIQ']),
                    'shiuniq' => $shiuniq,
                    'docnumber' => trim($get_arinv['DOCNUMBER']),
                    'shinumber' => trim($get_arinv['SHINUMBER']),
                    'shidate' => trim($get_arinv['SHIDATE']),
                    'custrcpdate' => trim($get_arinv['CUSTRCPDATE']),
                    'customer' => trim($get_arinv['CUSTOMER']),
                    'cust_name' => trim($get_arinv['NAMECUST']),
                    'shiitemno' => trim($get_arinv['SHIITEMNO']),
                    'inventory_desc' => trim($get_arinv['SHIITEMDESC']),
                    'shiqty' => $get_arinv['SHIQTY'],
                    'shiqtyouts' => $get_arinv['SHIQTYOUTSTANDING'],
                    'uom' => trim($get_arinv['SHIUNIT']),
                    'pocuststatus' => $get_arinv['POCUSTSTATUS'],
                    'ednfilename' => $get_arinv['EDNFILENAME'],
                    'ednfilepath' => $get_arinv['EDNFILEPATH'],
                    'dnstatus' => $get_arinv['DNSTATUS'],
                    'inv_number' => $get_arinv['IDINVC'],
                    'finstatus' => $get_arinv['FINSTATUS'],
                    //'postingstatus' => $get_arinv['FINPOSTINGSTAT'],
                    'arinvoice_list' => $this->FinanceModel->list_sage_ar_by_contract($ct_no),
                    'form_action' => base_url($act),
                    'post_stat' => $postingstat,
                    'button' => $button,
                );
                //}
                echo view('finance/ajax_fill_invoice', $data);
            }
        }
    }


    public function insert_action()
    {

        $shiuniq = $this->request->getPost('shiuniq');
        $finuniq = $this->request->getPost('finuniq');
        $get_arinv = $this->FinanceModel->get_fin_by_id($finuniq);
        if (null == ($this->request->getPost('shiuniq'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('fillinvoice'));
        } else {

            //$sender = $this->AdministrationModel->get_mailsender();
            $id_so = $this->request->getPost('csruniq');
            $shiuniq = $this->request->getPost('shiuniq');
            $docnumber = $this->request->getPost('docnumber');
            $idinvc = $this->request->getPost('idinvc');
            $post_stat = $this->request->getPost('post_stat');
            $choose_arinv = $this->FinanceModel->get_arinvoice_by_id($idinvc);

            $groupuser = 9;
            if ($choose_arinv) {
                $data1 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'CSRUNIQ' => $id_so,
                    'SHIUNIQ' => $shiuniq,
                    'DOCNUMBER' => $docnumber,
                    'IDINVC' => $this->request->getPost('idinvc'),
                    'INVOICEDATE' => $choose_arinv["DATEINVC"],
                    'FINSTATUS' => $this->request->getPost('finstatus'),
                    'RRSTATUS' => 0,
                    'OTPROCESS' => $groupuser,
                    'POSTINGSTAT' => $post_stat,
                    'RRPOSTINGSTAT' => 0,
                    'OFFLINESTAT' => 1,
                );
                $this->FinanceModel->finance_insert($data1);

                if ($post_stat == 1) {

                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'IDINVC' => $choose_arinv["IDINVC"],
                        'INVOICEDATE' => $choose_arinv["DATEINVC"],
                        'FINSTATUS' => $this->request->getPost('finstatus'),
                    );

                    $this->FinanceModel->ot_finance_update($id_so, $data2);
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/fillinvoice'));
        session()->remove('success');
    }


    public function update_action()
    {

        $shiuniq = $this->request->getPost('shiuniq');
        $finuniq = $this->request->getPost('finuniq');
        $get_arinv = $this->FinanceModel->get_fin_by_id($finuniq);
        if (null == ($this->request->getPost('shiuniq'))) {
            session()->set('success', '-1');
            return redirect()->to(base_url('fillinvoice'));
        } else if ($get_arinv['POSTINGSTAT'] == 1) {
            session()->set('success', '-1');
            return redirect()->to(base_url('fillinvoice'));
        } else {

            //$sender = $this->AdministrationModel->get_mailsender();
            $id_so = $this->request->getPost('csruniq');
            $shiuniq = $this->request->getPost('shiuniq');
            $docnumber = $this->request->getPost('docnumber');
            $idinvc = $this->request->getPost('idinvc');
            $post_stat = $this->request->getPost('post_stat');
            $choose_arinv = $this->FinanceModel->get_arinvoice_by_id($idinvc);

            $groupuser = 9;
            if ($choose_arinv) {
                $data1 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'IDINVC' => $this->request->getPost('idinvc'),
                    'INVOICEDATE' => $choose_arinv["DATEINVC"],
                    'FINSTATUS' => $this->request->getPost('finstatus'),
                    'RRSTATUS' => 0,
                    'OTPROCESS' => $groupuser,
                    'POSTINGSTAT' => $post_stat,
                    'RRPOSTINGSTAT' => 0,
                    'OFFLINESTAT' => 1,
                );
                $this->FinanceModel->finance_update($finuniq, $data1);

                if ($post_stat == 1) {

                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'IDINVC' => $choose_arinv["IDINVC"],
                        'INVOICEDATE' => $choose_arinv["DATEINVC"],
                        'FINSTATUS' => $this->request->getPost('finstatus'),
                    );

                    $this->FinanceModel->ot_finance_update($id_so, $data2);
                }
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/fillinvoice'));
        session()->remove('success');
    }



    public function sendnotif($shiuniq)
    {
        //check dari sini
        $sender = $this->AdministrationModel->get_mailsender();
        $choose_shi = $this->DeliveryordersModel->get_dn_by_id($shiuniq);
        $shidate = substr($choose_shi["SHIDATE"], 6, 2) . "/" . substr($choose_shi["SHIDATE"], 4, 2) . "/" . substr($choose_shi["SHIDATE"], 0, 4);
        $groupuser = 9;

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
        $attachment_filepath = '';
        $attachment_filename = '';

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
