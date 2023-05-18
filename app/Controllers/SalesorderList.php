<?php

namespace App\Controllers;

use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Salesorder_model;

class SalesorderList extends BaseController
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
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->SalesorderModel = new Salesorder_model();
        $this->db_name = \Config\Database::connect();


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
                $activenavd = 'salesorderlist';
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
        $currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        //session()->remove('success');
        $so_data = $this->SalesorderModel->select('*')
            ->where('POSTINGSTAT !=', 2);
        $perpage = 20;
        $data = array(
            'so_data' => $so_data->paginate($perpage, 'so_data'),
            'success_code' => session()->get('success'),
            'pager' => $so_data->pager,
            'currentpage' => $currentpage
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_list', $data);
        echo view('view_footer', $this->footer_data);
        session()->remove('success');
    }

    public function deletedata($csruniq = '')
    {
        $getcsropen = $this->SalesorderModel->get_csr_open($csruniq);
        if ($getcsropen['POSTINGSTAT'] == 0) {
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'POSTINGSTAT' => 2,
            );
            $this->SalesorderModel->set_csr_delete($csruniq, $data);
            session()->set('success', '1');
            return redirect()->to(base_url('/salesorderlist'));
        } else {
            session()->set('success', '-1');
            return redirect()->to(base_url('/salesorderlist'));
        }
    }

    public function preview()
    {
        $so_data = $this->SalesorderModel->get_so_open();
        $data = array(
            'so_data' => $so_data,
            'success_code' => session()->get('success'),
        );

        echo view('crm/data_so_list_preview', $data);
    }

    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $so_data = $this->SalesorderModel->get_so_open();
        $spreadsheet = new Spreadsheet();

        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Customer Name')
            ->setCellValue('C1', 'Customer Email')
            ->setCellValue('D1', 'ContractNo')
            ->setCellValue('E1', 'ProjectNo')
            ->setCellValue('F1', 'CrmNo')
            ->setCellValue('G1', 'PoCustomer')
            ->setCellValue('H1', 'PoDate')
            ->setCellValue('I1', 'Inventroy No')
            ->setCellValue('J1', 'Material No')
            ->setCellValue('K1', 'ReqDate')
            ->setCellValue('L1', 'SalesPerson')
            ->setCellValue('M1', 'Order Description')
            ->setCellValue('M1', 'Service Type')
            ->setCellValue('N1', 'Qty')
            ->setCellValue('O1', 'Uom')
            ->setCellValue('P1', 'Status');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($so_data as $data) {
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

            $dd = substr($data['PODATECUST'], 6, 2);
            $mm = substr($data['PODATECUST'], 4, 2);
            $yyyy = substr($data['PODATECUST'], 0, 4);
            $pocustdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['CRMREQDATE'], 6, 2);
            $mm = substr($data['CRMREQDATE'], 4, 2);
            $yyyy = substr($data['CRMREQDATE'], 0, 4);
            $reqdate = $mm . '/' . $dd . '/' . $yyyy;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['NAMECUST'])
                ->setCellValue('C' . $rows, $data['EMAIL1CUST'])
                ->setCellValue('D' . $rows, $data['CONTRACT'])
                ->setCellValue('E' . $rows, $data['PROJECT'])
                ->setCellValue('F' . $rows, $data['CRMNO'])

                ->setCellValue('G' . $rows, $data['PONUMBERCUST'])
                ->setCellValue('J' . $rows, trim($pocustdate))
                ->setCellValue('H' . $rows, $data['ITEMNO'])
                ->setCellValue('I' . $rows, $data['MATERIALNO'])
                ->setCellValue('K' . $rows, trim($reqdate))
                ->setCellValue('L' . $rows, $data['SALESNAME'])
                ->setCellValue('M' . $rows, $data['ORDERDESC'])
                ->setCellValue('M' . $rows, $data['SERVICETYPE'])
                ->setCellValue('N' . $rows, $data['QTY'])
                ->setCellValue('O' . $rows, $data['STOCKUNIT'])
                ->setCellValue('P' . $rows, $postingstatus);
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Sales_Order_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
