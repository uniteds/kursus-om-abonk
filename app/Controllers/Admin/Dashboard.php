<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\ClassModel;
use App\Models\EnrollmentModel;
use App\Models\ContentModel;
use App\Models\VisitorLogModel;
use App\Models\PaymentModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $courseModel = new CourseModel();
        $classModel = new ClassModel();
        $enrollmentModel = new EnrollmentModel();
        $contentModel = new ContentModel();
        $visitorModel = new VisitorLogModel();
        $paymentModel = new PaymentModel();

        $data = [
            'title'            => 'Dashboard Admin',
            'totalUsers'       => $userModel->countAllResults(),
            'totalMembers'     => $userModel->countByRole('member'),
            'totalCourses'     => $courseModel->countAllResults(),
            'totalClasses'     => $classModel->countAllResults(),
            'totalEnrollments' => $enrollmentModel->countAllResults(),
            'pendingEnrollments' => $enrollmentModel->countByStatus('pending'),
            'approvedEnrollments' => $enrollmentModel->countByStatus('approved'),
            'totalContent'     => $contentModel->countAllResults(),
            'pendingPayments'  => $paymentModel->countByStatus('pending'),
            'approvedPayments' => $paymentModel->countByStatus('approved'),
            'settings'         => $this->getAllSettings(),
            // Visitor stats
            'visitorsToday'      => $visitorModel->countToday(),
            'visitorsMonth'      => $visitorModel->countThisMonth(),
            'visitorsUniqueToday'=> $visitorModel->countUniqueToday(),
            'visitorsUniqueMonth'=> $visitorModel->countUniqueThisMonth(),
            'visitorsTotal'      => $visitorModel->getTotalAllTime(),
            'visitorsUniqueTotal'=> $visitorModel->getUniqueAllTime(),
            'dailyStats'         => $visitorModel->getDailyStats(7),
            'topPages'           => $visitorModel->getTopPages(10),
            'hourlyStats'        => $visitorModel->getHourlyStats(),
        ];

        return view('admin/dashboard', $data);
    }
}
