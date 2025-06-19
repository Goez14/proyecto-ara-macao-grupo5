<?php
$host = 'localhost';
$db   = 'ara_macoa_db';
$user = 'root'; // o el usuario que uses en tu servidor local
$pass = '';     // contraseña si tienes
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>


