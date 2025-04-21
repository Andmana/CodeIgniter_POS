<?php

namespace App\Controllers;

use App\Models\DataSupplierModel;
use Config\Services;
use App\Models\SellModel;
use App\Models\DetailSellModel;
use \Dompdf\Dompdf;
use Dompdf\Options;


class SaleReport extends BaseController
{
    // [ ... baris kode sebelumnya ]
    public function __construct()
    {
        $this->sell = new SellModel();
        $request = Services::request();
        $this->detail = new DetailSellModel();
    }
    public function index()
    {
        $numPages = $this->request->getVar('pag_sale') ? $this->request->getVar('pag_sale') : 1;
        $data = [
            'title' => 'Laporan Transaksi Penjualan List',
        ];

        return view('sale/salereport', $data);
    }
    public function dataTable()
    {
        if ($this->request->isAJAX()) {
            $group = $this->request->getPost('group');
            $sellData = $this->sell;
            if ($group == "onebyone") {
                $sellDataquery = $sellData->join('users', 'sig_sell=id');
                $data = [
                    'group' => $group,
                    'table' => $sellDataquery->findall(),
                ];
                $msg = [
                    'data' => view('sale/saletable', $data),
                ];
            } else {
                if ($group == "day") {
                    $queryGroup = "DATE_FORMAT(date_sell, '%Y-%m-%d'";
                    $sellDataquery = $sellData
                        ->select('count(invoice_sell) as invoice, DATE_FORMAT(date_sell, "%W, %d %M %Y") as date, sum(totalpay_sell) as total')
                        ->groupBy('DATE_FORMAT(date_sell, "%Y-%m-%d")', $queryGroup);
                } else if ($group == "month") {
                    $queryGroup = "DATE_FORMAT(date_sell, '%Y-%m'";
                    $sellDataquery = $sellData
                        ->select('count(invoice_sell) as invoice, DATE_FORMAT(date_sell, "%M %Y") as date, sum(totalpay_sell) as total')
                        ->groupBy('DATE_FORMAT(date_sell, "%Y-%m")', $queryGroup);
                } else if ($group == "year") {
                    $queryGroup = "DATE_FORMAT(date_sell, '%Y'";
                    $sellDataquery = $sellData
                        ->select('count(invoice_sell) as invoice, DATE_FORMAT(date_sell, "%Y") as date, sum(totalpay_sell) as total')
                        ->groupBy('DATE_FORMAT(date_sell, "%Y")', $queryGroup);
                }
                $data = [
                    'group' => $group,
                    'table' => $sellDataquery->findAll(),
                ];
                $msg = [
                    'data' => view('sale/saletablegroup', $data),

                ];
            }
            echo json_encode($msg);
        }
    }
    public function printinvoice($nota)
    {
        // $invoice = $this->request->getPost('aninvoice');
        $invoice = $nota;
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
        return view('sell/invoice', $data);
    }
    public function viewDataProduct()
    {
        if ($this->request->isAJAX()) {
            $datadetail = $this->detail->select('name_product, qr_product, sum(qtyproduct_Dsell) as qtys')->join('sell', 'invoice_sell = invoice_Dsell')->join('product', 'id_product=idproduct_Dsell')->groupBy('id_product');
            $clause = $this->request->getPost('group');
            $date = $this->request->getPost('date');

            // $clause = 'month';
            if ($clause == 'year') {
                $datatable = $datadetail->where('DATE_FORMAT(date_sell, "%Y")', $date)->findAll();
            } else if ($clause == 'month') {
                $datatable = $datadetail->where('DATE_FORMAT(date_sell, "%M %Y")', $date)->findAll();
            } else if ($clause == 'day') {
                $tgl = date_create($date);
                $dates = date_format($tgl, 'Y m d');
                $datatable = $datadetail->where('DATE_FORMAT(date_sell, "%Y %m %d")', $dates)->findAll();
            }
            $data = [
                'where' => $clause,
                'date' => $date,
                'lists' => $datatable,
            ];

            $msg = [
                'viewmodal' => view('sale/detailmodal', $data),
            ];
            echo json_encode($msg);
        }
    }
    public function printpdf()
    {
    }
}
