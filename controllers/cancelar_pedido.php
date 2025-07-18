<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'cliente') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'] ?? null;
    $usuario_id = $_SESSION['usuario']['id'];

    if ($pedido_id) {
        try {
            // 1. Verificar si el pedido existe, pertenece al usuario y está pendiente
            $stmt = $pdo->prepare("SELECT total, estado FROM pedidos WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$pedido_id, $usuario_id]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {
                header("Location: ../views/pedidos/mis_pedidos.php?cancelado=error");
                exit();
            }

            if (strtolower($pedido['estado']) !== 'pendiente') {
                header("Location: ../views/pedidos/mis_pedidos.php?cancelado=no-permitido");
                exit();
            }

            // 2. Calcular puntos otorgados por ese pedido
            $puntosGanados = floor($pedido['total'] / 1000);

            // 3. Obtener puntos actuales del usuario
            $stmtPuntos = $pdo->prepare("SELECT puntos FROM usuarios WHERE id = ?");
            $stmtPuntos->execute([$usuario_id]);
            $usuario = $stmtPuntos->fetch(PDO::FETCH_ASSOC);
            $puntosActuales = (int) $usuario['puntos'];

            // 4. Calcular nuevos puntos (sin dejar que bajen de cero)
            $puntosFinales = max($puntosActuales - $puntosGanados, 0);

            // 5. Ejecutar transacción
            $pdo->beginTransaction();

            // a. Cancelar pedido
            $updatePedido = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id = ?");
            $updatePedido->execute([$pedido_id]);

            // b. Actualizar puntos del usuario
            $updateUsuario = $pdo->prepare("UPDATE usuarios SET puntos = ? WHERE id = ?");
            $updateUsuario->execute([$puntosFinales, $usuario_id]);

            $pdo->commit();

            // ✅ Todo correcto
            header("Location: ../views/pedidos/mis_pedidos.php?cancelado=ok");
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error al cancelar pedido: " . $e->getMessage());
            header("Location: ../views/pedidos/mis_pedidos.php?cancelado=error");
            exit();
        }
    }
}

// ❌ Si llega aquí, algo falló
header("Location: ../views/pedidos/mis_pedidos.php?cancelado=error");
exit();
