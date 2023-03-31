<?php

namespace App\Controllers;

use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use App\Controllers\AdminController;

use App\Models\Customer_model;



class Customer extends BaseController
{
    public function __construct()
    {
        //$this->db = \Config\Database::connect();
        $this->CustModel = new Customer_model();
    }

    public function index()
    {
        $custdata = $this->CustModel->get_customer();


        $data = array(
            'customer_data' => $custdata,
        );
        //return view('welcome_message');
        echo view('view_header');
        echo view('view_nav');
        echo view('customer/data_customer', $data);
        echo view('view_footer');
    }

    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $custdata = $this->CustModel->get_customer();
        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'IDCUST')
            ->setCellValue('C1', 'IDGRP')
            ->setCellValue('D1', 'SHORTNAME')
            ->setCellValue('E1', 'CUSTNAME');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($custdata as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['IDCUST'])
                ->setCellValue('C' . $rows, $data['IDGRP'])
                ->setCellValue('D' . $rows, $data['TEXTSNAM'])
                ->setCellValue('E' . $rows, $data['NAMECUST']);
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'DATA_CUSTOMER';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
