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
$name   = trim($data['name'] ?? '');
$parent = $data['parent_id'] ?? null;

if (!$name) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid folder name']);
    exit;
}

$sql = 'INSERT INTO folders (user_id, name, parent_folder_id) VALUES (:u, :n, :p)';
$stmt = $pdo->prepare($sql);
$stmt->execute([':u' => $userId, ':n' => $name, ':p' => $parent]);
$folderId = $pdo->lastInsertId();

echo json_encode(['success' => true, 'folder_id' => $folderId]);