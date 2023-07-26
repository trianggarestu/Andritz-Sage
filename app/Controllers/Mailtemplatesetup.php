<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Setup_model;
//use App\Models\Settingnavheader_model;


class Mailtemplatesetup extends BaseController
{

    private $nav_data;
    private $header_data;
    private $footer_data;
    private $audtuser;
    private $db_name;
    public function __construct()
    {
        //parent::__construct();
        helper('form', 'url', 'filesystem');
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
                    'success_code' => session()->get('success'),
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavh = 'mailsetup';
                $activenavd = 'mailtemplatesetup';

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
                    'NAMELGN' => $infouser['namalgn'],

                ];
            }
        }
    }


    public function index()
    {
        $mailtemplate_data = $this->SetupModel->get_mailtemplate();

        $data = array(
            'mailtemplate_data' => $mailtemplate_data,
            'form_action' => base_url("mailtemplatesetup/update_mailtemplate"),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/mailtemplate_list', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update($tmpltid)
    {

        $mailtemplate_by_id = $this->SetupModel->get_mailtemplate_by_id($tmpltid);
        $subject = trim($mailtemplate_by_id['SUBJECT_MAIL']);
        $bodyemail = str_replace('?>', ']', (str_replace('<?=', '[', $mailtemplate_by_id['BODY_MAIL'])));

        $data = array(
            'tmpltuniq' => $mailtemplate_by_id['TMPLTUNIQ'],
            'groupuser' => $mailtemplate_by_id['MAILROUTE'],
            'subject' => $subject,
            'mail_body' => $bodyemail,
            'filename' => $mailtemplate_by_id['FILENAME'],
            'filepath' => $mailtemplate_by_id['PATH_TEMPLATE'],
            'form_action' => base_url("mailtemplatesetup/update_mailtemplate"),
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/mailtemplate_form', $data);
        echo view('view_footer', $this->footer_data);
    }


    public function update_mailtemplate()
    {
        if (!$this->validate([
            'subject_email' => 'required',
            'body_email' => 'required',

        ])) {
            return redirect()->to(base_url('/mailstemplatesetup'))->withInput();
        } else {
            $text = $this->request->getPost('body_email');
            $bodyemail = str_replace(']', '?>', (str_replace('[', '<?=', $text)));
            $filepath = $this->request->getPost('filepath');
            $filepath = str_replace('/', '\\', $filepath);
            //$views = get_filenames(APPPATH . 'view/');
            //delete_files('./path/to/directory/');
            //write_file('./path/to/file.php', $text, 'r+');
            $file = fopen(APPPATH . 'views\\' . $filepath . ".php", "w+");
            while (!feof($file)) {
                //$old = file_put_contents(APPPATH . 'views\\' . $filepath . ".php", "");
                $old = fgets($file) . "<br />";
            }
            file_put_contents(APPPATH . 'views\\' . $filepath . ".php", $old . $bodyemail);

            fclose($file);

            $data = array(
                'SUBJECT_MAIL' => $this->request->getPost('subject_email'),
                'BODY_MAIL' => $this->request->getPost('body_email'),
            );

            $tmpltuniq = $this->request->getPost('tmpltuniq');

            $this->SetupModel->mailtemplate_update($tmpltuniq, $data);

            session()->set('success', '1');
            return redirect()->to(base_url('/mailtemplatesetup'));
            session()->remove('success');
        }
    }

    public function update_offlinestat()
    {
        if (!$this->validate([
            'offlinestat' => 'required',

        ])) {
            return redirect()->to(base_url('/mailsendersetup/notifsetup'))->withInput();
            session()->setFlashdata('messageerror', 'Update Data Failed');
        } else {
            $id = $this->request->getPost('id');
            $data = array(
                'AUDTDATE' => $this->audtuser['AUDTDATE'],
                'AUDTTIME' => $this->audtuser['AUDTTIME'],
                'AUDTUSER' => $this->audtuser['AUDTUSER'],
                'AUDTORG' => $this->audtuser['AUDTORG'],
                'OFFLINESTAT' => $this->request->getPost('offlinestat'),
            );

            $this->SetupModel->mailsender_update($id, $data);
            session()->setFlashdata('messagesuccess', 'Update Data Success');
            return redirect()->to(base_url('/mailsendersetup/notifsetup'));
        }
    }
}
