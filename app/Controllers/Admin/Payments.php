<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\EnrollmentModel;

class Payments extends BaseController
{
    public function index()
    {
        $model = new PaymentModel();
        $status = $this->request->getGet('status');

        $builder = $model->builder();
        $builder->select('payments.*, users.name as user_name, users.email as user_email, classes.name as class_name, courses.title as course_title');
        $builder->join('users', 'users.id = payments.user_id', 'left');
        $builder->join('classes', 'classes.id = payments.class_id', 'left');
        $builder->join('courses', 'courses.id = classes.course_id', 'left');

        if ($status) {
            $builder->where('payments.status', $status);
        }

        $payments = $model->orderBy('payments.id', 'DESC')->paginate(10);
        $pager = $model->pager;

        $pendingCount = $model->countByStatus('pending');
        $approvedCount = $model->countByStatus('approved');
        $rejectedCount = $model->countByStatus('rejected');

        return view('admin/payments/index', [
            'title'         => 'Manage Pembayaran',
            'payments'      => $payments,
            'pager'         => $pager,
            'status'        => $status,
            'pendingCount'  => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'settings'      => $this->getAllSettings(),
        ]);
    }

    public function view($id)
    {
        $model = new PaymentModel();

        $payment = $model->select('payments.*, users.name as user_name, users.email as user_email, classes.name as class_name, courses.title as course_title, courses.price as course_price')
            ->join('users', 'users.id = payments.user_id', 'left')
            ->join('classes', 'classes.id = payments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('payments.id', $id)
            ->first();

        if (!$payment) {
            return redirect()->to('/admin/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        return view('admin/payments/view', [
            'title'    => 'Detail Pembayaran',
            'payment'  => $payment,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function approve($id)
    {
        $paymentModel = new PaymentModel();
        $enrollmentModel = new EnrollmentModel();

        $payment = $paymentModel->find($id);
        if (!$payment) {
            return redirect()->to('/admin/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $paymentModel->update($id, [
            'status' => 'approved',
            'admin_notes' => $this->request->getPost('admin_notes') ?: null,
        ]);

        $existingEnrollment = $enrollmentModel->where('user_id', $payment->user_id)->where('class_id', $payment->class_id)->first();

        if (!$existingEnrollment) {
            $enrollmentModel->save([
                'user_id'  => $payment->user_id,
                'class_id' => $payment->class_id,
                'status'   => 'approved',
            ]);
        } else {
            $enrollmentModel->update($existingEnrollment->id, ['status' => 'approved']);
        }

        return redirect()->to('/admin/payments')->with('success', 'Pembayaran berhasil disetujui. Enrollment telah dibuat.');
    }

    public function reject($id)
    {
        $paymentModel = new PaymentModel();

        $payment = $paymentModel->find($id);
        if (!$payment) {
            return redirect()->to('/admin/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $paymentModel->update($id, [
            'status' => 'rejected',
            'admin_notes' => $this->request->getPost('admin_notes') ?: null,
        ]);

        return redirect()->to('/admin/payments')->with('success', 'Pembayaran berhasil ditolak.');
    }
}
