<header class="header">
    <div class="container header__inner">
        <a href="<?= url() ?>" class="header__logo" aria-label="<?= e(SITE_NAME) ?> — на главную">
            <img src="<?= asset('img/logo-gold.svg') ?>" alt="<?= e(SITE_NAME) ?>">
        </a>

        <input type="checkbox" id="nav-toggle" class="nav-toggle" hidden>
        <label for="nav-toggle" class="nav-toggle__btn" aria-label="Меню">
            <span></span><span></span><span></span>
        </label>

        <nav class="nav" aria-label="Главная навигация">
            <a href="<?= url() ?>"          class="nav__link<?= nav_class('/') ?>">Главная</a>
            <a href="<?= url('about') ?>"   class="nav__link<?= nav_class('/about') ?>">О компании</a>
            <a href="<?= url('services') ?>"  class="nav__link<?= nav_class('/services') ?>">Услуги</a>
            <a href="<?= url('documents') ?>" class="nav__link<?= nav_class('/documents') ?>">Документы</a>
            <a href="<?= url('news') ?>"      class="nav__link<?= nav_class('/news', true) ?>">Новости</a>
            <a href="<?= url('contacts') ?>"  class="nav__link<?= nav_class('/contacts') ?>">Контакты</a>
        </nav>

        <a href="<?= url('contacts') ?>#feedback" class="btn btn--primary header__cta">Связаться</a>
    </div>
</header>
