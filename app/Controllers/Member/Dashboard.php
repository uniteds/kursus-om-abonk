<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\AnnouncementModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model = new EnrollmentModel();
        $announcementModel = new AnnouncementModel();
        $userId = $this->session->get('user_id');

        $enrollments = $model->getEnrollmentsByUser($userId);
        $pending = $model->where('user_id', $userId)->where('status', 'pending')->countAllResults();
        $approved = $model->where('user_id', $userId)->where('status', 'approved')->countAllResults();
        $completed = $model->where('user_id', $userId)->where('status', 'completed')->countAllResults();

        return view('member/dashboard', [
            'title'          => 'Dashboard',
            'enrollments'    => $enrollments,
            'pendingCount'   => $pending,
            'approvedCount'  => $approved,
            'completedCount' => $completed,
            'announcements'  => $announcementModel->getForMembers(5),
            'settings'       => $this->getAllSettings(),
        ]);
    }
}
