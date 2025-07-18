<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';
$cliente_id = $_SESSION['usuario']['id'];

// Obtener pedidos del cliente con nombre del producto y detalle
$sql = "SELECT d.id, d.producto_id, pr.nombre AS producto, d.cantidad, d.precio_unitario, 
               (d.cantidad * d.precio_unitario) AS subtotal, p.fecha, p.estado, p.id AS pedido_id 
        FROM pedidos p
        JOIN detalle_pedido d ON p.id = d.pedido_id
        JOIN productos pr ON d.producto_id = pr.id
        WHERE p.usuario_id = ?
        ORDER BY p.fecha DESC";
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

    <!-- Mensajes de éxito -->
    <?php if (isset($_GET['pedido']) && $_GET['pedido'] === 'exito'): ?>
      <?php
        $stmtP = $pdo->prepare("SELECT total FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 1");
        $stmtP->execute([$cliente_id]);
        $ultimo = $stmtP->fetch(PDO::FETCH_ASSOC);
        $puntosGanados = floor($ultimo['total'] / 1000);
      ?>
      <p class="exito" style="color: green;">
        ✅ ¡Pedido realizado con éxito! Has ganado <b><?= $puntosGanados ?></b> punto<?= $puntosGanados !== 1 ? 's' : '' ?> por tu compra. 🎉
      </p>
      <?php if (isset($_GET['desc']) && $_GET['desc'] === '1'): ?>
        <p class="exito" style="color: blue;">💸 ¡Tu compra incluyó un <b>descuento automático</b> por superar $50.000 COP!</p>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['resena']) && $_GET['resena'] === 'ok'): ?>
      <p class="exito">✅ ¡Gracias por calificar el producto!</p>
    <?php endif; ?>

    <?php if (isset($_GET['cancelado'])): ?>
      <?php if ($_GET['cancelado'] === 'ok'): ?>
        <p class="exito" style="color: green;">✅ Pedido cancelado correctamente.</p>
      <?php elseif ($_GET['cancelado'] === 'no-permitido'): ?>
        <p class="error" style="color: red;">❌ Este pedido no se puede cancelar.</p>
      <?php else: ?>
        <p class="error" style="color: red;">⚠️ Ocurrió un error al cancelar el pedido.</p>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Tabla de pedidos -->
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
            <th>Acción</th>
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
            <td>
              <span class="estado estado-<?= strtolower($p['estado']) ?>">
                <?= ucfirst(str_replace('_', ' ', $p['estado'])) ?>
              </span>
            </td>
            <td><?= $p['fecha'] ?></td>
            <td>
              <?php if (strtolower($p['estado']) === 'pendiente'): ?>
                <form method="POST" action="../../controllers/cancelar_pedido.php" onsubmit="return confirm('¿Estás seguro de cancelar este pedido?');">
                  <input type="hidden" name="pedido_id" value="<?= $p['pedido_id'] ?>">
                  <button type="submit" class="btn-cancelar">Cancelar</button>
                </form>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>

          <?php
            if (strtolower($p['estado']) === 'entregado') {
              $check = $pdo->prepare("SELECT COUNT(*) FROM resenas WHERE usuario_id = ? AND producto_id = ?");
              $check->execute([$cliente_id, $p['producto_id']]);
              $ya_calificado = $check->fetchColumn();

              if (!$ya_calificado): ?>
                <tr>
                  <td colspan="8" style="background: #f9f9f9;">
                    <form method="POST" action="../../controllers/guardar_resena.php">
                      <input type="hidden" name="producto_id" value="<?= $p['producto_id'] ?>">
                      <label>⭐ Califica este producto:</label>
                      <select name="calificacion" required>
                        <option value="">Seleccionar</option>
                        <option value="1">1 estrella</option>
                        <option value="2">2 estrellas</option>
                        <option value="3">3 estrellas</option>
                        <option value="4">4 estrellas</option>
                        <option value="5">5 estrellas</option>
                      </select><br>
                      <textarea name="comentario" placeholder="Escribe tu opinión..." rows="2" cols="60"></textarea><br>
                      <button type="submit">Enviar reseña</button>
                    </form>
                  </td>
                </tr>
              <?php endif;
            }
          ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
