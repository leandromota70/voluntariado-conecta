<?php
session_start();
require 'conexao.php';

$erro = "";

// Quando o formulário for enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        $erro = "Preencha e-mail e senha.";
    } else {
        // Buscar ONG pelo e-mail
        $stmt = $conexao->prepare("SELECT id_ong, nome, email, senha FROM ongs WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows === 1) {
            $ong = $resultado->fetch_assoc();

            // Senha padrão cadastrada no banco é 123456
            if ($senha === $ong['senha']) {
                $_SESSION['id_ong']   = $ong['id_ong'];
                $_SESSION['nome_ong'] = $ong['nome'];

                // 🔴 AQUI QUE MUDA:
                header("Location: dashboard-ong.php");
                exit;
            } else {
                $erro = "E-mail ou senha inválidos.";
            }
        } else {
            $erro = "E-mail ou senha inválidos.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área da ONG | Voluntariado Conecta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i data-feather="heart" class="text-indigo-600"></i>
                <span class="text-xl font-bold text-indigo-600">Voluntariado Conecta</span>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="index.html" class="text-gray-600 hover:text-indigo-600">Home</a>
                <a href="catalogo.html" class="text-gray-600 hover:text-indigo-600">Quero ser Voluntário</a>
                <a href="ong-login.php" class="text-indigo-600 font-medium">Sou ONG</a>
                <a href="ong-login.php" class="text-gray-600 hover:text-indigo-600">Login</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-indigo-600 py-4 px-6">
                <h1 class="text-xl font-bold text-white">Área da ONG</h1>
            </div>
            
            <div class="p-6">

                <?php if ($erro !== ""): ?>
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">
                        <?php echo $erro; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                               value="<?php echo $email ?? ''; ?>">
                    </div>
                    <div class="mb-6">
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <input type="password" id="senha" name="senha" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700">
                        Entrar
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="ong-cadastro.html" class="text-indigo-600 hover:underline">Cadastrar nova ONG</a>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-8 text-center">
        <p>&copy; 2023 Voluntariado Conecta. Todos os direitos reservados.</p>
    </footer>

    <script>
        AOS.init();
        feather.replace();
    </script>
</body>
</html>
