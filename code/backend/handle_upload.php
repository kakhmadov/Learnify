<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    die('Fehler beim Hochladen.');
}

$allowed = ['docx','pdf','pptx','zip','png','jpg','mp4','mp3'];
$tmp    = $_FILES['file']['tmp_name'];
$name   = basename($_FILES['file']['name']);
$ext    = strtolower(pathinfo($name, PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
    die('Dateityp nicht erlaubt.');
}

$storageDir = __DIR__ . '/uploads/' . $userId;
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}
$destName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
$destPath = $storageDir . '/' . $destName;

if (!move_uploaded_file($tmp, $destPath)) {
    die('Konnte Datei nicht speichern.');
}

$sql = "INSERT INTO files
    (user_id, folder_id, filename, file_type, subject, description, is_public)
  VALUES
    (:uid, :fid, :fname, :ftype, :subject, :desc, :pub)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':uid'     => $userId,
    ':fid'     => $_POST['folder_id'] ?: null,
    ':fname'   => $destName,
    ':ftype'   => $ext,
    ':subject' => $_POST['subject'] ?: null,
    ':desc'    => $_POST['description'] ?: null,
    ':pub'     => (int)($_POST['is_public'] ?? 0),
]);

header('Location: upload.php?success=1');
exit;
