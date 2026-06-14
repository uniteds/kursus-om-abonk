<?php

namespace App\Controllers\Landing;

use App\Controllers\BaseController;
use App\Models\ContentModel;
use App\Models\SiteSettingModel;

class Articles extends BaseController
{
    public function index()
    {
        $model = new ContentModel();
        $settingsModel = new SiteSettingModel();
        $category = $this->request->getGet('kategori');

        $perPage = 9;
        if ($category) {
            $articles = $model->getPublishedByCategory($category, $perPage);
        } else {
            $articles = $model->getPublished($perPage);
        }

        return view('landing/articles', [
            'title'      => 'Artikel & Tutorial',
            'articles'   => $articles,
            'category'   => $category,
            'settings'   => $settingsModel->getAllSettings(),
        ]);
    }
}
