<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'receber';

//percorrer para verificar se tem conta paga
$query = $pdo->query("SELECT * FROM receber where pago = 'Não' and ref_pix is not null ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){	
	for($i=0; $i < $total_reg; $i++){

		$ref_pix = $res[$i]['ref_pix'];
		$id = $res[$i]['id'];

		//verificar se o pagamento está aprovado
		if($ref_pix != ""){
			require_once("../../pagamentos/consultar_pagamento.php");
			if($status_api == 'approved'){
					require_once("../../pagamentos/baixar_conta.php");
				}			

		}

	}
}

?>

<style>
	/* Página de Pagamentos Moderna */
	.pagamentos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.pagamentos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.pagamentos-header-content {
		flex: 1;
	}

	.pagamentos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.pagamentos-title-icon {
		width: 40px;
		height: 40px;
		background: rgba(0, 122, 99, 0.12);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 20px;
		flex-shrink: 0;
	}

	.pagamentos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.pagamentos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.pagamentos-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	/* Tabela Moderna */
	.table-card-modern {
		background: #fff;
		border-radius: 16px;
		padding: 0;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		overflow: hidden;
	}

	.table-modern {
		margin: 0;
		width: 100%;
	}

	.table-modern thead th {
		background: #007A63;
		color: #ffffff;
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 1px;
		padding: 16px 20px;
		border: none;
	}

	.table-modern thead th:last-child {
		text-align: center;
		width: 120px;
	}

	.table-modern tbody tr {
		border-bottom: none;
		transition: all 0.2s ease;
		background: #fff;
	}

	.table-modern tbody tr:nth-child(even) {
		background: #fafbfc;
	}

	.table-modern tbody tr:hover {
		background: #f5f9f8;
	}

	.table-modern tbody tr.vencido {
		background: rgba(239, 83, 80, 0.05);
	}

	.table-modern tbody tr.vencido:hover {
		background: rgba(239, 83, 80, 0.08);
	}

	.table-modern tbody td {
		padding: 16px 20px;
		vertical-align: middle;
		font-size: 14px;
		color: #1a1a1a;
		border: none;
	}

	.status-badge {
		display: inline-block;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
		border: none;
	}

	.status-badge.pendente {
		background: rgba(255, 152, 0, 0.12);
		color: #f57c00;
	}

	.status-badge.pago {
		background: rgba(0, 216, 150, 0.12);
		color: #00a574;
	}

	.status-badge.vencido {
		background: rgba(239, 83, 80, 0.12);
		color: #ef5350;
	}

	.pagamento-info {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.pagamento-icon {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		flex-shrink: 0;
	}

	.pagamento-icon.pendente {
		background: #ff9800;
	}

	.pagamento-icon.pago {
		background: #00d896;
	}

	.pagamento-icon.vencido {
		background: #ef5350;
	}

	.table-actions-cell {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
	}

	.table-action-icon {
		width: 34px;
		height: 34px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
		transition: all 0.25s ease;
		cursor: pointer;
		text-decoration: none;
		font-size: 14px;
		flex-shrink: 0;
	}

	.table-action-icon.pay {
		color: #00a574;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.pay:hover {
		background: rgba(0, 216, 150, 0.15);
	}

	.table-action-icon.file {
		color: #42a5f5;
		background: rgba(66, 165, 245, 0.08);
	}

	.table-action-icon.file:hover {
		background: rgba(66, 165, 245, 0.15);
	}

	/* Resumo de Totais */
	.totais-resumo {
		background: #fff;
		border-radius: 12px;
		padding: 20px 28px;
		margin-top: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		display: flex;
		justify-content: flex-end;
		gap: 32px;
		flex-wrap: wrap;
	}

	.total-item {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.total-label {
		font-size: 13px;
		color: #6c757d;
		font-weight: 500;
	}

	.total-valor {
		font-size: 16px;
		font-weight: 700;
	}

	.total-valor.pago {
		color: #00a574;
	}

	.total-valor.pendente {
		color: #ef5350;
	}

	/* Cards Mobile */
	.pagamentos-mobile-list {
		display: none;
	}

	.pagamento-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.pagamento-card-mobile.vencido {
		border-left: 3px solid #ef5350;
	}

	.pagamento-card-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 12px;
	}

	.pagamento-card-descricao {
		flex: 1;
	}

	.pagamento-card-descricao-nome {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.pagamento-card-details {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 12px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.pagamento-card-detail-item {
		flex: 1;
	}

	.pagamento-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.pagamento-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.pagamento-card-actions {
		display: flex;
		gap: 8px;
	}

	.pagamento-card-action-btn {
		flex: 1;
		padding: 8px;
		border-radius: 8px;
		font-size: 12px;
		font-weight: 600;
		border: none;
		cursor: pointer;
		transition: all 0.3s ease;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		text-decoration: none;
	}

	.pagamento-card-action-btn.pay {
		background: rgba(0, 216, 150, 0.1);
		color: #00a574;
	}

	.pagamento-card-action-btn.file {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.pagamento-card-action-btn i {
		font-size: 14px;
	}

	/* Barra de Busca Compacta */
	.search-bar-compact {
		position: relative;
		max-width: 350px;
	}

	.search-input-compact {
		width: 100%;
		padding: 10px 16px 10px 40px;
		border: 2px solid #e9ecef;
		border-radius: 10px;
		font-size: 13px;
		transition: all 0.3s ease;
		background: #fff;
	}

	.search-input-compact:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.search-icon {
		position: absolute;
		left: 14px;
		top: 50%;
		transform: translateY(-50%);
		color: #6c757d;
		pointer-events: none;
		font-size: 13px;
	}

	/* Paginação Moderna */
	.pagination-modern {
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 8px;
		padding: 20px;
		background: #fff;
		border-radius: 0 0 16px 16px;
	}

	.pagination-modern .page-item {
		list-style: none;
	}

	.pagination-modern .paginador {
		padding: 8px 14px;
		border-radius: 8px;
		color: #495057;
		text-decoration: none;
		font-weight: 500;
		font-size: 14px;
		transition: all 0.2s ease;
		display: inline-block;
		border: 1px solid #dee2e6;
		background: #fff;
	}

	.pagination-modern .paginador:hover {
		background: rgba(0, 122, 99, 0.08);
		border-color: #007A63;
		color: #007A63;
	}

	.pagination-modern .page-item.active .paginador {
		background: #007A63;
		color: #fff;
		border-color: #007A63;
	}

	@media (max-width: 768px) {
		.pagamentos-page-modern {
			padding: 16px;
		}

		.pagamentos-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.pagamentos-title-wrapper {
			gap: 10px;
		}

		.pagamentos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.pagamentos-title {
			font-size: 20px;
		}

		.pagamentos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.pagamentos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.table-card-modern {
			display: none;
		}

		.pagamentos-mobile-list {
			display: block;
		}

		.totais-resumo {
			justify-content: center;
			gap: 20px;
		}
	}
</style>

<div class="pagamentos-page-modern">
	
	<div class="pagamentos-header">
		<div class="pagamentos-header-content">
			<div class="pagamentos-title-wrapper">
				<div class="pagamentos-title-icon">
					<i class="fa fa-dollar"></i>
				</div>
				<h1 class="pagamentos-title">Meus Pagamentos</h1>
			</div>
			<p class="pagamentos-subtitle">Acompanhe seus pagamentos e pendências</p>
			<div class="pagamentos-divider"></div>
		</div>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarPagamentos(0)" style="
				padding: 8px 12px;
				border: 2px solid #e9ecef;
				border-radius: 8px;
				font-size: 13px;
				color: #495057;
				cursor: pointer;
				background: #fff;
				transition: all 0.3s ease;
			">
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">registros</span>
		</div>

		<div class="search-bar-compact" style="max-width: 350px;">
			<i class="fa fa-search search-icon"></i>
			<input 
				type="text" 
				class="search-input-compact" 
				id="buscar" 
				placeholder="Buscar pagamento..." 
				onkeyup="listarPagamentos()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="pagamentos-mobile-list">
		<div id="listar-mobile"></div>
	</div>
	
</div>

<script type="text/javascript">var pag = "<?=$pag?>"</script>

<script type="text/javascript">
	$(document).ready(function() {
		listarPagamentos();
	});

	function listarPagamentos(pagina){
		$("#pagina").val(pagina);

		var busca = $("#buscar").val();
		var itens_por_pagina = $("#itens_por_pagina").val();
		
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {busca, pagina, itens_por_pagina},
			dataType: "html",

			success:function(result){
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>

