<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) die('Bitte einloggen.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileId  = $_POST['file_id'];
    $content = trim($_POST['content']);
    $parent  = $_POST['parent_id'] ?: null;

    $sql  = "INSERT INTO comments (file_id, user_id, parent_comment_id, content)
             VALUES (:fid, :uid, :pid, :cont)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fid' => $fileId,
        ':uid' => $userId,
        ':pid' => $parent,
        ':cont'=> $content,
    ]);
}
header('Location: list_files.php');
exit;