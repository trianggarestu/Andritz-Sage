<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\Administration_model;
use App\Models\Notif_model;
use App\Models\Setup_model;

use function PHPUnit\Framework\matches;

//use App\Models\Settingnavheader_model;


class Usersetup extends BaseController
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
                    'notif_messages' => $mailbox_unread,
                ];
                $this->footer_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                ];
                // Assign the model result to the badly named Class Property
                $activenavh = 'usersettings';
                $activenavd = 'usersetup';

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
        $users_data = $this->SetupModel->get_users();
        $nav['act_tab'] = 1;
        $data = array(
            'users_data' => $users_data,
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/users_list', $data);
        echo view('view_footer', $this->footer_data);
    }

    public function add()
    {
        $data = array(
            'button' => 'Add New User',
            'form_action' => base_url('/usersetup/create_action'),
            'useruniq' => set_value('USERUNIQ'),
            'groupid' => set_value('GROUPID'),
            'username' => set_value('USERNAME'),
            'name' => set_value('NAME'),
            'email' => set_value('EMAIL'),
            'password' => set_value('PASSWORD'),
            'groupdesc' => set_value('GROUPNAME'),
            'photo' => set_value('PATH_PHOTO')
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/view_adduser', $data);
        echo view('view_footer', $this->footer_data);
    }
    public function create_action()
    {
        if (!$this->validate([
            'username' => [
                'rules' => 'required|max_length[50]',
                //    |is_unique[webot_USERAUTH.USERNAME]',
                // 'errors' => [
                //     'is_unique' => 'Username already exist'

                // ]
            ],
            'name' => 'required|max_length[50]',
            // 'email' => 'required|max_length[50]',
            "email" => [
                'rules' => 'required|valid_email|min_length[6]|max_length[50]',
                "required" => "Email required",
                "valid_email" => "Email address is not in format",

            ],
            'password' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} password is required',
                    'min_length' => '{field} Min 4 character',
                    'max_length' => '{field} Maks 50 charater',
                ]
            ],
            'password_conf' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'passwords are not the same',
                ]
            ],
            'photo' => [
                'rules' => 'max_size[photo,3072]|is_image[photo]',
                // |mime_in[photo,image/jpg,image/jpeg/image/png]',
                'errors' => [
                    'uploaded' => 'Please select the image',
                    'max_size' => 'Your image file is too large',
                    'is_image' => 'The file you uploaded is not an image',
                    'mime_in' => 'The file you uploaded is not an image'
                ]

            ]


        ])) {
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');
            return redirect()->to(base_url('/usersetup/add/'))->withInput();
        } else {
            $filephoto = $this->request->getFile('photo');

            if ($filephoto == '') {
                $photo = 'kuser.png';
            } else {
                $photo = $filephoto->getRandomName();
                $filephoto->move('/', $photo);

                $this->SetupModel->save([
                    'USERNAME' => strtoupper($this->request->getVar('username')),
                    'NAME' => strtoupper($this->request->getVar('name')),
                    'EMAIL' => $this->request->getVar('email'),
                    'PASSWORD' => md5(strtoupper($this->request->getVar('password'))),
                    'GROUPID' => $this->request->getPost('groupid'),
                    'ISSUPERUSER' => '0',
                    'INACTIVE' => '0',
                    'PATH_PHOTO' => 'img/' . $photo
                ]);


                session()->setFlashdata('messagesuccess', 'Create Record Success');
                return redirect()->to(base_url('/usersetup'));
            }
        }
    }
    public function update()
    {
        $data = array(
            'button' => 'Add New User',
            'form_action' => base_url('/usersetup/create_action'),
            'useruniq' => set_value('USERUNIQ'),
            'groupid' => set_value('GROUPID'),
            'username' => set_value('USERNAME'),
            'name' => set_value('NAME'),
            'email' => set_value('EMAIL'),
            'password' => set_value('PASSWORD'),
            'groupdesc' => set_value('GROUPNAME'),
            'photo' => set_value('PATH_PHOTO')
        );

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/view_updateuser', $data);
        echo view('view_footer', $this->footer_data);
    }
    public function form($useruniq = '')
    {
        if ($useruniq) {
            $user = $this->SetupModel->get_data_user($useruniq);
            $data = array(
                'button' => 'Save',
                'form_action' => base_url('/usersetup/update_action'),
                'useruniq' => $user['USERUNIQ'],
                'username' => $user['USERNAME'],
                'name' => $user['NAME'],
                'email' => $user['EMAIL'],
                'password' => $user['PASSWORD'],
                'groupid' => $user['GROUPID'],
                'groupdesc' => $user['GROUPNAME'],
                'photo' => $user['PATH_PHOTO']


            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/view_updateuser', $data);
        echo view('view_footer', $this->footer_data);
    }
    public function update_action()
    {
        if (!$this->validate([

            "email" => [
                'rules' => 'valid_email|min_length[6]|max_length[50]',
                "valid_email" => "Email address is not in format",

            ],
            'password' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} password is required',
                    'min_length' => '{field} Min 4 character',
                    'max_length' => '{field} Maks 50 charater',
                ]
            ],
            'password_conf' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'passwords are not the same',
                ]
            ],
            'photo' => [
                'rules' => 'max_size[photo,3072]|is_image[photo]',
                // |mime_in[photo,image/jpg,image/jpeg/image/png]',
                'errors' => [
                    'uploaded' => 'Please select the image',
                    'max_size' => 'Your image file is too large',
                    'is_image' => 'The file you uploaded is not an image',
                    'mime_in' => 'The file you uploaded is not an image'
                ]

            ]


        ])) {
            session()->setFlashdata('messagefailed', 'Input Failed, Complete data before save!..');
            return redirect()->to(base_url('/usersetup/update/'))->withInput();
        } else {
            $filephoto = $this->request->getFile('photo');

            if ($filephoto->getError() == UPLOAD_ERR_NO_FILE) {
                $photo = 'kuser.png';
            } else {
                $photo = $filephoto->getRandomName();
                $filephoto->move('img', $photo);
                $data = array(
                    'USERNAME' => strtoupper($this->request->getVar('username')),
                    'NAME' => strtoupper($this->request->getVar('name')),
                    'EMAIL' => $this->request->getVar('email'),
                    'PASSWORD' => md5(strtoupper($this->request->getVar('password'))),
                    'GROUPID' => $this->request->getPost('groupid'),
                    'ISSUPERUSER' => '0',
                    'INACTIVE' => '0',
                    'PATH_PHOTO' => 'img' . $photo
                );

                $this->SetupModel->updateuser($data);
                session()->setFlashdata('messagesuccess', 'Create Record Success');
                return redirect()->to(base_url('/usersetup'));
            }
        }
    }
    public function formupdate($useruniq = '')
    {
        if ($useruniq) {
            $user = $this->SetupModel->get_data_user($useruniq);
            $data = array(
                'button' => 'Save',
                'form_action' => base_url('/usersetup/update_action'),
                'useruniq' => $user['USERUNIQ'],
                'username' => $user['USERNAME'],
                'name' => $user['NAME'],
                'email' => $user['EMAIL'],
                'password' => $user['PASSWORD'],
                'groupid' => $user['GROUPID'],
                'groupdesc' => $user['GROUPNAME'],
                'photo' => $user['PATH_PHOTO']


            );
        }

        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);
        echo view('settings/view_updateUser', $data);
        echo view('view_footer', $this->footer_data);
    }
    public function delete($useruniq = '')
    {
        $data = $this->SetupModel->DELETE('webot_USERAUTH')
            ->WHERE("USERUNIQ = '$useruniq'");
        echo view('settings/user_list', $data);


        echo view('view_header', $this->header_data);
        echo view('view_nav', $this->nav_data);

        echo view('view_footer', $this->footer_data);
    }
}
