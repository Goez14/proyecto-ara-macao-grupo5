<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

// Obtener los logs
$sql = "SELECT l.id, u.nombre AS usuario, u.rol, l.accion, l.fecha
        FROM logs l
        LEFT JOIN usuarios u ON l.usuario_id = u.id
        ORDER BY l.fecha DESC";

$logs = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📊 Historial de Logs</title>
  <link rel="stylesheet" href="../../assets/css/ver_logs.css">
</head>
<body>
  <div class="contenedor">
    <h2>📋 Historial de Actividades</h2>

    <?php if (count($logs) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acción</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $log): ?>
            <tr>
              <td><?= $log['id'] ?></td>
              <td><?= htmlspecialchars($log['usuario'] ?? 'Desconocido') ?></td>
              <td><?= htmlspecialchars($log['rol'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($log['accion']) ?></td>
              <td><?= $log['fecha'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No hay registros de actividades.</p>
    <?php endif; ?>

    <a class="volver" href="../admin/admin.php">⬅ Volver al Panel</a>
  </div>
</body>
</html>
