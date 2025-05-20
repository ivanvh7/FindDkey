<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['role'] !== 'admin') {
    die("Acceso no autorizado.");
}

$accion = $_POST['accion'] ?? '';
$tabla = $_POST['tabla'] ?? '';

$tablas_permitidas = ['keyboards', 'keycaps', 'switches', 'deskmats', 'tools', 'lube', 'pcb', 'stabilizers', 'silenciadores'];

if (!in_array($tabla, $tablas_permitidas)) {
    die("Tabla no permitida.");
}

switch ($accion) {
    case 'eliminar':
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM $tabla WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        break;

    case 'editar_precio':
        $id = intval($_POST['id']);
        $precio = floatval($_POST['nuevo_precio']);
        $stmt = $conn->prepare("UPDATE $tabla SET precio = ? WHERE id = ?");
        $stmt->bind_param("di", $precio, $id);
        $stmt->execute();
        break;

case 'agregar':
    if ($tabla === 'keyboards') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $marca = $_POST['marca'];
        $tamanio = $_POST['tamanio'];
        $lenguaje = $_POST['lenguaje'];
        $features = $_POST['features'] ?? '';
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        // Validar y mover imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/keyboards/' . $nombre_imagen;
            $ruta_bd = 'media/keyboards/' . $nombre_imagen;

            // Crear carpeta si no existe
            if (!is_dir('../media/keyboards')) {
                mkdir('../media/keyboards', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO keyboards 
                (nombre, descripcion, marca, tamanio, lenguaje, features, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssssssdd",
                $nombre,
                $descripcion,
                $marca,
                $tamanio,
                $lenguaje,
                $features,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

    // AGREGAR KEYCAPS
    if ($tabla === 'keycaps') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $marca = $_POST['marca'];
        $material = $_POST['material'];
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/keycaps/' . $nombre_imagen;
            $ruta_bd = 'media/keycaps/' . $nombre_imagen;

            if (!is_dir('../media/keycaps')) {
                mkdir('../media/keycaps', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO keycaps 
                (nombre, descripcion, marca, material, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "ssssssd",
                $nombre,
                $descripcion,
                $marca,
                $material,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'switches') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $tipo = $_POST['tipo'];
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/switches/' . $nombre_imagen;
            $ruta_bd = 'media/switches/' . $nombre_imagen;

            if (!is_dir('../media/switches')) {
                mkdir('../media/switches', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO switches 
                (nombre, descripcion, tipo, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "ssssdd",
                $nombre,
                $descripcion,
                $tipo,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'deskmats') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $tamanio = $_POST['tamanio'];
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/deskmats/' . $nombre_imagen;
            $ruta_bd = 'media/deskmats/' . $nombre_imagen;

            if (!is_dir('../media/deskmats')) {
                mkdir('../media/deskmats', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO deskmats 
                (nombre, descripcion, tamanio, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssssd",
                $nombre,
                $descripcion,
                $tamanio,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'stabilizers') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $tipo = $_POST['tipo'];
        $lubricado = $_POST['lubricado'] == '1' ? 1 : 0;
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/stabilizers/' . $nombre_imagen;
            $ruta_bd = 'media/stabilizers/' . $nombre_imagen;

            if (!is_dir('../media/stabilizers')) {
                mkdir('../media/stabilizers', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO stabilizers 
                (nombre, descripcion, tipo, lubricado, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssisdd",
                $nombre,
                $descripcion,
                $tipo,
                $lubricado,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'silenciadores') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $tipo = $_POST['tipo'];
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/silenciadores/' . $nombre_imagen;
            $ruta_bd = 'media/silenciadores/' . $nombre_imagen;

            if (!is_dir('../media/silenciadores')) {
                mkdir('../media/silenciadores', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO silenciadores 
                (nombre, descripcion, tipo, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "ssssdd",
                $nombre,
                $descripcion,
                $tipo,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'pcb') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $layout = $_POST['layout'];
        $hotswap = $_POST['hotswap'] == '1' ? 1 : 0;
        $rgb = $_POST['rgb'] == '1' ? 1 : 0;
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/pcb/' . $nombre_imagen;
            $ruta_bd = 'media/pcb/' . $nombre_imagen;

            if (!is_dir('../media/pcb')) {
                mkdir('../media/pcb', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO pcb 
                (nombre, descripcion, hotswap, layout, rgb, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "ssisssdd",
                $nombre,
                $descripcion,
                $hotswap,
                $layout,
                $rgb,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'tools') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/tools/' . $nombre_imagen;
            $ruta_bd = 'media/tools/' . $nombre_imagen;

            if (!is_dir('../media/tools')) {
                mkdir('../media/tools', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO tools 
                (nombre, descripcion, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssdd",
                $nombre,
                $descripcion,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

        if ($tabla === 'lube') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = floatval($_POST['precio']);
        $precio_original = isset($_POST['precio_original']) && $_POST['precio_original'] !== ''
            ? floatval($_POST['precio_original']) : null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_imagen = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['imagen']['name']));
            $ruta_destino = '../media/lube/' . $nombre_imagen;
            $ruta_bd = 'media/lube/' . $nombre_imagen;

            if (!is_dir('../media/lube')) {
                mkdir('../media/lube', 0775, true);
            }

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                die("Error al subir la imagen.");
            }

            $stmt = $conn->prepare("INSERT INTO lube 
                (nombre, descripcion, imagen, precio, precio_original) 
                VALUES (?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssdd",
                $nombre,
                $descripcion,
                $ruta_bd,
                $precio,
                $precio_original
            );

            $stmt->execute();
        } else {
            die("Error: No se seleccionó imagen o hubo un problema con la subida.");
        }
    }

    break;

}

header("Location: ../index_admin.php");
exit();
