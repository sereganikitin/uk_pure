<?php /** @var array|null $row; array $cats; array $errors */
$row = $row ?? null;
$cur = static fn(string $key, $default = '') => e((string)($_POST[$key] ?? ($row[$key] ?? $default)));
?>
<div class="admin-page-head">
    <h1><?= $row ? 'Редактирование документа' : 'Новый документ' ?></h1>
    <a class="admin-link admin-link--ghost" href="<?= url('admin/documents') ?>">← К списку</a>
</div>

<form method="post" action="<?= e($_SERVER['REQUEST_URI']) ?>" enctype="multipart/form-data" class="admin-form">
    <?= Csrf::field() ?>

    <div class="admin-form__row">
        <label class="admin-form__label" for="d_title">Название *</label>
        <input id="d_title" name="title" type="text" class="admin-form__input<?= isset($errors['title']) ? ' is-error' : '' ?>"
               value="<?= $cur('title') ?>" required maxlength="200">
        <?php if (isset($errors['title'])): ?><div class="admin-form__error"><?= e($errors['title']) ?></div><?php endif; ?>
    </div>

    <div class="admin-form__grid-2">
        <div class="admin-form__row">
            <label class="admin-form__label" for="d_category">Категория</label>
            <select id="d_category" name="category_id" class="admin-form__input">
                <option value="">— Без категории —</option>
                <?php foreach ($cats as $c):
                    $selected = (string)($row['category_id'] ?? '') === (string)$c['id'];
                ?>
                    <option value="<?= (int)$c['id'] ?>" <?= $selected ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-form__row">
            <label class="admin-form__label" for="d_sort">Порядок (меньше — выше)</label>
            <input id="d_sort" name="sort_order" type="number" class="admin-form__input" value="<?= e((string)($row['sort_order'] ?? 100)) ?>">
        </div>
    </div>

    <div class="admin-form__row">
        <label class="admin-form__label">Файл <?= $row ? '' : '*' ?></label>
        <?php if ($row && !empty($row['file_path'])): ?>
            <div class="admin-muted">
                Текущий файл: <a href="<?= url('uploads/documents/' . rawurlencode((string)$row['file_path'])) ?>" target="_blank"><?= e($row['file_path']) ?></a>
                · <?= e(admin_format_size((int)$row['file_size'])) ?>
            </div>
        <?php endif; ?>
        <input type="file" name="file" class="admin-form__file<?= isset($errors['file']) ? ' is-error' : '' ?>">
        <?php if (isset($errors['file'])): ?><div class="admin-form__error"><?= e($errors['file']) ?></div><?php endif; ?>
        <div class="admin-form__hint">PDF, DOC, DOCX, XLS, XLSX, ZIP, TXT, XML, JPG, PNG · до 50 МБ.</div>
    </div>

    <div class="admin-form__actions">
        <button type="submit" class="admin-btn admin-btn--primary">Сохранить</button>
        <a class="admin-btn admin-btn--ghost" href="<?= url('admin/documents') ?>">Отмена</a>
    </div>
</form>
