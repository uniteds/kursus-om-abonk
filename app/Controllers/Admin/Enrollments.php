<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;

class Enrollments extends BaseController
{
    public function index()
    {
        $model = new EnrollmentModel();
        $status = $this->request->getGet('status');

        $builder = $model->builder();
        $builder->select('enrollments.*, users.name as user_name, users.email as user_email, classes.name as class_name, courses.title as course_title');
        $builder->join('users', 'users.id = enrollments.user_id', 'left');
        $builder->join('classes', 'classes.id = enrollments.class_id', 'left');
        $builder->join('courses', 'courses.id = classes.course_id', 'left');

        if ($status) {
            $builder->where('enrollments.status', $status);
        }

        $enrollments = $model->orderBy('enrollments.id', 'DESC')->paginate(10);
        $pager = $model->pager;

        return view('admin/enrollments/index', [
            'title'       => 'Manage Enrollment',
            'enrollments' => $enrollments,
            'pager'       => $pager,
            'status'      => $status,
            'settings'    => $this->getAllSettings(),
        ]);
    }

    public function approve($id)
    {
        $model = new EnrollmentModel();
        $model->update($id, ['status' => 'approved']);

        return redirect()->to('/admin/enrollments')->with('success', 'Enrollment berhasil disetujui.');
    }

    public function reject($id)
    {
        $model = new EnrollmentModel();
        $model->update($id, ['status' => 'rejected']);

        return redirect()->to('/admin/enrollments')->with('success', 'Enrollment berhasil ditolak.');
    }
}
