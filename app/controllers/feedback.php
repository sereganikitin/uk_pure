<?php
declare(strict_types=1);

function feedback_submit(): void
{
    Csrf::requireValid();

    $name    = trim((string)($_POST['name']    ?? ''));
    $phone   = trim((string)($_POST['phone']   ?? ''));
    $email   = trim((string)($_POST['email']   ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    $honeypot = (string)($_POST['website'] ?? '');
    $renderedAt = (int)($_POST['_t'] ?? 0);
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ip = client_ip();

    // Тихие отказы для ботов: возвращаем «успех», чтобы не давать обратной связи скриптам
    if (SpamGuard::isHoneypotFilled($honeypot)) { feedback_silent_ok(); return; }
    if (SpamGuard::isTooFast($renderedAt, FEEDBACK_MIN_FILL_SECONDS)) { feedback_silent_ok(); return; }
    if (SpamGuard::isSuspiciousUserAgent($ua)) { feedback_silent_ok(); return; }
    if (SpamGuard::containsStopWords($name, $message)) { feedback_silent_ok(); return; }

    // Rate-limit по IP
    if (!RateLimit::hit('feedback:' . $ip, FEEDBACK_RATE_LIMIT_PER_HOUR, 3600)) {
        $_SESSION['feedback_error'] = 'Слишком много обращений с одного IP. Попробуйте позже.';
        redirect(url('contacts#feedback'));
    }

    $errors = SpamGuard::validate([
        'name' => $name, 'phone' => $phone, 'email' => $email, 'message' => $message,
    ]);
    if ($errors) {
        $_SESSION['feedback_error']  = 'Проверьте корректность заполнения полей.';
        $_SESSION['feedback_errors'] = $errors;
        $_SESSION['feedback_old']    = compact('name', 'phone', 'email', 'message');
        redirect(url('contacts#feedback'));
    }

    DB::execute(
        'INSERT INTO feedback_requests (name, phone, email, message, ip, user_agent, status, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        [$name, $phone ?: null, $email ?: null, $message, $ip, $ua, 'new', time()]
    );
    $requestId = (int)DB::lastId();

    $subject = 'Обращение с сайта Pure Home Comfort';
    $html =
        '<html><body style="font-family:Arial,Helvetica,sans-serif;color:#222;line-height:1.5;">'
      . '<h2 style="margin:0 0 16px;color:#a98843;">Новое обращение с сайта</h2>'
      . '<table cellpadding="6" style="border-collapse:collapse;">'
      .   '<tr><td><strong>Имя:</strong></td><td>' . e($name) . '</td></tr>'
      . ($phone   ? '<tr><td><strong>Телефон:</strong></td><td>' . e($phone) . '</td></tr>' : '')
      . ($email   ? '<tr><td><strong>Email:</strong></td><td>' . e($email) . '</td></tr>' : '')
      .   '<tr><td valign="top"><strong>Сообщение:</strong></td><td>' . nl2br(e($message)) . '</td></tr>'
      .   '<tr><td><strong>IP:</strong></td><td>' . e($ip) . '</td></tr>'
      .   '<tr><td><strong>Время:</strong></td><td>' . date('d.m.Y H:i') . '</td></tr>'
      .   '<tr><td><strong>ID заявки:</strong></td><td>#' . $requestId . '</td></tr>'
      . '</table>'
      . '</body></html>';

    Mailer::send(Mailer::feedbackRecipients(), $subject, $html, $email ?: null);

    $_SESSION['feedback_success'] = 'Спасибо! Мы получили ваше обращение и свяжемся с вами в ближайшее время.';
    redirect(url('contacts#feedback'));
}

function feedback_silent_ok(): void
{
    $_SESSION['feedback_success'] = 'Спасибо! Мы получили ваше обращение и свяжемся с вами в ближайшее время.';
    redirect(url('contacts#feedback'));
}
