<?php

namespace App\Models;

use App\Controllers\Pages\Product;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'product';
    protected $primaryKey = 'id_product';

    protected $allowedFields = [
        'id_product',
        'name_product',
        'qr_product',
        'stock_product',
        'sellPrice_product',
        'purchasePrice_product',
        'image_product',
        'supplier_product',
    ];

    public function findData($find)
    {
        return $this->table('product')->join('supplier', 'id_supplier=supplier_product')->orlike('qr_product', $find)->orlike('name_product', $find);
    }
    public function getProduct($id)
    {
        return $this->find($id);
    }
}
