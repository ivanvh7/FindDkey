<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['role'] !== 'admin') {
    die("Acceso no autorizado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // ✅ Eliminar usuario
    if ($accion === 'eliminar' && isset($_POST['usuario_id'])) {
        $usuario_id = intval($_POST['usuario_id']);

        if ($usuario_id === $_SESSION['usuario']['id']) {
            die("No puedes eliminar tu propia cuenta.");
        }

        // Elimina carritos y pedidos del usuario primero
        $conn->query("DELETE FROM carritos WHERE usuario_id = $usuario_id");
        $conn->query("DELETE FROM pedidos WHERE usuario_id = $usuario_id");

        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->close();
    }

    // ✅ Crear usuario nuevo
    if ($accion === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($nombre && $email && $password && in_array($role, ['user', 'admin'])) {
            // Verificar duplicado
            $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $check->close();
                die("Ya existe un usuario con ese email.");
            }
            $check->close();

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $email, $password_hashed, $role);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Todos los campos son obligatorios y válidos.");
        }
    }
}

header("Location: ../index_admin.php");
exit();
