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

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

parse_str(file_get_contents('php://input'), $data);
$id = $data['folder_id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$sql = 'DELETE FROM folders WHERE id = :id AND user_id = :u';
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':u' => $userId]);
echo json_encode(['success' => true]);