<?php
$errors = $_SESSION['feedback_errors'] ?? [];
$old    = $_SESSION['feedback_old']    ?? [];
$success = $_SESSION['feedback_success'] ?? null;
$error   = $_SESSION['feedback_error']   ?? null;
unset($_SESSION['feedback_errors'], $_SESSION['feedback_old'], $_SESSION['feedback_success'], $_SESSION['feedback_error']);
$val = static fn(string $field) => e($old[$field] ?? '');
?>
<div class="feedback" id="feedback">
    <h2 class="feedback__title">Написать в управляющую компанию</h2>
    <p class="feedback__lead">Опишите вопрос — мы свяжемся с вами в&nbsp;течение рабочего дня.</p>

    <?php if ($success): ?>
        <div class="alert alert--success"><?= e($success) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <form class="form" action="<?= url('contacts/send') ?>" method="post" novalidate>
        <?= Csrf::field() ?>
        <input type="hidden" name="_t" value="<?= time() ?>">

        <!-- honeypot: люди не заполняют, css прячет от глаз -->
        <div class="form__honeypot" aria-hidden="true">
            <label>Сайт (не заполнять):
                <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
            </label>
        </div>

        <div class="form__row">
            <label class="form__label" for="ff_name">Ваше имя <span class="form__req">*</span></label>
            <input id="ff_name" class="form__input<?= isset($errors['name']) ? ' form__input--error' : '' ?>"
                   type="text" name="name" required minlength="2" maxlength="100"
                   value="<?= $val('name') ?>" autocomplete="name">
            <?php if (isset($errors['name'])): ?><div class="form__error"><?= e($errors['name']) ?></div><?php endif; ?>
        </div>

        <div class="form__grid">
            <div class="form__row">
                <label class="form__label" for="ff_phone">Телефон</label>
                <input id="ff_phone" class="form__input<?= isset($errors['phone']) ? ' form__input--error' : '' ?>"
                       type="tel" name="phone" maxlength="32" inputmode="tel"
                       placeholder="+7 (___) ___-__-__"
                       value="<?= $val('phone') ?>" autocomplete="tel">
                <?php if (isset($errors['phone'])): ?><div class="form__error"><?= e($errors['phone']) ?></div><?php endif; ?>
            </div>
            <div class="form__row">
                <label class="form__label" for="ff_email">Email</label>
                <input id="ff_email" class="form__input<?= isset($errors['email']) ? ' form__input--error' : '' ?>"
                       type="email" name="email" maxlength="120"
                       placeholder="name@example.ru"
                       value="<?= $val('email') ?>" autocomplete="email">
                <?php if (isset($errors['email'])): ?><div class="form__error"><?= e($errors['email']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="form__row">
            <label class="form__label" for="ff_message">Сообщение <span class="form__req">*</span></label>
            <textarea id="ff_message" class="form__textarea<?= isset($errors['message']) ? ' form__textarea--error' : '' ?>"
                      name="message" required minlength="5" maxlength="<?= FEEDBACK_MESSAGE_MAX_LENGTH ?>"
                      rows="6"><?= $val('message') ?></textarea>
            <?php if (isset($errors['message'])): ?><div class="form__error"><?= e($errors['message']) ?></div><?php endif; ?>
            <div class="form__hint">Минимум 5 символов. Не публикуем персональные данные третьих лиц.</div>
        </div>

        <div class="form__actions">
            <button type="submit" class="btn btn--primary">Отправить</button>
            <span class="form__legal">Нажимая «Отправить», вы соглашаетесь на обработку персональных данных.</span>
        </div>
    </form>
</div>
