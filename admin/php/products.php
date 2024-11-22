<?php
header('Content-Type: application/json');
$productsFile = '../../data/products.json';

if (!file_exists($productsFile)) {
    echo json_encode(array('success' => false, 'message' => 'Archivo de productos no encontrado.'));
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : null;

// Leer productos
$products = json_decode(file_get_contents($productsFile), true);

// Acciones CRUD
switch ($action) {
    case 'read':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $product = null;
            foreach ($products as $p) {
                if ($p['id'] === $id) {
                    $product = $p;
                    break;
                }
            }
            echo json_encode($product);
        } else {
            echo json_encode($products);
        }
        break;

    case 'create':
        $lastProduct = end($products);
        $id = $lastProduct ? $lastProduct['id'] + 1 : 1;
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        $image = handleImageUpload();

        $newProduct = array(
            'id' => $id,
            'producto' => $name,
            'descripcion' => $description,
            'imagen' => $image,
            'precio' => $price,
        );

        $products[] = $newProduct;
        saveProducts($products);
        echo json_encode(array('success' => true));
        break;

    case 'update':
        $id = intval($_POST['id']);
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        $image = handleImageUpload();

        foreach ($products as &$product) {
            if ($product['id'] === $id) {
                $product['producto'] = $name;
                $product['descripcion'] = $description;
                $product['precio'] = $price;
                if ($image) {
                    $product['imagen'] = $image;
                }
                break;
            }
        }
        saveProducts($products);
        echo json_encode(array('success' => true));
        break;

    case 'delete':
        $id = intval($_GET['id']);
        $newProducts = array();
        foreach ($products as $product) {
            if ($product['id'] !== $id) {
                $newProducts[] = $product;
            }
        }
        $products = $newProducts;
        saveProducts($products);
        echo json_encode(array('success' => true));
        break;

    default:
        echo json_encode(array('success' => false, 'message' => 'Acción no válida.'));
        break;
}

// Función para guardar productos en el archivo JSON
function saveProducts($products) {
    global $productsFile;
    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));
}

// Función para manejar la carga de imágenes
function handleImageUpload() {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/';
        $fileName = basename($_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            return $fileName;
        }
    }
    return null;
}
?>
