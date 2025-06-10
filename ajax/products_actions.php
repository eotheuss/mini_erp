<?php
session_start();
define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/db.php';
require_once BASE_DIR . '/functions.php';

// Ação para adicionar ou atualizar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_product') {
    $id = $_POST['product_id'] ?? null;
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $variations = $_POST['variations'] ?? null;
    $stock = intval($_POST['stock']);

    if ($id) {
        updateProduct($conn, $id, $name, $price, $variations);
        updateProductStock($conn, $id, $stock);
        echo "Produto atualizado com sucesso!";
    } else {
        $product_id = addProduct($conn, $name, $price, $variations);
        updateProductStock($conn, $product_id, $stock);
        echo "Produto adicionado com sucesso!";
    }
    exit;
}

// Ação para adicionar produto no carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'buy_product') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    // Pega o produto e estoque
    $product = getProductById($conn, $product_id);
    if (!$product) {
        echo "Produto não encontrado.";
        exit;
    }
    if ($product['stock'] < $quantity) {
        echo "Estoque insuficiente.";
        exit;
    }

    // Adiciona no carrinho da sessão
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
        ];
    }

    // Atualiza o estoque no banco
    updateProductStock($conn, $product_id, $product['stock'] - $quantity);

    echo "Produto adicionado ao carrinho com sucesso!";
    exit;
}
?>
