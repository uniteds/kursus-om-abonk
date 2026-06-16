<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\CourseModel;
use App\Models\ClassModel;
use App\Models\EnrollmentModel;

class Payments extends BaseController
{
    private function getDoku(): \App\Libraries\DokuService
    {
        return new \App\Libraries\DokuService();
    }

    public function index()
    {
        $model = new PaymentModel();
        $userId = $this->session->get('user_id');

        $payments = $model->getPaymentsByUser($userId);

        return view('member/payments/index', [
            'title'    => 'Riwayat Pembayaran',
            'payments' => $payments,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function create($classId)
    {
        $paymentModel = new PaymentModel();
        $classModel = new ClassModel();
        $enrollmentModel = new EnrollmentModel();
        $userId = $this->session->get('user_id');
        $doku = $this->getDoku();

        $class = $classModel->select('classes.*, courses.title as course_title, courses.price as course_price, courses.slug as course_slug')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('classes.id', $classId)
            ->first();

        if (!$class) {
            return redirect()->to('/member/courses')->with('error', 'Kelas tidak ditemukan.');
        }

        if ($class->course_price <= 0) {
            return redirect()->to('/member/courses/view/' . $class->course_id)->with('error', 'Kursus ini gratis, tidak perlu melakukan pembayaran.');
        }

        if ($enrollmentModel->isEnrolled($userId, $classId)) {
            return redirect()->to('/member/courses/view/' . $class->course_id)->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        if ($paymentModel->hasPendingPayment($userId, $classId)) {
            $pendingPayment = $paymentModel->where('user_id', $userId)
                ->where('class_id', $classId)
                ->where('status', 'pending')
                ->first();
            if ($pendingPayment && $pendingPayment->doku_payment_url) {
                return redirect()->to('/member/payments/view/' . $pendingPayment->id);
            }
            return redirect()->to('/member/courses/view/' . $class->course_id)->with('error', 'Anda sudah memiliki pembayaran yang sedang diproses.');
        }

        return view('member/payments/create', [
            'title'    => 'Bayar & Daftar Kelas',
            'class'    => $class,
            'doku'     => $doku,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function store()
    {
        $rules = [
            'class_id' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $classModel    = new ClassModel();
        $paymentModel  = new PaymentModel();
        $userId        = $this->session->get('user_id');
        $doku          = $this->getDoku();

        $classId = $this->request->getPost('class_id');
        $class = $classModel->select('classes.*, courses.title as course_title, courses.price as course_price')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('classes.id', $classId)
            ->first();

        if (!$class) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $invoiceNumber = $doku->generateInvoiceNumber('OMA');

        $paymentId = $paymentModel->insert([
            'user_id'        => $userId,
            'class_id'       => $classId,
            'invoice_number' => $invoiceNumber,
            'amount'         => $class->course_price,
            'status'         => 'pending',
        ]);

        if (!$paymentId) {
            return redirect()->back()->with('error', 'Gagal membuat data pembayaran.');
        }

        if (!$doku->isConfigured()) {
            log_message('error', '[DOKU] Client ID or Secret Key not configured');
            $paymentModel->update($paymentId, [
                'status'       => 'pending',
                'admin_notes'  => 'DOKU belum dikonfigurasi. Menunggu admin.',
            ]);
            return redirect()->to('/member/payments/view/' . $paymentId)
                ->with('info', 'Payment gateway sedang dikonfigurasi. Silakan tunggu admin atau hubungi admin.');
        }

        $baseUrl = base_url();
        $orderData = [
            'order' => [
                'amount'          => (int) $class->course_price,
                'invoice_number'  => $invoiceNumber,
                'currency'        => 'IDR',
                'callback_url'    => $baseUrl . '/doku/callback',
                'auto_redirect'   => true,
                'language'        => 'ID',
            ],
            'payment' => [
                'payment_due_date' => 60,
            ],
            'customer' => [
                'id'    => 'USR-' . $userId,
                'name'  => $this->session->get('name'),
                'email' => $this->session->get('email') ?? '',
            ],
        ];

        $result = $doku->createPayment($orderData);

        if ($result['success']) {
            $response = $result['response'];
            $paymentUrl  = $response['payment']['url'] ?? '';
            $sessionId   = $response['order']['session_id'] ?? '';
            $tokenId     = $response['payment']['token_id'] ?? '';

            $paymentModel->update($paymentId, [
                'doku_session_id'  => $sessionId,
                'doku_token_id'    => $tokenId,
                'doku_payment_url' => $paymentUrl,
                'payment_method'   => 'DOKU Checkout',
                'admin_notes'      => null,
            ]);

            return view('member/payments/checkout', [
                'title'      => 'Pembayaran DOKU',
                'payment'    => $paymentModel->find($paymentId),
                'paymentUrl' => $paymentUrl,
                'settings'   => $this->getAllSettings(),
            ]);
        }

        log_message('error', '[DOKU] Create payment failed: ' . json_encode($result));
        $paymentModel->update($paymentId, [
            'status'      => 'pending',
            'admin_notes' => 'DOKU API Error: ' . ($result['error'] ?? 'Unknown'),
        ]);

        return redirect()->to('/member/payments/view/' . $paymentId)
            ->with('error', 'Gagal membuat pembayaran: ' . ($result['error'] ?? 'Silakan coba lagi.'));
    }

    public function view($id)
    {
        $paymentModel = new PaymentModel();
        $userId = $this->session->get('user_id');

        $payment = $paymentModel->select('payments.*, users.name as user_name, classes.name as class_name, courses.title as course_title, courses.price as course_price')
            ->join('users', 'users.id = payments.user_id', 'left')
            ->join('classes', 'classes.id = payments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('payments.id', $id)
            ->where('payments.user_id', $userId)
            ->first();

        if (!$payment) {
            return redirect()->to('/member/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        return view('member/payments/view', [
            'title'    => 'Detail Pembayaran',
            'payment'  => $payment,
            'settings' => $this->getAllSettings(),
        ]);
    }

    public function pay($id)
    {
        $paymentModel = new PaymentModel();
        $userId = $this->session->get('user_id');
        $doku = $this->getDoku();

        $payment = $paymentModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$payment) {
            return redirect()->to('/member/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        if ($payment->status !== 'pending') {
            return redirect()->to('/member/payments/view/' . $id)->with('error', 'Pembayaran ini sudah diproses.');
        }

        $classModel = new ClassModel();
        $class = $classModel->select('classes.*, courses.title as course_title, courses.price as course_price')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('classes.id', $payment->class_id)
            ->first();

        if (!$doku->isConfigured()) {
            return redirect()->to('/member/payments/view/' . $id)
                ->with('error', 'Payment gateway sedang dikonfigurasi.');
        }

        $baseUrl = base_url();
        $newInvoiceNumber = $doku->generateInvoiceNumber('OMA');

        $paymentModel->update($id, [
            'invoice_number' => $newInvoiceNumber,
            'doku_session_id'  => null,
            'doku_token_id'    => null,
            'doku_payment_url' => null,
        ]);

        $orderData = [
            'order' => [
                'amount'          => (int) $payment->amount,
                'invoice_number'  => $newInvoiceNumber,
                'currency'        => 'IDR',
                'callback_url'    => $baseUrl . '/doku/callback',
                'auto_redirect'   => true,
                'language'        => 'ID',
            ],
            'payment' => [
                'payment_due_date' => 60,
            ],
            'customer' => [
                'id'    => 'USR-' . $userId,
                'name'  => $this->session->get('name'),
                'email' => $this->session->get('email') ?? '',
            ],
        ];

        $result = $doku->createPayment($orderData);

        if ($result['success']) {
            $response  = $result['response'];
            $paymentUrl = $response['payment']['url'] ?? '';
            $sessionId  = $response['order']['session_id'] ?? '';
            $tokenId    = $response['payment']['token_id'] ?? '';

            $paymentModel->update($id, [
                'doku_session_id'  => $sessionId,
                'doku_token_id'    => $tokenId,
                'doku_payment_url' => $paymentUrl,
                'payment_method'   => 'DOKU Checkout',
            ]);

            return view('member/payments/checkout', [
                'title'      => 'Pembayaran DOKU',
                'payment'    => $paymentModel->find($id),
                'paymentUrl' => $paymentUrl,
                'settings'   => $this->getAllSettings(),
            ]);
        }

        return redirect()->to('/member/payments/view/' . $id)
            ->with('error', 'Gagal membuat pembayaran: ' . ($result['error'] ?? 'Silakan coba lagi.'));
    }

    public function callback()
    {
        $invoiceNumber = $this->request->getGet('invoice_number') ?? '';
        $status        = $this->request->getGet('status') ?? '';

        if (empty($invoiceNumber)) {
            return redirect()->to('/member/payments')->with('error', 'Invalid callback.');
        }

        $paymentModel = new PaymentModel();
        $payment = $paymentModel->findByInvoiceNumber($invoiceNumber);

        if (!$payment) {
            return redirect()->to('/member/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $userId = $this->session->get('user_id');
        if ($payment->user_id != $userId) {
            return redirect()->to('/member/payments')->with('error', 'Akses ditolak.');
        }

        return redirect()->to('/member/payments/view/' . $payment->id);
    }

    public function status($paymentId)
    {
        $paymentModel = new PaymentModel();
        $payment = $paymentModel->find($paymentId);

        if (!$payment) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        }

        $userId = $this->session->get('user_id');
        if ($payment->user_id != $userId) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        return $this->response->setJSON([
            'status'  => $payment->status,
            'paid_at' => $payment->paid_at,
        ]);
    }
}
