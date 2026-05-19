<?php /** @var array $cats; array $docs */ ?>
<div class="admin-page-head">
    <h1>Документы</h1>
    <a class="admin-btn admin-btn--primary" href="<?= url('admin/documents/create') ?>">+ Добавить документ</a>
</div>

<section class="admin-section">
    <h2>Категории</h2>
    <form method="post" action="<?= url('admin/documents/categories') ?>" class="admin-form admin-form--inline-table">
        <?= Csrf::field() ?>
        <table class="admin-table">
            <thead>
                <tr><th>Название</th><th style="width:120px">Порядок</th><th style="width:80px">Удалить</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cats as $c): ?>
                    <tr>
                        <td><input type="text" class="admin-form__input" name="rename[<?= (int)$c['id'] ?>]" value="<?= e($c['name']) ?>" maxlength="120"></td>
                        <td><input type="number" class="admin-form__input" name="order[<?= (int)$c['id'] ?>]" value="<?= (int)$c['sort_order'] ?>"></td>
                        <td><label class="admin-form__check"><input type="checkbox" name="delete[]" value="<?= (int)$c['id'] ?>"><span></span></label></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><input type="text" class="admin-form__input" name="new_name" placeholder="Новая категория…" maxlength="120"></td>
                    <td><input type="number" class="admin-form__input" name="new_order" value="100"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="admin-form__actions">
            <button type="submit" class="admin-btn">Сохранить категории</button>
        </div>
    </form>
</section>

<section class="admin-section">
    <h2>Документы</h2>
    <?php if (!$docs): ?>
        <p class="admin-muted">Документов ещё нет.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Файл</th>
                    <th>Размер</th>
                    <th style="width:80px">Порядок</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docs as $d): ?>
                    <tr>
                        <td><a class="admin-link" href="<?= url('admin/documents/' . (int)$d['id'] . '/edit') ?>"><?= e($d['title']) ?></a></td>
                        <td class="admin-table__muted"><?= e($d['category_name'] ?? '—') ?></td>
                        <td class="admin-table__muted">
                            <a href="<?= url('uploads/documents/' . rawurlencode((string)$d['file_path'])) ?>" target="_blank"><?= e($d['file_path']) ?></a>
                        </td>
                        <td class="admin-table__muted"><?= e(admin_format_size((int)$d['file_size'])) ?></td>
                        <td class="admin-table__muted"><?= (int)$d['sort_order'] ?></td>
                        <td class="admin-table__actions">
                            <form action="<?= url('admin/documents/' . (int)$d['id'] . '/delete') ?>" method="post" class="admin-inline-form" onsubmit="return confirm('Удалить документ?');">
                                <?= Csrf::field() ?>
                                <button type="submit" class="admin-link admin-link--danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
