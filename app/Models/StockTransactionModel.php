<?php

namespace App\Models;

use App\Controllers\Pages\Product;
use CodeIgniter\Model;

class StockTransactionModel extends Model
{
    protected $table      = 'trans_stock';
    protected $primaryKey = 'id_trans';

    protected $allowedFields = [
        'id_trans',
        'product_trans',
        'type_trans',
        'detail_trans',
        'supplier_trans',
        'qty_trans',
        'date_trans',
        'totalpay_trans',
        'sig_trans',
    ];
}
