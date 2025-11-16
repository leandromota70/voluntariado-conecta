<?php
session_start();
require 'conexao.php';

// Protege a página
if (!isset($_SESSION['id_ong'])) {
    header('Location: ong-login.php');
    exit;
}

$id_ong  = $_SESSION['id_ong'];
$nomeOng = $_SESSION['nome_ong'] ?? 'ONG';

// Busca vagas dessa ONG
$stmt = $conexao->prepare(
    "SELECT id_vaga, titulo, area, tipo_atividade, cidade, estado, status, criado_em 
     FROM vagas 
     WHERE id_ong = ?
     ORDER BY criado_em DESC"
);
$stmt->bind_param("i", $id_ong);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas | Voluntariado Conecta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64 bg-indigo-700">
            <div class="flex items-center h-16 px-4 bg-indigo-800">
                <div class="flex items-center space-x-2">
                    <i data-feather="heart" class="text-white"></i>
                    <span class="text-xl font-bold text-white">
                        <?php echo htmlspecialchars($nomeOng); ?>
                    </span>
                </div>
            </div>
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                <div class="flex-1 px-4 space-y-2">
                    <nav class="flex-1 space-y-2">
                        <a href="dashboard-ong.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
                            <i data-feather="home" class="mr-3"></i>
                            Dashboard
                        </a>
                        <a href="minhas-vagas.php" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-800 rounded-lg">
                            <i data-feather="file-text" class="mr-3"></i>
                            Minhas Vagas
                        </a>
                        <a href="criar-vaga.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
                            <i data-feather="plus" class="mr-3"></i>
                            Criar Nova Vaga
                        </a>
                        <a href="inscritos.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
    <i data-feather="users" class="mr-3"></i>
    Inscritos
</a>
                    </nav>
                </div>
                <div class="px-4 mt-auto">
                    <div class="flex items-center p-2 rounded-lg bg-indigo-800">
                        <div class="flex-shrink-0">
                            <img class="w-10 h-10 rounded-full" src="http://static.photos/people/200x200/1" alt="User profile">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">
                                <?php echo htmlspecialchars($nomeOng); ?>
                            </p>
                            <a href="logout.php" class="text-xs font-medium text-indigo-200 hover:text-white">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="flex flex-col flex-1 overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4">
            <div class="max-w-6xl mx-auto">
                <div class="mb-6 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Minhas Vagas</h1>
                    <a href="criar-vaga.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center">
                        <i data-feather="plus" class="mr-2"></i> Nova Vaga
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Área</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Local</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Criada em</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($resultado->num_rows === 0): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-sm">
                                            Nenhuma vaga cadastrada ainda.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php while ($vaga = $resultado->fetch_assoc()): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <?php echo htmlspecialchars($vaga['titulo']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($vaga['area']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($vaga['tipo_atividade']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                <?php echo htmlspecialchars($vaga['cidade'] . ' - ' . $vaga['estado']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if ($vaga['status'] === 'ativa'): ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Ativa
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        <?php echo htmlspecialchars($vaga['status']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <?php echo htmlspecialchars($vaga['criado_em']); ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    AOS.init();
    feather.replace();
</script>
</body>
</html>
