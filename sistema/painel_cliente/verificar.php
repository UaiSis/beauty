<?php 
@session_start();
require_once("../conexao.php");

// Se não tiver sessão ativa, tenta recuperar dos cookies (login por 30 dias)
if(@$_SESSION['id'] == "" && isset($_COOKIE['id_cliente'])){
	$id_cookie = $_COOKIE['id_cliente'];
	$telefone_cookie = $_COOKIE['telefone_cliente'];
	
	// Verifica se o ID e telefone do cookie realmente existem no banco e correspondem
	$query = $pdo->prepare("SELECT * FROM clientes WHERE id = :id AND telefone = :telefone LIMIT 1");
	$query->bindValue(":id", $id_cookie);
	$query->bindValue(":telefone", $telefone_cookie);
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($res) > 0){
		// Dados válidos, restaura a sessão com dados do banco
		$_SESSION['id'] = $res[0]['id'];
		$_SESSION['nome'] = $res[0]['nome'];
		$_SESSION['telefone'] = $res[0]['telefone'];
		$_SESSION['nivel'] = 'Cliente';
		$_SESSION['aut_token_505052022'] = "fdsfdsafda885574125";
	} else {
		// Dados inválidos, limpa os cookies
		setcookie('id_cliente', '', time() - 3600, '/');
		setcookie('nome_cliente', '', time() - 3600, '/');
		setcookie('telefone_cliente', '', time() - 3600, '/');
	}
}

// Verifica se tem sessão
if(@$_SESSION['id'] == ""){
	echo "<script>window.location='../acesso.php'</script>";
	exit();
}

// Verifica token de autenticação
if(@$_SESSION['aut_token_505052022'] != "fdsfdsafda885574125"){
	echo "<script>window.location='../acesso.php'</script>";
	exit();
}
 ?>