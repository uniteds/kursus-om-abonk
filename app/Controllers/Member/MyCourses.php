<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;

class MyCourses extends BaseController
{
    public function index()
    {
        $model = new EnrollmentModel();
        $userId = $this->session->get('user_id');

        $enrollments = $model->getEnrollmentsByUser($userId);

        return view('member/my-courses/index', [
            'title'       => 'Kursus Saya',
            'enrollments' => $enrollments,
            'settings'    => $this->getAllSettings(),
        ]);
    }
}
