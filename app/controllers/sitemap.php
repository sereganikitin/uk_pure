<?php
declare(strict_types=1);

/**
 * Динамический /sitemap.xml — статические страницы + все опубликованные новости.
 */
function sitemap_xml(): void
{
    $scheme = !empty($_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'purehome.ru';
    $origin = $scheme . '://' . $host;

    $static = [
        ['path' => '/',          'priority' => '1.0', 'changefreq' => 'weekly'],
        ['path' => '/about',     'priority' => '0.7', 'changefreq' => 'monthly'],
        ['path' => '/services',  'priority' => '0.8', 'changefreq' => 'monthly'],
        ['path' => '/documents', 'priority' => '0.7', 'changefreq' => 'weekly'],
        ['path' => '/news',      'priority' => '0.8', 'changefreq' => 'daily'],
        ['path' => '/contacts',  'priority' => '0.7', 'changefreq' => 'monthly'],
    ];

    $news = DB::fetchAll(
        'SELECT slug, updated_at, published_at
         FROM news
         WHERE is_published = 1
         ORDER BY published_at DESC'
    );

    header('Content-Type: application/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    $today = date('Y-m-d');
    foreach ($static as $row) {
        echo "  <url>\n";
        echo "    <loc>" . e($origin . url(ltrim($row['path'], '/'))) . "</loc>\n";
        echo "    <lastmod>{$today}</lastmod>\n";
        echo "    <changefreq>{$row['changefreq']}</changefreq>\n";
        echo "    <priority>{$row['priority']}</priority>\n";
        echo "  </url>\n";
    }
    foreach ($news as $n) {
        $lastmod = date('Y-m-d', max((int)$n['updated_at'], (int)$n['published_at']));
        echo "  <url>\n";
        echo "    <loc>" . e($origin . url('news/' . $n['slug'])) . "</loc>\n";
        echo "    <lastmod>{$lastmod}</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.6</priority>\n";
        echo "  </url>\n";
    }

    echo '</urlset>' . "\n";
}
