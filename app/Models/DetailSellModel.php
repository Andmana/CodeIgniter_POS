<?php

namespace App\Models;

// use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Model;
// use Config\Services;

class DetailSellModel extends Model
{
    protected $table      = 'detail_sell';
    protected $primaryKey = 'id_Dsell';

    // public function __construct(RequestInterface $request)
    // {
    //     parent::__construct();
    //     $this->db = db_connect();
    //     $this->request = $request;
    // }

    // private function getDatatablesQuery($keywordcode)
    // {
    //     $this->dt = $this->db->table($this->table)->join('sell', 'invoice_sell=invoice_Dsell')->join('product', 'id_product=idproduct_Dsell')->groupBy('idproduct_Dsell');
    //     $i = 0;
    //     foreach ($this->column_search as $item) {
    //         if ($this->request->getPost('search')['value']) {
    //             if ($i === 0) {
    //                 $this->dt->groupStart();
    //                 $this->dt->like($item, $this->request->getPost('search')['value']);
    //             } else {
    //                 $this->dt->orLike($item, $this->request->getPost('search')['value']);
    //             }
    //             if (count($this->column_search) - 1 == $i)
    //                 $this->dt->groupEnd();
    //         }
    //         $i++;
    //     }

    //     if ($this->request->getPost('order')) {
    //         $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
    //     } else if (isset($this->order)) {
    //         $order = $this->order;
    //         $this->dt->orderBy(key($order), $order[key($order)]);
    //     }
    // }

    // public function getDatatables($keywordcode)
    // {
    //     $this->getDatatablesQuery($keywordcode);
    //     if ($this->request->getPost('length') != -1)
    //         $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
    //     $query = $this->dt->get();
    //     return $query->getResult();
    // }

    // public function countFiltered($keywordcode)
    // {
    //     $this->getDatatablesQuery($keywordcode);
    //     return $this->dt->countAllResults();
    // }

    // public function countAll($keywordcode)
    // {
    //     $tbl_storage = $this->db->table($this->table)->join('sell', 'invoice_sell=invoice_Dsell')->join('product', 'id_product=idproduct_Dsell')->groupBy('idproduct_Dsell');

    //     return $tbl_storage->countAllResults();
    // }
}
