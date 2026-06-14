<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $totalUsers ?></h3>
                <p>Total Users</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="/admin/users" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $totalCourses ?></h3>
                <p>Total Kursus</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
            <a href="/admin/courses" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $totalClasses ?></h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon"><i class="fas fa-chalkboard"></i></div>
            <a href="/admin/classes" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $pendingEnrollments ?></h3>
                <p>Menunggu Persetujuan</p>
            </div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="/admin/enrollments?status=pending" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Statistik Users</h3>
            </div>
            <div class="card-body">
                <p><strong>Total Members:</strong> <?= $totalMembers ?></p>
                <p><strong>Total Admin:</strong> <?= $totalUsers - $totalMembers ?></p>
                <p><strong>Total Content:</strong> <?= $totalContent ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Statistik Enrollment</h3>
            </div>
            <div class="card-body">
                <p><strong>Total Enrollment:</strong> <?= $totalEnrollments ?></p>
                <p><strong>Disetujui:</strong> <?= $approvedEnrollments ?></p>
                <p><strong>Menunggu:</strong> <?= $pendingEnrollments ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Info</h3>
            </div>
            <div class="card-body">
                <p><strong>Site:</strong> <?= esc($settings['site_name'] ?? 'Om Abonk') ?></p>
                <p><strong>Selamat datang di Dashboard Admin!</strong></p>
                <p class="text-muted">Kelola kursus, kelas, dan pengguna dari sini.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
