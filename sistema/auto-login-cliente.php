<?php 
@session_start();
require_once("conexao.php");

// Se não tiver sessão mas tiver cookie, restaura a sessão
if(!isset($_SESSION['id']) || $_SESSION['id'] == ""){
	if(isset($_COOKIE['id_cliente']) && $_COOKIE['id_cliente'] != ""){
		// Verifica se o cliente existe no banco
		$id_cliente_cookie = $_COOKIE['id_cliente'];
		$query = $pdo->query("SELECT * FROM clientes WHERE id = '$id_cliente_cookie' LIMIT 1");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($res) > 0){
			// Cliente existe, restaura a sessão com dados do banco
			$_SESSION['id'] = $res[0]['id'];
			$_SESSION['nome'] = $res[0]['nome'];
			$_SESSION['telefone'] = $res[0]['telefone'];
			$_SESSION['nivel'] = 'Cliente';
			$_SESSION['aut_token_505052022'] = "fdsfdsafda885574125";
		}
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

