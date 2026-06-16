<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;

class Courses extends BaseController
{
    public function index()
    {
        $model = new CourseModel();
        $keyword = $this->request->getGet('q');

        $builder = $model->builder();
        $builder->select('courses.*, categories.name as category_name');
        $builder->join('categories', 'categories.id = courses.category_id', 'left');
        if ($keyword) {
            $builder->like('courses.title', $keyword);
        }
        $courses = $model->orderBy('courses.id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/courses/index', [
            'title'    => 'Manage Kursus',
            'courses'  => $courses,
            'pager'    => $pager,
            'keyword'  => $keyword,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        $categoryModel = new CategoryModel();

        return view('admin/courses/form', [
            'title'      => 'Tambah Kursus',
            'course'     => null,
            'categories' => $categoryModel->orderBy('name', 'ASC')->findAll(),
            'settings'   => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'title'       => 'required|min_length[3]|max_length[200]',
            'category_id' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new CourseModel();
        $title = $this->request->getPost('title');
        $slug = $model->getSlug($title);

        $data = [
            'title'            => $title,
            'slug'             => $slug,
            'category_id'      => $this->request->getPost('category_id'),
            'description'      => $this->request->getPost('description'),
            'meetings_count'   => $this->request->getPost('meetings_count') ?: null,
            'meeting_duration' => $this->request->getPost('meeting_duration') ?: null,
            'curriculum'       => $this->request->getPost('curriculum'),
            'price'            => $this->request->getPost('price') ?: 0,
            'is_active'        => $this->request->getPost('is_active') ?? 1,
        ];

        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $model->save($data);

        return redirect()->to('/admin/courses')->with('success', 'Kursus berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new CourseModel();
        $categoryModel = new CategoryModel();
        $course = $model->find($id);

        if (!$course) {
            return redirect()->to('/admin/courses')->with('error', 'Kursus tidak ditemukan.');
        }

        return view('admin/courses/form', [
            'title'      => 'Edit Kursus',
            'course'     => $course,
            'categories' => $categoryModel->orderBy('name', 'ASC')->findAll(),
            'settings'   => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new CourseModel();

        $rules = [
            'title'       => 'required|min_length[3]|max_length[200]',
            'category_id' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $course = $model->find($id);
        $title = $this->request->getPost('title');

        if ($title !== $course->title) {
            $slug = $model->getSlug($title);
        } else {
            $slug = $course->slug;
        }

        $data = [
            'id'               => $id,
            'title'            => $title,
            'slug'             => $slug,
            'category_id'      => $this->request->getPost('category_id'),
            'description'      => $this->request->getPost('description'),
            'meetings_count'   => $this->request->getPost('meetings_count') ?: null,
            'meeting_duration' => $this->request->getPost('meeting_duration') ?: null,
            'curriculum'       => $this->request->getPost('curriculum'),
            'price'            => $this->request->getPost('price') ?: 0,
            'is_active'        => $this->request->getPost('is_active') ?? 0,
        ];

        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $model->save($data);

        return redirect()->to('/admin/courses')->with('success', 'Kursus berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new CourseModel();
        $model->delete($id);

        return redirect()->to('/admin/courses')->with('success', 'Kursus berhasil dihapus.');
    }
}
