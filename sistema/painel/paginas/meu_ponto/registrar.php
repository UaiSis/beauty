<?php 
@session_start();
require_once("../../../conexao.php");

$tipo = @$_POST['tipo'];
$latitude = @$_POST['latitude'];
$longitude = @$_POST['longitude'];
$ponto_id = @$_POST['ponto_id'];
$usuario_id = @$_POST['usuario_id'];
$data_hoje = date('Y-m-d');
$hora_atual = date('H:i:s');

$ip = $_SERVER['REMOTE_ADDR'];
if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $ip = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
}

if($ponto_id == ""){
	if($tipo != 'entrada'){
		echo 'Você precisa registrar a entrada primeiro!';
		exit();
	}
	
	$pdo->query("INSERT INTO pontos SET 
		usuario_id = '$usuario_id',
		data = '$data_hoje',
		entrada = '$hora_atual',
		ip_entrada = '$ip',
		latitude_entrada = '$latitude',
		longitude_entrada = '$longitude',
		status = 'aberto'");
	
	echo 'Entrada registrada com sucesso!';
	exit();
}

$query = $pdo->query("SELECT * FROM pontos WHERE id = '$ponto_id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	echo 'Registro de ponto não encontrado!';
	exit();
}

$dados_ponto = $res[0];

switch($tipo){
	case 'entrada':
		echo 'Entrada já foi registrada!';
		exit();
		break;
		
	case 'saida_almoco':
		if($dados_ponto['saida_almoco']){
			echo 'Saída para almoço já foi registrada!';
			exit();
		}
		
		try {
			$pdo->query("UPDATE pontos SET 
				saida_almoco = '$hora_atual',
				ip_saida_almoco = '$ip',
				latitude_saida_almoco = '$latitude',
				longitude_saida_almoco = '$longitude',
				status = 'almoco'
				WHERE id = '$ponto_id'");
			
			echo 'Saída para almoço registrada com sucesso!';
		} catch (Exception $e) {
			echo 'Erro ao registrar: ' . $e->getMessage();
		}
		break;
		
	case 'retorno_almoco':
		if(!$dados_ponto['saida_almoco']){
			echo 'Você precisa registrar a saída para almoço primeiro!';
			exit();
		}
		if($dados_ponto['retorno_almoco']){
			echo 'Retorno do almoço já foi registrado!';
			exit();
		}
		
		try {
			$pdo->query("UPDATE pontos SET 
				retorno_almoco = '$hora_atual',
				ip_retorno_almoco = '$ip',
				latitude_retorno_almoco = '$latitude',
				longitude_retorno_almoco = '$longitude',
				status = 'aberto'
				WHERE id = '$ponto_id'");
			
			echo 'Retorno do almoço registrado com sucesso!';
		} catch (Exception $e) {
			echo 'Erro ao registrar: ' . $e->getMessage();
		}
		break;
		
	case 'saida':
		if($dados_ponto['saida']){
			echo 'Saída já foi registrada!';
			exit();
		}
		
		try {
			$entrada_time = strtotime($dados_ponto['entrada']);
			$saida_time = strtotime($hora_atual);
			
			$total_segundos = $saida_time - $entrada_time;
			
			if($dados_ponto['saida_almoco'] && $dados_ponto['retorno_almoco']){
				$saida_almoco_time = strtotime($dados_ponto['saida_almoco']);
				$retorno_almoco_time = strtotime($dados_ponto['retorno_almoco']);
				$almoco_segundos = $retorno_almoco_time - $saida_almoco_time;
				$total_segundos -= $almoco_segundos;
			}
			
			$horas_trabalhadas = $total_segundos / 3600;
			
			$horas_extras = 0;
			$query_config = $pdo->query("SELECT * FROM configuracoes_ponto WHERE usuario_id = '$usuario_id' AND ativo = 'Sim'");
			$res_config = $query_config->fetchAll(PDO::FETCH_ASSOC);
			if(@count($res_config) > 0){
				$horas_diarias = $res_config[0]['horas_diarias'];
				if($horas_trabalhadas > $horas_diarias){
					$horas_extras = $horas_trabalhadas - $horas_diarias;
				}
			}
			
			$pdo->query("UPDATE pontos SET 
				saida = '$hora_atual',
				ip_saida = '$ip',
				latitude_saida = '$latitude',
				longitude_saida = '$longitude',
				horas_trabalhadas = '$horas_trabalhadas',
				horas_extras = '$horas_extras',
				status = 'encerrado'
				WHERE id = '$ponto_id'");
			
			echo 'Saída registrada com sucesso! Total de horas: '.number_format($horas_trabalhadas, 2).'h';
		} catch (Exception $e) {
			echo 'Erro ao registrar: ' . $e->getMessage();
		}
		break;
}
?>

