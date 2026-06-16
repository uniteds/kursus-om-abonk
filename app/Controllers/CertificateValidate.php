<?php

namespace App\Controllers;

use App\Models\CertificateModel;

class CertificateValidate extends BaseController
{
    public function index(string $certNumber)
    {
        $certModel = new CertificateModel();
        $cert = $certModel->where('certificate_number', $certNumber)->first();

        return view('certificate/validate', [
            'cert'     => $cert,
            'settings' => $this->getAllSettings(),
        ]);
    }
}
