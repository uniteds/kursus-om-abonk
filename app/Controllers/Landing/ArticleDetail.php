<?php

namespace App\Controllers\Landing;

use App\Controllers\BaseController;
use App\Models\ContentModel;
use App\Models\SiteSettingModel;

class ArticleDetail extends BaseController
{
    public function index($slug)
    {
        $model = new ContentModel();
        $settingsModel = new SiteSettingModel();

        $article = $model->findBySlug($slug);
        if (!$article) {
            return view('errors/404', [
                'title'   => 'Artikel Tidak Ditemukan',
                'message' => 'Artikel yang Anda cari tidak ditemukan atau belum dipublikasikan.',
                'settings' => $settingsModel->getAllSettings(),
            ]);
        }

        $model->incrementViews($article->id);

        $relatedArticles = $model->where('category', $article->category)
            ->where('id !=', $article->id)
            ->where('is_published', 1)
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->findAll();

        return view('landing/article', [
            'title'    => $article->title,
            'article'  => $article,
            'related'  => $relatedArticles,
            'settings' => $settingsModel->getAllSettings(),
        ]);
    }
}
