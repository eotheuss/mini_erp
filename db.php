<?php
// db.php

$host = 'localhost:3307';
$user = 'root';
$password = ''; // Insira sua senha aqui
$database = 'mini_erp';

// Conexão com o banco de dados usando mysqli
$conn = new mysqli($host, $user, $password, $database);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>