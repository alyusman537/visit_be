<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// use CodeIgniter\API\ResponseTrait;

class Render extends BaseController
{
    // use ResponseTrait;
    public function image($imageName)
    {
        $mimeType = 'image/jpg';
        try {
            $image = file_get_contents(WRITEPATH.'uploads/customer/'.$imageName);
            $this->response
            ->setStatusCode(200)
            ->setContentType($mimeType)
            ->setBody($image)
            ->send();
        } catch (\Throwable) {
            $no_image = file_get_contents(WRITEPATH.'uploads/No_Image_Available.jpg');
            $this->response
            ->setStatusCode(404)
            ->setContentType($mimeType)
            ->setBody($no_image)
            ->send();
        }

    }
    public function js ($jsName)
    {
        $mimeType = 'text/javascript';
        try {
            //code...
            $js = file_get_contents(__DIR__.'/../Views/js/'.$jsName);
            // if($js === FALSE) show_404();
    
            $this->response
            ->setStatusCode(200)
            ->setContentType($mimeType)
            ->setBody($js)
            ->send();
        } catch (\Throwable) {
            $this->response
            ->setStatusCode(404)
            ->setContentType($mimeType)
            ->setBody("not found")
            ->send();
        }
    }
}