<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <link rel="stylesheet" href="../assets/css/registro.css">
</head>
<body>

<div class="contenedor-formulario">
  <a href="../index.php" class="logo-link">
    <img src="../assets/img/logo.png" alt="Logo Ara Macao" class="logo">
    <h1 class="titulo">ARA MACAO</h1>
  </a>

  <form method="POST" action="registro.php" class="formulario">
    <h2>Registro</h2>

    <?php
    session_start();
    require_once '../config/database.php';
    $error = "";
    $success = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
      $nombre = trim($_POST['nombre']);
      $correo = trim($_POST['correo']);
      $password = $_POST['password'];
      $rol = $_POST['rol'];

      if (empty($nombre) || empty($correo) || empty($password) || empty($rol)) {
        $error = "Todos los campos son obligatorios.";
      } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no es válido.";
      } elseif (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d).{6,}$/', $password)) {
        $error = "La contraseña debe tener al menos 6 caracteres, incluir una letra y un número.";
      } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
          $error = "El correo ya está registrado.";
        } else {
          $hash = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
          $stmt->execute([$nombre, $correo, $hash, $rol]);
          $success = "✅ Usuario registrado correctamente.";
        }
      }
    }

    if (!empty($error)) {
      echo "<p style='color:red;text-align:center;'>$error</p>";
    } elseif (!empty($success)) {
      echo "<p style='color:green;text-align:center;'>$success</p>";
    }
    ?>

    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="correo" placeholder="Correo electrónico" required>

    <div class="campo-password">
      <input type="password" name="password" placeholder="Contraseña" id="password" required>
      <span class="toggle-password" id="togglePassword">👁️</span>
    </div>

    <select name="rol" required>
      <option value="">Seleccionar rol</option>
      <option value="admin">Administrador</option>
      <option value="cliente">Cliente</option>
      <option value="empleado">Empleado</option>
    </select>

    <button type="submit" name="registrar">Registrarse</button>

    <div class="link">
      ¿Ya tienes cuenta? <a href="login.php">Iniciar</a>
    </div>
  </form>
</div>

<script>
  document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    this.textContent = isPassword ? '👁️' : '👁️';
  });
</script>

</body>
</html>
