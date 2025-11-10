<?php 
@session_start();
require_once("../conexao.php");

// Se não tiver sessão ativa, tenta recuperar dos cookies (login por 30 dias)
if(@$_SESSION['id'] == "" && isset($_COOKIE['id_cliente']) && $_COOKIE['id_cliente'] != ""){
	// Busca dados do cliente no banco para garantir que está correto
	$id_cliente_cookie = $_COOKIE['id_cliente'];
	$query = $pdo->query("SELECT * FROM clientes WHERE id = '$id_cliente_cookie' LIMIT 1");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($res) > 0){
		// Cliente existe, restaura a sessão com dados do banco (garante dados corretos)
		$_SESSION['id'] = $res[0]['id'];
		$_SESSION['nome'] = $res[0]['nome'];
		$_SESSION['telefone'] = $res[0]['telefone'];
		$_SESSION['nivel'] = 'Cliente';
		$_SESSION['aut_token_505052022'] = "fdsfdsafda885574125";
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