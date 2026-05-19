<?php /** @var string $content */ ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= e($description ?? 'Управляющая компания Pure Home — Москва') ?>">
    <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
<?php require APP . '/views/partials/header.php'; ?>

<main class="main">
<?= $content ?>
</main>

<?php require APP . '/views/partials/footer.php'; ?>
</body>
</html>
