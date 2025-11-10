<?php 
require_once("../sistema/conexao.php");
@session_start();
$usuario = @$_SESSION['id'];

$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];
$hora_rec = @$_POST['hora'];
$servico = @$_POST['servico'];

$hoje = date('Y-m-d');
$hora_atual = date('H:i:s');

if(@strtotime($data) < @strtotime($hoje)){
	echo '000';
	exit();
}

if($funcionario == ""){
	
	exit();
}


//verificar se possui essa data nos dias bloqueio geral
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '0' and data = '$data'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	echo 'Não estaremos funcionando nesta Data!';
	exit();
}

//verificar se possui essa data nos dias bloqueio func
$query = $pdo->query("SELECT * FROM dias_bloqueio where funcionario = '$funcionario'  and data = '$data'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
		echo 'Este Profissional não irá trabalhar nesta Data, selecione outra data ou escolhar outro Profissional!';
		exit();
}


$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', @strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
		echo 'Este Funcionário não trabalha neste Dia!';
	exit();
}else{
	$inicio = $res[0]['inicio'];
	$final = $res[0]['final'];
	$inicio_almoco = $res[0]['inicio_almoco'];
	$final_almoco = $res[0]['final_almoco'];
}

$query = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $res[0]['intervalo'];


?>
<div class="row">

	<?php 
	$i = 0;
	while (@strtotime($inicio) < @strtotime($final)){
	
	if(@strtotime($inicio) >= @strtotime($inicio_almoco) and @strtotime($inicio) < @strtotime($final_almoco)){
		$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));			
		$inicio = date('H:i:s', $hora_minutos);
	}else{
			
				$hora = $inicio;
				$horaF = date("H:i", @strtotime($hora));
				$dataH = '';

				//validar horario
$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);

$total_reg2 = @count($res2);
if($total_reg2 == 0 || @strtotime($hora_rec) == @strtotime($hora)){
	$hora_agendada = '';
	$texto_hora = '';

	if(@strtotime($hora_rec) == @strtotime($hora)){
		$checado = 'checked';
	}else{
		$checado = '';
	}

	if(@strtotime($hora) < @strtotime($hora_atual) and @strtotime($data) == @strtotime($hoje)){
		$esconder = 'none';
	}else{
		$esconder = '';
				
		//VERIFICAR NA TABELA HORARIOS AGD SE TEM O HORARIO NESSA DATA
		$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$hora'");
		$res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res_agd) > 0){
			$esconder = 'none';			
		}else{
			$esconder = '';
			$i += 1;
		}

	}




//CODIGO PARA RECUPERAR TEMPO DO SERVIÇO PARA VER SE CABERÁ O SERVIÇO NO INTERVALO
$query = $pdo->prepare("SELECT * FROM servicos where id = :servico");
$query->bindValue(":servico", "$servico");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = $res[0]['tempo'];


//CODIGO PARA NÃO MOSTRAR O HORÁRIO SE TIVER AGENDAMENTO QUE NÃO CAIBA O SERVIÇO
	// Hora inicial do serviço tentado
$hora_inicio_tentada = strtotime($hora);
$hora_final_tentada = strtotime("+$tempo minutes", $hora_inicio_tentada);

$hora_minutos = @strtotime("+$tempo minutes", @strtotime($hora));			
$nova_hora = date('H:i:s', $hora_minutos);		

// Buscar todos os agendamentos no dia e funcionário
$query_agd = $pdo->prepare("SELECT * FROM agendamentos WHERE data = :data AND funcionario = :funcionario");
$query_agd->bindValue(":funcionario", "$funcionario");
$query_agd->bindValue(":data", "$data");
$query_agd->execute();
$res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);

foreach($res_agd as $agendamento) {
    $hora_agendada = strtotime($agendamento['hora']);
    $hora_fim_agendada = strtotime("+$tempo minutes", $hora_agendada); // ou tempo salvo no banco, se variável

    // Verificar se há sobreposição
    if (
        ($hora_inicio_tentada < $hora_fim_agendada) && 
        ($hora_final_tentada > $hora_agendada)
    ) {
        // Há conflito de horário, esconder essa opção
        $esconder = 'none';
        break;
    }		



}

//verificacao almoço para não aparecer horarios
if(@strtotime($nova_hora) > @strtotime($inicio_almoco) and @strtotime($nova_hora) < @strtotime($final_almoco)){
		$esconder = 'none';       
}




	if(@strtotime($dataH) != @strtotime($data) and $dataH != "" and $dataH != "null"){
		continue;
	}	
				?>

					<div class="col-3" style='display: <?php echo $esconder ?>'>
					<div class="form-check">
					  <input onchange="$('#hora_rec').val('<?php echo $horaF ?>');" class="form-check-input" type="radio" name="hora" value="<?php echo $horaF ?>" <?php echo $hora_agendada ?> style="width:17px; height: 17px;" <?php echo $checado ?>>
					  <label class="form-check-label <?php echo $texto_hora ?> text-white" for="flexRadioDefault1">
					    <?php echo $horaF ?>
					  </label>
					</div>
				</div> 

				<?php 
				
		}

		$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));			
$inicio = date('H:i:s', $hora_minutos);

	}
}
	
	if($i == 0){
		echo '<div align="center"> <small>Não temos mais horários disponíveis com este funcionário para essa data!</small></div>';
	}
	?>


</div>