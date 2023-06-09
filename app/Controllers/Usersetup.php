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
            $chkuser = $this->LoginModel->datapengguna($user);
            if (session()->get('keylog') == $chkuser['passlgn']) {
                $infouser = $this->LoginModel->datapengguna($user);
                $mailbox_unread = $this->NotifModel->get_mailbox_unread($user);
                $this->header_data = [
                    'usernamelgn'   => $infouser['usernamelgn'],
                    'namalgn' => $infouser['namalgn'],
                    'emaillgn' => $infouser['emaillgn'],
                    'issuperuserlgn' => $infouser['issuperuserlgn'],
                    'photolgn' => $infouser['photolgn'],
                    'notif_messages' => $mailbox_unread,
                    'success_code' => session()->get('success'),
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
        session()->remove('success');
        session()->set('success', '0');
        session()->remove('cari');
        session()->remove('from_date');
        session()->remove('to_date');

        $users_data = $this->SetupModel->get_users();

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
        session()->remove('success');
        session()->set('success', '0');

        $data = array(
            'button' => 'Add New User',
            'form_action' => base_url('/usersetup/create_action'),
            'groupid' => set_value('GROUPID'),
            'username' => set_value('USERNAME'),
            'name' => set_value('NAME'),
            'email' => set_value('EMAIL'),
            'password' => set_value('PASSWORD'),
            'groupdesc' => set_value('GROUPNAME'),
            'photo' => set_value('PATH_PHOTO'),
            'groupuser' => $this->SetupModel->get_usergroup(),
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
                'rules' => 'required|min_length[2]|max_length[10]',
            ],
            'name' => 'required|max_length[50]',
            // 'email' => 'required|max_length[50]',
            "email" => [
                'rules' => 'required|valid_email|min_length[6]|max_length[50]',
                "required" => "Email required",
                "valid_email" => "Email address is not in format",

            ],
            'password' => [
                'rules' => 'required|min_length[6]|max_length[20]',
                'errors' => [
                    'required' => '{field} password is required',
                    'min_length' => '{field} Min 6 character',
                    'max_length' => '{field} Maks 20 charater',
                ]
            ],
            'password_conf' => [
                'rules' => 'matches[password]',
                'errors' => [
                    'matches' => 'passwords are not the same',
                ]
            ],
            'photo' => [
                'rules' => 'max_size[photo,3072]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Please select the image',
                    'max_size' => 'Your image file is too large',
                    'is_image' => 'The file you uploaded is not an image',
                    'mime_in' => 'The file you uploaded is not an image'
                ]

            ],

            'isactive' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Field {field} is required',
                ]
            ]


        ])) {
            session()->set('success', '-1');
            return redirect()->to(base_url('/usersetup/add/'))->withInput();
        } else {
            $username = strtoupper($this->request->getVar('username'));
            $chek = $this->SetupModel->checkusername($username);
            if ($chek > 0) {
                session()->set('success', '-2');
                return redirect()->to(base_url('/usersetup/add/'))->withInput();
            } else {

                $filephoto = $this->request->getFile('photo');
                $chk_photo = $filephoto->getExtension();
                if (empty($chk_photo)) {
                    $photo = 'kuser.png';
                    $path_photo = NULL;
                } else {
                    $photo = 'photo_' . strtolower($this->request->getVar('username')) . '.' . $filephoto->getExtension();
                    $filephoto->move('assets/files/user_pict/', $photo);
                    $path_photo = 'assets/files/user_pict/' . $photo;
                }

                $data = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'USERNAME' => strtoupper($this->request->getVar('username')),
                    'PASSWORD' => md5(strtoupper($this->request->getVar('password'))),
                    'USERHASH' => md5(strtoupper($this->request->getVar('username'))),
                    'NAME' => strtoupper($this->request->getVar('name')),
                    'EMAIL' => $this->request->getVar('email'),
                    'GROUPID' => $this->request->getPost('groupid'),
                    'PATH_PHOTO' => $path_photo,
                    'ISSUPERUSER' => '0',
                    'INACTIVE' => $this->request->getPost('isactive'),
                    'ISDELETED' => '0',
                );

                $this->SetupModel->user_insert($data);
                session()->set('success', '1');
                return redirect()->to(base_url('/usersetup'));
            }
        }
    }


    public function update($hashuser)
    {
        $user_data = $this->SetupModel->get_users_by_hash($hashuser);
        if (empty($user_data)) {
            return redirect()->to(base_url('/usersetup'));
        } else {
            $data = array(
                'button' => 'Update User',
                'form_action' => base_url('/usersetup/update_action'),
                'groupid' => $user_data['GROUPID'],
                'username' => $user_data['USERNAME'],
                'userhash' => $user_data['USERHASH'],
                'name' => trim($user_data['NAME']),
                'email' => trim($user_data['EMAIL']),
                'password' => trim($user_data['PASSWORD']),
                'groupdesc' => $user_data['GROUPNAME'],
                'photo' => $user_data['PATH_PHOTO'],
                'inactive' => $user_data['INACTIVE'],
                'groupuser' => $this->SetupModel->get_usergroup(),
            );

            echo view('view_header', $this->header_data);
            echo view('view_nav', $this->nav_data);
            echo view('settings/view_edituser', $data);
            echo view('view_footer', $this->footer_data);
        }
    }


    public function update_action()
    {
        $hashuser = $this->request->getVar('userhash');
        if (!$this->validate([

            "email" => [
                'rules' => 'valid_email|min_length[6]|max_length[50]',
                "valid_email" => "Email address is not in format",

            ],
            'photo' => [
                'rules' => 'max_size[photo,3072]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Please select the image',
                    'max_size' => 'Your image file is too large',
                    'is_image' => 'The file you uploaded is not an image',
                    'mime_in' => 'The file you uploaded is not an image'
                ]
            ],
            'isactive' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Field {field} is required',
                ]
            ]

        ])) {

            session()->set('success', '-1');
            return redirect()->to(base_url('/usersetup/update/' . $hashuser))->withInput();
        } else {
            $user_data = $this->SetupModel->get_users_by_hash($hashuser);
            $username = $this->request->getVar('username');
            $filephoto = $this->request->getFile('photo');
            $chk_photo = $filephoto->getExtension();
            if (empty($chk_photo)) {
                if (empty($this->request->getPost('old_photo'))) {
                    $path_photo = NULL;
                } else {
                    $path_photo = $this->request->getPost('old_photo');
                }
            } else {
                if (is_file($this->request->getVar('old_photo'))) {
                    unlink(trim($this->request->getVar('old_photo')));
                }
                $photo = 'photo_' . trim(strtolower($this->request->getVar('username'))) . '.' . $filephoto->getExtension();
                $filephoto->move('assets/files/user_pict/', $photo);
                $path_photo = 'assets/files/user_pict/' . $photo;
            }

            $inactive = $this->request->getPost('isactive');

            if (
                trim($this->request->getVar('name')) == trim($user_data['NAME']) and trim($this->request->getVar('email')) == trim($user_data['EMAIL'])
                and $this->request->getPost('groupid') == $user_data['GROUPID'] and $inactive == $user_data['INACTIVE']
                and trim($path_photo) == trim($user_data['PATH_PHOTO'])
            ) {
                return redirect()->to(base_url('/usersetup'));
            } else {

                $data = array(
                    'AUDTDATE' => $this->audtuser['AUDTDATE'],
                    'AUDTTIME' => $this->audtuser['AUDTTIME'],
                    'AUDTUSER' => $this->audtuser['AUDTUSER'],
                    'AUDTORG' => $this->audtuser['AUDTORG'],
                    'NAME' => trim($this->request->getVar('name')),
                    'EMAIL' => trim($this->request->getVar('email')),
                    'GROUPID' => $this->request->getPost('groupid'),
                    'INACTIVE' => $inactive,
                    'PATH_PHOTO' => $path_photo,
                );

                $this->SetupModel->user_update($username, $data);
                session()->set('success', '1');
                return redirect()->to(base_url('/usersetup'));
            }
        }
    }

    public function changepassword($userhash)
    {
        //$user_data = $this->SetupModel->get_users_by_hash($hashuser);
        if (empty($userhash)) {
            return redirect()->to(base_url('/usersetup'));
        } else {
            //$data['user'] = $this->SalesorderModel->list_contract_open();
            $data['userhash'] = $userhash;
            $data['form_action'] = base_url("usersetup/changepassword_action");
            echo view('settings/ajax_changepassword', $data);
        }
    }


    public function changepassword_action()
    {
        $userhash = $this->request->getVar('userhash');
        if (!$this->validate([
            'new_pass' => [
                'rules' => 'required|min_length[6]|max_length[20]',
                'errors' => [
                    'required' => '{field} password is required',
                    'min_length' => '{field} Min 6 character',
                    'max_length' => '{field} Maks 20 charater',
                ]
            ],
            're_new_pass' => [
                'rules' => 'matches[new_pass]',
                'errors' => [
                    'matches' => 'passwords are not the same',
                ]
            ]
        ])) {
            session()->set('success', '-3');
            return redirect()->to(base_url('/usersetup/update/' . $userhash))->withInput();
        } else {
            $data = array(
                'PASSWORD' => md5(strtoupper($this->request->getVar('new_pass'))),
            );

            $this->SetupModel->password_update($userhash, $data);
            session()->set('success', '1');
            return redirect()->to(base_url('/usersetup/update/' . $userhash))->withInput();
        }
    }

    public function setactive($hashuser = '')
    {
        $this->SetupModel->set_active($hashuser, 0);
        return redirect()->to(base_url('/usersetup'));
    }

    public function setinactive($hashuser = '')
    {
        $this->SetupModel->set_active($hashuser, 1);
        return redirect()->to(base_url('/usersetup'));
    }

    public function delete($hashuser)
    {
        $user_data = $this->SetupModel->get_users_by_hash($hashuser);
        if ($user_data['INACTIVE'] == 1) {
            if (is_file($user_data['PATH_PHOTO'])) {
                unlink(trim($user_data['PATH_PHOTO']));
            }

            $this->SetupModel->user_delete($hashuser, 1);


            session()->set('success', '1');
            return redirect()->to(base_url('/usersetup'));
        } else {
            session()->set('success', '-1');
            return redirect()->to(base_url('/usersetup'));
        }
    }
}
