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
        $deliverydata = $this->FinanceModel->get_shi_pending_to_finance();
        $shilist_data = $this->FinanceModel->get_shilist_on_shiopen();
        $finlist_data = $this->FinanceModel->get_finlist_on_csr();

        $data = array(
            'delivery_data' => $deliverydata,
            'finlist_data' => $finlist_data,
            'shilist_data' => $shilist_data,
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


    public function update($csruniq, $postingstat)
    {

        $get_csrfin = $this->FinanceModel->get_csr_by_id($csruniq);
        if ($get_csrfin) {
            $act = 'fillinvoice/insert_action';
            if ($postingstat == 0) {
                $button = 'Save';
            } else {
                $button = 'Save & Posting';
            }
            $ct_no = $get_csrfin['CONTRACT'];

            $data = array(
                'csruniq' => trim($get_csrfin['CSRUNIQ']),
                'customer' => trim($get_csrfin['CUSTOMER']),
                'cust_name' => trim($get_csrfin['NAMECUST']),
                'arinvoice_list' => $this->FinanceModel->list_sage_ar_by_contract($ct_no),
                'shi_by_csr_list' => $this->FinanceModel->list_shipments_by_contract($csruniq),
                'form_action' => base_url($act),
                'post_stat' => $postingstat,
                'button' => $button,
            );
            //}
            echo view('finance/ajax_fill_invoice', $data);
        }
    }


    public function insert_action()
    {
        /*if (!$this->validate([
            'csruniq' => 'required',
            'shichecked[]' => 'required|greater_than[0]',


        ])) {
            session()->set('success', '-1');
            return redirect()->to(base_url('fillinvoice'));
        } else {
            */
        $csruniq = $this->request->getPost('csruniq');

        $groupuser = 9;

        $idinvc = $this->request->getPost('idinvc');
        $dateinvc = $this->request->getPost('dateinvc');
        $invcdesc = $this->request->getPost('invcdesc');
        $dppamt = $this->request->getPost('dppamt');
        $rcporigdn_date = $this->request->getPost('rcporigdn_date');
        $post_stat = $this->request->getPost('post_stat');
        $choose_arinv = $this->FinanceModel->get_arinvoice_by_id($idinvc);
        $n_rcporigdn_date = substr($rcporigdn_date, 6, 4) . "" . substr($rcporigdn_date, 0, 2) . "" . substr($rcporigdn_date, 3, 2);

        if ($choose_arinv) {
            $data1 = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'FINKEY' => $csruniq . '-' . trim($idinvc),
                'CSRUNIQ' => $csruniq,
                'IDINVC' => $this->request->getPost('idinvc'),
                'DATEINVC' => $choose_arinv["DATEINVC"],
                'INVCDESC' => $choose_arinv["INVCDESC"],
                'AMTINVCTOT' => $choose_arinv["AMTINVCTOT"],
                'RCPORIGINALDNDATE' => $n_rcporigdn_date,
                'FINSTATUS' => $this->request->getPost('finstatus'),
                'RRSTATUS' => 0,
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => $post_stat,
                'OFFLINESTAT' => 1,
                'RRPOSTINGSTAT' => 0,

            );

            $getfinuniq = $this->FinanceModel->get_finuniq_open($csruniq, $idinvc);
            if (!empty($getfinuniq['FINKEY']) and $getfinuniq['FINKEY'] == $csruniq . '-' . $idinvc) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/fillinvoice'));
                session()->remove('success');
            } else if (empty($getfinuniq['FINKEY'])) {
                // Insert Finance Header
                $add_fin = $this->FinanceModel->finance_insert($data1);

                if ($add_fin) {
                    $getfinuniq = $this->FinanceModel->get_finuniq_open($csruniq, $idinvc);
                    $finuniq = $getfinuniq['FINUNIQ'];
                    $result = array();


                    foreach ($_POST['SHIUNIQ'] as $key => $val) {
                        if ((isset($_POST["shichecked"][$key])) && ($_POST["shichecked"][$key] == 1)) {
                            $result[] = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                                'AUDTORG' => $this->audtuser['AUDTORG'],
                                'FINUNIQ' => $finuniq,
                                'CSRUNIQ' => $csruniq,
                                'SHIUNIQ' => $_POST['SHIUNIQ'][$key],
                                'SHIDOCNUMBER' => $_POST['SHIDOCNUMBER'][$key],
                                'SHICHECKED' => $_POST['shichecked'][$key],
                            );
                        }
                    }

                    $fin_m_shi_insert = $this->FinanceModel->fin_m_line_insert($result);
                }
            }
            // Ini jika Posting
            if ($post_stat == 1) {
                $fin_to_ot = $this->FinanceModel->get_fin_open_by_id($finuniq, $csruniq);
                foreach ($fin_to_ot as $ot_fin) :
                    $csruniq = $ot_fin['CSRUNIQ'];
                    $csrluniq = $ot_fin['CSRLUNIQ'];
                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'IDINVC' => $ot_fin["IDINVC"],
                        'DATEINVC' => $ot_fin["DATEINVC"],
                        'FINSTATUS' => $ot_fin["FINSTATUS"],
                    );

                    $this->FinanceModel->ot_finance_update($csruniq, $csrluniq, $data2);

                endforeach;
            }
        }
        session()->set('success', '1');
        return redirect()->to(base_url('/fillinvoice'));
        session()->remove('success');
    }


    /*public function update_action()
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
    */





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
