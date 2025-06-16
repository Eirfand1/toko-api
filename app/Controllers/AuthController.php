<?php
namespace App\Controllers;

use App\Models\Member;
use App\Models\MemberToken;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    public function login()
    {
        $request = $this->request->getJSON();

        $email = $request->email ?? null;
        $password = $request->password ?? null;

        if (!$email || !$password) {
            return $this->fail("Email dan password wajib diisi", 400);
        }

        $memberModel = new Member();
        $user = $memberModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->fail("Email atau password salah", 401);
        }

        $authKey = bin2hex(random_bytes(32));

        $tokenModel = new MemberToken();
        $tokenModel->insert([
            'member_id' => $user['id'],
            'auth_key' => $authKey
        ]);

        return $this->respond([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $authKey,
            'user' => [
                'id' => $user['id'],
                'nama' => $user['nama'],
                'email' => $user['email']
            ]
        ]);

    }

    public function register()
    {
        $request = $this->request->getJSON();

        $nama = $request->nama;
        $email = $request->email;
        $password = $request->password;

        if (!$nama || !$email || !$password) {
            return $this->fail('Nama, email, dan password wajib diisi', 400);
        }

        $memberModel = new Member();
        
        if ($memberModel->where('email', $email)->first()) {
            return $this->fail('Email sudah digunakan', 409);
        }

        $data = [
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        if (!$memberModel->insert($data)) {
            return $this->fail($memberModel->errors(), 400);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'nama' => $nama,
                'email' => $email
            ]
        ], 201);
    }
}
