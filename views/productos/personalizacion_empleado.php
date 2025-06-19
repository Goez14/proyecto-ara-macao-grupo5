<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'empleado') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

// Consultamos todas las personalizaciones con el nombre del cliente
$sql = "SELECT p.color, p.tamano, p.fecha, u.nombre AS cliente
        FROM personalizaciones p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";

$stmt = $pdo->query($sql);
$personalizaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Personalizaciones de Clientes</title>
  <link rel="stylesheet" href="../../assets/css/personalizacion_empleado.css">
</head>
<body>
  <div class="contenedor">
    <h2>🧾 Personalizaciones de Clientes</h2>
    <?php if (count($personalizaciones) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Color</th>
            <th>Tamaño</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($personalizaciones as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['cliente']) ?></td>
              <td><?= htmlspecialchars($row['color']) ?></td>
              <td><?= htmlspecialchars($row['tamano']) ?></td>
              <td><?= htmlspecialchars($row['fecha']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No hay personalizaciones registradas aún.</p>
    <?php endif; ?>
    <a class="volver" href="../usuario/empleado.php">⬅ Volver al Dashboard</a>
  </div>
</body>
</html>
