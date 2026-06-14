<?php

namespace App\Controllers\Landing;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\ClassModel;
use App\Models\EnrollmentModel;
use App\Models\ContentModel;
use App\Models\SiteSettingModel;

class CourseDetail extends BaseController
{
    public function index($slug)
    {
        $courseModel = new CourseModel();
        $classModel  = new ClassModel();
        $enrollmentModel = new EnrollmentModel();
        $contentModel = new ContentModel();
        $settingsModel = new SiteSettingModel();

        $course = $courseModel->where('courses.slug', $slug)
            ->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->first();

        if (!$course) {
            return redirect()->to('/')->with('error', 'Kursus tidak ditemukan.');
        }

        // Get classes with enrollment counts
        $classes = $classModel->where('classes.course_id', $course->id)
            ->select('classes.*, 
                (SELECT COUNT(*) FROM enrollments WHERE enrollments.class_id = classes.id AND enrollments.status IN ("pending","approved")) as total_enrolled,
                (SELECT COUNT(*) FROM enrollments WHERE enrollments.class_id = classes.id AND enrollments.status = "approved") as approved_count,
                (SELECT COUNT(*) FROM content WHERE content.class_id = classes.id) as content_count')
            ->orderBy('classes.id', 'ASC')
            ->findAll();

        // Parse curriculum into array
        $curriculum = [];
        if (!empty($course->curriculum)) {
            $lines = array_filter(array_map('trim', explode("\n", $course->curriculum)));
            $curriculum = $lines;
        }

        return view('landing/course', [
            'course'    => $course,
            'classes'   => $classes,
            'curriculum'=> $curriculum,
            'settings'  => $settingsModel->getAllSettings(),
        ]);
    }
}
