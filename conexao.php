<?php
// conexao.php
// Arquivo central de conexão com o banco de dados

$host   = "localhost";
$usuario = "root";        // padrão do XAMPP
$senha  = "";             // geralmente vem vazio
$banco  = "voluntariado_conecta";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");
