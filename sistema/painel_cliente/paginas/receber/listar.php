<?php 
@session_start();
$id_cliente = $_SESSION['id'];
require_once("../../../conexao.php");
$tabela = 'receber';
$data_hoje = date('Y-m-d');

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

$total_pago = 0;
$total_a_pagar = 0;

$query = $pdo->query("SELECT * FROM $tabela where pessoa = '$id_cliente' and (descricao LIKE '$busca' or valor LIKE '$busca' or data_venc LIKE '$busca') order by id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Descrição</th>	
	<th>Valor</th> 	
	<th>Vencimento</th> 	
	<th>Data PGTO</th> 
	<th>Arquivo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];	
	$descricao = $res[$i]['descricao'];
	$tipo = $res[$i]['tipo'];
	$valor = $res[$i]['valor'];
	$data_lanc = $res[$i]['data_lanc'];
	$data_pgto = $res[$i]['data_pgto'];
	$data_venc = $res[$i]['data_venc'];
	$foto = $res[$i]['foto'];
	$pessoa = $res[$i]['pessoa'];
	$pago = $res[$i]['pago'];
	$pgto = $res[$i]['pgto'];
	
	$valorF = 'R$ ' . number_format($valor, 2, ',', '.');
	$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
	$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));

	// Definir classe do badge de status
	$badge_class = 'pendente';
	$icon_class = 'pendente';
	$visivel = '';
	$classe_linha = '';

	if($pago == "Sim"){
		$badge_class = 'pago';
		$icon_class = 'pago';
		$data_pgto_display = $data_pgtoF;
		$visivel = 'ocultar';
		$total_pago += $valor;
	}else{
		$data_pgto_display = '<span class="status-badge pendente">Pendente</span>';
		$total_a_pagar += $valor;
		
		// Verificar se está vencido
		if($data_venc < $data_hoje){
			$badge_class = 'vencido';
			$icon_class = 'vencido';
			$classe_linha = 'vencido';
		}
	}

	//extensão do arquivo
	$ext = pathinfo($foto, PATHINFO_EXTENSION);
	if($ext == 'pdf'){
		$tumb_arquivo = 'pdf.png';
	}else if($ext == 'rar' || $ext == 'zip'){
		$tumb_arquivo = 'rar.png';
	}else{
		$tumb_arquivo = $foto;
	}

	$tem_arquivo = ($foto != "" && $foto != "sem-foto.jpg");

echo <<<HTML
<tr class="{$classe_linha}">
<td>
	<div class="pagamento-info">
		<div class="pagamento-icon {$icon_class}"></div>
		<span>{$descricao}</span>
	</div>
</td>
<td>{$valorF}</td>
<td>{$data_vencF}</td>
<td>{$data_pgto_display}</td>
<td>
HTML;

if($tem_arquivo){
	echo '<a href="../painel/img/contas/'.$foto.'" target="_blank"><img src="../painel/img/contas/'.$tumb_arquivo.'" width="27px" class="mr-2"></a>';
}else{
	echo '-';
}

echo <<<HTML
</td>
<td>
	<div class="table-actions-cell">
HTML;

if($tem_arquivo){
	echo '<a href="../painel/img/contas/'.$foto.'" target="_blank" class="table-action-icon file" title="Ver Arquivo"><i class="fa fa-file"></i></a>';
}

if($visivel != 'ocultar'){
	echo '<a href="../../conta/'.$id.'" target="_blank" class="table-action-icon pay" title="Efetuar Pagamento"><i class="fa fa-money"></i></a>';
}

echo <<<HTML
	</div>
</td>
</tr>
HTML;

}

echo <<<HTML
</tbody>
</table>
HTML;

// Calcular totais gerais
$query_totais = $pdo->query("SELECT * FROM $tabela where pessoa = '$id_cliente'");
$res_totais = $query_totais->fetchAll(PDO::FETCH_ASSOC);
$total_pago_geral = 0;
$total_pendente_geral = 0;

foreach($res_totais as $item){
	if($item['pago'] == 'Sim'){
		$total_pago_geral += $item['valor'];
	}else{
		$total_pendente_geral += $item['valor'];
	}
}

$total_pago_geralF = number_format($total_pago_geral, 2, ',', '.');
$total_pendente_geralF = number_format($total_pendente_geral, 2, ',', '.');

