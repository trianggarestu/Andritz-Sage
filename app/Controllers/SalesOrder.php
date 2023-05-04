<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Salesorder_model;

//use App\Controllers\AdminController;

class SalesOrder extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $audtuser;
    private $db_name;
    public function __construct()
    {
        //parent::__construct();
        helper(['form', 'url']);
        $this->db_name = \Config\Database::connect();

        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->SalesorderModel = new Salesorder_model();

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
            $activenavd = 'salesorder';
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
        $data = array(
            'ct_no' => '',
            'ct_desc' => '',
            'ct_manager' => '',
            'ct_salesperson' => '',
            'ct_custno' => '',
            'ct_custname' => '',
            'ct_email' => '',
            'prj_no' => '',
            'prj_desc' => '',
            'po_cust' => '',
            'prj_startdate' => '',
            'inventory_no' => '',
            'item_data' => $this->SalesorderModel->get_icitem(),
            'form_action' => '',
            'validation' => \Config\Services::validation(),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function selectcontract($ct_no = '')
    {

        if ($ct_no == '') {
            $data = array(
                'ct_no' => '',
                'ct_desc' => '',
                'ct_manager' => '',
                'ct_salesperson' => '',
                'ct_custno' => '',
                'ct_custname' => '',
                'ct_email' => '',
                'prj_no' => '',
                'prj_desc' => '',
                'po_cust' => '',
                'prj_startdate' => '',
                'inventory_no' => '',
                'item_data' => $this->SalesorderModel->get_icitem(),
                'form_action' => base_url("salesorder/save_salesorder"),
                //'validation' => \Config\Services::validation(),

            );
        } else {
            $row = $this->SalesorderModel->get_contract_by_id($ct_no);
            if ($row) {
                $data = array(
                    'ct_no' => trim($row['CONTRACT']),
                    'ct_desc' => trim($row['DESC']),
                    'ct_manager' => trim($row['MANAGER']),
                    'ct_salesperson' => trim($row['NAME']),
                    'ct_custno' => trim($row['CUSTOMER']),
                    'ct_custname' => trim($row['NAMECUST']),
                    'ct_email' => trim($row['EMAIL1']),
                    'prj_no' => '',
                    'prj_desc' => '',
                    'po_cust' => '',
                    'prj_startdate' => '',
                    'inventory_no' => '',
                    'item_data' => $this->SalesorderModel->get_icitem(),
                    'form_action' => base_url("salesorder/save_salesorder"),
                    //'validation' => \Config\Services::validation(),

                );
            }
        }
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function selectproject($ct_no = '', $prj_no = '')
    {

        if ($prj_no == '') {
            $data = array(
                'ct_no' => '',
                'ct_desc' => '',
                'ct_manager' => '',
                'ct_salesperson' => '',
                'ct_custno' => '',
                'ct_custname' => '',
                'ct_email' => '',
                'prj_no' => '',
                'prj_desc' => '',
                'po_cust' => '',
                'prj_startdate' => '',
                'inventory_no' => '',
                'item_data' => $this->SalesorderModel->get_icitem(),
                'form_action' => base_url("salesorder/save_salesorder"),

            );
        } else {
            $row = $this->SalesorderModel->get_project_by_contract($ct_no, $prj_no);
            if ($row) {
                $podate = $row['PODATE'];
                $dd = substr($podate, 6, 2);
                $mm = substr($podate, 4, 2);
                $yyyy = substr($podate, 0, 4);
                $n_podate = $dd . '-' . $mm . '-' . $yyyy;
                $data = array(
                    'ct_no' => trim($row['CONTRACT']),
                    'ct_desc' => trim($row['DESC']),
                    'ct_manager' => trim($row['MANAGER']),
                    'ct_salesperson' => trim($row['NAME']),
                    'ct_custno' => trim($row['CUSTOMER']),
                    'ct_custname' => trim($row['NAMECUST']),
                    'ct_email' => trim($row['EMAIL1']),
                    'prj_no' => trim($row['PROJECT']),
                    'prj_desc' => trim($row['Prj_Desc']),
                    'po_cust' => trim($row['PONUMBER']),
                    'prj_startdate' => $n_podate,
                    'inventory_no' => '',
                    'item_data' => $this->SalesorderModel->get_icitem(),
                    'form_action' => base_url("salesorder/save_salesorder"),
                    //'validation' => \Config\Services::validation(),

                );
            }
        }
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function form_select_contractopen()
    {


        $data['contractopen'] = $this->SalesorderModel->list_contract_open();
        $data['form_action'] = base_url("salesorder/choosecontract");
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_add_contract', $data);
    }



    public function form_select_project_by_contract($contract = '')
    {


        $data['projectbycontract'] = $this->SalesorderModel->list_project_by_contract($contract);
        $data['form_action'] = base_url("salesorder/chooseproject");
        $data['ct_no'] = $contract;
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_add_project', $data);
    }

    public function choosecontract()
    {
        if (null == ($this->request->getPost('contract'))) {
            $ct_no = "contract not found";
        } else {
            $ct_no = $this->request->getPost('contract');
        }

        return redirect()->to(base_url('salesorder/selectcontract/' . $ct_no));
    }

    public function chooseproject()
    {
        if (null == ($this->request->getPost('contract')) && ($this->request->getPost('contract')) == null) {
            $ct_no = "contract not found";
            $prj_no = "";
        } else if (($this->request->getPost('contract')) <> "" && ($this->request->getPost('contract')) == null) {
            $ct_no = $this->request->getPost('contract');
            $prj_no = "";
        } else if (($this->request->getPost('contract')) <> "" && ($this->request->getPost('contract')) <> "") {
            $ct_no = $this->request->getPost('contract');
            $prj_no = $this->request->getPost('project');
        }

        return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/'));
    }

    public function save_salesorder()
    {
        if (!$this->validate([
            'ct_no' => 'required|min_length[3]',
            'ct_desc' => 'required',
            'ct_salesperson' => 'required',
            'ct_custno' => 'required',
            'ct_email' => 'required|valid_email',
            'ct_namecust' => 'required',
            'prj_no' => 'required|min_length[3]',
            'prj_desc' => 'required|alpha_numeric_space',
            'po_cust' => 'required',
            'prj_startdate' => 'required',
            'crm_no' => 'required|min_length[1]',
            'req_date' => 'required',
            'ord_desc' => 'required',
            'so_service' => 'required',
            'so_remarks' => 'required|min_length[3]',
            'inventory_no' => 'required',
            'material_no' => 'required',
            'so_qty' => 'required|numeric|greater_than[0]',
            'so_uom' => 'required',

        ])) {
            $ct_no = $this->request->getPost('ct_no');
            $prj_no = $this->request->getPost('prj_no');
            if (($ct_no == "")) {
                session()->setFlashdata('messagefailed', 'Input Failed, Select Contract!..');
                return redirect()->to(base_url('/salesorder'))->withInput();
            } else if (($ct_no <> "") and $prj_no == "") {
                session()->setFlashdata('messagefailed', 'Input Failed, Select Project!..');
                return redirect()->to(base_url('/salesorder/selectcontract/' . $ct_no))->withInput();
            } else if (($ct_no <> "") and $prj_no <> "") {
                session()->setFlashdata('messagefailed', 'Input Failed, Complete the data before save!..');
                return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no))->withInput();
            }

            //echo $prj_no;
            //echo $this->validate;

        } else {
            // Check Status Mail Notification
            $sender = $this->AdministrationModel->get_mailsender();
            $prj_startdate = $this->request->getPost('prj_startdate');
            $req_date = $this->request->getPost('req_date');
            $podatecust = substr($prj_startdate, 6, 4) . "" . substr($prj_startdate, 3, 2) . "" . substr($prj_startdate, 0, 2);
            $crmreqdate = substr($req_date, 6, 4) . "" . substr($req_date, 3, 2) . "" . substr($req_date, 0, 2);
            $groupuser = 2;
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'CONTRACT' => $this->request->getPost('ct_no'),
                'CTDESC' => $this->request->getPost('ct_desc'),
                'MANAGER' => $this->request->getPost('ct_manager'),
                'SALESNAME' => $this->request->getPost('ct_salesperson'),
                'PROJECT' => $this->request->getPost('prj_no'),
                'PRJDESC' => $this->request->getPost('prj_desc'),
                'PONUMBERCUST' => $this->request->getPost('po_cust'),
                'PODATECUST' => $podatecust,
                'CUSTOMER' => $this->request->getPost('ct_custno'),
                'NAMECUST' => $this->request->getPost('ct_namecust'),
                'EMAIL1CUST' => $this->request->getPost('ct_email'),
                'CRMNO' => $this->request->getPost('crm_no'),
                'CRMREQDATE' => $crmreqdate,
                'ORDERDESC' => $this->request->getPost('ord_desc'),
                'SERVICETYPE' => $this->request->getPost('so_service'),
                'CRMREMARKS' => $this->request->getPost('so_remarks'),
                'ITEMNO' => $this->request->getPost('inventory_no'),
                'MATERIALNO' => $this->request->getPost('material_no'),
                'STOCKUNIT' => $this->request->getPost('so_uom'),
                'QTY' => $this->request->getPost('so_qty'),
                'OTPROCESS' => $groupuser,
                'POSTINGSTAT' => 0,
                'OFFLINESTAT' => $sender['OFFLINESTAT'],
            );

            //print_r($data_notif);
            $so_insert = $this->SalesorderModel->csr_insert($data);

            if ($sender['OFFLINESTAT'] == 0) {
                //print_r($_POST);
                if ($so_insert) {
                    //inisiasi proses kirim ke group

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
                            'subject' => 'Pending Sales Order Allert. Contract No : ' . $data['ContractNo'],
                            'message' =>    'Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
                
                Please to follow up CRM No :' . $this->request->getPost('crm_no') . ' / Customer PO : ' . $this->request->getPost('po_cust') . ' from ' . $this->request->getPost('ct_namecust') . ' is pending for you to request PR/PO.
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
                                'contract' =>  $data['ContractNo'],
                                'from_user' => $this->header_data['usernamelgn'],
                                'from_email' => $this->header_data['emaillgn'],
                                'from_name' => ucwords(strtolower($this->header_data['namalgn'])),
                                'subject' => 'Pending Sales Order Allert. Contract No : ' . $data['ContractNo'],
                                'message' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
                    
                        Please to follow up CRM No :' . $this->request->getPost('crm_no') . ' / Customer PO : ' . $this->request->getPost('po_cust') . ' from ' . $this->request->getPost('ct_namecust') . ' is pending for you to request PR/PO.
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
                            $this->SalesorderModel->mailbox_insert($data_notif);
                        }
                    }
                    //return redirect()->to(base_url('/salesorder'));
                    //}
                }

                session()->setFlashdata('messagesuccess', 'Create Record Success');
                return redirect()->to(base_url('/salesorderlist'));
            } else {
                session()->setFlashdata('messageerror', 'Create Record Failed');
                return redirect()->to(base_url('/salesorderlist'));
            }
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
        $subject            = $data_email['subject'];
        $message            = $data_email['message'];

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
            return redirect()->to(base_url('/salesorder'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/salesorder'));
        }
    }
}
