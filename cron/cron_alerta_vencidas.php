<?php 
require_once("../sistema/conexao.php");

$query = $pdo->query("SELECT * from receber where data_venc < curDate() and pago != 'Sim' and pessoa > 0 and pessoa is not null and hora_alerta <= curTime() and (data_alerta != curDate() or data_alerta is null) ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];
	$cliente = $res[$i]['pessoa'];
	$vencimento = $res[$i]['data_venc'];

	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if (@count($res2) > 0) {
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	} else {
		$nome_cliente = 'Sem Registro';
		$telefone_cliente = "";
	}



$link_pgto = $url_sistema.'conta/'.$id;

$valorF = @number_format($valor, 2, ',', '.');
	

	//enviar whatsapp
		if ($msg_agendamento == 'Api' and $telefone_cliente != '') {
			$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone_cliente);
			$mensagem = '💰 *' . $nome_sistema . '*%0A';
			$mensagem .= '_Sua conta Venceu_ %0A';
			
			$mensagem .= '*Descrição:* '.$descricao.' %0A';
	$mensagem .= '*Cliente:* '.$nome_cliente.' %0A';	
	$mensagem .= '*Valor:* R$ '.$valorF.' %0A';	
	$mensagem .= '*Vencimento:* '.$vencimentoF.' %0A%0A';	
	$mensagem .= '*Link Pagamento:* %0A';
	$mensagem .= $link_pgto;
		
			
			require('texto.php');

			if(@$status_mensagem == "Mensagem enviada com sucesso." and $api == 'menuia'){
				$pdo->query("UPDATE receber SET data_alerta = curDate(), hora_alerta = '$hora_random' where id = '$id'");
			}

			if($api != 'menuia'){
				$pdo->query("UPDATE receber SET data_alerta = curDate(), hora_alerta = '$hora_random' where id = '$id'");
			}
			
		}

}

echo $contas_pagar_vencidas;

 ?>