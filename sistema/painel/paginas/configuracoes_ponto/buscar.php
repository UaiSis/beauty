<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT * FROM configuracoes_ponto WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(@count($res) > 0){
	echo json_encode($res[0]);
} else {
	echo json_encode(array('error' => 'Não encontrado'));
}
?>

