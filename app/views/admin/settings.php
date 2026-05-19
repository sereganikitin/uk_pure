<?php /** @var array $fields; array $values; array $recipients */ ?>
<div class="admin-page-head">
    <h1>Настройки</h1>
</div>

<form method="post" action="<?= url('admin/settings') ?>" class="admin-form">
    <?= Csrf::field() ?>

    <section class="admin-section">
        <h2>Контакты и ссылки</h2>
        <?php foreach ($fields as $key => $label): ?>
            <div class="admin-form__row">
                <label class="admin-form__label" for="s_<?= e($key) ?>"><?= e($label) ?></label>
                <input id="s_<?= e($key) ?>" name="<?= e($key) ?>" type="text"
                       class="admin-form__input" value="<?= e($values[$key] ?? '') ?>" maxlength="500">
            </div>
        <?php endforeach; ?>
    </section>

    <section class="admin-section">
        <h2>Получатели заявок с формы ОС</h2>
        <div class="admin-form__row">
            <label class="admin-form__label" for="s_recipients">Email-адреса (по одному в строке, либо через запятую)</label>
            <textarea id="s_recipients" name="feedback_recipients" class="admin-form__textarea" rows="4"><?= e(implode("\n", $recipients)) ?></textarea>
            <div class="admin-form__hint">На эти адреса будут уходить уведомления о новых обращениях. Заявки также сохраняются в БД и доступны в разделе «Заявки».</div>
        </div>
    </section>

    <div class="admin-form__actions">
        <button type="submit" class="admin-btn admin-btn--primary">Сохранить настройки</button>
    </div>
</form>
