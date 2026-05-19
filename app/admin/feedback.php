<?php
declare(strict_types=1);

require_once APP . '/admin/_helpers.php';

const FEEDBACK_STATUSES = [
    'new'         => 'Новая',
    'in_progress' => 'В работе',
    'closed'      => 'Закрыта',
];

function admin_feedback_list(): void
{
    Auth::requireAuth();
    $filter = $_GET['status'] ?? '';
    $sql = 'SELECT id, name, phone, email, status, created_at FROM feedback_requests';
    $params = [];
    if (isset(FEEDBACK_STATUSES[$filter])) {
        $sql .= ' WHERE status = ?';
        $params[] = $filter;
    }
    $sql .= ' ORDER BY id DESC LIMIT 200';
    $rows = DB::fetchAll($sql, $params);

    $counts = [];
    foreach (DB::fetchAll('SELECT status, COUNT(*) AS c FROM feedback_requests GROUP BY status') as $r) {
        $counts[$r['status']] = (int)$r['c'];
    }
    $counts['all'] = (int)DB::fetchColumn('SELECT COUNT(*) FROM feedback_requests');

    admin_layout('feedback_list', [
        'title'    => 'Заявки с формы',
        'rows'     => $rows,
        'filter'   => $filter,
        'counts'   => $counts,
        'statuses' => FEEDBACK_STATUSES,
    ]);
}

function admin_feedback_show($id): void
{
    Auth::requireAuth();
    $row = DB::fetch('SELECT * FROM feedback_requests WHERE id = ?', [(int)$id]);
    if (!$row) {
        admin_flash('error', 'Заявка не найдена.');
        redirect(url('admin/feedback'));
    }
    admin_layout('feedback_show', [
        'title'    => 'Заявка #' . $row['id'],
        'row'      => $row,
        'statuses' => FEEDBACK_STATUSES,
    ]);
}

function admin_feedback_status($id): void
{
    Auth::requireAuth();
    Csrf::requireValid();
    $status = (string)($_POST['status'] ?? '');
    if (!isset(FEEDBACK_STATUSES[$status])) {
        admin_flash('error', 'Неизвестный статус.');
        redirect(url('admin/feedback/' . (int)$id));
    }
    DB::execute('UPDATE feedback_requests SET status = ? WHERE id = ?', [$status, (int)$id]);
    admin_flash('success', 'Статус обновлён.');
    redirect(url('admin/feedback/' . (int)$id));
}
