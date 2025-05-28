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

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid upload']);
    exit;
}

$allowed = ['docx','pdf','pptx','zip','png','jpg','mp4','mp3'];
$tmp     = $_FILES['file']['tmp_name'];
$name    = basename($_FILES['file']['name']);
$ext     = strtolower(pathinfo($name, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed, true)) {
    http_response_code(415);
    echo json_encode(['error' => 'Unsupported Media Type']);
    exit;
}

$storageDir = __DIR__ . '/uploads/' . $userId;
if (!is_dir($storageDir)) mkdir($storageDir, 0755, true);

$destName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
$destPath = $storageDir . '/' . $destName;

if (!move_uploaded_file($tmp, $destPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}

$subject     = $_POST['subject']     ?? null;
$description = $_POST['description'] ?? null;
$isPublic    = isset($_POST['is_public']) ? (int)$_POST['is_public'] : 0;
$folderId    = $_POST['folder_id']   ?: null;

$sql = 'INSERT INTO files (user_id, folder_id, filename, file_type, subject, description, is_public)
        VALUES (:uid, :fid, :fname, :ftype, :subject, :desc, :pub)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':uid'     => $userId,
    ':fid'     => $folderId,
    ':fname'   => $destName,
    ':ftype'   => $ext,
    ':subject' => $subject,
    ':desc'    => $description,
    ':pub'     => $isPublic,
]);

echo json_encode(['success' => true, 'file_id' => $pdo->lastInsertId()]);