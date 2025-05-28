<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name']);
    $parent = $_POST['parent_id'] ?: null;

    $sql  = "INSERT INTO folders (user_id, name, parent_folder_id) VALUES (:u, :n, :p)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':u' => $userId,
        ':n' => $name,
        ':p' => $parent,
    ]);

    header('Location: list_folders.php');
    exit;
}
?>
<form method="post">
  <input name="name" placeholder="Ordnername" required><br>
  <select name="parent_id">
    <option value="">-- Root --</option>
    <!-- Hier können vorhandene Ordner als Optionen ergänzt werden -->
  </select><br>
  <button type="submit">Ordner erstellen</button>
</form>