<?php 
@session_start();
require_once("conexao.php");


$telefone = filter_var($_POST['telefone'], @FILTER_SANITIZE_STRING);
$senha = filter_var($_POST['senha'], @FILTER_SANITIZE_STRING);

$query = $pdo->prepare("SELECT * from clientes where telefone = :telefone");
$query->bindValue(":telefone", "$telefone");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$total_reg = @count($res);
if($total_reg > 0){
	
	if(!password_verify($senha, $res[0]['senha_crip'])){
		echo '<script>window.alert("Dados Incorretos!!")</script>'; 
		echo '<script>window.location="acesso"</script>';  
		exit();
	}


		$_SESSION['id'] = $res[0]['id'];		
		$_SESSION['nome'] = $res[0]['nome'];
		$_SESSION['telefone'] = $res[0]['telefone'];
		$_SESSION['nivel'] = 'Cliente';
		$_SESSION['aut_token_505052022'] = 'fdsfdsafda885574125';
		
		// Salva cookies por 30 dias para manter login
		$tempo_expiracao = time() + (30 * 24 * 60 * 60); // 30 dias
		setcookie('id_cliente', $res[0]['id'], $tempo_expiracao, '/');
		setcookie('nome_cliente', $res[0]['nome'], $tempo_expiracao, '/');
		setcookie('telefone_cliente', $res[0]['telefone'], $tempo_expiracao, '/');
	
		//ir para o painel
		echo "<script>window.location='painel_cliente'</script>";
	
	
}else{
	echo "<script>window.alert('Usuário ou Senha Incorretos!')</script>";
	echo "<script>window.location='acesso'</script>";
}

 ?>