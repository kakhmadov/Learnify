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

// Filter parameters
$fileType   = $_GET['file_type']   ?? '';
$subjectKey = $_GET['subject']     ?? '';
$visibility = $_GET['visibility']  ?? '';
$folderId   = $_GET['folder_id']   ?? '';

$sql = 'SELECT f.id, f.filename, f.file_type, f.subject, f.is_public, f.uploaded_at, fo.name AS folder
        FROM files f
        LEFT JOIN folders fo ON f.folder_id = fo.id
        WHERE f.user_id = :uid';
$params = [':uid' => $userId];

if ($fileType) {
    $sql .= ' AND f.file_type = :ft';
    $params[':ft'] = $fileType;
}
if ($subjectKey) {
    $sql .= ' AND f.subject LIKE :sub';
    $params[':sub'] = "%{$subjectKey}%";
}
if ($visibility === 'public') {
    $sql .= ' AND f.is_public = 1';
} elseif ($visibility === 'private') {
    $sql .= ' AND f.is_public = 0';
}
if ($folderId) {
    $sql .= ' AND f.folder_id = :fid';
    $params[':fid'] = $folderId;
}

$sql .= ' ORDER BY f.uploaded_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$files = $stmt->fetchAll();

echo json_encode($files);