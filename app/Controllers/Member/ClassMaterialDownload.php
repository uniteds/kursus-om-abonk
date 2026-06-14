<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\ClassMaterialModel;
use App\Models\EnrollmentModel;

class ClassMaterialDownload extends BaseController
{
    public function index($classId, $materialId)
    {
        $userId = $this->session->get('user_id');
        $enrollmentModel = new EnrollmentModel();
        $materialModel = new ClassMaterialModel();

        $enrollment = $enrollmentModel->where('user_id', $userId)->where('class_id', $classId)->where('status', 'approved')->first();
        if (!$enrollment) {
            return redirect()->to('/member/my-courses')->with('error', 'Anda tidak memiliki akses ke materi ini.');
        }

        $material = $materialModel->find($materialId);
        if (!$material || !$material->file_path || $material->class_id != $classId) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $filePath = WRITEPATH . 'uploads/materials/' . $material->file_path;
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        $materialModel->incrementDownloads($materialId);

        $ext = pathinfo($material->file_path, PATHINFO_EXTENSION);
        return $this->response->download($material->title . '.' . $ext, file_get_contents($filePath));
    }
}
