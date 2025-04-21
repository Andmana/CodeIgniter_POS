<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table      = 'supplier';
    protected $primaryKey = 'id_supplier';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_supplier',
        'name_supplier',
        'phone_supplier',
        'address_supplier',
    ];

    public function finddata($find)
    {
        return $this->table('supplier')->like('name_supplier', $find);
    }
}
