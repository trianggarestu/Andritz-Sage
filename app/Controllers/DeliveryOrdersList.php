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

class DeliveryOrdersList extends BaseController
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
                $activenavd = 'deliveryorderslist';
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

        $delivery_data = $this->DeliveryordersModel->select('webot_SHIPMENTS.*,a.NAMECUST,it.DESC as SHIITEMDESC')
            ->join('ARCUS a', 'a.IDCUST = webot_SHIPMENTS.CUSTOMER', 'left')
            ->join('ICITEM it', 'it.ITEMNO = webot_SHIPMENTS.SHIITEMNO')
            ->where('webot_SHIPMENTS.POSTINGSTAT=', 1)
            ->orderBy('DOCUNIQ', 'DESC');
        //$Purchaseorderdata = $this->PurchaseOrderModel->get_PurchaseOrder_Close();
        $perpage = 20;

        $data = array(
            'delivery_data' =>  $delivery_data->paginate($perpage, 'gr_posting_list'),
            'pager' => $delivery_data->pager,
            'ct_po_posting' => $this->DeliveryordersModel->count_delivery_posting(),
            'perpage' => $perpage,
            'currentpage' => $delivery_data->pager->getCurrentPage('gr_posting_list'),
            'totalpages'  => $delivery_data->pager->getPageCount('gr_posting_list'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('delivery/data_delivery_list.php', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $deliverydata = $this->DeliveryordersModel->get_delivery_preview();
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'SHIPMENTNO')
            ->setCellValue('C1', 'SHIPMENTDATE')
            ->setCellValue('D1', 'DOCUMENTNO')
            ->setCellValue('E1', 'CUSTOMERNAME')
            ->setCellValue('F1', 'RECEIPT CUSTOMER(DATE)')
            ->setCellValue('G1', 'ITEMNO')
            ->setCellValue('H1', 'ITEMDESC')
            ->setCellValue('I1', 'QTY DELIVERY')
            ->setCellValue('J1', 'QTT OUTSTANDING')
            ->setCellValue('K1', 'CONTRACT')
            ->setCellValue('L1', 'PROJECT')
            ->setCellValue('M1', 'D/N STATUS')
            ->setCellValue('N1', 'POSTING STATUS');




        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($deliverydata as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['SHINUMBER'])
                ->setCellValue('C' . $rows, $data['SHIDATE'])
                ->setCellValue('D' . $rows, $data['DOCNUMBER'])
                ->setCellValue('E' . $rows, $data['NAMECUST'])
                ->setCellValue('F' . $rows, $data['CUSTRCPDATE'])
                ->setCellValue('G' . $rows, $data['SHIITEMNO'])
                ->setCellValue('H' . $rows, $data['SHIITEMDESC'])
                ->setCellValue('I' . $rows, $data['SHIQTY'])
                ->setCellValue('J' . $rows, $data['SHIQTYOUTSTANDING'])
                ->setCellValue('K' . $rows, $data['CONTRACT'])
                ->setCellValue('L' . $rows, $data['PROJECT'])
                ->setCellValue('M' . $rows, $data['POCUSTSTATUS'])
                ->setCellValue('N' . $rows, $data['DNPOSTINGSTAT'])


                ->setCellValue('Q' . $rows, '');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'DeliveryOrder';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    public function preview()
    {
        $delivery_data = $this->DeliveryordersModel->get_delivery_preview();
        $data = array(
            'delivery_data' => $delivery_data,
            'success_code' => session()->get('success'),
        );

        echo view('delivery/data_delivery_list_preview', $data);
    }
}
