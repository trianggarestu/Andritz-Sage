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
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class RequisitionList extends BaseController
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
                $activenavd = 'requisitionlist';
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
        session()->remove('success');
        session()->set('success', '0');
        $requisition_data = $this->RequisitionModel->select('webot_REQUISITION.*,b.CTDESC,b.PRJDESC,b.PONUMBERCUST,b.PODATECUST,b.NAMECUST,
        b.CRMNO,b.CRMREQDATE,b.ITEMNO,b.MATERIALNO,b.SERVICETYPE,b.CRMREMARKS,b.MANAGER,b.SALESNAME,b.STOCKUNIT,b.QTY,b.ORDERDESC,
        c.PONUMBER,c.PODATE')
            ->join('webot_CSR b', 'b.CSRUNIQ = webot_REQUISITION.CSRUNIQ', 'left')
            ->join('webot_PO c', 'c.RQNUNIQ = webot_REQUISITION.RQNUNIQ', 'left')
            ->where('webot_REQUISITION.POSTINGSTAT', 1)
            ->orderBy('webot_REQUISITION.RQNUNIQ', 'ASC');

        $perpage = 20;
        $data = array(
            'requisition_data' => $requisition_data->paginate($perpage, 'rqn_posting_list'),
            'pager' => $requisition_data->pager,
            'ct_po_posting' => $this->RequisitionModel->count_rqn_posting(),
            'perpage' => $perpage,
            'currentpage' => $requisition_data->pager->getCurrentPage('rqn_posting_list'),
            'totalpages'  => $requisition_data->pager->getPageCount('rqn_posting_list'),
        );

        /*$requisitiondata = $this->RequisitionModel->get_requisition_close();


        $data = array(
            'requisition_data' => $requisitiondata,
        );*/

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
        $requisitiondata = $this->RequisitionModel->get_requisition_close();
        $spreadsheet = new Spreadsheet();

        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'RQNNUMBER')
            ->setCellValue('C1', 'RQNDATE')
            ->setCellValue('D1', 'CUSTOMER NAME')
            ->setCellValue('E1', 'CONRACT NO')
            ->setCellValue('F1', 'CONTRACT DESC')
            ->setCellValue('G1', 'PROJECT NO')
            ->setCellValue('H1', 'CRM NO')
            ->setCellValue('I1', 'CRM DESC')
            ->setCellValue('J1', 'CRM DATE')
            ->setCellValue('K1', 'ITEM NO')
            ->setCellValue('L1', 'QTY')
            ->setCellValue('M1', 'UOM')
            ->setCellValue('N1', 'STATUS')



            ->setCellValue('S1', '');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($requisitiondata as $data) {
            $postingstat =  $data['POSTINGSTAT'];
            switch ($postingstat) {
                case "0":
                    $postingstatus = "Open";
                    break;
                case "1":
                    $postingstatus = "Posted";
                    break;
                case "2":
                    $postingstatus = "Deleted";
                    break;
                default:
                    $postingstatus = "Open";
            }
        }
        $dd = substr($data['RQNDATE'], 6, 2);
        $mm = substr($data['RQNDATE'], 4, 2);
        $yyyy = substr($data['RQNDATE'], 0, 4);
        $rqndate = $mm . '/' . $dd . '/' . $yyyy;

        $dd = substr($data['CRMREQDATE'], 6, 2);
        $mm = substr($data['CRMREQDATE'], 4, 2);
        $yyyy = substr($data['CRMREQDATE'], 0, 4);
        $reqdate = $mm . '/' . $dd . '/' . $yyyy;
        foreach ($requisitiondata as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['RQNNUMBER'])
                ->setCellValue('C' . $rows, trim($rqndate))
                ->setCellValue('D' . $rows, $data['NAMECUST'])
                ->setCellValue('E' . $rows, $data['CONTRACT'])
                ->setCellValue('F' . $rows, $data['CTDESC'])
                ->setCellValue('G' . $rows, $data['PROJECT'])
                ->setCellValue('H' . $rows, $data['CRMNO'])
                ->setCellValue('I' . $rows, trim($reqdate))
                ->setCellValue('J' . $rows, $data['ORDERDESC'])
                ->setCellValue('K' . $rows, $data['MATERIALNO'])
                ->setCellValue('L' . $rows, $data['QTY'])
                ->setCellValue('M' . $rows, $data['STOCKUNIT'])
                ->setCellValue('N' . $rows, $postingstatus)

                ->setCellValue('S' . $rows, '');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'PR_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    public function preview()
    {
        $pr_data = $this->RequisitionModel->get_requisition_close();
        $data = array(
            'pr_data' => $pr_data,
            'success_code' => session()->get('success'),
        );

        echo view('requisition/data_pr_list_preview', $data);
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
