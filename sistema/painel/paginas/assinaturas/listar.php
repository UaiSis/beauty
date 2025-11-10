<?php 
require_once("../../../conexao.php");
$tabela = 'grupo_assinaturas';

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

$query = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Grupo de Assinatura</th>	
	<th>Itens</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$ativo = $res[$i]['ativo'];
	$icone = $res[$i]['icone'];

	// Se não tem ícone definido, usar padrão
	if(empty($icone)){
		$icone = 'fa-certificate';
	}
	
	if($ativo == 'Sim'){
			$icone_status = 'fa-check-square';
			$titulo_link = 'Desativar Item';
			$acao = 'Não';
			$classe_linha = '';
			$action_class = 'active';
		}else{
			$icone_status = 'fa-square-o';
			$titulo_link = 'Ativar Item';
			$acao = 'Sim';
			$classe_linha = 'text-muted';
			$action_class = 'inactive';
		}


	$query2 = $pdo->query("SELECT * FROM itens_assinaturas where grupo = '$id'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_itens = @count($res2);
		


echo <<<HTML
<tr class="{$classe_linha}">
<td>
	<div class="grupo-info-cell">
		<div class="grupo-icon">
			<i class="fa {$icone}"></i>
		</div>
		<div class="grupo-name">{$nome}</div>
	</div>
</td>
<td>
	<span class="count-badge">{$total_itens}</span>
</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$icone}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="itens('{$id}', '{$nome}')" class="table-action-icon items" title="Gerenciar Itens">
			<i class="fa fa-list"></i>
		</a>

		<a href="#" onclick="ativar('{$id}', '{$acao}')" class="table-action-icon {$action_class}" title="{$titulo_link}">
			<i class="fa {$icone_status}"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoGrupo('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca'");
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
			<a onclick="listarAssinaturas(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarAssinaturas({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarAssinaturas({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
		<i class="fa fa-certificate"></i>
		<p>Nenhum grupo de assinatura cadastrado</p>
		<small>Adicione o primeiro grupo clicando no botão "Novo Grupo"</small>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$nome = $res_mobile[$i]['nome'];
				$ativo = $res_mobile[$i]['ativo'];
				$icone = $res_mobile[$i]['icone'];
				
				// Se não tem ícone definido, usar padrão
				if(empty($icone)){
					$icone = 'fa-certificate';
				}
				
				// Contar itens
				$query_itens = $pdo->query("SELECT * FROM itens_assinaturas where grupo = '$id'");
				$res_itens = $query_itens->fetchAll(PDO::FETCH_ASSOC);
				$total_itens = @count($res_itens);
				
				echo "mobileHtml += `";
				echo "<div class='assinatura-card-mobile'>";
				echo "<div class='assinatura-card-header'>";
				echo "<div class='assinatura-card-icon'><i class='fa {$icone}'></i></div>";
				echo "<div class='assinatura-card-info'>";
				echo "<div class='assinatura-card-name'>{$nome}</div>";
				echo "<div class='assinatura-card-count'>{$total_itens} itens</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='assinatura-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$icone}')\" class='assinatura-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"itens('{$id}', '{$nome}')\" class='assinatura-card-action-btn items'>";
				echo "<i class='fa fa-list'></i> Itens";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoGrupo('{$id}')\" class='assinatura-card-action-btn delete'>";
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
	function confirmarExclusaoGrupo(id){
		$('#mensagem-excluir').text('Confirmar Exclusão?');
		$('#mensagem-excluir').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-excluir').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja excluir este grupo?</span>
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
	function editar(id, nome, icone){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#icone').val(icone);
		
		// Resetar e ativar ícone selecionado
		$('.icon-selector-item').removeClass('active');
		$('.icon-selector-item[data-icon="' + icone + '"]').addClass('active');
		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
	}

	function itens(id, nome){
		$('#id_itens').val(id);
		$('#nome_itens').text(nome);			
		
		$('#modalItens').modal('show');

		setTimeout(function() {
		  listarItens();
		}, 500)
		
		limparCamposItens();
	}
</script>
