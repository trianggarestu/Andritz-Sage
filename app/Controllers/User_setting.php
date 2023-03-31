<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;
use App\Models\User_model;
use App\Models\Administration_model;


class User_setting extends BaseController
{

	private $nav_data;
	private $header_data;
	private $footer_data;
	public function __construct()
	{
		//parent::__construct();
		helper('form', 'url');
		$this->LoginModel = new Login_model();
		$this->UserModel = new User_model();
		$this->AdministrationModel = new Administration_model();
		//$this->SettingnavheaderModel = new Settingnavheader_model();
		if (empty(session()->get('keylog'))) {
			//Tidak Bisa menggunakan redirect kalau condition session di construct
			//return redirect()->to(base_url('/login'));
			header('Location: ' . base_url());
			exit();
		} else {
			$user = session()->get('username');
			/*$chksu = $this->LoginModel->datalevel($user);
            if ($chksu == 0) {
                redirect('administration');
            } else {
                */
			$infouser = $this->LoginModel->datapengguna($user);
			$this->header_data = [
				'usernamelgn'   => $infouser['usernamelgn'],
				'namalgn' => $infouser['namalgn'],
				'emaillgn' => $infouser['emaillgn'],
				'issuperuserlgn' => $infouser['issuperuserlgn'],
			];
			$this->footer_data = [
				'usernamelgn'   => $infouser['usernamelgn'],
			];
			// Assign the model result to the badly named Class Property
			$activenavd = 'Settingnavheader';
			$this->nav_data = [
				'active_navd' => $activenavd,
				'menu_nav' => $this->AdministrationModel->get_navigation(),
				//'ttl_inbox_unread' => $this->AdministrationModel->count_message(),
				//'chkusernav' => $this->AdministrationModel->count_navigation($user), 
				//'active_navh' => $this->AdministrationModel->get_activenavh($activenavd),
			];
			//}
		}
	}

	public function index()
	{
		$user = session()->get('username');
		$keylog = session()->GET('keylog');
		$data['main'] = $this->UserModel->get_user($user);
		echo view('view_usersetting', $data);
	}

	public function update($id = '')
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('pass_baru', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
		$this->form_validation->set_message('syarat_sandi', 'Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');

		if ($this->form_validation->run() !== true) {
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$this->user_model->update_setting($id);
			if ($this->session->success == -1) {
				redirect($_SERVER['HTTP_REFERER']);
			} else redirect("main");
		}
	}

	public function update_password($id = '')
	{
		$this->user_model->update_password($id);
		if ($this->session->success == -1) {
			redirect($_SERVER['HTTP_REFERER']);
		} else redirect("main");
	}

	// Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
	public function syarat_sandi($str)
	{
		if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str))
			return TRUE;
		else
			return FALSE;
	}

	public function change_pwd()
	{
		$id = $_SESSION['user'];
		$data['main'] = $this->user_model->get_user($id);
		$data['header'] = $this->config_model->get_data();
		$this->load->view('setting_pwd', $data);
	}
}
