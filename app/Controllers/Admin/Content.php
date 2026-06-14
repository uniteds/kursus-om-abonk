<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContentModel;
use App\Models\ClassModel;

class Content extends BaseController
{
    public function index()
    {
        $model = new ContentModel();
        $keyword = $this->request->getGet('q');

        $builder = $model->builder();
        $builder->select('content.*, classes.name as class_name, courses.title as course_title');
        $builder->join('classes', 'classes.id = content.class_id', 'left');
        $builder->join('courses', 'courses.id = classes.course_id', 'left');
        if ($keyword) {
            $builder->like('content.title', $keyword);
        }
        $contents = $model->orderBy('content.id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/content/index', [
            'title'    => 'Manage Konten',
            'contents' => $contents,
            'pager'    => $pager,
            'keyword'  => $keyword,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        $classModel = new ClassModel();

        return view('admin/content/form', [
            'title'    => 'Tambah Konten',
            'content'  => null,
            'classes'  => $classModel->getClassesWithCourse(),
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'class_id' => 'required',
            'title'    => 'required|min_length[2]|max_length[200]',
            'type'     => 'required|in_list[video,document,link]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new ContentModel();
        $data = [
            'class_id'    => $this->request->getPost('class_id'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'type'        => $this->request->getPost('type'),
            'sort_order'  => $this->request->getPost('sort_order') ?? 0,
        ];

        $type = $this->request->getPost('type');
        if ($type === 'link') {
            $data['file_path'] = $this->request->getPost('file_path');
        } else {
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/files', $newName);
                $data['file_path'] = $newName;
            }
        }

        $model->save($data);

        return redirect()->to('/admin/content')->with('success', 'Konten berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new ContentModel();
        $classModel = new ClassModel();
        $content = $model->find($id);

        if (!$content) {
            return redirect()->to('/admin/content')->with('error', 'Konten tidak ditemukan.');
        }

        return view('admin/content/form', [
            'title'    => 'Edit Konten',
            'content'  => $content,
            'classes'  => $classModel->getClassesWithCourse(),
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new ContentModel();

        $rules = [
            'class_id' => 'required',
            'title'    => 'required|min_length[2]|max_length[200]',
            'type'     => 'required|in_list[video,document,link]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $data = [
            'id'          => $id,
            'class_id'    => $this->request->getPost('class_id'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'type'        => $this->request->getPost('type'),
            'sort_order'  => $this->request->getPost('sort_order') ?? 0,
        ];

        $type = $this->request->getPost('type');
        if ($type === 'link') {
            $data['file_path'] = $this->request->getPost('file_path');
        } else {
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/files', $newName);
                $data['file_path'] = $newName;
            }
        }

        $model->save($data);

        return redirect()->to('/admin/content')->with('success', 'Konten berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new ContentModel();
        $model->delete($id);

        return redirect()->to('/admin/content')->with('success', 'Konten berhasil dihapus.');
    }
}
