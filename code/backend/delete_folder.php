<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['folder_id'];

    $stmt = $pdo->prepare("DELETE FROM folders WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $userId]);

    header('Location: list_folders.php');
    exit;
}