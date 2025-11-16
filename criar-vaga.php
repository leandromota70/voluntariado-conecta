<?php
session_start();
require 'conexao.php';

// Se não estiver logado, volta pro login
if (!isset($_SESSION['id_ong'])) {
    header('Location: ong-login.php');
    exit;
}

$mensagem_sucesso = "";
$mensagem_erro = "";

// Variáveis para manter valores no formulário em caso de erro
$titulo = $area = $tipo = $cidade = $estado = $descricao = $carga_horaria = $dias_horarios = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo        = trim($_POST['titulo'] ?? '');
    $area          = trim($_POST['area'] ?? '');
    $tipo          = trim($_POST['tipo'] ?? '');
    $cidade        = trim($_POST['cidade'] ?? '');
    $estado        = trim($_POST['estado'] ?? '');
    $descricao     = trim($_POST['descricao'] ?? '');
    $carga_horaria = trim($_POST['carga_horaria'] ?? '');
    $dias_horarios = trim($_POST['dias_horarios'] ?? '');

    if ($titulo === '' || $area === '' || $tipo === '' || $cidade === '' || $estado === '' || $descricao === '') {
        $mensagem_erro = "Preencha todos os campos obrigatórios (*).";
    } else {
        $id_ong = $_SESSION['id_ong'];

        $stmt = $conexao->prepare(
            "INSERT INTO vagas 
            (id_ong, titulo, area, tipo_atividade, cidade, estado, carga_horaria_semana, dias_horarios, descricao, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ativa')"
        );

        if (!$stmt) {
            $mensagem_erro = "Erro na preparação da query: " . $conexao->error;
        } else {
            // 1 inteiro (id_ong) + 8 strings
            $stmt->bind_param(
                "issssssss",
                $id_ong,
                $titulo,
                $area,
                $tipo,
                $cidade,
                $estado,
                $carga_horaria,
                $dias_horarios,
                $descricao
            );

            if ($stmt->execute()) {
                $mensagem_sucesso = "Vaga criada com sucesso!";

                // Limpa os campos do formulário
                $titulo = $area = $tipo = $cidade = $estado = $descricao = $carga_horaria = $dias_horarios = "";
            } else {
                $mensagem_erro = "Erro ao salvar vaga: " . $conexao->error;
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Vaga | Voluntariado Conecta</title>
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
                            <?php echo htmlspecialchars($_SESSION['nome_ong'] ?? 'ONG'); ?>
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
                            <a href="minhas-vagas.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
    <i data-feather="file-text" class="mr-3"></i>
    Minhas Vagas
</a>
                            <a href="criar-vaga.php" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-800 rounded-lg">
                                <i data-feather="plus" class="mr-3"></i>
                                Criar Nova Vaga
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
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
                                    <?php echo htmlspecialchars($_SESSION['nome_ong'] ?? 'ONG'); ?>
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
            <main class="flex-1 overflow-y-auto p-4">
                <div class="max-w-4xl mx-auto">
                    <div class="mb-6">
                        <a href="dashboard-ong.php" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                            <i data-feather="arrow-left" class="mr-2"></i> Voltar para dashboard
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h1 class="text-xl font-bold text-gray-800">Criar Nova Vaga de Voluntariado</h1>
                        </div>
                        
                        <div class="p-6">
                            <?php if ($mensagem_sucesso): ?>
                                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">
                                    <?php echo htmlspecialchars($mensagem_sucesso); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($mensagem_erro): ?>
                                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">
                                    <?php echo htmlspecialchars($mensagem_erro); ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título da Vaga*</label>
                                        <input type="text" id="titulo" name="titulo" required
                                               value="<?php echo htmlspecialchars($titulo); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               placeholder="Ex: Apoio Escolar para Crianças">
                                    </div>
                                    
                                    <div>
                                        <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Área de Atuação*</label>
                                        <select id="area" name="area" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Selecione</option>
                                            <option <?php if($area === 'Educação') echo 'selected'; ?>>Educação</option>
                                            <option <?php if($area === 'Meio Ambiente') echo 'selected'; ?>>Meio Ambiente</option>
                                            <option <?php if($area === 'Saúde') echo 'selected'; ?>>Saúde</option>
                                            <option <?php if($area === 'Assistência Social') echo 'selected'; ?>>Assistência Social</option>
                                            <option <?php if($area === 'Cultura') echo 'selected'; ?>>Cultura</option>
                                            <option <?php if($area === 'Esporte') echo 'selected'; ?>>Esporte</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Atividade*</label>
                                        <select id="tipo" name="tipo" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Selecione</option>
                                            <option <?php if($tipo === 'Presencial') echo 'selected'; ?>>Presencial</option>
                                            <option <?php if($tipo === 'Remoto') echo 'selected'; ?>>Remoto</option>
                                            <option <?php if($tipo === 'Híbrido') echo 'selected'; ?>>Híbrido</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-1">Cidade*</label>
                                        <input type="text" id="cidade" name="cidade" required
                                               value="<?php echo htmlspecialchars($cidade); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado*</label>
                                        <select id="estado" name="estado" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Selecione</option>
                                            <option <?php if($estado === 'SP') echo 'selected'; ?>>SP</option>
                                            <option <?php if($estado === 'RJ') echo 'selected'; ?>>RJ</option>
                                            <option <?php if($estado === 'MG') echo 'selected'; ?>>MG</option>
                                            <option <?php if($estado === 'RS') echo 'selected'; ?>>RS</option>
                                            <option <?php if($estado === 'PR') echo 'selected'; ?>>PR</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="carga_horaria" class="block text-sm font-medium text-gray-700 mb-1">Carga horária semanal (opcional)</label>
                                        <input type="text" id="carga_horaria" name="carga_horaria"
                                               value="<?php echo htmlspecialchars($carga_horaria); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               placeholder="Ex: 3 horas por semana">
                                    </div>

                                    <div>
                                        <label for="dias_horarios" class="block text-sm font-medium text-gray-700 mb-1">Dias e horários (opcional)</label>
                                        <input type="text" id="dias_horarios" name="dias_horarios"
                                               value="<?php echo htmlspecialchars($dias_horarios); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               placeholder="Ex: Segundas e Quartas, 14h-17h">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição da Vaga*</label>
                                        <textarea id="descricao" name="descricao" rows="4" required
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?php echo htmlspecialchars($descricao); ?></textarea>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <a href="dashboard-ong.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Salvar Vaga</button>
                                </div>
                            </form>
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
