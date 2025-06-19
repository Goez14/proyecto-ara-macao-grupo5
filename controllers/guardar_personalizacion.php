<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario'])) {
  http_response_code(403);
  echo "No autorizado";
  exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$color = $_POST['color'] ?? '';
$tamano = $_POST['tamano'] ?? 1;

try {
  $stmt = $pdo->prepare("INSERT INTO personalizaciones (usuario_id, color, tamano) VALUES (?, ?, ?)");
  $stmt->execute([$usuario_id, $color, $tamano]);
  echo "Personalización guardada correctamente.";
} catch (PDOException $e) {
  http_response_code(500);
  echo "Error al guardar: " . $e->getMessage();
}
