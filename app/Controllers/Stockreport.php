<?php

namespace App\Controllers;

use Config\Services;
use App\Models\StockTransactionModel;


class Stockreport extends BaseController
{
    // [ ... baris kode sebelumnya ]
    public function __construct()
    {
        $this->stock = new StockTransactionModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Laporan Barang masuk',
        ];

        return view('stockreport/index', $data);
    }
    public function dataTable()
    {
        if ($this->request->isAJAX()) {
            $group = $this->request->getPost('group');
            $types = $this->request->getPost('types');
            $stocinktable = $this->stock;
            if ($types == 'BOTH') {
                $stocinktable->findAll;;
            } else {
                $stocinktable->where('type_trans', $types);
            }
            if ($group == "onebyone") {
                $tableTStock = $stocinktable->join('users', 'sig_trans=id')
                    ->join('product', 'id_product=product_trans')
                    ->join('supplier', 'id_supplier=supplier_trans');
                $data = [
                    'group' => $group,
                    'table' => $tableTStock->findall(),
                ];
                $msg = [
                    'data' => view('stockreport/tableeach', $data),
                ];
            } else {
                if ($group == "day") {
                    $queryGroup = "DATE_FORMAT(date_trans, '%Y-%m-%d'";
                    $tableTStock = $stocinktable
                        ->select('count(id_trans) as transaction, DATE_FORMAT(date_trans, "%W, %d %M %Y") as date, sum(totalpay_trans) as total, sum(qty_trans) as items, type_trans as types')
                        ->groupBy('DATE_FORMAT(date_trans, "%Y-%m-%d")', $queryGroup)->groupBy('type_trans');
                } else if ($group == "month") {
                    $queryGroup = "DATE_FORMAT(date_trans, '%Y-%m'";
                    $tableTStock = $stocinktable
                        ->select('count(id_trans) as transaction, DATE_FORMAT(date_trans, "%M %Y") as date, sum(totalpay_trans) as total, sum(qty_trans) as items, type_trans as types')
                        ->groupBy('DATE_FORMAT(date_trans, "%Y-%m")', $queryGroup)->groupBy('type_trans');
                } else if ($group == "year") {
                    $queryGroup = "DATE_FORMAT(date_trans, '%Y'";
                    $tableTStock = $stocinktable
                        ->select('count(id_trans) as transaction, DATE_FORMAT(date_trans, "%Y") as date, sum(totalpay_trans) as total , sum(qty_trans) as items, type_trans as types')
                        ->groupBy('DATE_FORMAT(date_trans, "%Y")', $queryGroup)->groupBy('type_trans');
                }
                $data = [
                    'group' => $group,
                    'table' => $tableTStock->findAll(),
                ];
                $msg = [
                    'data' => view('stockreport/tablegroup', $data),
                ];
            }
            echo json_encode($msg);
        }
    }
}
