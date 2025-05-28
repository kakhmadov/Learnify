<?php
$config = require __DIR__ . '/config.php';
$dbCfg  = $config['db'];

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $dbCfg['host'],
    $dbCfg['dbname'],
    $dbCfg['charset']
);

try {
    $pdo = new PDO($dsn, $dbCfg['user'], $dbCfg['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('Datenbank-Verbindungsfehler: ' . $e->getMessage());
}
