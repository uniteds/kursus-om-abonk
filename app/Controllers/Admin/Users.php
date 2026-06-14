<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $keyword = $this->request->getGet('q');

        $builder = $model->builder();
        if ($keyword) {
            $builder->like('name', $keyword)->orLike('email', $keyword);
        }
        $users = $model->orderBy('id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/users/index', [
            'title'   => 'Manage Users',
            'users'   => $users,
            'pager'   => $pager,
            'keyword' => $keyword,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function create()
    {
        return view('admin/users/form', [
            'title'    => 'Tambah User',
            'user'     => null,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,member]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $model = new UserModel();
        $model->save([
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        return view('admin/users/form', [
            'title'    => 'Edit User',
            'user'     => $user,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update($id)
    {
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $existing = $model->where('email', $email)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('errors', 'Email sudah terdaftar.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'role'  => 'required|in_list[admin,member]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $data = [
            'id'        => $id,
            'name'      => $this->request->getPost('name'),
            'email'     => $email,
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ?? 0,
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->save($data);

        return redirect()->to('/admin/users')->with('success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new UserModel();
        $model->delete($id);

        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }

    public function toggle($id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if ($user) {
            $model->update($id, ['is_active' => !$user->is_active]);
            $status = !$user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->to('/admin/users')->with('success', "User berhasil $status.");
        }

        return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
    }
}
