<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../../auth/login.php');
  exit();
}
require_once __DIR__ . '/../../../config/database.php';
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="../../../assets/css/perfil.css">
</head>
<body>

  <div class="perfil-container">
    <h2>Mi Perfil</h2>

    <p><b>Nombre:</b> <?= htmlspecialchars($usuario['nombre']) ?></p>
    <p><b>Correo:</b> <?= htmlspecialchars($usuario['correo']) ?></p>
    <p><b>Rol:</b> <?= htmlspecialchars($usuario['rol']) ?></p>

    <div class="perfil-avatar">
      <img src="../../../assets/img/perfiles/<?= htmlspecialchars($usuario['avatar'] ?? 'default.png') ?>" alt="Avatar">
      <form method="POST" action="subir_avatar.php" enctype="multipart/form-data">
        <input type="file" name="avatar" accept="image/*" required>
        <button type="submit">Actualizar Avatar</button>
      </form>
    </div>

    <div class="perfil-links">
      <a href="editar_datos.php">Editar datos</a>
      <a href="cambiar_password.php">Cambiar contraseña</a>
      <a href="../../auth/logout.php">Cerrar sesión</a>
    </div>
  </div>

</body>
</html>
