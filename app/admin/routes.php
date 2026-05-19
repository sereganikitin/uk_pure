<?php
declare(strict_types=1);

// Точка входа в админку (редиректит)
Router::get('/admin',          'admin/auth@admin_index');
Router::get('/admin/',         'admin/auth@admin_index');

// Авторизация и первичная настройка
Router::get('/admin/login',    'admin/auth@admin_login_form');
Router::post('/admin/login',   'admin/auth@admin_login_submit');
Router::get('/admin/setup',    'admin/auth@admin_setup_form');
Router::post('/admin/setup',   'admin/auth@admin_setup_submit');
Router::post('/admin/logout',  'admin/auth@admin_logout');

// Главная админки
Router::get('/admin/dashboard',  'admin/dashboard@admin_dashboard');

// Новости
Router::get('/admin/news',                  'admin/news@admin_news_list');
Router::get('/admin/news/create',           'admin/news@admin_news_form');
Router::post('/admin/news/create',          'admin/news@admin_news_save');
Router::get('/admin/news/{id}/edit',        'admin/news@admin_news_form');
Router::post('/admin/news/{id}/edit',       'admin/news@admin_news_save');
Router::post('/admin/news/{id}/delete',     'admin/news@admin_news_delete');

// Документы
Router::get('/admin/documents',                 'admin/documents@admin_docs_list');
Router::post('/admin/documents/categories',     'admin/documents@admin_docs_categories_save');
Router::get('/admin/documents/create',          'admin/documents@admin_docs_form');
Router::post('/admin/documents/create',         'admin/documents@admin_docs_save');
Router::get('/admin/documents/{id}/edit',       'admin/documents@admin_docs_form');
Router::post('/admin/documents/{id}/edit',      'admin/documents@admin_docs_save');
Router::post('/admin/documents/{id}/delete',    'admin/documents@admin_docs_delete');

// Настройки
Router::get('/admin/settings',  'admin/settings@admin_settings_form');
Router::post('/admin/settings', 'admin/settings@admin_settings_save');

// Заявки с формы ОС
Router::get('/admin/feedback',                    'admin/feedback@admin_feedback_list');
Router::get('/admin/feedback/{id}',               'admin/feedback@admin_feedback_show');
Router::post('/admin/feedback/{id}/status',       'admin/feedback@admin_feedback_status');
