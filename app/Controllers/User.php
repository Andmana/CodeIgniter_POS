<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\LevelModel;

use App\Models\UsersModel;

class User extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UsersModel();
        $this->level = new LevelModel();
    }
    public function index()
    {
        $findbutton = $this->request->getPost('userfindbutton');
        session()->set('finduser', "");


        if (isset($findbutton)) {
            $find = $this->request->getPost('finduser');
            session()->set('finduser', $find);
            redirect()->to('/user/index');
        } else {
            $find = session()->get('finduser');
        }


        $datauser = $find ? $this->userModel->findData($find) : $this->userModel->join('level', 'level = id_level');

        $numpages = $this->request->getVar('page_users') ? $this->request->getVar('page_users') : 1;
        $data = [
            'title' => 'Daftar Pengguna',
            'users' => $datauser->orderBy('name', 'asc')->paginate(10, 'users'),
            'pager' => $this->userModel->pager,
            'numpages' => $numpages,
            'find' => $find,
        ];
        return view('user/tables', $data);
    }
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if ($user) {
            $data = [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'phone' => $user['phone'],
                'email' => $user['email'],
                'address' => $user['address'],
                'level' => $user['level'],
                'image' => $user['image'],
                'title' => 'Edit Produk',
                'list_level' => $this->level->findAll(),
            ];
            return view('user/editform', $data);
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

                if ($this->userModel->update($id, [
                    'username' => $username,
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'level' => $level,
                    'address' => $address,
                    'image' => $pathImage,
                ])) {
                    $session = session();
                    if ($session->get('id') == $id) {
                        $data = $data = array(
                            'log' => true,
                            'id' => $id,
                            'name' => $name,
                            'username' => $username,
                            'level' => $level,
                        );
                        session()->set($data);
                    }
                }


                $msg = [
                    'sukses' => 'Data berhasil diupdate'
                ];
            }
            echo json_encode($msg);
        }
    }
    public function add()
    {
        $data = [
            'title' => 'Taambah Pengguna',
            'list_level' => $this->level->findAll(),
        ];
        return view('user/addform', $data);
    }
    public function adduser()
    {
        if ($this->request->isAJAX()) {
            $username = $this->request->getVar('username');
            $name = $this->request->getVar('nameuser');
            $phone = $this->request->getVar('phoneuser');
            $email = $this->request->getVar('emailuser');
            $level = $this->request->getVar('leveluser');
            $address = $this->request->getVar('addressuser');


            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'username' => [
                    'label' => 'Username',
                    'rules' => 'is_unique[users.username]|required',
                    'errors' => [
                        'is_unique' => '{field} sudah ada, coba dengan kode yang lain',
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'nameuser' => [
                    'label' => 'Nama Pengguna',
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
                    'label' => 'No Ponsel',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'emailuser' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email',
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
                        'addresserror' => $validation->getError('addressuser'),
                        'imageerror' => $validation->getError('imageuser'),
                    ]
                ];
            } else {
                $imageuser = $_FILES['imageuser']['name'];

                if ($imageuser != NULL) {
                    $imageFileName = "$username-$name";
                    $fileImage = $this->request->getFile('imageuser');
                    $fileImage->move('assets/upload/profile/', $imageFileName . '.' . $fileImage->getExtension());

                    $pathImage = 'assets/upload/profile/' . $fileImage->getName();
                } else {
                    $pathImage = '';
                }

                $this->userModel->insert([
                    'username' => $username,
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'level' => $level,
                    'address' => $address,
                    'password' => password_hash('qwerty', PASSWORD_DEFAULT),
                    'image' => $pathImage,
                ]);

                $msg = [
                    'sukses' => 'Data Pengguna berhasil ditambahkan'
                ];
            }
            echo json_encode($msg);
        }
    }
    public function resetpassword()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $this->userModel->update($id, [
                'password' => password_hash('qwerty', PASSWORD_DEFAULT),
            ]);
            $msg = [
                'success' => 'Kata sandi berhasil direset!',
            ];

            echo json_encode($msg);
        }
    }
    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $this->userModel->delete($id);
            $msg = [
                'success' => 'Data Pengguna berhasil dihapus!',
            ];

            echo json_encode($msg);
        }
    }
}
