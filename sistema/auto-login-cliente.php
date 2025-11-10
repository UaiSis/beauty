<?php 
@session_start();
require_once("conexao.php");

// Se não tiver sessão mas tiver cookie, valida e restaura a sessão
if(!isset($_SESSION['id']) && isset($_COOKIE['id_cliente']) && $_COOKIE['id_cliente'] != ""){
	$id_cookie = $_COOKIE['id_cliente'];
	$telefone_cookie = $_COOKIE['telefone_cliente'];
	
	// Verifica se o ID e telefone do cookie realmente existem no banco e correspondem
	$query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id AND telefone = :telefone LIMIT 1");
	$query->bindValue(":id", $id_cookie);
	$query->bindValue(":telefone", $telefone_cookie);
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($res) > 0){
		// Dados válidos, restaura a sessão
		$_SESSION['id'] = $res[0]['id'];
		$_SESSION['nome'] = $res[0]['nome'];
		$_SESSION['telefone'] = $res[0]['telefone'];
		$_SESSION['nivel'] = 'Cliente';
		$_SESSION['aut_token_505052022'] = "fdsfdsafda885574125";
		
		// Atualiza os cookies com os dados corretos do banco
		$tempo_expiracao = time() + (30 * 24 * 60 * 60);
		setcookie('id_cliente', $res[0]['id'], $tempo_expiracao, '/');
		setcookie('nome_cliente', $res[0]['nome'], $tempo_expiracao, '/');
		setcookie('telefone_cliente', $res[0]['telefone'], $tempo_expiracao, '/');
	} else {
		// Dados inválidos, limpa os cookies
		setcookie('id_cliente', '', time() - 3600, '/');
		setcookie('nome_cliente', '', time() - 3600, '/');
		setcookie('telefone_cliente', '', time() - 3600, '/');
	}
}

// Redireciona para o painel
if(isset($_SESSION['id']) && $_SESSION['id'] != ""){
	header("Location: painel_cliente");
	exit();
} else {
	// Se não conseguiu fazer auto-login, vai para a tela de login
	header("Location: acesso.php");
	exit();
}
?>

