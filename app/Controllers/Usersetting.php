<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\User_model;
use App\Models\Setup_model;
use App\Models\Administration_model;


class Usersetting extends BaseController
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
		$this->UserModel = new User_model();
		$this->SetupModel = new Setup_model();
		$this->AdministrationModel = new Administration_model();
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
				$this->header_data = [
					'usernamelgn'   => $infouser['usernamelgn'],
					'namalgn' => $infouser['namalgn'],
					'emaillgn' => $infouser['emaillgn'],
					'photolgn' => $infouser['photolgn'],
					'userhashlgn' => $infouser['userhashlgn'],
					'issuperuserlgn' => $infouser['issuperuserlgn'],
				];
				$this->footer_data = [
					'usernamelgn'   => $infouser['usernamelgn'],
				];
				// Assign the model result to the badly named Class Property
				$activenavd = 'User_setting';
				$this->nav_data = [
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

		$user = session()->get('username');
		$keylog = session()->GET('keylog');
		$data['main'] = $this->SetupModel->get_users_by_hash($this->header_data['userhashlgn']);
		$data['form_action'] = base_url("usersetting/update_action");
		echo view('view_usersetting', $data);
	}


	public function update_action()
	{
		$hashuser = $this->request->getVar('userhash');
		if (!$this->validate([
			'name' => 'required|max_length[50]',
			'photo' => [
				'rules' => 'max_size[photo,3072]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
				'errors' => [
					'uploaded' => 'Please select the image',
					'max_size' => 'Your image file is too large',
					'is_image' => 'The file you uploaded is not an image',
					'mime_in' => 'The file you uploaded is not an image'
				]
			],

		])) {

			session()->set('success', '-1');
			return redirect()->back();
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

			//if (md5(strtoupper($this->request->getVar('old_pass'))) == $user_data['PASSWORD']) {
			if (
				trim($this->request->getVar('name')) == trim($user_data['NAME']) and trim($path_photo) == trim($user_data['PATH_PHOTO'])
				and empty($this->request->getVar('old_pass')) and empty($this->request->getVar('new_pass')) and empty($this->request->getVar('re_new_pass'))
			) {
				//session()->set('success', '-1');
				return redirect()->back();
			} else {
				if (empty($this->request->getVar('old_pass')) and empty($this->request->getVar('new_pass')) and empty($this->request->getVar('re_new_pass'))) {
					$data = array(
						'AUDTDATE' => $this->audtuser['AUDTDATE'],
						'AUDTTIME' => $this->audtuser['AUDTTIME'],
						'AUDTUSER' => $this->audtuser['AUDTUSER'],
						'AUDTORG' => $this->audtuser['AUDTORG'],
						'NAME' => trim($this->request->getVar('name')),
						'PATH_PHOTO' => $path_photo,
					);
				} else {
					if (md5(strtoupper($this->request->getVar('old_pass'))) != $user_data['PASSWORD']) {
						session()->set('success', '-3');
						return redirect()->back();
					} else {
						$data = array(
							'AUDTDATE' => $this->audtuser['AUDTDATE'],
							'AUDTTIME' => $this->audtuser['AUDTTIME'],
							'AUDTUSER' => $this->audtuser['AUDTUSER'],
							'AUDTORG' => $this->audtuser['AUDTORG'],
							'NAME' => trim($this->request->getVar('name')),
							'PASSWORD' => md5(strtoupper($this->request->getVar('new_pass'))),
							'PATH_PHOTO' => $path_photo,
						);
					}
				}
				$this->SetupModel->user_update($username, $data);
				session()->set('success', '1');
				return redirect()->back();
			}
		}
	}
}
