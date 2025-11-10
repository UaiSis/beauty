<?php 
@session_start();
require_once("../../../conexao.php");

$ponto_id = $_POST['ponto_id'];
$entrada = $_POST['entrada'];
$saida_almoco = $_POST['saida_almoco'];
$retorno_almoco = $_POST['retorno_almoco'];
$saida = $_POST['saida'];
$motivo = $_POST['motivo'];
$usuario_ajuste = $_SESSION['id'];

$query = $pdo->query("SELECT * FROM pontos WHERE id = '$ponto_id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	echo 'Registro não encontrado!';
	exit();
}

$dados_antigos = $res[0];

$horas_trabalhadas = 0;
$horas_extras = 0;

if($entrada && $saida){
	$entrada_time = strtotime($entrada);
	$saida_time = strtotime($saida);
	
	$total_segundos = $saida_time - $entrada_time;
	
	if($saida_almoco && $retorno_almoco){
		$saida_almoco_time = strtotime($saida_almoco);
		$retorno_almoco_time = strtotime($retorno_almoco);
		$almoco_segundos = $retorno_almoco_time - $saida_almoco_time;
		$total_segundos -= $almoco_segundos;
	}
	
	$horas_trabalhadas = $total_segundos / 3600;
	
	$query_config = $pdo->query("SELECT * FROM configuracoes_ponto WHERE usuario_id = '".$dados_antigos['usuario_id']."'");
	$res_config = $query_config->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_config) > 0){
		$horas_diarias = $res_config[0]['horas_diarias'];
		if($horas_trabalhadas > $horas_diarias){
			$horas_extras = $horas_trabalhadas - $horas_diarias;
		}
	}
}

$status = 'encerrado';
if(!$saida){
	if($saida_almoco && !$retorno_almoco){
		$status = 'almoco';
	} else {
		$status = 'aberto';
	}
}

$pdo->query("UPDATE pontos SET 
	entrada = '$entrada',
	saida_almoco = ".($saida_almoco ? "'$saida_almoco'" : "NULL").",
	retorno_almoco = ".($retorno_almoco ? "'$retorno_almoco'" : "NULL").",
	saida = ".($saida ? "'$saida'" : "NULL").",
	horas_trabalhadas = '$horas_trabalhadas',
	horas_extras = '$horas_extras',
	status = '$status'
	WHERE id = '$ponto_id'");

$campos_ajustados = array();

if($entrada != $dados_antigos['entrada']){
	$pdo->query("INSERT INTO ajustes_ponto SET 
		ponto_id = '$ponto_id',
		usuario_ajuste = '$usuario_ajuste',
		campo_ajustado = 'entrada',
		valor_anterior = '".$dados_antigos['entrada']."',
		valor_novo = '$entrada',
		motivo = '$motivo'");
}

if($saida_almoco != $dados_antigos['saida_almoco']){
	$pdo->query("INSERT INTO ajustes_ponto SET 
		ponto_id = '$ponto_id',
		usuario_ajuste = '$usuario_ajuste',
		campo_ajustado = 'saida_almoco',
		valor_anterior = '".$dados_antigos['saida_almoco']."',
		valor_novo = '$saida_almoco',
		motivo = '$motivo'");
}

if($retorno_almoco != $dados_antigos['retorno_almoco']){
	$pdo->query("INSERT INTO ajustes_ponto SET 
		ponto_id = '$ponto_id',
		usuario_ajuste = '$usuario_ajuste',
		campo_ajustado = 'retorno_almoco',
		valor_anterior = '".$dados_antigos['retorno_almoco']."',
		valor_novo = '$retorno_almoco',
		motivo = '$motivo'");
}

if($saida != $dados_antigos['saida']){
	$pdo->query("INSERT INTO ajustes_ponto SET 
		ponto_id = '$ponto_id',
		usuario_ajuste = '$usuario_ajuste',
		campo_ajustado = 'saida',
		valor_anterior = '".$dados_antigos['saida']."',
		valor_novo = '$saida',
		motivo = '$motivo'");
}

echo 'Ajustado com Sucesso';
?>

