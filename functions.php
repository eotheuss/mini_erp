<?php

function getProducts($conn) {
    $sql = "SELECT p.*, s.stock FROM products p LEFT JOIN stock s ON p.id = s.product_id";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

function getProductById($conn, $id) {
    $sql = "SELECT p.*, s.stock FROM products p LEFT JOIN stock s ON p.id = s.product_id WHERE p.id = " . $id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function addProduct($conn, $name, $price, $variations) {
    $name = $conn->real_escape_string($name);
    $variations = $conn->real_escape_string($variations);
    $sql = "INSERT INTO products (name, price, variations) VALUES ('$name', $price, '$variations')";
    if ($conn->query($sql) === TRUE) {
        return $conn->insert_id;
    } else {
        return false;
    }
}

function updateProduct($conn, $id, $name, $price, $variations) {
    $name = $conn->real_escape_string($name);
    $variations = $conn->real_escape_string($variations);
    $sql = "UPDATE products SET name = '$name', price = $price, variations = '$variations' WHERE id = $id";
    return $conn->query($sql);
}

function updateProductStock($conn, $product_id, $stock) {
    // Verifica se já existe um registro de estoque para o produto
    $sql_check = "SELECT * FROM stock WHERE product_id = $product_id";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Se existir, atualiza
        $sql = "UPDATE stock SET stock = $stock WHERE product_id = $product_id";
    } else {
        // Se não existir, cria um novo
        $sql = "INSERT INTO stock (product_id, stock) VALUES ($product_id, $stock)";
    }

    return $conn->query($sql);
}

function getFrete($subtotal) {
    $frete = 20.00;
    if ($subtotal >= 52 && $subtotal <= 166.59) {
        $frete = 15.00;
    } elseif ($subtotal > 200) {
        $frete = 0.00;
    }
    return $frete;
}
?>
