<?php
declare(strict_types=1);

final class DB
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::ensureDirs();
            self::$pdo = new PDO('sqlite:' . DB_PATH);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
            self::$pdo->exec('PRAGMA journal_mode = WAL');
            self::migrate();
        }
        return self::$pdo;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function fetchColumn(string $sql, array $params = [])
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        $value = $stmt->fetchColumn();
        return $value === false ? null : $value;
    }

    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function lastId(): string
    {
        return self::pdo()->lastInsertId();
    }

    private static function ensureDirs(): void
    {
        foreach ([DATA, DATA . '/logs'] as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
        }
    }

    private static function migrate(): void
    {
        $migrationsDir = DATA . '/migrations';
        if (!is_dir($migrationsDir)) {
            return;
        }
        $files = glob($migrationsDir . '/*.sql') ?: [];
        if (!$files) {
            return;
        }
        sort($files);

        self::$pdo->exec(
            'CREATE TABLE IF NOT EXISTS _migrations (
                name TEXT PRIMARY KEY,
                applied_at INTEGER NOT NULL
            )'
        );

        $applied = self::$pdo->query('SELECT name FROM _migrations')->fetchAll(PDO::FETCH_COLUMN);

        foreach ($files as $path) {
            $name = basename($path);
            if (in_array($name, $applied, true)) {
                continue;
            }
            try {
                $sql = (string)file_get_contents($path);
                self::$pdo->exec($sql);
                $stmt = self::$pdo->prepare('INSERT INTO _migrations (name, applied_at) VALUES (?, ?)');
                $stmt->execute([$name, time()]);
            } catch (\Throwable $e) {
                error_log('[migrate] ' . $name . ': ' . $e->getMessage());
                // Не падаем — даём остальному сайту работать; миграция повторится в следующий раз.
                break;
            }
        }
    }
}

final class Settings
{
    private static ?array $cache = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        if (self::$cache === null) {
            self::$cache = [];
            foreach (DB::fetchAll('SELECT key, value FROM settings') as $row) {
                self::$cache[$row['key']] = $row['value'];
            }
        }
        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        DB::execute(
            'INSERT INTO settings(key, value) VALUES(?, ?)
             ON CONFLICT(key) DO UPDATE SET value=excluded.value',
            [$key, $value]
        );
        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }
    }
}
