<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kursus Saya</h3>
    </div>
    <div class="card-body">
        <?php if (empty($enrollments)): ?>
            <div class="text-center py-5">
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
                        <?php foreach ($enrollments as $e): ?>
                            <tr>
                                <td><strong><?= esc($e->course_title ?? '-') ?></strong></td>
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
                                    <?php elseif ($e->status === 'completed'): ?>
                                        <a href="/member/class/<?= $e->class_id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-door-open"></i> Masuk</a>
                                        <a href="/member/certificate/generate/<?= $e->id ?>" class="btn btn-success btn-sm"><i class="fas fa-certificate"></i> Sertifikat</a>
                                    <?php else: ?>
                                        <span class="text-muted">Menunggu...</span>
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

<?= $this->endSection() ?>
