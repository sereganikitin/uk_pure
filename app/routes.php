<?php
declare(strict_types=1);

Router::get('/',          function () { layout('pages/home',      ['title' => SITE_NAME . ' — управляющая компания в Москве']); });
Router::get('/about',     function () { layout('pages/about',     ['title' => 'О компании · ' . SITE_NAME]); });
Router::get('/services',  function () { layout('pages/services',  ['title' => 'Услуги · ' . SITE_NAME]); });
Router::get('/documents', function () { layout('pages/documents', ['title' => 'Документы · ' . SITE_NAME]); });
Router::get('/news',      function () { layout('pages/news',      ['title' => 'Новости · ' . SITE_NAME]); });
Router::get('/contacts',  function () { layout('pages/contacts',  ['title' => 'Контакты · ' . SITE_NAME]); });
Router::get('/privacy',   function () { layout('pages/privacy',   ['title' => 'Политика обработки персональных данных · ' . SITE_NAME, 'description' => 'Политика в отношении обработки персональных данных ' . LEGAL_OPERATOR]); });

Router::get('/news/{slug}', function (string $slug) {
    $news = DB::fetch(
        'SELECT * FROM news WHERE slug = ? AND is_published = 1 LIMIT 1',
        [$slug]
    );
    if (!$news) {
        Router::notFound();
        return;
    }
    layout('pages/news_show', [
        'title'       => $news['title'] . ' · ' . SITE_NAME,
        'description' => $news['excerpt'] ?: $news['title'],
        'news'        => $news,
    ]);
});

Router::post('/contacts/send', 'feedback@feedback_submit');

Router::get('/sitemap.xml', 'sitemap@sitemap_xml');
