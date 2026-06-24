<?php /** Реквизиты управляющей компании (из config.php → REQUISITES). */ ?>
<div class="requisites">
    <h2 class="requisites__title">Реквизиты компании</h2>
    <dl class="requisites__list">
        <?php foreach (REQUISITES as $label => $value): ?>
            <div class="requisites__row">
                <dt class="requisites__label"><?= e($label) ?></dt>
                <dd class="requisites__value"><?= e($value) ?></dd>
            </div>
        <?php endforeach; ?>
    </dl>
</div>
