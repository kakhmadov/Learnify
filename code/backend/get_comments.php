<?php
header('Content-Type: application/json');
require __DIR__ . '/db.php';
session_start();

$fileId = $_GET['file_id'] ?? null;
if (!$fileId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing file_id']);
    exit;
}

$stmt = $pdo->prepare(
    'SELECT c.id, c.parent_comment_id, c.content, c.created_at, u.username
     FROM comments c
     JOIN users u ON c.user_id = u.id
     WHERE c.file_id = :fid
     ORDER BY c.created_at ASC'
);
$stmt->execute([':fid' => $fileId]);
$comments = $stmt->fetchAll();

echo json_encode($comments);