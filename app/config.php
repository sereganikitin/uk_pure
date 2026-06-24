<?php
declare(strict_types=1);

const SITE_NAME    = 'Pure Home';
const SITE_TAGLINE = 'Управляющая компания';

// Юридическое лицо — оператор персональных данных (152-ФЗ).
const LEGAL_OPERATOR = 'ООО «УК Хоум»';

// Реквизиты управляющей компании (обновлены 08.06.2026).
// Меняются редко и целиком — поэтому хранятся здесь, а не в админке.
const REQUISITES = [
    'Полное наименование'       => 'Общество с ограниченной ответственностью «УК Хоум»',
    'Сокращённое наименование'  => 'ООО «УК Хоум»',
    'Юридический адрес'         => '125252, г. Москва, вн.тер.г. Муниципальный округ Хорошевский, ул. Зорге, д. 9А, к. 1, помещ. 3К',
    'Фактический адрес'         => '125252, г. Москва, вн.тер.г. Муниципальный округ Хорошевский, ул. Зорге, д. 9А, к. 1, помещ. 3К',
    'Генеральный директор'      => 'Макарова Ирина Викторовна',
    'ОГРН'                      => '1227700692075',
    'ИНН'                       => '9703114760',
    'КПП'                       => '771401001',
    'ОКВЭД'                     => '68.32',
    'Банк'                      => 'ТКБ БАНК ПАО, г. Москва',
    'Расчётный счёт'            => '40702 810 8 2015 0002654',
    'БИК'                       => '044525388',
    'Корр. счёт'                => '30101 810 8 0000 0000388',
    'Телефон'                   => '+7 (929) 501-50-71',
    'E-mail'                    => 'info@purehome.ru',
];

const DB_PATH      = DATA . '/purehome.sqlite';
const SESSION_NAME = 'PUREHOME_SID';

const DEFAULT_FEEDBACK_RECIPIENT = 'info@purehome.ru';
const MAIL_FROM_ADDRESS          = 'no-reply@purehome.ru';
const MAIL_FROM_NAME             = 'Pure Home — сайт';

const FEEDBACK_MIN_FILL_SECONDS = 3;
const FEEDBACK_RATE_LIMIT_PER_HOUR = 3;
const FEEDBACK_MESSAGE_MAX_LENGTH  = 4000;

const ADMIN_SESSION_TTL_SECONDS = 3600 * 2;
const ADMIN_LOGIN_RATE_LIMIT    = 5;
const ADMIN_LOGIN_RATE_WINDOW   = 900;

if (is_file(APP . '/config.local.php')) {
    require APP . '/config.local.php';
}
