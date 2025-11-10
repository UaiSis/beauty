<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'clientes';

//verificar se ele tem a permissão de estar nessa página
if(@$clientes == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>

<style>
	/* Página de Clientes Moderna */
	.clientes-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.clientes-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.clientes-header-content {
		flex: 1;
	}

	.clientes-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.clientes-title-icon {
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

	.clientes-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.clientes-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.clientes-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-novo-cliente {
		background: #007A63;
		color: #fff;
		border: none;
		border-radius: 12px;
		padding: 12px 24px;
		font-weight: 600;
		font-size: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);
	}

	.btn-novo-cliente:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
		color: #fff;
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

	.client-cpf {
		font-size: 12px;
		color: #6c757d;
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

	.table-action-icon.edit {
		color: #007A63;
		background: rgba(0, 122, 99, 0.08);
	}

	.table-action-icon.edit:hover {
		background: rgba(0, 122, 99, 0.15);
		transform: scale(1.1);
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

	.table-action-icon.contract {
		color: #9c27b0;
		background: rgba(156, 39, 176, 0.08);
	}

	.table-action-icon.contract:hover {
		background: rgba(156, 39, 176, 0.15);
		transform: scale(1.1);
	}

	.table-action-icon.delete {
		color: #ef5350;
		background: rgba(239, 83, 80, 0.08);
	}

	.table-action-icon.delete:hover {
		background: rgba(239, 83, 80, 0.15);
		transform: scale(1.1);
	}

	/* Cards Mobile */
	.clientes-mobile-list {
		display: none;
	}

	.client-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
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

	.client-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.client-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.client-card-action-btn.whatsapp {
		background: rgba(37, 211, 102, 0.1);
		color: #25d366;
	}

	.client-card-action-btn.contract {
		background: rgba(156, 39, 176, 0.1);
		color: #9c27b0;
	}

	.client-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
		grid-column: span 2;
	}

	.client-card-action-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	/* Botão Flutuante Mobile */
	.btn-novo-cliente-mobile {
		display: none;
		position: fixed;
		bottom: 24px;
		right: 24px;
		width: 56px;
		height: 56px;
		border-radius: 50%;
		background: #007A63;
		color: #fff;
		border: none;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.4);
		font-size: 20px;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		z-index: 999;
		transition: all 0.3s ease;
	}

	.btn-novo-cliente-mobile:hover {
		transform: scale(1.1);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.4);
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

	.modal-body .form-group {
		margin-bottom: 20px;
	}

	.modal-body label {
		font-weight: 600;
		font-size: 13px;
		color: #495057;
		margin-bottom: 8px;
		text-transform: uppercase;
		letter-spacing: 0.3px;
	}

	.modal-body .form-control {
		border: 2px solid #e9ecef;
		border-radius: 10px;
		padding: 10px 16px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.modal-body .form-control:focus {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.modal-footer {
		border: none;
		padding: 16px 24px 24px;
		background: #f8f9fa;
		border-radius: 0 0 16px 16px;
	}

	.modal-footer .btn {
		padding: 12px 32px;
		border-radius: 10px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.modal-footer .btn-primary {
		background: #007A63;
		border: none;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);
	}

	.modal-footer .btn-primary:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
	}

	.modal-footer .btn-success {
		background: #00d896;
		border: none;
		box-shadow: 0 4px 12px rgba(0, 216, 150, 0.2);
	}

	.modal-footer .btn-success:hover {
		background: #00c085;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 216, 150, 0.3);
	}

	/* Responsivo */
	@media (max-width: 768px) {
		.clientes-page-modern {
			padding: 16px;
		}

		.clientes-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.clientes-title-wrapper {
			gap: 10px;
		}

		.clientes-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.clientes-title {
			font-size: 20px;
		}

		.clientes-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.clientes-divider {
			margin-left: 46px;
			width: 100px;
		}

		.btn-novo-cliente {
			display: none;
		}

		.btn-novo-cliente-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.clientes-mobile-list {
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

<div class="clientes-page-modern">
	
	<div class="clientes-header">
		<div class="clientes-header-content">
			<div class="clientes-title-wrapper">
				<div class="clientes-title-icon">
					<i class="fa fa-users"></i>
				</div>
				<h1 class="clientes-title">Clientes</h1>
			</div>
			<p class="clientes-subtitle">Gerencie seus clientes e histórico de atendimentos</p>
			<div class="clientes-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-novo-cliente">
			<i class="fa fa-plus"></i> Novo Cliente
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarClientes(0)" style="
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
				placeholder="Buscar cliente..." 
				onkeyup="listarClientes()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="clientes-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-novo-cliente-mobile">
		<i class="fa fa-plus"></i>
	</button>
	
</div>






<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form id="form_cli">
			<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>    
							</div> 	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" >    
							</div> 	
						</div>
					
					</div>

					<div class="row">

						<div class="col-md-7">
							<div class="form-group">
								<label for="exampleInputEmail1">Cpf</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" >    
							</div> 	
						</div>

							<div class="col-md-5">
							<div class="form-group">
								<label for="exampleInputEmail1">Cartões</label>
								<input type="number" class="form-control" id="cartao" name="cartao"  value="0">    
							</div> 	
						</div>
					</div>

							

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua X Número 1 Bairro xxx" >    
							</div> 	
						</div>


						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Nascimento</label>
								<input type="date" class="form-control" id="data_nasc" name="data_nasc" >    
							</div> 	
						</div>
						
					</div>


					
						<input type="hidden" name="id" id="id">

					<br>
					<small><div id="mensagem" align="center"></div></small>
				</div>

				<div class="modal-footer">      
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>

			
		</div>
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


				<br>

				<small><table class="table table-hover">
	<thead> 
	<tr> 
	<th>Último Serviço</th>	
	<th class="esc">Data</th> 
	<th class="esc">Valor</th> 	
	<th class="esc">OBS</th> 	
	
	</tr> 
	</thead> 
	<tbody>
	<td><span id="servico_dados_tab"></span></td>
	<td><span id="data_dados_tab"></span></td>
	<td><span id="valor_dados_tab"></span></td>
	<td><span id="obs_dados_tab"></span></td>
	</tbody>
	</table></small>

	<hr>

	<div id="listar-debitos">

	</div>
			

			</div>

			
		</div>
	</div>
</div>




<!-- Modal Contrato-->
<div class="modal fade" id="modalContrato" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_contrato"></span></h4>
				<button id="btn-fechar-conta" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>	
			<form id="form-contrato">	
			<div class="modal-body">

					<div>
						<textarea name="contrato" id="contrato" class="textareag"> </textarea>
					</div>
					<input type="hidden" name="id" id="id_contrato">

					<small><div id="mensagem-contrato" align="center"></div></small>
					
			</div>
			<div class="modal-footer">       
				<button type="submit" class="btn btn-primary">Gerar Relatório</button>
			</div>	
			</form>		

				

		</div>
	</div>
</div>






<!-- Modal Baixar-->
<div class="modal fade" id="modalBaixar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_baixar"></span></h4>
				<button id="btn-fechar-baixar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form id="form-baixar">
				<div class="modal-body">						


					<div class="row">

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Valor</label>
								<input type="text" class="form-control" id="valor_baixar" name="valor" placeholder="Valor" required>    
							</div> 	
						</div>					
						

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Forma de Pagamento</label>
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
								<label for="exampleInputEmail1">Pago Em</label>
								<input type="date" class="form-control" id="data_pgto_baixar" name="data_pgto"  value="<?php echo date('Y-m-d') ?>">    
							</div> 	
						</div>

						

					</div>

					

					

					
					<input type="hidden" name="id" id="id_baixar">
					<input type="hidden" name="cliente_baixar" id="cliente_baixar">

					<br>
					<small><div id="mensagem-baixar" align="center"></div></small>
				</div>

				<div class="modal-footer">      
					<button id="btn_baixar" type="submit" class="btn btn-success">Baixar</button>
				</div>
			</form>

			
		</div>
	</div>
</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

<script type="text/javascript">
	$(document).ready( function () {
		listarClientes()
	} );

	function inserir(){
		$('#titulo_inserir').text('Novo Cliente');
		$('#modalForm').modal('show');
		limparCampos();
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#endereco').val('');
		$('#data_nasc').val('');
		$('#cartao').val('0');
		$('#cpf').val('');
	}

</script>


<script type="text/javascript">
	
function listarClientes(pagina){

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

function listarDebitos(id){	 

    $.ajax({
        url: 'paginas/' + pag + "/listar-debitos.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(result){
            $("#listar-debitos").html(result);
           
        }
    });
}
</script>


<script type="text/javascript">
	
$("#form_cli").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/salvar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                $('#btn-fechar').click();

                var pagina = $("#pagina").val();
                listarClientes(pagina)          

            } else {

                $('#mensagem').addClass('text-danger')
                $('#mensagem').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});





function excluir(id){
    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {            
            if (mensagem.trim() == "Excluído com Sucesso") {                
                 var pagina = $("#pagina").val();
                listarClientes(pagina)              
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }

        },      

    });
}



	function listarTextoContrato(id){
	
    $.ajax({
        url: 'paginas/' + pag + "/texto-contrato.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(result){            
            nicEditors.findEditor("contrato").setContent(result);	          
        }
    });
}




$("#form-contrato").submit(function () {
	var id_emp = $('#id_contrato').val();
    event.preventDefault();
    nicEditors.findEditor('contrato').saveContent();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/salvar-contrato.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-contrato').text('');
            $('#mensagem-contrato').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {                
                   
                let a= document.createElement('a');
                a.target= '_blank';
                a.href= 'rel/contrato_servico_class.php?id=' + id_emp;
                a.click();  	 

            } else {

                $('#mensagem-contrato').addClass('text-danger')
                $('#mensagem-contrato').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});

</script>





<script type="text/javascript">

	$("#form-baixar").submit(function () {

		var cliente = $('#cliente_baixar').val()

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
                listarDebitos(cliente);          

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