<?php 
require_once("../../../conexao.php");

$data = @$_POST['data'];
$usuario_id = @$_POST['usuario_id'];
$busca = '%'.@$_POST['busca'].'%';

if(@$_POST['pagina'] == ""){
    @$_POST['pagina'] = 0;
}

$itens_por_pagina = @$_POST['itens_por_pagina'];
if($itens_por_pagina == "" || $itens_por_pagina == 0){
	$itens_por_pagina = 10;
}

$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_por_pagina;

$where = "WHERE p.data = '$data'";

if($usuario_id != ""){
	$where .= " AND p.usuario_id = '$usuario_id'";
}

if($busca != "%%"){
	$where .= " AND (u.nome LIKE '$busca' OR u.nivel LIKE '$busca')";
}

$query_total = $pdo->query("SELECT COUNT(*) as total FROM pontos p
	LEFT JOIN usuarios u ON p.usuario_id = u.id
	$where");
$res_total = $query_total->fetch(PDO::FETCH_ASSOC);
$total_registros = $res_total['total'];

$sql_query = "SELECT p.*, u.nome as usuario_nome, u.foto, u.nivel as cargo_nome 
	FROM pontos p
	LEFT JOIN usuarios u ON p.usuario_id = u.id
	$where
	ORDER BY p.id DESC 
	LIMIT $limite, $itens_por_pagina";

$query = $pdo->query($sql_query);
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Funcionário</th>
	<th>Entrada</th>
	<th>Saída Almoço</th>
	<th>Retorno</th>
	<th>Saída</th>
	<th>Horas</th>
	<th>Status</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	$id = $res[$i]['id'];
	$usuario_id = $res[$i]['usuario_id'];
	$usuario_nome = $res[$i]['usuario_nome'];
	$foto = $res[$i]['foto'];
	$cargo_nome = $res[$i]['cargo_nome'];
	$entrada = $res[$i]['entrada'];
	$saida_almoco = $res[$i]['saida_almoco'];
	$retorno_almoco = $res[$i]['retorno_almoco'];
	$saida = $res[$i]['saida'];
	$horas_trabalhadas = $res[$i]['horas_trabalhadas'];
	$horas_extras = $res[$i]['horas_extras'];
	$status = $res[$i]['status'];

	$entradaF = $entrada ? date("H:i", strtotime($entrada)) : '-';
	$saida_almocoF = $saida_almoco ? date("H:i", strtotime($saida_almoco)) : '-';
	$retorno_almocoF = $retorno_almoco ? date("H:i", strtotime($retorno_almoco)) : '-';
	$saidaF = $saida ? date("H:i", strtotime($saida)) : '-';
	
	$entradaInput = $entrada ? substr($entrada, 0, 5) : '';
	$saida_almocoInput = $saida_almoco ? substr($saida_almoco, 0, 5) : '';
	$retorno_almocoInput = $retorno_almoco ? substr($retorno_almoco, 0, 5) : '';
	$saidaInput = $saida ? substr($saida, 0, 5) : '';
	
	$horas_trabalhadasF = number_format($horas_trabalhadas, 2);
	$horas_extrasF = number_format($horas_extras, 2);

	$tipo_registro = $res[$i]['tipo_registro'];
	
	if($tipo_registro == 'atestado'){
		$badge_class = 'atestado';
		$badge_text = 'Atestado';
		$badge_icon = 'fa-file-text-o';
	} elseif($tipo_registro == 'folga'){
		$badge_class = 'folga';
		$badge_text = 'Folga';
		$badge_icon = 'fa-calendar-check-o';
	} elseif($status == 'aberto'){
		$badge_class = 'presente';
		$badge_text = 'Trabalhando';
		$badge_icon = 'fa-check';
	} elseif($status == 'almoco'){
		$badge_class = 'almoco';
		$badge_text = 'Almoço';
		$badge_icon = 'fa-utensils';
	} elseif($status == 'encerrado'){
		$badge_class = 'encerrado';
		$badge_text = 'Encerrado';
		$badge_icon = 'fa-check-circle';
	} else {
		$badge_class = 'ausente';
		$badge_text = 'Ausente';
		$badge_icon = 'fa-times';
	}

	$iniciais = '';
	$palavras = explode(' ', $usuario_nome);
	if(count($palavras) >= 2){
		$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
	}else{
		$iniciais = strtoupper(substr($usuario_nome, 0, 2));
	}

	$cores_avatar = ['#007A63', '#42a5f5', '#9c27b0', '#ff9800', '#ef5350', '#00d896'];
	$cor_index = $usuario_id % count($cores_avatar);
	$cor_avatar = $cores_avatar[$cor_index];

	$tem_foto = ($foto != 'sem-foto.jpg' && !empty($foto));

echo <<<HTML
<tr>
<td>
	<div class="user-info-cell">
		<div class="user-avatar" style="background: {$cor_avatar};">
HTML;

if($tem_foto){
	echo '<img src="img/perfil/'.$foto.'" alt="'.$usuario_nome.'">';
} else {
	echo $iniciais;
}

$cargo_display = $cargo_nome ? $cargo_nome : 'Sem cargo';

echo <<<HTML
		</div>
		<div>
			<div class="user-name">{$usuario_nome}</div>
			<div class="user-email">{$cargo_display}</div>
		</div>
	</div>
</td>
<td><span class="time-display">{$entradaF}</span></td>
<td><span class="time-display">{$saida_almocoF}</span></td>
<td><span class="time-display">{$retorno_almocoF}</span></td>
<td><span class="time-display">{$saidaF}</span></td>
<td>
	<div style="display: flex; flex-direction: column; gap: 4px;">
		<span class="time-display">{$horas_trabalhadasF}h</span>
HTML;

if($horas_extras > 0){
	echo '<small style="color: #ff9800; font-weight: 600;">+'.$horas_extrasF.'h extras</small>';
}

echo <<<HTML
	</div>
</td>
<td>
	<span class="badge-status {$badge_class}">
		<span class="badge-status-dot"></span>
		{$badge_text}
	</span>
</td>
<td>
	<div class="actions-cell">
		<button class="btn-action view" onclick="verDetalhes({$id})" title="Ver Detalhes">
			<i class="fa fa-eye"></i>
		</button>
		<button class="btn-action edit" onclick="abrirAjustar({$id}, '{$entradaInput}', '{$saida_almocoInput}', '{$retorno_almocoInput}', '{$saidaInput}')" title="Ajustar">
			<i class="fa fa-edit"></i>
		</button>
		<button class="btn-action delete" onclick="excluirPonto({$id})" title="Excluir">
			<i class="fa fa-trash"></i>
		</button>
	</div>
</td>
</tr>
HTML;
}

echo <<<HTML
	</tbody>
	</table>
HTML;

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-inbox" style="font-size: 64px; color: #e0e0e0; margin-bottom: 16px;"></i>
		<h4 style="color: #6c757d; font-weight: 600;">Nenhum registro encontrado</h4>
		<p style="color: #6c757d; font-size: 14px;">Não há pontos registrados para os filtros selecionados.</p>
	</div>';
}

?>

