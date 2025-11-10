<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$produtos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'produtos';
?>

<style>
	/* Página de Produtos Moderna */
	.produtos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.produtos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.produtos-header-content {
		flex: 1;
	}

	.produtos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.produtos-title-icon {
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

	.produtos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.produtos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.produtos-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-novo-produto {
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

	.btn-novo-produto:hover {
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
		width: 280px;
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

	.table-modern tbody tr.low-stock {
		background: rgba(239, 83, 80, 0.05);
	}

	.table-modern tbody tr.low-stock:hover {
		background: rgba(239, 83, 80, 0.1);
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
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
	}

	.stock-badge.low {
		background: rgba(239, 83, 80, 0.12);
		color: #ef5350;
	}

	.stock-badge.normal {
		background: rgba(0, 216, 150, 0.12);
		color: #00a574;
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

	.table-action-icon.entrada {
		color: #00d896;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.entrada:hover {
		background: rgba(0, 216, 150, 0.15);
	}

	.table-action-icon.saida {
		color: #ff9800;
		background: rgba(255, 152, 0, 0.08);
	}

	.table-action-icon.saida:hover {
		background: rgba(255, 152, 0, 0.15);
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

	.foto-preview-container {
		text-align: center;
		margin-top: 16px;
	}

	.foto-preview {
		width: 120px;
		height: 120px;
		border-radius: 12px;
		object-fit: cover;
		border: 3px solid #e0e0e0;
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
	.products-mobile-list {
		display: none;
	}

	.product-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.product-card-mobile.low-stock {
		border-left: 4px solid #ef5350;
		background: rgba(239, 83, 80, 0.02);
	}

	.product-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.product-card-image {
		width: 60px;
		height: 60px;
		border-radius: 10px;
		object-fit: cover;
		border: 2px solid #e8f5f3;
	}

	.product-card-info {
		flex: 1;
	}

	.product-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
	}

	.product-card-category {
		font-size: 12px;
		color: #6c757d;
	}

	.product-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
		padding: 12px 0;
		border-top: 1px solid #f0f0f0;
		border-bottom: 1px solid #f0f0f0;
	}

	.product-card-detail-item {
		display: flex;
		flex-direction: column;
	}

	.product-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.product-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.product-card-actions {
		display: flex;
		gap: 8px;
	}

	.product-card-action-btn {
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

	.product-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.product-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.product-card-action-btn.entrada {
		background: rgba(0, 216, 150, 0.1);
		color: #00d896;
	}

	.product-card-action-btn.saida {
		background: rgba(255, 152, 0, 0.1);
		color: #ff9800;
	}

	.product-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.btn-novo-produto-mobile {
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

	.btn-novo-produto-mobile:hover {
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
		.produtos-page-modern {
			padding: 16px;
		}

		.produtos-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.produtos-title-wrapper {
			gap: 10px;
		}

		.produtos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.produtos-title {
			font-size: 20px;
		}

		.produtos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.produtos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.btn-novo-produto {
			display: none;
		}

		.btn-novo-produto-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.products-mobile-list {
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

<div class="produtos-page-modern">
	
	<div class="produtos-header">
		<div class="produtos-header-content">
			<div class="produtos-title-wrapper">
				<div class="produtos-title-icon">
					<i class="fa fa-cube"></i>
				</div>
				<h1 class="produtos-title">Produtos</h1>
			</div>
			<p class="produtos-subtitle">Gerencie o estoque e informações dos produtos</p>
			<div class="produtos-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-novo-produto">
			<i class="fa fa-plus"></i> Novo Produto
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarProdutos(0)" style="
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
				onkeyup="listarProdutos()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="products-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-novo-produto-mobile">
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
							<i class="fa fa-cube"></i>
						</div>
						<span id="titulo_inserir">Novo Produto</span>
					</div>
					<div class="modal-subtitle">
						Adicione um novo produto ao estoque, definindo preços e quantidades
					</div>
				</div>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">

					<!-- Nome e Categoria -->
					<div class="row">
						<div class="col-md-7">
							<div class="form-group">
								<label>Nome do Produto <span class="required">*</span></label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do produto" required>    
							</div> 	
						</div>

						<div class="col-md-5">
							<div class="form-group">
								<label>Categoria <span class="required">*</span></label>
								<select class="form-control sel2" id="categoria" name="categoria" style="width:100%;" required> 
									<?php 
									$query = $pdo->query("SELECT * FROM cat_produtos ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
										foreach ($res[$i] as $key => $value){}
										echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}else{
											echo '<option value="0">Cadastre uma Categoria</option>';
										}
									 ?>
								</select>   
							</div> 	
						</div>
					</div>

					<!-- Descrição -->
					<div class="form-group">
						<label>Descrição</label>
						<input maxlength="255" type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição do produto (até 255 caracteres)">    
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Valores e Estoque -->
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Valor de Compra</label>
								<input type="text" class="form-control" id="valor_compra" name="valor_compra" placeholder="R$ 0,00">    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Valor de Venda <span class="required">*</span></label>
								<input type="text" class="form-control" id="valor_venda" name="valor_venda" placeholder="R$ 0,00" required>    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Alerta de Estoque</label>
								<input type="number" class="form-control" id="nivel_estoque" name="nivel_estoque" placeholder="Nível mínimo">    
							</div> 	
						</div>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Foto do Produto -->
					<div class="row">
						<div class="col-md-8">						
							<div class="form-group"> 
								<label>Foto do Produto</label> 
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>						
						</div>
						<div class="col-md-4">
							<div class="foto-preview-container">
								<img src="img/produtos/sem-foto.jpg" class="foto-preview" id="target">
							</div>
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
						<span id="btn-text">Salvar Produto</span>
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
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon">
							<i class="fa fa-info-circle"></i>
						</div>
						<span id="nome_dados"></span>
					</div>
					<div class="modal-subtitle">
						Detalhes completos do produto
					</div>
				</div>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">

				<!-- Foto do Produto -->
				<div style="text-align: center; margin-bottom: 28px;">
					<img id="target_mostrar" style="width: 200px; height: 200px; border-radius: 12px; object-fit: cover; border: 3px solid #e0e0e0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
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
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="estoque_dados"></div>
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

				<div style="background: #fafbfc; border-radius: 12px; padding: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-bell" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Alerta de Estoque</span>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Nível Mínimo</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="nivel_estoque_dados"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>





<!-- Modal Saida-->
<div class="modal fade" id="modalSaida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon" style="background: rgba(255, 152, 0, 0.1);">
							<i class="fa fa-sign-out" style="color: #ff9800;"></i>
						</div>
						<span id="nome_saida"></span>
					</div>
					<div class="modal-subtitle">
						Registrar saída de estoque
					</div>
				</div>
				<button id="btn-fechar-saida" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<form id="form-saida">

					<div class="form-group">
						<label>Quantidade de Saída <span class="required">*</span></label>
						<input type="number" class="form-control" id="quantidade_saida" name="quantidade_saida" placeholder="Digite a quantidade" required>    
					</div>

					<div class="form-group">
						<label>Motivo da Saída <span class="required">*</span></label>
						<input type="text" class="form-control" id="motivo_saida" name="motivo_saida" placeholder="Ex: Venda, Uso interno, etc." required>    
					</div>
				
					<input type="hidden" id="id_saida" name="id">
					<input type="hidden" id="estoque_saida" name="estoque">

					<div id="mensagem-saida" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px;"></div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn-cancel" data-dismiss="modal">
					Cancelar
				</button>
				<button type="button" onclick="document.getElementById('form-saida').requestSubmit()" class="btn-submit" style="background: #ff9800;">
					<i class="fa fa-check"></i>
					<span>Registrar Saída</span>
				</button>
			</div>
		</div>
	</div>
</div>





<!-- Modal Entrada-->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon" style="background: rgba(0, 216, 150, 0.1);">
							<i class="fa fa-sign-in" style="color: #00d896;"></i>
						</div>
						<span id="nome_entrada"></span>
					</div>
					<div class="modal-subtitle">
						Registrar entrada de estoque
					</div>
				</div>
				<button id="btn-fechar-entrada" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<form id="form-entrada">

					<div class="form-group">
						<label>Quantidade de Entrada <span class="required">*</span></label>
						<input type="number" class="form-control" id="quantidade_entrada" name="quantidade_entrada" placeholder="Digite a quantidade" required>    
					</div>

					<div class="form-group">
						<label>Motivo da Entrada <span class="required">*</span></label>
						<input type="text" class="form-control" id="motivo_entrada" name="motivo_entrada" placeholder="Ex: Compra, Devolução, etc." required>    
					</div>
				
					<input type="hidden" id="id_entrada" name="id">
					<input type="hidden" id="estoque_entrada" name="estoque">

					<div id="mensagem-entrada" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px;"></div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn-cancel" data-dismiss="modal">
					Cancelar
				</button>
				<button type="button" onclick="document.getElementById('form-entrada').requestSubmit()" class="btn-submit" style="background: #00d896;">
					<i class="fa fa-check"></i>
					<span>Registrar Entrada</span>
				</button>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
		listarProdutos();
		
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});

	function listarProdutos(pagina){
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
		$('#titulo_inserir').text('Novo Produto');
		$('#btn-text').text('Salvar Produto');
		limparCampos();
		$('#modalForm').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#valor_compra').val('');
		$('#valor_venda').val('');
		$('#descricao').val('');		
		$('#foto').val('');
		$('#nivel_estoque').val('');
		$('#target').attr('src','img/produtos/sem-foto.jpg');
		$('#titulo_inserir').text('Novo Produto');
		$('#btn-text').text('Salvar Produto');
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
	

$("#form-saida").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/saida.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-saida').text('');
            $('#mensagem-saida').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                $('#btn-fechar-saida').click();
                listar();          

            } else {

                $('#mensagem-saida').addClass('text-danger')
                $('#mensagem-saida').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>





 <script type="text/javascript">
	

$("#form-entrada").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/entrada.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-entrada').text('');
            $('#mensagem-entrada').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                $('#btn-fechar-entrada').click();
                listar();          

            } else {

                $('#mensagem-entrada').addClass('text-danger')
                $('#mensagem-entrada').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});
</script>