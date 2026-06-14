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
        $keyword = $this->request->getGet('q') ?? '';
        $announcements = $model->getAllAdmin(10, $keyword);
        $pager = $model->pager;

        return view('admin/announcements/index', [
            'title'         => 'Kelola Pengumuman',
            'announcements' => $announcements,
            'pager'         => $pager,
            'keyword'       => $keyword,
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
            'title' => 'required|min_length[2]|max_length[200]',
            'type'  => 'required|in_list[umum,kelas,diskon,event,lainnya]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new AnnouncementModel();
        $data = [
            'title'        => $this->request->getPost('title'),
            'type'         => $this->request->getPost('type'),
            'body'         => $this->request->getPost('body'),
            'icon'         => $this->request->getPost('icon') ?: 'fas fa-bullhorn',
            'color'        => $this->request->getPost('color') ?: 'primary',
            'target'       => $this->request->getPost('target') ?: 'semua',
            'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
            'published_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->request->getPost('type') === 'kelas' && !empty($_POST['class_id'])) {
            $data['class_id'] = $this->request->getPost('class_id');
        }

        $model->save($data);

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
            'title' => 'required|min_length[2]|max_length[200]',
            'type'  => 'required|in_list[umum,kelas,diskon,event,lainnya]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $data = [
            'id'         => $id,
            'title'      => $this->request->getPost('title'),
            'type'       => $this->request->getPost('type'),
            'body'       => $this->request->getPost('body'),
            'icon'       => $this->request->getPost('icon') ?: 'fas fa-bullhorn',
            'color'      => $this->request->getPost('color') ?: 'primary',
            'target'     => $this->request->getPost('target') ?: 'semua',
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
            'class_id'   => null,
        ];

        if ($this->request->getPost('type') === 'kelas' && !empty($_POST['class_id'])) {
            $data['class_id'] = $this->request->getPost('class_id');
        }

        $model->save($data);

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new AnnouncementModel();
        $model->delete($id);

        return redirect()->to('/admin/announcements')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
