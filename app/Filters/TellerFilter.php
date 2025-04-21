<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UsersModel;

class TellerFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $iduser = $session->get('id');
        $this->user = new UsersModel();
        $datauser = $this->user->find($iduser);
        $leveluser = $session->get('level');
        if ($leveluser == 2) {
            return redirect()->to('/Dashboard/error');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $session = session();
        $iduser = $session->get('id');
        $this->user = new UsersModel();
        $datauser = $this->user->find($iduser);
        $leveluser = $session->get('level');
        if ($leveluser > 2) {
            return redirect()->to('/');
        }
    }
}
