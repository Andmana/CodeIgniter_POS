<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\AuthModel;
use Config\Validation;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UsersModel();
    }
    public function register()
    {
        $val = $this->validate(
            [
                'fullName' => 'required',
                'username' => [
                    'field' => 'Username',
                    'rules' => 'required|is_unique[users.username]',
                    'errors' => [
                        'is_unique' => '{field} Sudah dipakai'
                    ]
                ],
                'password' => 'required',
                'repeatPassword' => [
                    'rules' => 'matches[password]',
                    'errors' => [
                        'matches' => '{field} Kata sandi tidak sesuai'
                    ]
                ],

            ],
        );
        if (!$val) {
            $pesanvalidasi = \Config\Services::validation();
            return redirect()->to('/home/register')->withInput()->with('validate', $pesanvalidasi);
        }
        $password = $this->request->getPost('password');
        $data = array(
            'name' => $this->request->getPost('fullName'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        );
        $model = new UsersModel;
        $model->insert($data);
        session()->setFlashdata('pesan', 'Selamat anda berhasil registrasi');
        return redirect()->to('/Home/login');
    }
    public function login()
    {
        $model = new AuthModel();
        $table = 'users';
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $row = $model->get_data_login($username, $table);
        if ($row == NULL) {
            session()->setFlashdata('pesan', 'username atau password salah');
            return redirect()->to('Home/login');
        }
        if (password_verify($password, $row->password)) {
            $data = array(
                'log' => true,
                'id' => $row->id,
                'name' => $row->name,
                'username' => $row->username,
                'level' => $row->level
            );
            session()->set($data);
            session()->setFlashdata('pesan', 'Berhasil Login');
            return redirect()->to('Dashboard/index');
        }
        session()->setFlashdata('pesan', 'Password salah');
        return redirect()->to('Home/login');
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        session()->setFlashdata('pesan', 'Berhasil Logout');
        return redirect()->to('/Home/login');
    }
}
