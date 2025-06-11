<?php
session_start();
define('BASE_DIR', __DIR__);
require_once BASE_DIR . '/db.php';
require_once BASE_DIR . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    if ($status === 'cancelado') {
        // Remover pedido
        deleteOrder($conn, $order_id);
        echo "Pedido removido com sucesso.";
    } else {
        // Atualizar status do pedido
        updateOrderStatus($conn, $order_id, $status);
        echo "Status do pedido atualizado com sucesso.";
    }
}

function deleteOrder($conn, $order_id) {
    $sql = "DELETE FROM orders WHERE id = $order_id";
    return $conn->query($sql);
}

function updateOrderStatus($conn, $order_id, $status) {
    $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    return $conn->query($sql);
}
?>
