<?php 
require_once("../../../conexao.php");

$busca = '%'.@$_POST['busca'].'%';

$query = $pdo->query("SELECT c.*, u.nome as usuario_nome, u.foto 
	FROM configuracoes_ponto c
	LEFT JOIN usuarios u ON c.usuario_id = u.id
	WHERE u.nome LIKE '$busca'
	ORDER BY u.nome");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Funcionário</th>
	<th>Horário</th>
	<th>Horas/Dia</th>
	<th>Tolerância</th>
	<th>Almoço</th>
	<th>Dias de Trabalho</th>
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
	$hora_entrada = $res[$i]['hora_entrada'];
	$hora_saida = $res[$i]['hora_saida'];
	$horas_diarias = $res[$i]['horas_diarias'];
	$tolerancia_minutos = $res[$i]['tolerancia_minutos'];
	$dias_trabalho = $res[$i]['dias_trabalho'];
	$almoco_obrigatorio = $res[$i]['almoco_obrigatorio'];
	$duracao_almoco = $res[$i]['duracao_almoco'];
	$ativo = $res[$i]['ativo'];

	$hora_entradaF = date("H:i", strtotime($hora_entrada));
	$hora_saidaF = date("H:i", strtotime($hora_saida));
	
	$dias_array = explode(',', $dias_trabalho);
	$dias_nomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
	$dias_texto = array();
	foreach($dias_array as $dia){
		$dias_texto[] = $dias_nomes[$dia];
	}
	$dias_trabalhoF = implode(', ', $dias_texto);

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

echo <<<HTML
		</div>
		<div>
			<div class="user-name">{$usuario_nome}</div>
		</div>
	</div>
</td>
<td><span class="time-display">{$hora_entradaF} - {$hora_saidaF}</span></td>
<td><strong>{$horas_diarias}h</strong></td>
<td>{$tolerancia_minutos} min</td>
<td>
HTML;

if($almoco_obrigatorio == 'Sim'){
	echo '<span class="badge-ativo">Sim ('.$duracao_almoco.' min)</span>';
} else {
	echo '<span class="badge-inativo">Não</span>';
}

echo <<<HTML
</td>
<td><small>{$dias_trabalhoF}</small></td>
<td>
HTML;

if($ativo == 'Sim'){
	echo '<span class="badge-ativo"><span class="badge-status-dot"></span>Ativo</span>';
} else {
	echo '<span class="badge-inativo"><span class="badge-status-dot"></span>Inativo</span>';
}

echo <<<HTML
</td>
<td>
	<div class="actions-cell">
		<button class="btn-action edit" onclick="editarConfig({$id})" title="Editar">
			<i class="fa fa-edit"></i>
		</button>
		<button class="btn-action delete" onclick="excluirConfig({$id})" title="Excluir">
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
		<h4 style="color: #6c757d; font-weight: 600;">Nenhuma configuração encontrada</h4>
		<p style="color: #6c757d; font-size: 14px;">Adicione configurações de horário para os funcionários.</p>
	</div>';
}
?>

