<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    $usuario_id = $_SESSION['usuario']['id'];
    $asunto = $_POST['asunto'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    if ($asunto && $mensaje) {
        $stmt = $pdo->prepare("INSERT INTO soporte (usuario_id, asunto, mensaje) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $asunto, $mensaje]);

        // Redireccionar con mensaje de éxito
        header('Location: ../views/soporte/contacto.php?enviado=1');
        exit();
    } else {
        echo "Faltan datos.";
    }
}
