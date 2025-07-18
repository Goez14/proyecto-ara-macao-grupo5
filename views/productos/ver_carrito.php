<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
$descuentoTotal = 0;
$envioGratis = false;
$valorPunto = 100; // Ajustado para que sea más razonable
$puntosGanados = 0;

function aplicarDescuento($pdo, $producto_id, $precio_unitario) {
  $stmt = $pdo->prepare("SELECT porcentaje FROM descuentos WHERE producto_id = ? AND activo = 1 AND CURDATE() BETWEEN fecha_inicio AND fecha_fin");
  $stmt->execute([$producto_id]);
  $descuento = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($descuento) {
    return ($precio_unitario * $descuento['porcentaje']) / 100;
  }
  return 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🛒 Mi Carrito</title>
  <link rel="stylesheet" href="../../assets/css/ver_carrito.css">
</head>
<body>
  <div class="carrito-container">
    <h2>🛒 Carrito de Compras</h2>

    <?php if (empty($carrito)): ?>
      <p>Tu carrito está vacío.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio Original</th>
            <th>Descuento</th>
            <th>Precio Final</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($carrito as $producto_id => $item): ?>
            <?php
              $descuento = aplicarDescuento($pdo, $producto_id, $item['precio']);
              $precioFinal = $item['precio'] - $descuento;
              $subtotal = $precioFinal * $item['cantidad'];
              $subtotalOriginal = $item['precio'] * $item['cantidad'];
              $descuentoTotal += ($item['precio'] - $precioFinal) * $item['cantidad'];
              $total += $subtotal;
            ?>
            <tr>
              <td><?= htmlspecialchars($item['nombre']) ?></td>
              <td>$<?= number_format($item['precio'], 2) ?></td>
              <td><?= $descuento > 0 ? '$' . number_format($descuento, 2) : '—' ?></td>
              <td>$<?= number_format($precioFinal, 2) ?></td>
              <td><?= $item['cantidad'] ?></td>
              <td>$<?= number_format($subtotal, 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php
        if ($total >= 150000) {
          $envioGratis = true;
        }
        $puntosGanados = floor($total / 1000);
      ?>

      <div class="resumen-carrito">
        <p><b>Descuento total aplicado:</b> $<?= number_format($descuentoTotal, 2) ?></p>
        <p><b>Envío:</b> <?= $envioGratis ? '<span style="color: green;">Gratis 🎁</span>' : '$10,000.00' ?></p>
        <p><b>Subtotal sin descuentos:</b> $<?= number_format($subtotalOriginal, 2) ?></p>
        <p><b>Total:</b> $<?= number_format($total + ($envioGratis ? 0 : 10000), 2) ?></p>
        <p><b>Puntos que ganarás:</b> <?= $puntosGanados ?> 🪙</p>

        <form method="POST" action="../../controllers/crear_pedido.php">
          <button type="submit" class="btn-confirmar">✅ Confirmar Pedido</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
