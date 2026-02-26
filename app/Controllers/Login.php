<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelUsers;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class Login extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        helper(['form']); // Load the form helper

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[4]',
        ];

        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors(), 409);

        $mu = new ModelUsers();
        $json = $this->request->getJSON();
        $username = $json->username;
        try {
            $user = $mu->select('*')->where('user_id', $username)->orWhere('email', $username)->first();
            if (!$user) return $this->fail('user id atau email yang anda masukkan belum terdaftar', 400);
            if ($user['aktif'] == '0') return $this->fail('user Anda tidak aktif silahkan hubungi admin.', 400);

            // return print_r([$user['pass'], $json->password]);
            if (!password_verify($json->password, $user['pass'])) return $this->fail("password Anda salah.", 400);

            $key = getenv("JWT_KEY");
            // return print_r($key);
            $iat = time();
            $exp = $iat + (getenv('JWT_EXP') * 60);
            $payload = [
                'sub' => $user['id'],
                'iat' => $iat,
                'exp' => $exp
            ];

            $token = JWT::encode($payload, $key, 'HS256');
            $resp = [
                'token' => $token,
                'id' => $user['id'],
                'user_id' => $user['user_id'],
                'name' => $user['name'],
                'role' => $user['role']
            ];
            return $this->respond($resp);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage(), 500);
        }
    }
}
