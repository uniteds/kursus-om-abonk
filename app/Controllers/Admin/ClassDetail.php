<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\CourseModel;
use App\Models\ClassMaterialModel;
use App\Models\AnnouncementModel;
use App\Models\EnrollmentModel;

class ClassDetail extends BaseController
{
    public function index($id)
    {
        $classModel = new ClassModel();
        $courseModel = new CourseModel();
        $materialModel = new ClassMaterialModel();
        $announcementModel = new AnnouncementModel();
        $enrollmentModel = new EnrollmentModel();

        $class = $classModel->select('classes.*, courses.title as course_title')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('classes.id', $id)
            ->first();

        if (!$class) {
            return redirect()->to('/admin/classes')->with('error', 'Kelas tidak ditemukan.');
        }

        $enrollments = $enrollmentModel->select('enrollments.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = enrollments.user_id', 'left')
            ->where('enrollments.class_id', $id)
            ->orderBy('enrollments.id', 'DESC')
            ->findAll();

        $pendingCount = 0;
        $approvedCount = 0;
        $rejectedCount = 0;
        foreach ($enrollments as $e) {
            switch ($e->status) {
                case 'pending': $pendingCount++; break;
                case 'approved': $approvedCount++; break;
                case 'rejected': $rejectedCount++; break;
            }
        }

        $materials = $materialModel->getByClass($id);

        $announcements = $announcementModel->where('class_id', $id)
            ->orderBy('id', 'DESC')
            ->findAll();

        $tab = $this->request->getGet('tab') ?? 'info';

        return view('admin/class-detail/index', [
            'title'           => 'Detail Kelas: ' . $class->name,
            'class'           => $class,
            'enrollments'     => $enrollments,
            'pendingCount'    => $pendingCount,
            'approvedCount'   => $approvedCount,
            'rejectedCount'   => $rejectedCount,
            'materials'      => $materials,
            'announcements'   => $announcements,
            'tab'             => $tab,
            'settings'        => $this->getAllSettings(),
        ]);
    }

    public function approveEnrollment($classId, $enrollmentId)
    {
        $model = new EnrollmentModel();
        $model->update($enrollmentId, ['status' => 'approved']);
        return redirect()->to("/admin/classes/view/{$classId}?tab=siswa")->with('success', 'Persetujuan berhasil.');
    }

    public function completeEnrollment($classId, $enrollmentId)
    {
        $model = new EnrollmentModel();
        $model->update($enrollmentId, ['status' => 'completed']);
        return redirect()->to("/admin/classes/view/{$classId}?tab=siswa")->with('success', 'Siswa dinyatakan selesai.');
    }

    public function rejectEnrollment($classId, $enrollmentId)
    {
        $model = new EnrollmentModel();
        $model->update($enrollmentId, ['status' => 'rejected']);
        return redirect()->to("/admin/classes/view/{$classId}?tab=siswa")->with('success', 'Penolakan berhasil.');
    }

    public function deleteContent($classId, $contentId)
    {
        $model = new ContentModel();
        $model->delete($contentId);
        return redirect()->to("/admin/classes/view/{$classId}?tab=materi")->with('success', 'Materi berhasil dihapus.');
    }
}
