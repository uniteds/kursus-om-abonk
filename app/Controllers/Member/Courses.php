<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class Courses extends BaseController
{
    public function index()
    {
        $model = new CourseModel();
        $keyword = $this->request->getGet('q');

        $builder = $model->builder();
        $builder->select('courses.*, categories.name as category_name');
        $builder->join('categories', 'categories.id = courses.category_id', 'left');
        $builder->where('courses.is_active', 1);

        if ($keyword) {
            $builder->like('courses.title', $keyword)->orLike('categories.name', $keyword);
        }

        $courses = $model->orderBy('courses.id', 'DESC')->paginate(9);
        $pager = $model->pager;

        return view('member/courses/index', [
            'title'    => 'Jelajahi Kursus',
            'courses'  => $courses,
            'pager'    => $pager,
            'keyword'  => $keyword,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function view($id)
    {
        $model = new CourseModel();
        $classModel = new \App\Models\ClassModel();
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $userId = $this->session->get('user_id');

        $course = $model->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('courses.id', $id)
            ->first();

        if (!$course) {
            return redirect()->to('/member/courses')->with('error', 'Kursus tidak ditemukan.');
        }

        $classes = $classModel->where('course_id', $id)->orderBy('id', 'DESC')->findAll();

        foreach ($classes as &$class) {
            $class->enrolled = $enrollmentModel->isEnrolled($userId, $class->id);
            $class->enrollment_status = null;
            if ($class->enrolled) {
                $enrollment = $enrollmentModel->where('user_id', $userId)->where('class_id', $class->id)->first();
                $class->enrollment_status = $enrollment ? $enrollment->status : null;
            }
        }

        return view('member/courses/view', [
            'title'    => $course->title,
            'course'   => $course,
            'classes'  => $classes,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function enroll($classId)
    {
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $userId = $this->session->get('user_id');

        if ($enrollmentModel->isEnrolled($userId, $classId)) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        $enrollmentModel->save([
            'user_id'  => $userId,
            'class_id' => $classId,
            'status'   => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Menunggu persetujuan admin.');
    }
}
