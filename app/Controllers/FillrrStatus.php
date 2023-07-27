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
        $fin_data = $this->FinanceModel->get_fin_pending_to_rrstatus();

        $data = array(
            'finance_data' => $fin_data,
            'keyword' => '',
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('finance/data_fin_pending_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('fillrrstatus'));
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
        return redirect()->to(base_url('fillrrstatus/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $fin_data = $this->FinanceModel->get_fin_pending_to_rrstatus();
        } else {
            $fin_data = $this->FinanceModel->get_fin_pending_to_rrstatus_search($keyword);
        }
        $data = array(
            'finance_data' => $fin_data,
            'keyword' => $keyword,
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
            $inv_date = substr($get_fin['DATEINVC'], 4, 2) . "/" . substr($get_fin['DATEINVC'], 6, 2) . "/" . substr($get_fin['DATEINVC'], 0, 4);
            $data = array(
                'csruniq' => trim($get_fin['CSRUNIQ']),
                'finuniq' => trim($get_fin['FINUNIQ']),
                'idinvc' => trim($get_fin['IDINVC']),
                'invdate' => $inv_date,
                'finstatus' => trim($get_fin['FINSTATUS']),
                'tcurcosthm' => $get_fin['TCURCOSTHM'],
                'tactcosthm' => $get_fin['TACTCOSTHM'],
                'vtcurcosthm' => number_format($get_fin['TCURCOSTHM'], 0, ",", "."),
                'vtactcosthm' => number_format($get_fin['TACTCOSTHM'], 0, ",", "."),
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
                'TCURCOSTHM' => $this->request->getPost('tcurcosthm'),
                'TACTCOSTHM' => $this->request->getPost('tactcosthm'),
                'RRPOSTINGSTAT' => $post_stat,
            );
            $this->FinanceModel->finance_update($finuniq, $data1);

            if ($post_stat == 1) {

                $data = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'POSTINGSTAT' => 1,
                    'OFFLINESTAT' => 1,
                );

                $fin_to_ot = $this->FinanceModel->get_fin_open_by_id($finuniq, $csruniq);
                foreach ($fin_to_ot as $ot_fin) :
                    $csruniq = $ot_fin['CSRUNIQ'];
                    $csrluniq = $ot_fin['CSRLUNIQ'];
                    $data2 = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'RRSTATUS' => $this->request->getPost('rrstatus'),
                    );

                    $this->FinanceModel->ot_finance_update($csruniq, $csrluniq, $data2);

                endforeach;
                $fin_update = $this->FinanceModel->finance_update($finuniq, $data);
            }
            session()->set('success', '1');
            return redirect()->to(base_url('/fillrrstatus'));
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
