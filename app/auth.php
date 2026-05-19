<?php
declare(strict_types=1);

final class Auth
{
    public static function check(): bool
    {
        if (empty($_SESSION['admin_user_id'])) {
            return false;
        }
        if (!empty($_SESSION['admin_last_seen']) &&
            (time() - (int)$_SESSION['admin_last_seen']) > ADMIN_SESSION_TTL_SECONDS) {
            self::logout();
            return false;
        }
        $_SESSION['admin_last_seen'] = time();
        return true;
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;
        $row = DB::fetch('SELECT id, username, role FROM admin_users WHERE id = ? LIMIT 1', [$_SESSION['admin_user_id']]);
        return $row ?: null;
    }

    /**
     * Возвращает true при успехе. При неудаче — false и заносит попытку в admin_login_log.
     */
    public static function attempt(string $username, string $password, string $ip): bool
    {
        $user = DB::fetch('SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1', [$username]);
        $ok = $user && password_verify($password, $user['password_hash']);
        DB::execute(
            'INSERT INTO admin_login_log(username, ip, success, created_at) VALUES(?, ?, ?, ?)',
            [$username, $ip, $ok ? 1 : 0, time()]
        );
        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['admin_user_id']   = (int)$user['id'];
            $_SESSION['admin_username']  = $user['username'];
            $_SESSION['admin_last_seen'] = time();
            DB::execute('UPDATE admin_users SET last_login_at = ? WHERE id = ?', [time(), $user['id']]);
        }
        return $ok;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_user_id'], $_SESSION['admin_username'], $_SESSION['admin_last_seen']);
        session_regenerate_id(true);
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            $_SESSION['admin_after_login'] = current_path();
            redirect(url('admin/login'));
        }
    }

    /** В системе нет ни одного админа — первый запуск, нужен setup. */
    public static function needsSetup(): bool
    {
        return (int)DB::fetchColumn('SELECT COUNT(*) FROM admin_users') === 0;
    }

    /** Подсчёт неудачных попыток с этого IP в последнее окно. */
    public static function failedAttempts(string $ip): int
    {
        return (int)DB::fetchColumn(
            'SELECT COUNT(*) FROM admin_login_log WHERE ip = ? AND success = 0 AND created_at > ?',
            [$ip, time() - ADMIN_LOGIN_RATE_WINDOW]
        );
    }
}
