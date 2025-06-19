<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

// Actualizar estado del pedido si se envió formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['estado'])) {
  $pedido_id = $_POST['pedido_id'];
  $nuevo_estado = $_POST['estado'];

  $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
  $stmt->execute([$nuevo_estado, $pedido_id]);
}

// Obtener todos los pedidos
$sql = "SELECT p.id, p.fecha, p.estado, p.total, u.nombre AS cliente
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";

$pedidos = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar Pedidos</title>
  <link rel="stylesheet" href="../../assets/css/gestionar_pedidos.css">
</head>
<body>
<div class="contenedor">
  <h2>📦 Gestionar Pedidos</h2>

  <?php if (count($pedidos) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Cambiar Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pedidos as $pedido): ?>
          <tr>
            <td><?= $pedido['id'] ?></td>
            <td><?= htmlspecialchars($pedido['cliente']) ?></td>
            <td><?= $pedido['fecha'] ?></td>
            <td>$<?= number_format($pedido['total'], 2) ?></td>
            <td><?= $pedido['estado'] ?></td>
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
    <p>No hay pedidos registrados.</p>
  <?php endif; ?>

  <a class="volver" href="../admin/admin.php">⬅ Volver al Dashboard</a>
</div>
</body>
</html>
