<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table = 'certificates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'enrollment_id', 'user_id', 'course_id', 'class_id',
        'certificate_number', 'participant_name', 'course_title',
        'course_duration', 'issued_at',
    ];

    public function findByEnrollment(int $enrollmentId)
    {
        return $this->where('enrollment_id', $enrollmentId)->first();
    }

    public function findByUser(int $userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function generateCertificateNumber(): string
    {
        $prefix = 'CERT';
        $date = date('Ymd');
        $lastCert = $this->like('certificate_number', $prefix . $date, 'after')->first();

        if ($lastCert) {
            $lastNum = (int) substr($lastCert->certificate_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }

        return $prefix . '-' . $date . '-' . $newNum;
    }
}
