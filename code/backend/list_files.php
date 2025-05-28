<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

$stmt = $pdo->prepare(
    "SELECT f.id, f.filename, f.is_public, fo.name AS folder
     FROM files f
     LEFT JOIN folders fo ON f.folder_id = fo.id
     WHERE f.user_id = ?"
);
$stmt->execute([$userId]);
$files = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Dateiliste</title></head>
<body>
  <h1>Meine Dateien</h1>
  <a href="upload.php">Upload</a>
  <ul>
    <?php foreach ($files as $f): ?>
      <li>
        <?php echo htmlspecialchars($f['filename']); ?>
        (<?php echo $f['folder'] ?: 'Root'; ?>) - 
        <a href="download.php?id=<?php echo $f['id']; ?>">Download</a> |
        <a href="delete_file.php?id=<?php echo $f['id']; ?>">Delete</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>