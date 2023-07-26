<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class PegawaiModels extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 
        'email', 
        'photo'
    ];
}