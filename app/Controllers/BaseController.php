<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = session();
    }

    protected function getSetting(string $key): ?string
    {
        $model = new \App\Models\SiteSettingModel();
        return $model->getSetting($key);
    }

    protected function getAllSettings(): array
    {
        $model = new \App\Models\SiteSettingModel();
        return $model->getAllSettings();
    }

    protected function uploadFile($file, string $directory, array $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        if (!$file->move(WRITEPATH . 'uploads/' . $directory)) {
            return null;
        }

        $fileName = $file->getName();
        return $fileName;
    }
}
