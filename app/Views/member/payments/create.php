<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-credit-card mr-1"></i> Konfirmasi Pembayaran</h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <table class="table table-bordered">
                    <tr>
                        <th width="200">Kursus</th>
                        <td><?= esc($class->course_title) ?></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td><?= esc($class->name) ?></td>
                    </tr>
                    <tr>
                        <th>Jadwal</th>
                        <td><?= esc($class->schedule ?? 'Belum diatur') ?></td>
                    </tr>
                    <tr>
                        <th>Total Pembayaran</th>
                        <td class="font-weight-bold text-primary" style="font-size:1.3rem;">
                            Rp <?= number_format($class->course_price, 0, ',', '.') ?>
                        </td>
                    </tr>
                </table>

                <form action="/member/payments/store" method="POST" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="class_id" value="<?= $class->id ?>">

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Klik tombol di bawah untuk melanjutkan ke halaman pembayaran. Anda akan memilih metode pembayaran (Virtual Account, QRIS, E-Wallet, dll) di halaman DOKU Checkout.
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-lock mr-1"></i> Lanjut ke Pembayaran
                    </button>
                    <a href="/member/courses/view/<?= $class->course_id ?>" class="btn btn-secondary btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Petunjuk</h3>
            </div>
            <div class="card-body">
                <ol class="mb-0" style="font-size:0.9rem;">
                    <li>Klik "Lanjut ke Pembayaran"</li>
                    <li>Halaman DOKU Checkout akan muncul sebagai popup</li>
                    <li>Pilih metode pembayaran yang diinginkan</li>
                    <li>Ikuti instruksi pembayaran</li>
                    <li>Pembayaran akan diverifikasi otomatis</li>
                    <li>Anda akan masuk kelas setelah pembayaran berhasil</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                <p class="text-muted mb-0" style="font-size:0.85rem;">Pembayaran aman dan terenkripsi via DOKU Payment Gateway</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
