<?php 
@session_start();
$id_cliente = $_SESSION['id'];
require_once("../../../conexao.php");
$tabela = 'agendamentos';

$data_atual = date('Y-m-d');

$busca = '%'.@$_POST['busca'].'%';

// pegar a pagina atual
if(@$_POST['pagina'] == ""){
    @$_POST['pagina'] = 0;
}

// pegar itens por página
$itens_por_pagina = @$_POST['itens_por_pagina'];
if($itens_por_pagina == ""){
	$itens_por_pagina = 10;
}

$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_por_pagina;

$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' and (data LIKE '$busca' or hora LIKE '$busca' or status LIKE '$busca') ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){	

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Serviço</th>	
	<th>Data</th> 	
	<th>Hora</th> 	
	<th>Profissional</th> 
	<th>Status</th> 		 	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
$id = $res[$i]['id'];
$funcionario = $res[$i]['funcionario'];
$cliente = $res[$i]['cliente'];
$hora = $res[$i]['hora'];
$data = $res[$i]['data'];
$usuario = $res[$i]['usuario'];
$data_lanc = $res[$i]['data_lanc'];
$obs = $res[$i]['obs'];
$status = $res[$i]['status'];
$servico = $res[$i]['servico'];
$ref_pix = $res[$i]['ref_pix'];
	
	
$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", strtotime($hora));


if($status == 'Concluído'){		
	$classe_linha = '';
}else{		
	$classe_linha = 'text-muted';
}



// Definir classe do badge de status
$status_lower = strtolower($status);
$badge_class = 'agendado';
$icon_class = 'agendado';

if($status == 'Concluído'){
	$badge_class = 'concluido';
	$icon_class = 'concluido';
	$classe_status = 'ocultar';
}else if($status == 'Cancelado'){
	$badge_class = 'cancelado';
	$icon_class = 'cancelado';
	$classe_status = 'ocultar';
}else{
	$classe_status = '';
}

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_usu = $res2[0]['nome'];
}else{
	$nome_usu = 'Sem Usuário';
}

$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_func = $res2[0]['nome'];
}else{
	$nome_func = 'Sem Usuário';
}


$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_serv = $res2[0]['nome'];
	$valor_serv = $res2[0]['valor'];
}else{
	$nome_serv = 'Não Lançado';
	$valor_serv = '';
}


$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_cliente = $res2[0]['nome'];
	$total_cartoes = $res2[0]['cartoes'];
}else{
	$nome_cliente = 'Sem Cliente';
	$total_cartoes = 0;
}

if($total_cartoes >= $quantidade_cartoes and $status == 'Agendado'){
	$ocultar_cartoes = '';
}else{
	$ocultar_cartoes = 'ocultar';
}

//retirar aspas do texto do obs
$obs = str_replace('"', "**", $obs);

echo <<<HTML
<tr>
<td>
	<div class="servico-info">
		<div class="servico-icon {$icon_class}"></div>
		<span>{$nome_serv}</span>
	</div>
</td>
<td>{$dataF}</td>
<td>{$horaF}</td>
<td>{$nome_func}</td>
<td><span class="status-badge {$badge_class}">{$status}</span></td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="confirmarExclusaoAgendamento('{$id}')" class="table-action-icon delete {$classe_status}" title="Cancelar Agendamento">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

echo <<<HTML
</tbody>
</table>
<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' and (data LIKE '$busca' or hora LIKE '$busca' or status LIKE '$busca')");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

$num_paginas = ceil($total_reg2/$itens_por_pagina);

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarAgendamentos(0)" class="paginador" href="#" aria-label="Previous">
				<i class="fa fa-angle-double-left"></i>
			</a>
		</li>
HTML;

	for($i=0;$i<$num_paginas;$i++){
		$estilo = "";
		if($pagina >= ($i - 2) and $pagina <= ($i + 2)){
			if($pagina == $i)
				$estilo = "active";

			$pag_num = $i+1;
			$ultimo_reg = $num_paginas - 1;

echo <<<HTML
		<li class="page-item {$estilo}">
			<a onclick="listarAgendamentos({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarAgendamentos({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
				<i class="fa fa-angle-double-right"></i>
			</a>
		</li>
	</ul>
</div>
HTML;
}

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-calendar" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum agendamento encontrado</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Você ainda não possui agendamentos</p>
	</div>';
}

