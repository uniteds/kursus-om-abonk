<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['class_id', 'title', 'body'];

    protected $validationRules = [
        'class_id' => 'required',
        'title'    => 'required|min_length[2]|max_length[200]',
        'body'     => 'required',
    ];

    public function getAnnouncementsByClass(int $classId)
    {
        return $this->where('class_id', $classId)->orderBy('id', 'DESC')->findAll();
    }

    public function getAnnouncementsWithClass()
    {
        return $this->select('announcements.*, classes.name as class_name, courses.title as course_title')
            ->join('classes', 'classes.id = announcements.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->orderBy('announcements.id', 'DESC')
            ->findAll();
    }
}
