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
      <form method="POST" action="login.php">
        <div class="input-group">
          <input type="email" name="correo" placeholder="Correo electrónico" required>
        </div>
        <div class="input-group password-group">
          <input type="password" name="password" placeholder="Contraseña" id="password" required>
          <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>
        <button type="submit" name="login">Iniciar sesión</button>
      </form>
      <a href="#" class="forgot-password">¿Has olvidado tu contraseña?</a>
    </div>
  </div>

<?php
session_start();

if (isset($_POST['login'])) {
    require_once '../config/database.php';

    $correo = $_POST['correo'];
    $password = $_POST['password'];

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
                echo "<p style='text-align:center;color:red;'>Rol no reconocido.</p>";
                exit;
        }
    } else {
        echo "<p style='text-align:center;color:red;'>Correo o contraseña incorrectos.</p>";
    }
}
?>
</body>
</html>




