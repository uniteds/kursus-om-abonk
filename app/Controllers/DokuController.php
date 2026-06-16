<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\EnrollmentModel;

class DokuController extends BaseController
{
    public function notification()
    {
        $rawBody = file_get_contents('php://input');
        $headers = [
            'Client-Id'         => $this->request->getServer('HTTP_CLIENT_ID') ?? $this->request->getHeaderLine('Client-Id'),
            'Request-Id'        => $this->request->getServer('HTTP_REQUEST_ID') ?? $this->request->getHeaderLine('Request-Id'),
            'Request-Timestamp' => $this->request->getServer('HTTP_REQUEST_TIMESTAMP') ?? $this->request->getHeaderLine('Request-Timestamp'),
            'Signature'         => $this->request->getServer('HTTP_SIGNATURE') ?? $this->request->getHeaderLine('Signature'),
        ];

        $doku = new \App\Libraries\DokuService();

        if ($doku->isConfigured()) {
            if (!$doku->verifyNotificationSignature($headers, $rawBody)) {
                log_message('error', '[DOKU] Invalid notification signature');
                return $this->response->setStatusCode(401)->setJSON(['error' => 'Invalid signature']);
            }
        }

        $body = json_decode($rawBody, true);
        if (!$body) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid JSON']);
        }

        log_message('info', '[DOKU] Notification received: ' . json_encode($body));

        $invoiceNumber = $body['order']['invoice_number'] ?? '';
        $status       = $body['transaction']['status'] ?? '';
        $channel      = $body['channel']['id'] ?? '';
        $externalId   = $body['transaction']['original_request_id'] ?? '';
        $amount       = $body['order']['amount'] ?? 0;

        if (empty($invoiceNumber)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing invoice_number']);
        }

        $paymentModel    = new PaymentModel();
        $enrollmentModel = new EnrollmentModel();

        $payment = $paymentModel->findByInvoiceNumber($invoiceNumber);
        if (!$payment) {
            log_message('error', "[DOKU] Payment not found for invoice: {$invoiceNumber}");
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Payment not found']);
        }

        $updateData = [
            'id'              => $payment->id,
            'payment_channel' => $channel,
            'external_id'     => $externalId,
        ];

        if ($status === 'SUCCESS') {
            $updateData['status'] = 'approved';
            $updateData['paid_at'] = $body['transaction']['date'] ?? date('Y-m-d H:i:s');

            $existingEnrollment = $enrollmentModel
                ->where('user_id', $payment->user_id)
                ->where('class_id', $payment->class_id)
                ->first();

            if (!$existingEnrollment) {
                $enrollmentModel->save([
                    'user_id'  => $payment->user_id,
                    'class_id' => $payment->class_id,
                    'status'   => 'approved',
                ]);
            } else {
                $enrollmentModel->update($existingEnrollment->id, ['status' => 'approved']);
            }

            log_message('info', "[DOKU] Payment {$payment->id} approved via notification");
        } elseif (in_array($status, ['FAILED', 'EXPIRED'])) {
            $updateData['status'] = 'rejected';
            $updateData['admin_notes'] = "DOKU Status: {$status}";
            log_message('info', "[DOKU] Payment {$payment->id} rejected via notification ({$status})");
        }

        $paymentModel->save($updateData);

        return $this->response->setStatusCode(200)->setJSON(['status' => 'ok']);
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

        if ($payment->status === 'approved') {
            return redirect()->to('/member/payments/view/' . $payment->id)
                ->with('success', 'Pembayaran berhasil! Anda sudah terdaftar di kelas.');
        }

        return redirect()->to('/member/payments/view/' . $payment->id)
            ->with('info', 'Status pembayaran: ' . ucfirst($payment->status));
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
            'status' => $payment->status,
            'paid_at' => $payment->paid_at,
        ]);
    }
}
