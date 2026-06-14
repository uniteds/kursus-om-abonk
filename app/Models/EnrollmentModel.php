<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['user_id', 'class_id', 'status', 'enrolled_at'];

    protected $validationRules = [
        'user_id'  => 'required',
        'class_id' => 'required',
    ];

    protected $afterInsert = ['setEnrolledAt'];

    protected function setEnrolledAt(array $data): array
    {
        $data['data']['enrolled_at'] = date('Y-m-d H:i:s');
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

    public function getEnrollmentsWithDetails()
    {
        return $this->select('enrollments.*, users.name as user_name, users.email as user_email, classes.name as class_name, courses.title as course_title')
            ->join('users', 'users.id = enrollments.user_id', 'left')
            ->join('classes', 'classes.id = enrollments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->orderBy('enrollments.id', 'DESC')
            ->findAll();
    }

    public function getEnrollmentsByUser(int $userId)
    {
        return $this->select('enrollments.*, classes.name as class_name, classes.schedule, classes.status as class_status, courses.title as course_title, courses.thumbnail')
            ->join('classes', 'classes.id = enrollments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('enrollments.user_id', $userId)
            ->orderBy('enrollments.id', 'DESC')
            ->findAll();
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    public function isEnrolled(int $userId, int $classId): bool
    {
        return $this->where('user_id', $userId)->where('class_id', $classId)->countAllResults() > 0;
    }
}
