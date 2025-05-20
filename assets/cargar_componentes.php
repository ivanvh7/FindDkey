<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/conexion.php');

function obtenerDatos($tabla) {
    global $conn;
    $resultado = mysqli_query($conn, "SELECT * FROM $tabla");
    $datos = [];
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $datos[] = $fila;
        }
    }
    return $datos;
}

$tools = obtenerDatos('tools');
$lube = obtenerDatos('lube');
$pcbs = obtenerDatos('pcb');
$keyboards = obtenerDatos('keyboards');
$switches = obtenerDatos('switches');
$keycaps = obtenerDatos('keycaps');
$stabilizers = obtenerDatos('stabilizers');
$silenciadores = obtenerDatos('silenciadores');
$deskmats = obtenerDatos('deskmats');
?>
