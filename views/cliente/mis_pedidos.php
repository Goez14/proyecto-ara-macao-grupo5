<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';
$cliente_id = $_SESSION['usuario']['id'];

// Obtener pedidos del cliente con nombre del producto
$sql = "SELECT p.id, pr.nombre AS producto, p.cantidad, p.estado, p.fecha
        FROM pedidos p
        JOIN productos pr ON p.producto_id = pr.id
        WHERE p.usuario_id = ?
        ORDER BY p.fecha DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$cliente_id]);
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Pedidos</title>
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
          <td><?= $p['estado'] ?></td>
          <td><?= $p['fecha'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
</html>
