<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\CourseModel;

class Classes extends BaseController
{
    public function index()
    {
        $model = new ClassModel();
        $keyword = $this->request->getGet('q');

        $builder = $model->builder();
        $builder->select('classes.*, courses.title as course_title');
        $builder->join('courses', 'courses.id = classes.course_id', 'left');
        if ($keyword) {
            $builder->like('classes.name', $keyword)->orLike('courses.title', $keyword);
        }
        $classes = $model->orderBy('classes.id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/classes/index', [
            'title'    => 'Manage Kelas',
            'classes'  => $classes,
            'pager'    => $pager,
            'keyword'  => $keyword,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        $courseModel = new CourseModel();

        return view('admin/classes/form', [
            'title'    => 'Tambah Kelas',
            'class'    => null,
            'courses'  => $courseModel->orderBy('title', 'ASC')->findAll(),
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'course_id' => 'required',
            'name'      => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new ClassModel();
        $model->save([
            'course_id'   => $this->request->getPost('course_id'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'schedule'    => $this->request->getPost('schedule'),
            'capacity'    => $this->request->getPost('capacity') ?? 30,
            'status'      => $this->request->getPost('status') ?? 'upcoming',
        ]);

        return redirect()->to('/admin/classes')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new ClassModel();
        $courseModel = new CourseModel();
        $class = $model->find($id);

        if (!$class) {
            return redirect()->to('/admin/classes')->with('error', 'Kelas tidak ditemukan.');
        }

        return view('admin/classes/form', [
            'title'    => 'Edit Kelas',
            'class'    => $class,
            'courses'  => $courseModel->orderBy('title', 'ASC')->findAll(),
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new ClassModel();

        $rules = [
            'course_id' => 'required',
            'name'      => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model->update($id, [
            'course_id'   => $this->request->getPost('course_id'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'schedule'    => $this->request->getPost('schedule'),
            'capacity'    => $this->request->getPost('capacity') ?? 30,
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/classes')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new ClassModel();
        $model->delete($id);

        return redirect()->to('/admin/classes')->with('success', 'Kelas berhasil dihapus.');
    }
}
