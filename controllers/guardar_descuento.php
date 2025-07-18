<?php
session_start();
require_once '../config/database.php';

// Verifica si el usuario tiene permiso
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['admin', 'empleado'])) {
  header("Location: ../auth/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $producto_id = $_POST['producto_id'] ?? null;
  $porcentaje = $_POST['porcentaje'] ?? null;
  $fecha_inicio = $_POST['fecha_inicio'] ?? null;
  $fecha_fin = $_POST['fecha_fin'] ?? null;
  $activo = isset($_POST['activo']) ? 1 : 0;

  // Validar
  if (!$producto_id || $porcentaje === null || !$fecha_inicio || !$fecha_fin) {
    echo "Datos incompletos.";
    exit();
  }

  try {
    // Verificamos si ya hay un descuento para ese producto
    $stmt = $pdo->prepare("SELECT id FROM descuentos WHERE producto_id = ?");
    $stmt->execute([$producto_id]);
    $existe = $stmt->fetch();

    if ($existe) {
      // Actualizar
      $stmt = $pdo->prepare("UPDATE descuentos 
                             SET porcentaje = ?, fecha_inicio = ?, fecha_fin = ?, activo = ? 
                             WHERE producto_id = ?");
      $stmt->execute([$porcentaje, $fecha_inicio, $fecha_fin, $activo, $producto_id]);
    } else {
      // Insertar
      $stmt = $pdo->prepare("INSERT INTO descuentos 
        (producto_id, porcentaje, fecha_inicio, fecha_fin, activo) 
        VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$producto_id, $porcentaje, $fecha_inicio, $fecha_fin, $activo]);
    }

    header("Location: ../views/productos/gestionar_descuentos.php?success=1");
    exit();

  } catch (Exception $e) {
    echo "Error al guardar descuento: " . htmlspecialchars($e->getMessage());
  }
} else {
  echo "Método no permitido.";
}
