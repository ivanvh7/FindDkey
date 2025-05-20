<?php
session_start();

include("assets/conexion.php");

function obtenerDatosFiltrados($tabla, $query) {
    global $conn;
    $permitidas = ['pcb', 'stabilizers', 'silenciadores', 'tools', 'lube'];
    if (!in_array($tabla, $permitidas)) return [];
    $sql = "SELECT * FROM $tabla";
    $params = [];

    if (!empty($query)) {
        $sql .= " WHERE nombre LIKE ? OR descripcion LIKE ?";
        $like = "%$query%";
        $params = [$like, $like];
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param("ss", ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $datos = [];
    while ($fila = $res->fetch_assoc()) {
        $fila['tabla'] = $tabla; // 👉 Añadimos la tabla al producto
        $datos[] = $fila;
    }
    return $datos;
}


$q = $_GET['q'] ?? '';

$tools = obtenerDatosFiltrados('tools', $q);
$lube = obtenerDatosFiltrados('lube', $q);
$stabilizers = obtenerDatosFiltrados('stabilizers', $q);
$silenciadores = obtenerDatosFiltrados('silenciadores', $q);
$pcbs = obtenerDatosFiltrados('pcb', $q);

function renderSection($id, $titulo, $datos) {
    if (count($datos) === 0) return;
    echo "<h2 id='$id' style='margin: 40px 20px 20px;'>{$titulo}</h2>";
    echo "<div class='products'>";
    foreach ($datos as $row) {
        echo "<a href='detalle_producto.php?tabla={$row['tabla']}&id={$row['id']}' class='product-card' style='text-decoration: none; color: inherit;'>";
        echo "<img src='{$row['imagen']}' alt='{$row['nombre']}'>";
        echo "<h2>{$row['nombre']}</h2>";
        echo "<p>{$row['descripcion']}</p>";
        echo "<p>";
        if (!empty($row['precio_original'])) {
            echo "<span style='text-decoration: line-through; color: #888;'>{$row['precio_original']} €</span> ";
        }
        echo "<strong>{$row['precio']} €</strong>";
        echo "</p>";
        echo "</a>";
    }
    echo "</div>";
}

$filtro = $_GET['filtro'] ?? '';

$tools = $filtro === '' || $filtro === 'tools' ? obtenerDatosFiltrados('tools', '') : [];
$lube = $filtro === '' || $filtro === 'lube' ? obtenerDatosFiltrados('lube', '') : [];
$stabilizers = $filtro === '' || $filtro === 'stabilizers' ? obtenerDatosFiltrados('stabilizers', '') : [];
$silenciadores = $filtro === '' || $filtro === 'silenciadores' ? obtenerDatosFiltrados('silenciadores', '') : [];
$pcbs = $filtro === '' || $filtro === 'pcb' ? obtenerDatosFiltrados('pcb', '') : [];

$todos = array_merge($tools, $lube, $stabilizers, $silenciadores, $pcbs);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Supplies</title>
  <link rel="stylesheet" href="css/teclados.css">
  <link rel="stylesheet" href="css/footer-info.css">
  <link rel="stylesheet" href="css/promo.css">
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/cart.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<!-- Encabezado -->
<header class="header">
    <div class="container">
        <a href="index.php" class="logo">
            <img src="media/logo.jpg" alt="FindDkey Logo" class="logo-img">
        </a>
        <nav class="nav">
            <!-- Mega menú para Keyboards -->
            <div class="nav-item">
    <a href="teclados.php">Keyboards ▾</a>
    <div class="mega-menu">
        <div class="column">
            <h4>Brands</h4>
            <a href="teclados.php?marca=Keychron">Keychron</a>
            <a href="teclados.php?marca=QwertyKeys">QwertyKeys</a>
            <a href="teclados.php?marca=Geon">Geon</a>
            <a href="teclados.php?marca=Eloquent">Eloquent</a>
            <a href="teclados.php?marca=Nuphy">Nuphy</a>
        </div>
        <div class="column">
            <h4>Keyboard Size</h4>
            <a href="teclados.php?tamanio=60%">60% Keyboards</a>
            <a href="teclados.php?tamanio=65%">65% Keyboards</a>
            <a href="teclados.php?tamanio=75%">75% Keyboards</a>
            <a href="teclados.php?tamanio=TKL">TKL Keyboards</a>
            <a href="teclados.php?tamanio=Full-size">Full Size</a>
        </div>
        <div class="column">
  <h4>Language</h4>
  <a href="teclados.php?lenguaje=ANSI">ANSI (English)</a>
  <a href="teclados.php?lenguaje=ISO">ISO (Spanish)</a>
</div>
<div class="column">
  <h4>Features</h4>
  <a href="teclados.php?features=Wireless">Wireless</a>
  <a href="teclados.php?features=Hot-swap">Hot-swap</a>
  <a href="teclados.php?features=RGB">RGB</a>
  <a href="teclados.php?features=Gasket mount">Gasket Mount</a>
</div>

    </div>
</div>

<!-- Mega menú para Keycaps -->
<div class="nav-item">
  <a href="keycaps.php">Keycaps ▾</a>
  <div class="mega-menu">
    <div class="column">
      <h4>Material</h4>
      <a href="keycaps.php?material=ABS">ABS Keycaps</a>
      <a href="keycaps.php?material=PBT">PBT Keycaps</a>
    </div>
    <div class="column">
      <h4>Brands</h4>
      <a href="keycaps.php?marca=GMK">GMK</a>
      <a href="keycaps.php?marca=Pbtfans">Pbtfans</a>
      <a href="keycaps.php?marca=Signature Plastics">Signature Plastics</a>
    </div>
  </div>
</div>

<div class="nav-item dropdown">
  <a href="switches.php">Switches ▾</a>
  <div class="dropdown-menu">
    <a href="switches.php?tipo=Clicky">Clicky</a>
    <a href="switches.php?tipo=Lineal">Lineal</a>
    <a href="switches.php?tipo=Táctil">Táctil</a>
  </div>
</div>

            <!-- Dropdown Accesorios -->
<div class="nav-item dropdown">
    <a href="supplies.php">Supplies ▾</a>
    <div class="dropdown-menu">
        <a href="supplies.php?filtro=tools">Tools</a>
        <a href="supplies.php?filtro=lube">Lube</a>
        <a href="supplies.php?filtro=stabilizers">Stabilizers</a>
        <a href="supplies.php?filtro=silenciadores">Switch mods</a>
        <a href="supplies.php?filtro=pcb">PCB</a>
    </div>
</div>

<!-- Item Deskmats (simple, sin desplegable) -->
<div class="nav-item">
  <a href="deskmats.php">Deskmats</a>
</div>


<div class="nav-icons">
  <!-- Búsqueda -->
  <button class="icon-button search-toggle" onclick="toggleSearch()" aria-label="Buscar">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="white" fill="none" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
    </svg>
  </button>

  <!-- Perfil -->
<?php if (isset($_SESSION['usuario'])): ?>
  <div class="user-dropdown" style="position: relative;">
    <button class="user-toggle" style="color: white; font-weight: 500; background: none; border: none; cursor: pointer;">
      <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?> ▾
    </button>
    <div class="user-menu" style="position: absolute; text-align:center; top: 100%; right: 0; background: white; border: 1px solid #ccc; border-radius: 6px; padding: 0.5rem; display: none; z-index: 1000;">
      <a href="assets/logout.php" style="text-decoration: none; color: #333; display: block;">Cerrar sesión</a>
    </div>
  </div>

  <script>
    const userToggle = document.querySelector('.user-toggle');
    const userMenu = document.querySelector('.user-menu');

    userToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function () {
      userMenu.style.display = 'none';
    });
  </script>
<?php else: ?>
  <button class="icon-button" onclick="location.href='registro.php'" aria-label="Perfil">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="white" fill="none" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 
               3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 
               7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 
               12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
    </svg>
  </button>
<?php endif; ?>

  <!-- Carrito -->
  <button class="icon-button" onclick="toggleCart()" aria-label="Carrito">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="white" fill="none" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 
               1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 
               0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 
               5.513 7.5h12.974c.576 0 1.059.435 1.119 
               1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 
               .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 
               1 1-.75 0 .375.375 0 0 1 .75 0Z" />
    </svg>
  </button>
</div>
    </div>

    <form id="searchForm" class="search-full hidden" action="buscar.php" method="get">
  <input type="text" id="searchInput" name="q" placeholder="Search" />
  <button type="submit" class="icon-button" aria-label="Buscar">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="white" fill="none" stroke-width="1.5" width="24" height="24">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
  </svg>
</button>

  <button type="button" class="close-search" onclick="toggleSearch()">✕</button>
</form>

</header>

<main style="margin-top:60px;">
    <h1 style="text-align:left;">Supplies</h1>
  <nav class="breadcrumb">
    <a href="index.php">Home</a> / <span>Supplies</span>
  </nav>

<form method="get" action="supplies.php" class="filtro-form">
  <label for="filtro">Supplies</label>
  <select name="filtro" id="filtro" onchange="this.form.submit()" style="padding: 6px; border-radius: 5px;">
    <option value="">Todos</option>
    <option value="tools" <?php if ($filtro == 'tools') echo 'selected'; ?>>Tools</option>
    <option value="lube" <?php if ($filtro == 'lube') echo 'selected'; ?>>Lube</option>
    <option value="stabilizers" <?php if ($filtro == 'stabilizers') echo 'selected'; ?>>Stabilizers</option>
    <option value="silenciadores" <?php if ($filtro == 'silenciadores') echo 'selected'; ?>>Switch Mods</option>
    <option value="pcb" <?php if ($filtro == 'pcb') echo 'selected'; ?>>PCBs</option>
  </select>
</form>

<div class="products">
  <?php foreach ($todos as $item): ?>
    <a href="detalle_producto.php?tabla=<?= urlencode($item['tabla']) ?>&id=<?= urlencode($item['id']) ?>" class="product-card" style="text-decoration: none; color: inherit;">
      <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>">
      <h2><?= htmlspecialchars($item['nombre']) ?></h2>
      <p><?= htmlspecialchars($item['descripcion']) ?></p>
      <p>
        <?php if (!empty($item['precio_original']) && $item['precio_original'] > $item['precio']): ?>
          <span style="text-decoration: line-through; color: #888;"><?= number_format($item['precio_original'], 2) ?> €</span>
        <?php endif; ?>
        <strong><?= number_format($item['precio'], 2) ?> €</strong>
      </p>
    </a>
  <?php endforeach; ?>
</div>

</main>

<section class="highlight-section">
  <video autoplay muted loop playsinline class="highlight-video">
    <source src="media/gmk-blot.mp4" type="video/mp4">
    Tu navegador no soporta video HTML5.
  </video>
  <div class="highlight-content">
    <h2>GMK CYL BLOT</h2>
    <p>Keycaps sangrientas inspiradas en rituales vikingos. Hechas en Alemania por GMK.</p>
    <a href="detalle_producto.php?tabla=keycaps&id=14" class="highlight-btn">Shop Now</a>
  </div>
</section>

    <section class="footer-info">
<div class="shipping-bar scroll-loop">
  <div class="scroll-track">
    <div class="scroll-content">
      📦 Free shipping on orders over €50! &nbsp;&nbsp;&nbsp;
      ✨ Fast delivery across Europe! &nbsp;&nbsp;&nbsp;
      💳 Secure checkout guaranteed!
      📦 Free shipping on orders over €50! &nbsp;&nbsp;&nbsp;
      ✨ Fast delivery across Europe! &nbsp;&nbsp;&nbsp;
      💳 Secure checkout guaranteed!
      📦 Free shipping on orders over €50! &nbsp;&nbsp;&nbsp;
      ✨ Fast delivery across Europe! &nbsp;&nbsp;&nbsp;
      💳 Secure checkout guaranteed!
    </div>
    <div class="scroll-content">
      📦 Free shipping on orders over €50! &nbsp;&nbsp;&nbsp;
      ✨ Fast delivery across Europe! &nbsp;&nbsp;&nbsp;
      💳 Secure checkout guaranteed!
      📦 Free shipping on orders over €50! &nbsp;&nbsp;&nbsp;
      ✨ Fast delivery across Europe! &nbsp;&nbsp;&nbsp;
      💳 Secure checkout guaranteed!
      📦 Free shipping on orders over €50! &nbsp;&nbsp;&nbsp;
      ✨ Fast delivery across Europe! &nbsp;&nbsp;&nbsp;
      💳 Secure checkout guaranteed!
    </div>
  </div>
</div>


  <div class="eu-banner">
    <img src="media/banner.jpg" alt="Financiado por la Unión Europea">
  </div>

  <section class="footer">
  <div class="footer-columns">
    <div class="footer-col">
      <h2>Info</h2>
      <ul>
        <li><a href="#">About us</a></li>
        <li><a href="#">Support</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Newsletter</a></li>
        <li><a href="#">Returns</a></li>
        <li><a href="#">Terms</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">My account</a></li>
      </ul>
    </div>


    <div class="footer-col">
      <h2>Get in touch</h2>
      <p><a href="mailto:info@tecladocustom.com">✉ Email us</a></p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
      </div>
    </div>

    <div class="footer-col">
      <h2>We accept</h2>
      <div class="payments">
        <img src="media/payment.png" alt="Payments">
      </div>
      <label>Language</label>
      <select>
        <option>English</option>
        <option>Español</option>
      </select>

      <label>Currency</label>
      <select>
        <option>Spain (EUR €)</option>
        <option>USD ($)</option>
      </select>
    </div>
  </div>

  <div class="footer-copy">
    © 2025 FindDkey · <a href="#">Terms</a> · <a href="#">Privacy Policy</a>
  </div>
</section>

  <!-- Modal de carrito -->
<div id="cart-modal" class="cart-modal hidden">
  <div class="cart-content">
    <button class="close-btn" onclick="toggleCart()">Cerrar ✕</button>

    <?php if (empty($_SESSION['carrito'])): ?>
      <p class="cart-empty">Tu carrito está vacío.</p>
    <?php else: ?>
      <ul class="cart-list">
        <?php foreach ($_SESSION['carrito'] as $item_id => $item): ?>
<li class="cart-item" data-item-id="<?php echo $item_id; ?>">
  <img src="<?php echo $item['imagen']; ?>" alt="" class="cart-img">
  <div class="cart-info">
    <p class="cart-name"><?php echo htmlspecialchars($item['nombre']); ?></p>
    <p class="cart-price">
      <span class="unit-price"><?php echo number_format($item['precio'], 2); ?> €</span> x 
      <span class="cantidad-text"><?php echo $item['cantidad']; ?></span> = 
      <span class="precio-total"><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?> €</span>
    </p>
    <form action="assets/actualizar_carrito.php" method="POST" class="cart-actions">
      <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
      <input type="hidden" name="tabla" value="<?php echo $item['tabla']; ?>">
      <button type="submit" name="accion" value="incrementar" class="btn-cart btn-plus">+</button>
      <button type="submit" name="accion" value="decrementar" class="btn-cart btn-minus">-</button>
      <button type="submit" name="accion" value="eliminar" class="btn-cart btn-remove">Eliminar</button>
    </form>
  </div>
</li>
        <?php endforeach; ?>
      </ul>

      <div class="cart-footer">
        <form action="assets/vaciar_carrito.php" method="POST" class="cart-clear-form">
          <button type="submit" class="btn-clear">Vaciar carrito</button>
        </form>

        <form action="checkout.php" method="POST" class="cart-checkout-form">
          <button type="submit" class="btn-checkout">Realizar compra</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
(function(){
  if(!window.chatbase || window.chatbase("getState") !== "initialized"){
    window.chatbase = (...args) => {
      if (!window.chatbase.q) window.chatbase.q = [];
      window.chatbase.q.push(args);
    };
    window.chatbase = new Proxy(window.chatbase, {
      get(target, prop) {
        if (prop === "q") return target.q;
        return (...args) => target(prop, ...args);
      }
    });
  }

  const onLoad = function() {
    const script = document.createElement("script");
    script.src = "https://www.chatbase.co/embed.min.js";
    script.id = "KM5S8DQPRwHcQWpK4dj7T"; // Tu chatbotId real
    script.domain = "www.chatbase.co";
    document.body.appendChild(script);
  };

  if (document.readyState === "complete") {
    onLoad();
  } else {
    window.addEventListener("load", onLoad);
  }
})();
</script>

<script src="js/script.js"></script>
</body>
</html>
