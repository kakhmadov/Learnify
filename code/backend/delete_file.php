<?php
session_start();
require __DIR__ . '/db.php';

$id     = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

$stmt = $pdo->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);
f    = $stmt->fetch();

if ($f) {
    unlink(__DIR__ . '/uploads/' . $userId . '/' . $f['filename']);
    $pdo->prepare("DELETE FROM files WHERE id = ?")->execute([$id]);
}

header('Location: list_files.php');
exit;