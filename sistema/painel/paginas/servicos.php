<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'servicos';

if($tipo_comissao == 'Porcentagem'){
		$item_comissao = '(%)';
	}else{
		$item_comissao = '(R$)';
	}


	//verificar se ele tem a permissão de estar nessa página
if(@$servicos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
?>

<style>
	/* Página de Serviços Moderna */
	.servicos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.servicos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.servicos-header-content {
		flex: 1;
	}

	.servicos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.servicos-title-icon {
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

	.servicos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.servicos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.servicos-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-novo-servico {
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

	.btn-novo-servico:hover {
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

	/* Serviço com Foto */
	.servico-info-cell {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.servico-foto {
		width: 40px;
		height: 40px;
		border-radius: 10px;
		object-fit: cover;
		flex-shrink: 0;
		border: 2px solid #e9ecef;
	}

	.servico-name {
		font-weight: 600;
		color: #1a1a1a;
		line-height: 1.4;
	}

	/* Badge de Status */
	.status-badge {
		display: inline-block;
		padding: 4px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 600;
	}

	.status-badge.ativo {
		background: rgba(0, 216, 150, 0.15);
		color: #00a370;
	}

	.status-badge.inativo {
		background: rgba(158, 158, 158, 0.15);
		color: #616161;
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

	.table-action-icon.active {
		color: #00d896;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.active:hover {
		background: rgba(0, 216, 150, 0.15);
		transform: scale(1.1);
	}

	.table-action-icon.inactive {
		color: #9e9e9e;
		background: rgba(158, 158, 158, 0.08);
	}

	.table-action-icon.inactive:hover {
		background: rgba(158, 158, 158, 0.15);
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
	.servicos-mobile-list {
		display: none;
	}

	.servico-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.servico-card-header {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 12px;
	}

	.servico-card-foto {
		width: 48px;
		height: 48px;
		border-radius: 10px;
		object-fit: cover;
		flex-shrink: 0;
		border: 2px solid #e9ecef;
	}

	.servico-card-info {
		flex: 1;
		min-width: 0;
	}

	.servico-card-name {
		font-weight: 600;
		font-size: 15px;
		color: #1a1a1a;
		margin-bottom: 2px;
	}

	.servico-card-category {
		font-size: 13px;
		color: #6c757d;
	}

	.servico-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
		padding-top: 12px;
		border-top: 1px solid #f0f0f0;
	}

	.servico-card-detail-item {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.servico-card-detail-label {
		font-size: 11px;
		color: #adb5bd;
		text-transform: uppercase;
		font-weight: 600;
		letter-spacing: 0.5px;
	}

	.servico-card-detail-value {
		font-size: 13px;
		color: #495057;
		font-weight: 500;
	}

	.servico-card-actions {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 8px;
	}

	.servico-card-action-btn {
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

	.servico-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.servico-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.servico-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
		grid-column: span 2;
	}

	.servico-card-action-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}

	/* Botão Flutuante Mobile */
	.btn-novo-servico-mobile {
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

	.btn-novo-servico-mobile:hover {
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

	/* Responsivo */
	@media (max-width: 768px) {
		.servicos-page-modern {
			padding: 16px;
		}

		.servicos-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.servicos-title-wrapper {
			gap: 10px;
		}

		.servicos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.servicos-title {
			font-size: 20px;
		}

		.servicos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.servicos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.search-bar-compact {
			max-width: 100%;
		}

		.btn-novo-servico {
			display: none;
		}

		.btn-novo-servico-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.servicos-mobile-list {
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

<div class="servicos-page-modern">
	
	<div class="servicos-header">
		<div class="servicos-header-content">
			<div class="servicos-title-wrapper">
				<div class="servicos-title-icon">
					<i class="fa fa-scissors"></i>
				</div>
				<h1 class="servicos-title">Serviços</h1>
			</div>
			<p class="servicos-subtitle">Gerencie os serviços oferecidos e suas configurações</p>
			<div class="servicos-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-novo-servico">
			<i class="fa fa-plus"></i> Novo Serviço
		</button>
	</div>

	<div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="font-size: 13px; color: #6c757d; white-space: nowrap;">Mostrar</span>
			<select id="itens_por_pagina" onchange="listarServicos(0)" style="
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
				placeholder="Buscar serviço..." 
				onkeyup="listarServicos()"
			>
			<input type="hidden" id="pagina">
		</div>
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="servicos-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-novo-servico-mobile">
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
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>    
							</div> 	
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="exampleInputEmail1">Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" >    
							</div> 	
						</div>
					</div>


					<div class="row">					
						

						<div class="col-md-6">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Categoria</label>
								<select class="form-control sel2" id="categoria" name="categoria" style="width:100%;" > 

									<?php 
									$query = $pdo->query("SELECT * FROM cat_servicos ORDER BY id asc");
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

						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Dias Retorno</label>
								<input type="number" class="form-control" id="dias_retorno" name="dias_retorno" placeholder="Dias Retorno" >    
							</div> 	
						</div>

					

					</div>

					<div class="row">
							<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Comissão <?php echo $item_comissao ?></label>
								<input type="text" class="form-control" id="comissao" name="comissao" placeholder="Valor Comissão" >    
							</div> 	
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Tempo Extimado</label>
								<input type="number" class="form-control" id="tempo" name="tempo" placeholder="Minutos" >    
							</div> 	
						</div>
					</div>

					

						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Foto</label> 
									<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
								</div>						
							</div>
							<div class="col-md-4">
								<div id="divImg">
									<img src="img/servicos/sem-foto.jpg"  width="80px" id="target">									
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
					<div class="col-md-8">							
						<span><b>Categoria: </b></span>
						<span id="categoria_dados"></span>							
					</div>
					<div class="col-md-4">							
						<span><b>Valor: </b></span>
						<span id="valor_dados"></span>
					</div>					

				</div>


			

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>Dias Retorno: </b></span>
						<span id="dias_retorno_dados"></span>							
					</div>

					<div class="col-md-6">							
						<span><b>Ativo: </b></span>
						<span id="ativo_dados"></span>							
					</div>
						

				</div>


				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">							
						<span><b>Comissão: </b></span>
						<span id="comissao_dados"></span>							
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





<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		listarServicos();
		
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});

	function listarServicos(pagina){
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
		$('#titulo_inserir').text('Novo Serviço');
		$('#modalForm').modal('show');
		limparCampos();
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


