<?php $errors = $_SESSION['admin_errors'] ?? []; $old = $_SESSION['admin_old'] ?? []; admin_clear_old_errors(); ?>
<div class="admin-auth">
    <h1>Создание первого администратора</h1>
    <p class="admin-auth__note">В системе ещё нет учётных записей. Создайте первого администратора. Пароль не короче 10 символов.</p>

    <form method="post" action="<?= url('admin/setup') ?>" class="admin-form">
        <?= Csrf::field() ?>
        <div class="admin-form__row">
            <label class="admin-form__label" for="su_username">Логин</label>
            <input id="su_username" name="username" type="text" class="admin-form__input<?= isset($errors['username']) ? ' is-error' : '' ?>"
                   value="<?= e($old['username'] ?? '') ?>" required minlength="3" maxlength="32" autocomplete="username">
            <?php if (isset($errors['username'])): ?><div class="admin-form__error"><?= e($errors['username']) ?></div><?php endif; ?>
        </div>
        <div class="admin-form__row">
            <label class="admin-form__label" for="su_password">Пароль</label>
            <input id="su_password" name="password" type="password" class="admin-form__input<?= isset($errors['password']) ? ' is-error' : '' ?>"
                   required minlength="10" autocomplete="new-password">
            <?php if (isset($errors['password'])): ?><div class="admin-form__error"><?= e($errors['password']) ?></div><?php endif; ?>
        </div>
        <div class="admin-form__row">
            <label class="admin-form__label" for="su_password_repeat">Повторите пароль</label>
            <input id="su_password_repeat" name="password_repeat" type="password" class="admin-form__input<?= isset($errors['password_repeat']) ? ' is-error' : '' ?>"
                   required minlength="10" autocomplete="new-password">
            <?php if (isset($errors['password_repeat'])): ?><div class="admin-form__error"><?= e($errors['password_repeat']) ?></div><?php endif; ?>
        </div>
        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Создать администратора</button>
        </div>
    </form>
</div>
