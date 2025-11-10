<?php 
@session_start();
require_once("../../../conexao.php");

$usuario_id = $_POST['usuario_id'];
$data_inicio = $_POST['data_inicio'];
$data_fim = $_POST['data_fim'];
$observacao = @$_POST['observacao'];
$admin_id = @$_SESSION['id'];

if(empty($usuario_id) || empty($data_inicio) || empty($data_fim)){
	echo 'Preencha todos os campos obrigatórios!';
	exit();
}

if(strtotime($data_fim) < strtotime($data_inicio)){
	echo 'A data fim não pode ser menor que a data início!';
	exit();
}

$diff = (strtotime($data_fim) - strtotime($data_inicio)) / (60 * 60 * 24);
if($diff > 30){
	echo 'O período não pode ser maior que 30 dias!';
	exit();
}

$data_atual = $data_inicio;
$dias_lancados = 0;

while(strtotime($data_atual) <= strtotime($data_fim)){
	$query_existe = $pdo->query("SELECT * FROM pontos WHERE usuario_id = '$usuario_id' AND data = '$data_atual'");
	$res_existe = $query_existe->fetchAll(PDO::FETCH_ASSOC);
	
	if(@count($res_existe) == 0){
		$pdo->query("INSERT INTO pontos SET
			usuario_id = '$usuario_id',
			data = '$data_atual',
			status = 'folga',
			tipo_registro = 'folga',
			observacao_atestado = '$observacao',
			horas_trabalhadas = 0,
			horas_extras = 0");
		
		$dias_lancados++;
	} else {
		$pdo->query("UPDATE pontos SET
			status = 'folga',
			tipo_registro = 'folga',
			observacao_atestado = '$observacao',
			horas_trabalhadas = 0,
			horas_extras = 0
			WHERE usuario_id = '$usuario_id' AND data = '$data_atual'");
		
		$dias_lancados++;
	}
	
	$data_atual = date('Y-m-d', strtotime($data_atual . ' +1 day'));
}

$query_usuario = $pdo->query("SELECT nome FROM usuarios WHERE id = '$usuario_id'");
$res_usuario = $query_usuario->fetch(PDO::FETCH_ASSOC);
$nome_usuario = $res_usuario['nome'];

$data_inicioF = implode('/', array_reverse(explode('-', $data_inicio)));
$data_fimF = implode('/', array_reverse(explode('-', $data_fim)));

echo 'Sucesso! Folga lançada para '.$nome_usuario.' do dia '.$data_inicioF.' até '.$data_fimF.' ('.$dias_lancados.' dia(s))';
?>

