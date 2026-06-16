<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Sertifikat - <?= esc($settings['site_name'] ?? 'Om Abonk') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f6f9; min-height: 100vh; display:flex; align-items:center; justify-content:center; }
        .valid-card { max-width:500px; width:100%; border:none; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        .valid-header { background: linear-gradient(135deg, #1a5276, #2e86c1); color:#fff; padding:2rem; text-align:center; border-radius:12px 12px 0 0; }
        .valid-header i { font-size:3rem; margin-bottom:10px; }
        .valid-body { padding:2rem; }
        .valid-result { text-align:center; padding:1.5rem; border-radius:8px; }
        .valid-result.valid { background:#d4edda; border:1px solid #c3e6cb; }
        .valid-result.invalid { background:#f8d7da; border:1px solid #f5c6cb; }
        .info-row { display:flex; padding:8px 0; border-bottom:1px solid #f0f0f0; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-weight:600; color:#555; min-width:140px; }
        .info-value { color:#333; }
    </style>
</head>
<body>
<div class="card valid-card">
    <div class="valid-header">
        <i class="fas fa-certificate"></i>
        <h4 class="mb-0">Validasi Sertifikat</h4>
        <small><?= esc($settings['site_name'] ?? 'Om Abonk') ?></small>
    </div>
    <div class="valid-body">
        <?php if ($cert): ?>
            <div class="valid-result valid mb-4">
                <i class="fas fa-check-circle text-success" style="font-size:2.5rem;"></i>
                <h5 class="text-success mt-2 mb-0">Sertifikat Valid</h5>
                <small class="text-muted">Sertifikat ini diterbitkan secara resmi</small>
            </div>
            <div class="info-row">
                <span class="info-label">No. Sertifikat</span>
                <span class="info-value"><strong><?= esc($cert->certificate_number) ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Peserta</span>
                <span class="info-value"><?= esc($cert->participant_name) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Judul Kursus</span>
                <span class="info-value"><?= esc($cert->course_title) ?></span>
            </div>
            <?php if ($cert->course_duration): ?>
            <div class="info-row">
                <span class="info-label">Durasi</span>
                <span class="info-value"><?= esc($cert->course_duration) ?></span>
            </div>
            <?php endif; ?>
            <div class="info-row">
                <span class="info-label">Tanggal Terbit</span>
                <span class="info-value"><?= date('d F Y', strtotime($cert->issued_at)) ?></span>
            </div>
        <?php else: ?>
            <div class="valid-result invalid mb-4">
                <i class="fas fa-times-circle text-danger" style="font-size:2.5rem;"></i>
                <h5 class="text-danger mt-2 mb-0">Sertifikat Tidak Ditemukan</h5>
                <small class="text-muted">Nomor sertifikat tidak valid atau belum terdaftar</small>
            </div>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="/" class="btn btn-secondary btn-sm"><i class="fas fa-home mr-1"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
</body>
</html>
