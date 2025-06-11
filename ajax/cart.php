<?php
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

    <!-- Formulário de CEP -->
    <div class="mb-3">
        <label for="cep" class="form-label">CEP:</label>
        <input type="text" class="form-control" id="cep" name="cep" required>
    </div>
    <div class="mb-3">
        <label for="endereco" class="form-label">Endereço:</label>
        <input type="text" class="form-control" id="endereco" name="endereco" readonly>
    </div>
    <div class="mb-3">
        <label for="bairro" class="form-label">Bairro:</label>
        <input type="text" class="form-control" id="bairro" name="bairro" readonly>
    </div>
    <div class="mb-3">
        <label for="cidade" class="form-label">Cidade:</label>
        <input type="text" class="form-control" id="cidade" name="cidade" readonly>
    </div>
    <div class="mb-3">
        <label for="estado" class="form-label">Estado:</label>
        <input type="text" class="form-control" id="estado" name="estado" readonly>
    </div>

<?php else: ?>
    <p>O carrinho está vazio.</p>
<?php endif; ?>
