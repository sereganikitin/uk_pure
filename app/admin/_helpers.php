<?php
declare(strict_types=1);

/** Рендер админ-шаблона с обёрткой admin/layout. */
function admin_layout(string $view, array $data = []): void
{
    $content = view('admin/' . $view, $data);
    extract($data, EXTR_SKIP);
    require APP . '/views/admin/layout.php';
}

/** Установить flash-сообщение для следующего запроса. */
function admin_flash(string $type, string $text): void
{
    $_SESSION['admin_flash'][] = ['type' => $type, 'text' => $text];
}

/** Достать и очистить все flash-сообщения. */
function admin_flashes(): array
{
    $msgs = $_SESSION['admin_flash'] ?? [];
    unset($_SESSION['admin_flash']);
    return $msgs;
}

function admin_old(string $key, $default = ''): string
{
    $val = $_SESSION['admin_old'][$key] ?? $default;
    return is_string($val) ? $val : (string)$default;
}

function admin_errors(): array { return $_SESSION['admin_errors'] ?? []; }

function admin_set_old(array $data): void { $_SESSION['admin_old'] = $data; }
function admin_set_errors(array $errors): void { $_SESSION['admin_errors'] = $errors; }
function admin_clear_old_errors(): void { unset($_SESSION['admin_old'], $_SESSION['admin_errors']); }

/** Транслит русского текста в латиницу для slug-ов. */
function admin_translit(string $s): string
{
    static $map = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i',
        'й'=>'i','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t',
        'у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'',
        'э'=>'e','ю'=>'yu','я'=>'ya',
    ];
    $s = mb_strtolower($s);
    $s = strtr($s, $map);
    $s = preg_replace('/[^a-z0-9\-]+/', '-', $s);
    $s = trim((string)$s, '-');
    return $s;
}

/** Генерирует slug из заголовка с проверкой уникальности в таблице (поле slug). */
function admin_make_slug(string $title, string $table, ?int $excludeId = null): string
{
    $base = admin_translit($title);
    if ($base === '') {
        $base = 'item';
    }
    $base = mb_substr($base, 0, 80);
    $slug = $base;
    $i = 2;
    while (true) {
        $sql = "SELECT id FROM {$table} WHERE slug = ?" . ($excludeId ? ' AND id != ?' : '');
        $params = $excludeId ? [$slug, $excludeId] : [$slug];
        $existing = DB::fetchColumn($sql, $params);
        if ($existing === null || $existing === false) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
        if ($i > 200) {
            return $base . '-' . bin2hex(random_bytes(3));
        }
    }
}

function admin_format_size(int $bytes): string
{
    if ($bytes < 1024)            return $bytes . ' Б';
    if ($bytes < 1024 * 1024)     return round($bytes / 1024, 1) . ' КБ';
    if ($bytes < 1024 * 1024 * 1024) return round($bytes / (1024 * 1024), 1) . ' МБ';
    return round($bytes / (1024 * 1024 * 1024), 1) . ' ГБ';
}
