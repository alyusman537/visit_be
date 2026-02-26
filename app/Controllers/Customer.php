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

    public function customerById($idCustomer)
    {
        $mc = new ModelCustomers();
        try {
            $customer = $mc->select('*')->where('id', $idCustomer)->first();
            if(!$customer) return $this->fail('ID customer tidak valid', 400);
            $data = [
                'name' => $customer['name'],
                'address' => $customer['address'],
                'latitude' => $customer['latitude'],
                'longitude' => $customer['longitude'],
                'phone' => $customer['phone'],
                'aktif' => $customer['aktif'],
                'imagePath' => base_url('/render/image/'.$customer['imagePath'])
            ];
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage(), $th->getCode());
        }
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
            // 'customer_id' => 'required|min_length[4]',
            'name' => 'required|min_length[3]|max_length[50]',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone' => 'required',
            'imagePath' => [
                // 'label' => 'Image File',
                'rules' => [
                    'uploaded[imagePath]',
                    'is_image[imagePath]',
                    'mime_in[imagePath,image/jpg,image/jpeg]',
                ],
                'errors' => [
                    'uploaded' => 'tidak ada gambar yagn diupload',
                    'is_image' => 'file harus berupa gambar',
                    'mime_in' => 'gambar harus berupa jpg atau jpeg'
                ]
            ],
        ];

        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors(), 409);
        $mc = new ModelCustomers();
        // $json = $this->request->getJSON();
        // $customer_id = $this->request->getVar('customer_id');
        // $customer = $mc->select('customer_id')->where('customer_id', $customer_id)->first();
        // if($customer) return $this->fail('ID Customer yang anda masukkan sudah ada', 400);

        $x_file = $this->request->getFile('imagePath');
        try {
            $namaFoto = $x_file->getRandomName();

            // $x_file->move(WRITEPATH . 'uploads/bukti', $namaFoto);
            $image = service('image');
            $image->withFile($x_file)
                ->resize(500, 500, true, 'height')
                ->save(WRITEPATH . '/uploads/customer/' . $namaFoto);

            unlink($x_file);
            $data = [
                // 'customer_id' => $customer_id,
                'name' => $this->request->getVar('name'),
                'address' => $this->request->getVar('address'),
                'latitude' => (float) $this->request->getVar('latitude'),
                'longitude' => (float) $this->request->getVar('longitude'),
                'phone' => $this->request->getVar('phone'),
                'aktif' => 1,
                'imagePath' => $namaFoto
            ];
            $mc->insert($data);
            return $this->respondCreated($data);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage(), 500);
        }
    }
}
