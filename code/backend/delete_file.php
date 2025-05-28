<?php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/db.php';

$id = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$stmt = $pdo->prepare('SELECT filename FROM files WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $userId]);
$f = $stmt->fetch();

if (!$f) {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

$filePath = __DIR__ . '/uploads/' . $userId . '/' . $f['filename'];
unlink($filePath);

$stmt = $pdo->prepare('DELETE FROM files WHERE id = ?');
$stmt->execute([$id]);

echo json_encode(['success' => true]);