<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'empleado') {
  header('Location: ../../auth/login.php');
  exit();
}
require_once '../../config/database.php';
$empleado = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Empleado</title>
  <link rel="stylesheet" href="../../assets/css/dashboard_empleado.css">

</head>
<body>
<div class="dashboard-container">
  <aside class="sidebar">
    <h3>👷 Empleado: <?= htmlspecialchars($empleado['nombre']) ?></h3>
    <ul>
      <li><a href="perfil/perfil.php">👤 Mi Perfil</a></li>
      <li><a href="../pedidos/ver_pedidos.php">📦 Pedidos Pendientes</a></li>
      <li><a href="../productos/personalizacion_empleado.php">🧪 Ver Personalizaciones</a></li>
      <li><a href="subir_producto.php">📤 Subir Producto</a></li>
      <li><a href="../soporte/ver_mensajes.php">📩 Ver Soporte</a></li>
      <li><a href="../../auth/logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>
  <main class="contenido">
    <h2>Panel del Empleado</h2>
    <p>Aquí puedes consultar y preparar los pedidos, revisar personalizaciones y más.</p>
  </main>
</div>
</body>
</html>
