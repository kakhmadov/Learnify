<?php
// upload.php
require __DIR__ . '/db.php';

// 1. Ordner aus DB laden, um sie im Select anzuzeigen
$stmt = $pdo->prepare("SELECT id, name FROM folders WHERE user_id = ?");
$stmt->execute([ /* hier eure User-ID, z.B. aus Session */ 1 ]);
$folders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Datei hochladen – Learnify</title>
</head>
<body>
  <h1>Datei hochladen</h1>
  <form action="handle_upload.php" method="post" enctype="multipart/form-data">
    
    <label for="file">Datei auswählen:</label><br>
    <input type="file" name="file" id="file" required><br><br>
    
    <label for="subject">Subject:</label><br>
    <input type="text" name="subject" id="subject" maxlength="100"><br><br>
    
    <label for="description">Beschreibung:</label><br>
    <textarea name="description" id="description" rows="4"></textarea><br><br>
    
    <label for="folder">Ordner:</label><br>
    <select name="folder_id" id="folder">
      <option value="">-- Hauptbereich --</option>
      <?php foreach ($folders as $f): ?>
        <option value="<?= htmlspecialchars($f['id']) ?>">
          <?= htmlspecialchars($f['name']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>
    
    <label>Sichtbarkeit:</label><br>
    <input type="radio" id="public"  name="is_public" value="1">
    <label for="public">Öffentlich</label><br>
    <input type="radio" id="private" name="is_public" value="0" checked>
    <label for="private">Privat</label><br><br>
    
    <button type="submit">Hochladen</button>
  </form>
</body>
</html>
