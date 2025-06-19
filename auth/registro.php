<!-- auth/registro.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <link rel="stylesheet" href="../assets/css/registro.css">
</head>
<body>

<form method="POST" action="registro.php">
  <h2>Registro</h2>
  <input type="text" name="nombre" placeholder="Nombre completo" required>
  <input type="email" name="correo" placeholder="Correo electrónico" required>
  <input type="password" name="password" placeholder="Contraseña" required>
  <select name="rol" required>
    <option value="">Seleccionar rol</option>
    <option value="admin">Administrador</option>
    <option value="cliente">Cliente</option>
    <option value="empleado">Empleado</option>
  </select>
  <button type="submit" name="registrar">Registrarse</button>
  <div class="link">
    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
  </div>
</form>

<?php
if (isset($_POST['registrar'])) {
  require_once '../config/database.php';
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $rol = $_POST['rol'];

  try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $password, $rol]);
    echo "<p style='text-align:center;color:green;'>Usuario registrado correctamente.</p>";
  } catch (PDOException $e) {
    echo "<p style='text-align:center;color:red;'>Error: " . $e->getMessage() . "</p>";
  }
}
?>

</body>
</html>

