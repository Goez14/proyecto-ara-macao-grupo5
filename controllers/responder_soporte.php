<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['admin', 'empleado'])) {
  header('Location: ../auth/login.php');
  exit();
}

require_once '../config/database.php';

$soporte_id = $_POST['soporte_id'];
$respuesta = $_POST['respuesta'];
$empleado_id = $_SESSION['usuario']['id'];

$stmt = $pdo->prepare("INSERT INTO respuestas_soporte (soporte_id, empleado_id, respuesta) VALUES (?, ?, ?)");
$stmt->execute([$soporte_id, $empleado_id, $respuesta]);

header('Location: ../views/soporte/ver_mensajes.php?enviado=1');
exit();
