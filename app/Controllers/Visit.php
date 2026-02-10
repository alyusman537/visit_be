<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Decoder;
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
        return $this->respond($data);
        // return print_r($token);
    }
}
