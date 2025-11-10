<?php 
@session_start();
require_once("../sistema/conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];

// Valida se o ID existe no banco antes de salvar
$query = $pdo->query("SELECT * FROM clientes WHERE id = '$id' LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(count($res) > 0){
	// Cliente existe, usa os dados do banco para garantir consistência
	$id_cliente = $res[0]['id'];
	$nome_cliente = $res[0]['nome'];
	$telefone_cliente = $res[0]['telefone'];
	
	// Salva na sessão PHP com dados do banco
	$_SESSION['id'] = $id_cliente;
	$_SESSION['nome'] = $nome_cliente;
	$_SESSION['telefone'] = $telefone_cliente;
	$_SESSION['nivel'] = 'Cliente';
	$_SESSION['aut_token_505052022'] = "fdsfdsafda885574125"; // Token de autenticação
	
	// Define cookie para durar 30 dias
	$tempo_expiracao = time() + (30 * 24 * 60 * 60); // 30 dias em segundos
	
	setcookie('id_cliente', $id_cliente, $tempo_expiracao, '/');
	setcookie('nome_cliente', $nome_cliente, $tempo_expiracao, '/');
	setcookie('telefone_cliente', $telefone_cliente, $tempo_expiracao, '/');
	
	echo "Sessão salva com sucesso!";
} else {
	echo "Erro: Cliente não encontrado no banco!";
}
?>

