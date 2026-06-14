<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $user = $model->find($this->session->get('user_id'));

        return view('member/profile/index', [
            'title'    => 'Profil Saya',
            'user'     => $user,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update()
    {
        $model = new UserModel();
        $userId = $this->session->get('user_id');

        $email = $this->request->getPost('email');
        $existing = $model->where('email', $email)->where('id !=', $userId)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('errors', 'Email sudah terdaftar.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $data = [
            'id'    => $userId,
            'name'  => $this->request->getPost('name'),
            'email' => $email,
            'phone' => $this->request->getPost('phone'),
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/avatars', $newName);
            $data['avatar'] = $newName;
        }

        $model->save($data);

        $this->session->set('name', $data['name']);
        if (isset($data['avatar'])) {
            $this->session->set('avatar', $data['avatar']);
        }

        return redirect()->to('/member/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
