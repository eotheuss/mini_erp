<?php
session_start();
define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/functions.php';

if (!empty($_SESSION['cart'])): ?>
    <table class="table table-striped">
        <thead>
            <tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            foreach ($_SESSION['cart'] as $item) {
                $itemSubtotal = $item['price'] * $item['quantity'];
                $subtotal += $itemSubtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($itemSubtotal, 2, ',', '.') ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Subtotal:</th>
                <th>R$ <?= number_format($subtotal, 2, ',', '.') ?></th>
            </tr>
            <tr>
                <th colspan="3" class="text-end">Frete:</th>
                <th>
                    <?php
                    $frete = getFrete($subtotal);
                    echo "R$ " . number_format($frete, 2, ',', '.');
                    ?>
                </th>
            </tr>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th>R$ <?= number_format($subtotal + $frete, 2, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <p>O carrinho está vazio.</p>
<?php endif; ?>
