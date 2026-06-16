<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h3><?= esc($course->title) ?></h3>
                <span class="badge badge-primary mb-2"><?= esc($course->category_name ?? 'Umum') ?></span>
                <div class="mt-3">
                    <?= nl2br(esc($course->description ?? '')) ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Kelas yang Tersedia</h3>
            </div>
            <div class="card-body">
                <?php if (empty($classes)): ?>
                    <p class="text-muted text-center">Belum ada kelas tersedia.</p>
                <?php else: ?>
                    <?php foreach ($classes as $cls): ?>
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1"><?= esc($cls->name) ?></h5>
                                        <p class="text-muted mb-1"><i class="fas fa-clock mr-1"></i> <?= esc($cls->schedule ?? 'Belum diatur') ?></p>
                                        <p class="text-muted mb-1"><i class="fas fa-users mr-1"></i> Kuota: <?= $cls->capacity ?> orang</p>
                                        <?php
                                        $statusColors = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                                        ?>
                                        <span class="badge badge-<?= $statusColors[$cls->status] ?? 'secondary' ?>"><?= ucfirst($cls->status) ?></span>
                                    </div>
                                    <div>
                                        <?php if ($cls->enrolled): ?>
                                            <?php if ($cls->enrollment_status === 'approved'): ?>
                                                <a href="/member/class/<?= $cls->id ?>" class="btn btn-success btn-sm"><i class="fas fa-door-open"></i> Masuk Kelas</a>
                                            <?php elseif ($cls->enrollment_status === 'pending'): ?>
                                                <span class="badge badge-warning p-2">Menunggu Persetujuan</span>
                                            <?php elseif ($cls->enrollment_status === 'rejected'): ?>
                                                <span class="badge badge-danger p-2">Ditolak</span>
                                            <?php else: ?>
                                                <span class="badge badge-info p-2"><?= ucfirst($cls->enrollment_status) ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($course->price > 0): ?>
                                                <a href="/member/payments/create/<?= $cls->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-credit-card"></i> Bayar & Daftar</a>
                                            <?php else: ?>
                                                <form action="/member/courses/enroll/<?= $cls->id ?>" method="POST" style="display:inline;">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Daftar</button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Info Kursus</h3>
            </div>
            <div class="card-body">
                <p><strong>Kategori:</strong> <?= esc($course->category_name ?? '-') ?></p>
                <p><strong>Jumlah Kelas:</strong> <?= count($classes) ?></p>
                <p>
                    <strong>Harga:</strong><br>
                    <?php if ($course->price > 0): ?>
                        <span class="text-primary font-weight-bold" style="font-size:1.3rem;">Rp <?= number_format($course->price, 0, ',', '.') ?></span>
                    <?php else: ?>
                        <span class="badge badge-success p-2" style="font-size:1rem;">Gratis</span>
                    <?php endif; ?>
                </p>
                <a href="/member/courses" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
