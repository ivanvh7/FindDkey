<?php
function autenticarUsuario($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT id, nombre, email, password, role FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            // Devolver datos del usuario (sin la contraseña)
            unset($usuario['password']);
            return [
                'success' => true,
                'usuario' => $usuario
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Contraseña incorrecta.'
            ];
        }
    } else {
        return [
            'success' => false,
            'error' => 'No existe una cuenta con ese correo.'
        ];
    }
}
?>
