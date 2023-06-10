<?php

namespace App\Controllers;

use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Settingnavheader_model;
use App\Models\Notif_model;
use App\Models\Ordertracking_model;

//use App\Controllers\AdminController;

class OrderTracking extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url');
        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
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
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavd = 'ordertracking';
                $activenavh = $this->AdministrationModel->get_activenavh($activenavd);
                $this->nav_data = [
                    'active_navd' => $activenavd,
                    'active_navh' => $activenavh,
                    'menu_nav' => $this->AdministrationModel->get_navigation($user),
                    //'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
                    //'chkusernav' => $this->AdministrationModel->count_navigation($user), 
                    //'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
                ];
            } else {
                header('Location: ' . base_url());
                exit();
            }
        }
    }


    public function index()
    {
        $ordtrackingdata = $this->OrdertrackingModel->get_ordertracking();


        $data = array(
            'ordtrack_data' => $ordtrackingdata,
            'keyword' => '',
        );
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('rpt/ordertracking_rpt', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function refresh()
    {
        session()->remove('cari');
        return redirect()->to(base_url('ordertracking'));
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
        return redirect()->to(base_url('ordertracking/filter'));
    }


    public function filter()
    {
        $keyword = session()->get('cari');
        if (empty($keyword)) {
            $ordtrackingdata = $this->OrdertrackingModel->get_ordertracking();
        } else {
            $ordtrackingdata = $this->OrdertrackingModel->get_ordertracking_search($keyword);
        }
        $data = array(
            'ordtrack_data' => $ordtrackingdata,
            'keyword' => $keyword,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('rpt/ordertracking_rpt', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function export_excel()
    {
        //$peoples = $this->builder->get()->getResultArray();
        $ordtrackingdata = $this->OrdertrackingModel->get_ordertracking();
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
            ->setCellValue('S1', '')
            ->setCellValue('T1', 'Po Vendor')
            ->setCellValue('U1', 'Po Vendor Date')
            ->setCellValue('V1', 'ETD (Date)')
            ->setCellValue('W1', 'Cargoreadiness(Date)')
            ->setCellValue('X1', 'Origin Country')
            ->setCellValue('Y1', 'Remarks')
            ->setCellValue('Z1', '')
            ->setCellValue('AA1', 'ETD Origin (Date)')
            ->setCellValue('AB1', 'ATD Origin (Date)')
            ->setCellValue('AC1', 'ETA PORT (Date)')
            ->setCellValue('AD1', 'PIB (Date)')
            ->setCellValue('AE1', 'Shipment Status')
            ->setCellValue('AF1', 'GR Date')
            ->setCellValue('AG1', 'Qty')
            ->setCellValue('AH1', 'Status')
            ->setCellValue('AI1', 'Delivery Date')
            ->setCellValue('AJ1', 'DN Number')
            ->setCellValue('AK1', 'Received Date')
            ->setCellValue('AL1', 'Delivered Qty')
            ->setCellValue('AM1', 'Outstansing Qty')
            ->setCellValue('AN1', 'PO Status')
            ->setCellValue('AO1', 'DN Status')
            ->setCellValue('AP1', 'Invoice Date')
            ->setCellValue('AQ1', 'Inv Status')
            ->setCellValue('AR1', 'RR Status')
            ->setCellValue('AS1', 'PO Cust to PR')
            ->setCellValue('AT1', 'PR to PO')
            ->setCellValue('AU1', 'Ontime Delivery')
            ->setCellValue('AV1', 'PO to DN');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($ord_data as $data) {
            $grpostingstat =  $data['GRSTATUS'];
            switch ($grpostingstat) {
                case "0":
                    $grpostingstatus = "Open";
                    break;
                case "1":
                    $grpostingstatus = "Posted";
                    break;
                case "2":
                    $grpostingstatus = "Deleted";
                    break;
                default:
                    $grpostingstatus = "Open";
            }
            $popostingstat =  $data['POCUSTSTATUS'];
            switch ($popostingstat) {
                case "0":
                    $popostingstatus = "Partial";
                    break;
                case "1":
                    $popostingstatus = "Completed";
                    break;

                default:
                    $popostingstatus = "Open";
            }
            $dnpostingstat =  $data['DNSTATUS'];
            switch ($dnpostingstat) {
                case "0":
                    $dnpostingstatus = "Open";
                    break;
                case "1":
                    $dnpostingstatus = "Posted";
                    break;
                case "2":
                    $dnpostingstatus = "Deleted";
                    break;
                default:
                    $dnpostingstatus = "Open";
            }
            $invpostingstat =  $data['FINSTATUS'];
            switch ($invpostingstat) {
                case "0":
                    $invpostingstatus = "Open";
                    break;
                case "1":
                    $invpostingstatus = "Posted";
                    break;
                case "2":
                    $invpostingstatus = "Deleted";
                    break;
                default:
                    $invpostingstatus = "Open";
            }
            $rrstat =  $data['RRSTATUS'];
            switch ($rrstat) {
                case "0":
                    $rrstat = "Open";
                    break;
                case "1":
                    $rrstat = "Done";
                    break;

                default:
                    $postingstatus = "Done";
            }
            $dd = substr($data['PODATECUST'], 6, 2);
            $mm = substr($data['PODATECUST'], 4, 2);
            $yyyy = substr($data['PODATECUST'], 0, 4);
            $pocustdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['CRMREQDATE'], 6, 2);
            $mm = substr($data['CRMREQDATE'], 4, 2);
            $yyyy = substr($data['CRMREQDATE'], 0, 4);
            $crmreqdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['INVOICEDATE'], 6, 2);
            $mm = substr($data['INVOICEDATE'], 4, 2);
            $yyyy = substr($data['INVOICEDATE'], 0, 4);
            $invdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['CUSTRCPDATE'], 6, 2);
            $mm = substr($data['CUSTRCPDATE'], 4, 2);
            $yyyy = substr($data['CUSTRCPDATE'], 0, 4);
            $rcpdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['RECPDATE'], 6, 2);
            $mm = substr($data['RECPDATE'], 4, 2);
            $yyyy = substr($data['RECPDATE'], 0, 4);
            $grdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['PODATE'], 6, 2);
            $mm = substr($data['PODATE'], 4, 2);
            $yyyy = substr($data['PODATE'], 0, 4);
            $podate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['RQNDATE'], 6, 2);
            $mm = substr($data['RQNDATE'], 4, 2);
            $yyyy = substr($data['RQNDATE'], 0, 4);
            $rqndate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['CARGOREADINESSDATE'], 6, 2);
            $mm = substr($data['CARGOREADINESSDATE'], 4, 2);
            $yyyy = substr($data['CARGOREADINESSDATE'], 0, 4);
            $cargodate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['ETDDATE'], 6, 2);
            $mm = substr($data['ETDDATE'], 4, 2);
            $yyyy = substr($data['ETDDATE'], 0, 4);
            $etddate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['ETDORIGINDATE'], 6, 2);
            $mm = substr($data['ETDORIGINDATE'], 4, 2);
            $yyyy = substr($data['ETDORIGINDATE'], 0, 4);
            $etdoridate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['ATDORIGINDATE'], 6, 2);
            $mm = substr($data['ATDORIGINDATE'], 4, 2);
            $yyyy = substr($data['ATDORIGINDATE'], 0, 4);
            $atddate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['ETAPORTDATE'], 6, 2);
            $mm = substr($data['ETAPORTDATE'], 4, 2);
            $yyyy = substr($data['ETAPORTDATE'], 0, 4);
            $portdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['PIBDATE'], 6, 2);
            $mm = substr($data['PIBDATE'], 4, 2);
            $yyyy = substr($data['PIBDATE'], 0, 4);
            $pibdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['SHIDATE'], 6, 2);
            $mm = substr($data['SHIDATE'], 4, 2);
            $yyyy = substr($data['SHIDATE'], 0, 4);
            $shidate = $mm . '/' . $dd . '/' . $yyyy;





            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['CONTRACT'])
                ->setCellValue('C' . $rows, $data['PROJECT'])
                ->setCellValue('D' . $rows, $data['CUSTOMER'])
                ->setCellValue('E' . $rows, $data['EMAIL1CUST'])
                ->setCellValue('F' . $rows, $data['CRMNO'])
                ->setCellValue('G' . $rows, $data['PONUMBERCUST'])
                ->setCellValue('H' . $rows, $data['ITEMNO'])
                ->setCellValue('I' . $rows, $data['MATERIALNO'])
                ->setCellValue('J' . $rows, $pocustdate)
                ->setCellValue('K' . $rows, $crmreqdate)
                ->setCellValue('L' . $rows, $data['SALESNAME'])
                ->setCellValue('M' . $rows, $data['ORDERDESC'])
                ->setCellValue('N' . $rows, $data['QTY'])
                ->setCellValue('O' . $rows, $data['STOCKUNIT'])
                ->setCellValue('P' . $rows, '')
                ->setCellValue('Q' . $rows, $rqndate)
                ->setCellValue('R' . $rows, $data['RQNNUMBER'])
                ->setCellValue('S' . $rows, '')
                ->setCellValue('T' . $rows, $data['PONUMBER'])
                ->setCellValue('U' . $rows, $podate)
                ->setCellValue('V' . $rows, $etddate)
                ->setCellValue('W' . $rows, $cargodate)
                ->setCellValue('X' . $rows, $data['ORIGINCOUNTRY'])
                ->setCellValue('Y' . $rows, $data['POREMARKS'])
                ->setCellValue('Z' . $rows, '')
                ->setCellValue('AA' . $rows, $etdoridate)
                ->setCellValue('AB' . $rows, $atddate)
                ->setCellValue('AC' . $rows, $portdate)
                ->setCellValue('AD' . $rows, $pibdate)
                ->setCellValue('AE' . $rows, $data['VENDSHISTATUS'])
                ->setCellValue('AF' . $rows, $grdate)
                ->setCellValue('AG' . $rows, $data['RECPQTY'])
                ->setCellValue('AH' . $rows, $grpostingstatus)
                ->setCellValue('AI' . $rows, $data['SHINUMBER'])
                ->setCellValue('AJ' . $rows, $shidate)
                ->setCellValue('AK' . $rows, $rcpdate)
                ->setCellValue('AL' . $rows, $data['SHIQTY'])
                ->setCellValue('AM' . $rows, $data['SHIQTYOUTSTANDING'])
                ->setCellValue('AN' . $rows, $popostingstatus)
                ->setCellValue('AO' . $rows, $dnpostingstatus)
                ->setCellValue('AP' . $rows, $invdate)
                ->setCellValue('AQ' . $rows, $invpostingstatus)
                ->setCellValue('AR' . $rows, $rrstat)
                ->setCellValue('AR' . $rows, $data['POCUSTTOPRDAYS'])
                ->setCellValue('AR' . $rows, $data['POTOPODAYS'])
                ->setCellValue('AR' . $rows, $data['ONTIMEDELDAYS'])
                ->setCellValue('AR' . $rows, $data['POTODNDAYS'])





                ->setCellValue('AS' . $rows, '');
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
}
