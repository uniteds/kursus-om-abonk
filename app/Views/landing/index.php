<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($settings['site_name'] ?? 'Om Abonk') ?> - <?= esc($settings['site_description'] ?? 'Platform Belajar IT') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; color: #1a1a2e; overflow-x: hidden; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            transition: all .3s ease;
        }
        .navbar.scrolled {
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 30px rgba(0,0,0,.08);
            padding: .7rem 2rem;
        }
        .nav-brand {
            display: flex; align-items: center; gap: .6rem;
            font-weight: 800; font-size: 1.25rem; color: #fff;
            text-decoration: none;
        }
        .navbar.scrolled .nav-brand { color: #4f46e5; }
        .nav-brand i { font-size: 1.5rem; }
        .nav-links { display: flex; align-items: center; gap: .5rem; }
        .nav-links a {
            padding: .5rem 1rem; border-radius: .5rem;
            text-decoration: none; font-weight: 500; font-size: .9rem;
            color: rgba(255,255,255,.85); transition: all .2s;
        }
        .nav-links a:hover { color: #fff; background: rgba(255,255,255,.15); }
        .navbar.scrolled .nav-links a { color: #555; }
        .navbar.scrolled .nav-links a:hover { color: #4f46e5; background: #f0f0ff; }
        .btn-nav-login {
            padding: .5rem 1.2rem !important; border-radius: .5rem !important;
            font-weight: 600 !important; color: #fff !important;
            background: rgba(255,255,255,.15) !important;
            border: 1.5px solid rgba(255,255,255,.4);
        }
        .btn-nav-register {
            padding: .55rem 1.4rem !important; border-radius: .5rem !important;
            font-weight: 600 !important; color: #fff !important;
            background: #4f46e5 !important; border: none !important;
            box-shadow: 0 4px 15px rgba(79,70,229,.4);
        }
        .btn-nav-register:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.5); }
        .navbar.scrolled .btn-nav-login { color: #4f46e5 !important; background: #f0f0ff !important; border-color: #d4d4ff !important; }
        .navbar.scrolled .btn-nav-register { color: #fff !important; background: #4f46e5 !important; }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 8rem 2rem 4rem;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: drift 30s linear infinite;
        }
        @keyframes drift { to { transform: translate(50px, 50px); } }
        .hero-content { position: relative; z-index: 2; max-width: 800px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            background: rgba(255,255,255,.15); backdrop-filter: blur(10px);
            padding: .5rem 1.2rem; border-radius: 50px;
            font-size: .85rem; font-weight: 500; color: #fff;
            margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,.2);
        }
        .hero-badge i { font-size: .7rem; }
        .hero h1 {
            font-size: 3.5rem; font-weight: 800; color: #fff;
            line-height: 1.15; margin-bottom: 1.2rem;
            text-shadow: 0 2px 40px rgba(0,0,0,.1);
        }
        .hero h1 span { color: #fbbf24; }
        .hero p {
            font-size: 1.2rem; color: rgba(255,255,255,.9);
            max-width: 600px; margin: 0 auto 2rem; line-height: 1.7;
        }
        .hero-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .9rem 2rem; border-radius: .75rem;
            font-size: 1rem; font-weight: 600; text-decoration: none;
            background: #fff; color: #4f46e5;
            box-shadow: 0 8px 30px rgba(0,0,0,.15);
            transition: all .3s;
        }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,.2); }
        .btn-hero-secondary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .9rem 2rem; border-radius: .75rem;
            font-size: 1rem; font-weight: 600; text-decoration: none;
            background: transparent; color: #fff;
            border: 2px solid rgba(255,255,255,.4);
            transition: all .3s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,.1); border-color: #fff; }
        .hero-illustration {
            margin-top: 3rem;
            display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;
        }
        .hero-stat {
            background: rgba(255,255,255,.12); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.15);
            padding: 1rem 1.5rem; border-radius: 1rem;
            text-align: center; color: #fff; min-width: 130px;
        }
        .hero-stat .num { font-size: 1.6rem; font-weight: 800; }
        .hero-stat .label { font-size: .8rem; opacity: .85; margin-top: .2rem; }

        /* ===== WAVE DIVIDER ===== */
        .wave-divider { margin-top: -2px; display: block; }

        /* ===== SECTIONS ===== */
        section { padding: 5rem 2rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 3rem; }
        .section-header h2 { font-size: 2.2rem; font-weight: 800; color: #1a1a2e; margin-bottom: .6rem; }
        .section-header p { font-size: 1.05rem; color: #6b7280; max-width: 550px; margin: 0 auto; }
        .section-header .accent-line {
            width: 60px; height: 4px; border-radius: 2px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            margin: .8rem auto 0;
        }

        /* ===== FEATURES ===== */
        .features { background: #f8f9ff; }
        .features-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .feature-card {
            background: #fff; border-radius: 1rem; padding: 2rem 1.5rem;
            text-align: center; transition: all .3s;
            border: 1px solid #f0f0f5;
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(79,70,229,.1); }
        .feature-icon {
            width: 64px; height: 64px; border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin: 0 auto 1.2rem;
        }
        .fi-blue   { background: #eef2ff; color: #4f46e5; }
        .fi-purple { background: #f5f3ff; color: #7c3aed; }
        .fi-green  { background: #ecfdf5; color: #059669; }
        .fi-orange { background: #fff7ed; color: #ea580c; }
        .feature-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .5rem; }
        .feature-card p { font-size: .9rem; color: #6b7280; line-height: 1.6; }

        /* ===== COURSES ===== */
        .courses-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        .course-card {
            background: #fff; border-radius: 1rem; overflow: hidden;
            border: 1px solid #f0f0f5; transition: all .3s;
            display: block;
        }
        .course-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,.08); text-decoration: none; }
        .course-thumb {
            height: 180px; background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .course-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .course-thumb i { font-size: 3rem; color: rgba(255,255,255,.3); }
        .course-thumb .course-badge {
            position: absolute; top: .8rem; left: .8rem;
            background: rgba(255,255,255,.9); color: #4f46e5;
            padding: .25rem .7rem; border-radius: 50px;
            font-size: .75rem; font-weight: 600;
        }
        .course-body { padding: 1.2rem 1.5rem 1.5rem; }
        .course-body h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: .5rem; line-height: 1.4; }
        .course-body p { font-size: .85rem; color: #6b7280; line-height: 1.5; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .course-meta { display: flex; align-items: center; gap: 1rem; font-size: .8rem; color: #9ca3af; }
        .course-meta i { margin-right: .3rem; }
        .course-meta span { display: flex; align-items: center; }
        .empty-courses {
            text-align: center; padding: 3rem; color: #9ca3af;
            grid-column: 1 / -1;
        }
        .empty-courses i { font-size: 3rem; margin-bottom: 1rem; display: block; color: #d1d5db; }

        /* ===== WHY US ===== */
        .why-us { background: #f8f9ff; }
        .why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .why-card {
            display: flex; align-items: flex-start; gap: 1rem;
            padding: 1.5rem; background: #fff; border-radius: 1rem;
            border: 1px solid #f0f0f5; transition: all .3s;
        }
        .why-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.05); }
        .why-num {
            flex-shrink: 0; width: 48px; height: 48px; border-radius: .75rem;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; font-weight: 800; color: #fff;
        }
        .why-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: .3rem; }
        .why-card p { font-size: .85rem; color: #6b7280; line-height: 1.5; }

        /* ===== CTA ===== */
        .cta-section {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            text-align: center; border-radius: 1.5rem; margin: 0 2rem;
            padding: 4rem 2rem; color: #fff;
        }
        .cta-section h2 { font-size: 2rem; font-weight: 800; margin-bottom: .8rem; }
        .cta-section p { font-size: 1.05rem; opacity: .9; max-width: 500px; margin: 0 auto 2rem; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .9rem 2.5rem; border-radius: .75rem;
            font-size: 1rem; font-weight: 700; text-decoration: none;
            background: #fff; color: #4f46e5;
            box-shadow: 0 8px 30px rgba(0,0,0,.2);
            transition: all .3s;
        }
        .btn-cta:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,.3); }

        /* ===== FOOTER ===== */
        .footer {
            background: #1a1a2e; color: #9ca3af; padding: 3rem 2rem 1.5rem;
            margin-top: 5rem;
        }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2rem;
        }
        .footer-brand { display: flex; align-items: center; gap: .5rem; font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: .8rem; }
        .footer-brand i { font-size: 1.3rem; color: #818cf8; }
        .footer p { font-size: .9rem; line-height: 1.6; }
        .footer h4 { font-size: .9rem; font-weight: 600; color: #fff; margin-bottom: .8rem; text-transform: uppercase; letter-spacing: .5px; }
        .footer a { color: #9ca3af; text-decoration: none; font-size: .9rem; transition: color .2s; }
        .footer a:hover { color: #818cf8; }
        .footer ul { list-style: none; }
        .footer ul li { margin-bottom: .4rem; }
        .footer-bottom {
            max-width: 1100px; margin: 2rem auto 0;
            padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,.08);
            display: flex; justify-content: space-between; align-items: center;
            font-size: .85rem;
        }
        .footer-bottom a { color: #818cf8; }
        .social-links { display: flex; gap: .8rem; }
        .social-links a {
            width: 36px; height: 36px; border-radius: .5rem;
            background: rgba(255,255,255,.08); display: flex;
            align-items: center; justify-content: center;
            color: #9ca3af; transition: all .2s; font-size: .85rem;
        }
        .social-links a:hover { background: #4f46e5; color: #fff; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.2rem; }
            .hero p { font-size: 1rem; }
            .hero-buttons { flex-direction: column; align-items: center; }
            .nav-links { display: none; }
            .footer-inner { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
            .cta-section { margin: 0 1rem; padding: 3rem 1.5rem; }
            .courses-grid { grid-template-columns: 1fr; }
        }

        /* ===== ANIMATIONS ===== */
        .fade-up {
            opacity: 0; transform: translateY(30px);
            transition: all .6s ease-out;
        }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <a href="/" class="nav-brand">
        <i class="fas fa-graduation-cap"></i>
        Om Abonk
    </a>
    <div class="nav-links">
        <a href="#fitur">Fitur</a>
        <a href="#kursus">Kursus</a>
        <a href="#alasan">Mengapa Kami</a>
        <a href="/login" class="btn-nav-login">Masuk</a>
        <a href="/register" class="btn-nav-register">Daftar Gratis</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-circle"></i>
            Platform Belajar #1 untuk Pemula
        </div>
        <h1>Belajar IT Jadi <span>Mudah</span> &amp; <span>Menyenangkan</span></h1>
        <p>Kursus IT untuk pemula hingga mahir. Belajar coding, jaringan, dan desain dengan cara yang seru bersama Om Abonk.</p>
        <div class="hero-buttons">
            <a href="/register" class="btn-hero-primary">
                <i class="fas fa-rocket"></i> Mulai Belajar Sekarang
            </a>
            <a href="#kursus" class="btn-hero-secondary">
                <i class="fas fa-play-circle"></i> Lihat Kursus
            </a>
        </div>
        <div class="hero-illustration">
            <div class="hero-stat">
                <div class="num"><?= esc($courseCount) ?>+</div>
                <div class="label">Kursus Tersedia</div>
            </div>
            <div class="hero-stat">
                <div class="num"><?= esc(count($categories)) ?></div>
                <div class="label">Kategori</div>
            </div>
            <div class="hero-stat">
                <div class="num"><?= esc($userCount) ?>+</div>
                <div class="label">Siswa Aktif</div>
            </div>
        </div>
    </div>
</section>

<!-- WAVE -->
<svg class="wave-divider" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;background:#f8f9ff;">
    <path d="M0,64L60,69.3C120,75,240,85,360,80C480,75,600,53,720,48C840,43,960,53,1080,64C1200,75,1320,85,1380,90.7L1440,96V120H0Z" fill="#f8f9ff"/>
</svg>

<!-- FEATURES -->
<section class="features" id="fitur">
    <div class="container">
        <div class="section-header fade-up">
            <h2>Kenapa Belajar di Om Abonk?</h2>
            <p>Fitur lengkap yang dirancang untuk memudahkan perjalanan belajarmu</p>
            <div class="accent-line"></div>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-up">
                <div class="feature-icon fi-blue"><i class="fas fa-book-open"></i></div>
                <h3>Kursus Terstruktur</h3>
                <p>Materi disusun berjenjang dari dasar hingga lanjutan, cocok untuk pemula sekalipun.</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon fi-purple"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3>Bimbingan Langsung</h3>
                <p>Belajar di kelas dengan jadwal tetap, dapat bimbingan langsung dari pengajar.</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon fi-green"><i class="fas fa-certificate"></i></div>
                <h3>Sertifikat Resmi</h3>
                <p>Dapatkan sertifikat setelah menyelesaikan kursus untuk memperkuat portofolio.</p>
            </div>
            <div class="feature-card fade-up">
                <div class="feature-icon fi-orange"><i class="fas fa-clock"></i></div>
                <h3>Belajar Fleksibel</h3>
                <p>Akses materi kapan saja dan dimana saja sesuai waktu luangmu.</p>
            </div>
        </div>
    </div>
</section>

<!-- COURSES -->
<section class="courses" id="kursus">
    <div class="container">
        <div class="section-header fade-up">
            <h2>Kursus Populer</h2>
            <p>Pilih kursus favoritmu dan mulai perjalanan belajar hari ini</p>
            <div class="accent-line"></div>
        </div>
        <div class="courses-grid">
            <?php if (empty($courses)): ?>
                <div class="empty-courses fade-up">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada kursus tersedia saat ini.<br>Silakan cek kembali nanti!</p>
                </div>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <a href="/course/<?= esc($course->slug) ?>" class="course-card fade-up" style="text-decoration:none; color:inherit;">
                        <div class="course-thumb">
                            <?php if (!empty($course->thumbnail)): ?>
                                <img src="/uploads/thumbnails/<?= esc($course->thumbnail) ?>" alt="<?= esc($course->title) ?>">
                            <?php else: ?>
                                <i class="fas fa-laptop-code"></i>
                            <?php endif; ?>
                            <span class="course-badge"><?= esc($course->category_name ?? 'Kursus') ?></span>
                        </div>
                        <div class="course-body">
                            <h3><?= esc($course->title) ?></h3>
                            <p><?= esc($course->description ?? 'Deskripsi kursus akan segera tersedia.') ?></p>
                            <div class="course-meta">
                                <?php if ($course->meetings_count): ?>
                                    <span><i class="fas fa-calendar-check"></i> <?= esc($course->meetings_count) ?> Pertemuan</span>
                                <?php endif; ?>
                                <span><i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($course->created_at)) ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- WHY US -->
<section class="why-us" id="alasan">
    <div class="container">
        <div class="section-header fade-up">
            <h2>Kenapa Harus Om Abonk?</h2>
            <p>Kami berkomitmen memberikan pengalaman belajar terbaik</p>
            <div class="accent-line"></div>
        </div>
        <div class="why-grid">
            <div class="why-card fade-up">
                <div class="why-num">1</div>
                <div>
                    <h3>Gratis untuk Pemula</h3>
                    <p>Daftar dan jelajahi kursus tanpa biaya. Mulai belajar tanpa hambatan.</p>
                </div>
            </div>
            <div class="why-card fade-up">
                <div class="why-num">2</div>
                <div>
                    <h3>Materi Berkualitas</h3>
                    <p>Setiap materi dirancang oleh praktisi IT berpengalaman dan selalu diperbarui.</p>
                </div>
            </div>
            <div class="why-card fade-up">
                <div class="why-num">3</div>
                <div>
                    <h3>Komunitas Aktif</h3>
                    <p>Terhubung dengan sesama learner dan dapatkan dukungan selama belajar.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="padding: 2rem;">
    <div class="cta-section fade-up">
        <h2>Siap Belajar Hari Ini?</h2>
        <p>Bergabung dengan ribuan siswa lainnya dan mulai perjalanan belajarmu sekarang juga.</p>
        <a href="/register" class="btn-cta">
            <i class="fas fa-user-plus"></i> Daftar Sekarang — Gratis!
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-inner">
        <div>
            <div class="footer-brand">
                <i class="fas fa-graduation-cap"></i> Om Abonk
            </div>
            <p style="white-space:pre-line;"><?= esc($settings['site_footer_about'] ?? '') ?></p>
        </div>
        <div>
            <h4>Navigasi</h4>
            <ul>
                <li><a href="#fitur">Fitur</a></li>
                <li><a href="#kursus">Kursus</a></li>
                <li><a href="#alasan">Mengapa Kami</a></li>
            </ul>
        </div>
        <div>
            <h4>Akun</h4>
            <ul>
                <li><a href="/login">Masuk</a></li>
                <li><a href="/register">Daftar</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?= date('Y') ?> <?= esc($settings['site_name'] ?? 'Om Abonk') ?>. All rights reserved.</span>
        <div class="social-links">
            <a href="#"><i class="fab fa-github"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Fade-up animation on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

</body>
</html>
