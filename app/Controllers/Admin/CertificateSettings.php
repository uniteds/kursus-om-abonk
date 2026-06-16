<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CertificateSettingModel;

class CertificateSettings extends BaseController
{
    public function index()
    {
        $model = new CertificateSettingModel();
        $settings = $model->getFirst();

        return view('admin/certificate-settings/index', [
            'title'    => 'Pengaturan Sertifikat',
            'certSettings' => $settings,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function update()
    {
        $model = new CertificateSettingModel();
        $current = $model->getFirst();

        $data = [
            'signer_name'          => $this->request->getPost('signer_name'),
            'signer_title'         => $this->request->getPost('signer_title'),
            'certificate_title'    => $this->request->getPost('certificate_title'),
            'certificate_subtitle' => $this->request->getPost('certificate_subtitle'),
            'border_color'         => $this->request->getPost('border_color'),
            'accent_color'         => $this->request->getPost('accent_color'),
        ];

        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedMimes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (in_array($file->getMimeType(), $allowedMimes)) {
                if ($current->logo) {
                    $oldPath = WRITEPATH . 'uploads/certificates/' . $current->logo;
                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/certificates', $newName);
                $data['logo'] = $newName;
            }
        }

        $model->update($current->id, $data);

        return redirect()->to('/admin/certificate-settings')->with('success', 'Pengaturan sertifikat berhasil disimpan.');
    }
}
