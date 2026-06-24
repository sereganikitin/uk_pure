-- Обновление юридического адреса в публичных контактах (смена адреса с 05.06.2026).
-- Полные реквизиты вынесены в app/config.php (REQUISITES).

INSERT OR REPLACE INTO settings(key, value) VALUES ('site_address', '125252, г. Москва, ул. Зорге, д. 9А, к. 1, помещ. 3К');
