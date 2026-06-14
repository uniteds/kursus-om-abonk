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
        $this->trackVisitor($request);
    }

    private function trackVisitor(RequestInterface $request): void
    {
        if ($request->getMethod() !== 'GET') {
            return;
        }

        $uri = $request->getUri()->getPath();
        // Clean index.php prefix and query string remnants
        $uri = preg_replace('#(/?index\.php)+#', '', $uri);
        $uri = '/' . ltrim($uri, '/');
        $skip = ['favicon', 'robots.txt', 'sitemap.xml', 'og-default', 'assets/', 'css/', 'js/', 'images/', '.svg', '.png', '.jpg', '.ico', '.css', '.js', 'debug_toolbar', 'toolbar'];

        foreach ($skip as $pattern) {
            if (stripos($uri, $pattern) !== false) {
                return;
            }
        }

        try {
            $model = new \App\Models\VisitorLogModel();
            $ip = $request->getIPAddress();
            $today = date('Y-m-d');
            $userId = session()->get('user_id');

            $existingToday = $model->where('ip_address', $ip)
                                   ->where('DATE(created_at)', $today)
                                   ->countAllResults();

            $model->logVisit([
                'ip_address'  => $ip,
                'user_agent'  => substr($request->getUserAgent()->getAgentString(), 0, 500),
                'url'         => substr($uri, 0, 500),
                'method'      => 'GET',
                'referer'     => substr($request->getServer('HTTP_REFERER') ?? '', 0, 500),
                'user_id'     => $userId,
                'is_unique'   => $existingToday === 0 ? 1 : 0,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // silently fail
        }
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
