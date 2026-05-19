<?php
$cats = DB::fetchAll(
    'SELECT c.id, c.name
     FROM document_categories c
     WHERE EXISTS (SELECT 1 FROM documents d WHERE d.category_id = c.id)
     ORDER BY c.sort_order, c.name'
);
$docsByCat = [];
$uncategorized = [];
foreach (DB::fetchAll(
    'SELECT id, category_id, title, file_path, file_size, mime_type
     FROM documents
     ORDER BY sort_order, id DESC'
) as $d) {
    if ($d['category_id']) {
        $docsByCat[(int)$d['category_id']][] = $d;
    } else {
        $uncategorized[] = $d;
    }
}
if ($uncategorized) {
    $cats[] = ['id' => 0, 'name' => 'Прочее'];
    $docsByCat[0] = $uncategorized;
}

$tabsId = 'docs-tab';
?>
<section class="page-header">
    <div class="container">
        <h1>Документы и раскрытие информации</h1>
        <p class="page-header__lead">
            Общие документы управляющей компании и материалы по&nbsp;каждому корпусу
            комплекса «Зорге 9А». Выберите вкладку.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!$cats): ?>
            <p class="prose">Документы появятся здесь после загрузки через админку.</p>
        <?php else: ?>
            <div class="doc-tabs">
                <style>
                    <?php $sel = []; foreach ($cats as $c): $cid = (int)$c['id']; ?>
                    #<?= e($tabsId) ?>-<?= $cid ?>:checked ~ .doc-tabs__panels .doc-tabs__panel[data-cat="<?= $cid ?>"] { display: block; }
                    #<?= e($tabsId) ?>-<?= $cid ?>:checked ~ .doc-tabs__labels label[for="<?= e($tabsId) ?>-<?= $cid ?>"] {
                        color: var(--color-text);
                        background: #fff;
                        border-color: var(--color-border);
                        border-bottom-color: #fff;
                    }
                    <?php endforeach; ?>
                </style>

                <?php foreach ($cats as $i => $c): ?>
                    <input class="doc-tabs__radio" type="radio" name="<?= e($tabsId) ?>"
                           id="<?= e($tabsId) ?>-<?= (int)$c['id'] ?>"
                           <?= $i === 0 ? 'checked' : '' ?>>
                <?php endforeach; ?>

                <nav class="doc-tabs__labels" role="tablist">
                    <?php foreach ($cats as $c): ?>
                        <label class="doc-tabs__label" for="<?= e($tabsId) ?>-<?= (int)$c['id'] ?>">
                            <?= e($c['name']) ?>
                            <span class="doc-tabs__count"><?= count($docsByCat[(int)$c['id']] ?? []) ?></span>
                        </label>
                    <?php endforeach; ?>
                </nav>

                <div class="doc-tabs__panels">
                    <?php foreach ($cats as $c): ?>
                        <section class="doc-tabs__panel" data-cat="<?= (int)$c['id'] ?>" aria-labelledby="<?= e($tabsId) ?>-<?= (int)$c['id'] ?>">
                            <?php $docs = $docsByCat[(int)$c['id']] ?? []; ?>
                            <?php if (!$docs): ?>
                                <p class="doc-tabs__empty">В этой категории пока нет документов.</p>
                            <?php else: ?>
                                <ul class="doc-list">
                                    <?php foreach ($docs as $d):
                                        $href = url('uploads/documents/' . rawurlencode((string)$d['file_path']));
                                    ?>
                                        <li class="doc-item">
                                            <a class="doc-item__title" href="<?= e($href) ?>" target="_blank" rel="noopener">
                                                <?= e($d['title']) ?>
                                            </a>
                                            <a class="doc-item__download" href="<?= e($href) ?>" download
                                               aria-label="Скачать «<?= e($d['title']) ?>»" title="Скачать">
                                                <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path fill="currentColor" d="M12 3a1 1 0 0 1 1 1v9.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-5 5a1 1 0 0 1-1.414 0l-5-5a1 1 0 1 1 1.414-1.414L11 13.586V4a1 1 0 0 1 1-1Zm-7 15a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1Z"/>
                                                </svg>
                                                <span class="doc-item__download-text">Скачать</span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
