<?php
declare(strict_types=1);

function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function redirect(string $url, int $code = 302): void
{
    header('Location: ' . $url, true, $code);
    exit;
}

function view(string $name, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require APP . '/views/' . $name . '.php';
    return (string)ob_get_clean();
}

function layout(string $contentView, array $data = [], string $layoutName = 'layout'): void
{
    $content = view($contentView, $data);
    extract($data, EXTR_SKIP);
    require APP . '/views/' . $layoutName . '.php';
}

function url(string $path = ''): string
{
    return BASE_PATH . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    $file = ROOT . '/public/' . ltrim($path, '/');
    $version = is_file($file) ? (string)filemtime($file) : '1';
    return BASE_PATH . '/public/' . ltrim($path, '/') . '?v=' . $version;
}

function current_path(): string
{
    $uri  = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH);
    $path = $path === null || $path === '' ? '/' : $path;

    if (BASE_PATH !== '' && strpos($path, BASE_PATH) === 0) {
        $path = substr($path, strlen(BASE_PATH));
        if ($path === '' || $path === false) {
            $path = '/';
        }
    }
    return $path;
}

function is_active(string $path, bool $prefix = false): bool
{
    $current = current_path();
    if ($prefix) {
        return $path === '/' ? $current === '/' : str_starts_with($current, $path);
    }
    return $current === $path;
}

function nav_class(string $path, bool $prefix = false): string
{
    return is_active($path, $prefix) ? ' nav__link--active' : '';
}

function client_ip(): string
{
    return (string)($_SERVER['REMOTE_ADDR'] ?? '');
}

function format_phone(string $phone): string
{
    return trim($phone);
}

/**
 * Преобразует unix timestamp в человеко-читаемую русскую дату ("24 февраля 2026").
 */
function format_date(?int $ts): string
{
    if (!$ts) return '';
    $months = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
                   'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
    return (int)date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Подставляет {{base}} плейсхолдеры в сохранённом HTML на текущий BASE_PATH.
 * Сам HTML не эскейпится — он считается доверенным (заведён через админку или сидер).
 */
function render_content(?string $html): string
{
    if ($html === null || $html === '') return '';
    return str_replace('{{base}}', BASE_PATH, $html);
}

/**
 * Возвращает URL картинки новости, если файл реально существует в /uploads/news/.
 */
function news_image_url(?string $imagePath): ?string
{
    if (!$imagePath) return null;
    $clean = basename($imagePath);
    $file  = UPLOADS . '/news/' . $clean;
    if (!is_file($file)) return null;
    return BASE_PATH . '/uploads/news/' . $clean;
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
