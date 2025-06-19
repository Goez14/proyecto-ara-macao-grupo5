<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

$stmt = $pdo->query("SELECT id, nombre, correo, rol, fecha_registro, avatar FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios Registrados</title>
  <link rel="stylesheet" href="../../assets/css/usuarios.css">
</head>
<body>

<div class="usuarios-container">
  <h2>👥 Usuarios Registrados</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Avatar</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Registrado</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $usuario): ?>
        <tr>
          <td><?= $usuario['id'] ?></td>
          <td><img src="../../assets/img/avatars/<?= $usuario['avatar'] ?>" alt="Avatar" class="avatar"></td>
          <td><?= htmlspecialchars($usuario['nombre']) ?></td>
          <td><?= htmlspecialchars($usuario['correo']) ?></td>
          <td><?= $usuario['rol'] ?></td>
          <td><?= $usuario['fecha_registro'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
