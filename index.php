<?php
// (opcional depois você pode puxar vagas do banco aqui)
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntariado Conecta</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #10b981 100%);
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

<!-- HEADER -->
<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <i data-feather="heart" class="text-indigo-600"></i>
            <span class="text-xl font-bold text-indigo-600">Voluntariado Conecta</span>
        </div>

        <nav class="hidden md:flex space-x-8">
            <a href="index.php" class="text-indigo-600 font-medium">Home</a>
            <a href="catalogo.php" class="text-gray-600 hover:text-indigo-600">Quero ser Voluntário</a>
            <a href="ong-login.php" class="text-gray-600 hover:text-indigo-600">Sou ONG</a>
            
        </nav>
    </div>
</header>

<!-- HERO -->
<section class="hero-gradient text-white py-20 text-center">
    <h1 class="text-4xl font-bold mb-6">Conectando voluntários e ONGs</h1>
    <p class="text-xl mb-10">Encontre oportunidades de voluntariado ou cadastre sua ONG</p>

    <div class="flex justify-center gap-4">
        <a href="catalogo.php" class="bg-indigo-600 px-8 py-3 rounded-lg">Quero ser Voluntário</a>
        <a href="ong-login.php" class="bg-green-600 px-8 py-3 rounded-lg">Sou ONG</a>
    </div>
</section>

<!-- CONTEÚDO -->
<main class="flex-grow container mx-auto px-4 py-10">
    <h2 class="text-2xl font-bold mb-6 text-center">Oportunidades Recentes</h2>

    <?php
    require 'conexao.php';

    $sql = "SELECT * FROM vagas ORDER BY id_vaga DESC LIMIT 3";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0):
    ?>

    <div class="grid md:grid-cols-3 gap-6">

        <?php while ($vaga = $result->fetch_assoc()): ?>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold text-indigo-600">
                <?php echo htmlspecialchars($vaga['titulo']); ?>
            </h3>

            <p class="text-gray-600 mt-2">
                <?php echo htmlspecialchars($vaga['cidade']); ?>
            </p>

            <a href="detalhe-vaga.php?id_vaga=<?php echo $vaga['id_vaga']; ?>"
               class="mt-4 inline-block text-indigo-600">
                Ver detalhes
            </a>
        </div>

        <?php endwhile; ?>

    </div>

    <?php else: ?>
        <p class="text-center text-gray-500">Nenhuma vaga cadastrada.</p>
    <?php endif; ?>
</main>

<!-- FOOTER -->
<footer class="bg-gray-800 text-white py-6 text-center">
    <p>&copy; 2026 Voluntariado Conecta</p>
</footer>

<script>
    feather.replace();
    AOS.init();
</script>

</body>
</html>