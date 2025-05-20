<?php
session_start();
require 'assets/conexion.php';

$mensaje = "";
$color = "red"; // color por defecto (error)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmar = trim($_POST["confirm_password"]);
    $role = (isset($_POST["is_admin"]) && $_POST["is_admin"] == "1") ? "admin" : "user";

    if (empty($nombre) || empty($email) || empty($password) || empty($confirmar)) {
        $mensaje = "Todos los campos son obligatorios";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo electrónico inválido";
    } elseif ($password !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden";
    } else {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "El email ya está registrado";
        } else {
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $_SESSION['usuario'] = ['nombre' => $nombre, 'email' => $email, 'role' => $role];
                $color = "green";
                $mensaje = "¡Registro exitoso! Bienvenido a la familia.";
            } else {
                $mensaje = "Error al registrar el usuario";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link rel="stylesheet" href="css/registros.css">
</head>
<body>
  <video autoplay muted loop class="video-background">
    <source src="media/login.mp4" type="video/mp4">
    Tu navegador no soporta videos HTML5.
  </video>

  <div class="login-container">
    <h2><?= isset($_GET["admin"]) ? "Registrar Administrador" : "Registro" ?></h2>
    <?php if ($mensaje): ?>
      <p style="color: <?= $color ?>; font-weight: bold;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="post">
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" required>

      <label for="email">Correo Electrónico</label>
      <input type="email" name="email" required>

      <label for="password">Contraseña</label>
      <input type="password" name="password" required>

      <label for="confirm_password">Repita su contraseña</label>
      <input type="password" name="confirm_password" required>

      <?php if (isset($_GET["admin"])): ?>
        <input type="hidden" name="is_admin" value="1">
      <?php endif; ?>

      <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    <p><a href="index.php">← Volver al inicio</a></p>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', function(event) {
      event.preventDefault();

      const password = document.getElementById('password').value;
      const confirm = document.getElementById('confirm_password').value;

      if (password !== confirm) {
        document.getElementById('registerMessage').innerText = 'Las contraseñas no coinciden.';
        return;
      }

      const formData = new FormData(this);
      const isAdmin = window.location.search.includes("admin=1");
      const url = isAdmin ? "registro.php?admin=1" : "registro.php";

      fetch(url, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          if (data.bienvenida) alert("¡Registro exitoso! Ya formas parte de la familia.");
          window.location.href = data.redirect;
        } else {
          document.getElementById('registerMessage').innerText = data.error;
        }
      })
      .catch(err => {
        console.error(err);
        document.getElementById('registerMessage').innerText = 'Error en la conexión con el servidor.';
      });
    });
  </script>
</body>
</html>
