<?php
require __DIR__ . '/db.php';
session_start();

$id     = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

$stmt = $pdo->prepare("SELECT filename, user_id FROM files WHERE id = ?");
$stmt->execute([$id]);
$f    = $stmt->fetch();

if (!$f || $f['user_id'] !== $userId) {
    die('Kein Zugriff.');
}

$path = __DIR__ . '/uploads/' . $userId . '/' . $f['filename'];

if (!file_exists($path)) {
    die('Datei nicht gefunden.');
}

header('Content-Disposition: attachment; filename="' . basename($f['filename']) . '"');
readfile($path);
exit;