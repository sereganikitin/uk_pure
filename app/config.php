<?php
declare(strict_types=1);

const SITE_NAME    = 'Pure Home';
const SITE_TAGLINE = 'Управляющая компания';

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
