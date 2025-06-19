<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../../auth/login.php');
  exit();
}
require_once __DIR__ . '/../../../config/database.php';

$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $actual = $_POST['password_actual'] ?? '';
  $nueva = $_POST['nueva_password'] ?? '';
  $confirmar = $_POST['confirmar_password'] ?? '';

  if ($nueva !== $confirmar) {
    $error = "La nueva contraseña y su confirmación no coinciden.";
  } elseif (strlen($nueva) < 6) {
    $error = "La nueva contraseña debe tener al menos 6 caracteres.";
  } else {
    // Verificar contraseña actual
    $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario['id']]);
    $row = $stmt->fetch();

    if ($row && password_verify($actual, $row['password'])) {
      $nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
      $stmt->execute([$nuevaHash, $usuario['id']]);
      $mensaje = "Contraseña actualizada correctamente.";
    } else {
      $error = "La contraseña actual no es correcta.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña</title>
  <link rel="stylesheet" href="../../../assets/css/perfil.css">
</head>
<body>
  <div class="perfil-container">
    <h2>Cambiar Contraseña</h2>

    <?php if (isset($mensaje)): ?>
      <p style="color: green;"><?= htmlspecialchars($mensaje) ?></p>
    <?php elseif (isset($error)): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="password_actual">Contraseña actual:</label>
      <input type="password" name="password_actual" id="password_actual" required>

      <label for="nueva_password">Nueva contraseña:</label>
      <input type="password" name="nueva_password" id="nueva_password" required>

      <label for="confirmar_password">Confirmar nueva contraseña:</label>
      <input type="password" name="confirmar_password" id="confirmar_password" required>

      <button type="submit">Actualizar contraseña</button>
      <a href="perfil.php">Volver</a>
    </form>
  </div>
</body>
</html>
