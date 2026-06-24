<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__col">
                <div class="footer__brand"><?= e(SITE_NAME) ?></div>
                <p class="footer__tagline"><?= e(SITE_TAGLINE) ?></p>
                <?php
                    $telegram   = Settings::get('telegram_url');
                    $whatsapp   = Settings::get('whatsapp_url');
                    $appStore   = Settings::get('app_store_url');
                    $googlePlay = Settings::get('google_play_url');
                ?>
                <?php if ($telegram || $whatsapp || $appStore || $googlePlay): ?>
                    <ul class="footer__social">
                        <?php if ($telegram):   ?><li><a href="<?= e($telegram) ?>"   target="_blank" rel="noopener noreferrer">Telegram</a></li><?php endif; ?>
                        <?php if ($whatsapp):   ?><li><a href="<?= e($whatsapp) ?>"   target="_blank" rel="noopener noreferrer">WhatsApp</a></li><?php endif; ?>
                        <?php if ($appStore):   ?><li><a href="<?= e($appStore) ?>"   target="_blank" rel="noopener noreferrer">App Store</a></li><?php endif; ?>
                        <?php if ($googlePlay): ?><li><a href="<?= e($googlePlay) ?>" target="_blank" rel="noopener noreferrer">Google Play</a></li><?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="footer__col">
                <div class="footer__heading">Разделы</div>
                <ul class="footer__list">
                    <li><a href="<?= url('about') ?>">О компании</a></li>
                    <li><a href="<?= url('services') ?>">Услуги</a></li>
                    <li><a href="<?= url('documents') ?>">Документы</a></li>
                    <li><a href="<?= url('news') ?>">Новости</a></li>
                    <li><a href="<?= url('contacts') ?>">Контакты</a></li>
                </ul>
            </div>

            <div class="footer__col">
                <div class="footer__heading">Контакты</div>
                <ul class="footer__list">
                    <?php $phone = Settings::get('site_phone'); if ($phone): ?>
                        <li>Тел.: <a href="tel:<?= e(preg_replace('/[^+\d]/', '', $phone)) ?>"><?= e($phone) ?></a></li>
                    <?php endif; ?>
                    <?php $email = Settings::get('site_email'); if ($email): ?>
                        <li>Email: <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li>
                    <?php endif; ?>
                    <?php $addr = Settings::get('site_address'); if ($addr): ?>
                        <li><?= e($addr) ?></li>
                    <?php endif; ?>
                    <?php $hours = Settings::get('site_work_hours'); if ($hours): ?>
                        <li><?= e($hours) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <span>© <?= date('Y') ?> <?= e(LEGAL_OPERATOR) ?>. Все права защищены.</span>
            <a href="<?= url('privacy') ?>" class="footer__legal-link">Политика обработки персональных данных</a>
        </div>
    </div>
</footer>
