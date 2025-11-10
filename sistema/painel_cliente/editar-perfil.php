<?php 
require_once('../conexao.php');

$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$conf_senha = $_POST['conf_senha'];
$endereco = $_POST['endereco'];
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

if($senha != $conf_senha){
	echo 'As senhas são diferentes!!';
	exit();
}


$query = $pdo->prepare("UPDATE clientes SET nome = :nome, telefone = :telefone, cpf = :cpf, senha_crip = '$senha_crip', endereco = :endereco WHERE id = '$id'");

$query->bindValue(":nome", "$nome");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":endereco", "$endereco");
$query->execute();

echo 'Editado com Sucesso';
 ?>