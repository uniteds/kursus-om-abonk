<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Enrollment</h3>
        <div class="card-tools">
            <div class="btn-group btn-group-sm">
                <a href="/admin/enrollments" class="btn btn-<?= !$status ? 'primary' : 'outline-primary' ?>">Semua</a>
                <a href="/admin/enrollments?status=pending" class="btn btn-<?= $status === 'pending' ? 'warning' : 'outline-warning' ?>">Pending</a>
                <a href="/admin/enrollments?status=approved" class="btn btn-<?= $status === 'approved' ? 'success' : 'outline-success' ?>">Approved</a>
                <a href="/admin/enrollments?status=rejected" class="btn btn-<?= $status === 'rejected' ? 'danger' : 'outline-danger' ?>">Rejected</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Kelas</th>
                        <th>Kursus</th>
                        <th width="80">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($enrollments)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($enrollments as $e): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($e->user_name ?? '-') ?></td>
                                <td><?= esc($e->user_email ?? '-') ?></td>
                                <td><?= esc($e->class_name ?? '-') ?></td>
                                <td><?= esc($e->course_title ?? '-') ?></td>
                                <td>
                                    <?php
                                    $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'completed' => 'info'];
                                    ?>
                                    <span class="badge badge-<?= $statusColors[$e->status] ?? 'secondary' ?>"><?= ucfirst($e->status) ?></span>
                                </td>
                                <td>
                                    <?php if ($e->status === 'pending'): ?>
                                        <a href="/admin/enrollments/approve/<?= $e->id ?>" class="btn btn-success btn-sm" title="Setujui"><i class="fas fa-check"></i></a>
                                        <a href="/admin/enrollments/reject/<?= $e->id ?>" class="btn btn-danger btn-sm" title="Tolak"><i class="fas fa-times"></i></a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->endSection() ?>
