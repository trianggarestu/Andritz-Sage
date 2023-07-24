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
use App\Models\Logistics_model;

//use App\Controllers\AdminController;

class ArrangeshipmentList extends BaseController
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
        $this->LogisticsModel = new Logistics_model();

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
                    'success_code' => session()->get('success'),
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavd = 'arrangeshipmentlist';
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
                    'NAMELGN' => $infouser['namalgn'],

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
        $log_data = $this->LogisticsModel->select('webot_LOGISTICS.*,po.*,csr.*')
            ->join('webot_PO po', 'po.CSRUNIQ = webot_LOGISTICS.CSRUNIQ', 'po.POUNIQ = webot_LOGISTICS.POUNIQ', 'inner')
            ->join('webot_CSR csr', 'csr.CSRUNIQ = webot_LOGISTICS.CSRUNIQ', 'inner')
            ->groupStart()
            ->where('webot_LOGISTICS.POSTINGSTAT =', 1)
            ->groupEnd()
            ->groupStart()
            ->where('webot_LOGISTICS.ETAPORTDATE >=', $fr_date)
            ->where('webot_LOGISTICS.ETAPORTDATE<=', $to_date)
            ->groupEnd()
            ->orderBy('webot_LOGISTICS.ETAPORTDATE', 'ASC');
        $perpage = 20;
        $data = array(
            'keyword' => '',
            'log_data' => $log_data->paginate($perpage, 'csr_data'),
            'pager' => $log_data->pager,
            'success_code' => session()->get('success'),
            // 'currentpage' => $currentpage,
            'perpage' => $perpage,
            'currentpage' => $log_data->pager->getCurrentPage('log_data'),
            'def_fr_date' => $def_fr_date,
            'def_to_date' => $def_to_date,
            //'fr_date' => $fr_date,
            //'to_date' => $to_date,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('logistics/data_logistics_list', $data);
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
        return redirect()->to(base_url('arrangeshipmentlist/filter'));
    }

    public function filter()
    {
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
        //  $currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $perpage = 20;
        $keyword = session()->get('cari');
        $fromdate = session()->get('from_date');
        $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
        $todate = session()->get('to_date');
        $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
        if (empty($keyword)) {
            $log_data = $this->LogisticsModel->select('webot_LOGISTICS.*,po.*,csr.*')
                ->join('webot_PO po', 'po.POUNIQ = webot_LOGISTICS.POUNIQ', 'inner')
                ->join('webot_CSR csr', 'csr.CSRUNIQ = webot_LOGISTICS.CSRUNIQ', 'inner')
                ->groupStart()
                ->where('webot_LOGISTICS.POSTINGSTAT =', 1)
                ->groupEnd()
                ->groupStart()
                ->where('webot_LOGISTICS.ETDORIGINDATE >=', $nfromdate)
                ->where('webot_LOGISTICS.ETDORIGINDATE <=', $ntodate)
                ->groupEnd()
                ->orderBy('webot_LOGISTICS.ETDORIGINDATE', 'ASC');
        } else {
            $log_data = $this->LogisticsModel->select('webot_LOGISTICS.*,po.*,csr.*')
                ->join('webot_PO po', 'po.POUNIQ = webot_LOGISTICS.POUNIQ', 'inner')
                ->join('webot_CSR csr', 'csr.CSRUNIQ = webot_LOGISTICS.CSRUNIQ', 'inner')
                ->groupStart()
                ->where('webot_LOGISTICS.POSTINGSTAT =', 1)
                ->groupEnd()
                ->groupStart()
                ->where('webot_LOGISTICS.ETDORIGINDATE >=', $nfromdate)
                ->where('webot_LOGISTICS.ETDORIGINDATE <=', $ntodate)
                ->groupEnd()
                ->groupStart()
                ->like('webot_LOGISTICS.PONUMBER', $keyword)
                ->orlike('po.ORIGINCOUNTRY', $keyword)
                ->orlike('po.POREMARKS', $keyword)
                ->orlike('webot_LOGISTICS.VENDSHISTATUS', $keyword)
                ->groupEnd()
                ->orderBy('webot_LOGISTICS.ETDORIGINDATE', 'ASC');
            //$so_data = $this->LogisticsModel->get_csr_list_post_search($keyword);
        }
        $data = array(
            'keyword' => $keyword,
            'log_data' => $log_data->paginate($perpage, 'req_data'),
            'pager' => $log_data->pager,
            'success_code' => session()->get('success'),
            'perpage' => $perpage,
            'currentpage' => $log_data->pager->getCurrentPage('log_data'),
            // 'currentpage' => $currentpage,
            'def_fr_date' => session()->get('from_date'),
            'def_to_date' => session()->get('to_date'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('logistics/data_logistics_list', $data);
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
            $log_data = $this->LogisticsModel->get_log_preview($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $log_data = $this->LogisticsModel->get_log_preview_filter($keyword, $nfromdate, $ntodate);
        }

        $data = array(
            'log_data' => $log_data,
            'keyword' => $keyword,
            'fromdate' => $nfromdate,
            'todate' => $ntodate,

        );

        echo view('logistics/data_log_list_preview', $data);
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
            $log_data = $this->LogisticsModel->get_log_preview($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $log_data = $this->LogisticsModel->get_log_preview_filter($keyword, $nfromdate, $ntodate);
        }
        //$so_data = $this->LogisticsModel->get_so_open();
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
            ->setCellValue('I1', 'ETDORIGINDATE')
            ->setCellValue('J1', 'ATDORIGINDATE')
            ->setCellValue('K1', 'ETAPORTDATE')
            ->setCellValue('L1', 'PIBDATE')
            ->setCellValue('M1', 'SHIPMENTSTATUS');


        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($log_data as $data) {
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
            $dd = substr($data['PODATE'], 6, 2);
            $mm = substr($data['PODATE'], 4, 2);
            $yyyy = substr($data['PODATE'], 0, 4);
            $podate = $mm . '/' . $dd . '/' . $yyyy;

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


            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['PONUMBER'])
                ->setCellValue('C' . $rows, $podate)
                ->setCellValue('D' . $rows, $etddate)
                ->setCellValue('E' . $rows, $cargodate)
                ->setCellValue('F' . $rows, $data['ORIGINCOUNTRY'])
                ->setCellValue('G' . $rows, $data['POREMARKS'])
                ->setCellValue('H' . $rows, $data['POSTINGSTAT'])
                ->setCellValue('I' . $rows, $etdoridate)
                ->setCellValue('J' . $rows, $atddate)
                ->setCellValue('K' . $rows, $portdate)
                ->setCellValue('L' . $rows, $pibdate)
                ->setCellValue('M' . $rows, $data['VENDSHISTATUS'])


                ->setCellValue('Q' . $rows, '');
            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Logistics_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
