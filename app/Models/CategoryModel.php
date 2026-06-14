<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = ['name', 'slug', 'description'];

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'permit_empty|is_unique[categories.slug,id,{id}]',
    ];

    public function getSlug(string $name): string
    {
        $slug = url_title($name, '-', true);
        $originalSlug = $slug;
        $count = 1;

        while ($this->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
