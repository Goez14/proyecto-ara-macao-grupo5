<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['admin', 'empleado'])) {
  header("Location: ../auth/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = $_POST['id'];

  try {
    $stmt = $pdo->prepare("DELETE FROM descuentos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ../views/productos/gestionar_descuentos.php?success=1");
    exit();
  } catch (Exception $e) {
    echo "Error al eliminar: " . htmlspecialchars($e->getMessage());
  }
} else {
  echo "Acceso no permitido.";
}
