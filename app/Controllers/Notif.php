<?php

namespace App\Controllers;

use App\Models\Login_model;
use App\Models\Notif_model;

class Notif extends BaseController
{

	public function __construct()
	{
		$this->LoginModel = new Login_model();
		$this->NotifModel = new Notif_model();
		$user = session()->get('username');
		$chkuser = $this->LoginModel->datapengguna($user);

		if (empty(session()->get('keylog'))) {
			//Tidak Bisa menggunakan redirect kalau condition session di construct
			//return redirect()->to(base_url('/login'));
			header('Location: ' . base_url());
			exit();
		} else if (session()->get('keylog') != $chkuser['passlgn']) {
			header('Location: ' . base_url('administration'));
			exit();
		} else {
			$user = session()->get('username');
		}
	}

	public function inbox($user)
	{
		$j = $this->NotifModel->count_new_notif($user);
		if ($j > 0) {
			echo $j;
		}
	}
}
