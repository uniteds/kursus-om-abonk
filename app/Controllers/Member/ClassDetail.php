<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\ContentModel;
use App\Models\AnnouncementModel;
use App\Models\EnrollmentModel;

class ClassDetail extends BaseController
{
    public function index($id)
    {
        $classModel = new ClassModel();
        $contentModel = new ContentModel();
        $announcementModel = new AnnouncementModel();
        $enrollmentModel = new EnrollmentModel();
        $userId = $this->session->get('user_id');

        // Check enrollment
        $enrollment = $enrollmentModel->where('user_id', $userId)->where('class_id', $id)->first();

        if (!$enrollment || $enrollment->status !== 'approved') {
            return redirect()->to('/member/my-courses')->with('error', 'Anda belum memiliki akses ke kelas ini.');
        }

        $class = $classModel->getClassBySlug($id);
        $contents = $contentModel->getContentByClass($id);
        $announcements = $announcementModel->getAnnouncementsByClass($id);

        if (!$class) {
            return redirect()->to('/member/my-courses')->with('error', 'Kelas tidak ditemukan.');
        }

        return view('member/class-detail/index', [
            'title'         => $class->name,
            'class'         => $class,
            'contents'      => $contents,
            'announcements' => $announcements,
            'settings'      => $this->getAllSettings(),
        ]);
    }
}
