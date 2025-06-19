<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../../auth/login.php');
  exit();
}
require_once __DIR__ . '/../../../config/database.php';

$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nuevoNombre = trim($_POST['nombre']);
  $nuevoCorreo = trim($_POST['correo']);

  if ($nuevoNombre && $nuevoCorreo) {
    // Validar que no se repita el correo (excepto el propio)
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ? AND id != ?");
    $stmt->execute([$nuevoCorreo, $usuario['id']]);
    if ($stmt->fetch()) {
      $error = "El correo ya está en uso.";
    } else {
      $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?");
      $stmt->execute([$nuevoNombre, $nuevoCorreo, $usuario['id']]);
      
      // Actualizar sesión
      $_SESSION['usuario']['nombre'] = $nuevoNombre;
      $_SESSION['usuario']['correo'] = $nuevoCorreo;
      header("Location: perfil.php");
      exit();
    }
  } else {
    $error = "Todos los campos son obligatorios.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Datos</title>
  <link rel="stylesheet" href="../../../assets/css/perfil.css">
</head>
<body>
  <div class="perfil-container">
    <h2>Editar Mis Datos</h2>

    <?php if (isset($error)): ?>
      <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="nombre">Nombre:</label>
      <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

      <label for="correo">Correo:</label>
      <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

      <button type="submit">Guardar cambios</button>
      <a href="perfil.php">Volver</a>
    </form>
  </div>
</body>
</html>
