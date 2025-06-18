<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Produk;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProdukController extends ResourceController
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new Produk();
    }

    public function index()
		{
			$data = $this->model->findAll();
			$data = array_map(function ($item) {
				$item['id'] = (int) $item['id'];
				$item['harga'] = (int) $item['harga'];
				return $item;
			}, $data);

			return $this->respond([
				"success"=> true,
				"data" => $data 
			], 200);
		}

		public function show($id = null)
		{
			$data = $this->model->find($id);
			if ($data) {
				return $this->respond([
					"success" => true,
					"data" => $data
				], 200);
			}

			return $this->failNotFound("Produk tidak ditemukan");
		}

		public function create()
		{
			$request = $this->request->getJSON();
			if (!$this->model->save($request)) {
				return $this->fail($this->model->errors());
			}

			return $this->respond(['success' => true, 'data' => $request], 201);
		}

		public function update($id = null)
		{
			$data = $this->request->getJSON();

			if (!$this->model->find($id)) {
				return $this->failNotFound("Produk dengan ID $id tidak ditemukan");
			}

			$data->id = $id;
			if (!$this->model->save($data)) {
				return $this->fail($this->model->errors());
			}

			return $this->respond(['success' => true, 'message' => "Produk berhasil diupdate", "data" => $data], 200);
		}

		public function delete($id = null)
		{
			if (!$this->model->find($id)) {
				return $this->failNotFound("Produk dengan ID $id tidak ditemukan");
			}

			$this->model->delete($id);
			return $this->respondDeleted(['success' => true, 'message' => "Produk berhasil dihapus"]);
		}
}
