<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Member;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class MemberController extends ResourceController
{
   
    protected $model;
    
    public function __construct()
    {
        $this->model = new Member();
    }

    public function index()
    {
        $data = $this->model->findAll();
    
        foreach ($data as &$row) {
            unset($row['password']);
        }
        
        return $this->respond([
            "success"=> true,
            "data" => $data
        ], 200);
    }

    public function show($id = null)
    {
        $data = $this->model->find($id);
        unset($data['password']);
        
        if ($data) {
            return $this->respond([
                "success" => true,
                "data" => $data
            ], 200);
        }

        return $this->failNotFound("Member tidak ditemukan");
    }

    public function create()
    {
        $request = $this->request->getPost();

        $data = [
            'nama' => $request['nama'],
            'email' => $request['email'],
            'password' => password_hash($request['password'], PASSWORD_DEFAULT),
        ];

        if (!$this->model->save($request)) {
            return $this->fail($this->model->errors());
        }

        return $this->respond(['success' => true, 'data' => $request], 201);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        if (!$this->model->find($id)) {
            return $this->failNotFound("Member dengan ID $id tidak ditemukan");
        }

        $data['id'] = $id;
        if (!$this->model->save($data)) {
            return $this->fail($this->model->errors());
        }

        return $this->respond(['success' => true, 'message' => "Member berhasil diupdate", "data" => $data], 200);
    }

    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound("Member dengan ID $id tidak ditemukan");
        }

        $this->model->delete($id);
        return $this->respondDeleted(['success' => true, 'message' => "Produk berhasil dihapus"]);
    }
}
