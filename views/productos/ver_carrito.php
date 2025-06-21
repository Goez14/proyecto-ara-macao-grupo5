<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🛒 Carrito de Compras</title>
  <link rel="stylesheet" href="../../assets/css/ver_carrito.css">
</head>
<body>
  <div class="carrito-container">
    <h2>🛒 Tu Carrito de Compras</h2>

    <?php if (empty($carrito)): ?>
      <p>No tienes productos en el carrito.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($carrito as $id => $item): ?>
            <?php $subtotal = $item['precio'] * $item['cantidad']; ?>
            <?php $total += $subtotal; ?>
            <tr>
              <td><?= htmlspecialchars($item['nombre']) ?></td>
              <td>$<?= number_format($item['precio'], 2) ?></td>
              <td><?= $item['cantidad'] ?></td>
              <td>$<?= number_format($subtotal, 2) ?></td>
              <td>
                <form method="POST" action="../../controllers/eliminar_del_carrito.php" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <button type="submit" class="btn-eliminar">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h3>Total: $<?= number_format($total, 2) ?></h3>

      <form action="../../controllers/crear_pedido.php" method="POST">
        <button type="submit" class="btn-comprar">Finalizar Pedido</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>


