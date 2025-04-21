<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Tempsellmodel;
use App\Models\DataProductModel;
use App\Models\SellModel;
use App\Models\DetailSellModel;

use CodeIgniter\HTTP\Request;
use Config\Services;

class Sell extends BaseController
{
    public function __construct()
    {
        $this->temp = new TempSellmodel();
    }
    public function index()
    {
        $data = [
            'title' => 'Penjualan',
            'invoice' => $this->createinvoice(),
        ];

        return view('sell/transaction', $data);
    }
    public function createinvoice()
    {
        // $date = $this->request->getPost('tanggal');
        $date = date('Y-m-d');
        $query = $this->db->query("SELECT count(invoice_sell) AS invoice FROM sell WHERE 
            DATE_FORMAT(date_sell, '%Y-%m-%d') = '$date'");
        $result = $query->getRowArray();
        $data = $result['invoice'];
        // dd($date);

        $lastcount = substr($data, -4);


        $nextcount = intval($lastcount) + 1;

        $invoicesell = "S" . date('dmy', strtotime($date)) . sprintf('%05s', $nextcount);
        return $invoicesell;
    }

    public function dataDetail()
    {
        $invoice = $this->request->getPost('nofaktur');
        $session = session();
        $idcashier = $session->get('id');

        $sellTemp = $this->db->table('temp_sell');
        $querySelect = $sellTemp->select('sellId_temp as id, sellProduct_temp as idproduct ,qr_product as qrcode, 
        name_product as product, sellPrice_temp as sellprice, sellQty_temp as qty, 
        sellSubtotal_temp as subtotal')->join('product', 'sellProduct_temp=id_product')->where('sellInvoice_temp', $idcashier);
        $data = [
            'datadetail' => $querySelect->get(),

        ];
        $msg = [
            'data' => view('sell/transactiondetail', $data),

        ];
        echo json_encode($msg);
    }
    public function viewDataProduct()
    {
        if ($this->request->isAJAX()) {
            $keyword = $this->request->getPost('keyword');
            $data = [
                'keyword' => $keyword
            ];
            $msg = [
                'viewmodal' => view('sell/viewmodalproduct', $data),
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
                    $row[] = number_format($list->sellPrice_product, 0, ",", ".");
                    $row[] = $list->name_supplier;
                    $row[] = "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"choose('" . $list->qr_product . "','" . $list->name_product . "')\">Pilih</button>";
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
    public function savetoTemp()
    {
        if ($this->request->isAJAX()) {
            $barcode = $this->request->getPost('barcode');
            $productname = $this->request->getPost('productname');
            $qty = $this->request->getPost('qty');
            $invoice = $this->request->getPost('invoice');
            $session = session();
            $idcashier = $session->get('id');


            #mengecek produk
            if (strlen($productname) > 0) {
                $queryCheckproduct = $this->db->table('product')->where('qr_product', $barcode)
                    ->where('name_product', $productname)->get();
            } else {
                $queryCheckproduct = $this->db->table('product')->like('qr_product', $barcode)
                    ->orlike('name_product', $barcode)->get();
            }
            // $queryCheckproduct = $this->db->table('product')->like('qr_product', $barcode)
            //     ->orlike('name_product', $barcode)->get();

            $totalData = $queryCheckproduct->getNumRows();
            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak',
                ];
            } else if ($totalData == 1) {
                //Insert to temp penjualan
                $tempSellTable = $this->db->table('temp_sell');
                $rowProduct = $queryCheckproduct->getRowArray();

                $stockproduct = $rowProduct['stock_product'];
                if (intval($stockproduct) == 0) {
                    $msg = [
                        'error' => "Maaf stock habis!",
                    ];
                } else if ($qty > intval($stockproduct)) {
                    $msg = [
                        'error' => "Maaf stock tidak mencukupi!",
                    ];
                } else if ($qty < 1) {
                    $msg = [
                        'error' => "Jumlah item tidak boleh kurang dari 1!",
                    ];
                } else {
                    $inserdata = [
                        'sellinvoice_temp' => $idcashier,
                        'sellProduct_temp' => $rowProduct['id_product'],
                        'sellPrice_temp' => $rowProduct['sellPrice_product'],
                        'buyPrice_temp' => $rowProduct['purchasePrice_product'],
                        'sellQty_temp' => $qty,
                        'sellSubtotal_temp' => floatval($rowProduct['sellPrice_product']) * $qty,
                    ];
                    $tempSellTable->insert($inserdata);
                    $msg = ['success' => 'Berhasil'];
                }
            } else {
                $msg = ['error' => 'Maaf Produk tidak ditemukan..'];
            }
            echo json_encode($msg);
        }
    }
    public function countTotalPay()
    {
        if ($this->request->isAJAX()) {
            $invoice = $this->request->getPost('invoice');
            $tempSellTable = $this->db->table('temp_sell');

            $queryTotal = $tempSellTable->select('COALESCE(SUM(sellSubtotal_temp),0) as totalPay')
                ->where('sellinvoice_temp', $invoice)->get();
            $rowTotal = $queryTotal->getRowArray();
            $msg = [
                'totalPay' => number_format($rowTotal['totalPay'], 0, ",", "."),
            ];
        }
        echo json_encode($msg);
    }
    public function deleteitem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $tempSellTable = $this->db->table('temp_sell');
            $queryDelete = $tempSellTable->delete(['sellID_temp' => $id]);

            if ($queryDelete) {
                $msg = [
                    'success' => 'berhasil'
                ];
                echo json_encode($msg);
            }
        }
    }
    public function cancelTransaction()
    {
        if ($this->request->isAJAX()) {
            $session = session();
            $idcashier = $session->get('id');
            $tempSellTable = $this->db->table('temp_sell')->where('sellInvoice_temp', $idcashier);
            $deleteData = $tempSellTable->delete();

            if ($deleteData) {
                $msg = [
                    'success' => 'berhasil',
                ];
            }
            echo json_encode($msg);
        }
    }
    public function payment()
    {
        if ($this->request->isAJAX()) {
            $invoice = $this->request->getPost('invoice');
            $idcashier = $this->request->getPost('nocashier');

            $tempSellTable = $this->db->table('temp_sell');
            $checkdataTempSell = $tempSellTable->getWhere(['sellinvoice_temp' => $idcashier]);

            $queryTotal = $tempSellTable->select('COALESCE(SUM(sellSubtotal_temp),0) as totalPay')
                ->where('sellinvoice_temp', $idcashier)->get();
            $rowTotal = $queryTotal->getRowArray();

            if ($checkdataTempSell->getNumRows() > 0) {
                //Modal Pembayaran
                $data = [
                    'invoice'  => $invoice,
                    'idcashier' => $idcashier,
                    'totalpay' => $rowTotal['totalPay'],
                ];

                $msg = [
                    'data' => view('sell/paymentmodal', $data)
                ];
            } else {
                $msg = [
                    'error' => 'Item belum ada..',
                ];
            }
            echo json_encode($msg);
        }
    }
    public function savePayment()
    {
        if ($this->request->isAJAX()) {
            // $invoice = $this->request->getPost('invoice');
            $invoice = $this->createinvoice();
            $idcashier = $this->request->getPost('idcashier');
            $totalprice = $this->request->getPost('totalprice');
            $totalpay = str_replace(".", "", $this->request->getPost('totalnet'));
            $percentdisc = str_replace(".", "", $this->request->getPost('percentdisc'));
            $nominaldisc = str_replace(".", "", $this->request->getPost('nominaldisc'));
            $paysaldo = str_replace(".", "", $this->request->getPost('paysaldo'));
            $paychange = str_replace(".", "", $this->request->getPost('paychange'));

            $sellTable = $this->db->table('sell');
            $tempSellTable = $this->db->table('temp_sell');
            $detailSellTable = $this->db->table('detail_sell');

            #insert data invoice penjualan ke tabel sell
            $dataInsertSell = [
                'invoice_sell' => $invoice,
                'date_sell' => date("Y-m-d H:i:s"),
                'sig_sell' => $idcashier,
                'percentdisc_sell' => $percentdisc,
                'nominaldisc_sell' => $nominaldisc,
                'totalprice_sell' => $totalprice,
                'totalpay_sell' => $totalpay,
                'pay_sell' => $paysaldo,
                'change_sell' => $paychange,
            ];
            $sellTable->insert($dataInsertSell);
            $copyDataTemp = $tempSellTable->getWhere(['sellinvoice_temp' => $idcashier]);
            $fileTemptoDetailSell = [];
            $filearray = $copyDataTemp->getResultArray();

            foreach ($copyDataTemp->getResultArray() as $row) {
                $fileTemptoDetailSell[] = [
                    'invoice_Dsell' => $invoice,
                    'idproduct_Dsell' => $row['sellProduct_temp'],
                    'sellPrice_Dsell' => $row['sellPrice_temp'],
                    'buyPrice_Dsell' => $row['buyPrice_temp'],
                    'qtyproduct_Dsell' => $row['sellQty_temp'],
                    'subtotal_Dsell' => $row['sellSubtotal_temp'],
                ];
            }


            $tempSellTable->where('sellInvoice_temp', $idcashier)->delete();
            $detailSellTable->insertBatch($fileTemptoDetailSell);

            $msg = [
                'success' => "berhasil",
                'invoice' => $invoice,
            ];
            echo json_encode($msg);
        }
    }
    public function printinvoice()
    {
        $invoice = $this->request->getPost('aninvoice');
        $this->sell = new SellModel();
        $this->detail = new DetailSellModel();
        $datatable = $this->detail->join('product', 'id_product=idproduct_Dsell');
        $dataproduct = $datatable->where('invoice_Dsell', $invoice);
        $data = [
            'nota' => $this->sell->find($invoice),
            'products' => $dataproduct->findAll(),
        ];

        $msg = [
            'nota' => view('sell/invoice', $data),
            'invoice' => $invoice,
        ];
        // return view('sell/invoice', $data);
        echo json_encode($msg);
    }
}
