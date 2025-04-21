<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }
    public function login()
    {
        $data = [
            'title' => 'Halaman Masuk'
        ];
        return view('auth/login', $data);
    }
    public function register()
    {
        session();
        $data = [
            'title' => 'Halaman Daftar',
            'validate' => \Config\Services::validation(),
        ];
        return view('auth/register', $data);
    }
}
