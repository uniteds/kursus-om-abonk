<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class Settings extends BaseController
{
    public function index()
    {
        $model = new SiteSettingModel();

        return view('admin/settings/index', [
            'title'    => 'Pengaturan Site',
            'settings' => $model->getAllSettings(),
        ]);
    }

    public function update()
    {
        $model = new SiteSettingModel();
        $keys = ['site_name', 'site_description', 'site_tagline', 'site_footer_about', 'site_logo', 'site_footer'];

        foreach ($keys as $key) {
            $value = $this->request->getPost($key);
            if ($value !== null) {
                $model->setSetting($key, $value);
            }
        }

        $file = $this->request->getFile('site_logo_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/thumbnails', $newName);
            $model->setSetting('site_logo', $newName);
        }

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
