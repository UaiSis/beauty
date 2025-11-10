<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'clientes_retorno';

//verificar se ele tem a permissão de estar nessa página
if(@$clientes_retorno == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>

<style>
	/* Página de Clientes Retorno Moderna */
	.clientes-retorno-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.clientes-retorno-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.clientes-retorno-header-content {
		flex: 1;
	}

	.clientes-retorno-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.clientes-retorno-title-icon {
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

	.clientes-retorno-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.clientes-retorno-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.clientes-retorno-divider {
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
		font-weight: 600;
		text-transform: uppercase;
		font-size: 12px;
		letter-spacing: 0.5px;
		padding: 16px 20px;
		border: none;
		white-space: nowrap;
	}

	.table-modern tbody td {
		padding: 16px 20px;
		vertical-align: middle;
		border-bottom: 1px solid #f0f0f0;
		font-size: 14px;
		color: #495057;
	}

	.table-modern tbody tr:last-child td {
		border-bottom: none;
	}

	.table-modern tbody tr:hover {
		background: rgba(0, 122, 99, 0.02);
	}

	/* Avatar de Cliente */
	.client-info-cell {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.client-avatar {
		width: 40px;
		height: 40px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 700;
		font-size: 14px;
		color: #fff;
		flex-shrink: 0;
		background: #007A63;
	}

	.client-name {
		font-weight: 600;
		color: #1a1a1a;
		line-height: 1.4;
	}

	.client-phone {
		font-size: 12px;
		color: #6c757d;
	}

	/* Badge de Dias */
	.dias-badge {
		display: inline-block;
		padding: 4px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 600;
	}

	.dias-badge.atrasado {
		background: rgba(239, 83, 80, 0.15);
		color: #ef5350;
	}

	/* Ações da Tabela */
	.table-actions-cell {
		display: flex;
		gap: 8px;
		align-items: center;
		justify-content: flex-start;
		flex-wrap: wrap;
	}

	.table-action-icon {
		width: 32px;
		height: 32px;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.2s ease;
		cursor: pointer;
		border: none;
		background: transparent;
		font-size: 14px;
		text-decoration: none;
	}

	.table-action-icon.view {
		color: #42a5f5;
		background: rgba(66, 165, 245, 0.08);
	}

	.table-action-icon.view:hover {
		background: rgba(66, 165, 245, 0.15);
		transform: scale(1.1);
	}

	.table-action-icon.whatsapp {
		color: #25d366;
		background: rgba(37, 211, 102, 0.08);
	}

	.table-action-icon.whatsapp:hover {
		background: rgba(37, 211, 102, 0.15);
		transform: scale(1.1);
	}

	/* Cards Mobile */
	.clientes-retorno-mobile-list {
		display: none;
	}

	.client-retorno-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		border-left: 4px solid #007A63;
	}

	.client-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.client-card-avatar {
		width: 48px;
		height: 48px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 700;
		font-size: 16px;
		color: #fff;
		background: #007A63;
		flex-shrink: 0;
	}

	.client-card-info {
		flex: 1;
		min-width: 0;
	}

	.client-card-name {
		font-weight: 600;
		font-size: 15px;
		color: #1a1a1a;
		margin-bottom: 2px;
	}

	.client-card-phone {
		font-size: 13px;
		color: #6c757d;
	}

	.client-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
		padding-top: 12px;
		border-top: 1px solid #f0f0f0;
	}

	.client-card-detail-item {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.client-card-detail-label {
		font-size: 11px;
		color: #adb5bd;
		text-transform: uppercase;
		font-weight: 600;
		letter-spacing: 0.5px;
	}

	.client-card-detail-value {
		font-size: 13px;
		color: #495057;
		font-weight: 500;
	}

	.client-card-detail-value.danger {
		color: #ef5350;
		font-weight: 600;
	}

	.client-card-actions {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 8px;
	}

	.client-card-action-btn {
		padding: 8px 12px;
		border-radius: 8px;
		font-size: 13px;
		font-weight: 600;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		transition: all 0.2s ease;
		border: none;
		cursor: pointer;
		text-decoration: none;
	}

	.client-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.client-card-action-btn.whatsapp {
		background: rgba(37, 211, 102, 0.1);
		color: #25d366;
	}

	.client-card-action-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	/* Empty State */
	.empty-state {
		text-align: center;
		padding: 60px 20px;
		background: #fff;
		border-radius: 16px;
	}

	.empty-state i {
		font-size: 48px;
		color: #dee2e6;
		margin-bottom: 16px;
	}

	.empty-state p {
		font-size: 16px;
		color: #6c757d;
		font-weight: 500;
		margin: 0;
	}

	.empty-state small {
		font-size: 13px;
		color: #adb5bd;
		margin-top: 8px;
		display: block;
	}

	/* Estilos Modernos para Modais */
	.modal-content {
		border-radius: 16px;
		border: none;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
	}

	.modal-header {
		background: linear-gradient(135deg, #007A63 0%, #00a370 100%);
		color: #fff;
		border-radius: 16px 16px 0 0;
		padding: 20px 24px;
		border: none;
	}

	.modal-header .modal-title {
		font-weight: 700;
		font-size: 20px;
		color: #fff;
	}

	.modal-header .close {
		color: #fff;
		opacity: 0.9;
		text-shadow: none;
		font-size: 28px;
		font-weight: 300;
	}

	.modal-header .close:hover {
		opacity: 1;
		color: #fff;
	}

	.modal-body {
		padding: 24px;
	}

	/* Responsivo */
	@media (max-width: 768px) {
		.clientes-retorno-page-modern {
			padding: 16px;
		}

		.clientes-retorno-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.clientes-retorno-title-wrapper {
			gap: 10px;
		}

		.clientes-retorno-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.clientes-retorno-title {
			font-size: 20px;
		}

		.clientes-retorno-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.clientes-retorno-divider {
			margin-left: 46px;
			width: 100px;
		}

		.table-card-modern {
			display: none;
		}

		.clientes-retorno-mobile-list {
			display: block;
		}

		.modal-dialog {
			margin: 8px;
		}

		.modal-body {
			padding: 20px;
		}
	}
</style>

<div class="clientes-retorno-page-modern">
	
	<div class="clientes-retorno-header">
		<div class="clientes-retorno-header-content">
			<div class="clientes-retorno-title-wrapper">
				<div class="clientes-retorno-title-icon">
					<i class="fa fa-history"></i>
				</div>
				<h1 class="clientes-retorno-title">Clientes com Retorno</h1>
			</div>
			<p class="clientes-retorno-subtitle">Clientes que ultrapassaram a data de retorno prevista</p>
			<div class="clientes-retorno-divider"></div>
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="clientes-retorno-mobile-list">
		<div id="listar-mobile"></div>
	</div>
	
</div>

<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dados"></span></h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			
			<div class="modal-body">

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>Telefone: </b></span>
						<span id="telefone_dados"></span>
					</div>	

					<div class="col-md-6">							
						<span><b>Cartões: </b></span>
						<span id="cartoes_dados"></span>							
					</div>
									

				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>Cadastro: </b></span>
						<span id="data_cad_dados"></span>							
					</div>
					<div class="col-md-6">							
						<span><b>Nascimento: </b></span>
						<span id="data_nasc_dados"></span>
					</div>					

				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>Data Retorno: </b></span>
						<span id="retorno_dados"></span>							
					</div>
					<div class="col-md-6">							
						<span><b>Último Serviço: </b></span>
						<span id="servico_dados"></span>
					</div>					

				</div>


				


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					
					<div class="col-md-12">							
						<span><b>Endereço: </b></span>
						<span id="endereco_dados"></span>
					</div>					

				</div>


			

			</div>

			
		</div>
	</div>
</div>





<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>



