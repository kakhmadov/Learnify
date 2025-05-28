<?php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql  = "INSERT INTO users (username, email, password_hash) VALUES (:user, :email, :pass)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user'  => $username,
        ':email' => $email,
        ':pass'  => $hash,
    ]);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    header('Location: upload.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Register – Learnify</title>
</head>
<body>
  <h1>Registrieren</h1>
  <form method="post">
    <input name="username" placeholder="Username" required><br>
    <input name="email" type="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
  </form>
</body>
</html>