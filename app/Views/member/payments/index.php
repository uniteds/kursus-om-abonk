<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-money-bill-wave mr-1"></i> Riwayat Pembayaran</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kursus</th>
                        <th>Kelas</th>
                        <th width="130">Jumlah</th>
                        <th width="120">Metode</th>
                        <th width="100">Status</th>
                        <th width="100">Tanggal</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="8" class="text-center text-muted">Belum ada riwayat pembayaran.</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($p->course_title ?? '-') ?></td>
                                <td><?= esc($p->class_name ?? '-') ?></td>
                                <td class="font-weight-bold">Rp <?= number_format($p->amount, 0, ',', '.') ?></td>
                                <td><?= esc($p->payment_method ?? '-') ?></td>
                                <td>
                                    <?php
                                    $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                    ?>
                                    <span class="badge badge-<?= $statusColors[$p->status] ?? 'secondary' ?>"><?= ucfirst($p->status) ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($p->created_at)) ?></td>
                                <td>
                                    <a href="/member/payments/view/<?= $p->id ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
