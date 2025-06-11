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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        h1, h2 {
            color: #0056b3;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #0056b3;
            border: none;
        }

        .btn-primary:hover {
            background-color: #004494;
        }

        .btn-warning {
            background-color: #f39c12;
            border: none;
        }

        .btn-warning:hover {
            background-color: #d87a09;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #0056b3;
            color: #fff;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        #loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #0056b3;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .form-control {
            border-radius: 8px;
        }

        .table-bordered th, .table-bordered td {
            vertical-align: middle;
            text-align: center;
        }

        .container {
            margin-top: 40px;
        }

        #cart-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .buy-form {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .buy-form input {
            border-radius: 8px;
            margin-right: 8px;
        }

        .buy-button {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mini ERP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="coupons.php">Cupons</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Produtos</h1>

        <!-- Formulário para adicionar/editar produto -->
        <form id="product-form" class="mb-4">
            <input type="hidden" name="product_id" value="" id="product_id" />
            <div id="loading" style="display: none;">
                <div class="loader"></div>
            </div>
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
