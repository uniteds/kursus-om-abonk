<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Belajar IT Bersama Om Abonk</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .login-box { width: 450px; }
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
            <span class="text-white font-weight-bold">Belajar IT Bersama Om Abonk</span>
        </a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg font-weight-bold">Daftar Akun Baru</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="POST">
                <?= csrf_field() ?>
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="<?= old('name') ?>" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password (min. 6 karakter)" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirm" class="form-control" placeholder="Konfirmasi Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus mr-1"></i> Daftar</button>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-0 text-center">
                Sudah punya akun? <a href="/login" class="text-center">Login</a>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
