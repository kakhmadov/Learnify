<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = $_POST['folder_id'];
    $name = trim($_POST['name']);

    $stmt = $pdo->prepare("UPDATE folders SET name = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$name, $id, $userId]);

    header('Location: list_folders.php');
    exit;
}