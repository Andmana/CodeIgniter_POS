<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Request;
use App\Models\DataProductModel;
use App\Models\ProductModel;
use App\Models\StockTransactionModel;
use App\Models\SupplierModel;

use Config\Services;



class stocktransaction extends BaseController

{
    public function __construct()
    {
        $this->product = new ProductModel();
    }
    public function index()
    {
        $data = [
            'title' => "Barang Masuk",
        ];
        return view('stockin/transactionform', $data);
    }
    public function getSupplier()
    {

        if ($this->request->isAJAX()) {
            $this->supplier = new SupplierModel();
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

    public function viewDataProduct()
    {
        if ($this->request->isAJAX()) {
            $keyword = $this->request->getPost('keyword');
            $data = [
                'keyword' => $keyword
            ];
            $msg = [
                'viewmodal' => view('stockin/viewmodalproduct', $data),
            ];
            echo json_encode($msg);
        }
    }
    public function getList()
    {
        if ($this->request->isAJAX()) {
            $keywordcode = $this->request->getPost('keywordcode');
            $request = Services::request();
            $datatable = new DataProductModel($request);

            if ($request->getMethod(true) === 'POST') {
                $lists = $datatable->getDatatables($keywordcode);
                $data = [];
                $no = $request->getPost('start');

                foreach ($lists as $list) {
                    $no++;
                    $row = [];
                    $row[] = $no;
                    $row[] = $list->qr_product;
                    $row[] = $list->name_product;
                    $row[] = number_format($list->stock_product, 0, ",", ".");
                    $row[] = number_format($list->purchasePrice_product, 0, ',', '.');
                    $row[] = $list->name_supplier;
                    $row[] = "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"choose('" . $list->id_product . "','" . $list->qr_product . "','" . $list->name_product . "','" . $list->stock_product . "','" . $list->purchasePrice_product . "')\">Pilih</button>";
                    $data[] = $row;
                }

                $output = [
                    'draw' => $request->getPost('draw'),
                    'recordsTotal' => $datatable->countAll($keywordcode),
                    'recordsFiltered' => $datatable->countFiltered($keywordcode),
                    'data' => $data
                ];

                echo json_encode($output);
            }
        }
    }
    public function finddata()
    {
        if ($this->request->isAJAX()) {
            $barcode = $this->request->getPost('barcode');



            $queryCheckproduct = $this->db->table('product')->like('qr_product', $barcode)
                ->orlike('name_product', $barcode)->get();

            $totalData = $queryCheckproduct->getNumRows();
            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak',
                ];
            } else if ($totalData == 1) {
                $data = $queryCheckproduct->getRowArray();
                $msg = [
                    'data' => 'satu',
                    'id' => $data['id_product'],
                    'name' => $data['name_product'],
                    'stock' => $data['stock_product'],
                    'price' => $data['purchasePrice_product'],
                ];
            } else {
                $msg = ['error' => 'Maaf Produk tidak ditemukan..'];
            }
            echo json_encode($msg);
        }
    }
    public function savetransaction()
    {
        if ($this->request->isAJAX()) {
            $date = $this->request->getPost('date');
            $id = $this->request->getPost('productid');
            $barcode = $this->request->getPost('barcode');
            $product = $this->request->getPost('productname');
            $qty = $this->request->getPost('productqty');
            $supplier = $this->request->getPost('suppliername');
            $totalprice = $this->request->getPost('totalprice');
            $description = $this->request->getPost('description');
            $user = $this->request->getPost('userid');

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'productqty' => [
                    'label' => 'Jumlah Stok masuk',
                    'rules' => 'greater_than[0]',
                    'errors' => [
                        'greater_than[0]' => '{field} tidak boleh kurang dari satu!'
                    ]
                ],
                'barcode' => [
                    'label' => 'Kode barcode',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'productname' => [
                    'label' => 'Produk',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'date' => [
                    'label' => 'Tanggal',
                    'rules' => 'valid_date',
                    'errors' => [
                        'required' => '{field} Salah'
                    ]
                ],

            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'errorcode' => $validation->getError('barcode'),
                        'errorproduct' => $validation->getError('productname'),
                        'errordate' => $validation->getError('date'),
                        'errorqty' => $validation->getError('productqty'),
                    ]
                ];
            } else {
                $row = $this->product->find($id);
                if ($row['qr_product'] ==  $barcode) {
                    $this->stock = new StockTransactionModel();
                    $this->stock->insert([
                        'product_trans' => $id,
                        'type_trans' => "In",
                        'detail_trans' => $description,
                        'supplier_trans' => $supplier,
                        'qty_trans' => $qty,
                        'date_trans' => $date,
                        'totalpay_trans' => $totalprice,
                        'sig_trans' => $user,
                    ]);
                    $currentstock = $row['stock_product'];
                    $this->product->update($id, [
                        'stock_product' => $currentstock + $qty,
                    ]);

                    $msg = [
                        'sukses' => 'Produk berhasil ditambahkan'
                    ];
                } else {
                    $msg = ['notfound' => 'TIdak Ditemukan'];
                }
            }

            echo json_encode($msg);
        }
    }
    public function reducetransaction()
    {
        if ($this->request->isAJAX()) {
            $date = $this->request->getPost('date');
            $id = $this->request->getPost('productid');
            $barcode = $this->request->getPost('barcode');
            $product = $this->request->getPost('productname');
            $qty = $this->request->getPost('productqty');
            $stock = $this->request->getPost('productstock');
            $supplier = $this->request->getPost('suppliername');
            $totalprice = $this->request->getPost('totalprice');
            $description = $this->request->getPost('description');
            $user = $this->request->getPost('userid');

            $validation = \Config\Services::validation();
            $doValid = $this->validate([
                'productqty' => [
                    'label' => 'Jumlah Stok masuk',
                    'rules' => 'greater_than[0]|less_than_equal_to[' . $stock . ']',
                    'errors' => [
                        'greater_than[0]' => '{field} tidak boleh kurang dari satu!',
                        'less_than_equal_to[]' => '{field} tidak boleh kurang dari satu!',
                    ]
                ],
                'barcode' => [
                    'label' => 'Kode barcode',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'productname' => [
                    'label' => 'Produk',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ],
                'date' => [
                    'label' => 'Tanggal',
                    'rules' => 'valid_date',
                    'errors' => [
                        'required' => '{field} Salah'
                    ]
                ],

            ]);
            if (!$doValid) {
                $msg = [
                    'error' => [
                        'errorcode' => $validation->getError('barcode'),
                        'errorproduct' => $validation->getError('productname'),
                        'errordate' => $validation->getError('date'),
                        'errorqty' => $validation->getError('productqty'),
                    ]
                ];
            } else {
                $row = $this->product->find($id);
                if ($row['qr_product'] ==  $barcode) {
                    $this->stock = new StockTransactionModel();
                    $this->stock->insert([
                        'product_trans' => $id,
                        'type_trans' => "Out",
                        'detail_trans' => $description,
                        'supplier_trans' => $supplier,
                        'qty_trans' => $qty,
                        'date_trans' => $date,
                        'totalpay_trans' => $totalprice,
                        'sig_trans' => $user,
                    ]);
                    $currentstock = $row['stock_product'];
                    $this->product->update($id, [
                        'stock_product' => $currentstock - $qty,
                    ]);

                    $msg = [
                        'sukses' => 'Produk berhasil ditambahkan'
                    ];
                } else {
                    $msg = ['notfound' => 'TIdak Ditemukan'];
                }
            }

            echo json_encode($msg);
        }
    }
    public function stockout()
    {
        $data = [
            'title' => "Barang Keluar",
        ];
        return view('stockin/outtransactionform', $data);
    }
}
