<?php
$phone    = Settings::get('site_phone');
$email    = Settings::get('site_email');
$address  = Settings::get('site_address');
$hours    = Settings::get('site_work_hours');
$telegram = Settings::get('telegram_url');
$whatsapp = Settings::get('whatsapp_url');
$lk       = Settings::get('lk_url');
?>
<section class="page-header page-header--image" style="background-image: url('<?= asset('img/objects/zorge9.jpg') ?>');">
    <div class="page-header__overlay"></div>
    <div class="container">
        <h1>Контакты</h1>
        <p class="page-header__lead">Свяжитесь с&nbsp;нами удобным способом или оставьте сообщение через форму ниже.</p>
    </div>
</section>

<section class="section">
    <div class="container contacts">
        <div class="contacts__info">
            <h2>Координаты</h2>
            <ul class="contacts__list">
                <?php if ($phone): ?>
                    <li>
                        <span class="contacts__label">Телефон</span>
                        <a class="contacts__value contacts__value--big" href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>"><?= e($phone) ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($email): ?>
                    <li>
                        <span class="contacts__label">Email</span>
                        <a class="contacts__value" href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($address): ?>
                    <li>
                        <span class="contacts__label">Адрес</span>
                        <span class="contacts__value"><?= e($address) ?></span>
                    </li>
                <?php endif; ?>
                <?php if ($hours): ?>
                    <li>
                        <span class="contacts__label">Часы работы</span>
                        <span class="contacts__value"><?= e($hours) ?></span>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($telegram || $whatsapp || $lk): ?>
                <div class="contacts__channels">
                    <?php if ($telegram): ?>
                        <a class="channel-btn" href="<?= e($telegram) ?>" target="_blank" rel="noopener noreferrer">Telegram-канал</a>
                    <?php endif; ?>
                    <?php if ($whatsapp): ?>
                        <a class="channel-btn" href="<?= e($whatsapp) ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                    <?php endif; ?>
                    <?php if ($lk): ?>
                        <a class="channel-btn channel-btn--ghost" href="<?= e($lk) ?>" target="_blank" rel="noopener noreferrer">Личный кабинет</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="contacts__form-wrap">
            <?php require APP . '/views/partials/feedback_form.php'; ?>
        </div>
    </div>
</section>

<section class="section section--surface">
    <div class="container">
        <?php require APP . '/views/partials/requisites.php'; ?>
    </div>
</section>
