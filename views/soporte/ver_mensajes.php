<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['admin', 'empleado'])) {
  header('Location: ../../auth/login.php');
  exit();
}

require_once '../../config/database.php';

// Obtener todos los mensajes de soporte y sus respuestas si existen
$stmt = $pdo->query("
  SELECT s.id AS soporte_id, s.asunto, s.mensaje, s.fecha, u.nombre AS cliente,
         r.respuesta, r.fecha AS fecha_respuesta
  FROM soporte s
  JOIN usuarios u ON s.usuario_id = u.id
  LEFT JOIN respuestas_soporte r ON s.id = r.soporte_id
  ORDER BY s.fecha DESC
");
$mensajes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mensajes de Soporte</title>
  <link rel="stylesheet" href="../../assets/css/ver_mensajes.css">
</head>
<body>
  <div class="container">
    <h2>📨 Mensajes de Soporte</h2>
    <?php foreach ($mensajes as $m): ?>
      <div class="mensaje">
        <h4><?= htmlspecialchars($m['asunto']) ?> - de <?= htmlspecialchars($m['cliente']) ?></h4>
        <p><?= nl2br(htmlspecialchars($m['mensaje'])) ?></p>
        <small>📅 <?= $m['fecha'] ?></small>

        <?php if ($m['respuesta']): ?>
          <div class="respuesta">
            <strong>Respuesta:</strong>
            <p><?= nl2br(htmlspecialchars($m['respuesta'])) ?></p>
            <small>🕒 <?= $m['fecha_respuesta'] ?></small>
          </div>
        <?php else: ?>
          <form action="../../controllers/responder_soporte.php" method="POST">
            <input type="hidden" name="soporte_id" value="<?= $m['soporte_id'] ?>">
            <textarea name="respuesta" rows="3" placeholder="Escribe una respuesta..." required></textarea>
            <button type="submit">Responder</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
