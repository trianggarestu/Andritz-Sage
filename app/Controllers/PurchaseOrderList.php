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
use App\Models\PurchaseOrder_model;

//use App\Controllers\AdminController;

class PurchaseOrderList extends BaseController
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
                $activenavd = 'purchaseorderlist';
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
        $purchaseorder_data = $this->PurchaseorderModel->select('webot_PO.*,a.RQNDATE,b.CONTRACT,b.CTDESC,b.NAMECUST,b.ITEMNO,b.QTY,b.STOCKUNIT')
            ->join('webot_REQUISITION a', 'a.RQNUNIQ = webot_PO.RQNUNIQ', 'left')
            ->join('webot_CSR b', 'b.CSRUNIQ = webot_PO.CSRUNIQ and webot_PO.CSRUNIQ = b.CSRUNIQ', 'left')

            ->where('webot_PO.POSTINGSTAT=', 1)
            ->where('webot_PO.CARGOREADINESSDATE>=', 1)

            ->orderBy('POUNIQ', 'DESC');
        //$Purchaseorderdata = $this->PurchaseOrderModel->get_PurchaseOrder_Close();
        $perpage = 20;

        $data = array(
            'purchaseorder_data' => $purchaseorder_data->paginate($perpage, 'po_posting_list'),
            'pager' => $purchaseorder_data->pager,
            'ct_po_posting' => $this->PurchaseorderModel->count_po_posting(),
            'perpage' => $perpage,
            'currentpage' => $purchaseorder_data->pager->getCurrentPage('po_posting_list'),
            'totalpages'  => $purchaseorder_data->pager->getPageCount('po_posting_list'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('purchaseorder/data_po_list.php', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $PurchaseOrderListdata = $this->PurchaseorderModel->get_purchaseorder_preview();
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'PONUMBER')
            ->setCellValue('C1', 'PODATE')
            ->setCellValue('D1', 'ETD(DATE)')
            ->setCellValue('E1', 'CARGOREADINESS(DATE)')
            ->setCellValue('F1', 'ORIGINCOUNTRY')
            ->setCellValue('G1', 'REMARKS')
            ->setCellValue('H1', 'STATUSPO')
            ->setCellValue('I1', 'PRNUMBER')
            ->setCellValue('J1', 'PRDATE')
            ->setCellValue('K1', 'CONTRACTNO')
            ->setCellValue('L1', 'CONTRACTDESC')
            ->setCellValue('M1', 'CUSTOMER')
            ->setCellValue('N1', 'ITEMNO')
            ->setCellValue('O1', 'Qty')
            ->setCellValue('P1', 'Uom');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($PurchaseOrderListdata as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['PONUMBER'])
                ->setCellValue('C' . $rows, $data['PODATE'])
                ->setCellValue('D' . $rows, $data['ETDDATE'])
                ->setCellValue('E' . $rows, $data['CARGOREADINESSDATE'])
                ->setCellValue('F' . $rows, $data['ORIGINCOUNTRY'])
                ->setCellValue('G' . $rows, $data['POREMARKS'])
                ->setCellValue('H' . $rows, $data['POSTINGSTAT'])
                ->setCellValue('I' . $rows, $data['RQNNUMBER'])
                ->setCellValue('J' . $rows, $data['RQNDATE'])
                ->setCellValue('K' . $rows, $data['CONTRACT'])
                ->setCellValue('L' . $rows, $data['CTDESC'])
                ->setCellValue('M' . $rows, $data['NAMECUST'])
                ->setCellValue('N' . $rows, $data['ITEMNO'])
                ->setCellValue('O' . $rows, $data['QTY'])

                ->setCellValue('P' . $rows, $data['STOCKUNIT'])
                ->setCellValue('Q' . $rows, '');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'PurchaseOrder_Listing';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    public function preview()
    {
        $po_data = $this->PurchaseorderModel->get_purchaseorder_preview();
        $data = array(
            'po_data' => $po_data,
            'success_code' => session()->get('success'),
        );

        echo view('purchaseorder/data_po_list_preview', $data);
    }
}
