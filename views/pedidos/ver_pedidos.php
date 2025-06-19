<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'empleado') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

// Actualizar estado del pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['estado'])) {
  $pedido_id = $_POST['pedido_id'];
  $nuevo_estado = $_POST['estado'];

  $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
  $stmt->execute([$nuevo_estado, $pedido_id]);
}

// Traer todos los pedidos
$sql = "SELECT p.id, pr.nombre AS producto, p.cantidad, p.estado, p.fecha, u.nombre AS cliente
        FROM pedidos p
        JOIN productos pr ON p.producto_id = pr.id
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";
$pedidos = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📦 Pedidos en Producción</title>
  <link rel="stylesheet" href="../../assets/css/ver_pedidos.css">
</head>

<body>
<div class="contenedor">
  <h2>📦 Pedidos en Producción</h2>

  <?php if (count($pedidos) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Actualizar Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pedidos as $pedido): ?>
          <tr>
            <td><?= $pedido['id'] ?></td>
            <td><?= htmlspecialchars($pedido['cliente']) ?></td>
            <td><?= htmlspecialchars($pedido['producto']) ?></td>
            <td><?= $pedido['cantidad'] ?></td>
            <td><?= $pedido['estado'] ?></td>
            <td><?= $pedido['fecha'] ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                <select name="estado">
                  <option <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>pendiente</option>
                  <option <?= $pedido['estado'] == 'en_produccion' ? 'selected' : '' ?>>en_produccion</option>
                  <option <?= $pedido['estado'] == 'enviado' ? 'selected' : '' ?>>enviado</option>
                  <option <?= $pedido['estado'] == 'entregado' ? 'selected' : '' ?>>entregado</option>
                  <option <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>cancelado</option>
                </select>
                <button type="submit">Actualizar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No hay pedidos en el sistema.</p>
  <?php endif; ?>

  <a href="../usuario/empleado.php">⬅ Volver al Panel</a>
</div>
</body>
</html>
