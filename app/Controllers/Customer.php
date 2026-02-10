<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelCustomers;
use CodeIgniter\API\ResponseTrait;

class Customer extends BaseController
{
    use ResponseTrait;
    public function listCustomers()
    {
        $mc = new ModelCustomers();
        return $this->respond(['data' => $mc->select('*')->where('aktif', '1')->orderBy('name', 'ASC')->findAll()]);
    }

    public function cariCustomers($kataPencarian)
    {
        $mc = new ModelCustomers();
        return $this->respond(['data' => $mc->select('*')->where('aktif', '1')->like('name', $kataPencarian)->orderBy('name', 'ASC')->findAll()]);
    }
}
