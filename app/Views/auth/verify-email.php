<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Om Abonk</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .verify-box { width: 460px; }
        .card { border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,.2); padding: 2rem; text-align: center; }
        .icon-success { font-size: 3.5rem; color: #10b981; margin-bottom: 1rem; }
        .icon-error { font-size: 3.5rem; color: #ef4444; margin-bottom: 1rem; }
        .card h2 { font-size: 1.3rem; font-weight: 700; margin-bottom: .8rem; }
        .card p { color: #6b7280; font-size: .9rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .btn-login { display: inline-block; padding: .6rem 2rem; background: #4f46e5; color: #fff; border-radius: .5rem; text-decoration: none; font-weight: 600; font-size: .9rem; }
        .btn-login:hover { background: #4338ca; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="verify-box">
    <div style="text-align:center;margin-bottom:1.2rem;">
        <a href="/" class="text-dark" style="text-decoration:none;">
            <i class="fas fa-graduation-cap" style="font-size:2rem;color:#fff;"></i>
            <span style="font-size:1.2rem;font-weight:700;color:#fff;margin-left:.5rem;">Om Abonk</span>
        </a>
    </div>
    <div class="card">
        <?php if ($status === 'success'): ?>
            <i class="fas fa-check-circle icon-success"></i>
            <h2 style="color:#10b981;">Berhasil!</h2>
        <?php else: ?>
            <i class="fas fa-exclamation-circle icon-error"></i>
            <h2 style="color:#ef4444;">Gagal</h2>
        <?php endif; ?>
        <p><?= $message ?></p>
        <a href="/login" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login Sekarang</a>
    </div>
</div>
</body>
</html>
