<?php 



require_once("../sistema/conexao.php");
@session_start();
$telefone = filter_var($_POST['telefone'], @FILTER_SANITIZE_STRING);
$nome = filter_var($_POST['nome'], @FILTER_SANITIZE_STRING);
$funcionario = filter_var($_POST['funcionario'], @FILTER_SANITIZE_STRING);
$hora = filter_var(@$_POST['hora'], @FILTER_SANITIZE_STRING);
$servico = filter_var($_POST['servico'], @FILTER_SANITIZE_STRING);
$obs = filter_var($_POST['obs'], @FILTER_SANITIZE_STRING);
$data = filter_var(@$_POST['data'], @FILTER_SANITIZE_STRING);
$data_agd = filter_var(@$_POST['data'], @FILTER_SANITIZE_STRING);
$hora_do_agd = filter_var(@$_POST['hora'], @FILTER_SANITIZE_STRING);
$id = filter_var(@$_POST['id'], @FILTER_SANITIZE_STRING);

$hash = "";



$tel_cli = $_POST['telefone'];

$query = $pdo->prepare("SELECT * FROM usuarios where id = :funcionario");
$query->bindValue(":funcionario", "$funcionario");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = @$res[0]['intervalo'];

$query = $pdo->prepare("SELECT * FROM servicos where id = :servico");
$query->bindValue(":servico", "$servico");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = @$res[0]['tempo'];


$hora_minutos = @strtotime("+$tempo minutes", @strtotime($hora));			
$hora_final_servico = date('H:i:s', $hora_minutos);

$nova_hora = $hora;



$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', @strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->prepare("SELECT * FROM dias where funcionario = :funcionario and dia = '$dia_procurado'");
$query->bindValue(":funcionario", "$funcionario");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
		echo 'Este Profissional não trabalha neste Dia!';
	exit();
}else{
	$inicio = $res[0]['inicio'];
	$final = $res[0]['final'];
	$inicio_almoco = $res[0]['inicio_almoco'];
	$final_almoco = $res[0]['final_almoco'];
}

//verificar se possui essa data nos dias bloqueio geral
$query = $pdo->prepare("SELECT * FROM dias_bloqueio where funcionario = '0' and data = :data_agd");
$query->bindValue(":data_agd", "$data_agd");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
	echo 'Não estaremos funcionando nesta Data!';
	exit();
}

//verificar se possui essa data nos dias bloqueio func
$query = $pdo->prepare("SELECT * FROM dias_bloqueio where funcionario = :funcionario  and data = :data_agd");
$query->bindValue(":data_agd", "$data_agd");
$query->bindValue(":funcionario", "$funcionario");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
		echo 'Este Profissional não irá trabalhar nesta Data, selecione outra data ou escolhar outro Profissional!';
		exit();
}

while (@strtotime($nova_hora) < @strtotime($hora_final_servico)){
		
		$hora_minutos = @strtotime("+$intervalo minutes", @strtotime($nova_hora));			
		$nova_hora = date('H:i:s', $hora_minutos);		
		
		//VERIFICAR NA TABELA HORARIOS AGD SE TEM O HORARIO NESSA DATA
		$query_agd = $pdo->prepare("SELECT * FROM horarios_agd where data = :data and funcionario = :funcionario and horario = '$nova_hora'");
		$query_agd->bindValue(":funcionario", "$funcionario");
		$query_agd->bindValue(":data", "$data");
		$query_agd->execute();
		$res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res_agd) > 0){
			echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
			exit();
		}



		//VERIFICAR NA TABELA AGENDAMENTOS SE TEM O HORARIO NESSA DATA e se tem um intervalo entre o horario marcado e o proximo agendado nessa tabela
		$query_agd = $pdo->prepare("SELECT * FROM agendamentos where data = :data and funcionario = :funcionario and hora = '$nova_hora'");
		$query_agd->bindValue(":funcionario", "$funcionario");
		$query_agd->bindValue(":data", "$data");
		$query_agd->execute();
		$res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res_agd) > 0){
			if($tempo <= $intervalo){

			}else{
				if($hora_final_servico == $res_agd[0]['hora']){
					
				}else{
					echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
						exit();
				}
				
			}
			
		}


		if(@strtotime($nova_hora) > @strtotime($inicio_almoco) and @strtotime($nova_hora) < @strtotime($final_almoco)){
		echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido ao horário de almoço!';
			exit();
	}

}



@$_SESSION['telefone'] = $telefone;

if($hora == ""){
	echo 'Escolha um Horário para Agendar!';
	exit();
}

if($data < date('Y-m-d')){
	echo 'Escolha uma data igual ou maior que Hoje!';
	exit();
}

//validar horario
$query = $pdo->prepare("SELECT * FROM agendamentos where data = :data and hora = :hora and funcionario = :funcionario");
$query->bindValue(":funcionario", "$funcionario");
$query->bindValue(":data", "$data");
$query->bindValue(":hora", "$hora");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0 and $res[0]['id'] != $id){
	echo 'Este horário não está disponível!';
	exit();
}

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

	//verificar se o cliente tem débito
	$query22 = $pdo->query("SELECT * FROM receber where pessoa = '$id_cliente' and data_venc < curDate() and pago = 'Não'");
	$res22 = $query22->fetchAll(PDO::FETCH_ASSOC);
	$total_debitos = @count($res22);
	if($total_debitos > 0){
		echo 'Você possui conta em aberto, efetue o pagamento antes de agendar!';
		exit();
	}

}


//excluir agendamentos temporarios deste cliente
$pdo->query("DELETE FROM agendamentos_temp where cliente = '$id_cliente'");

//marcar o agendamento
$query = $pdo->prepare("INSERT INTO agendamentos_temp SET funcionario = :funcionario, cliente = '$id_cliente', hora = :hora, data = :data_agd, usuario = '0', status = 'Agendado', obs = :obs, data_lanc = curDate(), servico = :servico, hash = '$hash'");

$query->bindValue(":funcionario", "$funcionario");
$query->bindValue(":hora", "$hora");	
$query->bindValue(":servico", "$servico");
$query->bindValue(":data_agd", "$data_agd");
$query->bindValue(":obs", "$obs");
$query->execute();

$ult_id = $pdo->lastInsertId();
echo 'Pré Agendado*'.$ult_id;

?>