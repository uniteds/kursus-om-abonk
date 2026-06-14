<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['category_id', 'title', 'slug', 'description', 'thumbnail', 'is_active', 'meetings_count', 'meeting_duration', 'curriculum'];

    protected $validationRules = [
        'title'       => 'required|min_length[3]|max_length[200]',
        'category_id' => 'required',
    ];

    public function getSlug(string $title): string
    {
        $slug = url_title($title, '-', true);
        $originalSlug = $slug;
        $count = 1;

        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getCategory()
    {
        return $this->belongsTo('App\Models\CategoryModel', 'category_id');
    }

    public function getClasses()
    {
        return $this->hasMany('App\Models\ClassModel', 'course_id');
    }

    public function getCoursesWithCategory()
    {
        return $this->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->orderBy('courses.id', 'DESC')
            ->findAll();
    }

    public function getCourseBySlug(string $slug)
    {
        return $this->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('courses.slug', $slug)
            ->first();
    }
}
