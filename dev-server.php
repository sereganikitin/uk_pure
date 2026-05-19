<?php
/**
 * Router для встроенного PHP-сервера (php -S localhost:8000 dev-server.php).
 * На проде НЕ используется — там работает Apache + .htaccess из корня.
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

if (preg_match('#^/(app|data)(/|$)#', $path)) {
    http_response_code(403);
    echo '403 Forbidden';
    return true;
}

$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
