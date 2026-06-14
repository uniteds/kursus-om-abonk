<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['course_id', 'name', 'description', 'schedule', 'capacity', 'status'];

    protected $validationRules = [
        'course_id' => 'required',
        'name'      => 'required|min_length[2]|max_length[100]',
    ];

    protected $validationMessages = [
        'course_id' => [
            'required' => 'Kursus harus dipilih.',
        ],
    ];

    public function getCourse()
    {
        return $this->belongsTo('App\Models\CourseModel', 'course_id');
    }

    public function getClassesWithCourse()
    {
        return $this->select('classes.*, courses.title as course_title')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->orderBy('classes.id', 'DESC')
            ->findAll();
    }

    public function getClassBySlug(int $classId)
    {
        return $this->select('classes.*, courses.title as course_title')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('classes.id', $classId)
            ->first();
    }

    public function getClassesByCourse(int $courseId)
    {
        return $this->where('course_id', $courseId)->orderBy('id', 'DESC')->findAll();
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }
}
