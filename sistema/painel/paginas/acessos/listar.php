<?php 
require_once("../../../conexao.php");
$tabela = 'acessos';

$busca = '%'.@$_POST['busca'].'%';

// pegar a pagina atual
if(@$_POST['pagina'] == ""){
    @$_POST['pagina'] = 0;
}

// pegar itens por página
$itens_por_pagina = @$_POST['itens_por_pagina'];
if($itens_por_pagina == ""){
	$itens_por_pagina = $itens_pag;
}

$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_por_pagina;

$query = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or chave LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){	

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Permissão</th>	
	<th>Chave</th> 	
	<th>Grupo</th> 		
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$chave = $res[$i]['chave'];
	$grupo = $res[$i]['grupo'];
	$icone = $res[$i]['icone'];

	// Se não tem ícone definido, usar padrão
	if(empty($icone)){
		$icone = 'fa-home';
	}
	
	$query2 = $pdo->query("SELECT * FROM grupo_acessos where id = '$grupo'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_grupo = $res2[0]['nome'];
	}else{
		$nome_grupo = 'Nenhum';
	}
	


echo <<<HTML
<tr>
<td>
	<div class="acesso-info-cell">
		<div class="acesso-icon">
			<i class="fa {$icone}"></i>
		</div>
		<div>
			<div class="acesso-name">{$nome}</div>
			<div class="acesso-chave">{$chave}</div>
		</div>
	</div>
</td>
<td><code style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{$chave}</code></td>
<td><span class="grupo-badge">{$nome_grupo}</span></td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$chave}', '{$grupo}', '{$icone}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoAcesso('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or chave LIKE '$busca'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

$num_paginas = ceil($total_reg2/$itens_por_pagina);

echo <<<HTML
</tbody>
</table>
<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarAcessos(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarAcessos({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarAcessos({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
				<i class="fa fa-angle-double-right"></i>
			</a>
		</li>
	</ul>
</div>
HTML;
}

echo <<<HTML

HTML;  


}else{
	echo '<div class="empty-state">
		<i class="fa fa-unlock-alt"></i>
		<p>Nenhum acesso cadastrado</p>
		<small>Adicione o primeiro acesso clicando no botão "Novo Acesso"</small>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or chave LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$nome = $res_mobile[$i]['nome'];
				$chave = $res_mobile[$i]['chave'];
				$grupo = $res_mobile[$i]['grupo'];
				$icone = $res_mobile[$i]['icone'];
				
				// Se não tem ícone definido, usar padrão
				if(empty($icone)){
					$icone = 'fa-home';
				}
				
				// Buscar nome do grupo
				$query_grupo = $pdo->query("SELECT * FROM grupo_acessos where id = '$grupo'");
				$res_grupo = $query_grupo->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res_grupo) > 0){
					$nome_grupo = $res_grupo[0]['nome'];
				}else{
					$nome_grupo = 'Nenhum';
				}
				
				echo "mobileHtml += `";
				echo "<div class='acesso-card-mobile'>";
				echo "<div class='acesso-card-header'>";
				echo "<div class='acesso-card-icon'><i class='fa {$icone}'></i></div>";
				echo "<div class='acesso-card-info'>";
				echo "<div class='acesso-card-name'>{$nome}</div>";
				echo "<div class='acesso-card-chave'>{$chave}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='acesso-card-details'>";
				echo "<div class='acesso-card-detail-item'>";
				echo "<div class='acesso-card-detail-label'>Grupo</div>";
				echo "<div class='acesso-card-detail-value'>{$nome_grupo}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='acesso-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$chave}', '{$grupo}', '{$icone}')\" class='acesso-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoAcesso('{$id}')\" class='acesso-card-action-btn delete'>";
				echo "<i class='fa fa-trash'></i> Excluir";
				echo "</a>";
				echo "</div>";
				
				echo "</div>";
				echo "`;";
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
	function confirmarExclusaoAcesso(id){
		$('#mensagem-excluir').text('Confirmar Exclusão?');
		$('#mensagem-excluir').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-excluir').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja excluir este acesso?</span>
				<button onclick="excluir(${id})" style="
					background: #ef5350;
					color: white;
					border: none;
					padding: 6px 16px;
					border-radius: 6px;
					cursor: pointer;
					font-weight: 600;
					margin-right: 8px;
				">Sim, Excluir</button>
				<button onclick="$('#mensagem-excluir').hide()" style="
					background: #6c757d;
					color: white;
					border: none;
					padding: 6px 16px;
					border-radius: 6px;
					cursor: pointer;
					font-weight: 600;
				">Cancelar</button>
			`);
		}, 300);
	}
</script>

<script type="text/javascript">
	function editar(id, nome, chave, grupo, icone){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#chave').val(chave);
		$('#grupo').val(grupo).change();
		$('#icone').val(icone);
		
		// Resetar e ativar ícone selecionado
		$('.icon-selector-item').removeClass('active');
		$('.icon-selector-item[data-icon="' + icone + '"]').addClass('active');
		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
	}
</script>

