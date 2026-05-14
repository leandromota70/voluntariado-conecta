<?php
include("conexao.php");

// PAGINAÇÃO
$pagina = intval($_GET['pagina'] ?? 1);
$limite = 5;
$offset = ($pagina - 1) * $limite;

// BUSCA VAGAS COM LIMITE
$sql = "SELECT * FROM vagas LIMIT $limite OFFSET $offset";
$result = mysqli_query($conexao, $sql);

// TOTAL DE REGISTROS
$total_result = mysqli_query($conexao, "SELECT COUNT(*) as total FROM vagas");
$total = mysqli_fetch_assoc($total_result)['total'];

$total_paginas = ceil($total / $limite);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Vagas | Voluntariado Conecta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="flex flex-col min-h-screen font-sans antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i data-feather="heart" class="text-indigo-600"></i>
                <span class="text-xl font-bold text-indigo-600">Voluntariado Conecta</span>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="index.php" class="text-gray-600 hover:text-indigo-600">Home</a>
                <a href="catalogo.php" class="text-indigo-600 font-medium">Quero ser Voluntário</a>
                <a href="ong-login.php" class="text-gray-600 hover:text-indigo-600">Sou ONG</a>
                
            </nav>
            <button class="md:hidden">
                <i data-feather="menu" class="text-gray-600"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Oportunidades de Voluntariado</h1>
            <a href="index.php" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <i data-feather="arrow-left" class="mr-2"></i> Voltar para home
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" placeholder="Buscar oportunidades..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <i data-feather="search" class="absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                    <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Área de atuação</option>
                        <option>Educação</option>
                        <option>Meio Ambiente</option>
                        <option>Saúde</option>
                        <option>Assistência Social</option>
                    </select>
                    <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Cidade</option>
                        <option>São Paulo</option>
                        <option>Rio de Janeiro</option>
                        <option>Belo Horizonte</option>
                        <option>Porto Alegre</option>
                    </select>
                    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Filtrar
                    </button>
                </div>
            </div>
        </div>

       <div class="grid gap-6">
<?php while($vaga = mysqli_fetch_assoc($result)) { ?> 

<div class="bg-white rounded-lg shadow-md overflow-hidden">
 <div class="p-6"> 
    <div class="flex flex-col md:flex-row justify-between">
         <div class="mb-4 md:mb-0">
             <h2 class="text-xl font-bold text-gray-800"> 
                <?php echo $vaga['titulo']; ?> 
            </h2>
             <div class="flex items-center mt-2 text-gray-600">
                 <span><?php echo $vaga['cidade']; ?> - <?php echo $vaga['estado']; ?></span> 
                </div> </div> 
<a href="detalhe-vaga.php?id_vaga=<?php echo $vaga['id_vaga']; ?>" 
   class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center justify-center h-10">
    Ver Detalhes
</a>
 </div>
 <p class="mt-4 text-gray-600">
 <h1><?php echo $vaga['titulo']; ?></h1>

<p><?php echo $vaga['descricao']; ?></p>

<p><?php echo $vaga['cidade']; ?> - <?php echo $vaga['estado']; ?></p>
            </p>
        </div>
    </div>

<?php } ?>
</div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                    <i data-feather="chevron-left" class="w-5 h-5"></i>
                </a>
                <?php if ($total_paginas > 1): ?>

<div class="mt-8 flex justify-center">
    <nav class="inline-flex rounded-md shadow">

        <!-- ANTERIOR -->
        <a href="?pagina=<?php echo max(1, $pagina - 1); ?>" 
           class="px-3 py-2 border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
            ←
        </a>

        <!-- NÚMEROS -->
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" 
               class="px-4 py-2 border 
               <?php echo $i == $pagina ? 'bg-indigo-600 text-white' : 'bg-white text-gray-500'; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- PRÓXIMO -->
        <a href="?pagina=<?php echo min($total_paginas, $pagina + 1); ?>" 
           class="px-3 py-2 border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
            →
        </a>

    </nav>
</div>

<?php endif; ?>
                <a href="#" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                    <i data-feather="chevron-right" class="w-5 h-5"></i>
                </a>
            </nav>
        </div>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <i data-feather="heart" class="text-indigo-400"></i>
                        <span class="text-xl font-bold">Voluntariado Conecta</span>
                    </div>
                    <p class="text-gray-400 mt-2">Conectando pessoas a causas sociais</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="linkedin"></i></a>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 Voluntariado Conecta. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init();
        feather.replace();
    </script>
</body>
</html>