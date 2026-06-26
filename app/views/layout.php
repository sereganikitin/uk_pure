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

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window, document,'script','https://mc.yandex.ru/metrika/tag.js', 'ym');

        ym(95634355, 'init', {webvisor:true, clickmap:true, referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/95634355" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</head>
<body>
<?php require APP . '/views/partials/header.php'; ?>

<main class="main">
<?= $content ?>
</main>

<?php require APP . '/views/partials/footer.php'; ?>

<div class="cookie-banner" id="cookie-banner" hidden role="dialog" aria-live="polite"
     aria-label="Уведомление об использовании файлов cookie">
    <p class="cookie-banner__text">
        Мы&nbsp;используем файлы cookie, чтобы сайт работал корректно и&nbsp;удобно.
        Продолжая пользоваться сайтом, вы&nbsp;соглашаетесь с&nbsp;их&nbsp;использованием и&nbsp;с&nbsp;нашей
        <a href="<?= url('privacy') ?>">политикой обработки персональных данных</a>.
    </p>
    <button type="button" class="btn btn--primary cookie-banner__btn" id="cookie-accept">Принять</button>
</div>
<script>
(function () {
    var KEY = 'ph_cookie_consent';
    var banner = document.getElementById('cookie-banner');
    var btn = document.getElementById('cookie-accept');
    if (!banner || !btn) return;
    var stored;
    try { stored = localStorage.getItem(KEY); } catch (e) { stored = '1'; }
    if (stored === '1') return;
    banner.hidden = false;
    btn.addEventListener('click', function () {
        try { localStorage.setItem(KEY, '1'); } catch (e) {}
        banner.hidden = true;
    });
})();
</script>
</body>
</html>
