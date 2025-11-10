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

$query = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or descricao LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
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

	// Verificar se estoque está baixo
	$low_stock_class = '';
	$stock_badge_class = 'normal';
	if($nivel_estoque >= $estoque){
		$low_stock_class = 'low-stock';
		$stock_badge_class = 'low';
	}

echo <<<HTML
<tr class="{$low_stock_class}">
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
		<i class="fa fa-cube"></i>
		{$estoque}
	</span>
</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$categoria}', '{$descricao}', '{$valor_compra}', '{$valor_venda}', '{$foto}', '{$nivel_estoque}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="mostrar('{$nome}', '{$nome_cat}', '{$descricao}', '{$valor_compraF}',  '{$valor_vendaF}', '{$estoque}', '{$foto}', '{$nivel_estoque}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="#" onclick="entrada('{$id}','{$nome}', '{$estoque}')" class="table-action-icon entrada" title="Entrada de Produto">
			<i class="fa fa-sign-in"></i>
		</a>

		<a href="#" onclick="saida('{$id}','{$nome}', '{$estoque}')" class="table-action-icon saida" title="Saída de Produto">
			<i class="fa fa-sign-out"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoProduto('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or descricao LIKE '$busca'");
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
			<a onclick="listarProdutos(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarProdutos({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarProdutos({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
		<i class="fa fa-cube" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum produto cadastrado</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Adicione o primeiro produto ao estoque</p>
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

				$low_stock_class = '';
				if($nivel_estoque >= $estoque){
					$low_stock_class = 'low-stock';
				}
				
				echo "mobileHtml += `";
				echo "<div class='product-card-mobile {$low_stock_class}'>";
				echo "<div class='product-card-header'>";
				echo "<img src='img/produtos/{$foto}' class='product-card-image' alt='{$nome}'>";
				echo "<div class='product-card-info'>";
				echo "<div class='product-card-name'>{$nome}</div>";
				echo "<div class='product-card-category'>{$nome_cat}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='product-card-details'>";
				echo "<div class='product-card-detail-item'>";
				echo "<div class='product-card-detail-label'>Venda</div>";
				echo "<div class='product-card-detail-value'>R$ {$valor_vendaF}</div>";
				echo "</div>";
				echo "<div class='product-card-detail-item'>";
				echo "<div class='product-card-detail-label'>Compra</div>";
				echo "<div class='product-card-detail-value'>R$ {$valor_compraF}</div>";
				echo "</div>";
				echo "<div class='product-card-detail-item'>";
				echo "<div class='product-card-detail-label'>Estoque</div>";
				echo "<div class='product-card-detail-value'>{$estoque}</div>";
				echo "</div>";
				echo "<div class='product-card-detail-item'>";
				echo "<div class='product-card-detail-label'>Alerta</div>";
				echo "<div class='product-card-detail-value'>{$nivel_estoque}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='product-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$categoria}', '{$descricao}', '{$valor_compra}', '{$valor_venda}', '{$foto}', '{$nivel_estoque}')\" class='product-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"mostrar('{$nome}', '{$nome_cat}', '{$descricao}', '{$valor_compraF}', '{$valor_vendaF}', '{$estoque}', '{$foto}', '{$nivel_estoque}')\" class='product-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				echo "</div>";
				
				echo "<div style='margin-top: 8px; display: flex; gap: 8px;'>";
				echo "<a href='#' onclick=\"entrada('{$id}','{$nome}', '{$estoque}')\" class='product-card-action-btn entrada' style='flex: 1;'>";
				echo "<i class='fa fa-sign-in'></i> Entrada";
				echo "</a>";
				echo "<a href='#' onclick=\"saida('{$id}','{$nome}', '{$estoque}')\" class='product-card-action-btn saida' style='flex: 1;'>";
				echo "<i class='fa fa-sign-out'></i> Saída";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoProduto('{$id}')\" class='product-card-action-btn delete' style='flex: 0;'>";
				echo "<i class='fa fa-trash'></i>";
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
function confirmarExclusaoProduto(id) {
	$('#mensagem-excluir').text('Confirmar Exclusão?');
	$('#mensagem-excluir').css({
		'background': 'rgba(239, 83, 80, 0.1)',
		'color': '#ef5350',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Tem certeza que deseja excluir este produto?</span>
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
	function editar(id, nome, categoria, descricao, valor_compra, valor_venda, foto, nivel_estoque){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor_venda').val(valor_venda);
		$('#valor_compra').val(valor_compra);
		$('#categoria').val(categoria).change();
		$('#descricao').val(descricao);
		$('#nivel_estoque').val(nivel_estoque);
						
		$('#titulo_inserir').text('Editar Produto');
		$('#btn-text').text('Salvar Alterações');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src','img/produtos/' + foto);
	}
</script>

<script type="text/javascript">
	function mostrar(nome, categoria, descricao, valor_compra, valor_venda, estoque, foto, nivel_estoque){

		$('#nome_dados').text(nome);
		$('#valor_compra_dados').text(valor_compra);
		$('#categoria_dados').text(categoria);
		$('#valor_venda_dados').text(valor_venda);
		$('#descricao_dados').text(descricao);
		$('#estoque_dados').text(estoque);
		$('#nivel_estoque_dados').text(nivel_estoque);
		
		$('#target_mostrar').attr('src','img/produtos/' + foto);

		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	function saida(id, nome, estoque){

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);		

		$('#modalSaida').modal('show');
	}
</script>

<script type="text/javascript">
	function entrada(id, nome, estoque){

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);		

		$('#modalEntrada').modal('show');
	}
</script>
