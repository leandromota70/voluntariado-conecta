<?php
$host   = "localhost";
$usuario = "root";
$senha  = "";
$banco  = "voluntariado_conecta";

$conn = new mysqli($host, $usuario, $senha, $banco);

// ✅ 1. valida conexão primeiro
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ✅ 2. pega o id corretamente
$id_vaga = intval($_GET['id_vaga'] ?? 0);

// 🔥 DEBUG (pode remover depois)
echo "ID da vaga: " . $id_vaga . "<br>";

// ✅ 3. query correta
$sql = "
SELECT i.*, v.titulo
FROM inscricoes i
INNER JOIN vagas v ON i.id_vaga = v.id_vaga
WHERE i.id_vaga = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro na query: " . $conn->error);
}

$stmt->bind_param("i", $id_vaga);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscritos | Voluntariado Conecta</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="flex h-screen">

  <!-- Sidebar -->
  <div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-indigo-700">
      <div class="flex items-center h-16 px-4 bg-indigo-800">
        <div class="flex items-center space-x-2">
          <i data-feather="heart" class="text-white"></i>
          <span class="text-xl font-bold text-white">ONG Educar</span>
        </div>
      </div>
      <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
        <div class="flex-1 px-4 space-y-2">
          <nav class="flex-1 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
              <i data-feather="home" class="mr-3"></i> Dashboard
            </a>
            <a href="minhas-vagas.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
              <i data-feather="file-text" class="mr-3"></i> Minhas Vagas
            </a>
            <a href="criar-vaga.php" class="flex items-center px-4 py-2 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg">
              <i data-feather="plus" class="mr-3"></i> Criar Nova Vaga
            </a>
            <a href="inscritos.php" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-800 rounded-lg">
              <i data-feather="users" class="mr-3"></i> Inscritos
            </a>
          </nav>
        </div>
        <div class="px-4 mt-auto">
          <div class="flex items-center p-2 rounded-lg bg-indigo-800">
            <div class="flex-shrink-0">
              <img class="w-10 h-10 rounded-full" src="http://static.photos/people/200x200/1" alt="User profile">
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-white">Maria Silva</p>
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
      <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">Lista de Inscritos</h1>
        <table class="min-w-full border-collapse">
          <thead>
            <tr class="bg-indigo-100 text-indigo-700">
              <th class="px-4 py-2 text-left">Nome</th>
              <th class="px-4 py-2 text-left">Vaga</th>
              <th class="px-4 py-2 text-left">Data</th>
              <th class="px-4 py-2 text-left">Status</th>
            </tr>
          </thead>
       <tbody>
<?php if ($result->num_rows > 0): ?>

<?php while ($row = $result->fetch_assoc()): ?>
<tr class="border-b hover:bg-gray-50">
    <td class="px-4 py-2"><?php echo $row['nome']; ?></td>
    <td class="px-4 py-2"><?php echo $row['titulo']; ?></td>
    <td class="px-4 py-2">
        <?php echo date('d/m/Y', strtotime($row['data_inscricao'] ?? 'now')); ?>
    </td>
    <td class="px-4 py-2">
        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">
            <?php
$status = $row['status'] ?? 'pendente';

$cor = match($status) {
    'aprovado' => 'bg-green-100 text-green-800',
    'rejeitado' => 'bg-red-100 text-red-800',
    default => 'bg-yellow-100 text-yellow-800'
};
?>

<span class="px-2 py-1 rounded <?php echo $cor; ?>">
    <?php echo ucfirst($status); ?>
</span>
        </span>
    </td>
</tr>
<?php endwhile; ?>

<?php else: ?>

<tr>
    <td colspan="4" class="text-center py-4">
        Nenhum inscrito ainda.
    </td>
</tr>

<?php endif; ?>
</tbody>
        </table>
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