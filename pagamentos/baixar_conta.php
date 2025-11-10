<?php 
@session_start();
$id_usuario = $_SESSION['id'];

if($id_usuario == ""){
	$id_usuario = 0;
}

$data_pgto = date('Y-m-d');
$forma_pgto = 'Pix';

$query = $pdo->query("SELECT * FROM receber where id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$funcionario = $res[0]['funcionario'];
$servico = $res[0]['servico'];
$cliente = $res[0]['pessoa'];
$descricao = 'Comissão - '.$res[0]['descricao'];
$tipo = $res[0]['tipo'];
$valor = $res[0]['valor'];
$pgto = $res[0]['pgto'];
$valor_serv = $res[0]['valor'];
$frequencia = $res[0]['frequencia'];
$dias_frequencia = $res[0]['frequencia'];
$data_venc = $res[0]['data_venc'];
$hash = $res[0]['hash'];

if($hash != ""){
	require("agendar-delete.php");
}


$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone_cliente = $res2[0]['telefone'];
$nome_cliente = $res2[0]['nome'];

$valorF = number_format($valor, 2, ',', '.');

if($frequencia > 0){
		if($dias_frequencia == 30 || $dias_frequencia == 31){			
			$novo_vencimento = date('Y-m-d', @strtotime("+1 month",@strtotime($data_venc)));
		}else if($dias_frequencia == 90){			
			$novo_vencimento = date('Y-m-d', @strtotime("+3 month",@strtotime($data_venc)));
		}else if($dias_frequencia == 180){ 
			$novo_vencimento = date('Y-m-d', @strtotime("6 month",@strtotime($data_venc)));
		}else if($dias_frequencia == 360 || $dias_frequencia == 365){ 			
			$novo_vencimento = date('Y-m-d', @strtotime("+12 month",@strtotime($data_venc)));

		}else{			
			$novo_vencimento = date('Y-m-d', @strtotime("+$dias_frequencia days",@strtotime($data_venc)));
		}

		$novo_vencimentoF = implode('/', array_reverse(@explode('-', $novo_vencimento)));


		$pdo->query("INSERT INTO receber SET descricao = 'Plano Assinatura', tipo = 'Assinatura', valor = '$valor', data_lanc = curDate(), data_venc = '$novo_vencimento', usuario_lanc = '$id_usuario', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = 'Não', hora = curTime(), frequencia = '$frequencia', hora_alerta = '$hora_random'");
		
		$ultima_conta = $pdo->lastInsertId();

		

}



//verificar caixa aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
	$id_caixa = @$res1[0]['id'];
}else{
	$id_caixa = 0;
}


$pdo->query("UPDATE receber SET pgto = '$forma_pgto', valor = '$valor', pago = 'Sim', usuario_baixa = '$id_usuario', data_pgto = '$data_pgto', valor = '$valor_serv', caixa = '$id_caixa', hora = curTime() where id = '$id'");



//enviar whatsapp
		if ($msg_agendamento == 'Api' and $telefone_cliente != '') {
			$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);
			$mensagem = '💰 *' . $nome_sistema . '*%0A';
			$mensagem .= '_Pagamento Aprovado_ %0A';
			$mensagem .= '*Descrição:* ' . $descricao . ' %0A';
			$mensagem .= '*Valor:* ' . $valorF . ' %0A';
			
			require('../ajax/api-texto.php');
			
			
		}


 ?>