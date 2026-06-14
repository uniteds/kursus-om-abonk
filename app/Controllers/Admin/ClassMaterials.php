<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassMaterialModel;
use App\Models\ClassModel;

class ClassMaterials extends BaseController
{
    public function index($classId)
    {
        $model = new ClassMaterialModel();
        $classModel = new ClassModel();

        $class = $classModel->select('classes.*, courses.title as course_title')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('classes.id', $classId)
            ->first();

        if (!$class) {
            return redirect()->to('/admin/classes')->with('error', 'Kelas tidak ditemukan.');
        }

        $materials = $model->getByClass($classId);

        return view('admin/class-materials/index', [
            'title'      => 'Materi Kelas: ' . $class->name,
            'class'      => $class,
            'materials'  => $materials,
            'settings'   => $this->getAllSettings(),
        ]);
    }

    public function create($classId)
    {
        $classModel = new ClassModel();
        $class = $classModel->where('id', $classId)->first();

        if (!$class) {
            return redirect()->to('/admin/classes')->with('error', 'Kelas tidak ditemukan.');
        }

        $model = new ClassMaterialModel();
        $maxOrder = $model->where('class_id', $classId)->max('sort_order') ?? 0;

        return view('admin/class-materials/form', [
            'title'    => 'Tambah Materi',
            'class'    => $class,
            'material' => null,
            'nextOrder'=> $maxOrder + 1,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store($classId)
    {
        $rules = [
            'title' => 'required|min_length[2]|max_length[200]',
            'type'  => 'required|in_list[document,video,link,slide,tugas,other]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new ClassMaterialModel();
        $data = [
            'class_id'     => $classId,
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'type'         => $this->request->getPost('type'),
            'sort_order'   => $this->request->getPost('sort_order') ?? 0,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];

        $type = $this->request->getPost('type');
        if ($type === 'link') {
            $data['external_url'] = $this->request->getPost('external_url');
        } else {
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/materials', $newName);
                $data['file_path'] = $newName;
                $data['file_original_name'] = $file->getName();
            }
        }

        $model->save($data);

        return redirect()->to("/admin/classes/materials/{$classId}")->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit($classId, $materialId)
    {
        $model = new ClassMaterialModel();
        $classModel = new ClassModel();

        $class = $classModel->where('id', $classId)->first();
        $material = $model->find($materialId);

        if (!$class || !$material) {
            return redirect()->to("/admin/classes/view/{$classId}")->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/class-materials/form', [
            'title'    => 'Edit Materi',
            'class'    => $class,
            'material' => $material,
            'nextOrder'=> $material->sort_order,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update($classId, $materialId)
    {
        $model = new ClassMaterialModel();

        $rules = [
            'title' => 'required|min_length[2]|max_length[200]',
            'type'  => 'required|in_list[document,video,link,slide,tugas,other]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $data = [
            'id'           => $materialId,
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'type'         => $this->request->getPost('type'),
            'sort_order'   => $this->request->getPost('sort_order') ?? 0,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
            'external_url' => null,
            'file_path'    => null,
        ];

        $type = $this->request->getPost('type');
        if ($type === 'link') {
            $data['external_url'] = $this->request->getPost('external_url');
        } else {
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/materials', $newName);
                $data['file_path'] = $newName;
            }
        }

        $model->save($data);

        return redirect()->to("/admin/classes/materials/{$classId}")->with('success', 'Materi berhasil diperbarui.');
    }

    public function delete($classId, $materialId)
    {
        $model = new ClassMaterialModel();
        $model->delete($materialId);

        return redirect()->to("/admin/classes/materials/{$classId}")->with('success', 'Materi berhasil dihapus.');
    }

    public function download($classId, $materialId)
    {
        $model = new ClassMaterialModel();
        $material = $model->find($materialId);

        if (!$material || !$material->file_path) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $model->incrementDownloads($materialId);

        $filePath = WRITEPATH . 'uploads/materials/' . $material->file_path;
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return $this->response->download($material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION), file_get_contents($filePath));
    }
}
