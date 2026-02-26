<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Decoder;
use App\Models\ModelCustomers;
use App\Models\ModelVisits;
use CodeIgniter\API\ResponseTrait;

class Visit extends BaseController
{
    use ResponseTrait;
    public function byUser($tgl_awal, $tgl_akhir)
    {
        $decoder = new Decoder();
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = $decoder->token($header);
        $mv = new ModelVisits();
        $data = $mv->byUser($token->sub, $tgl_awal, $tgl_akhir);
        return $this->respond($data);
        // return print_r($token);
    }

    public function byUserToDay()
    {
        $decoder = new Decoder();
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = $decoder->token($header);
        $mv = new ModelVisits();
        $data = $mv->byUserToDay($token->sub);
        return $this->respond(['data' => $data]);
        // return print_r($token);
    }

    public function addVisit()
    {
        $decoder = new Decoder();
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = $decoder->token($header);

        helper(['form']); // Load the form helper

        $rules = [
            'customer_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => [
                // 'label' => 'Image File',
                'rules' => [
                    'uploaded[photo]',
                    'is_image[photo]',
                    'mime_in[photo,image/jpg,image/jpeg]',
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
        $customer_id = $this->request->getVar('customer_id');
        $customer = $mc->select('id, customer_id, name, address, latitude, longitude, phone, aktif')->where('customer_id', $customer_id)->first();
        if(!$customer) return $this->fail('ID Customer yang anda masukkan tidak terdaftar', 400);
        if($customer['aktif'] == '0') return $this->fail('ID Customer yang anda masukkan telah nonaktif. Silahkan hubungi Admin', 400);
        $lat1 = (double) $customer['latitude'];
        $lon1 = (double) $customer['longitude'];
        $lat2 = (double) $this->request->getVar('latitude');
        $lon2 = (double) $this->request->getVar('longitude');
        $hitung_jarak = $this->getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2);
        $notes = !$this->request->getVar('notes') ? null : $this->request->getVar('notes');
        if(floor($hitung_jarak['meters']) > 10) return $this->fail('Jarak Anda dengan lokasi toko terlalu jauh.', 400);
        // return print_r([floor($hitung_jarak['meters']), $notes]);
        $mv = new ModelVisits();
        $x_file = $this->request->getFile('photo');
        try {
            $namaFoto = $x_file->getRandomName();

            // $x_file->move(WRITEPATH . 'uploads/bukti', $namaFoto);
            $image = service('image');
            $image->withFile($x_file)
                ->resize(500, 500, true, 'height')
                ->save(WRITEPATH . '/uploads/kunjungan/' . $namaFoto);

            unlink($x_file);
            $data = [
                'customer_id' => $customer_id,
                'latitude' => $lat1,
                'longitude' => $lon1,
                'photo_path' => $namaFoto,
                'visit_date' => date('Y-m-d H:i:s'),
                'notes' => $notes,
                'user_id' => $token->sub
            ];
            $mv->insert($data);
            return $this->respondCreated($data);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage(), 500);
        }
    }

    private function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet  = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }
}
