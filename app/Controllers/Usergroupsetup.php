<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Setup_model;
//use App\Models\Settingnavheader_model;


class Usergroupsetup extends BaseController
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
        $this->SetupModel = new Setup_model();
        //$this->SettingnavheaderModel = new Settingnavheader_model();
        if (empty(session()->get('keylog'))) {
            //Tidak Bisa menggunakan redirect kalau condition session di construct
            //return redirect()->to(base_url('/login'));
            header('Location: ' . base_url());
            exit();
        } else {
            $user = session()->get('username');
            $infouser = $this->LoginModel->datapengguna($user);
            $chksu = $this->LoginModel->datalevel($user);
            if ($chksu == 0) {
                //header("HTTP/1.1 401 Unauthorized"); //Status Code 401 = Unauthorized
                header('Location: ' . base_url('administration'));
                exit();
            } else if (session()->get('keylog') != $infouser['passlgn']) {
                header('Location: ' . base_url('administration'));
                exit();
            } else {
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
                $activenavh = 'usersettings';
                $activenavd = 'usergroupsetup';

                $this->nav_data = [
                    'active_navh' => $activenavh,
                    'active_navd' => $activenavd,
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
            }
        }
    }


    public function index()
    {
        $usergroup_data = $this->SetupModel->get_usergroup();
        $data = array(
            'usergroup_data' => $usergroup_data,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/usergroup_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function add()
    {
        $data = array(
            'button' => 'Add Group',
            'form_action' => base_url('/usergroupsetup/create_action'),
            'groupid' => set_value('GROUPID'),
            'groupname' => set_value('GROUPNAME'),
            'groupdesc' => set_value('GROUPDESC'),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/usergroup_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function create_action()
    {
        if (!$this->validate([
            'groupname' => 'required|max_length[50]',
            'groupdesc' => 'required|max_length[50]',

        ])) {
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');
            return redirect()->to(base_url('/usergroupsetup/add/'))->withInput();
        } else {
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'GROUPNAME' => $this->request->getPost('groupname'),
                'GROUPDESC' => $this->request->getPost('groupdesc'),
            );

            $this->SetupModel->usergroup_insert($data);
            session()->setFlashdata('messagesuccess', 'Create Record Success');
            return redirect()->to(base_url('/usergroupsetup'));
        }
    }

    public function form($groupid = '')
    {
        if ($groupid) {
            $usergroup = $this->SetupModel->get_data_groups($groupid);
            $data = array(
                'button' => 'Save',
                'form_action' => base_url('/usergroupsetup/update_action'),
                'groupid' => $usergroup['GROUPID'],
                'groupname' => $usergroup['GROUPNAME'],
                'groupdesc' => $usergroup['GROUPDESC'],
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/usergroup_form', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update_action()
    {
        if (!$this->validate([
            'groupname' => 'required|max_length[50]',
            'groupdesc' => 'required|max_length[50]',

        ])) {
            $groupid = $this->request->getPost('groupid');
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');
            return redirect()->to(base_url('/usergroupsetup/form/' . $groupid))->withInput();
        } else {
            $groupid = $this->request->getPost('groupid');
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'GROUPNAME' => $this->request->getPost('groupname'),
                'GROUPDESC' => $this->request->getPost('groupdesc'),
            );

            $this->SetupModel->usergroup_update($groupid, $data);
            session()->setFlashdata('messagesuccess', 'Create Record Success');
            return redirect()->to(base_url('/usergroupsetup'));
        }
    }

    public function role($groupid = '')
    {
        $usergroup = $this->SetupModel->get_data_groups($groupid);
        $grouprole = $this->SetupModel->get_all_navigation($groupid);
        if ($grouprole) {
            $data = array(
                'button' => 'Save',
                'form_action' => base_url('/usergroupsetup/update_role_action'),
                'grouprole_data' => $grouprole,
                'groupid' => $groupid,
                'groupname' => $usergroup['GROUPNAME'],
                'groupdesc' => $usergroup['GROUPDESC'],
            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/usergroup_role', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function update_role_action()
    {

        //if ($_POST['saved_grouprole']) {
        $idgrusergroup = $_POST['idgrusergroup'];
        // INSERT USERROLE
        $result = array();
        foreach ($_POST['IDNAVL1'] as $key => $val) {
            $result[] = array(
                'GROUPID' => $_POST['GROUPID'][$key],
                'IDNAVL1' => $_POST['IDNAVL1'][$key],
                'IDNAVDL1' => $_POST['IDNAVDL1'][$key],
                'ISACTIVE' => $_POST['grisactive'][$key],
            );
        }
        // Fungsi Delete Data Group Role
        $this->SetupModel->grouprole_delete($idgrusergroup);
        // fungsi dari codeigniter untuk insert multi array detail
        //$this->$db->insertBatch('webot_USERGROUPROLE', $result);
        $insert = $this->SetupModel->insert_grouprole($result);
        session()->setFlashdata('messagesuccess', 'Update Record Success');
        return redirect()->to(base_url('/usergroupsetup/role/' . $idgrusergroup));
        //}
    }
}
