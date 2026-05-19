-- Реальные контакты, соцсети, расписание.
-- INSERT OR REPLACE — совместимо со старыми SQLite (3.22+).

INSERT OR REPLACE INTO settings(key, value) VALUES ('site_phone',          '+7 (929) 501-50-71');
INSERT OR REPLACE INTO settings(key, value) VALUES ('site_email',          'info@purehome.ru');
INSERT OR REPLACE INTO settings(key, value) VALUES ('site_address',        'г. Москва, ул. Зорге, 9, к. 1');
INSERT OR REPLACE INTO settings(key, value) VALUES ('site_work_hours',     'Пн–Пт 10:00–19:00 · Сб–Вс выходной');
INSERT OR REPLACE INTO settings(key, value) VALUES ('telegram_url',        'https://t.me/pure_home_comfort');
INSERT OR REPLACE INTO settings(key, value) VALUES ('whatsapp_url',        'https://wa.me/79365009097');
INSERT OR REPLACE INTO settings(key, value) VALUES ('whatsapp_number',     '+7 (936) 500-90-97');
INSERT OR REPLACE INTO settings(key, value) VALUES ('app_store_url',       'https://apps.apple.com/ru/app/pure-home/id6753172190');
INSERT OR REPLACE INTO settings(key, value) VALUES ('google_play_url',     'https://play.google.com/store/apps/details?id=ru.ds24.stmichael');
INSERT OR REPLACE INTO settings(key, value) VALUES ('lk_url',              'https://lk.purehome.ru/login');
INSERT OR REPLACE INTO settings(key, value) VALUES ('feedback_recipients', '["info@purehome.ru"]');
