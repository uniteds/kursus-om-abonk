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

    protected $allowedFields = [
        'class_id', 'title', 'type', 'body', 'icon', 'color',
        'target', 'is_active', 'published_at',
    ];

    protected $validationRules = [
        'title' => 'required|min_length[2]|max_length[200]',
        'type'  => 'required|in_list[umum,kelas,diskon,event,lainnya]',
    ];

    public function getActive(int $limit = 0)
    {
        $builder = $this->where('is_active', 1);
        if ($limit > 0) {
            $builder->limit($limit);
        }
        return $builder->orderBy('published_at', 'DESC')->findAll();
    }

    public function getForMembers(int $limit = 0)
    {
        $builder = $this->where('is_active', 1)
            ->groupStart()
                ->where('target', 'semua')
                ->orWhere('target', 'member')
            ->groupEnd();
        if ($limit > 0) {
            $builder->limit($limit);
        }
        return $builder->orderBy('published_at', 'DESC')->findAll();
    }

    public function getAllAdmin(int $perPage = 10, string $keyword = '')
    {
        if ($keyword) {
            $this->groupStart()
                ->like('title', $keyword)
                ->orLike('type', $keyword)
                ->groupEnd();
        }
        return $this->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function getWithClass()
    {
        return $this->select('announcements.*, classes.name as class_name, courses.title as course_title')
            ->join('classes', 'classes.id = announcements.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->orderBy('announcements.id', 'DESC')
            ->findAll();
    }

    public function getAnnouncementsByClass(int $classId)
    {
        return $this->where('class_id', $classId)
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->findAll();
    }
}
