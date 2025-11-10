<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];
$usuario_id = $_POST['usuario_id'];
$hora_entrada = $_POST['hora_entrada'];
$hora_saida = $_POST['hora_saida'];
$horas_diarias = $_POST['horas_diarias'];
$tolerancia_minutos = $_POST['tolerancia_minutos'];
$almoco_obrigatorio = $_POST['almoco_obrigatorio'];
$duracao_almoco = $_POST['duracao_almoco'];
$ativo = $_POST['ativo'];

$dias_trabalho = '';
if(isset($_POST['dias']) && is_array($_POST['dias'])){
	$dias_trabalho = implode(',', $_POST['dias']);
} else {
	echo 'Selecione pelo menos um dia de trabalho!';
	exit();
}

if($id == ""){
	$query = $pdo->query("SELECT * FROM configuracoes_ponto WHERE usuario_id = '$usuario_id'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		echo 'Este funcionário já possui uma configuração cadastrada!';
		exit();
	}
	
	$pdo->query("INSERT INTO configuracoes_ponto SET 
		usuario_id = '$usuario_id',
		hora_entrada = '$hora_entrada',
		hora_saida = '$hora_saida',
		horas_diarias = '$horas_diarias',
		tolerancia_minutos = '$tolerancia_minutos',
		dias_trabalho = '$dias_trabalho',
		almoco_obrigatorio = '$almoco_obrigatorio',
		duracao_almoco = '$duracao_almoco',
		ativo = '$ativo'");
} else {
	$query = $pdo->query("SELECT * FROM configuracoes_ponto WHERE usuario_id = '$usuario_id' AND id != '$id'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res) > 0){
		echo 'Este funcionário já possui uma configuração cadastrada!';
		exit();
	}
	
	$pdo->query("UPDATE configuracoes_ponto SET 
		usuario_id = '$usuario_id',
		hora_entrada = '$hora_entrada',
		hora_saida = '$hora_saida',
		horas_diarias = '$horas_diarias',
		tolerancia_minutos = '$tolerancia_minutos',
		dias_trabalho = '$dias_trabalho',
		almoco_obrigatorio = '$almoco_obrigatorio',
		duracao_almoco = '$duracao_almoco',
		ativo = '$ativo'
		WHERE id = '$id'");
}

echo 'Salvo com Sucesso';
?>

