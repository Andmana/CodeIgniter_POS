<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Request;
use App\Models\ProductModel;

use App\Models\SellModel;
use App\Models\SupplierModel;
use App\Models\DetailSellModel;


class Dashboard extends BaseController

{
    public function __construct()
    {
        $this->sell = new SellModel();
        $this->detail = new DetailSellModel();
        $this->product = new ProductModel();
    }
    public function yearly()
    {
        $datasell = $this->sell;
        $yearly = $datasell->select('sum(totalpay_sell) as totalpay')
            ->where('DATE_FORMAT(date_sell, "%Y")', date("Y"))
            ->groupBy('date_format(date_sell, "%Y")', 'date_format(date_sell, "%Y")');
        return $yearly->first();
    }
    public function monthly()
    {
        $datasell = $this->sell;
        $monthly = $datasell->select('sum(totalpay_sell) as totalpay')
            ->where('DATE_FORMAT(date_sell, "%Y %m")', date("Y m"))
            ->groupBy('date_format(date_sell, "%Y %m")', 'date_format(date_sell, "%Y %m")');

        return $monthly->first();
    }
    public function daily()
    {
        $datasell = $this->sell;
        $daily = $datasell->select('sum(totalpay_sell) as totalpay, count(invoice_sell) as invoice,')
            ->where('DATE_FORMAT(date_sell, "%Y %m %d")', date("Y m d"))
            ->groupBy('date_format(date_sell, "%Y %m %d")', 'date_format(date_sell, "%Y %m %d")');
        return $daily->first();
    }


    public function dailyuser()
    {
        $session = session();
        $idcashier = $session->get('id');
        $datasell = $this->sell;
        $daily = $datasell->select('sum(totalpay_sell) as totalpay, count(invoice_sell) as invoice,')
            ->where('DATE_FORMAT(date_sell, "%Y %m %d")', date("Y m d"))
            ->where('sig_sell', $idcashier)
            ->groupBy('date_format(date_sell, "%Y %m %d")', 'date_format(date_sell, "%Y %m %d")');


        return $daily->first();
    }
    public function saleuser()
    {
        $session = session();
        $idcashier = $session->get('id');
        $datasell = $this->sell;
        $daily = $datasell->select('sum(totalpay_sell) as totalpay, count(invoice_sell) as invoice, sum(qtyproduct_Dsell) as qtys, count(idproduct_Dsell) as items')
            ->join('detail_sell', 'invoice_sell=invoice_Dsell')
            ->where('DATE_FORMAT(date_sell, "%Y %m %d")', date("Y m d"))
            ->where('sig_sell', $idcashier)
            ->groupBy('date_format(date_sell, "%Y %m %d")', 'date_format(date_sell, "%Y %m %d")');


        return $daily->first();
    }

    public function tester()
    {
        $id = 2;
        $supp = new SupplierModel();
        $supp->delete($id);
        $dates = $supp->findAll();


        dd($dates);
    }
    public function index()
    {
        $session = session();
        $idcashier = $session->get('id');
        $daily = $this->daily();
        if ($daily == null) {
            $daily['totalpay'] = 0;
            $daily['invoice'] = 0;
        }
        $monthly = $this->monthly();
        if ($monthly == null) {
            $monthly['totalpay'] = 0;
        }
        $yearly = $this->yearly();
        if ($yearly == null) {
            $yearly['totalpay'] = 0;
        }
        $saleuser = $this->saleuser();
        if ($saleuser == null) {
            $saleuser['totalpay'] = 0;
            $saleuser['invoice'] = 0;
            $saleuser['qtys'] = 0;
            $saleuser['items'] = 0;
        }
        $data = [
            'title' => "Dashbboard",
            'yearly' => $yearly,
            'monthly' => $monthly,
            'daily' => $daily,
            'usersale' => $saleuser,
        ];
        return view('dashboard/Home', $data);
    }
    public function error()
    {
        $data = [
            'title' => "Error",
            'ecode' => '505',
            'msg' => 'Kamu tidak punya izin untuk mengakses',
        ];
        return view('error/restricted', $data);
    }
    public function invoice()
    {
        $data = [
            'title' => "Dashbboard",
        ];
        return view('sell/invoice', $data);
    }
}
