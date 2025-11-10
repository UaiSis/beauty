<?php 
require_once("../../../conexao.php");
$tabela = 'cat_servicos';

$id = $_POST['id'];
$nome = $_POST['nome'];
$icone = $_POST['icone'];

// Se não foi selecionado ícone, usar padrão
if(empty($icone)){
	$icone = 'fa-scissors';
}

//validar nome
$query = $pdo->query("SELECT * from $tabela where nome = '$nome'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0 and $id != $res[0]['id']){
	echo 'Nome já Cadastrado, escolha outro!!';
	exit();
}


if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, icone = :icone");
}else{
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, icone = :icone WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":icone", "$icone");
$query->execute();

echo 'Salvo com Sucesso';
 ?>