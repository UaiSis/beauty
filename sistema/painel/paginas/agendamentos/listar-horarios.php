<?php 
require_once("../../../conexao.php");
@session_start();
$usuario = @$_SESSION['id'];

$checado3 = '';
$esconder3 = '';

$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];
$hora_rec = '';
$hora_atual = date('H:i:s');
$hoje = date('Y-m-d');
$servico = @$_POST['servico'];

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

	if(@strtotime(@$res2[0]['hora']) == @strtotime($inicio)){
		$esconder = 'text-danger';
		$checado = 'disabled';
	}else{
		$esconder = '';
		$checado = '';
	}

	if(@strtotime($hora) < @strtotime($hora_atual) and @strtotime($data) == @strtotime($hoje)){
		$esconder2 = 'text-danger';
		$checado2 = 'disabled';
		$ocultar = 'ocultar';
	}else{
		$ocultar = '';
		$esconder2 = '';
		$checado2 = '';
		$i += 1;
		
		//VERIFICAR NA TABELA HORARIOS AGD SE TEM O HORARIO NESSA DATA
		$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$hora'");
		$res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res_agd) > 0){
			$esconder3 = 'text-danger';
			$checado3 = 'disabled';
		}else{
			$esconder3 = '';
			$checado3 = '';
		}

	}

	//CODIGO PARA RECUPERAR TEMPO DO SERVIÇO PARA VER SE CABERÁ O SERVIÇO NO INTERVALO
	if($servico != ""){
		$query_tempo_servico = $pdo->prepare("SELECT * FROM servicos where id = :servico");
		$query_tempo_servico->bindValue(":servico", "$servico");
		$query_tempo_servico->execute();
		$res_tempo_servico = $query_tempo_servico->fetchAll(PDO::FETCH_ASSOC);
		$tempo = @$res_tempo_servico[0]['tempo'] ?: 0;

		if($tempo > 0){
			//CODIGO PARA NÃO MOSTRAR O HORÁRIO SE TIVER AGENDAMENTO QUE NÃO CAIBA O SERVIÇO
			// Hora inicial do serviço tentado
			$hora_inicio_tentada = strtotime($hora);
			$hora_final_tentada = strtotime("+$tempo minutes", $hora_inicio_tentada);

			// Buscar todos os agendamentos no dia e funcionário
			$query_agd_verif = $pdo->prepare("SELECT * FROM agendamentos WHERE data = :data AND funcionario = :funcionario");
			$query_agd_verif->bindValue(":funcionario", "$funcionario");
			$query_agd_verif->bindValue(":data", "$data");
			$query_agd_verif->execute();
			$res_agd_verif = $query_agd_verif->fetchAll(PDO::FETCH_ASSOC);

			foreach($res_agd_verif as $agendamento) {
				$hora_agendada = strtotime($agendamento['hora']);
				
				// Buscar o tempo real do serviço agendado
				$servico_agendado = $agendamento['servico'];
				$query_tempo = $pdo->prepare("SELECT tempo FROM servicos WHERE id = :servico_id");
				$query_tempo->bindValue(":servico_id", $servico_agendado);
				$query_tempo->execute();
				$res_tempo = $query_tempo->fetchAll(PDO::FETCH_ASSOC);
				$tempo_servico_agendado = @$res_tempo[0]['tempo'] ?: 30; // padrão 30 minutos se não encontrar
				
				$hora_fim_agendada = strtotime("+$tempo_servico_agendado minutes", $hora_agendada);

				// Verificar se há sobreposição
				if (
					($hora_inicio_tentada < $hora_fim_agendada) && 
					($hora_final_tentada > $hora_agendada)
				) {
					// Há conflito de horário, desabilitar essa opção
					$esconder = 'text-danger';
					$checado = 'disabled';
					break;
				}
			}

			//verificacao almoço para não aparecer horarios
			if(($hora_inicio_tentada < strtotime($final_almoco)) && ($hora_final_tentada > strtotime($inicio_almoco))){
				$esconder = 'text-danger';
				$checado = 'disabled';
			}

			// Verificar se algum dos horários necessários está bloqueado manualmente
			$query_bloq = $pdo->query("SELECT * FROM horarios_agd WHERE data = '$data' AND funcionario = '$funcionario'");
			$res_bloq = $query_bloq->fetchAll(PDO::FETCH_ASSOC);

			foreach($res_bloq as $bloqueio) {
				$hora_bloqueada = strtotime($bloqueio['horario']);
				
				// Verificar se algum momento do serviço cai em um horário bloqueado
				if ($hora_bloqueada >= $hora_inicio_tentada && $hora_bloqueada < $hora_final_tentada) {
					$esconder = 'text-danger';
					$checado = 'disabled';
					break;
				}
			}
		}
	}



	if(@strtotime($dataH) != @strtotime($data) and $dataH != "" and $dataH != "null"){

		continue;
	}	
				?>

				<div class="col-md-2 <?php echo $ocultar ?>">
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $checado ?> <?php echo $checado2 ?> <?php echo $checado3 ?>>
					  <label class="form-check-label <?php echo $esconder ?> <?php echo $esconder2 ?> <?php echo $esconder3 ?>" for="flexRadioDefault1">
					    <?php echo $horaF ?>
					  </label>
					</div>
				</div> 
<?php 
				
		

		$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($inicio));			
		$inicio = date('H:i:s', $hora_minutos);

	}
}
	
	if($i == 0){
		echo 'Não temos mais horários disponíveis com este funcionário para essa data!';
	}
	?>



</div>