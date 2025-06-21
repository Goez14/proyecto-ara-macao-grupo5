<!-- views/usuario/perfil/dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../../auth/login.php');
  exit();
}
$usuario = $_SESSION['usuario'];

require_once '../../../config/database.php';

// Consulta para obtener productos más comprados
$sql = "
  SELECT p.nombre, p.imagen, p.precio, SUM(dp.cantidad) AS total_vendido
  FROM detalle_pedido dp
  JOIN productos p ON dp.producto_id = p.id
  GROUP BY dp.producto_id
  ORDER BY total_vendido DESC
  LIMIT 5
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$productos_mas_comprados = $stmt->fetchAll();
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
        <li><a href="perfil.php">👤 Mi Perfil</a></li>
        <li><a href="../../productos/productos_cliente.php">🛍 Ver Productos</a></li>
        <li><a href="../../productos/personalizacion.php">🎨 Personalizar Gragea</a></li>
        <li><a href="../../pedidos/mis_pedidos.php">📦 Mis Pedidos</a></li>
        <li><a href="../../soporte/contacto.php">📩 Soporte</a></li>
        <li><a href="../../productos/ver_carrito.php">🛒 Mi Carrito</a></li>
        <li><a href="../../../auth/logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </aside>

    <main class="contenido">
      <h2>Panel del Cliente</h2>
      <p>Aquí verás tus productos, pedidos, personalizaciones y más.</p>

      <section class="productos-populares">
        <h3>🔥 Productos más comprados</h3>
        <div class="productos-grid">
          <?php if (count($productos_mas_comprados) > 0): ?>
            <?php foreach ($productos_mas_comprados as $producto): ?>
              <div class="producto-popular">
                <img src="../../../assets/img/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <h4><?= htmlspecialchars($producto['nombre']) ?></h4>
                <p>$<?= number_format($producto['precio'], 2) ?></p>
                <p>🛍 Vendido: <?= $producto['total_vendido'] ?> veces</p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No hay datos de productos vendidos aún.</p>
          <?php endif; ?>
        </div>
      </section>

    </main>
  </div>
</body>
</html>

