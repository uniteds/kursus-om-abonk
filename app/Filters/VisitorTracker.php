<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VisitorTracker implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do nothing before
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Only log GET requests (page views, not AJAX/API calls)
        if ($request->getMethod() !== 'GET') {
            return;
        }

        $uri = $request->getUri()->getPath();

        // Skip static assets, API calls, and non-page URLs
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
            $model = new \App\Models\VisitorLogModel();

            $ip       = $request->getIPAddress();
            $today    = date('Y-m-d');
            $userId   = session()->get('user_id');

            // Check if this is a unique visit today from this IP
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
            // Silently fail — visitor logging should never break the app
        }
    }
}
