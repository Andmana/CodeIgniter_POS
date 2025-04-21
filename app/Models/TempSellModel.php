<?php

namespace App\Models;

use CodeIgniter\Model;

class TempSellmodel extends Model
{
    protected $table      = 'temp_sell';
    public function findData()
    {
        return $this->table('temp_sell')->join('product', 'sellProduct_temp=id_product');
    }
}
