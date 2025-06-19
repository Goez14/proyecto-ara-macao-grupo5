<?php
require_once __DIR__ . '/../config/database.php';

function registrar_log($usuario_id, $accion) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO logs (usuario_id, accion) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $accion]);
}
