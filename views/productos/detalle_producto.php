<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

if (!isset($_GET['id'])) {
  die("Producto no especificado.");
}

$producto_id = $_GET['id'];

// Obtener datos del producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
  die("Producto no encontrado.");
}

// Calificación promedio
$stmtRating = $pdo->prepare("SELECT AVG(calificacion) AS promedio, COUNT(*) AS total FROM resenas WHERE producto_id = ?");
$stmtRating->execute([$producto_id]);
$rating = $stmtRating->fetch(PDO::FETCH_ASSOC);

// Obtener reseñas
$stmtResenas = $pdo->prepare("SELECT r.calificacion, r.comentario, u.nombre, r.fecha FROM resenas r 
                              JOIN usuarios u ON r.usuario_id = u.id 
                              WHERE r.producto_id = ? 
                              ORDER BY r.fecha DESC");
$stmtResenas->execute([$producto_id]);
$resenas = $stmtResenas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['nombre']) ?></title>
  <link rel="stylesheet" href="../../assets/css/detalle_producto.css">
</head>
<body>
  <div class="detalle-container">
    <a href="productos_cliente.php" class="volver">← Volver al catálogo</a>

    <div class="producto-detalle">
      <img src="../../assets/img/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
      <div class="info">
        <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
        <p><?= htmlspecialchars($producto['descripcion']) ?></p>
        <p><strong>Precio:</strong> $<?= number_format($producto['precio'], 2) ?></p>

        <?php if ($rating['total'] > 0): ?>
          <p>⭐ <?= number_format($rating['promedio'], 1) ?>/5 (<?= $rating['total'] ?> reseña<?= $rating['total'] > 1 ? 's' : '' ?>)</p>
        <?php else: ?>
          <p>⭐ Sin calificaciones aún</p>
        <?php endif; ?>

        <form action="../../controllers/carrito_controller.php" method="POST">
          <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
          <input type="hidden" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">
          <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
          <label for="cantidad">Cantidad:</label>
          <input type="number" name="cantidad" id="cantidad" value="1" min="1" style="width: 50px;">
          <button type="submit" class="btn-agregar">🛒 Agregar al carrito</button>
        </form>
      </div>
    </div>

    <hr>

    <h3>🗨 Reseñas</h3>
    <?php if (!empty($resenas)): ?>
      <?php foreach ($resenas as $r): ?>
        <div class="comentario">
          <strong><?= htmlspecialchars($r['nombre']) ?></strong> — <?= date('d/m/Y', strtotime($r['fecha'])) ?><br>
          <?= str_repeat('⭐', $r['calificacion']) ?><br>
          <small><?= htmlspecialchars($r['comentario']) ?></small>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No hay reseñas para este producto aún.</p>
    <?php endif; ?>
  </div>
</body>
</html>
