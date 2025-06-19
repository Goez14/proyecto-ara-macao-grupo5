<!-- views/usuario/perfil/dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../../../auth/login.php');
  exit();
}
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Cliente</title>
  <link rel="stylesheet" href="../../../assets/css/dashboard_cliente.css">
</head>
<body>
  <div class="dashboard-container">
    
    <aside class="sidebar">
      <h3>Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></h3>
      <ul>
        <li><a href="perfil.php">👤 Mi Perfil</a>
        <li><a href="../../productos/productos_cliente.php">🛍 Ver Productos</a></li>
        <li><a href="../../productos/personalizacion.php">🎨 Personalizar Gragea</a></li>
        <li><a href="../../pedidos/mis_pedidos.php">📦 Mis Pedidos</a></li>
        <li><a href="../../soporte/contacto.php">📩 Soporte</a></li>
        <li><a href="../../../auth/logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </aside>

    <main class="contenido">
      <h2>Panel del Cliente</h2>
      <p>Aquí verás tus productos, pedidos, personalizaciones y más.</p>
      <!-- Aquí puedes agregar tarjetas o widgets en el futuro -->
    </main>

  </div>
</body>
</html>
