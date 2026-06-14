<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContentModel;

class Content extends BaseController
{
    public function index()
    {
        $model = new ContentModel();
        $keyword = $this->request->getGet('q') ?? '';
        $contents = $model->getAllAdmin(10, $keyword);
        $pager = $model->pager;

        return view('admin/content/index', [
            'title'    => 'Kelola Artikel',
            'contents' => $contents,
            'pager'    => $pager,
            'keyword'  => $keyword,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        return view('admin/content/form', [
            'title'    => 'Tambah Artikel',
            'content'  => null,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'title'    => 'required|min_length[2]|max_length[200]',
            'category' => 'required|in_list[berita,tutorial,artikel]',
            'body'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new ContentModel();
        $title = $this->request->getPost('title');
        $slug = $model->generateSlug($title);

        $data = [
            'title'       => $title,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'excerpt'     => $this->request->getPost('excerpt'),
            'body'        => $this->request->getPost('body'),
            'category'    => $this->request->getPost('category'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
            'published_at' => $this->request->getPost('is_published') ? date('Y-m-d H:i:s') : null,
        ];

        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $model->save($data);

        return redirect()->to('/admin/content')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new ContentModel();
        $content = $model->find($id);

        if (!$content) {
            return redirect()->to('/admin/content')->with('error', 'Artikel tidak ditemukan.');
        }

        return view('admin/content/form', [
            'title'    => 'Edit Artikel',
            'content'  => $content,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new ContentModel();

        $rules = [
            'title'    => 'required|min_length[2]|max_length[200]',
            'category' => 'required|in_list[berita,tutorial,artikel]',
            'body'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $title = $this->request->getPost('title');
        $data = [
            'id'          => $id,
            'title'       => $title,
            'slug'        => $model->generateSlug($title, $id),
            'description' => $this->request->getPost('description'),
            'excerpt'     => $this->request->getPost('excerpt'),
            'body'        => $this->request->getPost('body'),
            'category'    => $this->request->getPost('category'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];

        $existing = $model->find($id);
        if ($this->request->getPost('is_published') && !$existing->published_at) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $file = $this->request->getFile('thumbnail');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $model->save($data);

        return redirect()->to('/admin/content')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new ContentModel();
        $model->delete($id);

        return redirect()->to('/admin/content')->with('success', 'Artikel berhasil dihapus.');
    }
}
