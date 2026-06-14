<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $totalUsers ?></h3>
                <p>Total Users</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="/admin/users" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $totalCourses ?></h3>
                <p>Total Kursus</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
            <a href="/admin/courses" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $totalClasses ?></h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon"><i class="fas fa-chalkboard"></i></div>
            <a href="/admin/classes" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $pendingEnrollments ?></h3>
                <p>Menunggu Persetujuan</p>
            </div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="/admin/enrollments?status=pending" class="small-box-footer">Lihat detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Visitor Stats -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;">
            <div class="inner">
                <h3><?= number_format($visitorsToday) ?></h3>
                <p>Kunjungan Hari Ini</p>
            </div>
            <div class="icon"><i class="fas fa-eye"></i></div>
            <div class="small-box-footer" style="background: rgba(0,0,0,.1); color: #fff;">
                <?= number_format($visitorsUniqueToday) ?> pengunjung unik
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #8b5cf6, #a855f7); color: #fff;">
            <div class="inner">
                <h3><?= number_format($visitorsMonth) ?></h3>
                <p>Kunjungan Bulan Ini</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <div class="small-box-footer" style="background: rgba(0,0,0,.1); color: #fff;">
                <?= number_format($visitorsUniqueMonth) ?> pengunjung unik
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #0ea5e9, #06b6d4); color: #fff;">
            <div class="inner">
                <h3><?= number_format($visitorsTotal) ?></h3>
                <p>Total Kunjungan</p>
            </div>
            <div class="icon"><i class="fas fa-globe"></i></div>
            <div class="small-box-footer" style="background: rgba(0,0,0,.1); color: #fff;">
                <?= number_format($visitorsUniqueTotal) ?> pengunjung unik
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff;">
            <div class="inner">
                <h3><?= $totalEnrollments ?></h3>
                <p>Total Enrollment</p>
            </div>
            <div class="icon"><i class="fas fa-user-plus"></i></div>
            <div class="small-box-footer" style="background: rgba(0,0,0,.1); color: #fff;">
                <?= $approvedEnrollments ?> disetujui
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Daily Visitors Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-area mr-1 text-purple"></i> Kunjungan 7 Hari Terakhir</h3>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" style="height: 260px;"></canvas>
            </div>
        </div>
    </div>
    <!-- Hourly Chart -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock mr-1 text-info"></i> Kunjungan Hari Ini (Per Jam)</h3>
            </div>
            <div class="card-body">
                <canvas id="hourlyChart" style="height: 260px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="row">
    <!-- Top Pages -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-fire mr-1 text-danger"></i> Halaman Paling Dikunjungi (30 Hari)</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Halaman</th>
                            <th width="80" class="text-right">Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($topPages)): ?>
                            <tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>
                        <?php else: ?>
                            <?php foreach ($topPages as $page): ?>
                                <tr>
                                    <td>
                                        <code style="font-size:.78rem;"><?= esc($page->url) ?></code>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-primary"><?= number_format($page->hits) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1 text-success"></i> Ringkasan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p><strong>Total Members:</strong> <?= $totalMembers ?></p>
                        <p><strong>Total Admin:</strong> <?= $totalUsers - $totalMembers ?></p>
                        <p><strong>Total Content:</strong> <?= $totalContent ?></p>
                    </div>
                    <div class="col-6">
                        <p><strong>Enrollment Disetujui:</strong> <?= $approvedEnrollments ?></p>
                        <p><strong>Enrollment Pending:</strong> <?= $pendingEnrollments ?></p>
                        <p><strong>Site:</strong> <?= esc($settings['site_name'] ?? 'Om Abonk') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Daily Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyData = <?= json_encode($dailyStats) ?>;
const dailyLabels = Object.keys(dailyData).map(d => {
    const date = new Date(d + 'T00:00:00');
    return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
});

new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: 'Total Kunjungan',
            data: Object.values(dailyData).map(v => v.total),
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,.1)',
            fill: true,
            tension: .4,
            pointRadius: 4,
            pointBackgroundColor: '#6366f1',
        }, {
            label: 'Pengunjung Unik',
            data: Object.values(dailyData).map(v => v.unique),
            borderColor: '#a855f7',
            backgroundColor: 'rgba(168,85,247,.08)',
            fill: true,
            tension: .4,
            pointRadius: 4,
            pointBackgroundColor: '#a855f7',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// Hourly Chart
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
const hourlyData = <?= json_encode($hourlyStats) ?>;
const hourlyLabels = Array.from({length: 24}, (_, i) => i + ':00');

new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: hourlyLabels,
        datasets: [{
            label: 'Kunjungan',
            data: hourlyData,
            backgroundColor: 'rgba(14,165,233,.6)',
            borderColor: '#0ea5e9',
            borderWidth: 1,
            borderRadius: 3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } },
            x: { ticks: { maxTicksLimit: 8, font: { size: 10 } } }
        }
    }
});
</script>
<?= $this->endSection() ?>
