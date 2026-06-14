<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    public function index()
    {
        $model = new CategoryModel();
        $categories = $model->orderBy('id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/categories/index', [
            'title'      => 'Manage Kategori',
            'categories' => $categories,
            'pager'      => $pager,
            'settings'   => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        return view('admin/categories/form', [
            'title'    => 'Tambah Kategori',
            'category' => null,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $model = new CategoryModel();

        $name = $this->request->getPost('name');
        $slug = $model->getSlug($name);

        $model->save([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new CategoryModel();
        $category = $model->find($id);

        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('admin/categories/form', [
            'title'    => 'Edit Kategori',
            'category' => $category,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new CategoryModel();

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $category = $model->find($id);
        $name = $this->request->getPost('name');

        if ($name !== $category->name) {
            $slug = $model->getSlug($name);
        } else {
            $slug = $category->slug;
        }

        $model->update($id, [
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new CategoryModel();
        $model->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
