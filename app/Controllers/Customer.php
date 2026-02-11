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

    public function addCustomer()
    {
        helper(['form']); // Load the form helper
        $rules = [
            'customer_id' => 'required|min_length[4]',
            'name' => 'required|min_length[3]|max_length[50]',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone' => 'required'
            ];
            
            if (!$this->validate($rules)) return $this->fail($this->validator->getErrors(), 409);
            $mc = new ModelCustomers();
        $json = $this->request->getJSON();
        $customer = $mc->select('customer_id')->where('customer_id', $json->customer_id)->first();
        if($customer) return $this->fail('ID Customer yang anda masukkan sudah ada', 400);
        $data = [
            'customer_id' => $json->customer_id,
            'name' => $json->name,
            'address' => $json->address,
            'latitude' => $json->latitude,
            'longitude' => $json->longitude,
            'phone' => $json->phone,
            'aktif' => 1
        ];
        try {
            $mc->insert($data);
            return $this->respond($data, 201);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage(), $th->getCode());
        }
    }
}
