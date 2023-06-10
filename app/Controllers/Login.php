<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login_model;


class Login extends BaseController
{
    public function __construct()
    {
        helper('form');
        $this->LoginModel = new Login_model();
    }

    function index()
    {
        return redirect()->to(base_url());
        //echo view('/view_login');
    }

    public function login()
    {
        $username = strtoupper($this->request->getPost('username'));
        $password = md5(strtoupper($this->request->getPost('password')));

        $cek = $this->LoginModel->ambilpengguna($username, $password);

        if (empty($cek['USERNAME'])) {
            // Jika Username & Password Salah
            session()->setFlashdata('gagal', 'The username or password is incorrect. Pleas try again !!!');
            return redirect()->to(base_url());
        } else if (!empty($cek['USERNAME']) && !empty($cek['PASSWORD'])) {
            session()->set('keylog', $cek['PASSWORD']);
            session()->set('userhash', $cek['USERHASH']);
            session()->set('username', $cek['USERNAME']);
            return redirect()->to(base_url('administration'));
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url());
    }
}
