<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Salesorder_model;

//use App\Controllers\AdminController;

class SalesOrder extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $audtuser;
    private $db_name;
    private $cart;
    public function __construct()
    {
        //parent::__construct();
        helper(['form', 'url']);
        $this->db_name = \Config\Database::connect();
        $this->cart = \Config\Services::cart();

        $this->LoginModel = new Login_model();
        $this->AdministrationModel = new Administration_model();
        $this->NotifModel = new Notif_model();
        $this->SalesorderModel = new Salesorder_model();

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
                $activenavd = 'salesorder';
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
        // Remove All Session filter
        session()->remove('success');
        session()->set('success', '0');
        session()->remove('cari');
        session()->remove('from_date');
        session()->remove('to_date');
        // Remove Session Delete
        session()->remove('manager');
        session()->remove('salesman');
        session()->remove('cust_email');
        session()->remove('po_cust');
        session()->remove('crm_no');
        session()->remove('req_date');
        session()->remove('ord_desc');
        session()->remove('so_remarks');
        // Clear the shopping cart
        $this->cart->destroy();

        $data = array(
            'csruniq' => '',
            'csr_uniq' => '',
            'ct_no' => '',
            'ct_desc' => '',
            'ct_staffcode' => '',
            'ct_manager' => '',
            'ct_salesperson' => '',
            'ct_custno' => '',
            'ct_custname' => '',
            'ct_email' => '',
            'prj_no' => '',
            'prj_desc' => '',
            'po_cust' => '',
            'prj_startdate' => '',
            'crm_no' => '',
            'req_date' => '',
            'order_desc' => '',
            'order_remarks' => '',
            'csropen_items' => '',
            'form_action' => base_url("salesorder/save_salesorder"),
            'validation' => \Config\Services::validation(),
            'cart' => $this->cart,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update($csruniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        session()->set('csruniq', $csruniq);
        // Clear the shopping cart
        $this->cart->destroy();
        $getcsropen = $this->SalesorderModel->get_csr_open($csruniq);
        $crmpodate = substr($getcsropen['PODATECUST'], 4, 2) . "/" . substr($getcsropen['PODATECUST'], 6, 2) . "/" .  substr($getcsropen['PODATECUST'], 0, 4);
        $crmreqdate = substr($getcsropen['CRMREQDATE'], 4, 2) . '/' . substr($getcsropen['CRMREQDATE'], 6, 2) . '/' . substr($getcsropen['CRMREQDATE'], 0, 4);
        session()->set('crm_no', trim($getcsropen['CRMNO']));
        session()->set('req_date', $crmreqdate);
        session()->set('ord_desc', trim($getcsropen['ORDERDESC']));
        session()->set('so_remarks', trim($getcsropen['CRMREMARKS']));
        session()->set('manager', trim($getcsropen['MANAGER']));
        session()->set('salesman', trim($getcsropen['SALESNAME']));
        session()->set('cust_email', trim($getcsropen['EMAIL1CUST']));
        session()->set('po_cust', trim($getcsropen['PONUMBERCUST']));
        $getcsrl_by_id = $this->SalesorderModel->get_csrl_open($csruniq);
        $data = array(
            'csruniq' => $csruniq,
            'csr_uniq' => $getcsropen['CSRUNIQ'],
            'ct_no' => trim($getcsropen['CONTRACT']),
            'ct_desc' => trim($getcsropen['CTDESC']),
            'ct_staffcode' => trim($getcsropen['MANAGER']),
            'ct_manager' => trim($getcsropen['MANAGER']),
            'ct_salesperson' => trim($getcsropen['SALESNAME']),
            'ct_custno' => trim($getcsropen['CUSTOMER']),
            'ct_custname' => trim($getcsropen['NAMECUST']),
            'ct_email' => trim($getcsropen['EMAIL1CUST']),
            'prj_no' => trim($getcsropen['PROJECT']),
            'prj_desc' => trim($getcsropen['PRJDESC']),
            'po_cust' => trim($getcsropen['PONUMBERCUST']),
            'prj_startdate' => $crmpodate,
            'crm_no' => trim($getcsropen['CRMNO']),
            'req_date' => $crmreqdate,
            'order_desc' => trim($getcsropen['ORDERDESC']),
            'order_remarks' => trim($getcsropen['CRMREMARKS']),
            'csropen_items' => $getcsrl_by_id,
            'form_action' => base_url("salesorder/update_salesorder"),
            'validation' => \Config\Services::validation(),
            'cart' => $this->cart,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function selectcontract($ct_no = '')
    {
        if (!empty(session()->get('csruniq'))) {
            $action = base_url("salesorder/update_salesorder");
            $csruniq = session()->get('csruniq');
        } else {
            $action = base_url("salesorder/save_salesorder");
            $csruniq = '';
        }

        if ($ct_no == '') {
            $data = array(
                'csruniq' => $csruniq,
                'csr_uniq' => '',
                'ct_no' => '',
                'ct_desc' => '',
                'ct_staffcode' => '',
                'ct_manager' => '',
                'ct_salesperson' => '',
                'ct_custno' => '',
                'ct_custname' => '',
                'ct_email' => '',
                'prj_no' => '',
                'prj_desc' => '',
                'po_cust' => '',
                'prj_startdate' => '',
                'crm_no' => '',
                'req_date' => '',
                'order_desc' => '',
                'order_remarks' => '',
                'csropen_items' => '',
                'form_action' => $action,
                'validation' => \Config\Services::validation(),
                'cart' => $this->cart,

            );
        } else {
            if (!empty(session()->get('salesman'))) {
                $getcsrl_by_id = $this->SalesorderModel->get_csrl_open(session()->get('csruniq'));
            } else {
                $getcsrl_by_id = '';
            }
            $row = $this->SalesorderModel->get_contract_by_id($ct_no);
            if ($row) {
                $data = array(
                    'csruniq' => $csruniq,
                    'csr_uniq' => '',
                    'ct_no' => trim($row['CONTRACT']),
                    'ct_desc' => trim($row['DESC']),
                    'ct_staffcode' => trim($row['MANAGER']),
                    'ct_manager' => trim($row['MANAGER']),
                    'ct_salesperson' => trim($row['NAME']),
                    'ct_custno' => trim($row['CUSTOMER']),
                    'ct_custname' => trim($row['NAMECUST']),
                    'ct_email' => trim($row['EMAIL1']),
                    'prj_no' => '',
                    'prj_desc' => '',
                    'po_cust' => '',
                    'prj_startdate' => '',
                    'crm_no' => '',
                    'req_date' => '',
                    'order_desc' => '',
                    'order_remarks' => '',
                    'csropen_items' => $getcsrl_by_id,
                    'form_action' => $action,
                    'validation' => \Config\Services::validation(),
                    'cart' => $this->cart,

                );
            }
        }
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function selectproject($ct_no = '', $prj_no = '')
    {
        if (!empty(session()->get('csruniq'))) {
            $action = base_url("salesorder/update_salesorder");
            $csruniq = session()->get('csruniq');
        } else {
            $action = base_url("salesorder/save_salesorder");
            $csruniq = '';
        }

        if ($prj_no == '') {
            $data = array(
                'csruniq' => $csruniq,
                'csr_uniq' => '',
                'ct_no' => '',
                'ct_desc' => '',
                'ct_staffcode' => '',
                'ct_manager' => '',
                'ct_salesperson' => '',
                'ct_custno' => '',
                'ct_custname' => '',
                'ct_email' => '',
                'prj_no' => '',
                'prj_desc' => '',
                'po_cust' => '',
                'prj_startdate' => '',
                'crm_no' => '',
                'req_date' => '',
                'order_desc' => '',
                'order_remarks' => '',
                'csropen_items' => '',
                'form_action' => $action,
                'validation' => \Config\Services::validation(),
                'cart' => $this->cart,

            );
        } else {
            if (!empty($csruniq)) {
                $getcsrl_by_id = $this->SalesorderModel->get_csrl_open($csruniq);
            } else {
                $getcsrl_by_id = '';
            }
            $row = $this->SalesorderModel->get_project_by_contract($ct_no, $prj_no);
            if ($row) {
                $podate = $row['PODATE'];
                $dd = substr($podate, 6, 2);
                $mm = substr($podate, 4, 2);
                $yyyy = substr($podate, 0, 4);
                $n_podate = $mm . '/' . $dd . '/' . $yyyy;
                $data = array(
                    'csruniq' => $csruniq,
                    'csr_uniq' => '',
                    'ct_no' => trim($row['CONTRACT']),
                    'ct_desc' => trim($row['DESC']),
                    'ct_staffcode' => trim($row['MANAGER']),
                    'ct_manager' => trim($row['MANAGER']),
                    'ct_salesperson' => trim($row['NAME']),
                    'ct_custno' => trim($row['CUSTOMER']),
                    'ct_custname' => trim($row['NAMECUST']),
                    'ct_email' => trim($row['EMAIL1']),
                    'prj_no' => trim($row['PROJECT']),
                    'prj_desc' => trim($row['Prj_Desc']),
                    'po_cust' => trim($row['PONUMBER']),
                    'prj_startdate' => $n_podate,
                    'crm_no' => '',
                    'req_date' => '',
                    'order_desc' => '',
                    'order_remarks' => '',
                    'csropen_items' => $getcsrl_by_id,
                    'form_action' => $action,
                    'validation' => \Config\Services::validation(),
                    'cart' => $this->cart,

                );
            }
        }
        //return view('welcome_message');
        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_so_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function form_select_contractopen()
    {


        $data['contractopen'] = $this->SalesorderModel->list_contract_open();
        $data['form_action'] = base_url("salesorder/choosecontract");
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_add_contract', $data);
    }

    public function form_select_salesman($ct_no)
    {

        $data['contract'] = $ct_no;
        $data['salesman'] = $this->SalesorderModel->list_salesman();
        $data['form_action'] = base_url("salesorder/choosesalesman");
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_add_salesman', $data);
    }

    public function form_input_email($ct_no, $prj_no = '')
    {
        $csruniq = session()->get('csruniq');
        if (!empty($csruniq)) {
            $getcsropen = $this->SalesorderModel->get_csr_open($csruniq);
            $cust_email = trim($getcsropen['EMAIL1CUST']);
        } else {
            $cust_email = '';
        }

        $data['contract'] = $ct_no;
        $data['project'] = $prj_no;
        $data['cust_email'] = $cust_email;
        $data['form_action'] = base_url("salesorder/getemail");
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_input_email', $data);
    }

    public function form_input_pocust($ct_no, $prj_no = '')
    {

        $data['contract'] = $ct_no;
        $data['project'] = $prj_no;
        $data['form_action'] = base_url("salesorder/getpocust");
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_input_pocust', $data);
    }


    public function form_input_item($ct_no, $prj_no, $csruniq = '')
    {
        $data = array(
            'contract' => $ct_no,
            'project' => $prj_no,
            'rowid' => '',
            'so_service' => '',
            'inventory_no' => '',
            'material_no' => '',
            'so_qty' => '',
            'so_uom' => '',
            'item_data' => $this->SalesorderModel->get_icitem(),
            'form_action' => base_url("salesorder/getitem"),
        );
        echo view('crm/ajax_input_item', $data);
    }

    //Update Item belum jalan sempurna
    /*public function form_update_item($ct_no, $prj_no, $so_service = '', $inventory_no = '', $material_no = '', $so_qty = '', $so_uom = '')
    {
        $data = array(
            'contract' => $ct_no,
            'project' => $prj_no,
            'rowid' => '',
            'so_service' => $so_service,
            'inventory_no' => $inventory_no,
            'material_no' => $material_no,
            'so_qty' => $so_qty,
            'so_uom' => $so_uom,
            'item_data' => $this->SalesorderModel->get_icitem(),
            'form_action' => base_url("salesorder/getitem"),
        );
        echo view('crm/ajax_input_item', $data);
    }*/


    public function form_select_project_by_contract($contract = '')
    {


        $data['projectbycontract'] = $this->SalesorderModel->list_project_by_contract($contract);
        $data['form_action'] = base_url("salesorder/chooseproject");
        $data['ct_no'] = $contract;
        //echo view('crm/ajax_add_contract', $data);
        echo view('crm/ajax_add_project', $data);
    }

    public function choosecontract()
    {
        session()->remove('manager');
        session()->remove('salesman');
        session()->remove('cust_email');
        session()->remove('po_cust');
        if (null == ($this->request->getPost('contract'))) {
            $ct_no = "contract not found";
        } else {
            $ct_no = $this->request->getPost('contract');
            $csruniq = $this->request->getPost('csruniq');
            if (!empty($csruniq)) :
                session()->set('csruniq', $csruniq);
            endif;
        }

        return redirect()->to(base_url('salesorder/selectcontract/' . $ct_no));
    }

    public function choosesalesman()
    {
        session()->remove('success');
        if (null == ($this->request->getPost('contract'))) {
            $ct_no = "contract not found";
        } else {
            $ct_no = $this->request->getPost('contract');
            $staffcode = $this->request->getPost('manager');
            $sales = $this->SalesorderModel->get_salesman_by_id($staffcode);
            session()->set('manager', trim($sales['STAFFCODE']));
            session()->set('salesman', trim($sales['SALESNAME']));
        }

        return redirect()->to(base_url('salesorder/selectcontract/' . $ct_no));
    }

    public function getemail()
    {
        session()->remove('success');
        if (null == ($this->request->getPost('contract'))) {
            $ct_no = "contract not found";
        } else {
            $ct_no = $this->request->getPost('contract');
            $prj_no = $this->request->getPost('project');
            $email_manual = $this->request->getPost('email_manual');
            session()->set('cust_email', $email_manual);
        }
        if (empty($prj_no)) {
            return redirect()->to(base_url('salesorder/selectcontract/' . $ct_no));
        } else if (!empty($prj_no)) {
            return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/'));
        }
    }

    public function getpocust()
    {
        session()->remove('success');
        if (null == ($this->request->getPost('contract'))) {
            $ct_no = "contract not found";
        } else {
            $ct_no = $this->request->getPost('contract');
            $prj_no = $this->request->getPost('project');
            $po_cust = $this->request->getPost('po_cust');
            session()->set('po_cust', $po_cust);
        }

        return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/'));
    }

    public function getitem()
    {
        if (null == ($this->request->getPost('contract')) and null == ($this->request->getPost('project'))) {
            $ct_no = "contract not found";
        } else {
            $ct_no = $this->request->getPost('contract');
            $csruniq = $this->request->getPost('csruniq');
            $prj_no = $this->request->getPost('project');
            $so_service = $this->request->getPost('so_service');
            $inventory_no = $this->request->getPost('inventory_no');
            $material_no = $this->request->getPost('material_no');
            $so_qty = $this->request->getPost('so_qty');
            $so_uom = $this->request->getPost('so_uom');
            $getitem = $this->SalesorderModel->get_icitem_by_id($inventory_no);
            $this->cart->insert(array(
                //'inventory_no' => $inventory_no,
                //'so_service' => $so_service,
                //'material_no' => $material_no,
                //'so_qty' => $so_qty,
                //'so_uom' => $so_uom
                'id'      => $inventory_no,
                'qty'     => $so_qty,
                'price'   => '1',
                'name'    => 'Item Description Sage',
                'options' => array('so_service' => $so_service, 'material_no' => $material_no, 'itemdesc' => $getitem['ITEMDESC'], 'so_uom' => $so_uom)
            ));
        }

        return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/' . '/' . $csruniq));
    }


    // delete item Cart
    public function delete_item_cart($ct_no = 0, $prj_no = 0, $rowid)
    {
        // Remove an item using its `rowid`
        $this->cart->remove($rowid);
        return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/'));
    }

    // delete item open
    public function delete_item_open($ct_no = 0, $prj_no = 0, $csrluniq = 0)
    {
        // Remove an item in table webot_CSRL when update
        $this->SalesorderModel->delete_item_csrlopen($csrluniq);
        return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/'));
    }



    public function chooseproject()
    {
        if (null == ($this->request->getPost('contract')) && ($this->request->getPost('contract')) == null) {
            $ct_no = "contract not found";
            $prj_no = "";
        } else if (($this->request->getPost('contract')) <> "" && ($this->request->getPost('contract')) == null) {
            $ct_no = $this->request->getPost('contract');
            $prj_no = "";
        } else if (($this->request->getPost('contract')) <> "" && ($this->request->getPost('contract')) <> "") {
            $ct_no = $this->request->getPost('contract');
            $prj_no = $this->request->getPost('project');
        }

        return redirect()->to(base_url('salesorder/selectproject/' . $ct_no . '/' . $prj_no . '/'));
    }


    public function save_salesorder()
    {
        if (!$this->validate([
            'ct_no' => 'required|min_length[3]',
            'ct_desc' => 'required',
            'ct_salesperson' => 'required',
            'ct_custno' => 'required',
            'ct_email' => 'required|valid_email',
            'ct_namecust' => 'required',
            'prj_no' => 'required|min_length[3]',
            'prj_desc' => 'required',
            'po_cust' => 'required',
            'prj_startdate' => 'required',
            'crm_no' => 'required|min_length[1]',
            'req_date' => 'required',
            'ord_desc' => 'required',
            'so_remarks' => 'required|min_length[3]',

        ])) {
            $ct_no = $this->request->getPost('ct_no');
            $prj_no = $this->request->getPost('prj_no');
            if (($ct_no == "") and ($prj_no == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/salesorder'))->withInput();
            } else if (($ct_no <> "") and $prj_no == "") {
                session()->set('success', '-1');
                return redirect()->to(base_url('/salesorder/selectcontract/' . $ct_no))->withInput();
            } else if (($ct_no <> "") and $prj_no <> "") {
                session()->set('success', '-1');
                return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no))->withInput();
                //return redirect()->back()->withInput();
            }

            //echo $prj_no;
            //echo $this->validate;

        } else {

            if ($_POST['form_save'] == 'crm_save') {
                session()->remove('success');
                $ct_no = $this->request->getPost('ct_no');
                $prj_no = $this->request->getPost('prj_no');
                $crm_no = $this->request->getPost('crm_no');
                $req_date = $this->request->getPost('req_date');
                $ord_desc = $this->request->getPost('ord_desc');
                $so_remarks = $this->request->getPost('so_remarks');
                session()->set('crm_no', $crm_no);
                session()->set('req_date', $req_date);
                session()->set('ord_desc', $ord_desc);
                session()->set('so_remarks', $so_remarks);
                return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no));
            } else if ($_POST['form_save'] == 'so_save') {
                if (!$this->validate(['chk_item' => 'greater_than[0]'])) {
                    $ct_no = $this->request->getPost('ct_no');
                    $prj_no = $this->request->getPost('prj_no');
                    session()->set('success', '-1');
                    return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no))->withInput();
                } else {
                    // Check Status Mail Notification
                    $sender = $this->AdministrationModel->get_mailsender();
                    $prj_startdate = $this->request->getPost('prj_startdate');
                    $req_date = $this->request->getPost('req_date');
                    $podatecust = substr($prj_startdate, 6, 4)  . "" . substr($prj_startdate, 0, 2) . "" . substr($prj_startdate, 3, 2);
                    $crmreqdate = substr($req_date, 6, 4) . "" . substr($req_date, 0, 2) . "" . substr($req_date, 3, 2);
                    $groupuser = 2;
                    $data = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'CSRKEY' => '0-' . $this->request->getPost('ct_no') . $this->request->getPost('prj_no'),
                        'CONTRACT' => $this->request->getPost('ct_no'),
                        'CTDESC' => $this->request->getPost('ct_desc'),
                        'MANAGER' => $this->request->getPost('ct_manager'),
                        'SALESNAME' => $this->request->getPost('ct_salesperson'),
                        'PROJECT' => $this->request->getPost('prj_no'),
                        'PRJDESC' => $this->request->getPost('prj_desc'),
                        'PONUMBERCUST' => $this->request->getPost('po_cust'),
                        'PODATECUST' => $podatecust,
                        'CUSTOMER' => $this->request->getPost('ct_custno'),
                        'NAMECUST' => $this->request->getPost('ct_namecust'),
                        'EMAIL1CUST' => $this->request->getPost('ct_email'),
                        'CRMNO' => $this->request->getPost('crm_no'),
                        'CRMREQDATE' => $crmreqdate,
                        'ORDERDESC' => $this->request->getPost('ord_desc'),
                        'CRMREMARKS' => $this->request->getPost('so_remarks'),
                        'CSRREPLACE' => 0,
                        'OTPROCESS' => $groupuser,
                        'POSTINGSTAT' => 0,
                        'OFFLINESTAT' => $sender['OFFLINESTAT'],
                    );
                    $contract = $this->request->getPost('ct_no');
                    $project = $this->request->getPost('prj_no');
                    $custno = $this->request->getPost('ct_custno');
                    $crmno = $this->request->getPost('crm_no');
                    $getcsruniq = $this->SalesorderModel->get_csruniq_open($contract, $project, $custno);
                    if (!empty($getcsruniq['CSRKEY']) and $getcsruniq['CHKCSRL'] > 0 and $getcsruniq['CSRKEY'] == '0-' . $contract . $project) {
                        session()->set('success', '-1');
                        return redirect()->to(base_url('/salesorder/selectproject/' . $contract . '/' . $project));
                        session()->remove('success');
                    } else if (!empty($getcsruniq['CSRKEY']) and $getcsruniq['CHKCSRL'] == 0 and $getcsruniq['CSRKEY'] == '0-' . $contract . $project) {

                        foreach ($this->cart->contents() as $items) :
                            $datal = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                                'AUDTORG' => trim($this->audtuser['AUDTORG']),
                                'CSRUNIQ' => $getcsruniq['CSRUNIQ'],
                                'CONTRACT' => $getcsruniq['CONTRACT'],
                                'PROJECT' => $getcsruniq['PROJECT'],
                                'SERVICETYPE' => $items['options']['so_service'],
                                'ITEMNO' => $items['id'],
                                'MATERIALNO' => $items['options']['material_no'],
                                'ITEMDESC' => trim($items['options']['itemdesc']),
                                'STOCKUNIT' => $items['options']['so_uom'],
                                'QTY' => $items['qty'],
                            );
                            $csrl_insert = $this->SalesorderModel->csrline_insert($datal);
                        endforeach;
                        if ($csrl_insert) {
                            $this->cart->destroy();
                            $bysetting = 1;
                            session()->set('success', '1');
                            return redirect()->to(base_url('/salesorder/csropenview/' . $getcsruniq['CSRUNIQ'] . '/' . $bysetting));
                            session()->remove('success');
                        }
                    } else if (empty($getcsruniq['CSRKEY'])) {
                        $csr_insert = $this->SalesorderModel->csr_insert($data);
                        if ($csr_insert) {
                            $getcsruniq = $this->SalesorderModel->get_csruniq_open($contract, $project, $custno);
                            foreach ($this->cart->contents() as $items) :
                                $datal = array(
                                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                    'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                                    'AUDTORG' => trim($this->audtuser['AUDTORG']),
                                    'CSRUNIQ' => $getcsruniq['CSRUNIQ'],
                                    'CONTRACT' => $getcsruniq['CONTRACT'],
                                    'PROJECT' => $getcsruniq['PROJECT'],
                                    'SERVICETYPE' => $items['options']['so_service'],
                                    'ITEMNO' => $items['id'],
                                    'MATERIALNO' => $items['options']['material_no'],
                                    'ITEMDESC' => trim($items['options']['itemdesc']),
                                    'STOCKUNIT' => $items['options']['so_uom'],
                                    'QTY' => $items['qty'],
                                );

                                $csrl_insert = $this->SalesorderModel->csrline_insert($datal);
                            endforeach;
                        }
                        $this->cart->destroy();
                        $bysetting = 1;
                        session()->set('success', '1');
                        return redirect()->to(base_url('/salesorder/csropenview/' . $getcsruniq['CSRUNIQ'] . '/' . $bysetting));
                        session()->remove('success');
                    } else {
                        session()->set('success', '-1');
                        return redirect()->to(base_url('/salesorder/selectproject/' . $contract . '/' . $project));
                        session()->remove('success');
                    }
                }
            }
        }
    }



    public function update_salesorder()
    {
        if (!$this->validate([
            'ct_no' => 'required|min_length[3]',
            'ct_desc' => 'required',
            'ct_salesperson' => 'required',
            'ct_custno' => 'required',
            'ct_email' => 'required|valid_email',
            'ct_namecust' => 'required',
            'prj_no' => 'required|min_length[3]',
            'prj_desc' => 'required',
            'po_cust' => 'required',
            'prj_startdate' => 'required',
            'crm_no' => 'required|min_length[1]',
            'req_date' => 'required',
            'ord_desc' => 'required',
            'so_remarks' => 'required|min_length[3]',

        ])) {
            $ct_no = $this->request->getPost('ct_no');
            $prj_no = $this->request->getPost('prj_no');
            if (($ct_no == "") and ($prj_no == "")) {
                session()->set('success', '-1');
                return redirect()->to(base_url('/salesorder'))->withInput();
            } else if (($ct_no <> "") and $prj_no == "") {
                session()->set('success', '-1');
                return redirect()->to(base_url('/salesorder/selectcontract/' . $ct_no))->withInput();
            } else if (($ct_no <> "") and $prj_no <> "") {
                session()->set('success', '-1');
                return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no))->withInput();
                //return redirect()->back()->withInput();
            }

            //echo $prj_no;
            //echo $this->validate;

        } else {
            if ($_POST['form_save'] == 'crm_save') {
                session()->remove('success');
                $ct_no = $this->request->getPost('ct_no');
                $prj_no = $this->request->getPost('prj_no');
                $crm_no = $this->request->getPost('crm_no');
                $req_date = $this->request->getPost('req_date');
                $ord_desc = $this->request->getPost('ord_desc');
                $so_remarks = $this->request->getPost('so_remarks');
                session()->set('crm_no', $crm_no);
                session()->set('req_date', $req_date);
                session()->set('ord_desc', $ord_desc);
                session()->set('so_remarks', $so_remarks);
                return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no));
            } else if ($_POST['form_save'] == 'so_save') {
                $csruniq = $this->request->getPost('csruniq');
                $cart_item = $this->request->getPost('chk_item');
                $chk_csrl = $this->SalesorderModel->chk_csrl_open($csruniq);
                if ($cart_item > 0 or $chk_csrl['CHKITEM'] > 0) {

                    // Check Status Mail Notification
                    $prj_startdate = $this->request->getPost('prj_startdate');
                    $req_date = $this->request->getPost('req_date');
                    $podatecust = substr($prj_startdate, 6, 4)  . "" . substr($prj_startdate, 0, 2) . "" . substr($prj_startdate, 3, 2);
                    $crmreqdate = substr($req_date, 6, 4) . "" . substr($req_date, 0, 2) . "" . substr($req_date, 3, 2);
                    $contract = $this->request->getPost('ct_no');
                    $project = $this->request->getPost('prj_no');
                    $custno = $this->request->getPost('ct_custno');
                    $getcsruniq = $this->SalesorderModel->get_csruniq_open($contract, $project, $custno);
                    $groupuser = 2;

                    $data = array(
                        'AUDTDATE' => $this->audtuser['AUDTDATE'],
                        'AUDTTIME' => $this->audtuser['AUDTTIME'],
                        'AUDTUSER' => $this->audtuser['AUDTUSER'],
                        'AUDTORG' => $this->audtuser['AUDTORG'],
                        'CSRKEY' => '0-' . $this->request->getPost('ct_no') . $this->request->getPost('prj_no'),
                        'CONTRACT' => $this->request->getPost('ct_no'),
                        'CTDESC' => $this->request->getPost('ct_desc'),
                        'MANAGER' => $this->request->getPost('ct_manager'),
                        'SALESNAME' => $this->request->getPost('ct_salesperson'),
                        'PROJECT' => $this->request->getPost('prj_no'),
                        'PRJDESC' => $this->request->getPost('prj_desc'),
                        'PONUMBERCUST' => $this->request->getPost('po_cust'),
                        'PODATECUST' => $podatecust,
                        'CUSTOMER' => $this->request->getPost('ct_custno'),
                        'NAMECUST' => $this->request->getPost('ct_namecust'),
                        'EMAIL1CUST' => $this->request->getPost('ct_email'),
                        'CRMNO' => $this->request->getPost('crm_no'),
                        'CRMREQDATE' => $crmreqdate,
                        'ORDERDESC' => $this->request->getPost('ord_desc'),
                        'CRMREMARKS' => $this->request->getPost('so_remarks'),
                        'CSRREPLACE' => 0,
                        'OTPROCESS' => $groupuser,
                        'POSTINGSTAT' => 0,
                        'OFFLINESTAT' => 1,
                    );

                    //print_r($data_notif);
                    $csr_update = $this->SalesorderModel->csr_update($csruniq, $data);
                    if ($csr_update) {
                        $getcsruniq = $this->SalesorderModel->get_csruniq_open($contract, $project, $custno);
                        foreach ($this->cart->contents() as $items) :
                            $datal = array(
                                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                                'AUDTUSER' => trim($this->audtuser['AUDTUSER']),
                                'AUDTORG' => trim($this->audtuser['AUDTORG']),
                                'CSRUNIQ' => $getcsruniq['CSRUNIQ'],
                                'CONTRACT' => $getcsruniq['CONTRACT'],
                                'PROJECT' => $getcsruniq['PROJECT'],
                                'SERVICETYPE' => $items['options']['so_service'],
                                'ITEMNO' => $items['id'],
                                'MATERIALNO' => $items['options']['material_no'],
                                'ITEMDESC' => trim($items['options']['itemdesc']),
                                'STOCKUNIT' => $items['options']['so_uom'],
                                'QTY' => $items['qty'],
                            );

                            $csrl_insert = $this->SalesorderModel->csrline_insert($datal);
                        endforeach;
                    }
                    $this->cart->destroy();
                    //session()->setFlashdata('messageerror', 'Create Record Failed');
                    session()->set('success', '1');
                    return redirect()->to(base_url('/salesorder/csropenview/' . $getcsruniq['CSRUNIQ']));
                    session()->remove('success');
                } else {
                    $ct_no = $this->request->getPost('ct_no');
                    $prj_no = $this->request->getPost('prj_no');
                    session()->set('success', '-1');
                    return redirect()->to(base_url('/salesorder/selectproject/' . $ct_no . '/' . $prj_no));
                }
            }
        }
    }

    public function csropenview($csruniq)
    {
        session()->remove('success');
        session()->set('success', '0');
        $getcsropen = $this->SalesorderModel->get_csr_open($csruniq);
        $getcsrlopen = $this->SalesorderModel->get_csrl_open($csruniq);
        if ($getcsropen['POSTINGSTAT'] == 0) {
            $data = array(
                'csropen_data' =>  $getcsropen,
                'csrlopen_data' =>  $getcsrlopen,
                'link_action' => 'salesorder/posting/',
                'btn_color' => 'bg-blue',
                'button' => 'Posting & Send Notification',
            );
        } else if ($getcsropen['POSTINGSTAT'] == 1) {

            $data = array(
                'csropen_data' =>  $getcsropen,
                'csrlopen_data' =>  $getcsrlopen,
                'link_action' => 'salesorder/sendnotif/',
                'btn_color' => 'bg-orange',
                'button' => 'Send Notification Manually',
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('crm/data_csr_view', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function posting($csruniq)
    {
        $getcsropen = $this->SalesorderModel->get_csr_open($csruniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 2;
        $data = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'CSRUNIQ' => $csruniq,
            'CONTRACT' => $getcsropen['CONTRACT'],
            'CTDESC' => $getcsropen['CTDESC'],
            'MANAGER' => $getcsropen['MANAGER'],
            'SALESNAME' => $getcsropen['SALESNAME'],
            'PROJECT' => $getcsropen['PROJECT'],
            'PRJDESC' => $getcsropen['PRJDESC'],
            'PONUMBERCUST' => $getcsropen['PONUMBERCUST'],
            'PODATECUST' => $getcsropen['PODATECUST'],
            'CUSTOMER' => $getcsropen['CUSTOMER'],
            'NAMECUST' => $getcsropen['NAMECUST'],
            'EMAIL1CUST' => $getcsropen['EMAIL1CUST'],
            'CRMNO' => $getcsropen['CRMNO'],
            'CRMREQDATE' => $getcsropen['CRMREQDATE'],
            'ORDERDESC' => $getcsropen['ORDERDESC'],
            'SERVICETYPE' => $getcsropen['SERVICETYPE'],
            'CRMREMARKS' => $getcsropen['CRMREMARKS'],
            'ITEMNO' => $getcsropen['ITEMNO'],
            'MATERIALNO' => $getcsropen['MATERIALNO'],
            'STOCKUNIT' => $getcsropen['STOCKUNIT'],
            'QTY' => $getcsropen['QTY'],
        );


        // Untuk Fungsi Posting & Send Notif
        $ot_insert = $this->SalesorderModel->ot_insert($data);
        if ($ot_insert) {
            if ($sender['OFFLINESTAT'] == 0) {
                //Untuk Update Status Posting CSR
                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'POSTINGSTAT' => 1,
                    'OFFLINESTAT' => 0,
                );
                //inisiasi proses kirim ke group

                $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

                foreach ($notiftouser_data as $sendto_user) {
                    $data_email = array(
                        'hostname'       => $sender['HOSTNAME'],
                        'sendername'       => $sender['SENDERNAME'],
                        'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                        'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                        'ssl'       => $sender['SSL'],
                        'smtpport'       => $sender['SMTPPORT'],
                        'to_email' => $sendto_user['EMAIL'],
                        'subject' => 'Pending Sales Order Allert. Contract No : ' . $getcsropen['CONTRACT'],
                        'message' =>    'Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
        
        Please to follow up CRM No :' . $getcsropen['CRMNO'] . ' / Customer PO : ' . $getcsropen['PONUMBERCUST'] . ' from ' . $getcsropen['NAMECUST'] . ' is pending for you to request PR/PO.
        <br><br>
        You can access Order Tracking System Portal via the URL below:
        <br>
        <a href="http://jktsms025:8082/Andritz-sage/public" target="blank">http://jktsms025:8082/Andritz-sage/public</a>
        <br>
        Thanks for your cooperation. 
        <br><br>
        Order Tracking Administrator',
                    );

                    $sending_mail = $this->send($data_email);

                    if ($sending_mail) {
                        $data_notif = array(
                            'FROM_USER' => $this->header_data['usernamelgn'],
                            'FROM_EMAIL' => $this->header_data['emaillgn'],
                            'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                            'TO_USER' => $sendto_user['USERNAME'],
                            'TO_EMAIL' => $sendto_user['EMAIL'],
                            'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                            'SUBJECT' => 'Pending Sales Order Allert. Contract No : ' . $getcsropen['CONTRACT'],
                            'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
            
                Please to follow up CRM No :' . $getcsropen['CRMNO'] . ' / Customer PO : ' . $getcsropen['PONUMBERCUST']  . ' from ' . $getcsropen['NAMECUST'] . ' is pending for you to request PR/PO.
                <br><br>
                You can access Order Tracking System Portal via the URL below:
                <br>
                <a href="http://jktsms025:8082/Andritz-sage/public" target="blank">http://jktsms025:8082/Andritz-sage/public</a>
                <br>
                Thanks for your cooperation. 
                <br><br>
                Order Tracking Administrator',

                            'SENDING_DATE' => $this->audtuser['AUDTDATE'],
                            'SENDING_TIME' => $this->audtuser['AUDTTIME'],
                            'UPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                            'UPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                            'SENDERUPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                            'SENDERUPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                            'IS_READ' => 0,
                            'IS_ARCHIVED' => 0,
                            'IS_TRASHED' => 0,
                            'IS_DELETED' => 0,
                            'IS_ATTACHED' => 0,
                            'IS_STAR' => 0,
                            'IS_READSENDER' => 1,
                            'IS_ARCHIVEDSENDER' => 0,
                            'IS_TRASHEDSENDER' => 0,
                            'IS_DELETEDSENDER' => 0,
                            'SENDING_STATUS' => 1,
                            'OTPROCESS' => $groupuser,
                            'UNIQPROCESS' => $getcsropen['CSRUNIQ'],
                        );
                        $this->NotifModel->mailbox_insert($data_notif);
                        $this->SalesorderModel->csr_post_update($csruniq, $data2);
                    }
                }
                //return redirect()->to(base_url('/salesorder'));
                //}
                //}

                session()->set('success', '1');
                return redirect()->to(base_url('/salesorderlist'));
                session()->remove('success');
            } else {
                $data2 = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'POSTINGSTAT' => 1,
                    'OFFLINESTAT' => 1,
                );
                $this->SalesorderModel->csr_post_update($csruniq, $data2);
                //session()->setFlashdata('messageerror', 'Create Record Failed');
                session()->set('success', '1');
                return redirect()->to(base_url('/salesorderlist'));
                session()->remove('success');
            }
        }
    }

    public function sendnotif($csruniq)
    {
        $getcsropen = $this->SalesorderModel->get_csr_open($csruniq);
        $sender = $this->AdministrationModel->get_mailsender();
        $groupuser = 2;
        //Untuk Update Status Posting CSR
        $data2 = array(
            'AUDTDATE' => $this->audtuser['AUDTDATE'],
            'AUDTTIME' => $this->audtuser['AUDTTIME'],
            'AUDTUSER' => $this->audtuser['AUDTUSER'],
            'AUDTORG' => $this->audtuser['AUDTORG'],
            'OFFLINESTAT' => 0,
        );
        //inisiasi proses kirim ke group

        $notiftouser_data = $this->NotifModel->get_sendto_user($groupuser);

        foreach ($notiftouser_data as $sendto_user) {
            $data_email = array(
                'hostname'       => $sender['HOSTNAME'],
                'sendername'       => $sender['SENDERNAME'],
                'senderemail'       => $sender['SENDEREMAIL'], // silahkan ganti dengan alamat email Anda
                'passwordemail'       => $sender['PASSWORDEMAIL'], // silahkan ganti dengan password email Anda
                'ssl'       => $sender['SSL'],
                'smtpport'       => $sender['SMTPPORT'],
                'to_email' => $sendto_user['EMAIL'],
                'subject' => 'Pending Sales Order Allert. Contract No : ' . $getcsropen['CONTRACT'],
                'message' =>    'Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>

Please to follow up CRM No :' . $getcsropen['CRMNO'] . ' / Customer PO : ' . $getcsropen['PONUMBERCUST'] . ' from ' . $getcsropen['NAMECUST'] . ' is pending for you to request PR/PO.
<br><br>
You can access Order Tracking System Portal via the URL below:
<br>
<a href="http://jktsms025:8082/Andritz-sage/public" target="blank">http://jktsms025:8082/Andritz-sage/public</a>
<br>
Thanks for your cooperation. 
<br><br>
Order Tracking Administrator',
            );

            $sending_mail = $this->send($data_email);

            if ($sending_mail) {
                $data_notif = array(
                    'FROM_USER' => $this->header_data['usernamelgn'],
                    'FROM_EMAIL' => $this->header_data['emaillgn'],
                    'FROM_NAME' => ucwords(strtolower($this->header_data['namalgn'])),
                    'TO_USER' => $sendto_user['USERNAME'],
                    'TO_EMAIL' => $sendto_user['EMAIL'],
                    'TO_NAME' => ucwords(strtolower($sendto_user['NAME'])),
                    'SUBJECT' => 'Pending Sales Order Allert. Contract No : ' . $getcsropen['CONTRACT'],
                    'MESSAGE' => ' Hello ' . ucwords(strtolower($sendto_user['NAME'])) . ',<br><br>
    
        Please to follow up CRM No :' . $getcsropen['CRMNO'] . ' / Customer PO : ' . $getcsropen['PONUMBERCUST']  . ' from ' . $getcsropen['NAMECUST'] . ' is pending for you to request PR/PO.
        <br><br>
        You can access Order Tracking System Portal via the URL below:
        <br>
        <a href="http://jktsms025:8082/Andritz-sage/public" target="blank">http://jktsms025:8082/Andritz-sage/public</a>
        <br>
        Thanks for your cooperation. 
        <br><br>
        Order Tracking Administrator',

                    'SENDING_DATE' => $this->audtuser['AUDTDATE'],
                    'SENDING_TIME' => $this->audtuser['AUDTTIME'],
                    'UPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                    'UPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                    'SENDERUPDATEDAT_DATE' => $this->audtuser['AUDTDATE'],
                    'SENDERUPDATEDAT_TIME' => $this->audtuser['AUDTTIME'],
                    'IS_READ' => 0,
                    'IS_ARCHIVED' => 0,
                    'IS_TRASHED' => 0,
                    'IS_DELETED' => 0,
                    'IS_ATTACHED' => 0,
                    'IS_STAR' => 0,
                    'IS_READSENDER' => 1,
                    'IS_ARCHIVEDSENDER' => 0,
                    'IS_TRASHEDSENDER' => 0,
                    'IS_DELETEDSENDER' => 0,
                    'SENDING_STATUS' => 1,
                    'OTPROCESS' => $groupuser,
                    'UNIQPROCESS' => $getcsropen['CSRUNIQ'],
                );
                $this->NotifModel->mailbox_insert($data_notif);
                $this->SalesorderModel->csr_post_update($csruniq, $data2);
            }
        }

        session()->set('success', '1');
        return redirect()->to(base_url('/salesorderlist'));
        session()->remove('success');
    }

    private function send($data_email)
    {
        $hostname           = $data_email['hostname'];
        $sendername         = $data_email['sendername'];
        $senderemail        = $data_email['senderemail'];
        $passwordemail      = $data_email['passwordemail'];
        $ssl                = $data_email['ssl'];
        $smtpport           = $data_email['smtpport'];
        $to                 = $data_email['to_email'];
        $subject            = $data_email['subject'];
        $message            = $data_email['message'];

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $hostname;
            $mail->SMTPAuth   = true;
            $mail->Username   = $senderemail; // silahkan ganti dengan alamat email Anda
            $mail->Password   = $passwordemail; // silahkan ganti dengan password email Anda
            $mail->SMTPSecure = $ssl;
            $mail->Port       = $smtpport;

            $mail->setFrom($senderemail, $sendername); // silahkan ganti dengan alamat email Anda
            $mail->addAddress($to);
            $mail->addReplyTo($senderemail, $sendername); // silahkan ganti dengan alamat email Anda
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            session()->setFlashdata('success', 'Send Email successfully');
            return redirect()->to(base_url('/salesorder'));
        } catch (Exception $e) {
            session()->setFlashdata('error', "Send Email failed. Error: " . $mail->ErrorInfo);
            return redirect()->to(base_url('/salesorder'));
        }
    }
}
