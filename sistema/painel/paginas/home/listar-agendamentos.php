<?php
require_once("../../../conexao.php");

$data_filtro = isset($_POST['data']) ? $_POST['data'] : date('Y-m-d');
$func_filtro = isset($_POST['funcionario']) ? $_POST['funcionario'] : '';

$where_func = $func_filtro ? "AND funcionario = '$func_filtro'" : '';
$query = $pdo->query("SELECT * FROM agendamentos WHERE data = '$data_filtro' $where_func ORDER BY hora asc");
$agendamentos = $query->fetchAll(PDO::FETCH_ASSOC);

if(count($agendamentos) > 0){
	foreach($agendamentos as $agenda){
		$id_agenda = $agenda['id'];
		$cliente_id = $agenda['cliente'];
		$hora = substr($agenda['hora'], 0, 5);
		$status = $agenda['status'];
		$servico_id = $agenda['servico'];
		
		// Buscar cliente
		$query2 = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente_id'");
		$cliente = $query2->fetch(PDO::FETCH_ASSOC);
		$nome_cliente = $cliente ? $cliente['nome'] : 'Cliente não encontrado';
		$telefone_cliente = $cliente ? $cliente['telefone'] : '';
		
		// Buscar serviço
		$query2 = $pdo->query("SELECT * FROM servicos WHERE id = '$servico_id'");
		$servico = $query2->fetch(PDO::FETCH_ASSOC);
		$nome_servico = $servico ? $servico['nome'] : 'Serviço';
		
		// Status badge
		$status_color = '#ff9800';
		$status_bg = 'rgba(255, 152, 0, 0.1)';
		if($status == 'Concluído'){
			$status_color = '#00d896';
			$status_bg = 'rgba(0, 216, 150, 0.1)';
		} else if($status == 'Cancelado'){
			$status_color = '#ef5350';
			$status_bg = 'rgba(239, 83, 80, 0.1)';
		}
		
		// Avatar com iniciais
		$iniciais = '';
		$palavras = explode(' ', $nome_cliente);
		if(count($palavras) >= 2){
			$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
		}else{
			$iniciais = strtoupper(substr($nome_cliente, 0, 2));
		}
		
		$cores_avatar = ['#007A63', '#42a5f5', '#9c27b0', '#ff9800', '#ef5350', '#00d896'];
		$cor_avatar = $cores_avatar[$id_agenda % count($cores_avatar)];
		
		echo '<div class="agenda-item" data-status="'.$status.'" style="display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f0f0f0; transition: all 0.2s ease;">
			<div style="width: 44px; height: 44px; border-radius: 50%; background: '.$cor_avatar.'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: #fff; margin-right: 12px; flex-shrink: 0;">
				'.$iniciais.'
			</div>
			<div style="flex: 1; min-width: 0;">
				<div style="font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 2px;">'.$nome_cliente.'</div>
				<div style="font-size: 12px; color: #6c757d;">'.$telefone_cliente.'</div>
			</div>
			<div style="text-align: right; margin-right: 12px;">
				<div style="font-size: 13px; font-weight: 600; color: #1a1a1a;">'.$hora.'</div>
				<div style="font-size: 11px; color: #6c757d;">'.$nome_servico.'</div>
			</div>
			<div>
				<span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: '.$status_bg.'; color: '.$status_color.';">
					'.$status.'
				</span>
			</div>
			<div style="margin-left: 12px; display: flex; gap: 4px;">
				<a href="agendamentos" class="table-action-icon view" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: rgba(66, 165, 245, 0.08); color: #42a5f5; text-decoration: none;">
					<i class="fa fa-eye"></i>
				</a>
			</div>
		</div>';
	}
} else {
	echo '<div style="text-align: center; padding: 40px 20px; color: #6c757d;">
		<i class="fa fa-calendar-o" style="font-size: 32px; margin-bottom: 12px; opacity: 0.3;"></i>
		<p style="font-size: 14px; margin: 0;">Nenhum agendamento para esta data</p>
	</div>';
}
?>


