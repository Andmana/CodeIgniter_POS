<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use CodeIgniter\HTTP\Request;

class Product extends BaseController
{
    public function __construct()
    {
        $this->product = new ProductModel();
        $this->supplier = new SupplierModel();
    }
    public function index()
    {
        $findbutton = $this->request->getPost('findbuttonproduct');
        session()->set('findproduct', "");
        if (isset($findbutton)) {
            $find = $this->request->getPost('findproduct');
            session()->set('findproduct', $find);
            redirect()->to('product/index');
        } else {
            $find = session()->get('findproduct');
        }


        $dataproduct = $find ? $this->product->findData($find) : $this->product->join('supplier', 'supplier_product = id_supplier');

        $numPages = $this->request->getVar('page_produk') ? $this->request->getVar('page_produk') : 1;
        $data = [
            'title' => 'Daftar Produk',
            'dataproduct' => $dataproduct->where('qr_product !=', null)->paginate(10, 'product'),
            'pager' => $this->product->pager,
            'numPages' => $numPages,
            'find' => $find,
        ];
        return view('Product/tables', $data);
    }
    public function add()
    {
        $data = [
            'title' => 'Tambah Produk'
        ];
        return view('product/addform', $data);
    }
    public function getSupplier()
    {
        if ($this->request->isAJAX()) {
            $datasupplier = $this->supplier->findAll();

            $isidata = "<option value='' selected>-Pilih-</option>";

            foreach ($datasupplier as $row) :
                $isidata .= '<option value="' . $row['id_supplier'] . '">' . $row['name_supplier'] . '</option>';
            endforeach;

            $msg = [
                'data' => $isidata,
            ];
            echo json_encode($msg);
        }
    }
    function savedata()
    {
        if ($this->request->isAJAX()) {
            $qrcodeproduct = $this->request->getVar('qrcode');
            $nameproduct = $this->request->getVar('productname');
            $stockproduct = str_replace(',', '', $this->request->getVar('productstock'));
            $sellproduct = str_replace(',', '', $this->request->getVar('sellprice'));
            $buyproduct = str_replace(',', '', $this->request->getVar('purchaseprice'));
            $supplierproduct = $this->request->getVar('suppliername');

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'qrcode' => [
                    'label' => 'Kode Barcode',
                    'rules' => 'is_unique[product.qr_product]|required',
                    'errors' => [
                        'is_unique' => '{field} sudah ada, coba dengan kode yang lain',
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'productname' => [
                    'label' => 'Nama Produk',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'productstock' => [
                    'label' => 'Stok Tersedia',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'sellprice' => [
                    'label' => 'Harga Jual',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'purchaseprice' => [
                    'label' => 'Harga Beli',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh Kosong',
                    ]
                ],
                'suppliername' => [
                    'label' => 'Nama Supplier',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} wajib dipilih'
                    ]
                ],
                'productimage' => [
                    'label' => 'Upload Gambar',
                    'rules' => 'mime_in[productimage,image/png,image/jpg,image/jpeg]|ext_in[productimage,png,jpg,jpeg]|is_image[productimage]',
                ]
            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'errorQRcode' => $validation->getError('qrcode'),
                        'errorName' => $validation->getError('productname'),
                        'errorStock' => $validation->getError('productstock'),
                        'errorSellPrice' => $validation->getError('sellprice'),
                        'errorBuyPrice' => $validation->getError('purchaseprice'),
                        'errorImage' => $validation->getError('productimage'),
                        'errorSupplier' => $validation->getError('suppliername')
                    ]
                ];
            } else {
                $productImage = $_FILES['productimage']['name'];

                if ($productImage != NULL) {
                    $imageFileName = "$qrcodeproduct-$nameproduct";
                    $fileImage = $this->request->getFile('productimage');
                    $fileImage->move('assets/upload', $imageFileName . '.' . $fileImage->getExtension());

                    $pathImage = 'assets/upload/' . $fileImage->getName();
                } else {
                    $pathImage = '';
                }

                $this->product->insert([
                    'qr_product' => $qrcodeproduct,
                    'name_product' => $nameproduct,
                    'stock_product' => $stockproduct,
                    'sellPrice_product' => $sellproduct,
                    'purchasePrice_product' => $buyproduct,
                    'image_product' => $pathImage,
                    'supplier_product' => $supplierproduct
                ]);

                $msg = [
                    'sukses' => 'Produk berhasil ditambahkan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            // $this->product->delete($qr);
            $this->product->update($id, [
                'qr_product' => null,
                'stock_product' => 0,
            ]);
            $msg = [
                'success' => 'Produk berhasil dihapus!',
            ];

            echo json_encode($msg);
        }
    }
    public function edit($id)
    {
        $item = $this->product->find($id);

        if ($item) {
            $data = [
                'item_id' => $item['id_product'],
                'item_qr' => $item['qr_product'],
                'item_sell' => $item['sellPrice_product'],
                'item_buy' => $item['purchasePrice_product'],
                'item_name' => $item['name_product'],
                'item_stock' => $item['stock_product'],
                'item_supplier' => $item['supplier_product'],
                'item_image' => $item['image_product'],
                'list_supplier' => $this->supplier->orderBy('name_supplier', 'asc')->findAll(),
                'title' => 'Edit Produk',
            ];
            return view('product/editform', $data);
        } else {
            exit('Data Produk Tidak Ada');
        }
    }
    public function updatedata()
    {
        if ($this->request->isAJAX()) {
            $idproduct = $this->request->getVar('productid');
            $oldproduct = $this->product->find($idproduct);
            $qrcodeproduct = $this->request->getVar('qrcode');
            $nameproduct = $this->request->getVar('productname');
            $stockproduct = str_replace(',', '', $this->request->getVar('productstock'));
            $sellproduct = str_replace(',', '', $this->request->getVar('sellprice'));
            $buyproduct = str_replace(',', '', $this->request->getVar('purchaseprice'));
            $supplierproduct = $this->request->getVar('suppliername');

            if ($oldproduct['qr_product'] == $qrcodeproduct) {
                $qrRule = 'required';
            } else {
                $qrRule = 'is_unique[product.qr_product]|required';
            }

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'qrcode' => [
                    'label' => 'Kode Barcode',
                    'rules' => $qrRule,
                    'errors' => [
                        'is_unique' => '{field} sudah ada, coba dengan kode yang lain',
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'productname' => [
                    'label' => 'Nama Produk',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'productstock' => [
                    'label' => 'Stok Tersedia',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'sellprice' => [
                    'label' => 'Harga Jual',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'purchaseprice' => [
                    'label' => 'Harga Beli',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh Kosong',
                    ]
                ],
                'suppliername' => [
                    'label' => 'Nama Supplier',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} wajib dipilih'
                    ]
                ],
                'productimage' => [
                    'label' => 'Upload Gambar',
                    'rules' => 'mime_in[productimage,image/png,image/jpg,image/jpeg]|ext_in[productimage,png,jpg,jpeg]|is_image[productimage]',
                ]
            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'errorQRcode' => $validation->getError('qrcode'),
                        'errorName' => $validation->getError('productname'),
                        'errorStock' => $validation->getError('productstock'),
                        'errorSellPrice' => $validation->getError('sellprice'),
                        'errorBuyPrice' => $validation->getError('purchaseprice'),
                        'errorImage' => $validation->getError('productimage'),
                    ]
                ];
            } else {
                $productImage = $_FILES['productimage']['name'];
                $item_product = $this->product->find($idproduct);

                if ($productImage != NULL) {
                    $imageFileName = "$qrcodeproduct-$nameproduct";
                    $fileImage = $this->request->getFile('productimage');
                    $fileImage->move('assets/upload', $imageFileName . '.' . $fileImage->getExtension());

                    $pathImage = 'assets/upload/' . $fileImage->getName();
                } else {
                    $pathImage = $item_product['image_product'];
                }

                $this->product->update($idproduct, [
                    'qr_product' => $qrcodeproduct,
                    'name_product' => $nameproduct,
                    'stock_product' => $stockproduct,
                    'sellPrice_product' => $sellproduct,
                    'purchasePrice_product' => $buyproduct,
                    'image_product' => $pathImage,
                    'supplier_product' => $supplierproduct
                ]);

                $msg = [
                    'sukses' => 'Produk berhasil diupdate'
                ];
            }
            echo json_encode($msg);
        }
    }
}
