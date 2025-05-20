<?php
$contraseña_plana = "admin123"; // cámbiala si quieres
$hash = password_hash($contraseña_plana, PASSWORD_DEFAULT);

echo "Contraseña original: " . $contraseña_plana . "<br>";
echo "Hash generado: <br><textarea rows='2' cols='80'>" . $hash . "</textarea>";
?>
