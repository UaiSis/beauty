<?php 
require_once("../../../conexao.php");
@session_start();
$usuario = @$_SESSION['id'];
$data_atual = date('Y-m-d');

$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];

if($data == ""){
	$data = date('Y-m-d');
}

// Formatar data para exibição
$dataF = implode('/', array_reverse(@explode('-', $data)));
$data_extenso = strftime('%d de %B', strtotime($data));

$query = $pdo->query("SELECT * FROM agendamentos where data = '$data' ORDER BY hora asc");

if($funcionario != ""){
	$query = $pdo->query("SELECT * FROM agendamentos where funcionario = '$funcionario' and data = '$data' ORDER BY hora asc");
}

$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// Header da listagem
echo <<<HTML
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
	<h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">
		Agendamentos de {$dataF}
	</h3>
	<span style="font-size: 14px; color: #6c757d; font-weight: 500;">
		{$total_reg} agendamento(s)
	</span>
</div>
HTML;

if($total_reg > 0){

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$funcionario_agd = $res[$i]['funcionario'];
	$cliente_id = $res[$i]['cliente'];
	$hora = $res[$i]['hora'];
	$data_agd = $res[$i]['data'];
	$usuario_lanc = $res[$i]['usuario'];
	$data_lanc = $res[$i]['data_lanc'];
	$obs = $res[$i]['obs'];
	$status = $res[$i]['status'];
	$servico_id = $res[$i]['servico'];
	$valor_pago = $res[$i]['valor_pago'];

	$horaF = date("H:i", @strtotime($hora));

	// Buscar serviço
	$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico_id'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_serv = $res2[0]['nome'];
		$valor_serv = $res2[0]['valor'];
		$duracao_serv = $res2[0]['tempo'];
	}else{
		$nome_serv = 'Não Lançado';
		$valor_serv = 0;
		$duracao_serv = '30';
	}

	// Buscar cliente
	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente_id'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_cliente = $res2[0]['nome'];
		$telefone_cliente = $res2[0]['telefone'];
	}else{
		$nome_cliente = 'Sem Cliente';
		$telefone_cliente = '';
	}

	// Buscar funcionário
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario_agd'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_funcionario = $res2[0]['nome'];
	}else{
		$nome_funcionario = 'Sem Funcionário';
	}

	// Determinar status visual
	$status_badge = '';
	$status_class = '';
	
	if($status == 'Concluído'){
		$status_badge = '<span class="agendamento-badge confirmado"><i class="fa fa-check-circle"></i> Confirmado</span>';
		$status_class = 'confirmado';
	}else if($status == 'Agendado'){
		$status_badge = '<span class="agendamento-badge pendente"><i class="fa fa-clock-o"></i> Pendente</span>';
		$status_class = 'pendente';
	}else{
		$status_badge = '<span class="agendamento-badge cancelado"><i class="fa fa-times-circle"></i> Cancelado</span>';
		$status_class = 'cancelado';
	}

	// Calcular duração
	$duracao_text = $duracao_serv . ' minutos';

echo <<<HTML
<div class="agendamento-card {$status_class}">
	<div class="agendamento-card-header">
		<div class="agendamento-time-section">
			<div class="agendamento-time-icon">
				<i class="fa fa-clock-o"></i>
			</div>
			<div class="agendamento-time-info">
				<div class="agendamento-hora">{$horaF}</div>
				<div class="agendamento-duracao">{$duracao_text}</div>
			</div>
		</div>
		
		<div class="agendamento-status-actions">
			{$status_badge}
			<div class="agendamento-menu">
				<button class="agendamento-menu-btn" onclick="toggleAgendamentoMenu({$id})">
					<i class="fa fa-ellipsis-v"></i>
				</button>
				<div class="agendamento-menu-dropdown" id="menu-{$id}">
					<a href="#" onclick="mostrarServico('{$id}', '{$nome_serv}')">
						<i class="fa fa-dollar"></i> Adicionar Serviço
					</a>
					<a href="#" onclick="editar('{$id}', '{$cliente_id}', '{$funcionario_agd}', '{$servico_id}', '{$hora}', '{$data_agd}', '{$obs}')">
						<i class="fa fa-edit"></i> Editar
					</a>
					<a href="#" onclick="confirmarExclusao('{$id}')">
						<i class="fa fa-trash"></i> Excluir
					</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="agendamento-card-body">
		<div class="agendamento-cliente">
			<i class="fa fa-user"></i>
			<span>{$nome_cliente}</span>
		</div>
		
		<div class="agendamento-profissional">
		Serviço: <strong>{$nome_serv}</strong>
		</div>
		
		<div class="agendamento-profissional">
			Profissional: <strong>{$nome_funcionario}</strong>
		</div>
	</div>
