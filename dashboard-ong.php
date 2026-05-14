<?php
session_start();

if (!isset($_SESSION['id_ong'])) {
    header('Location: ong-login.php');
    exit;
}

require 'conexao.php';

$id_ong = $_SESSION['id_ong'];
$nomeOng = $_SESSION['nome_ong'] ?? 'ONG';


// =============================
// 🔥 1. QUERY DAS VAGAS
// =============================
$sql = "
SELECT v.*, COUNT(i.id) as total_inscritos
FROM vagas v
LEFT JOIN inscricoes i ON v.id_vaga = i.id_vaga
WHERE v.id_ong = ?
GROUP BY v.id_vaga
";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    die("Erro na query de vagas: " . $conexao->error);
}

$stmt->bind_param("i", $id_ong);
$stmt->execute();

$result_vagas = $stmt->get_result();


// =============================
// 🔥 2. QUERY DAS INSCRIÇÕES
// =============================
$sql_inscricoes = "
SELECT i.*, v.titulo
FROM inscricoes i
INNER JOIN vagas v ON i.id_vaga = v.id_vaga
WHERE v.id_ong = ?
ORDER BY i.created_at DESC
LIMIT 5
";

$stmt_insc = $conexao->prepare($sql_inscricoes);

if (!$stmt_insc) {
    die("Erro na query de inscrições: " . $conexao->error);
}

$stmt_insc->bind_param("i", $id_ong);
$stmt_insc->execute();

$result_inscricoes = $stmt_insc->get_result();
// =============================
// 🔥 3. TOTAL DE VAGAS ATIVAS
// =============================
$sql_total_vagas = "
SELECT COUNT(*) AS total
FROM vagas
WHERE id_ong = ?
";

$stmt_total_vagas = $conexao->prepare($sql_total_vagas);
$stmt_total_vagas->bind_param("i", $id_ong);
$stmt_total_vagas->execute();

$result_total_vagas = $stmt_total_vagas->get_result();
$total_vagas = $result_total_vagas->fetch_assoc()['total'];


// =============================
// 🔥 4. TOTAL DE INSCRIÇÕES
// =============================
$sql_total_inscricoes = "
SELECT COUNT(*) AS total
FROM inscricoes i
INNER JOIN vagas v ON i.id_vaga = v.id_vaga
WHERE v.id_ong = ?
";

$stmt_total_insc = $conexao->prepare($sql_total_inscricoes);
$stmt_total_insc->bind_param("i", $id_ong);
$stmt_total_insc->execute();

$result_total_insc = $stmt_total_insc->get_result();
$total_inscricoes = $result_total_insc->fetch_assoc()['total'];


// =============================
// 🔥 5. VOLUNTÁRIOS APROVADOS
// =============================
$sql_total_aprovados = "
SELECT COUNT(*) AS total
FROM inscricoes i
INNER JOIN vagas v ON i.id_vaga = v.id_vaga
WHERE v.id_ong = ?
AND i.status = 'aprovado'
";

$stmt_total_aprovados = $conexao->prepare($sql_total_aprovados);
$stmt_total_aprovados->bind_param("i", $id_ong);
$stmt_total_aprovados->execute();

$result_total_aprovados = $stmt_total_aprovados->get_result();
$total_aprovados = $result_total_aprovados->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Voluntariado Conecta</title>
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
    <!-- Dashboard (ativo) -->
    <a href="dashboard-ong.php"
       class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-800 rounded-lg">
        <i data-feather="home" class="mr-3"></i>
        Dashboard
    </a>

    <!-- Minhas Vagas -->
    <a href="minhas-vagas.php"
       class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
        <i data-feather="file-text" class="mr-3"></i>
        Minhas Vagas
    </a>

    <!-- Criar Nova Vaga -->
    <a href="criar-vaga.php"
       class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
        <i data-feather="plus" class="mr-3"></i>
        Criar Nova Vaga
    </a>

    <!-- Inscritos (ainda só visual) -->
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

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Mobile header -->
            <div class="md:hidden bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <i data-feather="heart" class="text-indigo-600"></i>
                        <span class="text-lg font-bold text-indigo-600">
                            <?php echo htmlspecialchars($nomeOng); ?>
                        </span>
                    </div>
                    <button>
                        <i data-feather="menu" class="text-gray-600"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4">
                <div class="max-w-7xl mx-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                        <a href="criar-vaga.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center">
                            <i data-feather="plus" class="mr-2"></i> Nova Vaga
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                                    <i data-feather="file-text"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Vagas Ativas</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        <?php echo $total_vagas; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <i data-feather="users"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Inscrições</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        <?php echo $total_inscricoes; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <i data-feather="check-circle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Voluntários Ativos</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        <?php echo $total_aprovados; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Opportunities -->
                    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Minhas Vagas Recentes</h2>
                        </div>
                       <div class="divide-y divide-gray-200">

