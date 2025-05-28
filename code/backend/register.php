<?php
header('Content-Type: application/json');
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = 'INSERT INTO users (username, email, password_hash) VALUES (:user, :email, :pass)';
$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([':user' => $username, ':email' => $email, ':pass' => $hash]);
    $_SESSION['user_id'] = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'user_id' => $_SESSION['user_id']]);
} catch (PDOException $e) {
    http_response_code(409);
    echo json_encode(['error' => 'User already exists']);
}