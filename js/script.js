
function toggleCart() {
    const modal = document.getElementById('cart-modal');
    if (modal.classList.contains('active')) {
        modal.classList.remove('active');
        setTimeout(() => modal.classList.add('hidden'), 300);
    } else {
        modal.classList.remove('hidden');
        modal.classList.add('active');
    }
}

// HEADER SCROLL

window.addEventListener('scroll', function() {
  const header = document.querySelector('.header');
  if (window.scrollY > 10) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

// FILTROS

document.addEventListener("DOMContentLoaded", function () {
  const formulario = document.querySelector(".filtro-form");
  const campos = ["marca", "tamanio", "lenguaje", "features", "material", "tipo"];

  campos.forEach(id => {
    const campo = document.getElementById(id);
    if (campo) {
      campo.addEventListener("change", () => formulario.submit());
    }
  });
});

// BUSCADOR

 function toggleSearch() {
    const form = document.getElementById("searchForm");
    const input = document.getElementById("searchInput");
    const header = document.querySelector(".header");

    form.classList.toggle("hidden");
    header.classList.toggle("hide-elements");

    if (!form.classList.contains("hidden")) {
      input.focus();
    }
  }

  // Detectar clic fuera del buscador
  document.addEventListener("click", function (e) {
    const form = document.getElementById("searchForm");
    const searchBtn = document.querySelector(".search-toggle");
    const header = document.querySelector(".header");

    const isInsideForm = form.contains(e.target);
    const isSearchBtn = searchBtn.contains(e.target); // Mejora: incluye clicks dentro del SVG

    if (!isInsideForm && !isSearchBtn) {
      if (document.getElementById("searchInput").value.trim() === '') {
        form.classList.add("hidden");
        header.classList.remove("hide-elements");
      }
    }
  });

  // LOGIN

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            document.getElementById('loginMessage').innerText = data.error;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loginMessage').innerText = 'Error en la conexión con el servidor';
    });
});

// CHATBOT

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

// ACCIONES CARRITO

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.cart-actions').forEach(form => {
    // Detectar clics en los botones + y −
    form.querySelectorAll('button').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();

        const accion = this.dataset.accion; // sumar o restar
        const formData = new FormData(form);
        const itemId = form.dataset.itemId;

        formData.append('accion', accion);

        fetch('assets/actualizar_carrito.php', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const priceLine = document.querySelector(`.cart-price[data-item-id="${itemId}"]`);
            if (priceLine) {
              if (data.cantidad > 0) {
                priceLine.querySelector('.cantidad').textContent = data.cantidad;
              } else {
                const item = priceLine.closest('.cart-item');
                if (item) item.remove();
              }
            }

            // Actualizar total del carrito (opcional)
            const totalDisplay = document.getElementById('cart-total');
            if (totalDisplay && data.total !== undefined) {
              totalDisplay.textContent = `Total: ${data.total.toFixed(2)} €`;
            }
          } else {
            alert(data.error || 'Error al actualizar el carrito');
          }
        })
        .catch(error => {
          console.error('Error en la solicitud:', error);
          alert('Error de conexión con el servidor');
        });
      });
    });
  });
});

// CARRITO

document.addEventListener('DOMContentLoaded', () => {
    const cartItemsElement = document.getElementById('cartItems');
    const totalPriceElement = document.getElementById('totalPrice');
    const paymentDetailsElement = document.getElementById('paymentDetails');
    const confirmPurchaseButton = document.getElementById('confirmPurchase');

    // Cargar carrito desde localStorage
    const carrito = JSON.parse(localStorage.getItem('cart')) || []; // Unificación de clave
    const metodoDePago = JSON.parse(localStorage.getItem('metodoDePago'));

    // Mostrar los productos del carrito
    function mostrarCarrito() {
        if (carrito.length === 0) {
            cartItemsElement.innerHTML = '<p>El carrito está vacío.</p>';
            confirmPurchaseButton.disabled = true;
            return;
        }

        cartItemsElement.innerHTML = '';
        let total = 0;

        carrito.forEach(item => {
            const li = document.createElement('li');
            li.classList.add('cart-item');

            const img = document.createElement('img');
            img.src = item.img;
            img.alt = item.name;

            const details = document.createElement('div');
            details.classList.add('item-details');
            details.innerHTML = `
                <p><strong>${item.name}</strong></p>
                <p>Cantidad: ${item.quantity}</p>
                <p>Subtotal: ${(item.price * item.quantity).toFixed(2)}€</p>
            `;

            li.appendChild(img);
            li.appendChild(details);
            cartItemsElement.appendChild(li);

            total += item.price * item.quantity;
        });

        totalPriceElement.textContent = `${total.toFixed(2)}€`;
        confirmPurchaseButton.disabled = !metodoDePago; // Habilita si hay método de pago
    }

    // Mostrar método de pago
    function mostrarMetodoDePago() {
        if (!metodoDePago) {
            paymentDetailsElement.innerHTML = `
                <p>No hay método de pago configurado.</p>
                <button id="addPaymentMethod">Agregar Método de Pago</button>
            `;

            document.getElementById('addPaymentMethod').addEventListener('click', () => {
                window.location.href = 'payment.html';
            });
        } else {
            paymentDetailsElement.innerHTML = `
                <p><strong>Nombre del titular:</strong> ${metodoDePago.name}</p>
                <p><strong>Número de tarjeta:</strong> **** **** **** ${metodoDePago.cardNumber.slice(-4)}</p>
                <button id="changePaymentMethod">Cambiar Método de Pago</button>
            `;

            document.getElementById('changePaymentMethod').addEventListener('click', () => {
                window.location.href = 'payment.html';
            });
        }

        confirmPurchaseButton.disabled = carrito.length === 0 || !metodoDePago;
    }

    // Confirmar compra
    confirmPurchaseButton.addEventListener('click', () => {
        if (!metodoDePago || carrito.length === 0) {
            alert('Por favor, asegúrate de que el carrito y el método de pago estén configurados.');
            return;
        }

        alert('Compra realizada con éxito. Gracias por tu pedido.');
        localStorage.removeItem('cart'); // Vaciar el carrito
        window.location.href = 'index.html'; // Redirigir a la página principal
    });

    // Inicializar
    mostrarCarrito();
    mostrarMetodoDePago();
});


