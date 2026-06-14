<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['class_id', 'title', 'description', 'type', 'file_path', 'sort_order'];

    protected $validationRules = [
        'class_id' => 'required',
        'title'    => 'required|min_length[2]|max_length[200]',
        'type'     => 'required|in_list[video,document,link]',
    ];

    public function getContentByClass(int $classId)
    {
        return $this->where('class_id', $classId)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
    }

    public function getContentWithClass()
    {
        return $this->select('content.*, classes.name as class_name, courses.title as course_title')
            ->join('classes', 'classes.id = content.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->orderBy('content.sort_order', 'ASC')
            ->findAll();
    }

    public function countByClass(int $classId): int
    {
        return $this->where('class_id', $classId)->countAllResults();
    }
}
