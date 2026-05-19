<?php
declare(strict_types=1);

require_once APP . '/admin/_helpers.php';

const ADMIN_SETTINGS_FIELDS = [
    'site_phone'      => 'Телефон',
    'site_email'      => 'Email',
    'site_address'    => 'Адрес офиса',
    'site_work_hours' => 'Часы работы',
    'telegram_url'    => 'Telegram (ссылка)',
    'whatsapp_url'    => 'WhatsApp (ссылка)',
    'whatsapp_number' => 'WhatsApp (номер для отображения)',
    'app_store_url'   => 'App Store (ссылка)',
    'google_play_url' => 'Google Play (ссылка)',
    'lk_url'          => 'Личный кабинет (ссылка)',
];

function admin_settings_form(): void
{
    Auth::requireAuth();
    $values = [];
    foreach (array_keys(ADMIN_SETTINGS_FIELDS) as $k) {
        $values[$k] = (string)(Settings::get($k) ?? '');
    }
    $recipientsRaw = Settings::get('feedback_recipients');
    $recipients = [];
    if ($recipientsRaw) {
        $decoded = json_decode($recipientsRaw, true);
        if (is_array($decoded)) $recipients = array_values(array_filter(array_map('strval', $decoded)));
    }
    admin_layout('settings', [
        'title'      => 'Настройки',
        'fields'     => ADMIN_SETTINGS_FIELDS,
        'values'     => $values,
        'recipients' => $recipients,
    ]);
}

function admin_settings_save(): void
{
    Auth::requireAuth();
    Csrf::requireValid();

    foreach (array_keys(ADMIN_SETTINGS_FIELDS) as $key) {
        $value = trim((string)($_POST[$key] ?? ''));
        Settings::set($key, $value !== '' ? $value : null);
    }

    $recRaw = trim((string)($_POST['feedback_recipients'] ?? ''));
    $list = [];
    foreach (preg_split('/[\r\n,;]+/', $recRaw) ?: [] as $email) {
        $email = trim($email);
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $list[] = $email;
        }
    }
    if (!$list) $list = [DEFAULT_FEEDBACK_RECIPIENT];
    Settings::set('feedback_recipients', json_encode($list, JSON_UNESCAPED_UNICODE));

    admin_flash('success', 'Настройки сохранены.');
    redirect(url('admin/settings'));
}
