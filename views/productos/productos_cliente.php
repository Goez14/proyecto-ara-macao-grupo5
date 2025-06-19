<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}
require_once '../../config/database.php';
$cliente = $_SESSION['usuario'];

// Registrar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
  $producto_id = $_POST['producto_id'];
  $usuario_id = $cliente['id'];

  // Verificar si hay stock
  $stmtStock = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
  $stmtStock->execute([$producto_id]);
  $stock = $stmtStock->fetchColumn();

  if ($stock > 0) {
    // Insertar pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, producto_id, cantidad, estado) VALUES (?, ?, 1, 'Pendiente')");
    $stmt->execute([$usuario_id, $producto_id]);

    // Reducir el stock
    $pdo->prepare("UPDATE productos SET stock = stock - 1 WHERE id = ?")->execute([$producto_id]);

    $mensaje = "¡Pedido realizado exitosamente!";
  } else {
    $mensaje = "❌ No hay stock disponible para este producto.";
  }
}

// Obtener productos
$stmt = $pdo->query("SELECT * FROM productos");
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos Disponibles</title>
  <link rel="stylesheet" href="../../assets/css/productos_cliente.css">
</head>
<body>

<div class="productos-container">
  <h2>🛍 Productos Disponibles</h2>

  <?php if (isset($mensaje)) echo "<p style='color: green;'>$mensaje</p>"; ?>

  <div class="productos-grid">
    <?php foreach ($productos as $producto): ?>
      <div class="producto">
        <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Producto">
        <h4><?= htmlspecialchars($producto['nombre']) ?></h4>
        <p><?= htmlspecialchars($producto['descripcion']) ?></p>
        <p><strong>💰 Precio:</strong> $<?= $producto['precio'] ?></p>
        <p><strong>📦 Stock:</strong> <?= $producto['stock'] ?></p>

        <?php if ($producto['stock'] > 0): ?>
          <form method="POST">
            <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
            <button type="submit">Hacer Pedido</button>
          </form>
        <?php else: ?>
          <p style="color:red;">Agotado</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
