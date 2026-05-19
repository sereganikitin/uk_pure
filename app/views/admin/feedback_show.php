<?php /** @var array $row; array $statuses */ ?>
<div class="admin-page-head">
    <h1>Заявка #<?= (int)$row['id'] ?></h1>
    <a class="admin-link admin-link--ghost" href="<?= url('admin/feedback') ?>">← К списку</a>
</div>

<div class="admin-card">
    <dl class="admin-dl">
        <dt>Имя</dt><dd><?= e($row['name']) ?></dd>
        <?php if (!empty($row['phone'])): ?><dt>Телефон</dt><dd><a href="tel:<?= e(preg_replace('/[^+\d]/', '', $row['phone'])) ?>"><?= e($row['phone']) ?></a></dd><?php endif; ?>
        <?php if (!empty($row['email'])): ?><dt>Email</dt><dd><a href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></dd><?php endif; ?>
        <dt>Сообщение</dt><dd class="admin-dl__pre"><?= nl2br(e($row['message'])) ?></dd>
        <dt>IP-адрес</dt><dd class="admin-table__muted"><?= e($row['ip'] ?? '') ?></dd>
        <dt>User-Agent</dt><dd class="admin-table__muted admin-table__nowrap"><?= e($row['user_agent'] ?? '') ?></dd>
        <dt>Получено</dt><dd><?= e(date('d.m.Y H:i', (int)$row['created_at'])) ?></dd>
    </dl>

    <form method="post" action="<?= url('admin/feedback/' . (int)$row['id'] . '/status') ?>" class="admin-form admin-form--inline">
        <?= Csrf::field() ?>
        <label class="admin-form__label" for="fs_status">Статус заявки</label>
        <select id="fs_status" name="status" class="admin-form__input">
            <?php foreach ($statuses as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= $row['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="admin-btn admin-btn--primary">Сохранить статус</button>
    </form>
</div>
