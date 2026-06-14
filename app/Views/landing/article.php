<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($article->title) ?> - <?= esc($settings['site_name'] ?? 'Om Abonk') ?></title>
    <meta name="description" content="<?= esc($article->description ?? mb_substr(strip_tags($article->body), 0, 160)) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= esc(base_url('/artikel/' . $article->slug)) ?>">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= esc($article->title) ?>">
    <meta property="og:description" content="<?= esc($article->description ?? mb_substr(strip_tags($article->body), 0, 160)) ?>">
    <meta property="og:image" content="<?= esc(base_url(!empty($article->thumbnail) ? '/uploads/thumbnails/' . $article->thumbnail : '/images/og-default.svg')) ?>">
    <meta property="og:url" content="<?= esc(base_url('/artikel/' . $article->slug)) ?>">
    <meta property="og:site_name" content="<?= esc($settings['site_name'] ?? 'Om Abonk') ?>">
    <meta property="article:published_time" content="<?= esc($article->published_at) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($article->title) ?>">
    <meta name="twitter:description" content="<?= esc($article->description ?? mb_substr(strip_tags($article->body), 0, 160)) ?>">
    <meta name="twitter:image" content="<?= esc(base_url(!empty($article->thumbnail) ? '/uploads/thumbnails/' . $article->thumbnail : '/images/og-default.svg')) ?>">
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

        .article-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 7rem 2rem 3rem; color: #fff; text-align: center;
        }
        .article-hero .breadcrumb { font-size: .85rem; margin-bottom: 1rem; opacity: .8; }
        .article-hero .breadcrumb a { color: #fff; text-decoration: none; }
        .article-hero .breadcrumb a:hover { text-decoration: underline; }
        .article-hero h1 { font-size: 2.2rem; font-weight: 800; max-width: 700px; margin: 0 auto 1rem; line-height: 1.3; }
        .article-hero .meta { font-size: .9rem; opacity: .85; display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; }
        .article-hero .meta span { display: flex; align-items: center; gap: .3rem; }

        .article-container {
            max-width: 800px; margin: -2rem auto 3rem;
            background: #fff; border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,.06);
            overflow: hidden;
        }
        .article-thumb-full { width: 100%; max-height: 400px; object-fit: cover; }
        .article-content { padding: 2rem 2.5rem 3rem; }
        .article-content h2 { font-size: 1.5rem; font-weight: 700; margin: 2rem 0 1rem; color: #1a1a2e; }
        .article-content h3 { font-size: 1.25rem; font-weight: 600; margin: 1.5rem 0 .8rem; }
        .article-content p { font-size: 1rem; line-height: 1.8; color: #374151; margin-bottom: 1rem; }
        .article-content img { max-width: 100%; height: auto; border-radius: .5rem; margin: 1rem 0; }
        .article-content blockquote {
            border-left: 4px solid #4f46e5; background: #f0f0ff;
            padding: 1rem 1.5rem; margin: 1.5rem 0; border-radius: 0 .5rem .5rem 0;
            font-style: italic; color: #4f46e5;
        }
        .article-content pre {
            background: #1a1a2e; color: #e2e8f0; padding: 1.2rem;
            border-radius: .5rem; overflow-x: auto; margin: 1.5rem 0;
            font-size: .9rem; line-height: 1.6;
        }
        .article-content code {
            background: #f0f0ff; color: #4f46e5; padding: .15rem .4rem;
            border-radius: .25rem; font-size: .9em;
        }
        .article-content pre code { background: none; color: inherit; padding: 0; }
        .article-content ul, .article-content ol { margin: 1rem 0; padding-left: 1.5rem; }
        .article-content li { margin-bottom: .4rem; line-height: 1.7; color: #374151; }
        .article-content a { color: #4f46e5; text-decoration: underline; }
        .article-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
        .article-content th, .article-content td { border: 1px solid #e5e7eb; padding: .6rem .8rem; text-align: left; }
        .article-content th { background: #f8f9ff; font-weight: 600; }

        .article-tags { padding: 0 2.5rem 2rem; display: flex; gap: .5rem; flex-wrap: wrap; }
        .article-tag {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .3rem .8rem; border-radius: 50px;
            background: #f0f0ff; color: #4f46e5; font-size: .8rem; font-weight: 500;
            text-decoration: none;
        }
        .article-tag:hover { background: #4f46e5; color: #fff; }

        .share-section { padding: 1.5rem 2.5rem; border-top: 1px solid #f0f0f5; }
        .share-section h4 { font-size: .9rem; font-weight: 600; margin-bottom: .8rem; }
        .share-buttons { display: flex; gap: .5rem; }
        .share-btn {
            padding: .5rem 1rem; border-radius: .5rem;
            text-decoration: none; font-size: .85rem; font-weight: 500;
            color: #fff; transition: all .2s;
        }
        .share-btn:hover { transform: translateY(-2px); }
        .share-btn.facebook { background: #1877f2; }
        .share-btn.twitter { background: #1da1f2; }
        .share-btn.whatsapp { background: #25d366; }
        .share-btn.copy { background: #6b7280; cursor: pointer; border: none; }

        .related-section { padding: 2rem 2.5rem; background: #f8f9ff; }
        .related-section h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; }
        .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
        .related-card {
            background: #fff; border-radius: .75rem; overflow: hidden;
            border: 1px solid #f0f0f5; text-decoration: none; color: inherit; transition: all .3s;
        }
        .related-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,.06); text-decoration: none; }
        .related-card img { width: 100%; height: 120px; object-fit: cover; }
        .related-card .rc-body { padding: .8rem 1rem; }
        .related-card .rc-body h4 { font-size: .9rem; font-weight: 600; margin-bottom: .3rem; line-height: 1.3; }
        .related-card .rc-body small { color: #9ca3af; font-size: .75rem; }

        .back-link { display: block; text-align: center; padding: 2rem; }
        .back-link a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .back-link a:hover { text-decoration: underline; }

        .footer { background: #1a1a2e; color: #9ca3af; padding: 2rem; text-align: center; }
        .footer a { color: #818cf8; text-decoration: none; }

        @media (max-width: 768px) {
            .article-hero h1 { font-size: 1.6rem; }
            .article-content { padding: 1.5rem; }
            .article-tags, .share-section, .related-section { padding-left: 1.5rem; padding-right: 1.5rem; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="/" class="nav-brand">
        <i class="fas fa-graduation-cap"></i> Om Abonk
    </a>
    <div class="nav-links">
        <a href="/">Beranda</a>
        <a href="/artikel">Artikel</a>
        <a href="/login" class="btn-nav">Masuk</a>
    </div>
</nav>

<div class="article-hero">
    <div class="breadcrumb">
        <a href="/">Beranda</a> / <a href="/artikel">Artikel</a> / <?= esc($article->title) ?>
    </div>
    <h1><?= esc($article->title) ?></h1>
    <div class="meta">
        <span><i class="fas fa-folder"></i> <?= ucfirst(esc($article->category)) ?></span>
        <span><i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($article->published_at ?? $article->created_at)) ?></span>
        <span><i class="fas fa-eye"></i> <?= number_format($article->views ?? 0) ?> dilihat</span>
    </div>
</div>

<div class="article-container">
    <?php if (!empty($article->thumbnail)): ?>
        <img src="/uploads/thumbnails/<?= esc($article->thumbnail) ?>" alt="<?= esc($article->title) ?>" class="article-thumb-full">
    <?php endif; ?>

    <div class="article-content">
        <?= $article->body ?>
    </div>

    <div class="article-tags">
        <a href="/artikel?kategori=<?= esc($article->category) ?>" class="article-tag">
            <i class="fas fa-tag"></i> <?= ucfirst(esc($article->category)) ?>
        </a>
    </div>

    <div class="share-section">
        <h4><i class="fas fa-share-alt"></i> Bagikan Artikel Ini</h4>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(base_url('/artikel/' . $article->slug)) ?>" target="_blank" class="share-btn facebook">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(base_url('/artikel/' . $article->slug)) ?>&text=<?= urlencode($article->title) ?>" target="_blank" class="share-btn twitter">
                <i class="fab fa-twitter"></i> Twitter
            </a>
            <a href="https://wa.me/?text=<?= urlencode($article->title . ' ' . base_url('/artikel/' . $article->slug)) ?>" target="_blank" class="share-btn whatsapp">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <button onclick="copyLink()" class="share-btn copy">
                <i class="fas fa-link"></i> Salin Link
            </button>
        </div>
    </div>

    <?php if (!empty($related)): ?>
    <div class="related-section">
        <h3>Artikel Terkait</h3>
        <div class="related-grid">
            <?php foreach ($related as $r): ?>
                <a href="/artikel/<?= esc($r->slug) ?>" class="related-card">
                    <?php if (!empty($r->thumbnail)): ?>
                        <img src="/uploads/thumbnails/<?= esc($r->thumbnail) ?>" alt="<?= esc($r->title) ?>">
                    <?php else: ?>
                        <div style="height:120px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;"><i class="fas fa-newspaper" style="color:rgba(255,255,255,.3);font-size:2rem;"></i></div>
                    <?php endif; ?>
                    <div class="rc-body">
                        <h4><?= esc($r->title) ?></h4>
                        <small><?= date('d M Y', strtotime($r->published_at ?? $r->created_at)) ?></small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="back-link">
    <a href="/artikel"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel</a>
</div>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> <?= esc($settings['site_name'] ?? 'Om Abonk') ?>. <a href="/">Kembali ke Beranda</a></p>
</footer>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link berhasil disalin!');
    });
}
</script>

</body>
</html>
