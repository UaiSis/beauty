<?php 
@session_start();
require_once("../sistema/conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];

// Remove formatação do telefone recebido
$telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);

// Valida se o ID realmente existe no banco
$query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id LIMIT 1");
$query->bindValue(":id", $id);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

// Se não encontrou, tenta buscar pelo telefone (caso o ID esteja errado)
if(count($res) == 0){
    // Busca pelo telefone removendo formatação
    $query = $pdo->query("SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', ''), '.', '') = '$telefone_limpo' LIMIT 1");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
}

if(count($res) > 0){
    // Dados válidos - usa os dados do banco (mais confiável)
    $cliente_bd = $res[0];
    
    // Salva na sessão PHP com dados do banco
    $_SESSION['id'] = $cliente_bd['id'];
    $_SESSION['nome'] = $cliente_bd['nome'];
    $_SESSION['telefone'] = $cliente_bd['telefone'];
    $_SESSION['nivel'] = 'Cliente';
    $_SESSION['aut_token_505052022'] = "fdsfdsafda885574125"; // Token de autenticação
    
    // Define cookie para durar 30 dias com dados do banco
    $tempo_expiracao = time() + (30 * 24 * 60 * 60); // 30 dias em segundos
    
    setcookie('id_cliente', $cliente_bd['id'], $tempo_expiracao, '/');
    setcookie('nome_cliente', $cliente_bd['nome'], $tempo_expiracao, '/');
    setcookie('telefone_cliente', $cliente_bd['telefone'], $tempo_expiracao, '/');
    
    echo "Sessão salva com sucesso!";
} else {
    // Dados inválidos - não salva
    echo "Erro: Cliente não encontrado no banco de dados!";
}
?>

