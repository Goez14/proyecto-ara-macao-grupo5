<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

$usuario_id = $_SESSION['usuario']['id'];

// Obtener mensajes y respuestas
$stmt = $pdo->prepare("
  SELECT s.id, s.asunto, s.mensaje, s.fecha, r.respuesta, r.fecha AS fecha_respuesta
  FROM soporte s
  LEFT JOIN respuestas_soporte r ON s.id = r.soporte_id
  WHERE s.usuario_id = ?
  ORDER BY s.fecha DESC
");
$stmt->execute([$usuario_id]);
$mensajes = $stmt->fetchAll();
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

  <hr>

  <h3>📜 Historial de Soporte</h3>

  <?php if (empty($mensajes)): ?>
    <p>No has enviado mensajes de soporte todavía.</p>
  <?php else: ?>
    <?php foreach ($mensajes as $m): ?>
      <div class="soporte-item">
        <p><strong>Asunto:</strong> <?= htmlspecialchars($m['asunto']) ?></p>
        <p><?= nl2br(htmlspecialchars($m['mensaje'])) ?></p>
        <small>📅 Enviado el: <?= $m['fecha'] ?></small>

        <?php if ($m['respuesta']): ?>
          <div class="respuesta">
            <strong>Respuesta del equipo:</strong>
            <p><?= nl2br(htmlspecialchars($m['respuesta'])) ?></p>
            <small>🕒 Respondido el: <?= $m['fecha_respuesta'] ?></small>
          </div>
        <?php else: ?>
          <p class="sin-respuesta">⌛ Aún sin respuesta</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
