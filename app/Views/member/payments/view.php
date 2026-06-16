<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<script src="<?= esc((new \App\Libraries\DokuService())->getJsUrl()) ?>"></script>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('info')): ?>
    <div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('info') ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-receipt mr-1"></i> Detail Pembayaran</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID Pembayaran</th>
                        <td>#<?= $payment->id ?></td>
                    </tr>
                    <?php if ($payment->invoice_number): ?>
                    <tr>
                        <th>Invoice Number</th>
                        <td><code><?= esc($payment->invoice_number) ?></code></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Kursus</th>
                        <td><?= esc($payment->course_title ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td><?= esc($payment->class_name ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Bayar</th>
                        <td class="font-weight-bold text-primary">Rp <?= number_format($payment->amount, 0, ',', '.') ?></td>
                    </tr>
                    <?php if ($payment->payment_channel): ?>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td><?= esc($payment->payment_channel) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php
                            $statusLabels = [
                                'pending'  => ['warning', 'Menunggu Pembayaran'],
                                'approved' => ['success', 'Berhasil'],
                                'rejected' => ['danger', 'Gagal'],
                            ];
                            [$color, $label] = $statusLabels[$payment->status] ?? ['secondary', ucfirst($payment->status)];
                            ?>
                            <span class="badge badge-<?= $color ?> p-2"><?= $label ?></span>
                        </td>
                    </tr>
                    <?php if ($payment->admin_notes): ?>
                    <tr>
                        <th>Catatan</th>
                        <td class="text-danger"><?= esc($payment->admin_notes) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td><?= date('d/m/Y H:i', strtotime($payment->created_at)) ?></td>
                    </tr>
                    <?php if ($payment->paid_at): ?>
                    <tr>
                        <th>Dibayar Pada</th>
                        <td><?= date('d/m/Y H:i', strtotime($payment->paid_at)) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <?php if ($payment->status === 'pending'): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-credit-card mr-1"></i> Bayar Sekarang</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($payment->doku_payment_url)): ?>
                        <button id="pay-button" class="btn btn-primary btn-block">
                            <i class="fas fa-lock mr-1"></i> Bayar via DOKU
                        </button>
                        <small class="text-muted d-block mt-2 text-center">Halaman pembayaran akan muncul sebagai popup</small>
                    <?php else: ?>
                        <a href="/member/payments/pay/<?= $payment->id ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-credit-card mr-1"></i> Buat Link Pembayaran
                        </a>
                        <small class="text-muted d-block mt-2 text-center">Klik untuk membuat link pembayaran DOKU</small>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($payment->status === 'approved'): ?>
            <div class="card card-outline card-success">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Pembayaran Berhasil!</h5>
                    <p class="text-muted">Anda sudah terdaftar di kelas ini.</p>
                    <a href="/member/class/<?= $payment->class_id ?>" class="btn btn-success btn-block">
                        <i class="fas fa-door-open"></i> Masuk Kelas
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($payment->status === 'rejected'): ?>
            <div class="card card-outline card-danger">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                    <h5>Pembayaran Gagal</h5>
                    <p class="text-muted">Silakan coba lagi atau hubungi admin.</p>
                    <a href="/member/payments/pay/<?= $payment->id ?>" class="btn btn-warning btn-block">
                        <i class="fas fa-redo"></i> Coba Bayar Lagi
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <a href="/member/payments" class="btn btn-secondary btn-block mt-2"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php if ($payment->status === 'pending' && !empty($payment->doku_payment_url)): ?>
<script>
document.getElementById('pay-button').addEventListener('click', function() {
    loadJokulCheckout('<?= esc($payment->doku_payment_url) ?>');
});

setInterval(function() {
    fetch('/member/payments/status/<?= $payment->id ?>')
        .then(r => r.json())
        .then(data => {
            if (data.status === 'approved' || data.status === 'rejected') {
                window.location.reload();
            }
        })
        .catch(() => {});
}, 5000);
</script>
<?php endif; ?>

<?= $this->endSection() ?>
