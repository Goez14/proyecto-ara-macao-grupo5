<?php
session_start();
require_once '../../../config/database.php';

if (!isset($_SESSION['usuario'])) {
  header('Location: ../../auth/login.php');
  exit;
}

$usuario = $_SESSION['usuario'];
$id = $usuario['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
  $directorioDestino = '../../../assets/img/perfiles/';
  $nombreArchivo = basename($_FILES['avatar']['name']);
  $rutaCompleta = $directorioDestino . $nombreArchivo;
  $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

  $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

  if (in_array(strtolower($extension), $extensionesPermitidas)) {
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $rutaCompleta)) {
      // Guardar solo el nombre en la base de datos
      $stmt = $pdo->prepare("UPDATE usuarios SET avatar = ? WHERE id = ?");
      $stmt->execute([$nombreArchivo, $id]);

      // Actualizar sesión con nuevo avatar
      $_SESSION['usuario']['avatar'] = $nombreArchivo;

      header('Location: perfil.php');
      exit;
    } else {
      echo "Error al subir la imagen.";
    }
  } else {
    echo "Extensión no permitida.";
  }
} else {
  header('Location: perfil.php');
  exit;
}
?>
