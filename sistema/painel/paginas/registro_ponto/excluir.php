<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM pontos WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	echo 'Registro não encontrado!';
	exit();
}

$pdo->query("DELETE FROM pontos WHERE id = '$id'");
$pdo->query("DELETE FROM ajustes_ponto WHERE ponto_id = '$id'");

echo 'Excluído com Sucesso';
?>

