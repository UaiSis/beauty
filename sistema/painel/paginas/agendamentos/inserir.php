<?php 
$tabela = 'agendamentos';
require_once("../../../conexao.php");

@session_start();
$usuario_logado = @$_SESSION['id'].'';

$cliente = $_POST['cliente'];
$data = $_POST['data'];
$hora = @$_POST['hora'];
$obs = $_POST['obs'];
$id = $_POST['id'];
$funcionario = $_POST['funcionario'];
$servico = $_POST['servico'];
$data_agd = $_POST['data'];
$hora_do_agd = @$_POST['hora'];
$hash = '';

if(@$hora == ""){
	echo 'Selecione um Horário antes de agendar!';
	exit();
}


$query = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $res[0]['intervalo'];
$nome_func = @$res[0]['nome'];
$tel_func = @$res[0]['telefone'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = $res[0]['tempo'];
$nome_serv = @$res[0]['nome'];

$hora_minutos = strtotime("+$tempo minutes", strtotime($hora));			
$hora_final_servico = date('H:i:s', $hora_minutos);

$nova_hora = $hora;



$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	//echo 'Este Funcionário não trabalha neste Dia!';
	//exit();
}else{
	$inicio = $res[0]['inicio'];
	$final = $res[0]['final'];
	$inicio_almoco = $res[0]['inicio_almoco'];
	$final_almoco = $res[0]['final_almoco'];
}



$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", strtotime($hora));




// Verificar conflitos com agendamentos existentes usando lógica de sobreposição
$query_agd = $pdo->query("SELECT * FROM agendamentos WHERE data = '$data' AND funcionario = '$funcionario' AND id != '$id'");
$res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);

$hora_inicio_tentada = strtotime($hora);
$hora_final_tentada = strtotime($hora_final_servico);

foreach($res_agd as $agendamento_existente) {
    // Buscar o tempo real do serviço agendado
    $servico_agendado = $agendamento_existente['servico'];
    $query_tempo = $pdo->query("SELECT tempo FROM servicos WHERE id = '$servico_agendado'");
    $res_tempo = $query_tempo->fetchAll(PDO::FETCH_ASSOC);
    $tempo_servico_agendado = @$res_tempo[0]['tempo'] ?: 30;
    
    $hora_agendada = strtotime($agendamento_existente['hora']);
    $hora_fim_agendada = strtotime("+$tempo_servico_agendado minutes", $hora_agendada);

    // Verificar se há sobreposição de horários
    if (($hora_inicio_tentada < $hora_fim_agendada) && ($hora_final_tentada > $hora_agendada)) {
        echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
        exit();
    }
}

// Verificar conflitos com horários bloqueados manualmente
$query_bloq = $pdo->query("SELECT * FROM horarios_agd WHERE data = '$data' AND funcionario = '$funcionario'");
$res_bloq = $query_bloq->fetchAll(PDO::FETCH_ASSOC);

foreach($res_bloq as $bloqueio) {
    $hora_bloqueada = strtotime($bloqueio['horario']);
    
    // Verificar se algum momento do serviço cai em um horário bloqueado
    if ($hora_bloqueada >= $hora_inicio_tentada && $hora_bloqueada < $hora_final_tentada) {
        echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
        exit();
    }
}

// Verificar se o serviço conflita com horário de almoço
if(strtotime($hora_final_servico) > strtotime($inicio_almoco) and strtotime($hora) < strtotime($final_almoco)){
    // Verifica se há sobreposição com o horário de almoço
    if((strtotime($hora) < strtotime($final_almoco)) && (strtotime($hora_final_servico) > strtotime($inicio_almoco))){
        echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido ao horário de almoço!';
        exit();
    }
}


//validar horario
$query = $pdo->query("SELECT * FROM $tabela where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0 and $res[0]['id'] != $id){
	echo 'Este horário não está disponível!';
	exit();
}





//pegar nome do cliente
$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente = $res[0]['nome'];
$telefone = $res[0]['telefone'];

if($not_sistema == 'Sim'){
	$mensagem_not = $nome_cliente;
	$titulo_not = 'Novo Agendamento '.$dataF.' - '.$horaF;
	$id_usu = $funcionario;
	require('../../../../api/notid.php');
} 


if($msg_agendamento == 'Api'){

//agendar o alerta de confirmação
$hora_atual = date('H:i:s');
$data_atual = date('Y-m-d');
$hora_minutos = strtotime("-$minutos_aviso minutes", strtotime($hora));
$nova_hora = date('H:i:s', $hora_minutos);


$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);


}


$query = $pdo->prepare("INSERT INTO $tabela SET funcionario = '$funcionario', cliente = '$cliente', hora = '$hora', data = '$data_agd', usuario = '$usuario_logado', status = 'Agendado', obs = :obs, data_lanc = curDate(), servico = '$servico', hash = '$hash'");

$query->bindValue(":obs", "$obs");
$query->execute();


$ult_id = $pdo->lastInsertId();

if($msg_agendamento == 'Api'){
if(strtotime($hora_atual) < strtotime($nova_hora) or strtotime($data_atual) != strtotime($data_agd)){

		$mensagem = '*Confirmação de Agendamento* ';
		$mensagem .= '                              Profissional: *'.$nome_func.'*';
		$mensagem .= '                                         Serviço: *'.$nome_serv.'*';
		$mensagem .= '                                               	       Data: *'.$dataF.'*';
		$mensagem .= '                                               	       Hora: *'.$horaF.'*';
		$mensagem .= '                                                             ';
		$mensagem .= '                                 _(Digite o número com a opção desejada)_';
		$mensagem .= '                                 1.  Digite 1️⃣ para confirmar ✅';		
		$mensagem .= '                                 2.  Digite 2️⃣ para Cancelar ❌';
		
		$id_envio = $ult_id;
		$data_envio = $data_agd.' '.$hora_do_agd;
		
		if($minutos_aviso > 0){
			require("../../../../ajax/confirmacao.php");
			$id_hash = $id;		
			$pdo->query("UPDATE agendamentos SET hash = '$id_hash' WHERE id = '$ult_id'");		
		}
	
}




$mensagem = '_Novo Agendamento_ %0A';
$mensagem .= 'Profissional: *'.$nome_func.'* %0A';
$mensagem .= 'Serviço: *'.$nome_serv.'* %0A';
$mensagem .= 'Data: *'.$dataF.'* %0A';
$mensagem .= 'Hora: *'.$horaF.'* %0A';
$mensagem .= 'Cliente: *'.$nome_cliente.'* %0A';
if($obs != ""){
	$mensagem .= 'Obs: *'.$obs.'* %0A';
}


require('../../../../ajax/api-texto.php');

if($tel_func != $whatsapp_sistema){
	$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $tel_func);
	require('../../../../ajax/api-texto.php');
}


}

while (strtotime($hora) < strtotime($hora_final_servico)){
		
		$hora_minutos = strtotime("+$intervalo minutes", strtotime($hora));			
		$hora = date('H:i:s', $hora_minutos);

		if(strtotime($hora) < strtotime($hora_final_servico)){
			$query = $pdo->query("INSERT INTO horarios_agd SET agendamento = '$ult_id', horario = '$hora', funcionario = '$funcionario', data = '$data_agd'");
		}
	

}


echo 'Salvo com Sucesso'; 

?>