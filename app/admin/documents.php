<?php
declare(strict_types=1);

require_once APP . '/admin/_helpers.php';
require_once APP . '/admin/upload.php';

function admin_docs_list(): void
{
    Auth::requireAuth();
    $cats = DB::fetchAll('SELECT id, name, sort_order FROM document_categories ORDER BY sort_order, name');
    $docs = DB::fetchAll(
        'SELECT d.id, d.title, d.file_path, d.file_size, d.mime_type, d.sort_order, d.created_at,
                d.category_id, c.name AS category_name
         FROM documents d
         LEFT JOIN document_categories c ON c.id = d.category_id
         ORDER BY c.sort_order, d.sort_order, d.id DESC'
    );
    admin_layout('documents_list', [
        'title' => 'Документы',
        'cats'  => $cats,
        'docs'  => $docs,
    ]);
}

function admin_docs_categories_save(): void
{
    Auth::requireAuth();
    Csrf::requireValid();

    $rename = $_POST['rename'] ?? [];
    $order  = $_POST['order']  ?? [];
    $delete = $_POST['delete'] ?? [];
    $newName = trim((string)($_POST['new_name'] ?? ''));
    $newOrder = (int)($_POST['new_order'] ?? 0);

    if (is_array($rename)) {
        foreach ($rename as $cid => $name) {
            $name = trim((string)$name);
            if ($name === '') continue;
            $sort = isset($order[$cid]) ? (int)$order[$cid] : 0;
            DB::execute('UPDATE document_categories SET name=?, sort_order=? WHERE id=?', [$name, $sort, (int)$cid]);
        }
    }
    if (is_array($delete)) {
        foreach ($delete as $cid) {
            DB::execute('DELETE FROM document_categories WHERE id=?', [(int)$cid]);
        }
    }
    if ($newName !== '') {
        DB::execute('INSERT INTO document_categories(name, sort_order) VALUES(?, ?)', [$newName, $newOrder]);
    }
    admin_flash('success', 'Категории обновлены.');
    redirect(url('admin/documents'));
}

function admin_docs_form($id = null): void
{
    Auth::requireAuth();
    $row = null;
    if ($id !== null) {
        $row = DB::fetch('SELECT * FROM documents WHERE id = ?', [(int)$id]);
        if (!$row) {
            admin_flash('error', 'Документ не найден.');
            redirect(url('admin/documents'));
        }
    }
    $cats = DB::fetchAll('SELECT id, name FROM document_categories ORDER BY sort_order, name');
    admin_layout('documents_form', [
        'title'  => $row ? 'Редактирование документа' : 'Новый документ',
        'row'    => $row,
        'cats'   => $cats,
        'errors' => admin_errors(),
    ]);
    admin_clear_old_errors();
}

function admin_docs_save($id = null): void
{
    Auth::requireAuth();
    Csrf::requireValid();

    $isNew = $id === null;
    $existing = $isNew ? null : DB::fetch('SELECT * FROM documents WHERE id = ?', [(int)$id]);
    if (!$isNew && !$existing) {
        admin_flash('error', 'Документ не найден.');
        redirect(url('admin/documents'));
    }

    $title      = trim((string)($_POST['title'] ?? ''));
    $categoryId = (int)($_POST['category_id'] ?? 0) ?: null;
    $sortOrder  = (int)($_POST['sort_order'] ?? 0);

    $errors = [];
    if (mb_strlen($title) < 2 || mb_strlen($title) > 200) {
        $errors['title'] = 'Название: 2–200 символов.';
    }
    $filePath = $existing['file_path'] ?? null;
    $fileSize = $existing['file_size'] ?? null;
    $mime     = $existing['mime_type'] ?? null;

    $hasNewFile = !empty($_FILES['file']['name']);
    if ($isNew && !$hasNewFile) {
        $errors['file'] = 'Выберите файл.';
    }
    if ($errors) {
        admin_set_errors($errors);
        admin_flash('error', 'Проверьте поля формы.');
        redirect($isNew ? url('admin/documents/create') : url('admin/documents/' . (int)$id . '/edit'));
    }

    if ($hasNewFile) {
        try {
            $newName = Upload::document($_FILES['file']);
            // Удаляем старый файл только если он не используется другой записью.
            if ($filePath && admin_doc_file_orphan($filePath, (int)$id)) {
                Upload::deleteIn('documents', $filePath);
            }
            $filePath = $newName;
            $fileSize = (int)($_FILES['file']['size'] ?? 0);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file(UPLOADS . '/documents/' . $newName) ?: null;
        } catch (UploadException $e) {
            admin_flash('error', 'Файл: ' . $e->getMessage());
            redirect($isNew ? url('admin/documents/create') : url('admin/documents/' . (int)$id . '/edit'));
        }
    }

    if ($isNew) {
        DB::execute(
            'INSERT INTO documents(category_id, title, file_path, file_size, mime_type, sort_order, created_at)
             VALUES(?, ?, ?, ?, ?, ?, ?)',
            [$categoryId, $title, $filePath, $fileSize, $mime, $sortOrder, time()]
        );
        admin_flash('success', 'Документ добавлен.');
    } else {
        DB::execute(
            'UPDATE documents SET category_id=?, title=?, file_path=?, file_size=?, mime_type=?, sort_order=? WHERE id=?',
            [$categoryId, $title, $filePath, $fileSize, $mime, $sortOrder, (int)$id]
        );
        admin_flash('success', 'Документ обновлён.');
    }
    admin_clear_old_errors();
    redirect(url('admin/documents'));
}

function admin_docs_delete($id): void
{
    Auth::requireAuth();
    Csrf::requireValid();
    $row = DB::fetch('SELECT file_path FROM documents WHERE id = ?', [(int)$id]);
    if ($row) {
        DB::execute('DELETE FROM documents WHERE id = ?', [(int)$id]);
        // Удаляем физический файл только если на него больше никто не ссылается.
        if (admin_doc_file_orphan((string)$row['file_path'], 0)) {
            Upload::deleteIn('documents', $row['file_path']);
        }
        admin_flash('success', 'Документ удалён.');
    }
    redirect(url('admin/documents'));
}

/**
 * Проверяет, не ссылается ли на $filePath никакая другая запись documents (кроме $excludeId).
 * Используется, чтобы не удалять физический файл, если на него ссылаются другие документы.
 */
function admin_doc_file_orphan(string $filePath, int $excludeId): bool
{
    if ($filePath === '') return false;
    $sql = 'SELECT COUNT(*) FROM documents WHERE file_path = ?';
    $params = [$filePath];
    if ($excludeId > 0) {
        $sql .= ' AND id != ?';
        $params[] = $excludeId;
    }
    return (int)DB::fetchColumn($sql, $params) === 0;
}
