<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($course->title) ?> - <?= esc($settings['site_name'] ?? 'Om Abonk') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', -apple-system, sans-serif; color: #1e293b; background: #f1f5f9; line-height: 1.6; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: 60px; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,.92); backdrop-filter: blur(16px);
            box-shadow: 0 1px 0 rgba(0,0,0,.06);
        }
        .nav-brand { display: flex; align-items: center; gap: .5rem; font-weight: 800; font-size: 1.1rem; color: #4f46e5; text-decoration: none; }
        .nav-brand i { font-size: 1.2rem; }
        .nav-links { display: flex; align-items: center; gap: .4rem; }
        .nav-links a { padding: .4rem .9rem; border-radius: .5rem; text-decoration: none; font-weight: 500; font-size: .82rem; color: #64748b; transition: all .2s; }
        .nav-links a:hover { color: #4f46e5; background: #eef2ff; }
        .btn-nav-primary { background: #4f46e5 !important; color: #fff !important; font-weight: 600 !important; box-shadow: 0 2px 8px rgba(79,70,229,.25); }
        .btn-nav-primary:hover { background: #4338ca !important; }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
            padding: 6.5rem 2rem 3.5rem;
            position: relative; overflow: hidden;
        }
        .hero::after {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-inner { max-width: 1140px; margin: 0 auto; position: relative; z-index: 2; }

        .breadcrumb { display: flex; align-items: center; gap: .4rem; margin-bottom: 1.2rem; font-size: .78rem; }
        .breadcrumb a { color: rgba(255,255,255,.7); text-decoration: none; transition: color .2s; }
        .breadcrumb a:hover { color: #fff; }
        .breadcrumb .sep { color: rgba(255,255,255,.35); font-size: .55rem; }
        .breadcrumb .current { color: rgba(255,255,255,.9); }

        .hero-grid { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }

        .hero-left { color: #fff; }
        .hero-tag {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(255,255,255,.12); backdrop-filter: blur(8px);
            padding: .3rem .8rem; border-radius: 50px;
            font-size: .72rem; font-weight: 600; letter-spacing: .3px;
            border: 1px solid rgba(255,255,255,.15); margin-bottom: 1rem;
        }
        .hero-left h1 { font-size: 2rem; font-weight: 800; line-height: 1.25; margin-bottom: .8rem; letter-spacing: -.3px; }
        .hero-left .desc { font-size: .92rem; color: rgba(255,255,255,.85); line-height: 1.7; margin-bottom: 1.5rem; max-width: 520px; }
        .hero-stats { display: flex; gap: .6rem; flex-wrap: wrap; }
        .hero-stat {
            display: flex; align-items: center; gap: .45rem;
            background: rgba(255,255,255,.1); backdrop-filter: blur(6px);
            padding: .45rem .9rem; border-radius: .5rem;
            font-size: .78rem; color: rgba(255,255,255,.9);
            border: 1px solid rgba(255,255,255,.1);
        }
        .hero-stat i { font-size: .75rem; opacity: .7; }

        /* HERO SIDEBAR CARD */
        .hero-card {
            background: #fff; border-radius: 1rem; overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.18);
        }
        .hero-card-img {
            height: 170px; background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .hero-card-img img { width: 100%; height: 100%; object-fit: cover; }
        .hero-card-img i { font-size: 2.5rem; color: rgba(255,255,255,.25); }
        .hero-card-body { padding: 1.25rem 1.5rem 1.5rem; }
        .hc-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: .6rem 0; border-bottom: 1px solid #f1f5f9;
        }
        .hc-row:last-child { border-bottom: none; }
        .hc-label { font-size: .8rem; color: #94a3b8; }
        .hc-value { font-size: .82rem; font-weight: 600; color: #1e293b; }
        .btn-cta {
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            width: 100%; padding: .75rem; margin-top: 1rem;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff; border: none; border-radius: .6rem;
            font-size: .88rem; font-weight: 700; text-decoration: none;
            box-shadow: 0 4px 15px rgba(79,70,229,.3);
            transition: all .25s;
        }
        .btn-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }

        /* ===== MAIN LAYOUT ===== */
        .main { max-width: 1140px; margin: 2rem auto 3rem; padding: 0 2rem; display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; }

        /* ===== SECTION CARD ===== */
        .card-section {
            background: #fff; border-radius: .75rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.25rem; overflow: hidden;
        }
        .card-section-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: .5rem;
        }
        .card-section-header i { color: #4f46e5; font-size: .9rem; width: 20px; text-align: center; }
        .card-section-header h2 { font-size: .95rem; font-weight: 700; color: #1e293b; }
        .card-section-body { padding: 1.25rem 1.5rem; }

        /* DESCRIPTION */
        .desc-text { font-size: .88rem; color: #475569; line-height: 1.8; }

        /* CURRICULUM */
        .curr-list { list-style: none; }
        .curr-item {
            display: flex; align-items: flex-start; gap: .75rem;
            padding: .65rem 0;
            border-bottom: 1px solid #f8fafc;
        }
        .curr-item:last-child { border-bottom: none; }
        .curr-num {
            flex-shrink: 0; width: 26px; height: 26px;
            background: #eef2ff; color: #4f46e5;
            border-radius: .35rem; display: flex;
            align-items: center; justify-content: center;
            font-size: .7rem; font-weight: 700; margin-top: .1rem;
        }
        .curr-text { font-size: .85rem; color: #334155; line-height: 1.5; }

        .empty-msg { color: #94a3b8; font-size: .85rem; padding: 1rem 0; text-align: center; }

        /* CLASS LIST */
        .class-card {
            border: 1px solid #e2e8f0; border-radius: .6rem;
            padding: 1rem 1.2rem; margin-bottom: .7rem;
            transition: all .2s;
        }
        .class-card:last-child { margin-bottom: 0; }
        .class-card:hover { border-color: #c7d2fe; background: #fafaff; }
        .cc-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; }
        .cc-name { font-size: .9rem; font-weight: 700; color: #1e293b; }
        .cc-badge {
            padding: .15rem .55rem; border-radius: 50px;
            font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .3px;
        }
        .cc-badge.upcoming { background: #fef3c7; color: #b45309; }
        .cc-badge.ongoing { background: #d1fae5; color: #047857; }
        .cc-badge.completed { background: #e2e8f0; color: #64748b; }
        .cc-desc { font-size: .8rem; color: #64748b; margin-bottom: .5rem; line-height: 1.5; }
        .cc-meta { display: flex; gap: .8rem; flex-wrap: wrap; margin-bottom: .5rem; }
        .cc-meta span { display: flex; align-items: center; gap: .3rem; font-size: .75rem; color: #94a3b8; }
        .cc-meta i { font-size: .7rem; }
        .cc-progress { display: flex; align-items: center; gap: .6rem; }
        .cc-bar { flex: 1; height: 5px; background: #f1f5f9; border-radius: 3px; overflow: hidden; }
        .cc-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #4f46e5, #7c3aed); transition: width .5s; }
        .cc-pct { font-size: .7rem; color: #94a3b8; font-weight: 600; min-width: 28px; text-align: right; }
        .cc-capacity { font-size: .72rem; color: #94a3b8; }

        /* ===== SIDEBAR ===== */
        .sidebar-sticky { position: sticky; top: 5rem; }

        .side-info-list { list-style: none; }
        .side-info-list li {
            display: flex; justify-content: space-between; align-items: center;
            padding: .55rem 0; border-bottom: 1px solid #f8fafc;
            font-size: .82rem;
        }
        .side-info-list li:last-child { border-bottom: none; }
        .si-label { color: #94a3b8; }
        .si-value { font-weight: 600; color: #1e293b; }

        .side-btn {
            display: flex; align-items: center; justify-content: center; gap: .4rem;
            width: 100%; padding: .65rem; border-radius: .5rem;
            font-size: .82rem; font-weight: 600; text-decoration: none;
            transition: all .2s; margin-top: .6rem; border: none; cursor: pointer;
        }
        .side-btn-outline { color: #4f46e5; background: #eef2ff; border: 1px solid #c7d2fe; }
        .side-btn-outline:hover { background: #e0e7ff; }
        .side-btn-primary { color: #fff; background: #4f46e5; box-shadow: 0 2px 8px rgba(79,70,229,.25); }
        .side-btn-primary:hover { background: #4338ca; }

        /* ===== FOOTER ===== */
        .footer {
            background: #0f172a; color: #94a3b8; padding: 2rem;
            text-align: center; font-size: .8rem;
        }
        .footer-inner { max-width: 1140px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .footer a { color: #818cf8; text-decoration: none; }
        .footer-links { display: flex; gap: 1.5rem; }
        .footer-links a { color: #94a3b8; font-size: .78rem; }
        .footer-links a:hover { color: #c7d2fe; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 960px) {
            .hero-grid, .main { grid-template-columns: 1fr; }
            .hero-card { margin-top: 1.5rem; }
            .hero-left h1 { font-size: 1.6rem; }
            .sidebar-sticky { position: static; }
            .nav-links { display: none; }
            .footer-inner { flex-direction: column; gap: .8rem; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="/" class="nav-brand"><i class="fas fa-graduation-cap"></i> Om Abonk</a>
    <div class="nav-links">
        <a href="/">Beranda</a>
        <a href="/login">Masuk</a>
        <a href="/register" class="btn-nav-primary">Daftar Gratis</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <div class="breadcrumb">
            <a href="/"><i class="fas fa-home"></i></a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <a href="/#kursus">Kursus</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span class="current"><?= esc($course->title) ?></span>
        </div>

        <div class="hero-grid">
            <div class="hero-left">
                <span class="hero-tag"><i class="fas fa-tag"></i> <?= esc($course->category_name ?? 'Kursus') ?></span>
                <h1><?= esc($course->title) ?></h1>
                <p class="desc"><?= esc($course->description ?? 'Kursus ini akan membantu Anda mempelajari materi dari dasar hingga mahir.') ?></p>
                <div class="hero-stats">
                    <?php if ($course->meetings_count): ?>
                        <span class="hero-stat"><i class="fas fa-calendar-check"></i> <?= esc($course->meetings_count) ?> Pertemuan</span>
                    <?php endif; ?>
                    <?php if ($course->meeting_duration): ?>
                        <span class="hero-stat"><i class="fas fa-clock"></i> <?= esc($course->meeting_duration) ?> Menit / Pertemuan</span>
                    <?php endif; ?>
                    <span class="hero-stat"><i class="fas fa-chalkboard"></i> <?= count($classes) ?> Kelas</span>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-card-img">
                    <?php if (!empty($course->thumbnail)): ?>
                        <img src="/uploads/thumbnails/<?= esc($course->thumbnail) ?>" alt="<?= esc($course->title) ?>">
                    <?php else: ?>
                        <i class="fas fa-laptop-code"></i>
                    <?php endif; ?>
                </div>
                <div class="hero-card-body">
                    <div class="hc-row">
                        <span class="hc-label">Kategori</span>
                        <span class="hc-value"><?= esc($course->category_name ?? '-') ?></span>
                    </div>
                    <div class="hc-row">
                        <span class="hc-label">Pertemuan</span>
                        <span class="hc-value"><?= $course->meetings_count ? esc($course->meetings_count) . 'x' : '-' ?></span>
                    </div>
                    <div class="hc-row">
                        <span class="hc-label">Durasi</span>
                        <span class="hc-value"><?= $course->meeting_duration ? esc($course->meeting_duration) . ' menit' : '-' ?></span>
                    </div>
                    <div class="hc-row">
                        <span class="hc-label">Kelas</span>
                        <span class="hc-value"><?= count($classes) ?> Rombongan</span>
                    </div>
                    <a href="#kelas" class="btn-cta"><i class="fas fa-arrow-down"></i> Lihat Kelas</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="main">
    <!-- LEFT COLUMN -->
    <div>
        <!-- Tentang Kursus -->
        <div class="card-section">
            <div class="card-section-header">
                <i class="fas fa-book-open"></i>
                <h2>Tentang Kursus Ini</h2>
            </div>
            <div class="card-section-body">
                <p class="desc-text"><?= nl2br(esc($course->description ?? 'Deskripsi kursus belum tersedia.')) ?></p>
            </div>
        </div>

        <!-- Kurikulum -->
        <div class="card-section">
            <div class="card-section-header">
                <i class="fas fa-list-ol"></i>
                <h2>Kurikulum / Silabus</h2>
            </div>
            <div class="card-section-body">
                <?php if (!empty($curriculum)): ?>
                    <ul class="curr-list">
                        <?php foreach ($curriculum as $i => $item): ?>
                            <li class="curr-item">
                                <span class="curr-num"><?= ($i + 1) ?></span>
                                <span class="curr-text"><?= esc($item) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-msg"><i class="fas fa-info-circle"></i> Kurikulum belum tersedia.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rombongan Belajar -->
        <div class="card-section" id="kelas">
            <div class="card-section-header">
                <i class="fas fa-users"></i>
                <h2>Rombongan Belajar (Kelas)</h2>
            </div>
            <div class="card-section-body">
                <?php if (empty($classes)): ?>
                    <p class="empty-msg"><i class="fas fa-info-circle"></i> Belum ada kelas tersedia untuk kursus ini.</p>
                <?php else: ?>
                    <?php foreach ($classes as $cls): ?>
                        <?php
                            $cap = $cls->capacity ?: 30;
                            $enrolled = (int)($cls->total_enrolled ?? 0);
                            $approved = (int)($cls->approved_count ?? 0);
                            $pct = $cap > 0 ? round(($enrolled / $cap) * 100) : 0;
                        ?>
                        <div class="class-card">
                            <div class="cc-top">
                                <span class="cc-name"><?= esc($cls->name) ?></span>
                                <span class="cc-badge <?= $cls->status ?>"><?= ucfirst($cls->status) ?></span>
                            </div>
                            <?php if ($cls->description): ?>
                                <p class="cc-desc"><?= esc($cls->description) ?></p>
                            <?php endif; ?>
                            <div class="cc-meta">
                                <?php if ($cls->schedule): ?>
                                    <span><i class="fas fa-calendar"></i> <?= esc($cls->schedule) ?></span>
                                <?php endif; ?>
                                <span><i class="fas fa-file-alt"></i> <?= $cls->content_count ?? 0 ?> Materi</span>
                            </div>
                            <div class="cc-progress">
                                <div class="cc-bar"><div class="cc-fill" style="width:<?= $pct ?>%"></div></div>
                                <span class="cc-pct"><?= $pct ?>%</span>
                            </div>
                            <div class="cc-capacity">
                                <i class="fas fa-users" style="margin-right:.3rem;"></i>
                                <?= $enrolled ?>/<?= $cap ?> peserta &middot; <?= $approved ?> disetujui
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="sidebar-sticky">
        <div class="card-section">
            <div class="card-section-header">
                <i class="fas fa-info-circle"></i>
                <h2>Informasi Kursus</h2>
            </div>
            <div class="card-section-body">
                <ul class="side-info-list">
                    <li><span class="si-label">Kategori</span><span class="si-value"><?= esc($course->category_name ?? '-') ?></span></li>
                    <li><span class="si-label">Pertemuan</span><span class="si-value"><?= $course->meetings_count ? esc($course->meetings_count) . 'x' : '-' ?></span></li>
                    <li><span class="si-label">Durasi</span><span class="si-value"><?= $course->meeting_duration ? esc($course->meeting_duration) . ' menit' : '-' ?></span></li>
                    <li><span class="si-label">Total Kelas</span><span class="si-value"><?= count($classes) ?></span></li>
                    <li>
                        <span class="si-label">Total Peserta</span>
                        <span class="si-value"><?php $t=0; foreach($classes as $c) $t+=(int)($c->total_enrolled??0); echo $t; ?></span>
                    </li>
                </ul>
                <a href="/" class="side-btn side-btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="/login" class="side-btn side-btn-primary"><i class="fas fa-sign-in-alt"></i> Masuk untuk Daftar</a>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-inner">
        <span>&copy; <?= date('Y') ?> <?= esc($settings['site_name'] ?? 'Om Abonk') ?></span>
        <div class="footer-links">
            <a href="/">Beranda</a>
            <a href="/login">Masuk</a>
            <a href="/register">Daftar</a>
        </div>
    </div>
</footer>

</body>
</html>
