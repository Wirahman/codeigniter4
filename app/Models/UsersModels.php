<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class UsersModels extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 
        'username', 
        'email', 
        'password', 
        'token', 
        'token_expired'
    ];
}