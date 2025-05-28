<?php
require __DIR__ . '/db.php';
session_start();

$id = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$stmt = $pdo->prepare('SELECT filename, user_id FROM files WHERE id = ?');
$stmt->execute([$id]);
$f = $stmt->fetch();

if (!$f || $f['user_id'] !== $userId) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$path = __DIR__ . '/uploads/' . $userId . '/' . $f['filename'];
if (!file_exists($path)) {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

// Serve file download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($f['filename']) . '"');
readfile($path);
exit;