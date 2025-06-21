<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['admin', 'empleado'])) {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $precio = $_POST['precio'];
  $stock = $_POST['stock'];
  $tipo = $_POST['tipo'];
  $sabor = $_POST['sabor'];
  $color = $_POST['color'];
  $tamano = $_POST['tamano'];

  // Subir imagen
  $nombreImagen = $_FILES['imagen']['name'];
  $rutaTemporal = $_FILES['imagen']['tmp_name'];
  $destino = '../../assets/img/productos/' . $nombreImagen;

  if (move_uploaded_file($rutaTemporal, $destino)) {
    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, stock, tipo, sabor, color, tamano) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $precio, $nombreImagen, $stock, $tipo, $sabor, $color, $tamano]);

    $mensaje = "✅ Producto subido exitosamente.";
  } else {
    $mensaje = "❌ Error al subir la imagen.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Producto</title>
  <link rel="stylesheet" href="../../assets/css/subir_producto.css">
</head>
<body>
  <div class="form-container">
    <h2>📤 Subir Nuevo Producto</h2>

    <?php if ($mensaje): ?>
      <p class="mensaje"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="nombre" placeholder="Nombre del producto" required>
      <textarea name="descripcion" placeholder="Descripción" required></textarea>
      <input type="number" name="precio" step="0.01" placeholder="Precio" required>
      <input type="number" name="stock" placeholder="Cantidad en stock" required>

      <select name="tipo" required>
        <option value="">Tipo</option>
        <option value="chocolate">Chocolate</option>
        <option value="bombon">Bombón</option>
        <option value="gomita">Gomitas</option>
      </select>

      <select name="sabor" required>
        <option value="">Sabor</option>
        <option value="fresa">Fresa</option>
        <option value="menta">Menta</option>
        <option value="café">Café</option>
        <option value="surtido">Surtido</option>
      </select>

      <select name="color" required>
        <option value="">Color</option>
        <option value="rojo">Rojo</option>
        <option value="verde">Verde</option>
        <option value="azul">Azul</option>
        <option value="amarillo">Amarillo</option>
      </select>


      <input type="file" name="imagen" accept="image/*" required>

      <button type="submit">Subir Producto</button>
    </form>
  </div>
</body>
</html>
