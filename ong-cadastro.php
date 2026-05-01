<?php
require 'conexao.php'; // conexão com o banco

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($nome === '' || $email === '' || $senha === '') {
        $mensagem = "Preencha todos os campos.";
    } else {
        // Inserir no banco
        $stmt = $conexao->prepare("INSERT INTO ongs (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            $mensagem = "ONG cadastrada com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar ONG.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar ONG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-indigo-600">Cadastrar ONG</h1>

        <?php if ($mensagem !== ""): ?>
            <div class="mb-4 text-center text-sm text-red-600">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium">Nome da ONG</label>
                <input type="text" id="nome" name="nome" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">E-mail</label>
                <input type="email" id="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="senha" class="block text-sm font-medium">Senha</label>
                <input type="password" id="senha" name="senha" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Cadastrar
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="ong-login.php" class="text-indigo-600 hover:underline">Já tenho conta</a>
        </div>
    </div>
</body>
</html>