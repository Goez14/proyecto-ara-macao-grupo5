<?php
session_start();
require_once '../config/database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $correo = trim($_POST['correo']);
  $password = $_POST['password'];

  // Validaciones
  if (empty($correo) || empty($password)) {
    $error = "Todos los campos son obligatorios.";
  } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $error = "Correo electrónico no válido.";
  } else {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
      $_SESSION['usuario'] = $usuario;

      switch ($usuario['rol']) {
        case 'admin':
          header("Location: ../views/admin/admin.php");
          exit;
        case 'cliente':
          header("Location: ../views/usuario/perfil/cliente.php");
          exit;
        case 'empleado':
          header("Location: ../views/usuario/empleado.php");
          exit;
        default:
          $error = "Rol no reconocido.";
      }
    } else {
      $error = "Correo o contraseña incorrectos.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="../assets/css/login.css">
  <script>
    function togglePassword() {
      const input = document.getElementById("password");
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
</head>
<body>
  <div class="container">
    <div class="form-container">

      <!-- Logo con enlace + título -->
      <div class="logo-title">
        <a href="../index.php">
          <img src="../assets/img/logo.png" alt="Logo Ara Macao">
        </a>
        <h1>ARA MACAO</h1>
      </div>

      <h2>Iniciar sesión</h2>

      <?php if (!empty($error)): ?>
        <p class="error" style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="input-group">
          <input type="email" name="correo" placeholder="Correo electrónico" required>
        </div>
        <div class="input-group password-group">
          <input type="password" name="password" placeholder="Contraseña" id="password" required>
          <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <button type="submit" name="login">Iniciar sesión</button>
      </form>

      <div class="link">
        ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
      </div>

    </div>
  </div>
</body>
</html>
