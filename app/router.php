<?php
declare(strict_types=1);

final class Router
{
    /** @var array<string, array<string, callable|string>> */
    private static array $routes = ['GET' => [], 'POST' => []];

    public static function get(string $path, $handler): void
    {
        self::$routes['GET'][$path] = $handler;
    }

    public static function post(string $path, $handler): void
    {
        self::$routes['POST'][$path] = $handler;
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'HEAD') {
            $method = 'GET';
        }
        $path = current_path();
        $path = '/' . trim($path, '/');
        if ($path === '/') {
            $path = '/';
        }

        if (!isset(self::$routes[$method])) {
            self::methodNotAllowed();
            return;
        }

        if (isset(self::$routes[$method][$path])) {
            self::call(self::$routes[$method][$path], []);
            return;
        }

        foreach (self::$routes[$method] as $pattern => $handler) {
            if (strpos($pattern, '{') === false) {
                continue;
            }
            $regex = '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern) . '$#';
            if (preg_match($regex, $path, $m)) {
                $params = [];
                foreach ($m as $k => $v) {
                    if (is_string($k)) {
                        $params[$k] = $v;
                    }
                }
                self::call($handler, $params);
                return;
            }
        }

        self::notFound();
    }

    public static function notFound(): void
    {
        http_response_code(404);
        layout('pages/404', ['title' => 'Страница не найдена']);
    }

    public static function methodNotAllowed(): void
    {
        http_response_code(405);
        echo 'Method Not Allowed';
    }

    private static function call($handler, array $params): void
    {
        if (is_callable($handler)) {
            $handler(...array_values($params));
            return;
        }
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$file, $function] = explode('@', $handler, 2);
            // Поддерживаем подпапки: 'admin/news@admin_news_list' → controllers/admin/news.php
            $path = APP . '/controllers/' . $file . '.php';
            if (!is_file($path)) {
                $path = APP . '/' . $file . '.php';
            }
            require_once $path;
            $function(...array_values($params));
            return;
        }
        throw new RuntimeException('Invalid route handler');
    }
}
