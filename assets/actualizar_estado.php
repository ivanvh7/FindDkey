<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['role'] !== 'admin') {
    die("Acceso no autorizado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido_id']) && isset($_POST['nuevo_estado'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $nuevo_estado = $_POST['nuevo_estado'];

    $estados_validos = ['Pendiente', 'Procesado', 'Enviado', 'Entregado'];
    if (!in_array($nuevo_estado, $estados_validos)) {
        die("Estado inválido.");
    }

    $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);
    $stmt->execute();

    $stmt->close();
}

header("Location: ../index_admin.php");
exit();
