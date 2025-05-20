<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']['id'])) {
    header('Location: ../index.php');
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];

$stmt = $conn->prepare("DELETE FROM carritos WHERE usuario_id = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

// Opcional: también limpiar variable de sesión si se usaba
unset($_SESSION['carrito']);

// Redirigir al index (o a donde desees)
header('Location: ../index.php');
exit;
