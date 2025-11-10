<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$usuarios == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'usuarios';
?>

<style>
	/* Página de Usuários Moderna */
	.usuarios-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.usuarios-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.usuarios-header-content {
		flex: 1;
	}

	.usuarios-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.usuarios-title-icon {
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

	.usuarios-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.usuarios-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.usuarios-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-novo-usuario {
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

	.btn-novo-usuario:hover {
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

	.user-avatar {
		width: 44px;
		height: 44px;
		border-radius: 50%;
		object-fit: cover;
		margin-right: 14px;
		background: #e0e0e0;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 700;
		font-size: 15px;
		color: #fff;
		flex-shrink: 0;
	}

	.user-avatar img {
		width: 100%;
		height: 100%;
		border-radius: 50%;
		object-fit: cover;
	}

	.user-info-cell {
		display: flex;
		align-items: center;
	}

	.user-name {
		font-weight: 600;
		color: #1a1a1a;
		font-size: 14px;
	}

	.nivel-badge {
		display: inline-block;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
		border: none;
	}

	.nivel-badge.admin {
		background: rgba(0, 122, 99, 0.12);
		color: #007A63;
	}

	.nivel-badge.barbeiro {
		background: rgba(66, 165, 245, 0.12);
		color: #1976d2;
	}

	.nivel-badge.cabeleireira {
		background: rgba(156, 39, 176, 0.12);
		color: #9c27b0;
	}

	.nivel-badge.recepcionista {
		background: rgba(255, 152, 0, 0.12);
		color: #f57c00;
	}

	.nivel-badge.gerente {
		background: rgba(0, 216, 150, 0.12);
		color: #00a574;
	}

	.nivel-badge.default {
		background: rgba(108, 117, 125, 0.12);
		color: #6c757d;
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

	.table-action-icon.edit {
		color: #007A63;
		background: rgba(0, 122, 99, 0.08);
	}

	.table-action-icon.edit:hover {
		background: rgba(0, 122, 99, 0.15);
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

	.table-action-icon.active {
		color: #00d896;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.active:hover {
		background: rgba(0, 216, 150, 0.15);
	}

	.table-action-icon.inactive {
		color: #6c757d;
		background: rgba(108, 117, 125, 0.08);
	}

	.table-action-icon.inactive:hover {
		background: rgba(108, 117, 125, 0.15);
	}

	.table-action-icon.permissions {
		color: #9c27b0;
		background: rgba(156, 39, 176, 0.08);
	}

	.table-action-icon.permissions:hover {
		background: rgba(156, 39, 176, 0.15);
	}

	/* Modal Moderno Estilo Shadcn */
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

	.form-control::placeholder {
		color: #adb5bd;
	}

	.form-divider {
		height: 1px;
		background: #f0f0f0;
		margin: 28px 0;
	}

	.form-section-title {
		font-size: 14px;
		font-weight: 600;
		color: #6c757d;
		margin-bottom: 16px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.select-cards-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 16px;
		margin-bottom: 24px;
	}

	.select-card {
		border: 2px solid #e0e0e0;
		border-radius: 10px;
		padding: 20px;
		cursor: pointer;
		transition: all 0.3s ease;
		background: #fff;
	}

	.select-card:hover {
		border-color: #007A63;
		background: rgba(0, 122, 99, 0.02);
	}

	.select-card.selected {
		border-color: #007A63;
		background: rgba(0, 122, 99, 0.05);
	}

	.select-card-icon {
		width: 36px;
		height: 36px;
		background: rgba(0, 122, 99, 0.1);
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 18px;
		margin-bottom: 12px;
	}

	.select-card-title {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 6px;
	}

	.select-card-desc {
		font-size: 12px;
		color: #6c757d;
		line-height: 1.5;
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

	.foto-preview-container {
		text-align: center;
		margin-top: 16px;
	}

	.foto-preview {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		object-fit: cover;
		border: 3px solid #e0e0e0;
	}

	/* Cards Mobile */
	.users-mobile-list {
		display: none;
	}

	.user-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.user-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.user-card-avatar {
		width: 48px;
		height: 48px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid #e8f5f3;
	}

	.user-card-info {
		flex: 1;
	}

	.user-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 2px;
	}

	.user-card-email {
		font-size: 12px;
		color: #6c757d;
	}

	.user-card-badge {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
		padding: 4px 10px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 600;
	}

	.user-card-details {
		display: flex;
		gap: 16px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.user-card-detail-item {
		flex: 1;
	}

	.user-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.user-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.user-card-actions {
		display: flex;
		gap: 8px;
	}

	.user-card-action-btn {
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

	.user-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.user-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.user-card-action-btn.more {
		background: rgba(156, 39, 176, 0.1);
		color: #9c27b0;
	}

	.user-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.user-card-action-btn.active {
		background: rgba(0, 216, 150, 0.1);
		color: #00d896;
	}

	.user-card-action-btn.inactive {
		background: rgba(108, 117, 125, 0.1);
		color: #6c757d;
	}

	.btn-novo-usuario-mobile {
		background: #007A63;
		color: #fff;
		border: none;
		border-radius: 50%;
		width: 56px;
		height: 56px;
		display: none;
		align-items: center;
		justify-content: center;
		font-size: 20px;
		position: fixed;
		bottom: 24px;
		right: 24px;
		box-shadow: 0 4px 16px rgba(0, 122, 99, 0.3);
		z-index: 1000;
		transition: all 0.3s ease;
	}

	.btn-novo-usuario-mobile:hover {
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

	@media (max-width: 768px) {
		.usuarios-page-modern {
			padding: 16px;
		}

		.usuarios-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.usuarios-title-wrapper {
			gap: 10px;
		}

		.usuarios-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.usuarios-title {
			font-size: 20px;
		}

		.usuarios-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.usuarios-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.btn-novo-usuario {
			display: none;
		}

		.btn-novo-usuario-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.users-mobile-list {
			display: block;
		}

		.select-cards-grid {
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

<div class="usuarios-page-modern">
	
	<div class="usuarios-header">
		<div class="usuarios-header-content">
			<div class="usuarios-title-wrapper">
				<div class="usuarios-title-icon">
					<i class="fa fa-users"></i>
				</div>
				<h1 class="usuarios-title">Usuários</h1>
			</div>
			<p class="usuarios-subtitle">Gerencie os usuários que têm acesso ao sistema</p>
			<div class="usuarios-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-novo-usuario">
			<i class="fa fa-plus"></i> Novo Usuário
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarUsuarios(0)" style="
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
				placeholder="Buscar usuário..." 
				onkeyup="listarUsuarios()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="users-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-novo-usuario-mobile">
		<i class="fa fa-plus"></i>
	</button>
	
</div>






<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon">
							<i class="fa fa-user"></i>
						</div>
						<span id="titulo_inserir">Novo Usuário</span>
					</div>
					<div class="modal-subtitle">
						Adicione um novo usuário ao sistema, definindo suas informações e nível de acesso
					</div>
				</div>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">

					<!-- Nome Completo -->
					<div class="form-group">
						<label>Nome completo <span class="required">*</span></label>
						<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" required>    
					</div>

					<!-- Email -->
					<div class="form-group">
						<label>Email <span class="required">*</span></label>
						<input type="email" class="form-control" id="email" name="email" placeholder="email@exemplo.com" required>    
					</div>

					<!-- CPF e Telefone -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>CPF</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">    
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">    
							</div>
						</div>
					</div>

					<!-- Endereço -->
					<div class="form-group">
						<label>Endereço</label>
						<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua, número, bairro, cidade">    
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Faz Atendimento -->
					<div class="form-group">
						<label>Faz Atendimento</label>
						<p style="font-size: 12px; color: #6c757d; margin-bottom: 12px;">Este usuário irá realizar atendimentos aos clientes?</p>
						<select class="form-control" name="atendimento" id="atendimento">
							<option value="Sim">Sim</option>
							<option value="Não">Não</option>
						</select>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Nível de Acesso -->
					<div class="form-group">
						<label>Nível de acesso <span class="required">*</span></label>
						<p style="font-size: 12px; color: #6c757d; margin-bottom: 12px;">Defina o nível de permissão deste usuário</p>
						
						<div class="select-cards-grid">
							<?php 
							$query = $pdo->query("SELECT * FROM cargos ORDER BY nome asc");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							$total_reg = @count($res);
							if($total_reg > 0){
								foreach($res as $cargo){
									$nome_cargo = $cargo['nome'];
									$nome_lower = strtolower($nome_cargo);
									$descricao = 'Nível de acesso personalizado com permissões específicas';
									$icone = 'fa-user-circle';
									
									// Definir ícone e descrição baseado no cargo
									if($nome_cargo == 'Administrador' || strpos($nome_lower, 'admin') !== false){
										$descricao = 'Acesso completo ao sistema, pode gerenciar usuários, configurações e todas as funcionalidades';
										$icone = 'fa-star';
									} 
									else if(strpos($nome_lower, 'barb') !== false){
										$descricao = 'Profissional de atendimento, pode visualizar e gerenciar seus agendamentos e serviços';
										$icone = 'fa-scissors';
									}
									else if(strpos($nome_lower, 'cabel') !== false || strpos($nome_lower, 'hair') !== false){
										$descricao = 'Profissional de atendimento, pode gerenciar agendamentos e realizar serviços';
										$icone = 'fa-cut';
									}
									else if(strpos($nome_lower, 'gerente') !== false || strpos($nome_lower, 'manager') !== false){
										$descricao = 'Acesso gerencial, pode visualizar relatórios e gerenciar operações do dia a dia';
										$icone = 'fa-briefcase';
									}
									else if(strpos($nome_lower, 'manicure') !== false || strpos($nome_lower, 'pedicure') !== false){
										$descricao = 'Profissional especializado, pode gerenciar seus agendamentos e serviços';
										$icone = 'fa-hand-paper-o';
									}
									else if(strpos($nome_lower, 'recep') !== false){
										$descricao = 'Atendimento ao cliente, pode gerenciar agendamentos e cadastros básicos';
										$icone = 'fa-phone';
									}
									else if(strpos($nome_lower, 'atend') !== false){
										$descricao = 'Profissional de atendimento, pode realizar serviços e gerenciar agendamentos';
										$icone = 'fa-user-md';
									}
									
									echo '<div class="select-card" onclick="selectNivel(this, \''.$nome_cargo.'\')">
										<div class="select-card-icon">
											<i class="fa '.$icone.'"></i>
										</div>
										<div class="select-card-title">'.$nome_cargo.'</div>
										<div class="select-card-desc">'.$descricao.'</div>
									</div>';
								}
							}else{
								echo '<p style="color: #6c757d;">Nenhum cargo cadastrado. Cadastre um cargo primeiro.</p>';
							}
							?>
						</div>
						<input type="hidden" id="cargo" name="cargo" required>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Foto do Perfil -->
					<div class="form-group">
						<label>Foto do perfil</label>
						<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
						<div class="foto-preview-container">
							<img src="img/perfil/sem-foto.jpg" class="foto-preview" id="target">
						</div>
					</div>

					<input type="hidden" name="id" id="id">
					<div id="mensagem" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px; display: none;"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn-cancel" data-dismiss="modal">
						Cancelar
					</button>
					<button type="submit" class="btn-submit">
						<i class="fa fa-check"></i>
						<span id="btn-text">Criar Usuário</span>
					</button>
				</div>
			</form>

			
		</div>
	</div>
</div>





<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dados"></span></h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				
				<!-- Foto do Perfil -->
				<div style="text-align: center; margin-bottom: 24px;">
					<img id="target_mostrar" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #007A63; box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);">
				</div>

				<!-- Informações em Cards -->
				<div class="modal-section-card">
					<div class="section-title" style="margin-bottom: 20px;">
						<i class="fa fa-id-card"></i> Informações de Contato
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Email</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="email_dados"></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Telefone</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="telefone_dados"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">CPF</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="cpf_dados"></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Senha</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="senha_dados"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Endereço</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="endereco_dados"></div>
						</div>
					</div>
				</div>

				<div class="modal-section-card">
					<div class="section-title" style="margin-bottom: 20px;">
						<i class="fa fa-cog"></i> Informações do Sistema
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Nível de Acesso</div>
							<div><span class="nivel-badge" id="nivel_dados"></span></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Data de Cadastro</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="data_dados"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">Faz Atendimento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="atendimento_dados"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>








<!-- Modal -->
<div class="modal fade" id="modalPermissoes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					Usuário: <span id="nome-usuario"></span>

					<span style="position:absolute; right:35px">
						<input class="form-check-input" type="checkbox" id="input-todos" onchange="marcarTodos()">
						<label class="" >Marcar Todos</label>
					</span>

				</h4>

				
				<button id="btn-fechar-permissoes" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">

				<div class="row" id="listar-permissoes">

				</div>

				<div class="row">	
					<div class="col-md-12">						
				
						
					</div>	
				</div>	

				<br>
				<input type="hidden" name="id" id="id-usuario"> 
				<small><div id="mensagem-permissao" align="center" class="mt-3"></div></small>		

				

				


			</div>	


			

		</div>
	</div>
</div>






<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listarUsuarios();
		
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});

	function listarUsuarios(pagina){
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

	function inserir(){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Novo Usuário');
		$('#btn-text').text('Criar Usuário');
		limparCampos();
		$('#modalForm').modal('show');
	}

	function selectNivel(element, nivel){
		$('.select-card').removeClass('selected');
		$(element).addClass('selected');
		$('#cargo').val(nivel);
	}
</script>


<script type="text/javascript">
	function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("#foto").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>






<script type="text/javascript">
	function listarPermissoes(id){
		$.ajax({
			url: 'paginas/' + pag + "/listar-permissoes.php",
			method: 'POST',
			data: {id},
			dataType: "html",

			success:function(result){
				$("#listar-permissoes").html(result);
				$('#mensagem-permissao').text('');
				//$('#input-todos').prop('checked', false);
			}
		});
	}


	function marcarTodos(){
		let checkbox = document.getElementById('input-todos');
		var usuario = $('#id-usuario').val();
		
		if(checkbox.checked) {
		    adicionarPermissoes(usuario);		    
		} else {
		    limparPermissoes(usuario);
		}
	}



	function adicionarPermissoes(id){
		$.ajax({
			url: 'paginas/' + pag + "/add-permissoes.php",
			method: 'POST',
			data: {id},
			dataType: "html",

			success:function(result){
				listarPermissoes(id)
			}
		});	
	}


	function limparPermissoes(id){
		$.ajax({
			url: 'paginas/' + pag + "/limpar-permissoes.php",
			method: 'POST',
			data: {id},
			dataType: "html",

			success:function(result){
				listarPermissoes(id)
			}
		});	
	}


	function adicionarPermissao(idpermissao, idusuario){
		
		$.ajax({
			url: 'paginas/' + pag + "/add-permissao.php",
			method: 'POST',
			data: {idpermissao, idusuario},
			dataType: "html",

			success:function(result){
				listarPermissoes(idusuario)
			}
		});	
	}

</script>