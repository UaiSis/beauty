<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$data_hoje = date('Y-m-d');

$pag = 'agendamentos';

?>

<style>
	/* Página de Agendamentos Moderna */
	.agendamentos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.agendamentos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.agendamentos-header-content {
		flex: 1;
	}

	.agendamentos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.agendamentos-title-icon {
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

	.agendamentos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.agendamentos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.agendamentos-divider {
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
		width: 150px;
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

	.status-badge.agendado {
		background: rgba(255, 152, 0, 0.12);
		color: #f57c00;
	}

	.status-badge.concluido {
		background: rgba(0, 216, 150, 0.12);
		color: #00a574;
	}

	.status-badge.cancelado {
		background: rgba(108, 117, 125, 0.12);
		color: #6c757d;
	}

	.servico-info {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.servico-icon {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		flex-shrink: 0;
	}

	.servico-icon.agendado {
		background: #ff9800;
	}

	.servico-icon.concluido {
		background: #00d896;
	}

	.servico-icon.cancelado {
		background: #6c757d;
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

	.table-action-icon.delete {
		color: #ff9800;
		background: rgba(255, 152, 0, 0.08);
	}

	.table-action-icon.delete:hover {
		background: rgba(255, 152, 0, 0.15);
	}

	/* Cards Mobile */
	.agendamentos-mobile-list {
		display: none;
	}

	.agendamento-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.agendamento-card-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 12px;
	}

	.agendamento-card-servico {
		flex: 1;
	}

	.agendamento-card-servico-nome {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.agendamento-card-profissional {
		font-size: 12px;
		color: #6c757d;
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.agendamento-card-details {
		display: flex;
		gap: 16px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.agendamento-card-detail-item {
		flex: 1;
	}

	.agendamento-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.agendamento-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.agendamento-card-actions {
		display: flex;
		gap: 8px;
	}

	.agendamento-card-action-btn {
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

	.agendamento-card-action-btn.delete {
		background: rgba(255, 152, 0, 0.1);
		color: #ff9800;
	}

	.agendamento-card-action-btn i {
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
		.agendamentos-page-modern {
			padding: 16px;
		}

		.agendamentos-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.agendamentos-title-wrapper {
			gap: 10px;
		}

		.agendamentos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.agendamentos-title {
			font-size: 20px;
		}

		.agendamentos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.agendamentos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.table-card-modern {
			display: none;
		}

		.agendamentos-mobile-list {
			display: block;
		}
	}
</style>

<div class="agendamentos-page-modern">
	
	<div class="agendamentos-header">
		<div class="agendamentos-header-content">
			<div class="agendamentos-title-wrapper">
				<div class="agendamentos-title-icon">
					<i class="fa fa-calendar"></i>
				</div>
				<h1 class="agendamentos-title">Meus Agendamentos</h1>
			</div>
			<p class="agendamentos-subtitle">Acompanhe seus agendamentos e compromissos</p>
			<div class="agendamentos-divider"></div>
		</div>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarAgendamentos(0)" style="
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
				placeholder="Buscar agendamento..." 
				onkeyup="listarAgendamentos()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="agendamentos-mobile-list">
		<div id="listar-mobile"></div>
	</div>
	
</div>

<script type="text/javascript">var pag = "<?=$pag?>"</script>

<script type="text/javascript">
	$(document).ready(function() {
		listarAgendamentos();
	});

	function listarAgendamentos(pagina){
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

	function excluirAgd(id){
		$.ajax({
			url: '../../ajax/excluir.php',
			method: 'POST',
			data: {id},
			dataType: "text",

			success: function (mensagem) {  
				if (mensagem.trim() == "Excluído com Sucesso") {                
					listarAgendamentos();                
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
					listarAgendamentos();  
				}
			},      
		});
	}
</script>