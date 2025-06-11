<?php
session_start();
define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/db.php';
require_once BASE_DIR . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_coupon') {
        $id = intval($_POST['id']);
        if (deleteCoupon($conn, $id)) {
            echo "Cupom excluído com sucesso!";
        } else {
            echo "Erro ao excluir o cupom.";
        }
    }
}
?>
