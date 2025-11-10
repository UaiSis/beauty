<?php 
require_once("../../../conexao.php");

$data = @$_POST['data'];
if($data == ""){
	$data = date('Y-m-d');
}

$presentes = 0;
$ausentes = 0;
$total_horas = 0;
$total_extras = 0;

$query = $pdo->query("SELECT * FROM usuarios WHERE atendimento = 'Sim' AND ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_funcionarios = @count($res);

$query = $pdo->query("SELECT COUNT(*) as total FROM pontos WHERE data = '$data'");
$res = $query->fetch(PDO::FETCH_ASSOC);
$presentes = $res['total'];

$ausentes = $total_funcionarios - $presentes;

$query = $pdo->query("SELECT SUM(horas_trabalhadas) as total FROM pontos WHERE data = '$data'");
$res = $query->fetch(PDO::FETCH_ASSOC);
$total_horas = @$res['total'] ? $res['total'] : 0;

$query = $pdo->query("SELECT SUM(horas_extras) as total FROM pontos WHERE data = '$data'");
$res = $query->fetch(PDO::FETCH_ASSOC);
$total_extras = @$res['total'] ? $res['total'] : 0;

$total_horasF = number_format($total_horas, 2).'h';
$total_extrasF = number_format($total_extras, 2).'h';

$resultado = array(
	'presentes' => $presentes,
	'ausentes' => $ausentes,
	'horas' => $total_horasF,
	'extras' => $total_extrasF
);

echo json_encode($resultado);
?>

