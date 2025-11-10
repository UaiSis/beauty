<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$data_hoje = date('Y-m-d');

//verificar se ele tem a permissão de estar nessa página
if(@$planos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'planos';

?>

<style>
	/* Página de Planos Moderna */
	.planos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.planos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.planos-header-content {
		flex: 1;
	}

	.planos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.planos-title-icon {
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

	.planos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.planos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.planos-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.planos-actions {
		display: flex;
		gap: 12px;
	}

	.btn-novo-plano {
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
		text-decoration: none;
	}

	.btn-novo-plano:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
		color: #fff;
		text-decoration: none;
	}

	.btn-relatorio {
		background: #fff;
		color: #007A63;
		border: 2px solid #007A63;
		border-radius: 12px;
		padding: 12px 24px;
		font-weight: 600;
		font-size: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
		text-decoration: none;
	}

	.btn-relatorio:hover {
		background: #007A63;
		color: #fff;
		text-decoration: none;
	}

	/* Cards de Planos */
	.planos-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
		gap: 20px;
	}

	.plano-card {
		background: #fff;
		border-radius: 16px;
		padding: 24px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		transition: all 0.3s ease;
		position: relative;
		overflow: hidden;
	}

	.plano-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 8px 24px rgba(0, 122, 99, 0.15);
		border-color: #007A63;
	}

	.plano-card.pago {
		border-left: 4px solid #00d896;
	}

	.plano-card.pendente {
		border-left: 4px solid #ef5350;
	}

	.plano-card.cancelado {
		border-left: 4px solid #ff9800;
		opacity: 0.8;
	}

	.plano-status-badge {
		position: absolute;
		top: 16px;
		right: 16px;
		display: flex;
		align-items: center;
		gap: 6px;
		padding: 4px 12px;
		border-radius: 20px;
		font-size: 10px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.plano-status-badge.pago {
		background: rgba(0, 216, 150, 0.15);
		color: #007A63;
	}

	.plano-status-badge.pendente {
		background: rgba(239, 83, 80, 0.15);
		color: #ef5350;
	}

	.plano-status-badge.cancelado {
		background: rgba(255, 152, 0, 0.15);
		color: #ff9800;
	}

	.plano-cliente {
		font-size: 18px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 8px;
	}

	.plano-info {
		margin-bottom: 16px;
	}

	.plano-info-item {
		display: flex;
		align-items: center;
		gap: 8px;
		margin-bottom: 8px;
		font-size: 13px;
		color: #6c757d;
	}

	.plano-info-item i {
		width: 18px;
		color: #007A63;
		font-size: 14px;
	}

	.plano-info-item strong {
		color: #1a1a1a;
	}

	.plano-valor {
		font-size: 32px;
		font-weight: 700;
		color: #007A63;
		margin: 16px 0 20px 0;
	}

	.plano-actions {
		display: flex;
		gap: 8px;
		margin-top: 16px;
		padding-top: 16px;
		border-top: 1px solid #f0f0f0;
	}

	.plano-action-btn {
		flex: 1;
		padding: 8px 12px;
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

	.plano-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.plano-action-btn.edit:hover {
		background: rgba(0, 122, 99, 0.2);
	}

	.plano-action-btn.baixar {
		background: rgba(0, 216, 150, 0.1);
		color: #00d896;
	}

	.plano-action-btn.baixar:hover {
		background: rgba(0, 216, 150, 0.2);
	}

	.plano-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.plano-action-btn.delete:hover {
		background: rgba(239, 83, 80, 0.2);
	}

	.plano-action-btn.cancel {
		background: rgba(255, 152, 0, 0.1);
		color: #ff9800;
	}

	.plano-action-btn.cancel:hover {
		background: rgba(255, 152, 0, 0.2);
	}

	/* Melhorias no Modal */
	.modal-section-card {
		background: #f8f9fa;
		border-radius: 12px;
		padding: 20px;
		margin-bottom: 20px;
	}

	.modal-body .section-title {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.modal-body .section-title i {
		color: #007A63;
		font-size: 16px;
	}

	.modal-body .section-divider {
		border-top: none;
		margin: 0;
		padding: 0;
	}

	#listar_planos select {
		width: 100%;
		border-radius: 8px;
		border: 1px solid #e0e0e0;
		padding: 10px 14px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	#listar_planos select:focus {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
		outline: none;
	}

	@media (max-width: 768px) {
		.planos-page-modern {
			padding: 16px;
		}

		.planos-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.planos-title-wrapper {
			gap: 10px;
		}

		.planos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.planos-title {
			font-size: 20px;
		}

		.planos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.planos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.planos-actions {
			width: 100%;
			flex-direction: column;
		}

		.btn-novo-plano,
		.btn-relatorio {
			width: 100%;
			justify-content: center;
		}

		.planos-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

<div class="planos-page-modern">
	
	<div class="planos-header">
		<div class="planos-header-content">
			<div class="planos-title-wrapper">
				<div class="planos-title-icon">
					<i class="fa fa-star"></i>
				</div>
				<h1 class="planos-title">Planos e Assinaturas</h1>
			</div>
			<p class="planos-subtitle">Gerencie os planos de assinatura e recorrências dos clientes</p>
			<div class="planos-divider"></div>
		</div>
		<div class="planos-actions">
			<a href="rel/assinaturas_class.php" target="_blank" class="btn-relatorio">
				<i class="fa fa-file-pdf-o"></i> Relatório
			</a>
			<button onclick="inserir()" class="btn-novo-plano">
				<i class="fa fa-plus"></i> Novo Plano
			</button>
		</div>
</div>   

	<div class="planos-grid" id="listar">
		
</div>   
	
</div>






<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
			<div class="modal-body">

					<!-- Card 1: Informações do Plano -->
					<div class="modal-section-card">
						<div class="section-title" style="margin-bottom: 20px;">
							<i class="fa fa-star"></i> Informações do Plano
						</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
									<label>Assinatura</label>
									<select onchange="listarPlanos()" class="form-control sel2" id="grupo" name="grupo" style="width:100%;">							
									<?php 
									$query = $pdo->query("SELECT * FROM grupo_assinaturas ORDER BY id asc");
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

						<div class="col-md-6">
							<div class="form-group">
									<label>Plano</label>
									<div id="listar_planos"></div>
								</div> 	
								 </div>
							</div> 	
						</div>

					<!-- Card 2: Cliente -->
					<div class="modal-section-card">
						<div class="section-title" style="margin-bottom: 20px;">
							<i class="fa fa-user"></i> Informações do Cliente
					</div>

							<div class="form-group">
							<label>Cliente</label>
								<select class="form-control sel2" id="cliente" name="cliente" style="width:100%;" required>	
									<option value="">Selecionar Cliente</option>						
									<?php 
								$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
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

					<!-- Card 3: Valor e Frequência -->
					<div class="modal-section-card">
						<div class="section-title" style="margin-bottom: 20px;">
							<i class="fa fa-dollar"></i> Valor e Periodicidade
						</div>

						<div class="row">
						<div class="col-md-4">
							<div class="form-group">
									<label>Valor da Assinatura</label>
									<input type="text" class="form-control" id="valor" name="valor" placeholder="R$ 0,00">    
							</div> 	
						</div>

							<div class="col-md-4">
							<div class="form-group">
									<label>Frequência de Cobrança</label>
								<select class="form-control sel2" id="frequencia" name="frequencia" style="width:100%;" required>	
									<?php 
									$query = $pdo->query("SELECT * FROM frequencias ORDER BY id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
										foreach ($res[$i] as $key => $value){}
										echo '<option value="'.$res[$i]['dias'].'">'.$res[$i]['frequencia'].'</option>';
										}
									}
									 ?>
								</select>
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
									<label>Primeiro Vencimento</label>
									<input type="date" class="form-control" id="vencimento" name="vencimento" value="<?php echo date('Y-m-d') ?>">    
								</div> 	
							</div>
							</div> 	
						</div>
					
						<input type="hidden" name="id" id="id">
					<div id="mensagem" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px; display: none;"></div>
				</div>

				<div class="modal-footer">      
					<button type="submit" class="btn-salvar-comanda" style="width: 100%; justify-content: center;">
						<i class="fa fa-save"></i> Salvar Plano
					</button>
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
				<h4 class="modal-title"><span id="">Baixar Plano</span></h4>
				<button id="btn-fechar-baixar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form id="form-baixar">
				<div class="modal-body">						

					<div class="modal-section-card">
						<div class="section-title" style="margin-bottom: 20px;">
							<i class="fa fa-dollar"></i> Dados do Pagamento
						</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
									<label>Valor Pago</label>
									<input type="text" class="form-control" id="valor_baixar" name="valor" placeholder="R$ 0,00" required>    
							</div> 	
						</div>					

						<div class="col-md-4">
							<div class="form-group">
									<label>Forma de Pagamento</label>
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
									<label>Data do Pagamento</label>
									<input type="date" class="form-control" id="data_pgto_baixar" name="data_pgto" value="<?php echo $data_hoje ?>">    
								</div> 	
							</div>
							</div> 	
						</div>
					
					<input type="hidden" name="id" id="id_baixar">
					<div id="mensagem-baixar" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px; display: none;"></div>
				</div>

				<div class="modal-footer">      
					<button id="btn_baixar" type="submit" class="btn-salvar-comanda" style="width: 100%; justify-content: center;">
						<i class="fa fa-check-circle"></i> Confirmar Baixa
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
	listarPlanos();
		setTimeout(function() {
		  alterarValor();
		}, 500)
    $('.sel2').select2({
    	dropdownParent: $('#modalForm')
    });
});


function listarPlanos(){
	var grupo = $('#grupo').val();

	 $.ajax({
        url: 'paginas/' + pag + "/listar_planos.php",
        method: 'POST',
        data: {grupo},
        dataType: "html",

        success:function(result){
            $("#listar_planos").html(result);            
        }
    });
}

function alterarValor(){
	var item = $('#item').val();

	 $.ajax({
        url: 'paginas/' + pag + "/valor.php",
        method: 'POST',
        data: {item},
        dataType: "html",

        success:function(result){        	
            $("#valor").val(result);            
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
        url: 'paginas/' + pag + "/baixar.php",
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
</script>
