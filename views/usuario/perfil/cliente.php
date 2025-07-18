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
  SELECT p.id, p.nombre, p.imagen, p.precio, SUM(dp.cantidad) AS total_vendido
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
  <div class="panel-flotante">
    
    <!-- Botón del carrito -->
    <a href="../../productos/ver_carrito.php" class="btn-carrito" title="Ver Carrito">🛒</a>

    <!-- Panel izquierdo -->
    <aside class="sidebar">
      <div class="logo-container">
      <img src="../../../assets/img/logo.png" alt="Logo Ara Macao">
      </div>

      <h3>Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></h3>
      <ul>
        <li><a href="perfil.php">👤 Mi Perfil</a></li>
        <li><a href="../../productos/productos_cliente.php">🛍 Ver Productos</a></li>
        <li><a href="../../productos/personalizacion.php">🎨 Personalizar Gragea</a></li>
        <li><a href="../../pedidos/mis_pedidos.php">📦 Mis Pedidos</a></li>
        <li><a href="../../soporte/contacto.php">📩 Soporte</a></li>
        <li><a href="../../../auth/logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </aside>

    <!-- Panel derecho -->
    <main class="contenido">
      <h2>Panel del Cliente</h2>
      <p>Aquí verás tus productos, pedidos, personalizaciones y más.</p>

      <section class="productos-populares">
        <h3 class="titulo-mas-comprados">🔥 Productos más comprados</h3>
        <div class="productos-grid">
          <?php if (count($productos_mas_comprados) > 0): ?>
            <?php foreach ($productos_mas_comprados as $producto): ?>
              <div class="producto-popular">
                <img src="../../../assets/img/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <h4><?= htmlspecialchars($producto['nombre']) ?></h4>
                <p><strong>$<?= number_format($producto['precio'], 2) ?></strong></p>
                <p>🛍 Vendido: <?= $producto['total_vendido'] ?> veces</p>
                <a class="ver-resenas-link" href="../../productos/detalle_producto.php?id=<?= $producto['id'] ?>">
                  📋 Ver comentarios y reseñas
                </a>
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

