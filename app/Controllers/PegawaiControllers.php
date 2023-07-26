<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PegawaiModels;
use CodeIgniter\I18n\Time;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

helper('text');
 
class PegawaiControllers extends ResourceController
{
    use ResponseTrait;

    // all pegawai
    public function getAll()
    {
        $model = new PegawaiModels();
        $data['pegawai'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    // create
    public function create()
    {
        $model = new PegawaiModels();

        $name = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $photo = $this->request->getVar('photo');
        
        $data = [
            'name' => $name,
            'email'  => $email,
            'photo'  => $photo
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Pegawai berhasil ditambahkan.'
            ]
        ];
        return $this->respondCreated($response);
    }


    // update
    public function update($id = null)
    {
        $model = new PegawaiModels();
        $id = $this->request->getVar('id');
        $name = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $photo = $this->request->getVar('photo');

        $data = [
            'name' => $name,
            'email'  => $email,
            'photo'  => $photo
        ];
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data pegawai berhasil diubah.'
            ]
        ];
        return $this->respond($response);
    }

    // get by params
    public function getByParams($id = null)
    {
        $model = new PegawaiModels();
        $id = $this->request->getVar('id');
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }
    }

    // delete
    public function delete($id = null)
    {
        $model = new PegawaiModels();
        $id = $this->request->getVar('id');
        $data = $model->where('id', $id)->delete($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data users berhasil dihapus.'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan.');
        }
    }

}