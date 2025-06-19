<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Contacto y Soporte</title>
  <link rel="stylesheet" href="../../assets/css/contacto.css">
</head>
<body>

<div class="contacto-container">
  <h2>📩 Soporte al Cliente</h2>

  <?php if (isset($_GET['enviado'])): ?>
    <p class="exito">✅ Mensaje enviado correctamente. Gracias por contactarnos.</p>
  <?php endif; ?>

  <form method="POST" action="../../controllers/guardar_contacto.php">
    <label for="asunto">Asunto:</label>
    <input type="text" id="asunto" name="asunto" placeholder="¿Cuál es tu duda?" required>

    <label for="mensaje">Mensaje:</label>
    <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>

    <button type="submit">Enviar Mensaje</button>
  </form>
</div>

</body>
</html>
