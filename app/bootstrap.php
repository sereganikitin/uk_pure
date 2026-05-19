<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Moscow');

define('ROOT',    dirname(__DIR__));
define('APP',     __DIR__);
define('DATA',    ROOT . '/data');
define('UPLOADS', ROOT . '/uploads');

// Автоопределение базового URL-префикса.
// В корне сайта: BASE_PATH = '' (сайт открывается как /).
// В подпапке /_new/: BASE_PATH = '/_new'.
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$basePath   = str_replace('\\', '/', dirname($scriptName));
$basePath   = rtrim($basePath, '/');
if ($basePath === '' || $basePath === '/') {
    $basePath = '';
}
define('BASE_PATH', $basePath);

require APP . '/config.php';

ini_set('error_log', DATA . '/logs/php-error.log');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => !empty($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_name(SESSION_NAME);
session_start();

require APP . '/helpers.php';
require APP . '/db.php';
require APP . '/csrf.php';
require APP . '/spamguard.php';
require APP . '/mailer.php';
require APP . '/auth.php';
require APP . '/router.php';
require APP . '/routes.php';
require APP . '/admin/routes.php';
