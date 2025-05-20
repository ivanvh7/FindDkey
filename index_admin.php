<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}


require_once 'assets/conexion.php'; // Tu archivo de conexión a MySQL

// Consultas
$usuarios = $conn->query("SELECT * FROM usuarios");
$pedidos = $conn->query("SELECT * FROM pedidos");
$keyboards = $conn->query("SELECT * FROM keyboards");
$keycaps = $conn->query("SELECT * FROM keycaps");
$switches = $conn->query("SELECT * FROM switches");
$deskmats = $conn->query("SELECT * FROM deskmats");
$stabilizers = $conn->query("SELECT * FROM stabilizers");
$silenciadores = $conn->query("SELECT * FROM silenciadores");
$pcb = $conn->query("SELECT * FROM pcb");
$tools = $conn->query("SELECT * FROM tools");
$lube = $conn->query("SELECT * FROM lube");
$carritos = $conn->query("SELECT * FROM carritos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/admin_form.css">

</head>
<body>
    <h1>Panel de Administración - FindDkey</h1>

    <div class="accordion-section">
    <button class="accordion-header">Usuarios Registrados</button>
    <div class="accordion-content">
<div class="section">
    <h2>Usuarios Registrados</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Fecha Registro</th><th>Acciones</th>
        </tr>
        <?php while($row = $usuarios->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/manejo_usuarios.php" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['fecha_registro'] ?></td>
                    <td>
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="usuario_id" value="<?= $row['id'] ?>">
                        <button type="submit">Eliminar</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Crear nuevo usuario</h3>
    <form method="POST" action="assets/manejo_usuarios.php" class="form-inline">
        <input type="hidden" name="accion" value="crear">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <label>Rol:</label>
        <select name="role" required>
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>

        <button type="submit">Crear usuario</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Pedidos Realizados</button>
    <div class="accordion-content">

<div class="section">
    <h2>Pedidos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario ID</th>
            <th>Tipo Producto</th>
            <th>Producto ID</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        <?php while($row = $pedidos->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/actualizar_estado.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['usuario_id'] ?></td>
                    <td><?= $row['producto_tipo'] ?></td>
                    <td><?= $row['producto_id'] ?></td>
                    <td><?= $row['fecha_pedido'] ?></td>
                    <td>
                        <select name="nuevo_estado">
                            <option value="Pendiente" <?= $row['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="Procesado" <?= $row['estado'] === 'Procesado' ? 'selected' : '' ?>>Procesado</option>
                            <option value="Enviado" <?= $row['estado'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                            <option value="Entregado" <?= $row['estado'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="pedido_id" value="<?= $row['id'] ?>">
                        <button type="submit">Actualizar</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Teclados en inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Keyboards</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Marca</th><th>Tamaño</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $keyboards->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['marca'] ?></td>
                    <td><?= $row['tamanio'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required>
                        €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="keyboards">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

<h3>Añadir nuevo Keyboard</h3>
<form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
    <input type="hidden" name="tabla" value="keyboards">

    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Descripción:</label>
    <input type="text" name="descripcion" maxlength="255">

    <label>Marca:</label>
    <input type="text" name="marca" required>

    <label>Tamaño:</label>
    <select name="tamanio" required>
        <option value="Full-size">Full-size</option>
        <option value="TKL">TKL</option>
        <option value="75%">75%</option>
        <option value="65%">65%</option>
        <option value="60%">60%</option>
        <option value="40%">40%</option>
    </select>

    <label>Lenguaje:</label>
    <select name="lenguaje" required>
        <option value="ANSI">ANSI</option>
        <option value="ISO">ISO</option>
    </select>

    <label>Características (features):</label>
    <input type="text" name="features" maxlength="255">

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" required>

    <label>Precio original (opcional):</label>
    <input type="number" name="precio_original" step="0.01">

    <label>Imagen:</label>
    <input type="file" name="imagen" accept="image/*" required>

    <button type="submit" name="accion" value="agregar">Añadir</button>
</form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Keycaps en Inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Keycaps</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Marca</th><th>Material</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $keycaps->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['marca'] ?></td>
                    <td><?= $row['material'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="keycaps">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nuevo Keycap</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="keycaps">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Marca:</label>
        <select name="marca" required>
            <option value="">-- Selecciona --</option>
            <option value="GMK">GMK</option>
            <option value="Pbtfans">Pbtfans</option>
            <option value="Signature Plastics">Signature Plastics</option>
        </select>

        <label>Material:</label>
        <select name="material" required>
            <option value="">-- Selecciona --</option>
            <option value="ABS">ABS</option>
            <option value="PBT">PBT</option>
        </select>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Switches en inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Switches</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Tipo</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $switches->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="switches">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nuevo Switch</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="switches">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Tipo:</label>
        <select name="tipo" required>
            <option value="">-- Selecciona --</option>
            <option value="Lineal">Lineal</option>
            <option value="Táctil">Táctil</option>
            <option value="Clicky">Clicky</option>
        </select>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Alfombrillas en inventario</button>
    <div class="accordion-content">

 <div class="section">
    <h2>Alfombrillas</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Tamaño</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $deskmats->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['tamanio'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="deskmats">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nueva Deskmat</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="deskmats">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Tamaño:</label>
        <select name="tamanio" required>
            <option value="">-- Selecciona --</option>
            <option value="900x400">900x400</option>
            <option value="800x300">800x300</option>
            <option value="XL">XL</option>
            <option value="M">M</option>
            <option value="Custom">Custom</option>
        </select>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Stabilizadores en inventario</button>
    <div class="accordion-content">

    <div class="section">
    <h2>Stabilizers</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Tipo</th><th>Lubricado</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $stabilizers->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td><?= $row['lubricado'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="stabilizers">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nuevo Stabilizer</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="stabilizers">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Tipo:</label>
        <select name="tipo" required>
            <option value="Plate-mounted">Plate-mounted</option>
            <option value="PCB-mounted">PCB-mounted</option>
        </select>

        <label>Lubricado:</label>
        <select name="lubricado" required>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Silenciadores en inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Silenciadores</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Tipo</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $silenciadores->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="silenciadores">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nuevo Silenciador</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="silenciadores">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Tipo:</label>
        <select name="tipo" required>
            <option value="Foam PCB">Foam PCB</option>
            <option value="Foam Case">Foam Case</option>
            <option value="Silicona Estabilizadores">Silicona Estabilizadores</option>
        </select>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">PCBS en inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>PCBs</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Layout</th><th>Hot-swap</th><th>RGB</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $pcb->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['layout'] ?></td>
                    <td><?= $row['hotswap'] ? 'Sí' : 'No' ?></td>
                    <td><?= $row['rgb'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="pcb">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nueva PCB</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="pcb">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Layout:</label>
        <select name="layout" required>
            <option value="ANSI">ANSI</option>
            <option value="ISO">ISO</option>
            <option value="HHKB">HHKB</option>
        </select>

        <label>Hot-swap:</label>
        <select name="hotswap" required>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>

        <label>RGB:</label>
        <select name="rgb" required>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Herramientas (TOOLS) en inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Herramientas (Tools)</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $tools->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['descripcion'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="tools">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nueva herramienta</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="tools">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>

<div class="accordion-section">
    <button class="accordion-header">Lube en inventario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Lubricantes (Lube)</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Acciones</th>
        </tr>
        <?php while($row = $lube->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="assets/acciones_productos.php">
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['descripcion'] ?></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="number" name="nuevo_precio" step="0.01" value="<?= $row['precio'] ?>" required> €
                    </td>
                    <td>
                        <button type="submit" name="accion" value="editar_precio">Actualizar Precio</button>
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="tabla" value="lube">
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Añadir nuevo lubricante</h3>
    <form method="POST" action="assets/acciones_productos.php" enctype="multipart/form-data">
        <input type="hidden" name="tabla" value="lube">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" maxlength="255">

        <label>Precio:</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Precio original (opcional):</label>
        <input type="number" name="precio_original" step="0.01">

        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*" required>

        <button type="submit" name="accion" value="agregar">Añadir</button>
    </form>
</div>
</div>
</div>


<div class="accordion-section">
    <button class="accordion-header">Carrito Usuario</button>
    <div class="accordion-content">

<div class="section">
    <h2>Carritos Guardados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario ID</th>
            <th>Producto</th>
            <th>Tabla</th>
            <th>Cantidad</th>
            <th>Fecha</th>
        </tr>
        <?php 
        $tablas_permitidas = ['keyboards', 'switches', 'keycaps', 'stabilizers', 'silenciadores', 'pcb', 'deskmats', 'tools', 'lube'];
        while($row = $carritos->fetch_assoc()): 
            $producto_nombre = 'N/A';
            if (in_array($row['tabla'], $tablas_permitidas)) {
                $tabla = $row['tabla'];
                $producto_id = $row['producto_id'];
                $stmt = $conn->prepare("SELECT nombre FROM $tabla WHERE id = ?");
                $stmt->bind_param("i", $producto_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $producto = $res->fetch_assoc();
                    $producto_nombre = $producto['nombre'];
                }
                $stmt->close();
            }
        ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['usuario_id'] ?></td>
                <td><?= $producto_nombre ?></td>
                <td><?= $row['tabla'] ?></td>
                <td><?= $row['cantidad'] ?></td>
                <td><?= $row['fecha'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</div>
</div>

<script>
document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
        const section = header.parentElement;
        section.classList.toggle('open');
    });
});
</script>



</body>
</html>
