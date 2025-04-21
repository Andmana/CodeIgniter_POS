<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;
use CodeIgniter\HTTP\Request;

class Supplier extends BaseController
{
    public function __construct()
    {
        $this->supplier = new SupplierModel();
    }

    public function index()
    {
        $findbutton = $this->request->getPost('supplierfindbutton');

        if (isset($findbutton)) {
            $find = $this->request->getPost('findsupplier');
            session()->set('findsupplier');
            redirect()->to('supplier/index');
        } else {
            $find = session()->get('findsupplier');
        }


        $supplierdata = $find ? $this->supplier->finddata($find) : $this->supplier;

        $numpages = $this->request->getVar('page_supplier') ? $this->request->getVar('page_supplier') : 1;
        $data = [
            'title' => 'Data Supplier',
            'supplierdata' => $supplierdata->orderBy('name_supplier', 'asc')->paginate(10, 'supplier'),
            'pager' => $this->supplier->pager,
            'numpages' => $numpages,
            'find' => $find,
        ];
        return view('Supplier/tables', $data);
    }
    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $this->supplier->delete($id);
            $msg = [
                'success' => 'Supplier berhasil dihapus!',
            ];

            echo json_encode($msg);
        }
    }
    public function edit($id)
    {
        $item = $this->supplier->find($id);

        if ($item) {
            $data = [
                'supplier_id' => $item['id_supplier'],
                'supplier_name' => $item['name_supplier'],
                'supplier_address' => $item['address_supplier'],
                'supplier_phone' => $item['phone_supplier'],
                'title' => 'Edit Produk',
            ];
            return view('supplier/editform', $data);
        } else {
            exit('Data Produk Tidak Ada');
        }
    }
    public function updatedata()
    {
        if ($this->request->isAJAX()) {
            $suppid = $this->request->getVar('supplierid');
            $suppname = $this->request->getVar('suppliername');
            $suppphone = $this->request->getVar('supplierphone');
            $suppaddress = $this->request->getVar('supplieraddress');

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'suppliername' => [
                    'label' => 'Nama Supplier',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'supplierphone' => [
                    'label' => 'Nomer Kontak',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'supplieraddress' => [
                    'label' => 'Alamat Supplier',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'suppliernameerror' => $validation->getError('suppliername'),
                        'supplierphoneerror' => $validation->getError('supplierphone'),
                        'supplieraddresserror' => $validation->getError('supplieraddress'),

                    ]
                ];
            } else {
                $this->supplier->update($suppid, [
                    'name_supplier' => $suppname,
                    'phone_supplier' => $suppphone,
                    'address_supplier' => $suppaddress,

                ]);

                $msg = [
                    'sukses' => 'Berhasil disimpan',
                ];
            }
            echo json_encode($msg);
        }
    }
    public function add()
    {
        $data = [
            'title' => 'Tambah Produk'
        ];
        return view('supplier/addform', $data);
    }
    public function adddata()
    {
        if ($this->request->isAJAX()) {
            $suppname = $this->request->getVar('suppliername');
            $suppphone = $this->request->getVar('supplierphone');
            $suppaddress = $this->request->getVar('supplieraddress');

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'suppliername' => [
                    'label' => 'Nama Supplier',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'supplierphone' => [
                    'label' => 'Nomer Kontak',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'supplieraddress' => [
                    'label' => 'Alamat Supplier',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'suppliernameerror' => $validation->getError('suppliername'),
                        'supplierphoneerror' => $validation->getError('supplierphone'),
                        'supplieraddresserror' => $validation->getError('supplieraddress'),

                    ]
                ];
            } else {
                $this->supplier->insert([
                    'name_supplier' => $suppname,
                    'phone_supplier' => $suppphone,
                    'address_supplier' => $suppaddress,

                ]);

                $msg = [
                    'sukses' => 'Berhasil disimpan',
                ];
            }
            echo json_encode($msg);
        }
    }
}
