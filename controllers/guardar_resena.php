<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../auth/login.php');
  exit();
}

require_once '../config/database.php';

$usuario_id = $_SESSION['usuario']['id'];
$producto_id = $_POST['producto_id'];
$calificacion = $_POST['calificacion'];
$comentario = $_POST['comentario'] ?? null;

$stmt = $pdo->prepare("INSERT INTO resenas (usuario_id, producto_id, calificacion, comentario) 
                       VALUES (?, ?, ?, ?)");
$stmt->execute([$usuario_id, $producto_id, $calificacion, $comentario]);

header("Location: ../views/pedidos/mis_pedidos.php?resena=ok");
exit();
