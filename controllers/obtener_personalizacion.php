<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de base de datos
$host = 'localhost';
$dbname = 'ara_macoa_db'; // 🔁 Reemplaza esto con el nombre real de tu base
$username = 'root';           // 🔁 Reemplaza si usas otro usuario
$password = '';               // 🔁 Reemplaza si tu usuario tiene contraseña

$response = [];

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ejecutar la consulta
    $stmt = $pdo->query("SELECT color, tamano FROM personalizaciones WHERE usuario_id = 1 LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $response = [
            'color' => $result['color'],
            'tamano' => floatval($result['tamano'])
        ];
    } else {
        // Si no hay datos en la base, usar valores por defecto
        $response = [
            'color' => '#ff0000',
            'tamano' => 1.0
        ];
    }

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode([
        'error' => 'Error en base de datos: ' . $e->getMessage()
    ]);
}