echo <<<HTML
<div class="totais-resumo">
	<div class="total-item">
		<span class="total-label">Total Pago:</span>
		<span class="total-valor pago">R$ {$total_pago_geralF}</span>
	</div>
	<div class="total-item">
		<span class="total-label">Total à Pagar:</span>
		<span class="total-valor pendente">R$ {$total_pendente_geralF}</span>
	</div>
</div>
HTML;

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where pessoa = '$id_cliente' and (descricao LIKE '$busca' or valor LIKE '$busca' or data_venc LIKE '$busca')");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

$num_paginas = ceil($total_reg2/$itens_por_pagina);

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarPagamentos(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarPagamentos({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarPagamentos({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
				<i class="fa fa-angle-double-right"></i>
			</a>
		</li>
	</ul>
</div>
HTML;
}

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-dollar" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum pagamento encontrado</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Você não possui pagamentos registrados</p>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where pessoa = '$id_cliente' and (descricao LIKE '$busca' or valor LIKE '$busca' or data_venc LIKE '$busca') order by id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];	
				$descricao = $res_mobile[$i]['descricao'];
				$valor = $res_mobile[$i]['valor'];
				$data_venc = $res_mobile[$i]['data_venc'];
				$data_pgto = $res_mobile[$i]['data_pgto'];
				$foto = $res_mobile[$i]['foto'];
				$pago = $res_mobile[$i]['pago'];
				
				$valorF = 'R$ ' . number_format($valor, 2, ',', '.');
				$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));
				$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
				
				$badge_class = 'pendente';
				$icon_class = 'pendente';
				$visivel = '';
				$classe_vencido = '';
				
				if($pago == "Sim"){
					$badge_class = 'pago';
					$icon_class = 'pago';
					$data_pgto_display = $data_pgtoF;
					$visivel = 'ocultar';
					$status_text = "Pago";
				}else{
					$data_pgto_display = 'Pendente';
					$status_text = "Pendente";
					
					if($data_venc < $data_hoje){
						$badge_class = 'vencido';
						$icon_class = 'vencido';
						$classe_vencido = ' vencido';
						$status_text = "Vencido";
					}
				}

				$ext = pathinfo($foto, PATHINFO_EXTENSION);
				if($ext == 'pdf'){
					$tumb_arquivo = 'pdf.png';
				}else if($ext == 'rar' || $ext == 'zip'){
					$tumb_arquivo = 'rar.png';
				}else{
					$tumb_arquivo = $foto;
				}

				$tem_arquivo = ($foto != "" && $foto != "sem-foto.jpg");
				$hide_pay = ($visivel == 'ocultar') ? 'style="display:none;"' : '';
				
				echo "mobileHtml += `";
				echo "<div class='pagamento-card-mobile{$classe_vencido}'>";
				echo "<div class='pagamento-card-header'>";
				echo "<div class='pagamento-card-descricao'>";
				echo "<div class='pagamento-card-descricao-nome'>";
				echo "<div class='pagamento-icon {$icon_class}'></div>";
				echo "{$descricao}";
				echo "</div>";
				echo "</div>";
				echo "<span class='status-badge {$badge_class}'>{$status_text}</span>";
				echo "</div>";
				
				echo "<div class='pagamento-card-details'>";
				echo "<div class='pagamento-card-detail-item'>";
				echo "<div class='pagamento-card-detail-label'>Valor</div>";
				echo "<div class='pagamento-card-detail-value'>{$valorF}</div>";
				echo "</div>";
				echo "<div class='pagamento-card-detail-item'>";
				echo "<div class='pagamento-card-detail-label'>Vencimento</div>";
				echo "<div class='pagamento-card-detail-value'>{$data_vencF}</div>";
				echo "</div>";
				echo "<div class='pagamento-card-detail-item'>";
				echo "<div class='pagamento-card-detail-label'>Data PGTO</div>";
				echo "<div class='pagamento-card-detail-value'>{$data_pgto_display}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='pagamento-card-actions'>";
				if($tem_arquivo){
					echo "<a href='../painel/img/contas/{$foto}' target='_blank' class='pagamento-card-action-btn file'>";
					echo "<i class='fa fa-file'></i> Ver Arquivo";
					echo "</a>";
				}
				echo "<a href='../../conta/{$id}' target='_blank' class='pagamento-card-action-btn pay' {$hide_pay}>";
				echo "<i class='fa fa-money'></i> Pagar";
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

<style>
	.ocultar {
		display: none !important;
	}
</style>
