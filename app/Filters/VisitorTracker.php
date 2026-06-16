<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VisitorTracker implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if ($request->getMethod() !== 'GET') {
            return;
        }

        $uri = $request->getUri()->getPath();

        $skipPatterns = [
            'favicon', 'robots.txt', 'sitemap.xml', 'og-default',
            'assets/', 'css/', 'js/', 'images/', '.svg', '.png',
            '.jpg', '.ico', '.css', '.js', 'index.php',
            'debug_toolbar', 'toolbar',
        ];

        foreach ($skipPatterns as $pattern) {
            if (stripos($uri, $pattern) !== false) {
                return;
            }
        }

        try {
            $session = session();
            $ip = $request->getIPAddress();
            $today = date('Y-m-d');
            $userId = $session->get('user_id');
            $lastLogKey = 'last_visitor_log';

            $lastLog = $session->get($lastLogKey);
            if ($lastLog && (time() - $lastLog) < 1800) {
                return;
            }

            $model = new \App\Models\VisitorLogModel();

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

            $session->set($lastLogKey, time());
        } catch (\Throwable $e) {
        }
    }
}
