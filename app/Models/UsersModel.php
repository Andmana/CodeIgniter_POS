<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{

    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $allowedFields = ['name', 'username', 'password', 'level', 'email', 'address', 'phone', 'image'];

    protected $useTimestamps = true;
    protected $createdField  = 'created';
    protected $updatedField  = 'updated';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function findData($find)
    {
        return $this->table('users')->join('level', 'id_level=level')->orlike('name', $find)->orlike('username', $find);
    }
    public function getUser($id)
    {
        return $this->find($id);
    }
}
