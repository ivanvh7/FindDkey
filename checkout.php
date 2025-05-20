<?php
session_start();

// Simulación de productos en el carrito (reemplázalo por tu lógica real)
$carrito = $_SESSION['carrito'] ?? [];

function calcularTotal($carrito) {
    $total = 0;
    foreach ($carrito as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    return $total;
}

$total = calcularTotal($carrito);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link rel="stylesheet" href="css/checkout.css">
</head>
<body>

<header>Resumen del Pedido</header>

<main>
  <section id="cartSummary">
    <h2>Productos en tu Carrito</h2>

    <?php if (empty($carrito)): ?>
      <p>Tu carrito está vacío.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($carrito as $id => $producto): ?>
          <li class="cart-item">
            <img src="<?= htmlspecialchars($producto['imagen'] ?? 'assets/img/placeholder.png') ?>" alt="Imagen de producto">

            <div class="item-details">
              <p><strong><?= htmlspecialchars($producto['nombre']) ?></strong></p>
              <p><?= number_format($producto['precio'], 2) ?> € / unidad</p>

              <div class="cart-price" data-item-id="<?= $id ?>">
                <form class="cart-actions" data-item-id="<?= $id ?>">
                  <input type="hidden" name="id_producto" value="<?= $id ?>">
                  <span class="cantidad">Cantidad: <?= $producto['cantidad'] ?></span>
                </form>
              </div>
            </div>

            <div class="subtotal">
              <?= number_format($producto['precio'] * $producto['cantidad'], 2) ?> €
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="total" id="cart-total">Total: <?= number_format($total, 2) ?> €</div>
    <?php endif; ?>
  </section>

  <?php if (!empty($carrito)): ?>
    <section id="confirmSection">
      <div id="paymentDetails">
        <button onclick="confirmarPedido()">Confirmar Pedido</button>
      </div>
    </section>
  <?php endif; ?>
</main>

<footer>
  &copy; <?= date('Y') ?> FindDkey
</footer>

<script>
function confirmarPedido() {
  fetch('assets/confirmar_pedido.php', {
    method: 'POST',
    credentials: 'include' // 👈 ¡Esto es la clave!
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Pedido confirmado');
        window.location.href = 'index.php';
      } else {
        alert('Error: ' + (data.error || 'No se pudo confirmar el pedido.'));
      }
    })
    .catch(err => {
      console.error('Error al confirmar el pedido:', err);
      alert('Hubo un error al procesar el pedido.');
    });
}

</script>

<script src="js/script.js"></script>
</body>
</html>
