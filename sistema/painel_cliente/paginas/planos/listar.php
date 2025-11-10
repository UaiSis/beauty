<?php 
@session_start();
$id_cliente = $_SESSION['id'];
require_once("../../../conexao.php");
$tabela = 'assinaturas';

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

$query = $pdo->query("SELECT * FROM $tabela where cliente = '$id_cliente' and (data LIKE '$busca' or valor LIKE '$busca' or vencimento LIKE '$busca') ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){	

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Assinatura</th> 	
	<th>Plano</th> 	
	<th>Valor</th> 
	<th>Data</th> 	
	<th>Vencimento</th> 	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$cliente = $res[$i]['cliente'];	
	$data = $res[$i]['data'];
	$grupo = $res[$i]['grupo'];	
	$item = $res[$i]['item'];	
	$pago = $res[$i]['pago'];
	$valor = $res[$i]['valor'];	
	$ref_pix = $res[$i]['ref_pix'];	
	$frequencia = $res[$i]['frequencia'];
	$vencimento = $res[$i]['vencimento'];	
	
	
	$query2 = $pdo->query("SELECT * FROM grupo_assinaturas where id = '$grupo'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_grupo = $res2[0]['nome'];
	}else{
		$nome_grupo = 'Nenhum!';
	}

	$query2 = $pdo->query("SELECT * FROM itens_assinaturas where id = '$item'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_item = $res2[0]['nome'];
	}else{
		$nome_item = 'Nenhum!';
	}

	$valorF = 'R$ ' . number_format($valor, 2, ',', '.');
	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));

	// Definir classe do badge de status
	$badge_class = 'pendente';
	$icon_class = 'pendente';
	$visivel = '';

	if($pago == "Sim"){
		$badge_class = 'pago';
		$icon_class = 'pago';
		$visivel = 'ocultar';
	}

	//verificar se o pagamento está aprovado
	if($pago == 'Não' and $ref_pix != ""){
		require_once("../../../../pagamentos/consultar_pagamento.php");
		if($status_api == 'approved'){
			$id_usuario = $_SESSION['id'];
			$id = $res[$i]['id'];
			$valor = $res[$i]['valor'];
			$forma_pgto = 'MP';
			$data_pgto = $data_atual;
			require_once("aprovar_plano.php");
		}				
	}

echo <<<HTML
<tr>
<td>
	<div class="plano-info">
		<div class="plano-icon {$icon_class}"></div>
		<span>{$nome_grupo}</span>
	</div>
</td>
<td>{$nome_item}</td>
<td>{$valorF}</td>
<td>{$dataF}</td>
<td>{$vencimentoF}</td>
<td>
	<div class="table-actions-cell">
		<a href="../../plano/{$id}" target="_blank" class="table-action-icon pay {$visivel}" title="Efetuar Pagamento">
			<i class="fa fa-money"></i>
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
$query2 = $pdo->query("SELECT * FROM $tabela where cliente = '$id_cliente' and (data LIKE '$busca' or valor LIKE '$busca' or vencimento LIKE '$busca')");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

$num_paginas = ceil($total_reg2/$itens_por_pagina);

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarPlanos(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarPlanos({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarPlanos({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
				<i class="fa fa-angle-double-right"></i>
			</a>
		</li>
	</ul>
</div>
HTML;
}

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-credit-card" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum plano encontrado</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Você ainda não possui assinaturas ativas</p>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where cliente = '$id_cliente' and (data LIKE '$busca' or valor LIKE '$busca' or vencimento LIKE '$busca') ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$grupo = $res_mobile[$i]['grupo'];	
				$item = $res_mobile[$i]['item'];	
				$pago = $res_mobile[$i]['pago'];
				$valor = $res_mobile[$i]['valor'];	
				$data = $res_mobile[$i]['data'];
				$vencimento = $res_mobile[$i]['vencimento'];	
				
				$query2 = $pdo->query("SELECT * FROM grupo_assinaturas where id = '$grupo'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_grupo = $res2[0]['nome'];
				}else{
					$nome_grupo = 'Nenhum!';
				}

				$query2 = $pdo->query("SELECT * FROM itens_assinaturas where id = '$item'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_item = $res2[0]['nome'];
				}else{
					$nome_item = 'Nenhum!';
				}
				
				$valorF = 'R$ ' . number_format($valor, 2, ',', '.');
				$dataF = implode('/', array_reverse(@explode('-', $data)));
				$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));
				
				$badge_class = 'pendente';
				$icon_class = 'pendente';
				$visivel = '';

				if($pago == "Sim"){
					$badge_class = 'pago';
					$icon_class = 'pago';
					$visivel = 'ocultar';
				}
				
				$status_text = ($pago == "Sim") ? "Pago" : "Pendente";
				$hide_pay = ($visivel == 'ocultar') ? 'style="display:none;"' : '';
				
				echo "mobileHtml += `";
				echo "<div class='plano-card-mobile'>";
				echo "<div class='plano-card-header'>";
				echo "<div class='plano-card-assinatura'>";
				echo "<div class='plano-card-assinatura-nome'>";
				echo "<div class='plano-icon {$icon_class}'></div>";
				echo "{$nome_grupo}";
				echo "</div>";
				echo "<div class='plano-card-plano-nome'>";
				echo "<i class='fa fa-tag' style='font-size: 10px;'></i> {$nome_item}";
				echo "</div>";
				echo "</div>";
				echo "<span class='status-badge {$badge_class}'>{$status_text}</span>";
				echo "</div>";
				
				echo "<div class='plano-card-details'>";
				echo "<div class='plano-card-detail-item'>";
				echo "<div class='plano-card-detail-label'>Valor</div>";
				echo "<div class='plano-card-detail-value'>{$valorF}</div>";
				echo "</div>";
				echo "<div class='plano-card-detail-item'>";
				echo "<div class='plano-card-detail-label'>Data</div>";
				echo "<div class='plano-card-detail-value'>{$dataF}</div>";
				echo "</div>";
				echo "<div class='plano-card-detail-item'>";
				echo "<div class='plano-card-detail-label'>Vencimento</div>";
				echo "<div class='plano-card-detail-value'>{$vencimentoF}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='plano-card-actions' {$hide_pay}>";
				echo "<a href='../../plano/{$id}' target='_blank' class='plano-card-action-btn pay'>";
				echo "<i class='fa fa-money'></i> Efetuar Pagamento";
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
