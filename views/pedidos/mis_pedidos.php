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
  <link rel="stylesheet" href="../../assets/css/mis_pedidos2.css">
</head>
<body>

<div class="pedidos-container">
  <h2>📦 Mis Pedidos</h2>

  <?php if (empty($pedidos)): ?>
    <p>No has realizado ningún pedido todavía.</p>
  <?php else: ?>
    <div class="pedidos-lista">
      <?php foreach ($pedidos as $p): ?>
        <div class="pedido-card">
          <h4>Pedido #<?= $p['id'] ?></h4>
          <p><strong>Producto:</strong> <?= htmlspecialchars($p['producto']) ?></p>
          <p><strong>Cantidad:</strong> <?= $p['cantidad'] ?></p>
          <p><strong>Estado:</strong> 
            <span class="estado <?= strtolower($p['estado']) ?>">
              <?= ucfirst(str_replace('_', ' ', $p['estado'])) ?>
            </span>
          </p>
          <p><strong>Fecha:</strong> <?= $p['fecha'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
