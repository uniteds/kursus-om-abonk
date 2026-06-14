<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;
use App\Models\ClassModel;

class Announcements extends BaseController
{
    public function index()
    {
        $model = new AnnouncementModel();

        $builder = $model->builder();
        $builder->select('announcements.*, classes.name as class_name, courses.title as course_title');
        $builder->join('classes', 'classes.id = announcements.class_id', 'left');
        $builder->join('courses', 'courses.id = classes.course_id', 'left');

        $announcements = $model->orderBy('announcements.id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/announcements/index', [
            'title'         => 'Manage Pengumuman',
            'announcements' => $announcements,
            'pager'         => $pager,
            'settings'      => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        $classModel = new ClassModel();

        return view('admin/announcements/form', [
            'title'        => 'Tambah Pengumuman',
            'announcement' => null,
            'classes'      => $classModel->getClassesWithCourse(),
            'settings'     => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'class_id' => 'required',
            'title'    => 'required|min_length[2]|max_length[200]',
            'body'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new AnnouncementModel();
        $model->save([
            'class_id' => $this->request->getPost('class_id'),
            'title'    => $this->request->getPost('title'),
            'body'     => $this->request->getPost('body'),
        ]);

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new AnnouncementModel();
        $classModel = new ClassModel();
        $announcement = $model->find($id);

        if (!$announcement) {
            return redirect()->to('/admin/announcements')->with('error', 'Pengumuman tidak ditemukan.');
        }

        return view('admin/announcements/form', [
            'title'        => 'Edit Pengumuman',
            'announcement' => $announcement,
            'classes'      => $classModel->getClassesWithCourse(),
            'settings'     => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new AnnouncementModel();

        $rules = [
            'class_id' => 'required',
            'title'    => 'required|min_length[2]|max_length[200]',
            'body'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model->update($id, [
            'class_id' => $this->request->getPost('class_id'),
            'title'    => $this->request->getPost('title'),
            'body'     => $this->request->getPost('body'),
        ]);

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new AnnouncementModel();
        $model->delete($id);

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
