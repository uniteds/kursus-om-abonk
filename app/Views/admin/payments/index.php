<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number"><?= $pendingCount ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Disetujui</span>
                <span class="info-box-number"><?= $approvedCount ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ditolak</span>
                <span class="info-box-number"><?= $rejectedCount ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pembayaran</h3>
        <div class="card-tools">
            <div class="btn-group btn-group-sm">
                <a href="/admin/payments" class="btn btn-<?= !$status ? 'primary' : 'outline-primary' ?>">Semua</a>
                <a href="/admin/payments?status=pending" class="btn btn-<?= $status === 'pending' ? 'warning' : 'outline-warning' ?>">Pending</a>
                <a href="/admin/payments?status=approved" class="btn btn-<?= $status === 'approved' ? 'success' : 'outline-success' ?>">Approved</a>
                <a href="/admin/payments?status=rejected" class="btn btn-<?= $status === 'rejected' ? 'danger' : 'outline-danger' ?>">Rejected</a>
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
                        <th>Kursus</th>
                        <th>Kelas</th>
                        <th width="130">Jumlah</th>
                        <th width="100">Status</th>
                        <th width="100">Tanggal</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="8" class="text-center text-muted">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><?= $p->id ?></td>
                                <td><?= esc($p->user_name ?? '-') ?></td>
                                <td><?= esc($p->course_title ?? '-') ?></td>
                                <td><?= esc($p->class_name ?? '-') ?></td>
                                <td class="font-weight-bold">Rp <?= number_format($p->amount, 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                    ?>
                                    <span class="badge badge-<?= $statusColors[$p->status] ?? 'secondary' ?>"><?= ucfirst($p->status) ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($p->created_at)) ?></td>
                                <td>
                                    <a href="/admin/payments/view/<?= $p->id ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                    <?php if ($p->status === 'pending'): ?>
                                        <form action="/admin/payments/approve/<?= $p->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Setujui pembayaran ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui"><i class="fas fa-check"></i></button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" title="Tolak" data-toggle="modal" data-target="#rejectModal<?= $p->id ?>"><i class="fas fa-times"></i></button>

                                        <div class="modal fade" id="rejectModal<?= $p->id ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="/admin/payments/reject/<?= $p->id ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Pembayaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Tolak pembayaran dari <strong><?= esc($p->user_name) ?></strong>?</p>
                                                            <div class="form-group">
                                                                <label for="admin_notes">Alasan Penolakan</label>
                                                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Tulis alasan penolakan..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
