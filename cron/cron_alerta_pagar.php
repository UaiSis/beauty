<?php 
require_once("../sistema/conexao.php");

$query = $pdo->query("SELECT * from pagar where data_venc = curDate() and pago != 'Sim' and hora_alerta <= curTime() and (alerta is null or alerta != 'Sim') ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_vencidas = @count($res);
for ($i = 0; $i < $contas_pagar_vencidas; $i++) {
	$valor = $res[$i]['valor'];
	$descricao = $res[$i]['descricao'];
	$id = $res[$i]['id'];
	
	$valorF = @number_format($valor, 2, ',', '.');

	echo $whatsapp_sistema;

	//enviar whatsapp
		if ($msg_agendamento == 'Api' and $whatsapp_sistema != '') {
			$telefone = '55' . preg_replace('/[ ()-]+/', '', $whatsapp_sistema);
			$mensagem = '💰 *' . $nome_sistema . '*%0A';
			$mensagem .= '_Conta Vencendo Hoje_ %0A';
			$mensagem .= '*Descrição:* ' . $descricao . ' %0A';
			$mensagem .= '*Valor:* ' . $valorF . ' %0A';
			
			require('texto.php');

			if(@$status_mensagem == "Mensagem enviada com sucesso." and $api == 'menuia'){
				$pdo->query("UPDATE pagar SET alerta = 'Sim' where id = '$id'");
			}

			if($api != 'menuia'){
				$pdo->query("UPDATE pagar SET alerta = 'Sim' where id = '$id'");
			}
			
		}

}

echo $contas_pagar_vencidas;

 ?>