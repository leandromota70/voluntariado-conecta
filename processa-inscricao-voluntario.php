<?php
require 'conexao.php'; // seu arquivo de conexão

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acesso direto não permitido.");
}

// Função auxiliar para limpar strings
function limparString($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Coleta os dados
$nome = limparString($_POST['nome'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$telefone = limparString($_POST['telefone'] ?? '');
$cidade = limparString($_POST['cidade'] ?? ''); // Não usado na tabela, mas pode ser útil
$experiencia = limparString($_POST['experiencia'] ?? '');
$motivacao = limparString($_POST['motivacao'] ?? '');
$id_vaga = (int)($_POST['id_vaga'] ?? 1);

// LGPD
$lgpd = isset($_POST['lgpd']) ? 1 : 0;

// Disponibilidade (opcional, não salva na tabela atual)
$disponibilidade = isset($_POST['disponibilidade']) && is_array($_POST['disponibilidade']) 
    ? implode(', ', $_POST['disponibilidade']) 
    : '';

// ✅ Validação básica
$erros = [];
if (empty($nome)) $erros[] = "Nome é obrigatório.";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "E-mail inválido.";
if (empty($telefone)) $erros[] = "Telefone é obrigatório.";
if (empty($motivacao)) $erros[] = "O campo 'Por que você quer participar?' é obrigatório.";
if (!$lgpd) $erros[] = "Você deve autorizar o uso dos dados conforme a LGPD.";

if (!empty($erros)) {
    // Redireciona com erros (ajuste o nome do arquivo se for diferente)
    header("Location: inscricao.php?erro=1");
    exit();
}

// ✅ Prepara a consulta SQL para inserir na tabela `inscricoes`
$sql = "INSERT INTO inscricoes 
        (id_vaga, nome_voluntario, email_voluntario, telefone_voluntario, mensagem, data_inscricao) 
        VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($conexao, $sql);

if (!$stmt) {
    die("Erro ao preparar a consulta: " . mysqli_error($conexao));
}

// Combina experiência e motivação em uma única mensagem (opcional)
$mensagem_completa = "";
if (!empty($experiencia)) {
    $mensagem_completa .= "Experiência:\n" . $experiencia . "\n\n";
}
$mensagem_completa .= "Motivação:\n" . $motivacao;

// Escapa a mensagem (ou use prepared statements — já estamos usando!)
$mensagem_completa = mysqli_real_escape_string($conexao, $mensagem_completa);

// Bind dos parâmetros
mysqli_stmt_bind_param(
    $stmt,
    "issss",
    $id_vaga,
    $nome,
    $email,
    $telefone,
    $mensagem_completa
);

if (mysqli_stmt_execute($stmt)) {
    // ✅ Sucesso!
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
    
    // Redireciona para página com mensagem de sucesso
    header("Location: inscricao.php?sucesso=1");
    exit();
} else {
    // ❌ Erro
    error_log("Erro no INSERT: " . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
    
    header("Location: inscricao.php?erro=db");
    exit();
}
?>