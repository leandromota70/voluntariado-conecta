<?php
require 'conexao.php';

// Pega o ID da vaga pela URL (ex: detalhe-vaga.php?id=1)
$id_vaga = $_GET['id'] ?? 0;

$stmt = $conexao->prepare("SELECT * FROM vagas WHERE id_vaga = ?");
$stmt->bind_param("i", $id_vaga);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $vaga = $resultado->fetch_assoc();
} else {
    die("Vaga não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhe da Vaga | Voluntariado Conecta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            <?php echo htmlspecialchars($vaga['titulo']); ?>
        </h1>
        <p class="text-gray-600 mb-4">
            <?php echo htmlspecialchars($vaga['descricao']); ?>
        </p>

        <h3 class="text-lg font-semibold mb-3">Atividades</h3>
        <p><?php echo nl2br(htmlspecialchars($vaga['atividades'])); ?></p>

        <h3 class="text-lg font-semibold mb-3">Requisitos</h3>
        <p><?php echo nl2br(htmlspecialchars($vaga['requisitos'])); ?></p>

        <h3 class="text-lg font-semibold mb-3">Benefícios</h3>
        <p><?php echo nl2br(htmlspecialchars($vaga['beneficios'])); ?></p>

        <h3 class="text-lg font-semibold mb-3">Sobre a ONG</h3>
        <p><strong><?php echo htmlspecialchars($vaga['nome_ong']); ?></strong></p>
        <p>Email: <?php echo htmlspecialchars($vaga['email_ong']); ?></p>
        <p>Telefone: <?php echo htmlspecialchars($vaga['telefone_ong']); ?></p>
        <p>Site: <?php echo htmlspecialchars($vaga['site_ong']); ?></p>
    </main>
</body>
</html>