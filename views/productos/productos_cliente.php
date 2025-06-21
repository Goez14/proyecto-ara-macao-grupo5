<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

$sql = "SELECT * FROM productos WHERE 1=1";
$params = [];

if (!empty($_GET['tipo'])) {
  $sql .= " AND tipo = ?";
  $params[] = $_GET['tipo'];
}
if (!empty($_GET['sabor'])) {
  $sql .= " AND sabor = ?";
  $params[] = $_GET['sabor'];
}
if (!empty($_GET['color'])) {
  $sql .= " AND color = ?";
  $params[] = $_GET['color'];
}
if (!empty($_GET['tamano'])) {
  $sql .= " AND tamano = ?";
  $params[] = $_GET['tamano'];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo de Productos</title>
  <link rel="stylesheet" href="../../assets/css/productos_cliente.css">
</head>
<body>
  <div class="container">
    <h2>🛍 Catálogo de Dulces</h2>

    <form method="GET" class="filtro-form">
      <select name="tipo">
        <option value="">Tipo</option>
        <option value="gragea">Gragea</option>
        <option value="chocolate">Chocolate</option>
        <option value="bombon">Bombón</option>
        <option value="gomita">Gomita</option>
      </select>

      <select name="sabor">
        <option value="">Sabor</option>
        <option value="fresa">Fresa</option>
        <option value="menta">Menta</option>
        <option value="café">Café</option>
        <option value="surtido">Surtido</option>
      </select>

      <select name="color">
        <option value="">Color</option>
        <option value="rojo">Rojo</option>
        <option value="azul">Azul</option>
        <option value="blanco">Blanco</option>
        <option value="verde">Verde</option>
      </select>

      <select name="tamano">
        <option value="">Tamaño</option>
        <option value="pequeño">Pequeño</option>
        <option value="mediano">Mediano</option>
        <option value="grande">Grande</option>
      </select>

      <button type="submit">🔎 Filtrar</button>
    </form>

    <div class="productos-grid">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <div class="producto-card">
            <img src="../../assets/img/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
            <h3><strong><?= htmlspecialchars($producto['nombre']) ?></strong></h3>
            <p><?= htmlspecialchars($producto['descripcion']) ?></p>
            <p><strong>$<?= number_format($producto['precio'], 2) ?></strong></p>

            <form action="../../controllers/carrito_controller.php" method="POST">
              <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
              <input type="hidden" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">
              <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
              <label for="cantidad_<?= $producto['id'] ?>">Cantidad:</label>
              <input type="number" name="cantidad" id="cantidad_<?= $producto['id'] ?>" value="1" min="1" style="width: 50px;">
              <button type="submit" class="btn-agregar">🛒 Agregar al carrito</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No se encontraron productos con esos filtros.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
