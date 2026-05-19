<?php
$appStore   = Settings::get('app_store_url');
$googlePlay = Settings::get('google_play_url');
$telegram   = Settings::get('telegram_url');
$whatsapp   = Settings::get('whatsapp_url');
if (!$appStore && !$googlePlay && !$telegram && !$whatsapp) return;
?>
<section class="section section--dark cta-app">
    <div class="container cta-app__inner">
        <div class="cta-app__text">
            <h2>Приложение резидента</h2>
            <p>Управляйте всеми услугами в&nbsp;одном приложении: пропуски, счётчики, платежи, заявки в&nbsp;сервис.</p>
        </div>
        <div class="cta-app__buttons">
            <?php if ($appStore): ?>
                <a class="store-btn" href="<?= e($appStore) ?>" target="_blank" rel="noopener noreferrer">App Store</a>
            <?php endif; ?>
            <?php if ($googlePlay): ?>
                <a class="store-btn" href="<?= e($googlePlay) ?>" target="_blank" rel="noopener noreferrer">Google Play</a>
            <?php endif; ?>
            <?php if ($telegram): ?>
                <a class="store-btn store-btn--ghost" href="<?= e($telegram) ?>" target="_blank" rel="noopener noreferrer">Telegram</a>
            <?php endif; ?>
            <?php if ($whatsapp): ?>
                <a class="store-btn store-btn--ghost" href="<?= e($whatsapp) ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            <?php endif; ?>
        </div>
    </div>
</section>
