<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - <?= esc($settings['site_name'] ?? 'Om Abonk') ?></title>
    <meta name="description" content="Artikel, tutorial, dan berita terbaru seputar IT dan pemrograman dari Om Abonk">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= esc(base_url('/artikel')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= esc($title) ?> - <?= esc($settings['site_name'] ?? 'Om Abonk') ?>">
    <meta property="og:description" content="Artikel, tutorial, dan berita terbaru seputar IT dan pemrograman dari Om Abonk">
    <meta property="og:image" content="<?= esc(base_url('/images/og-default.svg')) ?>">
    <meta property="og:url" content="<?= esc(base_url('/artikel')) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; color: #1a1a2e; background: #f8f9ff; }

        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: .8rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 30px rgba(0,0,0,.06);
        }
        .nav-brand { display: flex; align-items: center; gap: .5rem; font-weight: 800; font-size: 1.2rem; color: #4f46e5; text-decoration: none; }
        .nav-brand i { font-size: 1.4rem; }
        .nav-links { display: flex; align-items: center; gap: .3rem; }
        .nav-links a { padding: .5rem 1rem; border-radius: .5rem; text-decoration: none; font-weight: 500; font-size: .9rem; color: #555; transition: all .2s; }
        .nav-links a:hover { color: #4f46e5; background: #f0f0ff; }
        .btn-nav { padding: .5rem 1.2rem !important; border-radius: .5rem !important; font-weight: 600 !important; color: #fff !important; background: #4f46e5 !important; }
        .btn-nav:hover { background: #4338ca !important; }

        .page-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 7rem 2rem 3rem; text-align: center; color: #fff;
        }
        .page-header h1 { font-size: 2.5rem; font-weight: 800; margin-bottom: .5rem; }
        .page-header p { font-size: 1.1rem; opacity: .9; }

        .container { max-width: 1100px; margin: 0 auto; padding: 2rem; }

        .category-filter { display: flex; gap: .5rem; justify-content: center; margin-bottom: 2.5rem; flex-wrap: wrap; }
        .category-filter a {
            padding: .5rem 1.2rem; border-radius: 50px;
            text-decoration: none; font-weight: 500; font-size: .9rem;
            background: #fff; color: #555; border: 1.5px solid #e5e7eb;
            transition: all .2s;
        }
        .category-filter a:hover { border-color: #4f46e5; color: #4f46e5; }
        .category-filter a.active { background: #4f46e5; color: #fff; border-color: #4f46e5; }

        .articles-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        .article-card {
            background: #fff; border-radius: 1rem; overflow: hidden;
            border: 1px solid #f0f0f5; transition: all .3s;
            display: block; text-decoration: none; color: inherit;
        }
        .article-card:hover { transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,.08); text-decoration: none; }
        .article-thumb {
            height: 200px; background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .article-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .article-thumb i { font-size: 3rem; color: rgba(255,255,255,.3); }
        .article-thumb .article-badge {
            position: absolute; top: .8rem; left: .8rem;
            background: rgba(255,255,255,.9); color: #4f46e5;
            padding: .25rem .7rem; border-radius: 50px;
            font-size: .75rem; font-weight: 600;
        }
        .article-body { padding: 1.2rem 1.5rem 1.5rem; }
        .article-body h3 {
            font-size: 1.05rem; font-weight: 700; margin-bottom: .5rem; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .article-body p {
            font-size: .85rem; color: #6b7280; line-height: 1.5; margin-bottom: 1rem;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .article-meta { display: flex; align-items: center; gap: 1rem; font-size: .8rem; color: #9ca3af; }
        .article-meta i { margin-right: .3rem; }
        .article-meta span { display: flex; align-items: center; }

        .empty-state {
            text-align: center; padding: 4rem 2rem; color: #9ca3af;
            grid-column: 1 / -1;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; color: #d1d5db; }

        .footer {
            background: #1a1a2e; color: #9ca3af; padding: 2rem;
            text-align: center; margin-top: 3rem;
        }
        .footer a { color: #818cf8; text-decoration: none; }
        .footer a:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            .page-header h1 { font-size: 1.8rem; }
            .articles-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }

        .fade-up { opacity: 0; transform: translateY(20px); transition: all .5s ease-out; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/" class="nav-brand">
        <i class="fas fa-graduation-cap"></i> Om Abonk
    </a>
    <div class="nav-links">
        <a href="/">Beranda</a>
        <a href="/artikel" style="color:#4f46e5;">Artikel</a>
        <a href="/login" class="btn-nav">Masuk</a>
    </div>
</nav>

<div class="page-header">
    <h1>Artikel & Tutorial</h1>
    <p>Temukan artikel, tutorial, dan berita terbaru seputar IT</p>
</div>

<div class="container">
    <div class="category-filter">
        <a href="/artikel" class="<?= empty($category) ? 'active' : '' ?>">Semua</a>
        <a href="/artikel?kategori=berita" class="<?= $category === 'berita' ? 'active' : '' ?>">Berita</a>
        <a href="/artikel?kategori=tutorial" class="<?= $category === 'tutorial' ? 'active' : '' ?>">Tutorial</a>
        <a href="/artikel?kategori=artikel" class="<?= $category === 'artikel' ? 'active' : '' ?>">Artikel</a>
    </div>

    <div class="articles-grid">
        <?php if (empty($articles)): ?>
            <div class="empty-state fade-up">
                <i class="fas fa-newspaper"></i>
                <p>Belum ada artikel dipublikasikan.</p>
            </div>
        <?php else: ?>
            <?php foreach ($articles as $a): ?>
                <a href="/artikel/<?= esc($a->slug) ?>" class="article-card fade-up">
                    <div class="article-thumb">
                        <?php if (!empty($a->thumbnail)): ?>
                            <img src="/uploads/thumbnails/<?= esc($a->thumbnail) ?>" alt="<?= esc($a->title) ?>">
                        <?php else: ?>
                            <i class="fas fa-newspaper"></i>
                        <?php endif; ?>
                        <span class="article-badge"><?= ucfirst(esc($a->category)) ?></span>
                    </div>
                    <div class="article-body">
                        <h3><?= esc($a->title) ?></h3>
                        <p><?= esc($a->excerpt ?? strip_tags(substr($a->body, 0, 150))) ?>...</p>
                        <div class="article-meta">
                            <span><i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($a->published_at ?? $a->created_at)) ?></span>
                            <span><i class="fas fa-eye"></i> <?= number_format($a->views ?? 0) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> <?= esc($settings['site_name'] ?? 'Om Abonk') ?>. <a href="/">Kembali ke Beranda</a></p>
</footer>

<script>
window.addEventListener('scroll', () => {
    document.querySelector('.navbar').style.boxShadow = window.scrollY > 10 ? '0 2px 30px rgba(0,0,0,.1)' : '0 2px 30px rgba(0,0,0,.06)';
});
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

</body>
</html>
