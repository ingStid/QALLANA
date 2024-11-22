document.addEventListener("DOMContentLoaded", function () {
    const gallery = document.getElementById("product-gallery");
    // Llama al archivo PHP para obtener los datos del JSON
    fetch("php/products.php")
    .then(response => response.json())
    .then(data => {
    data.forEach(product => {
    const card = document.createElement("div");
    card.className = "product-card";
    card.innerHTML = `
    <img src="images/${product.imagen}" alt="${product.producto}">
    <h3>${product.producto}</h3>
   <p>${product.descripcion}</p>
   <p><strong>Precio:</strong> $${product.precio}</p>
   Cuantos:<input class="numero-max-3" type="number" id="cant${product.id}" min="0" value="1">
   <button class="boton-carrito" data-id="${product.id}" data-price="${product.precio}" data-name="${product.producto}">Añadir al carrito</button>
    `;
    gallery.appendChild(card);
    });
    })
    .catch(error => console.error("Error al cargar los productos:", error));
   });
   