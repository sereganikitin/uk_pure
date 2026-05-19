CREATE TABLE IF NOT EXISTS settings (
    key   TEXT PRIMARY KEY,
    value TEXT
);

INSERT OR IGNORE INTO settings(key, value) VALUES
    ('site_phone',          '+7 (495) ___-__-__'),
    ('site_email',          'info@purehome.ru'),
    ('site_address',        'Москва'),
    ('feedback_recipients', '["info@purehome.ru"]');

CREATE TABLE IF NOT EXISTS news (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    slug          TEXT    NOT NULL UNIQUE,
    title         TEXT    NOT NULL,
    excerpt       TEXT,
    content       TEXT    NOT NULL,
    image_path    TEXT,
    published_at  INTEGER,
    is_published  INTEGER NOT NULL DEFAULT 0,
    created_at    INTEGER NOT NULL,
    updated_at    INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_news_published ON news(is_published, published_at DESC);

CREATE TABLE IF NOT EXISTS document_categories (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT    NOT NULL,
    sort_order  INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS documents (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id  INTEGER REFERENCES document_categories(id) ON DELETE SET NULL,
    title        TEXT    NOT NULL,
    file_path    TEXT    NOT NULL,
    file_size    INTEGER,
    mime_type    TEXT,
    sort_order   INTEGER NOT NULL DEFAULT 0,
    created_at   INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS pages (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    slug         TEXT    NOT NULL UNIQUE,
    title        TEXT    NOT NULL,
    blocks_json  TEXT    NOT NULL DEFAULT '[]',
    updated_at   INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS feedback_requests (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT    NOT NULL,
    phone       TEXT,
    email       TEXT,
    message     TEXT    NOT NULL,
    ip          TEXT,
    user_agent  TEXT,
    status      TEXT    NOT NULL DEFAULT 'new',
    created_at  INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_feedback_status_created ON feedback_requests(status, created_at DESC);

CREATE TABLE IF NOT EXISTS rate_limits (
    key  TEXT    NOT NULL,
    ts   INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_rate_limits_key_ts ON rate_limits(key, ts);

CREATE TABLE IF NOT EXISTS admin_users (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    username       TEXT    NOT NULL UNIQUE,
    password_hash  TEXT    NOT NULL,
    role           TEXT    NOT NULL DEFAULT 'admin',
    last_login_at  INTEGER,
    created_at     INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS admin_login_log (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    username    TEXT    NOT NULL,
    ip          TEXT,
    success     INTEGER NOT NULL,
    created_at  INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_admin_login_log_username_ts ON admin_login_log(username, created_at DESC);
