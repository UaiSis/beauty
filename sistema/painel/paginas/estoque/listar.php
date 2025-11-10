<?php 
require_once("../../../conexao.php");
$tabela = 'produtos';

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

// Buscar produtos com estoque baixo
$query = $pdo->query("SELECT * FROM $tabela where (nome LIKE '$busca' or descricao LIKE '$busca') ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// Contar estatísticas gerais
$query_stats = $pdo->query("SELECT * FROM $tabela");
$res_stats = $query_stats->fetchAll(PDO::FETCH_ASSOC);
$total_produtos_baixo = 0;
$produtos_criticos = 0;

foreach($res_stats as $produto_stat){
	if($produto_stat['nivel_estoque'] >= $produto_stat['estoque']){
		$total_produtos_baixo++;
		if($produto_stat['estoque'] == 0){
			$produtos_criticos++;
		}
	}
}

// Renderizar cards de estatísticas
echo "<script>
document.getElementById('stats-container').innerHTML = `
	<div class='stat-card alert'>
		<div class='stat-icon alert'>
			<i class='fa fa-exclamation-triangle'></i>
		</div>
		<div class='stat-content'>
			<div class='stat-label'>Produtos com Estoque Baixo</div>
			<div class='stat-value'>{$total_produtos_baixo}</div>
		</div>
	</div>
	<div class='stat-card alert'>
		<div class='stat-icon alert'>
			<i class='fa fa-times-circle'></i>
		</div>
		<div class='stat-content'>
			<div class='stat-label'>Produtos Esgotados</div>
			<div class='stat-value' style='color: #ef5350;'>{$produtos_criticos}</div>
		</div>
	</div>
`;
</script>";

$produtos_exibidos = 0;

if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Produto</th>	
	<th>Categoria</th> 	
	<th>Valor Compra</th> 	
	<th>Valor Venda</th> 
	<th>Estoque</th>
	<th>Nível Mínimo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$descricao = $res[$i]['descricao'];
	$categoria = $res[$i]['categoria'];
	$valor_compra = $res[$i]['valor_compra'];
	$valor_venda = $res[$i]['valor_venda'];
	$foto = $res[$i]['foto'];
	$estoque = $res[$i]['estoque'];
	$nivel_estoque = $res[$i]['nivel_estoque'];

	$valor_vendaF = number_format($valor_venda, 2, ',', '.');
	$valor_compraF = number_format($valor_compra, 2, ',', '.');

	$query2 = $pdo->query("SELECT * FROM cat_produtos where id = '$categoria'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_cat = $res2[0]['nome'];
	}else{
		$nome_cat = 'Sem Categoria';
	}

	// Verificar se estoque está baixo - só exibir produtos com estoque baixo
	if($nivel_estoque >= $estoque){
		$produtos_exibidos++;
		
		$critical_class = '';
		$stock_badge_class = 'warning';
		
		if($estoque == 0){
			$critical_class = 'critical';
			$stock_badge_class = 'critical';
		}

echo <<<HTML
<tr class="{$critical_class}">
<td>
	<div class="product-info-cell">
		<img src="img/produtos/{$foto}" class="product-image" alt="{$nome}">
		<span class="product-name">{$nome}</span>
	</div>
</td>
<td><span class="categoria-badge">{$nome_cat}</span></td>
<td>R$ {$valor_compraF}</td>
<td style="font-weight: 600; color: #00a574;">R$ {$valor_vendaF}</td>
<td>
	<span class="stock-badge {$stock_badge_class}">
		<i class="fa fa-exclamation-circle"></i>
		{$estoque}
	</span>
</td>
<td>
	<div class="alert-level-indicator">
		<i class="fa fa-arrow-down" style="color: #ef5350;"></i>
		{$nivel_estoque}
	</div>
</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="mostrar('{$nome}', '{$nome_cat}', '{$descricao}', '{$valor_compraF}',  '{$valor_vendaF}', '{$estoque}', '{$foto}', '{$nivel_estoque}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>
	</div>
</td>
</tr>
HTML;

	}

}

echo <<<HTML
</tbody>
</table>
HTML;

