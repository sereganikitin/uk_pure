<?php
$objects = [
    ['name' => 'Зорге 9',                  'address' => 'ул. Зорге, 9',      'url' => 'https://zorge9.com.ru/',   'image' => 'zorge9.jpg'],
    ['name' => 'Квартал «Серебряный Бор»', 'address' => 'ул. Берзарина, 37', 'url' => 'https://berzarina37.ru/',  'image' => 'berzarina37.jpg'],
    ['name' => 'Толбухина 3',              'address' => 'ул. Толбухина, 3',  'url' => null,                       'image' => 'tolbuhina3.jpg'],
    ['name' => 'Рублёвское шоссе 151',     'address' => 'Рублёвское ш., 151','url' => null,                       'image' => 'rublevskoe151.jpg'],
];
?>
<section class="page-header page-header--image" style="background-image: url('<?= asset('img/pages/lobby.jpg') ?>');">
    <div class="page-header__overlay"></div>
    <div class="container">
        <h1>О компании</h1>
        <p class="page-header__lead">Pure Home Comfort — управляющая компания нового поколения.</p>
    </div>
</section>

<section class="section">
    <div class="container prose">
        <p>
            Мы верим в заботу о&nbsp;домах, а&nbsp;не только о&nbsp;зданиях. Обеспечение безопасности,
            надёжности и&nbsp;соблюдения требований к&nbsp;объектам под нашей опекой — это само
            собой разумеющееся. Главное, что мы&nbsp;осознаём, — это дом наших клиентов, где они
            хотят чувствовать внимание к&nbsp;себе и&nbsp;быть частью заботливого сообщества.
        </p>
        <p>
            Наша компания специализируется на&nbsp;обслуживании комплексов апартаментов
            бизнес-класса в&nbsp;Москве. Делегируйте нам решение повседневных и&nbsp;рутинных задач,
            освободив свой график для более важных событий.
        </p>
    </div>
</section>

<section class="section section--surface">
    <div class="container">
        <h2 class="section__title">Нам доверяют</h2>
        <div class="grid grid--2 objects-grid">
            <?php foreach ($objects as $o):
                $img = asset('img/objects/' . $o['image']);
                $tag = !empty($o['url']) ? 'a' : 'div';
                $attrs = !empty($o['url']) ? ' href="' . e($o['url']) . '" target="_blank" rel="noopener noreferrer"' : '';
            ?>
                <<?= $tag ?> class="object-card<?= empty($o['url']) ? ' object-card--plain' : '' ?>"<?= $attrs ?>>
                    <div class="object-card__media" style="background-image: url('<?= $img ?>');"></div>
                    <div class="object-card__body">
                        <h3 class="object-card__name"><?= e($o['name']) ?></h3>
                        <p class="object-card__addr"><?= e($o['address']) ?></p>
                        <?php if (!empty($o['url'])): ?>
                            <span class="object-card__link">Сайт объекта →</span>
                        <?php endif; ?>
                    </div>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require APP . '/views/partials/cta_app.php'; ?>
