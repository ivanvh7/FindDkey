<?php
session_start();
require 'conexion.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: ../registro.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $tabla = $_POST['tabla'] ?? '';
    $accion = $_POST['accion'] ?? '';

    $usuario_id = $_SESSION['usuario']['id'];

    if (!is_numeric($id) || !in_array($tabla, ['keyboards', 'switches', 'keycaps', 'stabilizers', 'silenciadores', 'pcb', 'deskmats', 'tools', 'lube'])) {
        header("Location: ../index.php");
        exit;
    }

    $item_id = $tabla . '_' . $id;

    // Verifica que el item esté en el carrito de sesión
    if (!isset($_SESSION['carrito'][$item_id])) {
        header("Location: ../index.php");
        exit;
    }

    // Lógica por acción
    switch ($accion) {
        case 'incrementar':
            $_SESSION['carrito'][$item_id]['cantidad']++;
            $stmt = $conn->prepare("UPDATE carritos SET cantidad = cantidad + 1 WHERE usuario_id = ? AND producto_id = ? AND tabla = ?");
            $stmt->bind_param("iis", $usuario_id, $id, $tabla);
            $stmt->execute();
            break;

        case 'decrementar':
            if ($_SESSION['carrito'][$item_id]['cantidad'] > 1) {
                $_SESSION['carrito'][$item_id]['cantidad']--;
                $stmt = $conn->prepare("UPDATE carritos SET cantidad = cantidad - 1 WHERE usuario_id = ? AND producto_id = ? AND tabla = ?");
                $stmt->bind_param("iis", $usuario_id, $id, $tabla);
                $stmt->execute();
            } else {
                unset($_SESSION['carrito'][$item_id]);
                $stmt = $conn->prepare("DELETE FROM carritos WHERE usuario_id = ? AND producto_id = ? AND tabla = ?");
                $stmt->bind_param("iis", $usuario_id, $id, $tabla);
                $stmt->execute();
            }
            break;

        case 'eliminar':
            unset($_SESSION['carrito'][$item_id]);
            $stmt = $conn->prepare("DELETE FROM carritos WHERE usuario_id = ? AND producto_id = ? AND tabla = ?");
            $stmt->bind_param("iis", $usuario_id, $id, $tabla);
            $stmt->execute();
            break;
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
