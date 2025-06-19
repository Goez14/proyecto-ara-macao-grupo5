<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

// Obtener todos los logs
$stmt = $pdo->query("SELECT l.*, u.nombre FROM logs l LEFT JOIN usuarios u ON l.usuario_id = u.id ORDER BY l.fecha DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📊 Ver Logs del Sistema</title>
  <link rel="stylesheet" href="../../assets/css/logs_admin.css">
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <h3>👑 Admin: <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h3>
      <ul>
        <li><a href="admin.php">🏠 Dashboard</a></li>
        <li><a href="ver_logs.php">📊 Ver Logs</a></li>
        <li><a href="../../auth/logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </aside>
    <main class="contenido">
      <h2>📋 Registros del Sistema</h2>
      <table>
        <thead>
          <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $log): ?>
            <tr>
              <td><?= htmlspecialchars($log['nombre'] ?? 'Desconocido') ?></td>
              <td><?= htmlspecialchars($log['accion']) ?></td>
              <td><?= $log['fecha'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>