if($produtos_exibidos == 0){
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-check-circle" style="font-size: 48px; color: #00d896; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #00a574; font-weight: 500; margin: 0;">Nenhum produto com estoque baixo!</p>
		<p style="font-size: 13px; color: #6c757d; margin-top: 8px;">Todos os produtos estão com níveis adequados de estoque</p>
	</div>';
}else{
	// Calcular paginação baseado apenas em produtos com estoque baixo
	$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or descricao LIKE '$busca'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	
	$total_baixo_estoque = 0;
	foreach($res2 as $prod){
		if($prod['nivel_estoque'] >= $prod['estoque']){
			$total_baixo_estoque++;
		}
	}

	$num_paginas = ceil($total_baixo_estoque/$itens_por_pagina);

	// Renderizar paginação se houver mais de uma página
	if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarEstoque(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarEstoque({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
			} 
		} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarEstoque({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
				<i class="fa fa-angle-double-right"></i>
			</a>
		</li>
	</ul>
</div>
HTML;
	}
}

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-check-circle" style="font-size: 48px; color: #00d896; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #00a574; font-weight: 500; margin: 0;">Nenhum produto encontrado!</p>
		<p style="font-size: 13px; color: #6c757d; margin-top: 8px;">Todos os produtos estão com níveis adequados de estoque</p>
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
		$query_mobile = $pdo->query("SELECT * FROM produtos where nome LIKE '$busca' or descricao LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		if($total_reg > 0){
			for($i=0; $i < $total_reg; $i++){
				$id = $res[$i]['id'];
				$nome = $res[$i]['nome'];
				$descricao = $res[$i]['descricao'];
				$categoria = $res[$i]['categoria'];
				$valor_compra = $res[$i]['valor_compra'];
				$valor_venda = $res[$i]['valor_venda'];
				$foto = $res[$i]['foto'];
				$estoque = $res[$i]['estoque'];
				$nivel_estoque = $res[$i]['nivel_estoque'];

				// Só exibir produtos com estoque baixo
				if($nivel_estoque >= $estoque){

					$valor_vendaF = number_format($valor_venda, 2, ',', '.');
					$valor_compraF = number_format($valor_compra, 2, ',', '.');

					$query2 = $pdo->query("SELECT * FROM cat_produtos where id = '$categoria'");
					$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					$total_reg2 = @count($res2);
					if($total_reg2 > 0){
						$nome_cat = $res2[0]['nome'];
					}else{
						$nome_cat = 'Sem Categoria';
					}

					$alert_text = ($estoque == 0) ? "ESGOTADO" : "BAIXO";
					
					echo "mobileHtml += `";
					echo "<div class='estoque-card-mobile'>";
					echo "<div class='alert-badge-mobile'>{$alert_text}</div>";
					echo "<div class='estoque-card-header'>";
					echo "<img src='img/produtos/{$foto}' class='estoque-card-image' alt='{$nome}'>";
					echo "<div class='estoque-card-info'>";
					echo "<div class='estoque-card-name'>{$nome}</div>";
					echo "<div class='estoque-card-category'>{$nome_cat}</div>";
					echo "</div>";
					echo "</div>";
					
					echo "<div class='estoque-card-details'>";
					echo "<div class='estoque-card-detail-item'>";
					echo "<div class='estoque-card-detail-label'>Venda</div>";
					echo "<div class='estoque-card-detail-value'>R$ {$valor_vendaF}</div>";
					echo "</div>";
					echo "<div class='estoque-card-detail-item'>";
					echo "<div class='estoque-card-detail-label'>Compra</div>";
					echo "<div class='estoque-card-detail-value'>R$ {$valor_compraF}</div>";
					echo "</div>";
					echo "<div class='estoque-card-detail-item'>";
					echo "<div class='estoque-card-detail-label'>Estoque Atual</div>";
					echo "<div class='estoque-card-detail-value alert'>{$estoque}</div>";
					echo "</div>";
					echo "<div class='estoque-card-detail-item'>";
					echo "<div class='estoque-card-detail-label'>Nível Mínimo</div>";
					echo "<div class='estoque-card-detail-value'>{$nivel_estoque}</div>";
					echo "</div>";
					echo "</div>";
					
					echo "<div class='estoque-card-actions'>";
					echo "<a href='#' onclick=\"mostrar('{$nome}', '{$nome_cat}', '{$descricao}', '{$valor_compraF}', '{$valor_vendaF}', '{$estoque}', '{$foto}', '{$nivel_estoque}')\" class='estoque-card-action-btn view'>";
					echo "<i class='fa fa-eye'></i> Ver Detalhes";
					echo "</a>";
					echo "</div>";
					echo "</div>";
					echo "`;\n";
				}
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
