<?php 
require_once("../sistema/conexao.php");
@session_start();
$telefone = filter_var($_POST['telefone'], @FILTER_SANITIZE_STRING);
$nome = filter_var($_POST['nome'], @FILTER_SANITIZE_STRING);
$id = filter_var(@$_POST['id'], @FILTER_SANITIZE_STRING);


$query = $pdo->query("SELECT * FROM itens_assinaturas where id = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_grupo = $res[0]['grupo'];
$valor = $res[0]['valor'];

$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
//Cadastrar o cliente caso não tenha cadastro
$query = $pdo->prepare("SELECT * FROM clientes where telefone LIKE :telefone ");
$query->bindValue(":telefone", "$telefone");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	$query = $pdo->prepare("INSERT INTO clientes SET nome = :nome, telefone = :telefone, data_cad = curDate(), cartoes = '0', alertado = 'Não', senha_crip = '$senha_crip'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":telefone", "$telefone");	
	$query->execute();
	$id_cliente = $pdo->lastInsertId();

}else{
	$id_cliente = $res[0]['id'];
}


//excluir assinaturas anteriores que estiverem pendentes
$pdo->query("DELETE FROM assinaturas where cliente = '$id_cliente' and item = '$id' and pago != 'Sim'");

//marcar o agendamento
$pdo->query("INSERT INTO assinaturas SET cliente = '$id_cliente', data = curDate(), pago = 'Não', grupo = '$id_grupo', item = '$id', valor = '$valor', frequencia = '30', vencimento = curDate()");

$ult_id = $pdo->lastInsertId();
echo 'Salvo*'.$ult_id;

?>