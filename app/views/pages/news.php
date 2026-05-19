<?php
$rows = DB::fetchAll(
    'SELECT id, slug, title, excerpt, image_path, published_at
     FROM news
     WHERE is_published = 1
     ORDER BY published_at DESC, id DESC'
);
?>
<section class="page-header">
    <div class="container">
        <h1>Новости</h1>
        <p class="page-header__lead">Новости управляющей компании Pure Home Comfort.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!$rows): ?>
            <p class="prose">Пока новостей нет. Загляните позже.</p>
        <?php else: ?>
            <div class="news-list">
                <?php foreach ($rows as $r):
                    $img = news_image_url($r['image_path']);
                ?>
                    <article class="news-card">
                        <a class="news-card__media<?= $img ? ' news-card__media--blur' : '' ?>"
                           href="<?= url('news/' . $r['slug']) ?>"
                           <?= $img ? 'style="--news-bg:url(\'' . e($img) . '\')"' : '' ?>>
                            <?php if ($img): ?>
                                <img src="<?= e($img) ?>" alt="<?= e($r['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="news-card__placeholder" aria-hidden="true"></div>
                            <?php endif; ?>
                        </a>
                        <div class="news-card__body">
                            <time class="news-card__date" datetime="<?= e(date('Y-m-d', (int)$r['published_at'])) ?>">
                                <?= e(format_date((int)$r['published_at'])) ?>
                            </time>
                            <h2 class="news-card__title">
                                <a href="<?= url('news/' . $r['slug']) ?>"><?= e($r['title']) ?></a>
                            </h2>
                            <?php if (!empty($r['excerpt'])): ?>
                                <p class="news-card__excerpt"><?= e($r['excerpt']) ?></p>
                            <?php endif; ?>
                            <a class="news-card__more" href="<?= url('news/' . $r['slug']) ?>">Читать целиком →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