<?php if ($result_vagas->num_rows > 0): ?>

<?php while ($vaga = $result_vagas->fetch_assoc()): ?>

<div class="p-6 hover:bg-gray-50">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        
        <!-- LADO ESQUERDO -->
        <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-medium text-indigo-600">
                <?php echo htmlspecialchars($vaga['titulo']); ?>
            </h3>

            <div class="flex items-center mt-2 text-gray-600">
                <i data-feather="map-pin" class="mr-2 w-4 h-4"></i>
                <span>
                    <?php echo htmlspecialchars($vaga['cidade'] . ', ' . $vaga['estado']); ?>
                </span>

                <i data-feather="calendar" class="ml-4 mr-2 w-4 h-4"></i>
                <span>
                    <?php echo htmlspecialchars($vaga['dias_horarios'] ?? 'Não informado'); ?>
                </span>
            </div>
        </div>

        <!-- LADO DIREITO -->
        <div class="flex items-center space-x-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <i data-feather="users" class="mr-1 w-4 h-4"></i>
                <?php echo $vaga['total_inscritos']; ?> inscritos
            </span>

            <a href="detalhe-vaga.php?id_vaga=<?php echo $vaga['id_vaga']; ?>" 
   class="text-indigo-600 hover:text-indigo-900">
   Ver detalhes
</a>
        </div>

    </div>
</div>

<?php endwhile; ?>

<?php else: ?>

<div class="p-6 text-center text-gray-500">
    Nenhuma vaga cadastrada ainda.
</div>

<?php endif; ?>

                    <!-- Recent Volunteers -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Inscrições Recentes</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaga</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">

<?php if ($result_inscricoes->num_rows > 0): ?>

<?php while ($insc = $result_inscricoes->fetch_assoc()): ?>

<tr class="hover:bg-gray-50">

    <!-- NOME -->
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 rounded-full" 
                     src="https://ui-avatars.com/api/?name=<?php echo urlencode($insc['nome']); ?>&background=4f46e5&color=fff">
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                    <?php echo htmlspecialchars($insc['nome']); ?>
                </div>
                <div class="text-sm text-gray-500">
                    <?php echo htmlspecialchars($insc['email']); ?>
                </div>
            </div>
        </div>
    </td>

    <!-- VAGA -->
    <td class="px-6 py-4 whitespace-nowrap">
        <?php echo htmlspecialchars($insc['titulo']); ?>
    </td>

    <!-- DATA -->
    <td class="px-6 py-4 whitespace-nowrap">
        <?php echo date('d/m/Y', strtotime($insc['created_at'] ?? 'now')); ?>
    </td>

    <!-- STATUS -->
    <td class="px-6 py-4 whitespace-nowrap">
        <?php
$status = $insc['status'] ?? 'pendente';

$cor = match($status) {
    'aprovado' => 'bg-green-100 text-green-800',
    'rejeitado' => 'bg-red-100 text-red-800',
    default => 'bg-yellow-100 text-yellow-800'
};
?>

<span class="px-2 inline-flex text-xs font-semibold rounded-full <?php echo $cor; ?>">
    <?php echo ucfirst($status); ?>
</span>
    </td>

    <!-- AÇÃO -->
    <td class="px-6 py-4 whitespace-nowrap text-right">
        <div class="flex gap-2 justify-end">

    <!-- Aprovar -->
    <a href="atualizar-status.php?id=<?php echo $insc['id']; ?>&status=aprovado"
       class="text-green-600 hover:text-green-800 text-sm">
       Aprovar
    </a>

    <!-- Rejeitar -->
    <a href="atualizar-status.php?id=<?php echo $insc['id']; ?>&status=rejeitado"
       class="text-red-600 hover:text-red-800 text-sm">
       Rejeitar
    </a>

</div>
    </td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="5" class="text-center py-4 text-gray-500">
        Nenhuma inscrição recente.
    </td>
</tr>

<?php endif; ?>

</tbody>
                                              
                                            </span>
                                        </td>
                                        
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-200 text-center">
                            <a href="inscritos.php" 
   class="text-indigo-600 hover:text-indigo-900 font-medium">
    Ver todas as inscrições
</a>
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
