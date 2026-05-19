<?php /** @var string $content */
$user = Auth::user();
$flashes = $_SESSION['admin_flash'] ?? [];
unset($_SESSION['admin_flash']);
$cur = current_path();
$nav = [
    ['/admin/dashboard', 'Главная'],
    ['/admin/news',      'Новости'],
    ['/admin/documents', 'Документы'],
    ['/admin/feedback',  'Заявки'],
    ['/admin/settings',  'Настройки'],
];
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Админка') ?> · Pure Home</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="<?= asset('img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=2">
</head>
<body class="admin-body<?= $user ? '' : ' admin-body--auth' ?>">

<?php if ($user): ?>
    <header class="admin-header">
        <div class="admin-header__inner">
            <a href="<?= url('admin/dashboard') ?>" class="admin-header__brand">Pure Home — админка</a>
            <nav class="admin-nav">
                <?php foreach ($nav as [$path, $label]):
                    $active = ($path === '/admin/dashboard' && $cur === '/admin/dashboard')
                            || ($path !== '/admin/dashboard' && strpos($cur, $path) === 0);
                ?>
                    <a class="admin-nav__link<?= $active ? ' is-active' : '' ?>" href="<?= url(ltrim($path, '/')) ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="admin-header__user">
                <a href="<?= url() ?>" class="admin-link admin-link--ghost" target="_blank">← на сайт</a>
                <span class="admin-user">@<?= e($user['username']) ?></span>
                <form action="<?= url('admin/logout') ?>" method="post" class="admin-logout">
                    <?= Csrf::field() ?>
                    <button type="submit" class="admin-link">Выйти</button>
                </form>
            </div>
        </div>
    </header>
<?php endif; ?>

<main class="admin-main">
    <div class="admin-container">
        <?php foreach ($flashes as $f): ?>
            <div class="admin-flash admin-flash--<?= e($f['type']) ?>"><?= e($f['text']) ?></div>
        <?php endforeach; ?>
        <?= $content ?>
    </div>
</main>
</body>
</html>
