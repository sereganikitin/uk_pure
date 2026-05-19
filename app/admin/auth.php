<?php
declare(strict_types=1);

require_once APP . '/admin/_helpers.php';

function admin_index(): void
{
    if (Auth::check()) {
        redirect(url('admin/dashboard'));
    }
    if (Auth::needsSetup()) {
        redirect(url('admin/setup'));
    }
    redirect(url('admin/login'));
}

function admin_login_form(): void
{
    if (Auth::check()) {
        redirect(url('admin/dashboard'));
    }
    if (Auth::needsSetup()) {
        redirect(url('admin/setup'));
    }
    admin_layout('login', ['title' => 'Вход в админку']);
}

function admin_login_submit(): void
{
    Csrf::requireValid();
    $ip = client_ip();
    if (Auth::failedAttempts($ip) >= ADMIN_LOGIN_RATE_LIMIT) {
        admin_flash('error', 'Слишком много неудачных попыток. Подождите 15 минут.');
        redirect(url('admin/login'));
    }
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($username === '' || $password === '') {
        admin_flash('error', 'Введите логин и пароль.');
        redirect(url('admin/login'));
    }
    if (Auth::attempt($username, $password, $ip)) {
        $next = $_SESSION['admin_after_login'] ?? url('admin/dashboard');
        unset($_SESSION['admin_after_login']);
        admin_flash('success', 'Здравствуйте, ' . $username . '!');
        redirect($next);
    }
    admin_flash('error', 'Неверный логин или пароль.');
    redirect(url('admin/login'));
}

function admin_setup_form(): void
{
    if (!Auth::needsSetup()) {
        redirect(url('admin/login'));
    }
    admin_layout('setup', ['title' => 'Первичная настройка админки']);
}

function admin_setup_submit(): void
{
    Csrf::requireValid();
    if (!Auth::needsSetup()) {
        redirect(url('admin/login'));
    }
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $repeat   = (string)($_POST['password_repeat'] ?? '');

    $errors = [];
    if (mb_strlen($username) < 3 || mb_strlen($username) > 32 || !preg_match('/^[A-Za-z0-9_.\-]+$/', $username)) {
        $errors['username'] = 'Логин: 3–32 символа, латинские буквы, цифры, _.-';
    }
    if (strlen($password) < 10) {
        $errors['password'] = 'Пароль должен быть не короче 10 символов.';
    }
    if ($password !== $repeat) {
        $errors['password_repeat'] = 'Пароли не совпадают.';
    }
    if ($errors) {
        admin_set_errors($errors);
        admin_set_old(['username' => $username]);
        admin_flash('error', 'Проверьте поля.');
        redirect(url('admin/setup'));
    }
    DB::execute(
        'INSERT INTO admin_users(username, password_hash, role, created_at) VALUES(?, ?, ?, ?)',
        [$username, password_hash($password, PASSWORD_BCRYPT), 'admin', time()]
    );
    admin_clear_old_errors();
    admin_flash('success', 'Администратор создан. Войдите в систему.');
    redirect(url('admin/login'));
}

function admin_logout(): void
{
    Csrf::requireValid();
    Auth::logout();
    admin_flash('success', 'Вы вышли из системы.');
    redirect(url('admin/login'));
}
