<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

$stmt = $pdo->prepare("SELECT id, name FROM folders WHERE user_id = ?");
$stmt->execute([$userId]);
$folders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Upload</title></head>
<body>
  <h1>Datei hochladen</h1>
  <?php if (isset($_GET['success'])): ?>
    <p style="color:green;">Upload erfolgreich!</p>
  <?php endif; ?>
  <form action="handle_upload.php" method="post" enctype="multipart/form-data">
    <label for="file">Datei:</label><input type="file" name="file" id="file" required><br>
    <label for="subject">Subject:</label><input type="text" name="subject" id="subject"><br>
    <label for="description">Beschreibung:</label><textarea name="description" id="description"></textarea><br>
    <label for="folder">Ordner:</label><select name="folder_id" id="folder">
      <option value="">-- Root --</option>
      <?php foreach ($folders as $f): ?>
        <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['name']); ?></option>
      <?php endforeach; ?>
    </select><br>
    <label>Sichtbar für alle?<input type="radio" name="is_public" value="1"></label>
    <label>Nur privat<input type="radio" name="is_public" value="0" checked></label><br>
    <button type="submit">Hochladen</button>
  </form>
</body>
</html>