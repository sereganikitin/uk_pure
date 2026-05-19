<?php /** @var array $rows */ ?>
<div class="admin-page-head">
    <h1>Новости</h1>
    <a class="admin-btn admin-btn--primary" href="<?= url('admin/news/create') ?>">+ Добавить новость</a>
</div>

<?php if (!$rows): ?>
    <p class="admin-muted">Пока новостей нет. Добавьте первую.</p>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Заголовок</th>
                <th>Slug</th>
                <th>Дата публикации</th>
                <th>Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $n): ?>
                <tr>
                    <td>
                        <a class="admin-link" href="<?= url('admin/news/' . (int)$n['id'] . '/edit') ?>"><?= e($n['title']) ?></a>
                    </td>
                    <td class="admin-table__muted"><?= e($n['slug']) ?></td>
                    <td class="admin-table__muted"><?= e(date('d.m.Y', (int)$n['published_at'])) ?></td>
                    <td><?= $n['is_published'] ? '<span class="admin-pill admin-pill--ok">опубликована</span>' : '<span class="admin-pill">черновик</span>' ?></td>
                    <td class="admin-table__actions">
                        <a class="admin-link" href="<?= url('news/' . $n['slug']) ?>" target="_blank">Открыть</a>
                        <form action="<?= url('admin/news/' . (int)$n['id'] . '/delete') ?>" method="post" class="admin-inline-form" onsubmit="return confirm('Удалить новость безвозвратно?');">
                            <?= Csrf::field() ?>
                            <button type="submit" class="admin-link admin-link--danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
