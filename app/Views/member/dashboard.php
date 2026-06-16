<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= count($enrollments) ?></h3>
                <p>Total Kursus Diikuti</p>
            </div>
            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
            <a href="/member/my-courses" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $pendingCount ?></h3>
                <p>Menunggu Persetujuan</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="/member/my-courses" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $approvedCount ?></h3>
                <p>Kursus Aktif</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="/member/my-courses" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<?php if ($pendingPayments > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="callout callout-warning">
            <h5><i class="fas fa-money-bill-wave mr-1"></i> Pembayaran Pending</h5>
            <p>Anda memiliki <strong><?= $pendingPayments ?></strong> pembayaran yang sedang menunggu verifikasi. <a href="/member/payments">Lihat detail</a></p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bullhorn mr-1"></i> Pengumuman Terbaru</h3>
            </div>
            <div class="card-body p-0">
                <?php if (empty($announcements)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada pengumuman.</p>
                    </div>
                <?php else: ?>
                    <?php
                    $colorMap = [
                        'primary' => 'primary', 'success' => 'success', 'danger' => 'danger',
                        'warning' => 'warning', 'info' => 'info', 'secondary' => 'secondary', 'dark' => 'dark',
                    ];
                    ?>
                    <?php foreach ($announcements as $a): ?>
                        <?php $c = $colorMap[$a->color] ?? 'primary'; ?>
                        <div class="callout callout-<?= $c ?>" style="margin:0;border-radius:0;border-left-width:4px;">
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    <i class="<?= esc($a->icon ?? 'fas fa-bullhorn') ?> fa-2x text-<?= $c ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 style="margin:0 0 .25rem;font-weight:700;">
                                        <?= esc($a->title) ?>
                                        <span class="badge badge-<?= $c ?>" style="font-size:.65rem;vertical-align:middle;"><?= ucfirst($a->type) ?></span>
                                    </h5>
                                    <div style="font-size:.85rem;line-height:1.6;color:#444;">
                                        <?= $a->body ?>
                                    </div>
                                    <small class="text-muted"><i class="fas fa-clock"></i> <?= date('d M Y H:i', strtotime($a->published_at ?? $a->created_at)) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-graduation-cap mr-1"></i> Kursus Terbaru yang Diikuti</h3>
                <div class="card-tools">
                    <a href="/member/courses" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Jelajahi Kursus</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($enrollments)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Anda belum mengikuti kursus apapun.</p>
                        <a href="/member/courses" class="btn btn-primary">Jelajahi Kursus</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kursus</th>
                                    <th>Kelas</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($enrollments, 0, 5) as $e): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($e->course_title ?? '-') ?></strong>
                                        </td>
                                        <td><?= esc($e->class_name ?? '-') ?></td>
                                        <td><?= esc($e->schedule ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'completed' => 'info'];
                                            ?>
                                            <span class="badge badge-<?= $statusColors[$e->status] ?? 'secondary' ?>"><?= ucfirst($e->status) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($e->status === 'approved'): ?>
                                                <a href="/member/class/<?= $e->class_id ?>" class="btn btn-primary btn-sm"><i class="fas fa-door-open"></i> Masuk</a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
