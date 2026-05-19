<?php /** @var array $news */
$img = news_image_url($news['image_path']);
?>
<section class="page-header page-header--news">
    <div class="container">
        <p class="page-header__crumbs"><a href="<?= url('news') ?>">← Все новости</a></p>
        <time class="page-header__date" datetime="<?= e(date('Y-m-d', (int)$news['published_at'])) ?>">
            <?= e(format_date((int)$news['published_at'])) ?>
        </time>
        <h1><?= e($news['title']) ?></h1>
    </div>
</section>

<section class="section">
    <div class="container news-show">
        <?php if ($img): ?>
            <div class="news-show__media" style="--news-bg:url('<?= e($img) ?>')">
                <img src="<?= e($img) ?>" alt="<?= e($news['title']) ?>">
            </div>
        <?php endif; ?>
        <div class="news-show__content prose">
            <?= render_content($news['content']) ?>
        </div>
        <div class="news-show__footer">
            <a class="btn btn--ghost" href="<?= url('news') ?>">← Ко всем новостям</a>
        </div>
    </div>
</section>
