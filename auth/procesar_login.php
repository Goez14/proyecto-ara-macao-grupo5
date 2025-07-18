<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = trim($_POST['correo']);
  $password = $_POST['password'];

  if (empty($correo) || empty($password)) {
    header("Location: login.php?error=Todos los campos son obligatorios");
    exit();
  }

  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=Correo inválido");
    exit();
  }
  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
  $stmt->execute([$correo]);
  $usuario = $stmt->fetch();

  if ($usuario && password_verify($password, $usuario['password'])) {
    $_SESSION['usuario'] = [
      'id' => $usuario['id'],
      'nombre' => $usuario['nombre'],
      'rol' => $usuario['rol'],
      'correo' => $usuario['correo']
    ];

    switch ($usuario['rol']) {
      case 'admin':
        header("Location: ../views/admin/admin.php");
        break;
      case 'empleado':
        header("Location: ../views/usuario/empleado.php");
        break;
      case 'cliente':
        header("Location: ../views/productos/productos_cliente.php");
        break;
      default:
        header("Location: login.php?error=Rol no válido");
    }
    exit();
  } else {
    header("Location: login.php?error=Correo o contraseña incorrectos");
    exit();
  }
} else {
  header("Location: login.php");
  exit();
}
