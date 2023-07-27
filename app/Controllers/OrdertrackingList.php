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

class OrdertrackingList extends BaseController
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
                $activenavd = 'ordertrackinglist';
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
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
        //$currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        //session()->remove('success');
        $ord_data = $this->OrdertrackingModel->select('*')
            // webot_FINANCE.*,a.DOCNUMBER,a.SHINUMBER,a.SHIDATE,b.CONTRACT,b.PROJECT,b.CTDESC')
            //     ->join('webot_SHIPMENTS a', 'a.SHIUNIQ = webot_FINANCE.SHIUNIQ', 'left')
            //     ->join('webot_CSR ', 'b.CSRUNIQ = webot_FINANCE.CSRUNIQ', 'left')
            // ->groupStart()
            // ->where('POSTINGSTAT =', 1)
            // ->groupEnd()
            ->groupStart()
            ->where('PODATECUST >=', $fr_date)
            ->where('PODATECUST <=', $to_date)
            ->groupEnd()
            ->orderBy('OTSEQ', 'ASC');
        $perpage = 20;
        $data = array(
            'keyword' => '',
            'ord_data' => $ord_data->paginate($perpage, 'ord_data'),
            'pager' => $ord_data->pager,
            'success_code' => session()->get('success'),
            //'currentpage' => $currentpage,
            'perpage' => $perpage,
            'currentpage' => $ord_data->pager->getCurrentPage('ord_data'),
            'def_fr_date' => $def_fr_date,
            'def_to_date' => $def_to_date,
            //'fr_date' => $fr_date,
            //'to_date' => $to_date,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('rpt/ordertrackinglist_rpt', $data);
        echo view('view_footer', $this->footer_data);
        session()->remove('success');
        session()->remove('cari');
        session()->remove('from_date');
        session()->remove('to_date');
        session()->remove('success');
        session()->set('success', '0');
    }

    public function search()
    {

        session()->remove('success');
        session()->set('success', '0');
        $cari = $this->request->getPost('cari');
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        if ($cari != '') {
            session()->set('cari', $cari);
            session()->set('from_date', $from_date);
            session()->set('to_date', $to_date);
        } else {
            session()->remove('cari');
            session()->set('from_date', $from_date);
            session()->set('to_date', $to_date);
        }
        return redirect()->to(base_url('ordertrackinglist/filter'));
    }

    public function filter()
    {
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
        //$currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $perpage = 20;
        $keyword = session()->get('cari');
        $fromdate = session()->get('from_date');
        $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
        $todate = session()->get('to_date');
        $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
        if (empty($keyword)) {
            $ord_data = $this->OrdertrackingModel->select('*')
                ->groupStart()
                ->where('RRSTATUS=', 1)
                ->groupEnd()
                ->groupStart()
                ->where('PODATECUST >=', $nfromdate)
                ->where('PODATECUST <=', $ntodate)
                ->groupEnd()
                ->orderBy('OTSEQ', 'ASC');
        } else {
            $ord_data = $this->OrdertrackingModel->select('*')
                ->groupStart()
                ->where('RRSTATUS=', 1)
                ->groupEnd()
                ->groupStart()
                ->where('PODATECUST >=', $nfromdate)
                ->where('PODATECUST <=', $ntodate)
                ->groupEnd()
                ->groupStart()

                ->like('CONTRACT', $keyword)
                ->orlike('PROJECT', $keyword)
                ->orlike('MANAGER', $keyword)
                ->orlike('SALESNAME', $keyword)
                ->orlike('PROJECT', $keyword)
                ->orlike('PRJDESC', $keyword)
                ->orlike('PONUMBERCUST', $keyword)
                ->orlike('CUSTOMER', $keyword)
                ->orlike('NAMECUST', $keyword)
                ->orlike('EMAIL1CUST', $keyword)
                ->orlike('CRMNO', $keyword)
                ->orlike('ORDERDESC', $keyword)
                ->orlike('CRMREMARKS', $keyword)
                ->orlike('RQNNUMBER', $keyword)
                ->orlike('PONUMBER', $keyword)
                ->orlike('ORIGINCOUNTRY', $keyword)
                ->orlike('POREMARKS', $keyword)
                ->orlike('VENDSHISTATUS', $keyword)
                ->orlike('SHINUMBER', $keyword)

                ->groupEnd()
                ->orderBy('OTSEQ', 'ASC');
            //$so_data = $this->OrdertrackingModel ->get_csr_list_post_search($keyword);
        }
        $data = array(
            'keyword' => $keyword,
            'ord_data' => $ord_data->paginate($perpage, 'ord_data'),
            'pager' => $ord_data->pager,
            'success_code' => session()->get('success'),
            //'currentpage' => $currentpage,
            'perpage' => $perpage,
            'currentpage' => $ord_data->pager->getCurrentPage('ord_data'),
            'def_fr_date' => session()->get('from_date'),
            'def_to_date' => session()->get('to_date'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('rpt/ordertrackinglist_rpt', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function preview()
    {
        $keyword = session()->get('cari');
        $fromdate = session()->get('from_date');
        $todate = session()->get('to_date');

        if (empty($fromdate) and empty($todate)) {
            $today = $this->audtuser['AUDTDATE'];
            $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
            $def_fr_date = date("m/01/Y", strtotime($def_date));
            $nfromdate = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
            $def_to_date = date("m/t/Y", strtotime($def_date));
            $ntodate = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
            $keyword = '';
            $ord_data = $this->OrdertrackingModel->get_inv_preview($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $ord_data = $this->OrdertrackingModel->get_inv_preview_filter($keyword, $nfromdate, $ntodate);
        }

        $data = array(
            'ord_data' => $ord_data,
            'keyword' => $keyword,
            'fromdate' => $nfromdate,
            'todate' => $ntodate,

        );

        echo view('rpt/data_ot_list_preview', $data);
    }

    public function export_excel()
    {
        $keyword = session()->get('cari');
        $fromdate = session()->get('from_date');
        $todate = session()->get('to_date');

        if (empty($fromdate) and empty($todate)) {
            $today = $this->audtuser['AUDTDATE'];
            $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
            $def_fr_date = date("m/01/Y", strtotime($def_date));
            $nfromdate = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
            $def_to_date = date("m/t/Y", strtotime($def_date));
            $ntodate = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
            $keyword = '';
            $ord_data = $this->OrdertrackingModel->get_inv_preview($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $ord_data = $this->OrdertrackingModel->get_inv_preview_filter($keyword, $nfromdate, $ntodate);
        }
        //$so_data = $this->OrdertrackingModel ->get_so_open();
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
                    $invpostingstatus = "Partial";
                    break;
                case "2":
                    $invpostingstatus = "Complete";
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
                    $rrstat = "Completed";
                    break;

                default:
                    $postingstatus = " ";
            }
            $dd = substr($data['PODATECUST'], 6, 2);
            $mm = substr($data['PODATECUST'], 4, 2);
            $yyyy = substr($data['PODATECUST'], 0, 4);
            $pocustdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['CRMREQDATE'], 6, 2);
            $mm = substr($data['CRMREQDATE'], 4, 2);
            $yyyy = substr($data['CRMREQDATE'], 0, 4);
            $crmreqdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['DATEINVC'], 6, 2);
            $mm = substr($data['DATEINVC'], 4, 2);
            $yyyy = substr($data['DATEINVC'], 0, 4);
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
                ->setCellValue('AS' . $rows, $data['POCUSTTOPRDAYS'])
                ->setCellValue('AT' . $rows, $data['POTOPODAYS'])
                ->setCellValue('AU' . $rows, $data['ONTIMEDELDAYS'])
                ->setCellValue('AV' . $rows, $data['POTODNDAYS'])





                ->setCellValue('AX' . $rows, '');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Order_tracking_List_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
