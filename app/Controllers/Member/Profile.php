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

        $whatsapp = $this->request->getPost('whatsapp');
        $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
        if (!empty($whatsapp) && substr($whatsapp, 0, 1) === '0') {
            $whatsapp = '62' . substr($whatsapp, 1);
        }

        $data = [
            'id'             => $userId,
            'name'           => $this->request->getPost('name'),
            'email'          => $email,
            'phone'          => $this->request->getPost('phone'),
            'whatsapp'       => $whatsapp ?: null,
            'bio'            => $this->request->getPost('bio'),
            'address'        => $this->request->getPost('address'),
            'date_of_birth'  => $this->request->getPost('date_of_birth') ?: null,
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $oldAvatar = $model->find($userId)->avatar ?? null;
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/avatars', $newName);
            $data['avatar'] = $newName;

            if ($oldAvatar) {
                $oldPath = WRITEPATH . 'uploads/avatars/' . $oldAvatar;
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $model->save($data);

        $this->session->set('name', $data['name']);
        if (isset($data['avatar'])) {
            $this->session->set('avatar', $data['avatar']);
        }

        return redirect()->to('/member/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
