<?php
$principles = [
    ['Компетентность', 'Команда — профессионалы своего дела: квалифицированное обучение и строгий отбор. Обслуживаем инженерные системы, ухаживаем за ландшафтом, поддерживаем чистоту в жилых комплексах.'],
    ['Безопасность',   'Полностью контролируем доступ на территорию дома. Безопасность резидентов — на высочайшем уровне. В наших дворах родители могут быть спокойны за детей.'],
    ['Открытость',     'Результаты работы максимально открыты для жителей. Ежегодно публикуем подробный отчёт о деятельности и делимся планами на следующий год.'],
    ['Комфорт',        'Высокий уровень комфорта — комплекс действий: от сохранения чистоты парадных до организации праздника для всего двора. Всегда на связи, всегда становимся лучше.'],
];
$cards = [
    ['Концепция',           'Бережное отношение к людям, вещам и природе. Пунктуальность и перфекционизм. Безупречный сервис в любое время.'],
    ['Зона ответственности','Сохраняем облик зданий и интерьеров. Заботимся о безопасности резидентов и имущества. Решаем бытовые задачи и административные поручения.'],
    ['Окружающая среда',    'Ремонт и поддержание чистоты, исправность инженерных коммуникаций, уход за газонами, парком, беседками, бассейнами и фонтанами.'],
];
?>
<section class="hero hero--bg" style="background-image: url('<?= asset('img/pages/hero.jpg') ?>');">
    <div class="hero__overlay"></div>
    <div class="container hero__inner">
        <h1 class="hero__title">С заботой о вашем комфорте и&nbsp;безопасности</h1>
        <p class="hero__lead">
            <strong>Pure Home Comfort</strong> — управляющая компания нового поколения.
            Создаём комфорт и высокое качество жизни в домах премиального уровня.
        </p>
        <div class="hero__actions">
            <a href="<?= url('services') ?>" class="btn btn--primary">Перейти к каталогу услуг</a>
            <a href="<?= url('contacts') ?>" class="btn btn--ghost btn--ghost-light">Связаться с нами</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="two-col">
            <div class="two-col__heading">
                <h2>Управляющая компания нового поколения</h2>
            </div>
            <div class="two-col__body prose">
                <p>Мы создаём комфорт и высокое качество жизни в домах от <strong>ST&nbsp;MICHAEL</strong> и убеждены: каждодневный уют, порядок и дружелюбную среду в доме может обеспечить тот, кто его спроектировал и построил — а значит, вложил душу.</p>
            </div>
        </div>

        <div class="principles">
            <?php foreach ($principles as [$title, $text]): ?>
                <div class="principle">
                    <h3 class="principle__title"><?= e($title) ?></h3>
                    <p class="principle__text"><?= e($text) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--surface">
    <div class="container">
        <div class="grid grid--3">
            <?php foreach ($cards as [$title, $text]): ?>
                <article class="card card--feature">
                    <h3 class="card__title"><?= e($title) ?></h3>
                    <p class="card__text"><?= e($text) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-band" style="background-image: url('<?= asset('img/pages/park.jpg') ?>');">
    <div class="cta-band__overlay"></div>
    <div class="container cta-band__inner">
        <p>
            Предоставляем сервис премиального уровня в жилых комплексах. Ценим время клиентов,
            оказываем безграничную поддержку, находим лучшие решения.
        </p>
        <a href="<?= url('services') ?>" class="btn btn--primary">Перейти к услугам</a>
    </div>
</section>

<?php require APP . '/views/partials/cta_app.php'; ?>
