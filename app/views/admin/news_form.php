<?php /** @var array|null $row; array $errors */
$row = $row ?? null;
$f = static fn(string $key, $default = '') => e((string)($_POST[$key] ?? ($row[$key] ?? $default)));
$publishedAtVal = '';
if ($row && !empty($row['published_at'])) {
    $publishedAtVal = date('Y-m-d', (int)$row['published_at']);
}
$publishedAtVal = (string)($_POST['published_at'] ?? $publishedAtVal);
$isPublished = $row ? (int)$row['is_published'] : 1;
if (isset($_POST['is_published']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $isPublished = !empty($_POST['is_published']) ? 1 : 0;
}
$imageName = $row['image_path'] ?? null;
$imageUrl  = $imageName ? news_image_url($imageName) : null;
?>
<div class="admin-page-head">
    <h1><?= $row ? 'Редактирование новости' : 'Новая новость' ?></h1>
    <a class="admin-link admin-link--ghost" href="<?= url('admin/news') ?>">← К списку</a>
</div>

<form method="post" action="<?= e($_SERVER['REQUEST_URI']) ?>" enctype="multipart/form-data" class="admin-form">
    <?= Csrf::field() ?>

    <div class="admin-form__row">
        <label class="admin-form__label" for="n_title">Заголовок *</label>
        <input id="n_title" name="title" type="text" class="admin-form__input<?= isset($errors['title']) ? ' is-error' : '' ?>"
               value="<?= $f('title') ?>" required maxlength="200">
        <?php if (isset($errors['title'])): ?><div class="admin-form__error"><?= e($errors['title']) ?></div><?php endif; ?>
        <div class="admin-form__hint">Slug (часть URL) будет сгенерирован автоматически из заголовка.</div>
    </div>

    <div class="admin-form__grid-2">
        <div class="admin-form__row">
            <label class="admin-form__label" for="n_published_at">Дата публикации</label>
            <input id="n_published_at" name="published_at" type="date" class="admin-form__input<?= isset($errors['published_at']) ? ' is-error' : '' ?>"
                   value="<?= e($publishedAtVal) ?>">
            <?php if (isset($errors['published_at'])): ?><div class="admin-form__error"><?= e($errors['published_at']) ?></div><?php endif; ?>
        </div>
        <div class="admin-form__row">
            <label class="admin-form__label">&nbsp;</label>
            <label class="admin-form__check">
                <input type="checkbox" name="is_published" value="1" <?= $isPublished ? 'checked' : '' ?>>
                <span>Опубликовать сразу</span>
            </label>
        </div>
    </div>

    <div class="admin-form__row">
        <label class="admin-form__label" for="n_excerpt">Краткое описание (анонс)</label>
        <textarea id="n_excerpt" name="excerpt" class="admin-form__textarea" rows="2" maxlength="500"><?= $f('excerpt') ?></textarea>
        <div class="admin-form__hint">Несколько предложений для карточки в списке новостей. До 500 символов.</div>
    </div>

    <div class="admin-form__row">
        <label class="admin-form__label" for="n_content">Полный текст (HTML разрешён) *</label>
        <textarea id="n_content" name="content" class="admin-form__textarea<?= isset($errors['content']) ? ' is-error' : '' ?>"
                  rows="14" required><?= $f('content') ?></textarea>
        <?php if (isset($errors['content'])): ?><div class="admin-form__error"><?= e($errors['content']) ?></div><?php endif; ?>
        <div class="admin-form__hint">
            Можно вставлять HTML: &lt;p&gt;, &lt;ul&gt;/&lt;li&gt;, &lt;a&gt;, &lt;strong&gt;, &lt;br&gt;.
            Для ссылок на свои документы используйте путь <code>/uploads/documents/имя.pdf</code>.
        </div>
    </div>

    <div class="admin-form__row">
        <label class="admin-form__label">Картинка</label>
        <?php if ($imageUrl): ?>
            <div class="admin-image-preview">
                <img src="<?= e($imageUrl) ?>" alt="">
                <label class="admin-form__check">
                    <input type="checkbox" name="delete_image" value="1">
                    <span>Удалить текущую картинку</span>
                </label>
            </div>
        <?php elseif ($imageName): ?>
            <div class="admin-muted">Файл «<?= e($imageName) ?>» не найден в /uploads/news/. Можно загрузить заново.</div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="admin-form__file">
        <div class="admin-form__hint">JPG / PNG / WebP, до 8 МБ. Большие картинки автоматически уменьшаются до 1600 px.</div>
    </div>

    <div class="admin-form__actions">
        <button type="submit" class="admin-btn admin-btn--primary">Сохранить</button>
        <a class="admin-btn admin-btn--ghost" href="<?= url('admin/news') ?>">Отмена</a>
    </div>
</form>
