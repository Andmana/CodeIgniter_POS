<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;

use App\Models\UsersModel;

class Profile extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UsersModel();
    }
    public function index()
    {
        $session = session();
        $id = $session->id;
        $user = $this->userModel->join('level', 'level=id_level')->find($id);
        $data = [
            'title' => 'Profil Saya',
            'id' => $user['id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'address' => $user['address'],
            'level' => $user['name_level'],
            'image' => $user['image'],
        ];
        return view('/profile/data', $data);
    }
    public function edit($id)
    {
        $user = $this->userModel->join('level', 'level=id_level')->find($id);

        if ($user) {
            $data = [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'phone' => $user['phone'],
                'email' => $user['email'],
                'address' => $user['address'],
                'level' => $user['level'],
                'level' => $user['level'],
                'position' => $user['name_level'],
                'image' => $user['image'],
                'title' => 'Edit Produk',
            ];
            return view('profile/editform', $data);
        } else {
            exit('Data Pengguna Tidak Ada');
        }
    }
    public function updatedata()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('iduser');
            $username = $this->request->getVar('username');
            $userdata = $this->userModel->find($id);
            $name = $this->request->getVar('nameuser');
            $phone = $this->request->getVar('phoneuser');
            $email = $this->request->getVar('emailuser');
            $level = $this->request->getVar('leveluser');
            $address = $this->request->getVar('addressuser');
            if ($userdata['id'] == $username) {
                $ruleuname = 'required';
            } else {
                $ruleuname = 'is_unique[product.qr_product]|required';
            }

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'username' => [
                    'label' => 'Username',
                    'rules' => $ruleuname,
                    'errors' => [
                        'is_unique' => '{field} sudah ada, coba dengan kode yang lain',
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'nameuser' => [
                    'label' => 'Nama',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'addressuser' => [
                    'label' => 'Alamat',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'phoneuser' => [
                    'label' => 'No Hanphone',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'emailuser' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'leveluser' => [
                    'label' => 'Level',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh Kosong',
                    ]
                ],
                'imageuser' => [
                    'label' => 'Upload Gambar',
                    'rules' => 'mime_in[imageuser,image/png,image/jpg,image/jpeg]|ext_in[imageuser,png,jpg,jpeg]|is_image[imageuser]',
                ]
            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'usernameerror' => $validation->getError('username'),
                        'nameerror' => $validation->getError('nameuser'),
                        'phoneerror' => $validation->getError('phoneuser'),
                        'emailerror' => $validation->getError('emailuser'),
                        'levelerror' => $validation->getError('leveluser'),
                        'addresserror' => $validation->getError('addressuser'),
                        'imageerror' => $validation->getError('imageuser'),
                    ]
                ];
            } else {
                $imageuser = $_FILES['imageuser']['name'];

                if ($imageuser != NULL) {
                    $imageFileName = "$id-$name";
                    $fileImage = $this->request->getFile('imageuser');
                    $fileImage->move('assets/upload/profile', $imageFileName . '.' . $fileImage->getExtension());

                    $pathImage = 'assets/upload/profile/' . $fileImage->getName();
                } else {
                    $pathImage = $userdata['image'];
                }

                $this->userModel->update($id, [
                    'username' => $username,
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'level' => $level,
                    'address' => $address,
                    'image' => $pathImage,
                ]);
                $session = session();

                $data = $data = array(
                    'log' => true,
                    'id' => $id,
                    'name' => $name,
                    'username' => $username,
                    'level' => $level,
                );
                session()->set($data);



                $msg = [
                    'sukses' => 'Data berhasil diupdate'
                ];
            }
            echo json_encode($msg);
        }
    }
    public function editpassword($id)
    {
        $user = $this->userModel->find($id);

        if ($user) {
            $data = [
                'id' => $id,
                'title' => 'Form Edit Kata Sandi'
            ];
            return view('profile/passwordform', $data);
        } else {
            exit('Data Pengguna Tidak Ada');
        }
    }
    public function changepassword()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('iduser');
            $newpassword = $this->request->getVar('newpassword');
            $repassword = $this->request->getVar('repeatpassword');

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'newpassword' => [
                    'label' => 'Kata Sandi',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'repeatpassword' => [
                    'label' => 'Kata Sandi',
                    'rules' => 'matches[newpassword]|required',
                    'errors' => [
                        'matches' => '{field} Tidak sama',
                        'required' => '{field} tidak boldeh kosong',
                    ]
                ],
            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'newpassworderror' => $validation->getError('newpassword'),
                        'repeatpassworderror' => $validation->getError('repassword'),
                    ]
                ];
            } else {
                $this->userModel->update($id, [
                    'password' => password_hash($newpassword, PASSWORD_DEFAULT),
                ]);
                $msg = [
                    'sukses' => 'Kata sandi berhasil diubah',
                ];
            }


            echo json_encode($msg);
        }
    }
}
