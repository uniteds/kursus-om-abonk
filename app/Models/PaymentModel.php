<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'user_id', 'class_id', 'invoice_number', 'amount', 'payment_method',
        'bank_name', 'account_name', 'proof_image', 'status',
        'notes', 'admin_notes', 'paid_at',
        'doku_session_id', 'doku_token_id', 'doku_payment_url',
        'payment_channel', 'external_id',
    ];

    protected $validationRules = [
        'user_id'   => 'required',
        'class_id'  => 'required',
        'amount'    => 'required|decimal',
    ];

    protected $validationMessages = [
        'user_id'  => ['required' => 'User wajib diisi.'],
        'class_id' => ['required' => 'Kelas wajib diisi.'],
        'amount'   => ['required' => 'Jumlah pembayaran wajib diisi.', 'decimal' => 'Format jumlah tidak valid.'],
    ];

    protected $afterInsert = ['setPaidAt'];

    protected function setPaidAt(array $data): array
    {
        $data['data']['paid_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getUser()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    public function getClass()
    {
        return $this->belongsTo('App\Models\ClassModel', 'class_id');
    }

    public function getPaymentsWithDetails()
    {
        return $this->select('payments.*, users.name as user_name, users.email as user_email, classes.name as class_name, courses.title as course_title')
            ->join('users', 'users.id = payments.user_id', 'left')
            ->join('classes', 'classes.id = payments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->orderBy('payments.id', 'DESC')
            ->findAll();
    }

    public function getPaymentsByUser(int $userId)
    {
        return $this->select('payments.*, classes.name as class_name, courses.title as course_title, courses.price as course_price')
            ->join('classes', 'classes.id = payments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('payments.user_id', $userId)
            ->orderBy('payments.id', 'DESC')
            ->findAll();
    }

    public function getPendingPayments()
    {
        return $this->select('payments.*, users.name as user_name, users.email as user_email, classes.name as class_name, courses.title as course_title')
            ->join('users', 'users.id = payments.user_id', 'left')
            ->join('classes', 'classes.id = payments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('payments.status', 'pending')
            ->orderBy('payments.id', 'DESC')
            ->findAll();
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    public function hasPendingPayment(int $userId, int $classId): bool
    {
        return $this->where('user_id', $userId)
            ->where('class_id', $classId)
            ->where('status', 'pending')
            ->countAllResults() > 0;
    }

    public function hasApprovedPayment(int $userId, int $classId): bool
    {
        return $this->where('user_id', $userId)
            ->where('class_id', $classId)
            ->where('status', 'approved')
            ->countAllResults() > 0;
    }

    public function findByInvoiceNumber(string $invoiceNumber): ?object
    {
        return $this->where('invoice_number', $invoiceNumber)->first();
    }
}
