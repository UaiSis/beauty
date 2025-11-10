<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$entradas == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'entradas';
?>

<style>
	/* Página de Entradas Moderna */
	.entradas-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.entradas-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.entradas-header-content {
		flex: 1;
	}

	.entradas-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.entradas-title-icon {
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

	.entradas-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.entradas-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.entradas-divider {
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
		width: 180px;
	}

	.table-modern tbody tr {
		border-bottom: none;
		transition: all 0.2s ease;
		background: #fff;
		border-left: 3px solid transparent;
	}

	.table-modern tbody tr:nth-child(even) {
		background: #fafbfc;
	}

	.table-modern tbody tr:hover {
		background: #f5f9f8;
		border-left-color: #007A63;
	}

	.table-modern tbody td {
		padding: 16px 20px;
		vertical-align: middle;
		font-size: 14px;
		color: #1a1a1a;
		border: none;
	}

	.product-image {
		width: 44px;
		height: 44px;
		border-radius: 8px;
		object-fit: cover;
		margin-right: 14px;
		background: #e0e0e0;
		flex-shrink: 0;
	}

	.product-info-cell {
		display: flex;
		align-items: center;
	}

	.product-name {
		font-weight: 600;
		color: #1a1a1a;
		font-size: 14px;
	}

	.quantidade-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 13px;
		font-weight: 700;
		background: rgba(0, 216, 150, 0.12);
		color: #00d896;
	}

	.motivo-text {
		color: #6c757d;
		font-size: 13px;
		font-style: italic;
	}

	.usuario-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
		background: rgba(66, 165, 245, 0.12);
		color: #1976d2;
	}

	/* Cards Mobile */
	.entradas-mobile-list {
		display: none;
	}

	.entrada-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		border-left: 4px solid #007A63;
	}

	.entrada-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.entrada-card-image {
		width: 60px;
		height: 60px;
		border-radius: 10px;
		object-fit: cover;
		border: 2px solid rgba(0, 122, 99, 0.2);
	}

	.entrada-card-info {
		flex: 1;
	}

	.entrada-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
	}

	.entrada-card-date {
		font-size: 12px;
		color: #6c757d;
	}

	.entrada-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.entrada-card-detail-item {
		display: flex;
		flex-direction: column;
	}

	.entrada-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.entrada-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.entrada-card-motivo {
		background: rgba(0, 122, 99, 0.05);
		border-radius: 8px;
		padding: 12px;
		margin-top: 12px;
	}

	.entrada-card-motivo-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 6px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.entrada-card-motivo-text {
		font-size: 13px;
		color: #007A63;
		font-style: italic;
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
		.entradas-page-modern {
			padding: 16px;
		}

		.entradas-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.entradas-title-wrapper {
			gap: 10px;
		}

		.entradas-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.entradas-title {
			font-size: 20px;
		}

		.entradas-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.entradas-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.table-card-modern {
			display: none;
		}

		.entradas-mobile-list {
			display: block;
		}
	}
</style>

<div class="entradas-page-modern">
	
	<div class="entradas-header">
		<div class="entradas-header-content">
			<div class="entradas-title-wrapper">
				<div class="entradas-title-icon">
					<i class="fa fa-sign-in"></i>
				</div>
				<h1 class="entradas-title">Entradas de Estoque</h1>
			</div>
			<p class="entradas-subtitle">Histórico completo de todas as entradas de produtos no estoque</p>
			<div class="entradas-divider"></div>
		</div>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarEntradas(0)" style="
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
				placeholder="Buscar entrada..." 
				onkeyup="listarEntradas()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="entradas-mobile-list">
		<div id="listar-mobile"></div>
	</div>
	
</div>

<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listarEntradas();
	});

	function listarEntradas(pagina){
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
			}
		});
	}
</script>
