<?php
require 'conexao.php'; // conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if ($nome && $email && $senha) {
        $stmt = $conexao->prepare("INSERT INTO ongs (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            echo "ONG cadastrada com sucesso! <a href='ong-login.php'>Ir para login</a>";
        } else {
            echo "Erro ao cadastrar ONG.";
        }
        $stmt->close();
    } else {
        echo "Preencha todos os campos.";
    }
}
?>