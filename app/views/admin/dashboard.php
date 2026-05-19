<?php /** @var array $stats, $latestNews, $latestFeedback */ ?>
<h1>Панель управления</h1>

<div class="admin-stats">
    <a class="admin-stat" href="<?= url('admin/news') ?>">
        <div class="admin-stat__num"><?= (int)$stats['news_published'] ?>/<?= (int)$stats['news_total'] ?></div>
        <div class="admin-stat__label">Опубликовано / всего новостей</div>
    </a>
    <a class="admin-stat" href="<?= url('admin/documents') ?>">
        <div class="admin-stat__num"><?= (int)$stats['docs_total'] ?></div>
        <div class="admin-stat__label">Документов</div>
    </a>
    <a class="admin-stat<?= $stats['feedback_new'] > 0 ? ' admin-stat--alert' : '' ?>" href="<?= url('admin/feedback') ?>">
        <div class="admin-stat__num"><?= (int)$stats['feedback_new'] ?></div>
        <div class="admin-stat__label">Новых заявок</div>
    </a>
    <a class="admin-stat" href="<?= url('admin/feedback') ?>">
        <div class="admin-stat__num"><?= (int)$stats['feedback_total'] ?></div>
        <div class="admin-stat__label">Всего заявок</div>
    </a>
</div>

<section class="admin-section">
    <div class="admin-section__head">
        <h2>Последние новости</h2>
        <a class="admin-link" href="<?= url('admin/news/create') ?>">+ Добавить новость</a>
    </div>
    <?php if ($latestNews): ?>
        <table class="admin-table">
            <thead><tr><th>Заголовок</th><th>Дата</th><th>Статус</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($latestNews as $n): ?>
                    <tr>
                        <td><?= e($n['title']) ?></td>
                        <td class="admin-table__muted"><?= e(format_date((int)$n['published_at'])) ?></td>
                        <td><?= $n['is_published'] ? '<span class="admin-pill admin-pill--ok">опубликована</span>' : '<span class="admin-pill">черновик</span>' ?></td>
                        <td class="admin-table__actions">
                            <a class="admin-link" href="<?= url('admin/news/' . (int)$n['id'] . '/edit') ?>">Редактировать</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="admin-muted">Новостей пока нет.</p>
    <?php endif; ?>
</section>

<section class="admin-section">
    <div class="admin-section__head">
        <h2>Последние заявки с формы</h2>
        <a class="admin-link" href="<?= url('admin/feedback') ?>">Все заявки →</a>
    </div>
    <?php if ($latestFeedback): ?>
        <table class="admin-table">
            <thead><tr><th>#</th><th>Имя</th><th>Дата</th><th>Статус</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($latestFeedback as $f): ?>
                    <tr>
                        <td>#<?= (int)$f['id'] ?></td>
                        <td><?= e($f['name']) ?></td>
                        <td class="admin-table__muted"><?= e(date('d.m.Y H:i', (int)$f['created_at'])) ?></td>
                        <td><span class="admin-pill admin-pill--<?= e($f['status']) ?>"><?= e($f['status']) ?></span></td>
                        <td class="admin-table__actions"><a class="admin-link" href="<?= url('admin/feedback/' . (int)$f['id']) ?>">Открыть</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="admin-muted">Обращений с сайта пока нет.</p>
    <?php endif; ?>
</section>
