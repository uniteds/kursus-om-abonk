<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<script src="<?= esc((new \App\Libraries\DokuService())->getJsUrl()) ?>"></script>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-credit-card mr-1"></i> Pembayaran DOKU</h3>
            </div>
            <div class="card-body text-center py-5">
                <div id="loading-state">
                    <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                    <h5>Memuat halaman pembayaran...</h5>
                    <p class="text-muted">Halaman DOKU Checkout akan muncul sebagai popup</p>
                </div>

                <div id="manual-trigger" style="display:none;">
                    <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                    <h5>Siap Membayar</h5>
                    <p class="text-muted mb-3">Klik tombol di bawah untuk membuka halaman pembayaran</p>
                    <button id="pay-button" class="btn btn-primary btn-lg">
                        <i class="fas fa-lock mr-1"></i> Bayar Sekarang
                    </button>
                    <p class="text-muted mt-2" style="font-size:0.85rem;">ID: #<?= $payment->id ?> | Rp <?= number_format($payment->amount, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Info</h3>
            </div>
            <div class="card-body">
                <p><strong>Kursus:</strong> <?= esc($payment->course_title ?? '-') ?></p>
                <p><strong>Kelas:</strong> <?= esc($payment->class_name ?? '-') ?></p>
                <p><strong>Total:</strong> <span class="text-primary font-weight-bold">Rp <?= number_format($payment->amount, 0, ',', '.') ?></span></p>
                <hr>
                <p class="text-muted mb-0" style="font-size:0.85rem;">
                    <i class="fas fa-clock mr-1"></i> Halaman ini akan auto-refresh setelah pembayaran berhasil.
                </p>
            </div>
        </div>
        <a href="/member/payments" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<script>
var paymentUrl = '<?= esc($paymentUrl) ?>';
var paymentId  = <?= $payment->id ?>;

function openCheckout() {
    loadJokulCheckout(paymentUrl);
}

setTimeout(function() {
    document.getElementById('loading-state').style.display = 'none';
    document.getElementById('manual-trigger').style.display = 'block';
    openCheckout();
}, 1500);

document.getElementById('pay-button').addEventListener('click', function() {
    openCheckout();
});

setInterval(function() {
    fetch('/member/payments/status/' + paymentId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.status === 'approved' || data.status === 'rejected') {
                window.location.href = '/member/payments/view/' + paymentId;
            }
        })
        .catch(function() {});
}, 5000);
</script>

<?= $this->endSection() ?>
