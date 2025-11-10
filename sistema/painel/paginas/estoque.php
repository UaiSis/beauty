<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$estoque == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'estoque';
?>

<style>
	/* Página de Estoque Moderna */
	.estoque-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.estoque-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.estoque-header-content {
		flex: 1;
	}

	.estoque-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.estoque-title-icon {
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

	.estoque-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.estoque-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.estoque-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	/* Alert Stats Cards */
	.stats-cards-container {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 16px;
		margin-bottom: 24px;
	}

	.stat-card {
		background: #fff;
		border-radius: 12px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		display: flex;
		align-items: center;
		gap: 16px;
	}

	.stat-card.alert {
		border-left: 4px solid #ef5350;
		background: linear-gradient(135deg, #fff 0%, rgba(239, 83, 80, 0.03) 100%);
	}

	.stat-icon {
		width: 48px;
		height: 48px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
		flex-shrink: 0;
	}

	.stat-icon.alert {
		background: rgba(239, 83, 80, 0.12);
		color: #ef5350;
	}

	.stat-content {
		flex: 1;
	}

	.stat-label {
		font-size: 12px;
		color: #6c757d;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		margin-bottom: 4px;
	}

	.stat-value {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
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
		border-left: 3px solid transparent;
	}

	.table-modern tbody tr:nth-child(even) {
		background: #fafbfc;
	}

	.table-modern tbody tr:hover {
		background: #f5f9f8;
		border-left-color: #007A63;
	}

	.table-modern tbody tr.critical {
		background: rgba(239, 83, 80, 0.05);
		border-left-color: #ef5350;
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

	.categoria-badge {
		display: inline-block;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
		background: rgba(0, 122, 99, 0.12);
		color: #007A63;
		border: none;
	}

	.stock-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 8px 14px;
		border-radius: 20px;
		font-size: 13px;
		font-weight: 700;
	}

	.stock-badge.critical {
		background: rgba(239, 83, 80, 0.15);
		color: #ef5350;
		animation: blink-alert 2s ease-in-out infinite;
	}

	.stock-badge.warning {
		background: rgba(255, 152, 0, 0.15);
		color: #ff9800;
	}

	@keyframes blink-alert {
		0%, 100% { opacity: 1; }
		50% { opacity: 0.7; }
	}

	.alert-level-indicator {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 12px;
		color: #6c757d;
	}

	.alert-level-indicator .fa {
		font-size: 14px;
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

	.table-action-icon.view {
		color: #42a5f5;
		background: rgba(66, 165, 245, 0.08);
	}

	.table-action-icon.view:hover {
		background: rgba(66, 165, 245, 0.15);
	}

	/* Modal Moderno */
	.modal-content {
		border: none;
		border-radius: 12px;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
	}

	.modal-header {
		background: #fff;
		border-bottom: 1px solid #f0f0f0;
		padding: 24px 28px;
		border-radius: 12px 12px 0 0;
	}

	.modal-header .modal-title {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.modal-title-main {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 20px;
		font-weight: 700;
		color: #1a1a1a;
	}

	.modal-title-icon {
		width: 40px;
		height: 40px;
		background: rgba(0, 122, 99, 0.1);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 18px;
	}

	.modal-subtitle {
		font-size: 13px;
		color: #6c757d;
		font-weight: 400;
		margin-left: 50px;
	}

	.modal-header .close {
		background: #f0f0f0;
		border-radius: 8px;
		width: 32px;
		height: 32px;
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 1;
		margin: 0;
		padding: 0;
		transition: all 0.2s ease;
	}

	.modal-header .close:hover {
		background: #e0e0e0;
	}

	.modal-body {
		padding: 28px;
		background: #fff;
	}

	/* Cards Mobile */
	.estoque-mobile-list {
		display: none;
	}

	.estoque-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		border-left: 4px solid #ef5350;
		position: relative;
	}

	.alert-badge-mobile {
		position: absolute;
		top: 12px;
		right: 12px;
		background: rgba(239, 83, 80, 0.15);
		color: #ef5350;
		padding: 4px 10px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 700;
		text-transform: uppercase;
	}

	.estoque-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.estoque-card-image {
		width: 60px;
		height: 60px;
		border-radius: 10px;
		object-fit: cover;
		border: 2px solid rgba(239, 83, 80, 0.2);
	}

	.estoque-card-info {
		flex: 1;
	}

	.estoque-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
	}

	.estoque-card-category {
		font-size: 12px;
		color: #6c757d;
	}

	.estoque-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.estoque-card-detail-item {
		display: flex;
		flex-direction: column;
	}

	.estoque-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.estoque-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.estoque-card-detail-value.alert {
		color: #ef5350;
		font-size: 14px;
	}

	.estoque-card-actions {
		display: flex;
		gap: 8px;
	}

	.estoque-card-action-btn {
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

	.estoque-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
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
		.estoque-page-modern {
			padding: 16px;
		}

		.estoque-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.estoque-title-wrapper {
			gap: 10px;
		}

		.estoque-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.estoque-title {
			font-size: 20px;
		}

		.estoque-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.estoque-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.table-card-modern {
			display: none;
		}

		.estoque-mobile-list {
			display: block;
		}

		.stats-cards-container {
			grid-template-columns: 1fr;
		}

		.modal-subtitle {
			margin-left: 0;
			margin-top: 8px;
		}

		.modal-dialog {
			margin: 8px;
		}

		.modal-body {
			padding: 20px;
		}
	}
</style>

<div class="estoque-page-modern">
	
	<div class="estoque-header">
		<div class="estoque-header-content">
			<div class="estoque-title-wrapper">
				<div class="estoque-title-icon">
					<i class="fa fa-cubes"></i>
				</div>
				<h1 class="estoque-title">Estoque Baixo</h1>
			</div>
			<p class="estoque-subtitle">Produtos que precisam de reposição urgente no estoque</p>
			<div class="estoque-divider"></div>
		</div>
	</div>

	<div id="stats-container" class="stats-cards-container"></div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarEstoque(0)" style="
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
				placeholder="Buscar produto..." 
				onkeyup="listarEstoque()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="estoque-mobile-list">
		<div id="listar-mobile"></div>
	</div>
	
</div>




<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon">
							<i class="fa fa-info-circle"></i>
						</div>
						<span id="nome_dados"></span>
					</div>
					<div class="modal-subtitle">
						Detalhes do produto com estoque baixo
					</div>
				</div>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">

				<!-- Alert Banner -->
				<div style="background: rgba(239, 83, 80, 0.1); border-left: 4px solid #ef5350; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
					<div style="display: flex; align-items: center; gap: 12px;">
						<i class="fa fa-exclamation-circle" style="font-size: 24px; color: #ef5350;"></i>
						<div>
							<div style="font-size: 14px; font-weight: 700; color: #ef5350; margin-bottom: 4px;">Estoque Crítico!</div>
							<div style="font-size: 13px; color: #6c757d;">Este produto precisa de reposição urgente.</div>
						</div>
					</div>
				</div>

				<!-- Foto do Produto -->
				<div style="text-align: center; margin-bottom: 28px;">
					<img id="target_mostrar" style="width: 200px; height: 200px; border-radius: 12px; object-fit: cover; border: 3px solid rgba(239, 83, 80, 0.3); box-shadow: 0 4px 12px rgba(239, 83, 80, 0.2);">
				</div>

				<!-- Informações em Cards -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-tag" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Informações Gerais</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Categoria</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="categoria_dados"></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Estoque Atual</div>
							<div style="font-size: 16px; font-weight: 700; color: #ef5350;" id="estoque_dados"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Descrição</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="descricao_dados"></div>
						</div>
					</div>
				</div>

				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-dollar" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Valores</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Valor de Compra</div>
							<div style="font-size: 16px; font-weight: 700; color: #007A63;">R$ <span id="valor_compra_dados"></span></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Valor de Venda</div>
							<div style="font-size: 16px; font-weight: 700; color: #00a574;">R$ <span id="valor_venda_dados"></span></div>
						</div>
					</div>
				</div>

				<div style="background: rgba(239, 83, 80, 0.05); border-radius: 12px; padding: 20px; border: 2px solid rgba(239, 83, 80, 0.2);">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-bell" style="color: #ef5350;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #ef5350;">Alerta de Estoque</span>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Nível Mínimo Definido</div>
							<div style="font-size: 16px; font-weight: 700; color: #ef5350;" id="nivel_estoque_dados"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listarEstoque();
	});

	function listarEstoque(pagina){
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
