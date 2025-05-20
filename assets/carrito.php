<?php
session_start();
require 'conexion.php';

// Solo usuarios logueados pueden añadir al carrito
if (!isset($_SESSION['usuario'])) {
    header("Location: ../registro.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $tabla = $_POST['tabla'] ?? '';

    $tablas_validas = ['keyboards', 'switches', 'keycaps', 'stabilizers', 'silenciadores', 'pcb', 'deskmats', 'tools', 'lube'];

    if (!is_numeric($id) || !in_array($tabla, $tablas_validas)) {
        header('Location: ../index.php');
        exit;
    }

    // Consulta segura para obtener el producto
    $stmt = $conn->prepare("SELECT nombre, precio, imagen FROM $tabla WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        header('Location: ../index.php');
        exit;
    }

    $producto = $res->fetch_assoc();
    $producto['tabla'] = $tabla;
    $producto['id'] = $id;

    $item_id = $tabla . '_' . $id;

    // Actualizar sesión
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$item_id])) {
        $_SESSION['carrito'][$item_id]['cantidad']++;
    } else {
        $_SESSION['carrito'][$item_id] = [
            'id' => $id,
            'tabla' => $tabla,
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'imagen' => $producto['imagen'],
            'cantidad' => 1
        ];
    }

    // Guardar en la base de datos
if (!isset($_SESSION['usuario']['id'])) {
    die("Error: No se encontró el ID del usuario en la sesión.");
}
$usuario_id = $_SESSION['usuario']['id'];


    $stmt_check = $conn->prepare("SELECT cantidad FROM carritos WHERE usuario_id = ? AND producto_id = ? AND tabla = ?");
    $stmt_check->bind_param("iis", $usuario_id, $id, $tabla);
    $stmt_check->execute();
    $check_result = $stmt_check->get_result();

    if ($check_result->num_rows > 0) {
        $stmt_update = $conn->prepare("UPDATE carritos SET cantidad = cantidad + 1 WHERE usuario_id = ? AND producto_id = ? AND tabla = ?");
        $stmt_update->bind_param("iis", $usuario_id, $id, $tabla);
        $stmt_update->execute();
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO carritos (usuario_id, producto_id, tabla, cantidad) VALUES (?, ?, ?, 1)");
        $stmt_insert->bind_param("iis", $usuario_id, $id, $tabla);
        $stmt_insert->execute();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    header('Location: ../index.php');
    exit;
}
