<?php
 
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UsersModels;
use CodeIgniter\I18n\Time;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

helper('text');
 
class UsersControllers extends ResourceController
{
    use ResponseTrait;

    public function test()
    {
        $data['user'] = "Ini cuma test";
        return $this->respond($data);
    }
    
    // all users
    public function index()
    {
        $model = new UsersModels();
        $data['user'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    // create
    public function create()
    {
        $model = new UsersModels();
        
        $newTimePlus3Weeks = new Time('+3 week');
        $newTimePlus = new Time('now');

        $newToken = random_string('alnum', 250);
        $newTokenExpired = new Time('+4 week');
        // var_dump($newTokenExpired);
        // exit();

        $data = [
            'name' => $this->request->getVar('name'),
            'username'  => $this->request->getVar('username'),
            'email'  => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'token' => $newToken,
            'token_expired' => $newTokenExpired
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Users berhasil ditambahkan.'
            ]
        ];
        return $this->respondCreated($response);
    }

    public function login()
    {
        $model = new UsersModels();
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        $pengguna = $model->where([
            'username' => $username,
        ])->first();

        if($pengguna){
            $passwordVerify = password_verify($password, $pengguna['password']);
            if($passwordVerify){
                $now = new Time('now');
                if($now > $pengguna['token_expired']){
                    $newTokenExpired = new Time('+4 week');
                    $this->updateToken($pengguna['id'], $newTokenExpired);
                }
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'pengguna'  => $pengguna,
                    'messages' => [
                        'success' => 'Password Sesuai'
                    ]
                ];
            return $this->respond($response);
            } else {
                $response = [
                    'status'   => 401,
                    'error'    => null,
                    'messages' => [
                        'success' => 'Password Tidak Sesuai'
                    ]
                ];
                return $this->respond($response);
            }
        } else {
            $response = [
                'status'   => 401,
                'error'    => null,
                'messages' => [
                    'success' => 'Pengguna tidak ditemukan'
                ]
            ];
            return $this->respond($response);
        }
    }

    // single user
    public function show($id = null)
    {
        $model = new UsersModels();
        $data = $model->where('id', $id)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('Data tidak ditemukan.');
        }
    }

    // update
    public function update($id = null)
    {
        $model = new UsersModels();
        $id = $this->request->getVar('id');
        $data = [
            'name' => $this->request->getVar('name'),
            'username'  => $this->request->getVar('username'),
            'email'  => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data users berhasil diubah.'
            ]
        ];
        return $this->respond($response);
    }

    public function updateToken($id, $tokenExpired){
        $model = new UsersModels();
        // var_dump($id);
        // var_dump($tokenExpired);
        $data = [
            'token_expired' => $tokenExpired
        ];
        $model->update($id, $data);
    }

    // get by params
    public function getByParams($id = null)
    {
        $model = new UsersModels();
        $id = $this->request->getVar('id');
        $data = $model->where('id', $id)->first();
        if($data){
            return $this->respond($data);
        }
    }

    // delete
    public function delete($id = null)
    {
        $model = new UsersModels();
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
    
    // all user
    public function getAll()
    {
        $model = new UsersModels();
        $data['user'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

}