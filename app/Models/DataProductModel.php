<?php

namespace App\Models;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;
use Config\Services;

class DataProductModel extends Model
{
    protected $table = 'product';
    // protected $primaryKey = 'id_product';

    protected $column_order = ['id_product', 'name_product', 'qr_product', 'stock_product', 'sellPrice_product', 'supplier_product', null];
    protected $column_search = ['name_product', 'qr_product'];
    protected $order = ['name_product' => 'DESC'];
    protected $request;
    protected $db;
    protected $dt;

    public function __construct(RequestInterface $request)
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = $request;
    }

    private function getDatatablesQuery($keywordcode)
    {
        if (strlen($keywordcode) == 0) {
            $this->dt = $this->db->table($this->table)->join('supplier', 'id_supplier=supplier_product')->where('qr_product !=', null);
        } else {
            $this->dt = $this->db->table($this->table)->join('supplier', 'id_supplier=supplier_product')
                ->where('qr_product !=', null)
                ->like('qr_product', $keywordcode)->orlike('name_product', $keywordcode);
        }
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $this->request->getPost('search')['value']);
                } else {
                    $this->dt->orLike($item, $this->request->getPost('search')['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        if ($this->request->getPost('order')) {
            $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    public function getDatatables($keywordcode)
    {
        $this->getDatatablesQuery($keywordcode);
        if ($this->request->getPost('length') != -1)
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function countFiltered($keywordcode)
    {
        $this->getDatatablesQuery($keywordcode);
        return $this->dt->countAllResults();
    }

    public function countAll($keywordcode)
    {
        if (strlen($keywordcode) == 0) {
            $tbl_storage = $this->db->table($this->table)->join('supplier', 'id_supplier=supplier_product')->where('qr_product !=', null);
        } else {
            $tbl_storage = $this->db->table($this->table)->join('supplier', 'id_supplier=supplier_product')->where('qr_product !=', null)
                ->like('qr_product', $keywordcode)->orlike('name_product', $keywordcode);
        }
        return $tbl_storage->countAllResults();
    }
}
