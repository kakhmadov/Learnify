<?php
require __DIR__ . '/db.php';
session_start();

$fileId = $_GET['file_id'];
$stmt   = $pdo->prepare("SELECT c.*, u.username FROM comments c
                        JOIN users u ON c.user_id = u.id
                        WHERE file_id = ?
                        ORDER BY created_at ASC");
$stmt->execute([$fileId]);
$comments = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($comments);