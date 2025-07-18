<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['admin', 'empleado'])) {
  header('Location: ../../auth/login.php');
  exit();
}

// Obtener productos
$stmt = $pdo->query("SELECT id, nombre FROM productos ORDER BY nombre");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener descuentos actuales
$descuentos = $pdo->query("SELECT d.*, p.nombre AS producto FROM descuentos d JOIN productos p ON d.producto_id = p.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎯 Gestionar Descuentos</title>
  <link rel="stylesheet" href="../../assets/css/gestionar_descuentos.css">
</head>
<body>
  <div class="container">
    <h2>🎯 Gestionar Descuentos</h2>

    <?php if (isset($_GET['success'])): ?>
      <p style="color: green;">✅ Descuento guardado correctamente.</p>
    <?php endif; ?>

    <!-- Formulario para agregar o actualizar descuentos -->
    <form method="POST" action="../../controllers/guardar_descuento.php">
      <label>Producto:</label>
      <select name="producto_id" required>
        <option value="">Seleccione un producto</option>
        <?php foreach ($productos as $p): ?>
          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Porcentaje de descuento (%):</label>
      <input type="number" name="porcentaje" step="0.1" min="0" max="100" required><br><br>

      <label>Fecha inicio:</label>
      <input type="date" name="fecha_inicio" required><br><br>

      <label>Fecha fin:</label>
      <input type="date" name="fecha_fin" required><br><br>

      <label>¿Activo?</label>
      <input type="checkbox" name="activo" checked><br><br>

      <button type="submit">💾 Guardar descuento</button>
    </form>

    <hr>

    <h3>📋 Descuentos Actuales</h3>
    <?php if (empty($descuentos)): ?>
      <p>No hay descuentos registrados.</p>
    <?php else: ?>
      <table border="1" cellpadding="8" cellspacing="0">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Porcentaje</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Activo</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($descuentos as $d): ?>
            <tr>
              <td><?= htmlspecialchars($d['producto']) ?></td>
              <td><?= $d['porcentaje'] ?>%</td>
              <td><?= $d['fecha_inicio'] ?></td>
              <td><?= $d['fecha_fin'] ?></td>
              <td><?= $d['activo'] ? '✅' : '❌' ?></td>
              <td>
                <form method="POST" action="../../controllers/eliminar_descuento.php" onsubmit="return confirm('¿Eliminar descuento?');">
                  <input type="hidden" name="id" value="<?= $d['id'] ?>">
                  <button type="submit">🗑 Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
