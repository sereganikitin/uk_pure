<?php
declare(strict_types=1);

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function verify(?string $token): bool
    {
        $expected = $_SESSION['_csrf'] ?? '';
        return is_string($token) && $expected !== '' && hash_equals($expected, $token);
    }

    public static function requireValid(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!self::verify(is_string($token) ? $token : null)) {
            http_response_code(419);
            exit('CSRF token mismatch');
        }
    }
}
