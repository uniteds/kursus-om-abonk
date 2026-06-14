<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - <?= esc($settings['site_name'] ?? 'Om Abonk') ?></title>
    <meta name="description" content="<?= esc($settings['site_description'] ?? 'Platform kursus IT untuk pemula hingga mahir') ?>">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Theme style (AdminLTE 3 + Bootstrap 4) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <style>
        .content-wrapper { min-height: calc(100vh - 57px); }
        .brand-link { border-bottom: 1px solid rgba(255,255,255,.1); padding: .8125rem .5rem; }
        .brand-link .brand-text { font-size: .95rem; letter-spacing: .5px; }
        .small-box { border-radius: .5rem; }
        .card { border-radius: .5rem; }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: rgba(255,255,255,.15);
            color: #fff;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: .6rem 1rem;
            color: rgba(255,255,255,.8);
            transition: all .15s ease-in-out;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,.08);
        }
        .sidebar .nav-link .nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.6rem;
            font-size: .85rem;
            margin-right: .6rem;
            text-align: center;
        }
        .sidebar .nav-link p {
            margin: 0;
            font-size: .875rem;
            line-height: 1.4;
        }
        .sidebar .nav-header {
            font-size: .7rem;
            letter-spacing: .8px;
            padding: 1rem 1rem .4rem;
            color: rgba(255,255,255,.4);
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle mr-1"></i>
                    <?= esc(session()->get('name')) ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/<?= esc(session()->get('role')) ?>/profile" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/<?= esc(session()->get('role')) ?>/dashboard" class="brand-link text-center">
            <span class="brand-text font-weight-bold text-white" style="font-size:1rem;">
                <i class="fas fa-graduation-cap mr-1"></i>
                Om Abonk
            </span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <?php if (session()->get('role') === 'admin'): ?>
                    <?= $this->include('templates/sidebar_admin') ?>
                <?php else: ?>
                    <?= $this->include('templates/sidebar_member') ?>
                <?php endif; ?>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?= esc($title ?? 'Dashboard') ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/<?= esc(session()->get('role')) ?>/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><?= esc($title ?? 'Dashboard') ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>&copy; <?= date('Y') ?> <?= esc($settings['site_name'] ?? 'Om Abonk') ?></strong>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script>
function confirmDelete(url) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = url;
    }
}
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
