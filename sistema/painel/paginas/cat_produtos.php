<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$cat_produtos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'cat_produtos';
?>

<style>
	/* Página de Categorias Moderna */
	.categorias-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.categorias-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.categorias-header-content {
		flex: 1;
	}

	.categorias-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.categorias-title-icon {
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

	.categorias-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.categorias-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.categorias-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-nova-categoria {
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

	.btn-nova-categoria:hover {
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
		width: 180px;
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

	.categoria-name {
		font-weight: 600;
		color: #1a1a1a;
		font-size: 14px;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.categoria-icon {
		width: 36px;
		height: 36px;
		background: rgba(0, 122, 99, 0.1);
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 16px;
		flex-shrink: 0;
	}

	.produtos-count-badge {
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

	.table-action-icon.delete {
		color: #ef5350;
		background: rgba(239, 83, 80, 0.08);
	}

	.table-action-icon.delete:hover {
		background: rgba(239, 83, 80, 0.15);
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

	.form-control::placeholder {
		color: #adb5bd;
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
	.categorias-mobile-list {
		display: none;
	}

	.categoria-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.categoria-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
		padding-bottom: 12px;
		border-bottom: 1px solid #f0f0f0;
	}

	.categoria-card-icon {
		width: 48px;
		height: 48px;
		background: rgba(0, 122, 99, 0.1);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 20px;
		flex-shrink: 0;
	}

	.categoria-card-info {
		flex: 1;
	}

	.categoria-card-name {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
	}

	.categoria-card-count {
		font-size: 12px;
		color: #6c757d;
	}

	.categoria-card-actions {
		display: flex;
		gap: 8px;
	}

	.categoria-card-action-btn {
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

	.categoria-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.categoria-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.btn-nova-categoria-mobile {
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

	.btn-nova-categoria-mobile:hover {
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
		.categorias-page-modern {
			padding: 16px;
		}

		.categorias-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.categorias-title-wrapper {
			gap: 10px;
		}

		.categorias-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.categorias-title {
			font-size: 20px;
		}

		.categorias-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.categorias-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.btn-nova-categoria {
			display: none;
		}

		.btn-nova-categoria-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.categorias-mobile-list {
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

<div class="categorias-page-modern">
	
	<div class="categorias-header">
		<div class="categorias-header-content">
			<div class="categorias-title-wrapper">
				<div class="categorias-title-icon">
					<i class="fa fa-tags"></i>
				</div>
				<h1 class="categorias-title">Categorias de Produtos</h1>
			</div>
			<p class="categorias-subtitle">Organize seus produtos em categorias para facilitar o gerenciamento</p>
			<div class="categorias-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-nova-categoria">
			<i class="fa fa-plus"></i> Nova Categoria
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarCategorias(0)" style="
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
				placeholder="Buscar categoria..." 
				onkeyup="listarCategorias()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="categorias-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-nova-categoria-mobile">
		<i class="fa fa-plus"></i>
	</button>
	
</div>






<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon">
							<i class="fa fa-tag"></i>
						</div>
						<span id="titulo_inserir">Nova Categoria</span>
					</div>
					<div class="modal-subtitle">
						Crie uma nova categoria para organizar seus produtos
					</div>
				</div>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<form id="form">
				<div class="modal-body">
					<div class="form-group">
						<label>Nome da Categoria <span class="required">*</span></label>
						<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome da categoria" required>    
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
						<span id="btn-text">Salvar Categoria</span>
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
		listarCategorias();
	});

	function listarCategorias(pagina){
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
		$('#titulo_inserir').text('Nova Categoria');
		$('#btn-text').text('Salvar Categoria');
		limparCampos();
		$('#modalForm').modal('show');
	}

	function limparCampos(){
		$('#nome').val('');
		$('#id').val('');
		$('#titulo_inserir').text('Nova Categoria');
		$('#btn-text').text('Salvar Categoria');
	}
</script>
