<?php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$fileId  = $data['file_id'] ?? null;
$content = trim($data['content'] ?? '');
$parent  = $data['parent_id'] ?? null;

if (!$fileId || !$content) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$sql = 'INSERT INTO comments (file_id, user_id, parent_comment_id, content)
        VALUES (:fid, :uid, :pid, :cont)';
$stmt = $pdo->prepare($sql);
$stmt->execute([':fid'=> $fileId, ':uid'=> $userId, ':pid'=> $parent, ':cont'=> $content]);

echo json_encode(['success' => true, 'comment_id' => $pdo->lastInsertId()]);