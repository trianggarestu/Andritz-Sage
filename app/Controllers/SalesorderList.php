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
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
        $currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        //session()->remove('success');
        $so_data = $this->SalesorderModel->select('webot_CSR.*,' . 'it."DESC"' . ' as ITEMDESC')
            ->join('ICITEM it', 'it.ITEMNO = webot_CSR.ITEMNO', 'left')
            ->groupStart()
            ->where('POSTINGSTAT =', 1)
            ->groupEnd()
            ->groupStart()
            ->where('PODATECUST >=', $fr_date)
            ->where('PODATECUST <=', $to_date)
            ->groupEnd()
            ->orderBy('PODATECUST', 'ASC');
        $perpage = 20;
        $data = array(
            'keyword' => '',
            'so_data' => $so_data->paginate($perpage, 'csr_data'),
            'pager' => $so_data->pager,
            'success_code' => session()->get('success'),
            'currentpage' => $currentpage,
            'def_fr_date' => $def_fr_date,
            'def_to_date' => $def_to_date,
            //'fr_date' => $fr_date,
            //'to_date' => $to_date,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_list', $data);
        echo view('view_footer', $this->footer_data);
        session()->remove('success');
        session()->remove('cari');
        session()->remove('from_date');
        session()->remove('to_date');
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
        return redirect()->to(base_url('salesorderlist/filter'));
    }


    public function filter()
    {
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
        $currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $perpage = 20;
        $keyword = session()->get('cari');
        $fromdate = session()->get('from_date');
        $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
        $todate = session()->get('to_date');
        $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
        if (empty($keyword)) {
            $so_data = $this->SalesorderModel->select('webot_CSR.*,' . 'it."DESC"' . ' as ITEMDESC')
                ->join('ICITEM it', 'it.ITEMNO = webot_CSR.ITEMNO', 'left')
                ->groupStart()
                ->where('POSTINGSTAT =', 1)
                ->groupEnd()
                ->groupStart()
                ->where('PODATECUST >=', $nfromdate)
                ->where('PODATECUST <=', $ntodate)
                ->groupEnd()
                ->orderBy('PODATECUST', 'ASC');
        } else {
            $so_data = $this->SalesorderModel->select('webot_CSR.*,' . 'it."DESC"' . ' as ITEMDESC')
                ->join('ICITEM it', 'it.ITEMNO = webot_CSR.ITEMNO', 'left')
                ->groupStart()
                ->where('POSTINGSTAT =', 1)
                ->groupEnd()
                ->groupStart()
                ->where('PODATECUST >=', $nfromdate)
                ->where('PODATECUST <=', $ntodate)
                ->groupEnd()
                ->groupStart()
                ->like('CONTRACT', $keyword)
                ->orlike('CTDESC', $keyword)
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
                ->orlike('SERVICETYPE', $keyword)
                ->orlike('CRMREMARKS', $keyword)
                ->orlike('webot_CSR.ITEMNO', $keyword)
                ->orlike('it."DESC"', $keyword)
                ->orlike('MATERIALNO', $keyword)
                ->orlike('webot_CSR.STOCKUNIT', $keyword)
                ->groupEnd()
                ->orderBy('PODATECUST', 'ASC');
            //$so_data = $this->SalesorderModel->get_csr_list_post_search($keyword);
        }
        $data = array(
            'keyword' => $keyword,
            'so_data' => $so_data->paginate($perpage, 'csr_data'),
            'pager' => $so_data->pager,
            'success_code' => session()->get('success'),
            'currentpage' => $currentpage,
            'def_fr_date' => session()->get('from_date'),
            'def_to_date' => session()->get('to_date'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_list', $data);
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
            $so_data = $this->SalesorderModel->get_so_open($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $so_data = $this->SalesorderModel->get_so_open_filter($keyword, $nfromdate, $ntodate);
        }
        //
        $data = array(
            'so_data' => $so_data,
            'keyword' => $keyword,
            'fromdate' => $nfromdate,
            'todate' => $ntodate,

        );

        echo view('crm/data_so_list_preview', $data);
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
            $so_data = $this->SalesorderModel->get_so_open($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $so_data = $this->SalesorderModel->get_so_open_filter($keyword, $nfromdate, $ntodate);
        }
        //$so_data = $this->SalesorderModel->get_so_open();
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
            ->setCellValue('K1', 'Item Desc.')
            ->setCellValue('L1', 'ReqDate')
            ->setCellValue('M1', 'SalesPerson')
            ->setCellValue('N1', 'Order Description')
            ->setCellValue('O1', 'Service Type')
            ->setCellValue('P1', 'Qty')
            ->setCellValue('Q1', 'Uom')
            ->setCellValue('R1', 'Status');

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
                ->setCellValue('H' . $rows, trim($pocustdate))
                ->setCellValue('I' . $rows, $data['ITEMNO'])
                ->setCellValue('J' . $rows, $data['MATERIALNO'])
                ->setCellValue('K' . $rows, $data['ITEMDESC'])
                ->setCellValue('L' . $rows, trim($reqdate))
                ->setCellValue('M' . $rows, $data['SALESNAME'])
                ->setCellValue('N' . $rows, $data['ORDERDESC'])
                ->setCellValue('O' . $rows, $data['SERVICETYPE'])
                ->setCellValue('P' . $rows, $data['QTY'])
                ->setCellValue('Q' . $rows, $data['STOCKUNIT'])
                ->setCellValue('R' . $rows, $postingstatus);
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
