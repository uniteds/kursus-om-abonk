<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-receipt mr-1"></i> Detail Pembayaran #<?= $payment->id ?></h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">User</th>
                        <td><?= esc($payment->user_name ?? '-') ?> (<?= esc($payment->user_email ?? '-') ?>)</td>
                    </tr>
                    <tr>
                        <th>Kursus</th>
                        <td><?= esc($payment->course_title ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td><?= esc($payment->class_name ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Harga Kursus</th>
                        <td>Rp <?= number_format($payment->course_price ?? $payment->amount, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Bayar</th>
                        <td class="font-weight-bold text-primary" style="font-size:1.2rem;">Rp <?= number_format($payment->amount, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td><?= esc($payment->payment_method ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Bank / E-Wallet</th>
                        <td><?= esc($payment->bank_name ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Nama Rekening</th>
                        <td><?= esc($payment->account_name ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php
                            $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                            ?>
                            <span class="badge badge-<?= $statusColors[$payment->status] ?? 'secondary' ?> p-2" style="font-size:0.95rem;"><?= ucfirst($payment->status) ?></span>
                        </td>
                    </tr>
                    <?php if ($payment->notes): ?>
                        <tr>
                            <th>Catatan User</th>
                            <td><?= esc($payment->notes) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($payment->admin_notes): ?>
                        <tr>
                            <th>Catatan Admin</th>
                            <td class="text-danger"><?= esc($payment->admin_notes) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Tanggal Kirim</th>
                        <td><?= date('d/m/Y H:i', strtotime($payment->created_at)) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <?php if ($payment->proof_image): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-image mr-1"></i> Bukti Pembayaran</h3>
                </div>
                <div class="card-body text-center">
                    <a href="/uploads/payments/<?= esc($payment->proof_image) ?>" target="_blank">
                        <img src="/uploads/payments/<?= esc($payment->proof_image) ?>" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-width:100%;">
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($payment->status === 'pending'): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-gavel mr-1"></i> Aksi</h3>
                </div>
                <div class="card-body">
                    <form action="/admin/payments/approve/<?= $payment->id ?>" method="POST" onsubmit="return confirm('Setujui pembayaran ini? Enrollment akan otomatis dibuat.')">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="admin_notes_approve">Catatan (Opsional)</label>
                            <input type="text" name="admin_notes" id="admin_notes_approve" class="form-control" placeholder="Catatan untuk user...">
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><i class="fas fa-check mr-1"></i> Setujui Pembayaran</button>
                    </form>
                    <hr>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal"><i class="fas fa-times mr-1"></i> Tolak Pembayaran</button>
                </div>
            </div>

            <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="/admin/payments/reject/<?= $payment->id ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="admin_notes_reject">Alasan Penolakan *</label>
                                    <textarea name="admin_notes" id="admin_notes_reject" class="form-control" rows="3" placeholder="Tulis alasan penolakan..." required></textarea>
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

        <a href="/admin/payments" class="btn btn-secondary btn-block mt-2"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<?= $this->endSection() ?>
