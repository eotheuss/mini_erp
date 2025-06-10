<?php
session_start();
define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . '/db.php';
require_once BASE_DIR . '/functions.php';

$products = getProducts($conn);

foreach ($products as $p) : ?>
    <tr>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td>R$ <?= number_format($p['price'], 2, ',', '.') ?></td>
        <td><?= $p['stock'] ?></td>
        <td><?= htmlspecialchars($p['variations']) ?></td>
        <td>
            <form class="buy-form d-flex" style="gap:0.25rem;">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="number" name="quantity" value="1" min="1"
                     class="form-control" style="width:80px;" required />
                <button type="submit" class="btn btn-success buy-button">Comprar</button>
            </form>
        </td>
        <td>
            <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $p['id'] ?>"
                data-name="<?= htmlspecialchars($p['name']) ?>" data-price="<?= $p['price'] ?>"
                data-variations="<?= htmlspecialchars($p['variations']) ?>"
                data-stock="<?= $p['stock'] ?>"
                >Editar</button>
        </td>
    </tr>
<?php endforeach;
