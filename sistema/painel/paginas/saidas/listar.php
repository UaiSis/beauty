<?php 
require_once("../../../conexao.php");
$tabela = 'saidas';

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

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Produto</th>	
	<th>Quantidade</th> 	
	<th>Motivo</th> 	
	<th>Usuário</th> 
	<th>Data</th>	
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$produto = $res[$i]['produto'];	
	$quantidade = $res[$i]['quantidade'];
	$motivo = $res[$i]['motivo'];
	$usuario = $res[$i]['usuario'];
	$data = $res[$i]['data'];

	$query2 = $pdo->query("SELECT * FROM produtos where id = '$produto'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_produto = $res2[0]['nome'];
		$foto_produto = $res2[0]['foto'];
	}else{
		$nome_produto = 'Sem Referência';
		$foto_produto = 'sem-foto.jpg';
	}

	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_usuario = $res2[0]['nome'];
	}else{
		$nome_usuario = 'Sem Referência';
	}

	$dataF = implode('/', array_reverse(@explode('-', $data)));

echo <<<HTML
<tr>
<td>
	<div class="product-info-cell">
		<img src="img/produtos/{$foto_produto}" class="product-image" alt="{$nome_produto}">
		<span class="product-name">{$nome_produto}</span>
	</div>
</td>
<td>
	<span class="quantidade-badge">
		<i class="fa fa-minus-circle"></i>
		{$quantidade}
	</span>
</td>
<td>
	<span class="motivo-text">{$motivo}</span>
</td>
<td>
	<span class="usuario-badge">
		<i class="fa fa-user"></i>
		{$nome_usuario}
	</span>
</td>
<td>{$dataF}</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

$num_paginas = ceil($total_reg2/$itens_por_pagina);

echo <<<HTML
</tbody>
</table>
HTML;

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarSaidas(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarSaidas({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarSaidas({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
		<i class="fa fa-sign-out" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhuma saída registrada</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">As saídas de estoque aparecerão aqui</p>
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
		$query_mobile = $pdo->query("SELECT * FROM saidas ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		if($total_reg > 0){
			for($i=0; $i < $total_reg; $i++){
				$id = $res[$i]['id'];
				$produto = $res[$i]['produto'];
				$quantidade = $res[$i]['quantidade'];
				$motivo = $res[$i]['motivo'];
				$usuario = $res[$i]['usuario'];
				$data = $res[$i]['data'];

				// Buscar nome do produto
				$query2 = $pdo->query("SELECT * FROM produtos where id = '$produto'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				$total_reg2 = @count($res2);
				if($total_reg2 > 0){
					$nome_produto = $res2[0]['nome'];
					$foto_produto = $res2[0]['foto'];
				}else{
					$nome_produto = 'Sem Referência';
					$foto_produto = 'sem-foto.jpg';
				}

				// Buscar nome do usuário
				$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				$total_reg2 = @count($res2);
				if($total_reg2 > 0){
					$nome_usuario = $res2[0]['nome'];
				}else{
					$nome_usuario = 'Sem Referência';
				}

				$dataF = implode('/', array_reverse(@explode('-', $data)));
				
				echo "mobileHtml += `";
				echo "<div class='saida-card-mobile'>";
				echo "<div class='saida-card-header'>";
				echo "<img src='img/produtos/{$foto_produto}' class='saida-card-image' alt='{$nome_produto}'>";
				echo "<div class='saida-card-info'>";
				echo "<div class='saida-card-name'>{$nome_produto}</div>";
				echo "<div class='saida-card-date'>{$dataF}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='saida-card-details'>";
				echo "<div class='saida-card-detail-item'>";
				echo "<div class='saida-card-detail-label'>Quantidade</div>";
				echo "<div class='saida-card-detail-value' style='color: #ff9800;'><i class='fa fa-minus-circle'></i> {$quantidade}</div>";
				echo "</div>";
				echo "<div class='saida-card-detail-item'>";
				echo "<div class='saida-card-detail-label'>Usuário</div>";
				echo "<div class='saida-card-detail-value'>{$nome_usuario}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='saida-card-motivo'>";
				echo "<div class='saida-card-motivo-label'>Motivo</div>";
				echo "<div class='saida-card-motivo-text'>{$motivo}</div>";
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
