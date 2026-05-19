<?php /** @var array $rows; string $filter; array $counts; array $statuses */ ?>
<div class="admin-page-head">
    <h1>Заявки с формы ОС</h1>
</div>

<div class="admin-tabs">
    <a class="admin-tab<?= $filter === '' ? ' is-active' : '' ?>" href="<?= url('admin/feedback') ?>">
        Все <span class="admin-tab__count"><?= (int)($counts['all'] ?? 0) ?></span>
    </a>
    <?php foreach ($statuses as $key => $label): ?>
        <a class="admin-tab<?= $filter === $key ? ' is-active' : '' ?>" href="<?= url('admin/feedback?status=' . $key) ?>">
            <?= e($label) ?> <span class="admin-tab__count"><?= (int)($counts[$key] ?? 0) ?></span>
        </a>
    <?php endforeach; ?>
</div>

<?php if (!$rows): ?>
    <p class="admin-muted">Обращений с такими параметрами нет.</p>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Контакты</th>
                <th>Дата</th>
                <th>Статус</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int)$r['id'] ?></td>
                    <td><?= e($r['name']) ?></td>
                    <td class="admin-table__muted">
                        <?php if (!empty($r['phone'])): ?><div><?= e($r['phone']) ?></div><?php endif; ?>
                        <?php if (!empty($r['email'])): ?><div><?= e($r['email']) ?></div><?php endif; ?>
                    </td>
                    <td class="admin-table__muted"><?= e(date('d.m.Y H:i', (int)$r['created_at'])) ?></td>
                    <td><span class="admin-pill admin-pill--<?= e($r['status']) ?>"><?= e($statuses[$r['status']] ?? $r['status']) ?></span></td>
                    <td class="admin-table__actions">
                        <a class="admin-link" href="<?= url('admin/feedback/' . (int)$r['id']) ?>">Открыть</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
