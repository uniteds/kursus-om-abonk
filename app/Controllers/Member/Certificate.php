<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CertificateModel;
use App\Models\CertificateSettingModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Certificate extends BaseController
{
    public function index()
    {
        $certModel = new CertificateModel();
        $userId = $this->session->get('user_id');
        $certificates = $certModel->where('user_id', $userId)->orderBy('issued_at', 'DESC')->findAll();

        return view('member/certificate/index', [
            'title'       => 'Sertifikat Saya',
            'certificates' => $certificates,
            'settings'    => $this->getAllSettings(),
        ]);
    }

    public function generate(int $enrollmentId)
    {
        $certModel = new CertificateModel();
        $enrollmentModel = new EnrollmentModel();
        $userModel = new UserModel();
        $userId = $this->session->get('user_id');

        $enrollment = $enrollmentModel->select('enrollments.*, courses.id as course_id, courses.title as course_title, courses.meetings_count, courses.meeting_duration')
            ->join('classes', 'classes.id = enrollments.class_id', 'left')
            ->join('courses', 'courses.id = classes.course_id', 'left')
            ->where('enrollments.id', $enrollmentId)
            ->first();

        if (!$enrollment || $enrollment->user_id != $userId) {
            return redirect()->back()->with('error', 'Enrollment tidak ditemukan.');
        }
        if ($enrollment->status !== 'completed') {
            return redirect()->back()->with('error', 'Kursus belum dinyatakan selesai.');
        }

        $existing = $certModel->findByEnrollment($enrollmentId);
        if ($existing) {
            return redirect()->to('/member/certificate/download/' . $existing->id);
        }

        $user = $userModel->find($userId);

        $duration = null;
        if (!empty($enrollment->meetings_count)) {
            $jamPelajaran = $enrollment->meetings_count * 2;
            $duration = $jamPelajaran . ' jam pelajaran';
        }

        $certNumber = $certModel->generateCertificateNumber();
        $now = date('Y-m-d H:i:s');

        $certId = $certModel->insert([
            'enrollment_id'       => $enrollmentId,
            'user_id'             => $userId,
            'course_id'           => $enrollment->course_id,
            'class_id'            => $enrollment->class_id,
            'certificate_number'  => $certNumber,
            'participant_name'    => $user->name,
            'course_title'        => $enrollment->course_title ?? 'Kursus',
            'course_duration'     => $duration,
            'issued_at'           => $now,
        ]);

        return redirect()->to('/member/certificate/download/' . $certId);
    }

    public function download(int $certId)
    {
        $certModel = new CertificateModel();
        $userId = $this->session->get('user_id');

        $cert = $certModel->find($certId);
        if (!$cert || $cert->user_id != $userId) {
            return redirect()->back()->with('error', 'Sertifikat tidak ditemukan.');
        }

        $settings = $this->getAllSettings();
        $siteName = $settings['site_name'] ?? 'Om Abonk';
        $siteDescription = $settings['site_description'] ?? 'Platform Kursus IT';

        $certSettingModel = new CertificateSettingModel();
        $certConfig = $certSettingModel->getFirst();

        $issuedFull = date('d F Y', strtotime($cert->issued_at));

        $logoBase64 = '';
        if (!empty($certConfig->logo)) {
            $logoPath = WRITEPATH . 'uploads/certificates/' . $certConfig->logo;
            if (is_file($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoMime = mime_content_type($logoPath);
                $logoBase64 = 'data:' . $logoMime . ';base64,' . base64_encode($logoData);
            }
        }

        $validationUrl = base_url('/certificate/validate/' . $cert->certificate_number);

        $qrOutput = new \chillerlan\QRCode\QRCode();
        $qrDataUri = $qrOutput->render($validationUrl);

        $html = $this->renderCertificateHtml($cert, $siteName, $siteDescription, $issuedFull, $certConfig, $logoBase64, $qrDataUri);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('isFontSubsettingEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $fileName = 'Sertifikat-' . $cert->certificate_number . '.pdf';
        $dompdf->stream($fileName, ['Attachment' => true]);
        exit;
    }

    private function renderCertificateHtml($cert, $siteName, $siteDescription, $issuedFull, $certConfig, $logoBase64, $qrBase64): string
    {
        $e = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        $bc = $e($certConfig->border_color ?: '#12395b');
        $ac = $e($certConfig->accent_color ?: '#18a0fb');
        $certTitle = $e($certConfig->certificate_title ?: 'Sertifikat Pelatihan');
        $certSubtitle = $e($certConfig->certificate_subtitle ?: 'SELESAI PELATIHAN');
        $signerName = $e($certConfig->signer_name ?: $siteName);
        $signerTitle = $e($certConfig->signer_title ?: 'Kepala Platform');
        $siteName = $e($siteName);
        $siteDescription = $e($siteDescription);
        $participantName = $e($cert->participant_name);
        $courseTitle = $e($cert->course_title);
        $certNumber = $e($cert->certificate_number);
        $issuedFull = $e($issuedFull);
        $courseDuration = $e($cert->course_duration ?? '');
        $qrBase64 = $e($qrBase64);

        $logoHtml = '';
        if ($logoBase64) {
            $logoHtml = '<img src="' . $e($logoBase64) . '" style="height:16mm;max-width:42mm;object-fit:contain;" />';
        }

        $durationText = $courseDuration ? '<span class="meta-separator">/</span> Durasi ' . $courseDuration : '';

        return '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page {
    size: A4 landscape;
    margin: 0;
}
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
html,
body {
    width: 297mm;
    height: 210mm;
}
body {
    font-family: "DejaVu Sans", Arial, sans-serif;
    color: #132238;
    background: #ffffff;
}
.page {
    position: relative;
    width: 297mm;
    height: 210mm;
    overflow: hidden;
    background: #f7fbff;
}
.outer-frame {
    position: absolute;
    left: 9mm;
    top: 9mm;
    width: 279mm;
    height: 192mm;
    border: 1.4mm solid ' . $bc . ';
    background: #ffffff;
}
.inner-frame {
    position: absolute;
    left: 13mm;
    top: 13mm;
    width: 271mm;
    height: 184mm;
    border: .38mm solid ' . $ac . ';
}
.hairline-frame {
    position: absolute;
    left: 16mm;
    top: 16mm;
    width: 265mm;
    height: 178mm;
    border: .18mm solid #d9e7f3;
}
.top-accent {
    position: absolute;
    left: 16mm;
    top: 16mm;
    width: 265mm;
    height: 3.4mm;
    background: ' . $bc . ';
}
.top-accent-light {
    position: absolute;
    left: 16mm;
    top: 19.4mm;
    width: 94mm;
    height: 1.2mm;
    background: ' . $ac . ';
}
.accent-block {
    position: absolute;
    right: 22mm;
    top: 29mm;
    width: 48mm;
    height: 27mm;
    border-top: .35mm solid #d8e8f5;
    border-bottom: .35mm solid #d8e8f5;
    opacity: .75;
}
.accent-line-a,
.accent-line-b {
    position: absolute;
    height: .45mm;
}
.accent-line-a {
    left: 8mm;
    top: 7mm;
    width: 36mm;
    background: ' . $ac . ';
}
.accent-line-b {
    left: 17mm;
    top: 15mm;
    width: 26mm;
    background: ' . $bc . ';
}
.content {
    position: absolute;
    left: 24mm;
    top: 26mm;
    width: 249mm;
    height: 159mm;
}
.header-table,
.footer-table {
    width: 100%;
    border-collapse: collapse;
}
.brand-cell {
    width: 70%;
    vertical-align: top;
}
.cert-number-cell {
    width: 30%;
    text-align: right;
    vertical-align: top;
}
.brand-row {
    font-size: 9px;
    letter-spacing: 2.4px;
    text-transform: uppercase;
    color: #5d7286;
}
.site-name {
    margin-top: 1.5mm;
    font-size: 19px;
    font-weight: bold;
    letter-spacing: 3.6px;
    text-transform: uppercase;
    color: ' . $bc . ';
}
.site-description {
    margin-top: 1.2mm;
    font-size: 9.5px;
    color: #68788a;
}
.cert-label {
    display: inline-block;
    padding: 2.2mm 3.4mm;
    border: .25mm solid #d7e4ef;
    background: #f6fbff;
    text-align: left;
}
.cert-label-title {
    font-size: 7px;
    letter-spacing: 1.6px;
    text-transform: uppercase;
    color: #7d8da0;
}
.cert-label-number {
    margin-top: .9mm;
    font-family: "DejaVu Sans Mono", monospace;
    font-size: 10px;
    font-weight: bold;
    letter-spacing: .8px;
    color: ' . $bc . ';
}
.hero {
    margin-top: 19mm;
    text-align: center;
}
.title {
    font-size: 34px;
    font-weight: bold;
    letter-spacing: 5px;
    text-transform: uppercase;
    color: ' . $bc . ';
    line-height: 1.1;
}
.subtitle {
    margin-top: 2.4mm;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: ' . $ac . ';
}
.divider {
    margin: 5mm auto 0;
    width: 92mm;
    height: .45mm;
    background: ' . $ac . ';
}
.statement {
    margin-top: 7mm;
    font-size: 12px;
    letter-spacing: .2px;
    color: #3c4b5f;
}
.participant {
    margin: 3.5mm auto 0;
    width: 182mm;
    font-size: 29px;
    font-weight: bold;
    color: #10243d;
    line-height: 1.18;
}
.participant-rule {
    margin: 3mm auto 0;
    width: 116mm;
    height: .55mm;
    background: ' . $bc . ';
}
.course-copy {
    margin-top: 4.5mm;
    font-size: 12px;
    color: #3c4b5f;
}
.course-title {
    margin: 2.5mm auto 0;
    width: 205mm;
    font-size: 18px;
    font-weight: bold;
    color: ' . $bc . ';
    line-height: 1.32;
}
.metadata {
    margin-top: 3.2mm;
    font-size: 10.5px;
    color: #5e6d7e;
}
.meta-separator {
    padding: 0 2.2mm;
    color: #9aa9b7;
}
.footer {
    position: absolute;
    left: 24mm;
    bottom: 21mm;
    width: 249mm;
}
.qr-cell {
    width: 31%;
    vertical-align: bottom;
    text-align: left;
}
.sign-cell {
    width: 38%;
    vertical-align: bottom;
    text-align: center;
}
.verify-cell {
    width: 31%;
    vertical-align: bottom;
    text-align: right;
}
.qr-box {
    display: inline-block;
    padding: 2.2mm;
    border: .25mm solid #d7e4ef;
    background: #fff;
}
.qr-box img {
    width: 22mm;
    height: 22mm;
}
.qr-text {
    margin-top: 1mm;
    font-size: 7.5px;
    color: #6f7f91;
}
.signature-line {
    width: 62mm;
    margin: 0 auto 2mm;
    border-top: .28mm solid #27364a;
}
.signer-name {
    font-size: 12px;
    font-weight: bold;
    color: #132238;
}
.signer-title {
    margin-top: .8mm;
    font-size: 8.5px;
    color: #66768a;
}
.verified-badge {
    display: inline-block;
    padding: 2mm 3mm;
    border: .25mm solid ' . $ac . ';
    color: ' . $bc . ';
    background: #f6fbff;
    text-align: left;
}
.verified-title {
    font-size: 7px;
    font-weight: bold;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}
.verified-copy {
    margin-top: .8mm;
    font-size: 8px;
    color: #67778a;
}
.corner-fill {
    position: absolute;
    right: 9mm;
    bottom: 9mm;
    width: 36mm;
    height: 36mm;
    background: #eef7ff;
    border-left: .35mm solid #d7e4ef;
    border-top: .35mm solid #d7e4ef;
}
.corner-mini {
    position: absolute;
    right: 9mm;
    bottom: 9mm;
    width: 18mm;
    height: 18mm;
    background: ' . $ac . ';
    opacity: .35;
}
</style>
</head>
<body>
<div class="page">
    <div class="outer-frame"></div>
    <div class="inner-frame"></div>
    <div class="hairline-frame"></div>
    <div class="top-accent"></div>
    <div class="top-accent-light"></div>
    <div class="corner-fill"></div>
    <div class="corner-mini"></div>

    <div class="accent-block">
        <div class="accent-line-a"></div>
        <div class="accent-line-b"></div>
    </div>

    <div class="content">
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="brand-cell">
                    ' . ($logoHtml ? '<div style="margin-bottom:3mm;">' . $logoHtml . '</div>' : '') . '
                    <div class="brand-row">Penyelenggara Pelatihan Profesional</div>
                    <div class="site-name">' . $siteName . '</div>
                    <div class="site-description">' . $siteDescription . '</div>
                </td>
                <td class="cert-number-cell">
                    <div class="cert-label">
                        <div class="cert-label-title">Nomor Sertifikat</div>
                        <div class="cert-label-number">' . $certNumber . '</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="hero">
            <div class="title">' . $certTitle . '</div>
            <div class="subtitle">' . $certSubtitle . '</div>
            <div class="divider"></div>
            <div class="statement">Diberikan kepada peserta berikut atas kompetensi, kedisiplinan, dan penyelesaian program pelatihan.</div>
            <div class="participant">' . $participantName . '</div>
            <div class="participant-rule"></div>
            <div class="course-copy">telah menyelesaikan program pelatihan</div>
            <div class="course-title">' . $courseTitle . '</div>
            <div class="metadata">Tanggal Terbit ' . $issuedFull . $durationText . '</div>
        </div>
    </div>

    <div class="footer">
        <table class="footer-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="qr-cell">
                    <div class="qr-box"><img src="' . $qrBase64 . '" /></div>
                    <div class="qr-text">Scan QR untuk validasi sertifikat</div>
                </td>
                <td class="sign-cell">
                    <div class="signature-line"></div>
                    <div class="signer-name">' . $signerName . '</div>
                    <div class="signer-title">' . $signerTitle . '</div>
                </td>
                <td class="verify-cell">
                    <div class="verified-badge">
                        <div class="verified-title">Validasi Sertifikat</div>
                        <div class="verified-copy">Nomor sertifikat dapat diverifikasi secara online.</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>';
    }
}
