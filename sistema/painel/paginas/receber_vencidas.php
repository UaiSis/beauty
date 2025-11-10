<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$receber_vencidas == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'receber_vencidas';
$data_hoje = date('Y-m-d');

//percorrer para verificar se tem conta paga via PIX
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
	/* Página de Contas Vencidas Moderna */
	.vencidas-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.vencidas-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.vencidas-header-content {
		flex: 1;
	}

	.vencidas-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.vencidas-title-icon {
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

	.vencidas-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.vencidas-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.vencidas-divider {
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
		width: 220px;
	}

	.table-modern tbody tr {
		border-bottom: none;
		transition: all 0.2s ease;
		background: rgba(239, 83, 80, 0.05);
		border-left: 3px solid #ef5350;
	}

	.table-modern tbody tr:nth-child(even) {
		background: rgba(239, 83, 80, 0.08);
	}

	.table-modern tbody tr:hover {
		background: rgba(239, 83, 80, 0.12);
		border-left-color: #ef5350;
	}

	.table-modern tbody td {
		padding: 16px 20px;
		vertical-align: middle;
		font-size: 14px;
		color: #1a1a1a;
		border: none;
	}

	.status-badge.vencido {
		background: rgba(239, 83, 80, 0.15);
		color: #ef5350;
		animation: blink-alert 2s ease-in-out infinite;
	}

	@keyframes blink-alert {
		0%, 100% { opacity: 1; }
		50% { opacity: 0.7; }
	}

	.valor-cell {
		font-weight: 700;
		font-size: 15px;
		color: #ef5350;
	}

	.table-actions-cell {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		flex-wrap: wrap;
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

	.table-action-icon.delete {
		color: #ef5350;
		background: rgba(239, 83, 80, 0.08);
	}

	.table-action-icon.delete:hover {
		background: rgba(239, 83, 80, 0.15);
	}

	.table-action-icon.baixar {
		color: #00d896;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.baixar:hover {
		background: rgba(0, 216, 150, 0.15);
	}

	.table-action-icon.whatsapp {
		color: #25D366;
		background: rgba(37, 211, 102, 0.08);
	}

	.table-action-icon.whatsapp:hover {
		background: rgba(37, 211, 102, 0.15);
	}

	/* Totalizadores */
	.totalizadores {
		display: flex;
		justify-content: flex-end;
		gap: 24px;
		padding: 20px;
		background: #fafbfc;
		border-radius: 0 0 16px 16px;
		border-top: 2px solid #f0f0f0;
	}

	.total-item {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
	}

	.total-label {
		font-size: 12px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.total-value {
		font-size: 18px;
		font-weight: 700;
	}

	.total-value.vencido {
		color: #ef5350;
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

	.form-group {
		margin-bottom: 20px;
	}

	.form-group label {
		font-size: 13px;
		font-weight: 600;
		color: #1a1a1a;
		margin-bottom: 8px;
		display: block;
	}

	.form-group label .required {
		color: #ef5350;
		margin-left: 4px;
	}

	.form-control {
		width: 100%;
		padding: 10px 14px;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		font-size: 14px;
		transition: all 0.3s ease;
		background: #fff;
	}

	.form-control:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.modal-footer {
		background: #fafafa;
		border-top: 1px solid #f0f0f0;
		padding: 20px 28px;
		border-radius: 0 0 12px 12px;
		display: flex;
		gap: 12px;
		justify-content: flex-end;
	}

	.btn-cancel {
		background: #fff;
		color: #6c757d;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 10px 24px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.btn-cancel:hover {
		background: #f8f9fa;
		border-color: #6c757d;
	}

	.btn-submit {
		background: #007A63;
		color: #fff;
		border: none;
		border-radius: 8px;
		padding: 10px 24px;
		font-weight: 600;
		font-size: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
	}

	.btn-submit:hover {
		background: #006854;
	}

	/* Cards Mobile */
	.vencidas-mobile-list {
		display: none;
	}

	.vencida-card-mobile {
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
		animation: blink-alert 2s ease-in-out infinite;
	}

	.vencida-card-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 12px;
		padding-bottom: 12px;
		border-bottom: 1px solid #f0f0f0;
		padding-right: 80px;
	}

	.vencida-card-title {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
	}

	.vencida-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
	}

	.vencida-card-detail-item {
		display: flex;
		flex-direction: column;
	}

	.vencida-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.vencida-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.vencida-card-detail-value.alert {
		color: #ef5350;
		font-weight: 700;
	}

	.vencida-card-actions {
		display: flex;
		gap: 8px;
		margin-top: 12px;
		padding-top: 12px;
		border-top: 1px solid #f0f0f0;
	}

	.vencida-card-action-btn {
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

	.vencida-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.vencida-card-action-btn.baixar {
		background: rgba(0, 216, 150, 0.1);
		color: #00d896;
	}

	.vencida-card-action-btn.whatsapp {
		background: rgba(37, 211, 102, 0.1);
		color: #25D366;
	}

	.vencida-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	/* Barra de Busca */
	.search-bar-compact {
		position: relative;
		max-width: 350px;
		margin-bottom: 20px;
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

	@media (max-width: 768px) {
		.vencidas-page-modern {
			padding: 16px;
		}

		.vencidas-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.vencidas-title-wrapper {
			gap: 10px;
		}

		.vencidas-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.vencidas-title {
			font-size: 20px;
		}

		.vencidas-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.vencidas-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.table-card-modern {
			display: none;
		}

		.vencidas-mobile-list {
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

		.totalizadores {
			flex-direction: column;
			align-items: stretch;
			gap: 12px;
		}

		.total-item {
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
		}
	}
</style>

<div class="vencidas-page-modern">
	
	<div class="vencidas-header">
		<div class="vencidas-header-content">
			<div class="vencidas-title-wrapper">
				<div class="vencidas-title-icon">
					<i class="fa fa-clock-o"></i>
				</div>
				<h1 class="vencidas-title">Contas Vencidas</h1>
			</div>
			<p class="vencidas-subtitle">Contas a receber que estão com pagamento atrasado</p>
			<div class="vencidas-divider"></div>
		</div>
	</div>

	<div id="stats-container" class="stats-cards-container"></div>

	<div style="margin-bottom: 20px;">
		<div class="search-bar-compact">
			<i class="fa fa-search search-icon"></i>
			<input 
				type="text" 
				class="search-input-compact" 
				id="buscar" 
				placeholder="Buscar conta vencida..." 
				onkeyup="$('#tabela').DataTable().search(this.value).draw();"
			>
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="vencidas-mobile-list">
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
						<div class="modal-title-icon" style="background: rgba(239, 83, 80, 0.1);">
							<i class="fa fa-exclamation-circle" style="color: #ef5350;"></i>
						</div>
						<span id="nome_dados"></span>
					</div>
					<div class="modal-subtitle">
						Detalhes da conta vencida
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
							<div style="font-size: 14px; font-weight: 700; color: #ef5350; margin-bottom: 4px;">Conta Vencida!</div>
							<div style="font-size: 13px; color: #6c757d;">Esta conta está com pagamento em atraso.</div>
						</div>
					</div>
				</div>

				<!-- Informações do Valor -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-dollar" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Valor e Pagamento</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 14px; color: #6c757d; margin-bottom: 8px;">Valor Total</div>
							<div style="font-size: 28px; font-weight: 700; color: #ef5350;">R$ <span id="valor_dados"></span></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Forma de Pagamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="pgto_dados"></div>
						</div>
					</div>
				</div>

				<!-- Informações de Datas -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-calendar" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Datas</span>
					</div>

					<div class="row">
						<div class="col-md-4" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Lançamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="data_lanc_dados"></div>
						</div>
						<div class="col-md-4" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Vencimento</div>
							<div style="font-size: 14px; font-weight: 700; color: #ef5350;" id="data_venc_dados"></div>
						</div>
						<div class="col-md-4" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Pagamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="data_pgto_dados"></div>
						</div>
					</div>
				</div>

				<!-- Informações de Usuários -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-users" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Usuários</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Lançamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="usuario_lanc_dados"></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Baixa</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="usuario_baixa_dados"></div>
						</div>
					</div>
				</div>

				<!-- Informações do Cliente -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-user" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Cliente</span>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Nome</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="pessoa_dados"></div>
						</div>
					</div>
				</div>

				<!-- Comprovante -->
				<div style="text-align: center; margin-top: 24px;">
					<div style="font-size: 12px; color: #6c757d; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Comprovante</div>
					<a id="link_mostrar" target="_blank" title="Clique para abrir o arquivo!">	
						<img id="target_mostrar" style="max-width: 300px; border-radius: 12px; border: 3px solid #e0e0e0; cursor: pointer;">
					</a>
				</div>

			</div>
		</div>
	</div>
</div>






<!-- Modal Baixar-->
<div class="modal fade" id="modalBaixar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon" style="background: rgba(0, 216, 150, 0.1);">
							<i class="fa fa-check-square" style="color: #00d896;"></i>
						</div>
						<span id="titulo_baixar"></span>
					</div>
					<div class="modal-subtitle">
						Confirme o recebimento da conta vencida
					</div>
				</div>
				<button id="btn-fechar-baixar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-baixar">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Valor <span class="required">*</span></label>
								<input type="text" class="form-control" id="valor_baixar" name="valor" placeholder="R$ 0,00" required>    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma de Pagamento <span class="required">*</span></label>
								<select class="form-control" id="pgto_baixar" name="pgto" style="width:100%;" required> 
									<?php 
									$query = $pdo->query("SELECT * FROM formas_pgto");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>
								</select>       
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Recebido Em <span class="required">*</span></label>
								<input type="date" class="form-control" id="data_pgto_baixar" name="data_pgto"  value="<?php echo $data_hoje ?>" required>    
							</div> 	
						</div>
					</div>

					<input type="hidden" name="id" id="id_baixar">
					<div id="mensagem-baixar" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px;"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn-cancel" data-dismiss="modal">
						Cancelar
					</button>
					<button id="btn_baixar" type="submit" class="btn-submit" style="background: #00d896;">
						<i class="fa fa-check"></i>
						<span>Confirmar Recebimento</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>




<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listar();
	});

	function listar(){	
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {},
			dataType: "html",

			success:function(result){
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>

<script type="text/javascript">
	$("#form-baixar").submit(function () {
		$('#btn_baixar').hide();
		$('#mensagem-baixar').text('Baixando!!');

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/receber/baixar.php',
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-baixar').text('');
				$('#mensagem-baixar').removeClass()
				if (mensagem.trim() == "Baixado com Sucesso") {
					$('#btn-fechar-baixar').click();
					listar();          
				} else {
					$('#mensagem-baixar').addClass('text-danger')
					$('#mensagem-baixar').text(mensagem)
				}

				$('#btn_baixar').show();
			},

			cache: false,
			contentType: false,
			processData: false,
		});
	});
</script>

<script type="text/javascript">
	function cobrar(id, valor, data, telefone, descricao){
		var instancia = "<?=$instancia?>";
		var token = "<?=$token?>";

		if(instancia.trim() == "" || token.trim() == ""){
			alert('Insira um Token e Instancia Whatsapp nas configurações');
			return;
		}
		
		$.ajax({
			url: 'paginas/receber/gerar_cobranca.php',
			method: 'POST',
			data: {id, valor, data, telefone, descricao},
			dataType: "html",

			success:function(result){	
				alert('Cobrança Efetuada!');           	
			}
		});
	}
</script>
