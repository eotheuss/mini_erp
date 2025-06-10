<?php
session_start();
define('BASE_DIR', __DIR__);
require_once BASE_DIR . '/db.php';
require_once BASE_DIR . '/functions.php';

$products = getProducts($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Mini ERP - Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h1>Produtos</h1>

        <!-- Formulário para adicionar/editar produto -->
        <form id="product-form" class="mb-4">
            <input type="hidden" name="product_id" value="" id="product_id" />
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nome" required id="name" />
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Preço" required
                        id="price" />
                </div>
                <div class="col-md-2">
                    <input type="number" name="stock" class="form-control" placeholder="Estoque" required id="stock" />
                </div>
                <div class="col-md-3">
                    <input type="text" name="variations" class="form-control" placeholder="Variações"
                        id="variations" />
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </div>
            </div>
        </form>

        <!-- Lista de produtos -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Variações</th>
                    <th>Comprar</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="product-list">
                <?php foreach ($products as $p) : ?>
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
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr />
        <h2>Carrinho</h2>
        <div id="cart-container">
            <?php include(BASE_DIR . '/ajax/cart.php'); ?>
        </div>

    </div>

    <script>
    $(document).ready(function() {
        // Função para atualizar a lista de produtos na página
        function loadProducts() {
            $.ajax({
                url: 'ajax/get_products.php',
                type: 'GET',
                success: function(data) {
                    $('#product-list').html(data);
                }
            });
        }

        // Função para atualizar o carrinho na página
        function loadCart() {
            $.ajax({
                url: 'ajax/cart.php',
                type: 'GET',
                success: function(data) {
                    $('#cart-container').html(data);
                }
            });
        }

        // Preencher formulário para edição ao clicar em editar
        $(document).on('click', '.edit-btn', function() {
            $('#product_id').val($(this).data('id'));
            $('#name').val($(this).data('name'));
            $('#price').val($(this).data('price'));
            $('#variations').val($(this).data('variations'));
            $('#stock').val($(this).data('stock'));
        });

        // Submeter formulário de produto via AJAX
        $('#product-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'ajax/products_actions.php',
                type: 'POST',
                data: $(this).serialize() + '&action=save_product',
                success: function(response) {
                    alert(response);
                    $('#product_id').val('');
                    $('#name').val('');
                    $('#price').val('');
                    $('#variations').val('');
                    $('#stock').val('');
                    loadProducts(); // Atualiza a lista de produtos
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao salvar o produto:", error);
                    alert("Erro ao salvar o produto.");
                }
            });
        });

        // Adicionar produto ao carrinho via AJAX
        $(document).on('submit', '.buy-form', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'ajax/products_actions.php',
                type: 'POST',
                data: $(this).serialize() + '&action=buy_product',
                success: function(response) {
                    alert(response);
                    loadProducts(); // Atualiza a lista de produtos
                    loadCart(); // Atualiza o carrinho
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao adicionar ao carrinho:", error);
                    alert("Erro ao adicionar ao carrinho.");
                }
            });
        });
    });
    </script>

</body>

</html>
