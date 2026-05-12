<?php
require 'conexao.php';

$id = intval($_GET['id'] ?? 0);
$status = $_GET['status'] ?? '';

$permitidos = ['aprovado', 'rejeitado'];

if ($id > 0 && in_array($status, $permitidos)) {

    $stmt = $conexao->prepare("UPDATE inscricoes SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

// volta para a página anterior
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;