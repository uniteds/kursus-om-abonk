<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['name', 'email', 'password', 'role', 'avatar', 'phone', 'whatsapp', 'bio', 'address', 'date_of_birth', 'is_active', 'email_verified_at', 'reset_token', 'reset_expires'];

    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email',
        'password' => 'permit_empty|min_length[6]',
    ];

    protected $validationMessages = [];

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function getAdmins()
    {
        return $this->where('role', 'admin')->findAll();
    }

    public function getMembers()
    {
        return $this->where('role', 'member')->findAll();
    }

    public function countByRole(string $role): int
    {
        return $this->where('role', $role)->countAllResults();
    }
}
