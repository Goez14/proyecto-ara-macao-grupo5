<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../../auth/login.php');
  exit();
}

require_once __DIR__ . '/../../../config/database.php';

// Obtener el ID del usuario
$usuario_id = $_SESSION['usuario']['id'];

// Consultar información actualizada del usuario (incluyendo puntos)
$stmt = $pdo->prepare("SELECT nombre, correo, rol, avatar, puntos FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el usuario (raro, pero por seguridad)
if (!$usuario) {
  echo "Usuario no encontrado.";
  exit();
}
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
    <h2>👤 Mi Perfil</h2>

    <p><b>Nombre:</b> <?= htmlspecialchars($usuario['nombre']) ?></p>
    <p><b>Correo:</b> <?= htmlspecialchars($usuario['correo']) ?></p>
    <p><b>Rol:</b> <?= htmlspecialchars($usuario['rol']) ?></p>
    <p><strong>Puntos acumulados:</strong> <?= $usuario['puntos'] ?> 🪙</p>

    <div class="perfil-avatar">
      <img src="../../../assets/img/perfiles/<?= htmlspecialchars($usuario['avatar'] ?? 'default.png') ?>" alt="Avatar" style="width:120px; height:120px; border-radius:50%; object-fit:cover;">
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
