<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'cargos';

//verificar se ele tem a permissão de estar nessa página
if(@$cargos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

?>

<style>
	/* Página de Cargos Moderna */
	.cargos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.cargos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.cargos-header-content {
		flex: 1;
	}

	.cargos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.cargos-title-icon {
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

	.cargos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.cargos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.cargos-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-novo-cargo {
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

	.btn-novo-cargo:hover {
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

	/* Cargo com Ícone */
	.cargo-info-cell {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.cargo-icon {
		width: 40px;
		height: 40px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		flex-shrink: 0;
		background: #007A63;
	}

	.cargo-icon i {
		font-size: 18px;
	}

	.cargo-name {
		font-weight: 600;
		color: #1a1a1a;
		line-height: 1.4;
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

	.table-action-icon.delete {
		color: #ef5350;
		background: rgba(239, 83, 80, 0.08);
	}

	.table-action-icon.delete:hover {
		background: rgba(239, 83, 80, 0.15);
		transform: scale(1.1);
	}

	/* Cards Mobile */
	.cargos-mobile-list {
		display: none;
	}

	.cargo-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.cargo-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.cargo-card-icon {
		width: 48px;
		height: 48px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		background: #007A63;
		flex-shrink: 0;
	}

	.cargo-card-icon i {
		font-size: 22px;
	}

	.cargo-card-info {
		flex: 1;
		min-width: 0;
	}

	.cargo-card-name {
		font-weight: 600;
		font-size: 15px;
		color: #1a1a1a;
	}

	.cargo-card-actions {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 8px;
	}

	.cargo-card-action-btn {
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

	.cargo-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.cargo-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.cargo-card-action-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	/* Botão Flutuante Mobile */
	.btn-novo-cargo-mobile {
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

	.btn-novo-cargo-mobile:hover {
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

	.modal-body .btn {
		width: 100%;
		padding: 12px 32px;
		border-radius: 10px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.modal-body .btn-primary {
		background: #007A63;
		border: none;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);
	}

	.modal-body .btn-primary:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
	}

	/* Seletor de Ícones */
	.icon-selector-grid {
		display: grid;
		grid-template-columns: repeat(8, 1fr);
		gap: 8px;
		padding: 12px;
		background: #f8f9fa;
		border-radius: 10px;
		border: 2px solid #e9ecef;
	}

	.icon-selector-item {
		width: 100%;
		aspect-ratio: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
		background: #fff;
		border: 2px solid #e9ecef;
		cursor: pointer;
		transition: all 0.2s ease;
		font-size: 18px;
		color: #6c757d;
	}

	.icon-selector-item:hover {
		border-color: #007A63;
		background: rgba(0, 122, 99, 0.05);
		transform: scale(1.05);
		color: #007A63;
	}

	.icon-selector-item.active {
		border-color: #007A63;
		background: #007A63;
		color: #fff;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.3);
	}

	.icon-selector-item i {
		font-size: 20px;
	}

	/* Responsivo */
	@media (max-width: 768px) {
		.cargos-page-modern {
			padding: 16px;
		}

		.cargos-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.cargos-title-wrapper {
			gap: 10px;
		}

		.cargos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.cargos-title {
			font-size: 20px;
		}

		.cargos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.cargos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.btn-novo-cargo {
			display: none;
		}

		.btn-novo-cargo-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.cargos-mobile-list {
			display: block;
		}

		.modal-dialog {
			margin: 8px;
		}

		.modal-body {
			padding: 20px;
		}

		.icon-selector-grid {
			grid-template-columns: repeat(4, 1fr);
		}
	}
</style>

<div class="cargos-page-modern">
	
	<div class="cargos-header">
		<div class="cargos-header-content">
			<div class="cargos-title-wrapper">
				<div class="cargos-title-icon">
					<i class="fa fa-briefcase"></i>
				</div>
				<h1 class="cargos-title">Cargos</h1>
			</div>
			<p class="cargos-subtitle">Gerencie os cargos disponíveis no sistema</p>
			<div class="cargos-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-novo-cargo">
			<i class="fa fa-plus"></i> Novo Cargo
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarCargos(0)" style="
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
				placeholder="Buscar cargo..." 
				onkeyup="listarCargos()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="cargos-mobile-list">
		<div id="listar-mobile"></div>
</div>

	<button onclick="inserir()" class="btn-novo-cargo-mobile">
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
			
			<form id="form">
			<div class="modal-body">

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Nome do Cargo</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o Nome do Cargo" required>    
							</div> 	
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Ícone do Cargo</label>
								<input type="hidden" name="icone" id="icone" value="fa-scissors">
								
								<div class="icon-selector-grid">
									<?php 
									$icones_disponiveis = [
										['icon' => 'fa-scissors', 'title' => 'Barbeiro/Tesoura'],
										['icon' => 'fa-female', 'title' => 'Cabeleireira'],
										['icon' => 'fa-hand-paper-o', 'title' => 'Manicure/Pedicure'],
										['icon' => 'fa-paint-brush', 'title' => 'Maquiador(a)'],
										['icon' => 'fa-user', 'title' => 'Atendente'],
										['icon' => 'fa-desktop', 'title' => 'Recepcionista'],
										['icon' => 'fa-briefcase', 'title' => 'Gerente'],
										['icon' => 'fa-user-md', 'title' => 'Administrador'],
										['icon' => 'fa-star', 'title' => 'Profissional Destaque'],
										['icon' => 'fa-trophy', 'title' => 'Especialista'],
										['icon' => 'fa-graduation-cap', 'title' => 'Aprendiz'],
										['icon' => 'fa-users', 'title' => 'Equipe'],
										['icon' => 'fa-shield', 'title' => 'Supervisor'],
										['icon' => 'fa-heart', 'title' => 'Estética'],
										['icon' => 'fa-magic', 'title' => 'Visagista']
									];

									foreach($icones_disponiveis as $index => $icone_data){
										$active_class = ($index == 0) ? 'active' : '';
										echo '<div class="icon-selector-item '.$active_class.'" data-icon="'.$icone_data['icon'].'" title="'.$icone_data['title'].'">';
										echo '<i class="fa '.$icone_data['icon'].'"></i>';
										echo '</div>';
									}
									?>
								</div>
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



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listarCargos();
	});

	function listarCargos(pagina){
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
		$('#titulo_inserir').text('Novo Cargo');
		$('#modalForm').modal('show');
		limparCampos();
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#icone').val('fa-scissors');
		
		// Resetar seleção visual
		$('.icon-selector-item').removeClass('active');
		$('.icon-selector-item[data-icon="fa-scissors"]').addClass('active');
	}

	// Gerenciar seleção de ícones
	$(document).on('click', '.icon-selector-item', function(){
		$('.icon-selector-item').removeClass('active');
		$(this).addClass('active');
		var iconClass = $(this).data('icon');
		$('#icone').val(iconClass);
	});
</script>


