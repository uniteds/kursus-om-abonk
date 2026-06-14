<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\ContentModel;
use App\Models\SiteSettingModel;

class Sitemap extends BaseController
{
    public function index()
    {
        $settingsModel = new SiteSettingModel();
        $courseModel   = new CourseModel();
        $contentModel  = new ContentModel();
        $settings      = $settingsModel->getAllSettings();
        $baseURL       = base_url('/');
        $lastMod       = date('Y-m-d');

        $courses = $courseModel->select('courses.slug, courses.updated_at')
            ->orderBy('courses.updated_at', 'DESC')
            ->findAll();

        $articles = $contentModel->select('slug, published_at, updated_at')
            ->where('is_published', 1)
            ->orderBy('published_at', 'DESC')
            ->findAll();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$baseURL}</loc>\n";
        $xml .= "    <lastmod>{$lastMod}</lastmod>\n";
        $xml .= "    <changefreq>weekly</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // Login & Register
        foreach (['login', 'register'] as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseURL}{$page}</loc>\n";
            $xml .= "    <lastmod>{$lastMod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.6</priority>\n";
            $xml .= "  </url>\n";
        }

        // Course pages
        foreach ($courses as $course) {
            $updated = !empty($course->updated_at) ? date('Y-m-d', strtotime($course->updated_at)) : $lastMod;
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseURL}course/{$course->slug}</loc>\n";
            $xml .= "    <lastmod>{$updated}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // Article pages
        foreach ($articles as $article) {
            $updated = !empty($article->published_at) ? date('Y-m-d', strtotime($article->published_at)) : $lastMod;
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseURL}artikel/{$article->slug}</loc>\n";
            $xml .= "    <lastmod>{$updated}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";

        return $this->response
            ->setHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->setBody($xml);
    }
}
