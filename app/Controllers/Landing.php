<?php

namespace App\Controllers;

use App\Models\SiteSettingModel;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use App\Models\ContentModel;
use App\Models\AnnouncementModel;

class Landing extends BaseController
{
    public function index()
    {
        $settingsModel    = new SiteSettingModel();
        $courseModel      = new CourseModel();
        $categoryModel    = new CategoryModel();
        $contentModel     = new ContentModel();
        $announcementModel = new AnnouncementModel();

        $data['settings']     = $settingsModel->getAllSettings();
        $data['courses']      = $courseModel->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->orderBy('courses.created_at', 'DESC')->limit(6)->findAll();
        $data['categories']   = $categoryModel->orderBy('name', 'ASC')->findAll();
        $data['courseCount']  = $courseModel->countAllResults();
        $data['userCount']    = model('UserModel')->countAllResults();
        $data['articles']     = $contentModel->getPublished(3);
        $data['announcements']= $announcementModel->getActive(3);

        return view('landing/index', $data);
    }
}
