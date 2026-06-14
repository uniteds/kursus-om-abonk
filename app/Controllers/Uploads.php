<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class Uploads extends Controller
{
    private const PUBLIC_DIRECTORIES = [
        'thumbnails' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'avatars'    => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
    ];

    private const MIME_TYPES = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'webp' => 'image/webp',
        'svg'  => 'image/svg+xml',
    ];

    public function show(string $directory, string $fileName)
    {
        $directory = trim($directory, '/\\');

        if (! isset(self::PUBLIC_DIRECTORIES[$directory]) || $this->hasInvalidPath($fileName)) {
            throw PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (! in_array($extension, self::PUBLIC_DIRECTORIES[$directory], true)) {
            throw PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $fileName;

        if (! is_file($filePath)) {
            throw PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        return $this->response
            ->setHeader('Cache-Control', 'public, max-age=604800')
            ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT')
            ->setContentType(self::MIME_TYPES[$extension] ?? 'application/octet-stream')
            ->setBody(file_get_contents($filePath));
    }

    private function hasInvalidPath(string $fileName): bool
    {
        return $fileName === ''
            || str_contains($fileName, '..')
            || str_contains($fileName, '/')
            || str_contains($fileName, '\\');
    }
}
