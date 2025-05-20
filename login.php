<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/registros.css">
</head>
<body>
    <video autoplay muted loop class="video-background">
        <source src="media/login.mp4" type="video/mp4">
        Tu navegador no soporta videos en HTML5.
    </video>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form id="loginForm" method="POST" action="assets/procesar_login.php">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>

            <button type="submit">Entrar</button>
        </form>

        <?php if (isset($_SESSION['login_error'])): ?>
    <p id="loginMessage" style="color: red;"><?= $_SESSION['login_error'] ?></p>
    <?php unset($_SESSION['login_error']); ?>
<?php endif; ?>


        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        <p><a href="index.php">← Volver al inicio</a></p>
    </div>
</body>
</html>
