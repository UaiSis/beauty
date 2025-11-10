<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'funcionarios';

//verificar se ele tem a permissão de estar nessa página
if(@$funcionarios == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>

<style>
	/* Página de Funcionários Moderna */
	.funcionarios-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.funcionarios-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.funcionarios-header-content {
		flex: 1;
	}

	.funcionarios-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.funcionarios-title-icon {
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

	.funcionarios-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.funcionarios-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.funcionarios-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-novo-funcionario {
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

	.btn-novo-funcionario:hover {
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
		width: 300px;
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

	.table-action-icon:hover[style*="color: #ff9800"] {
		background: rgba(255, 152, 0, 0.15) !important;
	}

	.table-action-icon:hover[style*="color: #9c27b0"] {
		background: rgba(156, 39, 176, 0.15) !important;
	}

	.table-action-icon:hover[style*="color: #25d366"] {
		background: rgba(37, 211, 102, 0.15) !important;
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

	.foto-preview {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		object-fit: cover;
		border: 3px solid #e0e0e0;
		margin-top: 12px;
	}

	/* Cards Mobile */
	.funcionarios-mobile-list {
		display: none;
	}

	.funcionario-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.funcionario-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.funcionario-card-avatar {
		width: 48px;
		height: 48px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid #e8f5f3;
	}

	.funcionario-card-info {
		flex: 1;
	}

	.funcionario-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 2px;
	}

	.funcionario-card-email {
		font-size: 12px;
		color: #6c757d;
	}

	.funcionario-card-badge {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
		padding: 4px 10px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 600;
	}

	.funcionario-card-details {
		display: flex;
		gap: 16px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.funcionario-card-detail-item {
		flex: 1;
	}

	.funcionario-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.funcionario-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.funcionario-card-actions {
		display: flex;
		gap: 8px;
	}

	.funcionario-card-action-btn {
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

	.funcionario-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.funcionario-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.funcionario-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.funcionario-card-action-btn.active {
		background: rgba(0, 216, 150, 0.1);
		color: #00d896;
	}

	.funcionario-card-action-btn.inactive {
		background: rgba(108, 117, 125, 0.1);
		color: #6c757d;
	}

	.funcionario-card-action-btn i {
		font-size: 14px;
	}

	.btn-novo-funcionario-mobile {
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

	.btn-novo-funcionario-mobile:hover {
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
		.funcionarios-page-modern {
			padding: 16px;
		}

		.funcionarios-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.funcionarios-title-wrapper {
			gap: 10px;
		}

		.funcionarios-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.funcionarios-title {
			font-size: 20px;
		}

		.funcionarios-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.funcionarios-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.btn-novo-funcionario {
			display: none;
		}

		.btn-novo-funcionario-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.funcionarios-mobile-list {
			display: block;
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

<div class="funcionarios-page-modern">
	
	<div class="funcionarios-header">
		<div class="funcionarios-header-content">
			<div class="funcionarios-title-wrapper">
				<div class="funcionarios-title-icon">
					<i class="fa fa-user-md"></i>
				</div>
				<h1 class="funcionarios-title">Funcionários</h1>
			</div>
			<p class="funcionarios-subtitle">Gerencie os profissionais que prestam atendimento aos clientes</p>
			<div class="funcionarios-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-novo-funcionario">
			<i class="fa fa-plus"></i> Novo Funcionário
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarFuncionarios(0)" style="
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
				placeholder="Buscar funcionário..." 
				onkeyup="listarFuncionarios()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="funcionarios-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-novo-funcionario-mobile">
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
							<i class="fa fa-user-md"></i>
						</div>
						<span id="titulo_inserir">Novo Funcionário</span>
					</div>
					<div class="modal-subtitle">
						Adicione um novo funcionário ao sistema, definindo suas informações e dados de pagamento
					</div>
				</div>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">

					<!-- Nome e Email -->
					<div class="form-group">
						<label>Nome completo <span class="required">*</span></label>
						<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome completo" required>    
					</div>

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

					<!-- Cargo e Atendimento -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Cargo <span class="required">*</span></label>
								<select class="form-control sel2" id="cargo" name="cargo" style="width:100%;"> 
									<?php 
									$query = $pdo->query("SELECT * FROM cargos where nome != 'Administrador' ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';
										}
									}else{
										echo '<option value="0">Cadastre um Cargo</option>';
									}
									?>
								</select>   
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Faz Atendimento <span class="required">*</span></label>
								<select class="form-control" name="atendimento" id="atendimento">
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
								</select>  
							</div>
						</div>
					</div>

					<!-- Intervalo e Comissão -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Intervalo entre Atendimentos (minutos)</label>
								<input type="number" class="form-control" id="intervalo" name="intervalo" placeholder="Ex: 30" required>    
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Comissão <small>(Opcional - se diferente do padrão)</small></label>
								<input type="number" class="form-control" id="comissao" name="comissao" placeholder="Valor R$ ou %">    
							</div>
						</div>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Dados Pix -->
					<div class="form-group">
						<label>Dados para Pagamento (Pix)</label>
						<p style="font-size: 12px; color: #6c757d; margin-bottom: 12px;">Informe os dados da chave Pix para pagamento de comissões</p>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Tipo de Chave Pix</label>
								<select class="form-control" name="tipo_chave" id="tipo_chave">
									<option value="">Selecionar Tipo</option>
									<option value="CPF">CPF</option>
									<option value="Telefone">Telefone</option>
									<option value="Email">Email</option>
									<option value="Código">Chave Aleatória</option>
									<option value="CNPJ">CNPJ</option>
								</select>  
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Chave Pix</label>
								<input type="text" class="form-control" id="chave_pix" name="chave_pix" placeholder="Digite a chave Pix"> 
							</div>
						</div>
					</div>

				<!-- Divisor -->
				<div class="form-divider"></div>

				<!-- Foto do Perfil -->
				<div class="form-group">
					<label>Foto do perfil</label>
					<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
					<div style="text-align: center; margin-top: 16px;">
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
					<span id="btn-text">Criar Funcionário</span>
				</button>
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
					<div class="col-md-8">							
						<span><b>Email: </b></span>
						<span id="email_dados"></span>							
					</div>
					<div class="col-md-4">							
						<span><b>Senha: </b></span>
						<span id="senha_dados"></span>
					</div>					

				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>CPF: </b></span>
						<span id="cpf_dados"></span>							
					</div>
					<div class="col-md-6">							
						<span><b>Telefone: </b></span>
						<span id="telefone_dados"></span>
					</div>					

				</div>




				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>Nível: </b></span>
						<span id="nivel_dados"></span>							
					</div>
					<div class="col-md-6">							
						<span><b>Ativo: </b></span>
						<span id="ativo_dados"></span>
					</div>		
								

				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
						
					<div class="col-md-6">							
						<span><b>Cadastro: </b></span>
						<span id="data_dados"></span>
					</div>	

						<div class="col-md-6">							
						<span><b>Atendimento: </b></span>
						<span id="atendimento_dados"></span>
					</div>				

				</div>



				<div class="row" style="border-bottom: 1px solid #cac7c7;">
						
					<div class="col-md-6">							
						<span><b>Tipo Chave: </b></span>
						<span id="tipo_chave_dados"></span>
					</div>	

						<div class="col-md-6">							
						<span><b>Chave Pix: </b></span>
						<span id="chave_pix_dados"></span>
					</div>				

				</div>




				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					
					<div class="col-md-12">							
						<span><b>Endereço: </b></span>
						<span id="endereco_dados"></span>
					</div>					

				</div>


				<div class="row">
					<div class="col-md-12" align="center">		
						<img width="250px" id="target_mostrar">	
					</div>					
				</div>


			</div>

			
		</div>
	</div>
</div>






<!-- Modal Horarios-->
<div class="modal fade" id="modalHorarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_horarios"></span></h4>
				<button id="btn-fechar-horarios" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<form id="form-horarios">
				<div class="row">
					<div class="col-md-4">						
						<div class="form-group">
							<label for="exampleInputEmail1">Horário</label>
							<input type="time" class="form-control" id="horario" name="horario" required>    
						</div> 	
					</div>

					<div class="col-md-4">						
						<button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
					</div>

					<input type="hidden" name="id" id="id_horarios">

				</div>
				</form>

				<hr>
				<div class="" id="listar-horarios">
					
				</div>

				<br>
				<small><div id="mensagem-horarios"></div></small>

			</div>

			
		</div>
	</div>
</div>





<!-- Modal Dias-->
<div class="modal fade" id="modalDias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:80%" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dias"></span></h4>
				<button id="btn-fechar-dias" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				
				<form id="form-dias">
				<div class="row">
					<div class="col-md-2">						
						<div class="form-group">
							<label for="exampleInputEmail1">Dia</label>
							<select class="form-control" id="dias" name="dias"  required> 
                                    <option value="Segunda-Feira">Segunda-Feira</option>    
                                    <option value="Terça-Feira">Terça-Feira</option>
                                    <option value="Quarta-Feira">Quarta-Feira</option>
                                    <option value="Quinta-Feira">Quinta-Feira</option>
                                    <option value="Sexta-Feira">Sexta-Feira</option>
                                    <option value="Sábado">Sábado</option>
                                    <option value="Domingo">Domingo</option>
                                                    

                                </select>      
						</div>

                      

                   
					</div>

                      <div class="col-md-4" align="center">  
                      <label for="exampleInputEmail1">(Início) Jornada de Trabalho (Final)</label>                  
                            <div class="row" style="margin-top: 2px">
                                <div class="col-md-6">
 <input type="time" name="inicio" class="form-control" id="inicio" required>
                                </div>

                                <div class="col-md-6">
                                    
                                     <input type="time" name="final" class="form-control" id="final" required>

                                </div>
                            </div>                         
                         
                    </div>

                    <div class="col-md-4" align="center">  
                      <label for="exampleInputEmail1">(Início) Intervalo de Almoço (Final)</label>                  
                            <div class="row" style="margin-top: 2px">
                                <div class="col-md-6">
 <input type="time" name="inicio_almoco" class="form-control" id="inicio_almoco" >
                                </div>

                                <div class="col-md-6">
                                    
                                     <input type="time" name="final_almoco" class="form-control" id="final_almoco" >

                                </div>
                            </div>                         
                         
                    </div>

					<div class="col-md-2">						
						<button type="submit" class="btn btn-primary" style="margin-top:22px">Salvar</button>
					</div>

					<input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">

                    <input type="hidden" name="id_d" id="id_d">

				</div>
				</form>

<small><div id="mensagem-dias"></div></small>

<big>
<div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">
	
</div>
</big>


			</div>

			
		</div>
	</div>
</div>






<!-- Modal Servicos-->
<div class="modal fade" id="modalServicos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_servico"></span></h4>
				<button id="btn-fechar-servico" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<form id="form-servico">
				<div class="row">
					<div class="col-md-6">						
						<div class="form-group">
							<label for="exampleInputEmail1">Serviço</label>
							<select class="form-control sel3" id="servico" name="servico" style="width:100%;" required> 

									<?php 
									$query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>        
						</div> 	
					</div>

					<div class="col-md-4">						
						<button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
					</div>

					<input type="hidden" name="id" id="id_servico">

				</div>
				</form>

				<hr>
				<div class="" id="listar-servicos">
					
				</div>

				<br>
				<small><div id="mensagem-servicos"></div></small>

			</div>

			
		</div>
	</div>
</div>






<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listarFuncionarios();
		
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});

	function listarFuncionarios(pagina){
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
		$('#titulo_inserir').text('Novo Funcionário');
		$('#btn-text').text('Criar Funcionário');
		limparCampos();
		$('#modalForm').modal('show');
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
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
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
	

$("#form-dias").submit(function () {

	var func = $("#id_dias").val();
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/inserir-dias.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-dias').text('');
            $('#mensagem-dias').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                //$('#btn-fechar-horarios').click();
                $("#id_d").val('');   
                listarDias(func);


            } else {

                $('#mensagem-dias').addClass('text-danger')
                $('#mensagem-dias').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>


<script type="text/javascript">
	function listarDias(func){
		
    $.ajax({
        url: 'paginas/' + pag + "/listar-dias.php",
        method: 'POST',
        data: {func},
        dataType: "html",

        success:function(result){
            $("#listar-dias").html(result);
            $('#mensagem-dias-excluir').text('');
        }
    });
}

</script>

<script type="text/javascript">


$("#form-servico").submit(function () {

	var func = $("#id_servico").val();
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/inserir-servico.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-servicos').text('');
            $('#mensagem-servicos').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                //$('#btn-fechar-horarios').click();
                listarServicos(func);          

            } else {

                $('#mensagem-servicos').addClass('text-danger')
                $('#mensagem-servicos').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>


<script type="text/javascript">
	function listarServicos(func){
		
    $.ajax({
        url: 'paginas/' + pag + "/listar-servicos.php",
        method: 'POST',
        data: {func},
        dataType: "html",

        success:function(result){
            $("#listar-servicos").html(result);
            $('#mensagem-servico-excluir').text('');
        }
    });
}

</script>




