<?php
declare(strict_types=1);

/**
 * Многослойная защита формы ОС без видимой CAPTCHA:
 *  - honeypot (скрытое поле, бот заполнит, человек нет)
 *  - time-trap (отправка раньше N сек после загрузки — бот)
 *  - rate-limit по IP (хранится в таблице rate_limits)
 *  - проверки User-Agent / стоп-слов
 *  - валидация формата полей
 */
final class SpamGuard
{
    /** Стоп-слова в имени/сообщении (lowercased). Подозрительный текст → отказ. */
    private const STOP_WORDS = [
        'http://', 'https://', 'www.', '.ru/', '.com/', 'viagra', 'casino', 'bitcoin',
        'crypto', 'porn', 'sex ', 'seo продвижение', 'продвижение сайт', 'наращивание',
        'кредит без', 'займ онлайн', 'купить ссылки', 'обратные ссылки',
    ];

    /** Поведение бота: время заполнения формы. */
    public static function isTooFast(int $renderedAt, int $minSeconds): bool
    {
        return (time() - $renderedAt) < $minSeconds;
    }

    public static function isHoneypotFilled(?string $value): bool
    {
        return $value !== null && $value !== '';
    }

    public static function isSuspiciousUserAgent(?string $ua): bool
    {
        $ua = trim((string)$ua);
        if ($ua === '') return true;
        $low = strtolower($ua);
        foreach (['curl', 'wget', 'python-requests', 'libwww', 'scrapy', 'httpclient', 'go-http-client'] as $pattern) {
            if (strpos($low, $pattern) !== false) return true;
        }
        return false;
    }

    public static function containsStopWords(string ...$fields): bool
    {
        foreach ($fields as $f) {
            $low = mb_strtolower($f);
            foreach (self::STOP_WORDS as $w) {
                if (strpos($low, $w) !== false) return true;
            }
        }
        return false;
    }

    /**
     * @return string[] список ошибок валидации (если пусто — всё ок)
     */
    public static function validate(array $data): array
    {
        $errors = [];

        $name = trim($data['name'] ?? '');
        if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
            $errors['name'] = 'Укажите имя (2–100 символов).';
        }

        $phone = trim($data['phone'] ?? '');
        $digits = preg_replace('/[^\d]/', '', $phone);
        if ($phone !== '' && (strlen($digits) < 6 || strlen($digits) > 16)) {
            $errors['phone'] = 'Проверьте номер телефона.';
        }

        $email = trim($data['email'] ?? '');
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Проверьте адрес электронной почты.';
        }

        if ($email === '' && $phone === '') {
            $errors['phone'] = 'Оставьте хотя бы телефон или email для связи.';
        }

        $message = trim($data['message'] ?? '');
        if (mb_strlen($message) < 5) {
            $errors['message'] = 'Сообщение слишком короткое (минимум 5 символов).';
        }
        if (mb_strlen($message) > FEEDBACK_MESSAGE_MAX_LENGTH) {
            $errors['message'] = 'Сообщение слишком длинное.';
        }

        return $errors;
    }
}

final class RateLimit
{
    public static function hit(string $key, int $maxRequests, int $windowSeconds): bool
    {
        $now = time();
        DB::execute('DELETE FROM rate_limits WHERE ts < ?', [$now - $windowSeconds]);
        $count = (int)DB::fetchColumn(
            'SELECT COUNT(*) FROM rate_limits WHERE key = ? AND ts >= ?',
            [$key, $now - $windowSeconds]
        );
        if ($count >= $maxRequests) {
            return false;
        }
        DB::execute('INSERT INTO rate_limits(key, ts) VALUES(?, ?)', [$key, $now]);
        return true;
    }
}
