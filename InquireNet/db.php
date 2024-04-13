<?php
$db_host = "localhost";
$db_user = "tjl914";
$db_pwd = "200392556";
$db_db = "tjl914";
$charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;dbname=$db_db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pwd, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
