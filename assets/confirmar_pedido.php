<?php
session_start();
require_once 'conexion.php'; // Este archivo define $conn (MySQLi)

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];

// Obtener productos del carrito (tabla carritos)
$stmt = $conn->prepare("SELECT producto_id, tabla FROM carritos WHERE usuario_id = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$productos = $resultado->fetch_all(MYSQLI_ASSOC);

if (empty($productos)) {
    echo json_encode(['error' => 'El carrito está vacío']);
    exit;
}

$conn->begin_transaction();

try {
    // Preparar inserción en la tabla pedidos
    $stmtInsert = $conn->prepare("
        INSERT INTO pedidos (usuario_id, tabla, producto_id, fecha_pedido, estado)
        VALUES (?, ?, ?, NOW(), 'Pendiente')
    ");

    foreach ($productos as $producto) {
        $producto_id = (int)$producto['producto_id'];
        $tabla = $producto['tabla'];

        // Verificación extra por seguridad
        if (!is_string($tabla) || empty($tabla)) {
            error_log("⚠️ Tabla vacía o incorrecta para producto_id: $producto_id");
            continue;
        }

        $stmtInsert->bind_param("ssi", $idUsuario, $tabla, $producto_id);
        $stmtInsert->execute();
    }

    // Vaciar el carrito después de confirmar el pedido
    $stmtVaciar = $conn->prepare("DELETE FROM carritos WHERE usuario_id = ?");
    $stmtVaciar->bind_param("i", $idUsuario);
    $stmtVaciar->execute();

    unset($_SESSION['carrito']);

    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al procesar el pedido',
        'detalle' => $e->getMessage()
    ]);
}
