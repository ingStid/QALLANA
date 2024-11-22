// Manejo de Login
if (document.getElementById("login-form")) {
    document.getElementById("login-form").addEventListener("submit", function (e) {
        e.preventDefault();
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        fetch("php/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, password }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = "dashboard.html";
                } else {
                    document.getElementById("login-error").innerText = data.message;
                }
            });
    });
}

// Manejo de CRUD
if (document.getElementById("product-form")) {
    const fetchProducts = () => {
        fetch("php/products.php?action=read")
            .then((res) => res.json())
            .then((products) => {
                const tbody = document.querySelector("#product-table tbody");
                tbody.innerHTML = "";
                products.forEach((product) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${product.id}</td>
                            <td>${product.producto}</td>
                            <td>${product.descripcion}</td>
                            <td>$${product.precio.toFixed(2)}</td>
                            <td><img src="../images/${product.imagen}" alt="${product.producto}" width="50"></td>
                            <td>
                                <button onclick="editProduct(${product.id})">Editar</button>
                                <button onclick="deleteProduct(${product.id})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
            });
    };

    document.getElementById("product-form").addEventListener("submit", function (e) {
        e.preventDefault();
        const id = document.getElementById("product-id").value;
        const name = document.getElementById("product-name").value;
        const description = document.getElementById("product-description").value;
        const price = document.getElementById("product-price").value;
        const image = document.getElementById("product-image").files[0];

        const formData = new FormData();
        formData.append("id", id);
        formData.append("name", name);
        formData.append("description", description);
        formData.append("price", price);
        if (image) formData.append("image", image);

        fetch("php/products.php?action=" + (id ? "update" : "create"), {
            method: "POST",
            body: formData,
        }).then(() => {
            fetchProducts();
            document.getElementById("product-form").reset();
        });
    });

    fetchProducts();
}

function editProduct(id) {
    fetch("php/products.php?action=read&id=" + id)
        .then((res) => res.json())
        .then((product) => {
            document.getElementById("product-id").value = product.id;
            document.getElementById("product-name").value = product.producto;
            document.getElementById("product-description").value = product.descripcion;
            document.getElementById("product-price").value = product.precio;
        });
}

function deleteProduct(id) {
    if (confirm("¿Deseas eliminar este producto?")) {
        fetch("php/products.php?action=delete&id=" + id, { method: "POST" }).then(() => fetchProducts());
    }
}
