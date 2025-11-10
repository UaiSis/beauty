<?php 
require_once("../sistema/conexao.php");

$servico = $_POST['serv'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	$nome = $res[0]['nome'];
	$valor = $res[0]['valor'];
	$valorF = number_format($valor, 2, ',', '.');
	
	echo $nome . ' - R$ ' . $valorF;
} else {
	echo '';
}

?>

