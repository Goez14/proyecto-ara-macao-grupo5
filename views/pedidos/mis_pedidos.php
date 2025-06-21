<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';
$cliente_id = $_SESSION['usuario']['id'];

// Obtener pedidos del cliente con nombre del producto y detalle
$sql = "SELECT d.id, pr.nombre AS producto, d.cantidad, d.precio_unitario, 
               (d.cantidad * d.precio_unitario) AS subtotal, p.fecha, p.estado 
        FROM pedidos p
        JOIN detalle_pedido d ON p.id = d.pedido_id
        JOIN productos pr ON d.producto_id = pr.id
        WHERE p.usuario_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$cliente_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📦 Mis Pedidos</title>
  <link rel="stylesheet" href="../../assets/css/pedidos_cliente.css">
</head>
<body>
  <div class="pedidos-container">
    <h2>📦 Mis Pedidos</h2>

    <?php if (empty($pedidos)): ?>
      <p>No has realizado ningún pedido todavía.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
            <th>Estado</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pedidos as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['producto']) ?></td>
            <td><?= $p['cantidad'] ?></td>
            <td>$<?= number_format($p['precio_unitario'], 2) ?></td>
            <td>$<?= number_format($p['subtotal'], 2) ?></td>
            <td><span class="estado estado-<?= strtolower($p['estado']) ?>"><?= ucfirst(str_replace('_', ' ', $p['estado'])) ?></span></td>
            <td><?= $p['fecha'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
