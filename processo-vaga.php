<?php
require 'conexao.php'; // conexão com MySQL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $area = $_POST['area'];
    $tipo_atividade = $_POST['tipo_atividade'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $carga_horaria_semana = $_POST['carga_horaria_semana'];
    $dias_horarios = $_POST['dias_horarios'];
    $requisitos = $_POST['requisitos'];
    $beneficios = $_POST['beneficios'];
    $status = $_POST['status'];

    $stmt = $conexao->prepare("INSERT INTO vagas 
        (titulo, area, tipo_atividade, cidade, estado, carga_horaria_semana, dias_horarios, requisitos, beneficios, status, criado_em, atualizado_em) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

    $stmt->bind_param("sssssssss s", 
        $titulo, $area, $tipo_atividade, $cidade, $estado, 
        $carga_horaria_semana, $dias_horarios, $requisitos, $beneficios, $status);

    if ($stmt->execute()) {
        echo "✅ Vaga criada com sucesso!";
    } else {
        echo "❌ Erro ao salvar vaga: " . $conexao->error;
    }

    $stmt->close();
}
?>