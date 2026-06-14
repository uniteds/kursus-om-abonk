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

    protected $allowedFields = [
        'class_id', 'title', 'slug', 'description', 'excerpt', 'body',
        'type', 'file_path', 'sort_order', 'category', 'thumbnail',
        'is_published', 'published_at', 'views',
    ];

    protected $validationRules = [
        'title'    => 'required|min_length[2]|max_length[200]',
        'category' => 'required|in_list[berita,tutorial,artikel]',
    ];

    public function getPublished(int $limit = 0)
    {
        $builder = $this->where('is_published', 1);
        if ($limit > 0) {
            $builder->limit($limit);
        }
        return $builder->orderBy('published_at', 'DESC')->findAll();
    }

    public function getPublishedByCategory(string $category, int $limit = 0)
    {
        $builder = $this->where('is_published', 1)->where('category', $category);
        if ($limit > 0) {
            $builder->limit($limit);
        }
        return $builder->orderBy('published_at', 'DESC')->findAll();
    }

    public function getPublishedWithCategory(int $limit = 0)
    {
        return $this->where('is_published', 1)
            ->orderBy('published_at', 'DESC')
            ->paginate($limit);
    }

    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)->where('is_published', 1)->first();
    }

    public function findBySlugOrAll(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function incrementViews(int $id)
    {
        $this->where('id', $id)->increment('views');
    }

    public function generateSlug(string $title, int $excludeId = 0): string
    {
        $slug = url_title($title, '-', true);
        $baseSlug = $slug;
        $counter = 1;

        while (true) {
            $builder = $this->where('slug', $slug);
            if ($excludeId > 0) {
                $builder->where('id !=', $excludeId);
            }
            if ($builder->countAllResults() === 0) {
                return $slug;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
    }

    public function getAllAdmin(int $perPage = 10, ?string $keyword = '')
    {
        $builder = $this->builder();
        if ($keyword) {
            $builder->groupStart()
                ->like('title', $keyword)
                ->orLike('category', $keyword)
                ->groupEnd();
        }
        return $this->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function countPublished(): int
    {
        return $this->where('is_published', 1)->countAllResults();
    }

    public function countByClass(int $classId): int
    {
        return $this->where('class_id', $classId)->countAllResults();
    }

    public function getContentByClass(int $classId)
    {
        return $this->where('class_id', $classId)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
    }
}
