<?php
declare(strict_types=1);

final class Mailer
{
    /**
     * Отправляет HTML-письмо через mail(). Возвращает true при успехе.
     *
     * @param string[] $to       Список email-адресов получателей
     * @param string   $subject  Тема (будет MIME-кодирована для UTF-8)
     * @param string   $htmlBody Тело письма (HTML)
     * @param string|null $replyTo email для ответа (опционально)
     */
    public static function send(array $to, string $subject, string $htmlBody, ?string $replyTo = null): bool
    {
        $to = array_values(array_filter(array_map('trim', $to), static fn($e) => $e !== '' && filter_var($e, FILTER_VALIDATE_EMAIL)));
        if (!$to) return false;

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $fromName       = '=?UTF-8?B?' . base64_encode(MAIL_FROM_NAME) . '?=';

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';
        $headers[] = 'From: ' . $fromName . ' <' . MAIL_FROM_ADDRESS . '>';
        if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }
        $headers[] = 'X-Mailer: PureHome/1.0';

        $ok = true;
        foreach ($to as $address) {
            $sent = @mail($address, $encodedSubject, $htmlBody, implode("\r\n", $headers));
            $ok = $ok && $sent;
        }
        return $ok;
    }

    /**
     * Получатели обращений с формы ОС: декодирует JSON-массив из settings.feedback_recipients.
     * Возвращает дефолтный адрес, если настройка пустая или невалидна.
     *
     * @return string[]
     */
    public static function feedbackRecipients(): array
    {
        $raw = Settings::get('feedback_recipients');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && $decoded) {
                $clean = array_filter(array_map('strval', $decoded));
                if ($clean) return array_values($clean);
            }
        }
        return [DEFAULT_FEEDBACK_RECIPIENT];
    }
}
