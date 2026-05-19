<?php
declare(strict_types=1);

require_once APP . '/admin/_helpers.php';

function admin_dashboard(): void
{
    Auth::requireAuth();

    $stats = [
        'news_total'        => (int)DB::fetchColumn('SELECT COUNT(*) FROM news'),
        'news_published'    => (int)DB::fetchColumn('SELECT COUNT(*) FROM news WHERE is_published = 1'),
        'docs_total'        => (int)DB::fetchColumn('SELECT COUNT(*) FROM documents'),
        'feedback_total'    => (int)DB::fetchColumn('SELECT COUNT(*) FROM feedback_requests'),
        'feedback_new'      => (int)DB::fetchColumn("SELECT COUNT(*) FROM feedback_requests WHERE status = 'new'"),
    ];
    $latestNews = DB::fetchAll('SELECT id, slug, title, published_at, is_published FROM news ORDER BY published_at DESC, id DESC LIMIT 5');
    $latestFeedback = DB::fetchAll('SELECT id, name, status, created_at FROM feedback_requests ORDER BY id DESC LIMIT 5');

    admin_layout('dashboard', [
        'title'           => 'Панель управления',
        'stats'           => $stats,
        'latestNews'      => $latestNews,
        'latestFeedback'  => $latestFeedback,
    ]);
}
