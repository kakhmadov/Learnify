<?php
session_start();
require __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die('Bitte einloggen.');
}

// Filter-Parameter aus GET
$fileType   = $_GET['file_type']   ?? '';
$subjectKey = $_GET['subject']     ?? '';\$visibility = $_GET['visibility'] ?? '';
$folderId   = $_GET['folder_id']   ?? '';

// Basis-Query
$sql = "SELECT f.id, f.filename, f.file_type, f.subject, f.is_public, fo.name AS folder
        FROM files f
        LEFT JOIN folders fo ON f.folder_id = fo.id
        WHERE f.user_id = :uid";
$params = [':uid' => $userId];

// Erweiterung durch Filter
if ($fileType) {
    $sql .= " AND f.file_type = :ft";
    $params[':ft'] = $fileType;
}
if ($subjectKey) {
    $sql .= " AND f.subject LIKE :sub";
    $params[':sub'] = "%{$subjectKey}%";
}
if ($visibility === 'public') {
    $sql .= " AND f.is_public = 1";
} elseif ($visibility === 'private') {
    $sql .= " AND f.is_public = 0";
}
if ($folderId) {
    $sql .= " AND f.folder_id = :fid";
    $params[':fid'] = $folderId;
}

$sql .= " ORDER BY f.uploaded_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$files = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Dateiliste</title></head>
<body>
  <h1>Meine Dateien</h1>

  <!-- Filter-Formular -->
  <form method="get">
    <label for="file_type">Typ:</label>
    <select name="file_type" id="file_type">
      <option value="">Alle</option>
      <?php foreach (['docx','pdf','pptx','zip','png','jpg','mp4','mp3'] as $type): ?>
        <option value="<?= $type ?>" <?= $fileType === $type ? 'selected' : '' ?>><?= strtoupper($type) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="subject">Subject:</label>
    <input type="text" name="subject" id="subject" value="<?= htmlspecialchars($subjectKey) ?>">

    <label for="visibility">Sichtbarkeit:</label>
    <select name="visibility" id="visibility">
      <option value="">Alle</option>
      <option value="public" <?= $visibility === 'public' ? 'selected' : '' ?>>Öffentlich</option>
      <option value="private" <?= $visibility === 'private' ? 'selected' : '' ?>>Privat</option>
    </select>

    <label for="folder_id">Ordner:</label>
    <select name="folder_id" id="folder_id">
      <option value="">Alle</option>
      <?php
      // Ordner-Liste laden
      $fstmt = $pdo->prepare("SELECT id, name FROM folders WHERE user_id = ?");
      $fstmt->execute([$userId]);
      $folders = $fstmt->fetchAll();
      foreach ($folders as $f): ?>
        <option value="<?= $f['id'] ?>" <?= $folderId == $f['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($f['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit">Filtern</button>
  </form>

  <ul>
    <?php foreach ($files as $f): ?>
      <li>
        <?= htmlspecialchars($f['filename']) ?>
        [<?= htmlspecialchars($f['file_type']) ?>]
        (<?= $f['folder'] ?: 'Root' ?>)
        <?= $f['is_public'] ? '(Öffentlich)' : '(Privat)' ?>
        - <a href="download.php?id=<?= $f['id'] ?>">Download</a>
        | <a href="delete_file.php?id=<?= $f['id'] ?>">Löschen</a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>