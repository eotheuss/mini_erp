<?php
session_start();
define('BASE_DIR', __DIR__);
require_once BASE_DIR . '/db.php';
require_once BASE_DIR . '/functions.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $code = $_POST['code'];
    $discount = floatval($_POST['discount']);
    $min_value = floatval($_POST['min_value']);
    $expiration_date = $_POST['expiration_date'];

    // Verifica se estamos atualizando um cupom existente
    if (isset($_POST['coupon_id']) && !empty($_POST['coupon_id'])) {
        $id = intval($_POST['coupon_id']);
        if (updateCoupon($conn, $id, $code, $discount, $min_value, $expiration_date)) {
            $_SESSION['message'] = "Cupom atualizado com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao atualizar o cupom.";
        }
    } else {
        // Adiciona um novo cupom
        if (addCoupon($conn, $code, $discount, $min_value, $expiration_date)) {
            $_SESSION['message'] = "Cupom adicionado com sucesso!";
        } else {
            $_SESSION['message'] = "Erro ao adicionar o cupom.";
        }
    }
}

// Obtém todos os cupons
$coupons = getCoupons($conn);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Cupons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        <h1>Gerenciar Cupons</h1>
        
        <!-- Mensagem de feedback -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form id="coupon-form" class="mb-4" method="POST">
            <input type="hidden" name="coupon_id" id="coupon_id" />
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="code" class="form-control" placeholder="Código do Cupom" required />
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="discount" class="form-control" placeholder="Desconto" required />
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="min_value" class="form-control" placeholder="Valor Mínimo" required />
                </div>
                <div class="col-md-3">
                    <input type="date" name="expiration_date" class="form-control" required />
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Salvar</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Desconto</th>
                    <th>Valor Mínimo</th>
                    <th>Validade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $coupon): ?>
                <tr>
                    <td><?= htmlspecialchars($coupon['code']) ?></td>
                    <td>R$ <?= number_format($coupon['discount'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($coupon['min_value'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($coupon['expiration_date']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $coupon['id'] ?>"
                            data-code="<?= htmlspecialchars($coupon['code']) ?>" data-discount="<?= $coupon['discount'] ?>"
                            data-min-value="<?= $coupon['min_value'] ?>" data-expiration-date="<?= $coupon['expiration_date'] ?>">Editar</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $coupon['id'] ?>">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        // Preencher formulário para edição ao clicar em editar
        $(document).on('click', '.edit-btn', function() {
            $('#coupon_id').val($(this).data('id'));
            $('input[name="code"]').val($(this).data('code'));
            $('input[name="discount"]').val($(this).data('discount'));
            $('input[name="min_value"]').val($(this).data('min-value'));
            $('input[name="expiration_date"]').val($(this).data('expiration-date'));
        });

        // Excluir cupom
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            if (confirm('Tem certeza que deseja excluir este cupom?')) {
                $.ajax({
                    url: 'ajax/coupons_actions.php',
                    type: 'POST',
                    data: { action: 'delete_coupon', id: id },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao excluir o cupom:", error);
                        alert("Erro ao excluir o cupom.");
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