?>

<script type="text/javascript">
	// Renderizar versão mobile
	function renderMobileCards() {
		var mobileListElement = document.getElementById('listar-mobile');
		if (!mobileListElement) return;
		
		var mobileHtml = '';
		<?php 
		$query_mobile = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' ORDER BY id desc");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$funcionario = $res_mobile[$i]['funcionario'];
				$cliente = $res_mobile[$i]['cliente'];
				$hora = $res_mobile[$i]['hora'];
				$data = $res_mobile[$i]['data'];
				$status = $res_mobile[$i]['status'];
				$servico = $res_mobile[$i]['servico'];
				
				$dataF = implode('/', array_reverse(explode('-', $data)));
				$horaF = date("H:i", strtotime($hora));
				
				$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_func = $res2[0]['nome'];
				}else{
					$nome_func = 'Sem Funcionário';
				}
				
				$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_serv = $res2[0]['nome'];
				}else{
					$nome_serv = 'Não Lançado';
				}
				
				$badge_class = 'agendado';
				$icon_class = 'agendado';
				if($status == 'Concluído'){
					$badge_class = 'concluido';
					$icon_class = 'concluido';
					$classe_status = 'ocultar';
				}else if($status == 'Cancelado'){
					$badge_class = 'cancelado';
					$icon_class = 'cancelado';
					$classe_status = 'ocultar';
				}else{
					$classe_status = '';
				}
				
				$hide_delete = ($classe_status == 'ocultar') ? 'style="display:none;"' : '';
				
				echo "mobileHtml += `";
				echo "<div class='agendamento-card-mobile'>";
				echo "<div class='agendamento-card-header'>";
				echo "<div class='agendamento-card-servico'>";
				echo "<div class='agendamento-card-servico-nome'>";
				echo "<div class='servico-icon {$icon_class}'></div>";
				echo "{$nome_serv}";
				echo "</div>";
				echo "<div class='agendamento-card-profissional'>";
				echo "<i class='fa fa-user' style='font-size: 10px;'></i> {$nome_func}";
				echo "</div>";
				echo "</div>";
				echo "<span class='status-badge {$badge_class}'>{$status}</span>";
				echo "</div>";
				
				echo "<div class='agendamento-card-details'>";
				echo "<div class='agendamento-card-detail-item'>";
				echo "<div class='agendamento-card-detail-label'>Data</div>";
				echo "<div class='agendamento-card-detail-value'>{$dataF}</div>";
				echo "</div>";
				echo "<div class='agendamento-card-detail-item'>";
				echo "<div class='agendamento-card-detail-label'>Horário</div>";
				echo "<div class='agendamento-card-detail-value'>{$horaF}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='agendamento-card-actions' {$hide_delete}>";
				echo "<a href='#' onclick=\"confirmarExclusaoAgendamento('{$id}')\" class='agendamento-card-action-btn delete'>";
				echo "<i class='fa fa-trash'></i> Cancelar Agendamento";
				echo "</a>";
				echo "</div>";
				
				echo "</div>";
				echo "`;\n";
			}
		}
		?>
		
		mobileListElement.innerHTML = mobileHtml;
	}
	
	// Executar ao carregar
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', renderMobileCards);
	} else {
		renderMobileCards();
	}
</script>

<script type="text/javascript">
function confirmarExclusaoAgendamento(id) {
	$('#mensagem-excluir').text('Confirmar Cancelamento?');
	$('#mensagem-excluir').css({
		'background': 'rgba(255, 152, 0, 0.1)',
		'color': '#f57c00',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Tem certeza que deseja cancelar este agendamento?</span>
			<button onclick="excluirAgd(${id})" style="
				background: #ff9800;
				color: white;
				border: none;
				padding: 6px 16px;
				border-radius: 6px;
				cursor: pointer;
				font-weight: 600;
				margin-right: 8px;
			">Sim, Cancelar</button>
			<button onclick="$('#mensagem-excluir').hide()" style="
				background: #6c757d;
				color: white;
				border: none;
				padding: 6px 16px;
				border-radius: 6px;
				cursor: pointer;
				font-weight: 600;
			">Não</button>
		`);
	}, 300);
}
</script>

<style>
	.ocultar {
		display: none !important;
	}
</style>

