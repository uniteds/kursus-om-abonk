<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Om Abonk</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .login-box { width: 420px; }
        .card { border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,.2); }
        .login-logo { font-size: 1.5rem; }
        .login-logo i { font-size: 2.5rem; display: block; margin-bottom: .5rem; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/" class="text-dark">
            <i class="fas fa-graduation-cap text-white"></i>
            <span class="text-white font-weight-bold">Om Abonk</span>
        </a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg font-weight-bold">Buat Password Baru</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="/reset-password" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password Baru (min. 6 karakter)" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirm" class="form-control" placeholder="Konfirmasi Password Baru" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Reset Password</button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-0 text-center">
                <a href="/login"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Login</a>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
