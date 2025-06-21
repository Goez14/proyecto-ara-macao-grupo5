<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
    header('Location: ../auth/login.php');
    exit();
}

$carrito = $_SESSION['carrito'] ?? [];
if (empty($carrito)) {
    header('Location: ../views/productos/ver_carrito.php');
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];
$total = 0;

try {
    $pdo->beginTransaction();

    // Calcular total del pedido
    foreach ($carrito as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    // Insertar el pedido principal
    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $total]);
    $pedido_id = $pdo->lastInsertId();

    // Insertar cada detalle del pedido
    $detalle_stmt = $pdo->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");

    foreach ($carrito as $producto_id => $item) {
        $detalle_stmt->execute([
            $pedido_id,
            $producto_id,
            $item['cantidad'],
            $item['precio']
        ]);
    }

    $pdo->commit();
    unset($_SESSION['carrito']); // Vaciar carrito
    header("Location: ../views/pedidos/mis_pedidos.php?pedido=exito");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<p style='color:red; text-align:center;'>Error al procesar el pedido: " . htmlspecialchars($e->getMessage()) . "</p>";
}
