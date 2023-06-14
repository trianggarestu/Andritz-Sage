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
        $today = $this->audtuser['AUDTDATE'];
        $def_date = substr($today, 4, 2) . "/" . substr($today, 6, 2) . "/" .  substr($today, 0, 4);
        $def_fr_date = date("m/01/Y", strtotime($def_date));
        $fr_date = substr($this->audtuser['AUDTDATE'], 0, 6) . '01';
        $def_to_date = date("m/t/Y", strtotime($def_date));
        $to_date = substr($def_to_date, 6, 4) . "" . substr($def_to_date, 0, 2) . "" . substr($def_to_date, 3, 2);
        $currentpage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        //session()->remove('success');
        $pr_data = $this->RequisitionModel->select('webot_REQUISITION.*,csr.*')
            ->join('webot_CSR csr', 'csr.CSRUNIQ = webot_REQUISITION.CSRUNIQ', 'left')
            ->groupStart()
            ->where('webot_REQUISITION.POSTINGSTAT =', 1)
            ->groupEnd()
            ->groupStart()
            ->where('webot_REQUISITION.RQNDATE >=', $fr_date)
            ->where('webot_REQUISITION.RQNDATE <=', $to_date)
            ->groupEnd()
            ->orderBy('webot_REQUISITION.RQNDATE', 'ASC');
        $perpage = 20;
        $data = array(
            'keyword' => '',
            'pr_data' => $pr_data->paginate($perpage, 'csr_data'),
            'pager' => $pr_data->pager,
            'success_code' => session()->get('success'),
            'currentpage' => $currentpage,
            'def_fr_date' => $def_fr_date,
            'def_to_date' => $def_to_date,
            //'fr_date' => $fr_date,
            //'to_date' => $to_date,
        );


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_pr_list', $data);
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
        return redirect()->to(base_url('requisitionlist/filter'));
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
            $pr_data = $this->RequisitionModel->select('webot_REQUISITION.*,csr.*')
                ->join('webot_CSR csr', 'csr.CSRUNIQ = webot_REQUISITION.CSRUNIQ', 'left')
                ->groupStart()
                ->where('webot_REQUISITION.POSTINGSTAT =', 1)
                ->groupEnd()
                ->groupStart()
                ->where('webot_REQUISITION.RQNDATE >=', $nfromdate)
                ->where('webot_REQUISITION.RQNDATE <=', $ntodate)
                ->groupEnd()
                ->orderBy('webot_REQUISITION.RQNDATE', 'ASC');
        } else {
            $pr_data = $this->RequisitionModel->select('webot_REQUISITION.*,csr.*')
                ->join('webot_CSR csr', 'csr.CSRUNIQ = webot_REQUISITION.CSRUNIQ', 'left')
                ->groupStart()
                ->where('webot_REQUISITION.POSTINGSTAT =', 1)
                ->groupEnd()
                ->groupStart()
                ->where('webot_REQUISITION.RQNDATE >=', $nfromdate)
                ->where('webot_REQUISITION.RQNDATE <=', $ntodate)
                ->groupEnd()
                ->groupStart()

                ->like('csr.CONTRACT', $keyword)
                ->orlike('csr.PROJECT', $keyword)
                ->orlike('webot_REQUISITION.RQNNUMBER', $keyword)
                ->orlike('csr.MANAGER', $keyword)
                ->orlike('csr.SALESNAME', $keyword)

                ->orlike('csr.PRJDESC', $keyword)
                ->orlike('csr.PONUMBERCUST', $keyword)
                ->orlike('csr.CUSTOMER', $keyword)
                ->orlike('csr.NAMECUST', $keyword)
                ->orlike('csr.EMAIL1CUST', $keyword)
                ->orlike('csr.CRMNO', $keyword)
                ->orlike('csr.ORDERDESC', $keyword)
                ->orlike('csr.SERVICETYPE', $keyword)
                ->orlike('csr.CRMREMARKS', $keyword)
                ->orlike('csr.ITEMNO', $keyword)
                ->orlike('csr.MATERIALNO', $keyword)
                ->orlike('csr.STOCKUNIT', $keyword)
                ->orlike('csr.CTDESC', $keyword)
                ->orlike('csr.QTY', $keyword)



                ->groupEnd()
                ->orderBy('webot_REQUISITION.RQNDATE', 'ASC');
            //$so_data = $this->RequisitionModel->get_csr_list_post_search($keyword);
        }
        $data = array(
            'keyword' => $keyword,
            'pr_data' => $pr_data->paginate($perpage, 'req_data'),
            'pager' => $pr_data->pager,
            'success_code' => session()->get('success'),
            'currentpage' => $currentpage,
            'def_fr_date' => session()->get('from_date'),
            'def_to_date' => session()->get('to_date'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('requisition/data_pr_list', $data);
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
            $pr_data = $this->RequisitionModel->get_pr_preview($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $pr_data = $this->RequisitionModel->get_pr_preview_filter($keyword, $nfromdate, $ntodate);
        }

        $data = array(
            'pr_data' => $pr_data,
            'keyword' => $keyword,
            'fromdate' => $nfromdate,
            'todate' => $ntodate,

        );

        echo view('requisition/data_pr_list_preview', $data);
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
            $pr_data = $this->RequisitionModel->get_pr_preview($nfromdate, $ntodate);
        } else {
            $keyword = session()->get('cari');
            $fromdate = session()->get('from_date');
            $nfromdate = substr($fromdate, 6, 4) . "" . substr($fromdate, 0, 2) . "" . substr($fromdate, 3, 2);
            $todate = session()->get('to_date');
            $ntodate = substr($todate, 6, 4) . "" . substr($todate, 0, 2) . "" . substr($todate, 3, 2);
            $pr_data = $this->RequisitionModel->get_pr_preview_filter($keyword, $nfromdate, $ntodate);
        }
        //$so_data = $this->RequisitionModel->get_so_open();
        $spreadsheet = new Spreadsheet();

        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)

            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'RQNNUMBER')
            ->setCellValue('C1', 'RQNDATE')
            ->setCellValue('D1', 'POVENDOR')
            ->setCellValue('E1', 'POCUSTOMERDATE')
            ->setCellValue('F1', 'CUSTOMER NAME')
            ->setCellValue('G1', 'CONRACT NO')
            ->setCellValue('H1', 'CONTRACT DESC')
            ->setCellValue('I1', 'PROJECT NO')
            ->setCellValue('J1', 'CRM NO')
            ->setCellValue('K1', 'CRM DESC')
            ->setCellValue('L1', 'CRM DATE')
            ->setCellValue('M1', 'ITEM NO')
            ->setCellValue('N1', 'QTY')
            ->setCellValue('O1', 'UOM')
            ->setCellValue('P1', 'STATUS');

        $rows = 2;
        // tulis data mobil ke cell
        $no = 1;
        foreach ($pr_data as $data) {
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
            $dd = substr($data['RQNDATE'], 6, 2);
            $mm = substr($data['RQNDATE'], 4, 2);
            $yyyy = substr($data['RQNDATE'], 0, 4);
            $rqndate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['CRMREQDATE'], 6, 2);
            $mm = substr($data['CRMREQDATE'], 4, 2);
            $yyyy = substr($data['CRMREQDATE'], 0, 4);
            $reqdate = $mm . '/' . $dd . '/' . $yyyy;

            $dd = substr($data['PODATECUST'], 6, 2);
            $mm = substr($data['PODATECUST'], 4, 2);
            $yyyy = substr($data['PODATECUST'], 0, 4);
            $pocustdate = $mm . '/' . $dd . '/' . $yyyy;


            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $rows, $no++)
                ->setCellValue('B' . $rows, $data['RQNNUMBER'])
                ->setCellValue('C' . $rows, trim($rqndate))
                ->setCellValue('D' . $rows, $data['PONUMBERCUST'])
                ->setCellValue('E' . $rows, trim($pocustdate))

                ->setCellValue('F' . $rows, $data['NAMECUST'])
                ->setCellValue('G' . $rows, $data['CONTRACT'])
                ->setCellValue('H' . $rows, $data['CTDESC'])
                ->setCellValue('I' . $rows, $data['PROJECT'])
                ->setCellValue('J' . $rows, $data['CRMNO'])
                ->setCellValue('K' . $rows, trim($reqdate))
                ->setCellValue('L' . $rows, $data['ORDERDESC'])
                ->setCellValue('M' . $rows, $data['MATERIALNO'])
                ->setCellValue('N' . $rows, $data['QTY'])
                ->setCellValue('O' . $rows, $data['STOCKUNIT'])
                ->setCellValue('P' . $rows, $postingstatus);

            $rows++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Requisition_data';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
