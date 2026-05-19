<div class="admin-auth">
    <h1>Вход в админку</h1>
    <form method="post" action="<?= url('admin/login') ?>" class="admin-form">
        <?= Csrf::field() ?>
        <div class="admin-form__row">
            <label class="admin-form__label" for="login_username">Логин</label>
            <input id="login_username" name="username" type="text" class="admin-form__input" required autocomplete="username" autofocus>
        </div>
        <div class="admin-form__row">
            <label class="admin-form__label" for="login_password">Пароль</label>
            <input id="login_password" name="password" type="password" class="admin-form__input" required autocomplete="current-password">
        </div>
        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Войти</button>
        </div>
    </form>
</div>
