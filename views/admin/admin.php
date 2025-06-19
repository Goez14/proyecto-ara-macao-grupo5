<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: ../../auth/login.php');
  exit();
}
require_once '../../config/database.php';
$admin = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="../../assets/css/dashboard_admin.css">
   <link rel="stylesheet" href="../../assets/css/logs_admin.css"> <!-- si aplica -->


</head>
<body>
<div class="dashboard-container">
  <aside class="sidebar">
    <h3>👑 Admin: <?= htmlspecialchars($admin['nombre']) ?></h3>
    <ul>
      <li><a href="../usuario/perfil/perfil.php">👤 Mi Perfil</a></li>
      <li><a href="usuarios.php">👥 Usuarios Registrados</a></li>
      <li><a href="../productos/personalizacion_admin.php">🎨 Ver Personalizaciones</a></li>
      <li><a href="../pedidos/gestionar_pedidos.php">📦 Gestionar Pedidos</a></li>
      <li><a href="../logs/ver_logs.php">📊 Ver Logs</a></li>
      <li><a href="../../auth/logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>
  <main class="contenido">
    <h2>Panel del Administrador</h2>
    <p>Desde aquí puedes administrar usuarios, pedidos y más.</p>
  </main>
</div>
</body>
</html>
