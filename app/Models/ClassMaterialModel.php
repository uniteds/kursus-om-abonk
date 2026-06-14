<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassMaterialModel extends Model
{
    protected $table = 'class_materials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'class_id', 'title', 'description', 'type', 'file_path',
        'external_url', 'sort_order', 'downloads', 'is_published',
    ];

    public function getByClass(int $classId)
    {
        return $this->where('class_id', $classId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getPublishedByClass(int $classId)
    {
        return $this->where('class_id', $classId)
            ->where('is_published', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function countByClass(int $classId): int
    {
        return $this->where('class_id', $classId)->countAllResults();
    }

    public function incrementDownloads(int $id)
    {
        $this->where('id', $id)->increment('downloads');
    }
}
