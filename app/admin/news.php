<?php
declare(strict_types=1);

require_once APP . '/admin/_helpers.php';
require_once APP . '/admin/upload.php';

function admin_news_list(): void
{
    Auth::requireAuth();
    $rows = DB::fetchAll(
        'SELECT id, slug, title, published_at, is_published, image_path
         FROM news
         ORDER BY published_at DESC, id DESC'
    );
    admin_layout('news_list', ['title' => 'Новости', 'rows' => $rows]);
}

function admin_news_form($id = null): void
{
    Auth::requireAuth();
    $row = null;
    if ($id !== null) {
        $row = DB::fetch('SELECT * FROM news WHERE id = ?', [(int)$id]);
        if (!$row) {
            admin_flash('error', 'Новость не найдена.');
            redirect(url('admin/news'));
        }
    }
    admin_layout('news_form', [
        'title'   => $row ? 'Редактирование новости' : 'Новая новость',
        'row'     => $row,
        'errors'  => admin_errors(),
    ]);
    admin_clear_old_errors();
}

function admin_news_save($id = null): void
{
    Auth::requireAuth();
    Csrf::requireValid();

    $isNew = $id === null;
    $existing = $isNew ? null : DB::fetch('SELECT * FROM news WHERE id = ?', [(int)$id]);
    if (!$isNew && !$existing) {
        admin_flash('error', 'Новость не найдена.');
        redirect(url('admin/news'));
    }

    $title       = trim((string)($_POST['title'] ?? ''));
    $excerpt     = trim((string)($_POST['excerpt'] ?? ''));
    $content     = trim((string)($_POST['content'] ?? ''));
    $publishedAt = trim((string)($_POST['published_at'] ?? ''));
    $isPublished = !empty($_POST['is_published']) ? 1 : 0;
    $deleteImage = !empty($_POST['delete_image']);

    $errors = [];
    if (mb_strlen($title) < 3 || mb_strlen($title) > 200) {
        $errors['title'] = 'Заголовок: 3–200 символов.';
    }
    if (mb_strlen($content) < 5) {
        $errors['content'] = 'Текст слишком короткий.';
    }
    $publishedTs = $publishedAt ? strtotime($publishedAt) : time();
    if ($publishedTs === false) {
        $errors['published_at'] = 'Дата некорректна.';
    }
    if ($errors) {
        admin_set_errors($errors);
        admin_flash('error', 'Проверьте поля формы.');
        redirect($isNew ? url('admin/news/create') : url('admin/news/' . (int)$id . '/edit'));
    }

    $imagePath = $existing['image_path'] ?? null;

    if ($deleteImage && $imagePath) {
        Upload::deleteIn('news', $imagePath);
        $imagePath = null;
    }
    if (!empty($_FILES['image']['name'])) {
        try {
            $newName = Upload::newsImage($_FILES['image']);
            if ($imagePath) Upload::deleteIn('news', $imagePath);
            $imagePath = $newName;
        } catch (UploadException $e) {
            admin_flash('error', 'Картинка: ' . $e->getMessage());
            redirect($isNew ? url('admin/news/create') : url('admin/news/' . (int)$id . '/edit'));
        }
    }

    $now = time();

    if ($isNew) {
        $slug = admin_make_slug($title, 'news');
        DB::execute(
            'INSERT INTO news(slug, title, excerpt, content, image_path, published_at, is_published, created_at, updated_at)
             VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$slug, $title, $excerpt ?: null, $content, $imagePath, (int)$publishedTs, $isPublished, $now, $now]
        );
        admin_flash('success', 'Новость создана.');
    } else {
        $slug = $existing['slug'];
        if (trim((string)$existing['title']) !== $title) {
            $slug = admin_make_slug($title, 'news', (int)$id);
        }
        DB::execute(
            'UPDATE news SET slug=?, title=?, excerpt=?, content=?, image_path=?, published_at=?, is_published=?, updated_at=?
             WHERE id=?',
            [$slug, $title, $excerpt ?: null, $content, $imagePath, (int)$publishedTs, $isPublished, $now, (int)$id]
        );
        admin_flash('success', 'Изменения сохранены.');
    }
    admin_clear_old_errors();
    redirect(url('admin/news'));
}

function admin_news_delete($id): void
{
    Auth::requireAuth();
    Csrf::requireValid();
    $row = DB::fetch('SELECT image_path FROM news WHERE id = ?', [(int)$id]);
    if ($row) {
        Upload::deleteIn('news', $row['image_path']);
        DB::execute('DELETE FROM news WHERE id = ?', [(int)$id]);
        admin_flash('success', 'Новость удалена.');
    }
    redirect(url('admin/news'));
}
