<?php 
require_once("../../../conexao.php");
$tabela = 'cat_produtos';

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
	<th>Categoria</th>	
	<th>Produtos</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];

	// Contar produtos nesta categoria
	$query2 = $pdo->query("SELECT * FROM produtos where categoria = '$id'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_produtos = @count($res2);

echo <<<HTML
<tr>
<td>
	<div class="categoria-name">
		<div class="categoria-icon">
			<i class="fa fa-tag"></i>
		</div>
		{$nome}
	</div>
</td>
<td>
	<span class="produtos-count-badge">
		<i class="fa fa-cube"></i>
		{$total_produtos}
	</span>
</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoCategoria('{$id}')" class="table-action-icon delete" title="Excluir">
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
			<a onclick="listarCategorias(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarCategorias({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarCategorias({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-tags" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhuma categoria cadastrada</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Crie a primeira categoria para organizar seus produtos</p>
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
		$query_mobile = $pdo->query("SELECT * FROM cat_produtos where nome LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		if($total_reg > 0){
			for($i=0; $i < $total_reg; $i++){
				$id = $res[$i]['id'];
				$nome = $res[$i]['nome'];

				// Contar produtos
				$query2 = $pdo->query("SELECT * FROM produtos where categoria = '$id'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				$total_produtos = @count($res2);
				
				echo "mobileHtml += `";
				echo "<div class='categoria-card-mobile'>";
				echo "<div class='categoria-card-header'>";
				echo "<div class='categoria-card-icon'>";
				echo "<i class='fa fa-tag'></i>";
				echo "</div>";
				echo "<div class='categoria-card-info'>";
				echo "<div class='categoria-card-name'>{$nome}</div>";
				echo "<div class='categoria-card-count'>{$total_produtos} produto(s)</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='categoria-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}')\" class='categoria-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoCategoria('{$id}')\" class='categoria-card-action-btn delete'>";
				echo "<i class='fa fa-trash'></i> Excluir";
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
function confirmarExclusaoCategoria(id) {
	$('#mensagem-excluir').text('Confirmar Exclusão?');
	$('#mensagem-excluir').css({
		'background': 'rgba(239, 83, 80, 0.1)',
		'color': '#ef5350',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Tem certeza que deseja excluir esta categoria?</span>
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
	function editar(id, nome){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#titulo_inserir').text('Editar Categoria');
		$('#btn-text').text('Salvar Alterações');
		$('#modalForm').modal('show');
	}
</script>
