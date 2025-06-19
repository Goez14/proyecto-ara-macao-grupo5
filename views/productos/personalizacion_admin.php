<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: ../../auth/login.php');
  exit();
}
require_once '../../config/database.php';

// Obtener personalizaciones con datos del usuario
$sql = "SELECT p.id, u.nombre, p.color, p.tamano, p.fecha 
        FROM personalizaciones p 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$personalizaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Personalizaciones de Usuarios</title>
  <link rel="stylesheet" href="../../assets/css/personalizacion_admin.css">
</head>
<body>
  <div class="contenedor">
    <h2>🎨 Personalizaciones Realizadas</h2>
    <table>
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Color</th>
          <th>Tamaño</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($personalizaciones as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['color']) ?></td>
            <td><?= htmlspecialchars($row['tamano']) ?></td>
            <td><?= htmlspecialchars($row['fecha']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <a class="volver" href="../admin/admin.php">⬅ Volver al Dashboard</a>
  </div>
</body>
</html>
