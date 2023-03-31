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
    private $db;

    public function __construct()
    {
        //parent::__construct();
        helper(['form', 'url']);
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->SalesorderModel = new Salesorder_model();
        $this->db = \Config\Database::connect();

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
            $activenavd = 'salesorderlist';
            $activenavh = $this->AdministrationModel->get_activenavh($activenavd);
            $this->nav_data = [
                'active_navd' => $activenavd,
                'active_navh' => $activenavh,
                'menu_nav' => $this->AdministrationModel->get_navigation(),
                //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
                //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
                //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
            ];
            //}
        }
    }

    public function index()
    {
        $so_data = $this->SalesorderModel->get_so_open();
        $data = array(
            'so_data' => $so_data,
            'audtorg' => $this->db->database,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_list', $data);
        echo view('view_footer', $this->footer_data);
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
            ->setCellValue('N1', 'Qty')
            ->setCellValue('O1', 'Uom')
            ->setCellValue('P1', 'Status');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($so_data as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['CustomerName'])
                ->setCellValue('C' . $rows, $data['CustomerEmail'])
                ->setCellValue('D' . $rows, $data['ContractNo'])
                ->setCellValue('E' . $rows, $data['ProjectNo'])
                ->setCellValue('F' . $rows, $data['CrmNo'])
                ->setCellValue('G' . $rows, $data['PoCustomer'])
                ->setCellValue('J' . $rows, $data['PoDate'])
                ->setCellValue('H' . $rows, $data['InventoryNo'])
                ->setCellValue('I' . $rows, $data['MaterialNo'])
                ->setCellValue('K' . $rows, $data['ReqDate'])
                ->setCellValue('L' . $rows, $data['SalesPerson'])
                ->setCellValue('M' . $rows, $data['OrderDesc'])
                ->setCellValue('N' . $rows, $data['Qty'])
                ->setCellValue('O' . $rows, $data['Uom'])
                ->setCellValue('P' . $rows, 'Waiting processed by Requester');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Sales_Order_Open_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
