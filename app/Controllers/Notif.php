<?php

namespace App\Controllers;

use App\Models\Notif_model;

class Notif extends BaseController
{

	public function __construct()
	{
		$this->NotifModel = new Notif_model();
		$user = session()->get('username');
		$infouser = $this->LoginModel->datapengguna($user);

		if (empty(session()->get('keylog'))) {
			//Tidak Bisa menggunakan redirect kalau condition session di construct
			//return redirect()->to(base_url('/login'));
			header('Location: ' . base_url());
			exit();
		} else if (session()->get('keylog') != $infouser['passlgn']) {
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