</div>
HTML;

}

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-calendar-times-o" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum agendamento para esta data</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Selecione outra data ou crie um novo agendamento</p>
	</div>';
}

?>

<script>
function toggleAgendamentoMenu(id) {
	event.stopPropagation();
	const menu = document.getElementById('menu-' + id);
	
	// Fechar outros menus
	document.querySelectorAll('.agendamento-menu-dropdown').forEach(m => {
		if(m.id !== 'menu-' + id) {
			m.classList.remove('show');
		}
	});
	
	menu.classList.toggle('show');
}

// Fechar menu ao clicar fora
document.addEventListener('click', function(event) {
	if(!event.target.closest('.agendamento-menu')) {
		document.querySelectorAll('.agendamento-menu-dropdown').forEach(menu => {
			menu.classList.remove('show');
		});
	}
});

function confirmarExclusao(id) {
	// Fechar menu
	document.querySelectorAll('.agendamento-menu-dropdown').forEach(menu => {
		menu.classList.remove('show');
	});
	
	// Mostrar confirmação elegante
	const mensagem = `
		<div style="
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background: #fff;
			padding: 24px;
			border-radius: 12px;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
			z-index: 10000;
			min-width: 300px;
			text-align: center;
		">
			<i class="fa fa-exclamation-triangle" style="font-size: 48px; color: #ff9800; margin-bottom: 16px;"></i>
			<h4 style="margin-bottom: 12px; color: #1a1a1a;">Confirmar Exclusão</h4>
			<p style="color: #6c757d; margin-bottom: 20px;">Tem certeza que deseja excluir este agendamento?</p>
			<div style="display: flex; gap: 12px; justify-content: center;">
				<button onclick="this.closest('div').remove(); document.getElementById('overlay-confirm').remove();" style="
					padding: 10px 24px;
					border: 1px solid #e0e0e0;
					border-radius: 8px;
					background: #fff;
					color: #6c757d;
					font-weight: 600;
					cursor: pointer;
				">Cancelar</button>
				<button onclick="excluir(${id}); this.closest('div').remove(); document.getElementById('overlay-confirm').remove();" style="
					padding: 10px 24px;
					border: none;
					border-radius: 8px;
					background: #ef5350;
					color: #fff;
					font-weight: 600;
					cursor: pointer;
				">Sim, Excluir</button>
			</div>
		</div>
		<div id="overlay-confirm" onclick="this.remove(); this.previousElementSibling.remove();" style="
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0, 0, 0, 0.5);
			z-index: 9999;
		"></div>
	`;
	
	document.body.insertAdjacentHTML('beforeend', mensagem);
}

function mostrarServico(id, servico) {
	// Fechar menu
	document.querySelectorAll('.agendamento-menu-dropdown').forEach(menu => {
		menu.classList.remove('show');
	});
	
	$('#id_agd').val(id);
	$('#titulo_servico').text(servico);
	$('#modalServico').modal('show');
}

function editar(id, cliente, funcionario, servico, hora, data, obs) {
	// Fechar menu
	document.querySelectorAll('.agendamento-menu-dropdown').forEach(menu => {
		menu.classList.remove('show');
	});
	
	$('#id').val(id);
	$('#obs').val(obs);
	$('#data-modal').val(data);
	
	// Atualizar cliente customizado
	const clienteOption = document.querySelector('.cliente-option[data-id="' + cliente + '"]');
	if(clienteOption) {
		selectCliente(clienteOption);
	}
	
	// Atualizar funcionário customizado
	const profissionalOption = document.querySelector('.profissional-option[data-id="' + funcionario + '"]');
	if(profissionalOption) {
		selectProfissional(profissionalOption);
	}
	
	// Carregar serviços do funcionário
	listarServicos(funcionario);
	
	// Aguardar um pouco para os serviços carregarem
	setTimeout(function(){
		const servicoOption = document.querySelector('.servico-option[data-id="' + servico + '"]');
		if(servicoOption) {
			selectServico(servicoOption);
		}
	}, 500);
	
	$('#titulo_inserir').text('Editar Agendamento');
	$('#modalForm').modal('show');
	
	listarHorarios(funcionario, data, hora);
}
</script>
