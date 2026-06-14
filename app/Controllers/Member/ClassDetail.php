<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\ClassMaterialModel;
use App\Models\AnnouncementModel;
use App\Models\EnrollmentModel;

class ClassDetail extends BaseController
{
    public function index($id)
    {
        $classModel = new ClassModel();
        $materialModel = new ClassMaterialModel();
        $announcementModel = new AnnouncementModel();
        $enrollmentModel = new EnrollmentModel();
        $userId = $this->session->get('user_id');

        $enrollment = $enrollmentModel->where('user_id', $userId)->where('class_id', $id)->first();

        if (!$enrollment || $enrollment->status !== 'approved') {
            return redirect()->to('/member/my-courses')->with('error', 'Anda belum memiliki akses ke kelas ini.');
        }

        $class = $classModel->getClassBySlug($id);
        $materials = $materialModel->getPublishedByClass($id);
        $announcements = $announcementModel->getAnnouncementsByClass($id);

        if (!$class) {
            return redirect()->to('/member/my-courses')->with('error', 'Kelas tidak ditemukan.');
        }

        return view('member/class-detail/index', [
            'title'         => $class->name,
            'class'         => $class,
            'materials'     => $materials,
            'announcements' => $announcements,
            'settings'      => $this->getAllSettings(),
        ]);
    }
}
