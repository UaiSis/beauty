<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM configuracoes_ponto WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	echo 'Registro não encontrado!';
	exit();
}

$pdo->query("DELETE FROM configuracoes_ponto WHERE id = '$id'");

echo 'Excluído com Sucesso';
?>

