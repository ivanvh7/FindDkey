<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Todos los campos son obligatorios";
        header("Location: ../login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, nombre, password, role FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $email,
                'role' => $usuario['role']
            ];

            $_SESSION['carrito'] = [];

            $carrito_stmt = $conn->prepare("SELECT * FROM carritos WHERE usuario_id = ?");
            $carrito_stmt->bind_param("i", $usuario['id']);
            $carrito_stmt->execute();
            $carrito_result = $carrito_stmt->get_result();

            $tablas_permitidas = ['keyboards', 'switches', 'keycaps', 'stabilizers', 'silenciadores', 'pcb', 'deskmats', 'tools', 'lube'];

            while ($item = $carrito_result->fetch_assoc()) {
                if (!in_array($item['tabla'], $tablas_permitidas)) continue;

                $stmt_prod = $conn->prepare("SELECT nombre, precio, imagen FROM {$item['tabla']} WHERE id = ?");
                $stmt_prod->bind_param("i", $item['producto_id']);
                $stmt_prod->execute();
                $res_prod = $stmt_prod->get_result();

                if ($res_prod->num_rows > 0) {
                    $producto = $res_prod->fetch_assoc();
                    $item_id = $item['tabla'] . '_' . $item['producto_id'];

                    $_SESSION['carrito'][$item_id] = [
                        'id' => $item['producto_id'],
                        'tabla' => $item['tabla'],
                        'nombre' => $producto['nombre'],
                        'precio' => $producto['precio'],
                        'imagen' => $producto['imagen'],
                        'cantidad' => $item['cantidad']
                    ];
                }
                $stmt_prod->close();
            }

            if ($usuario['role'] === 'admin') {
    header("Location: ../index_admin.php");
} else {
    header("Location: ../index.php");
}
exit();

        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta";
        }
    } else {
        $_SESSION['login_error'] = "El usuario no existe";
    }

    $stmt->close();
    header("Location: ../login.php");
    exit();
}
?>
