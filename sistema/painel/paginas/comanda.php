<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'comanda';


//verificar se ele tem a permissão de estar nessa página
if(@$comanda == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}


$data_hoje = date('Y-m-d');
$data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data_hoje)));

$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";

if($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11'){
	$dia_final_mes = '30';
}else if($mes_atual == '2'){
	$dia_final_mes = '28';
}else{
	$dia_final_mes = '31';
}

$data_final_mes = $ano_atual."-".$mes_atual."-".$dia_final_mes;


?>

<style>
	.comanda-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.comanda-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.comanda-header-content {
		flex: 1;
	}

	.comanda-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.comanda-title-icon {
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

	.comanda-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.comanda-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.comanda-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-nova-comanda {
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

	.btn-nova-comanda:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
		color: #fff;
	}

	.filters-card {
		background: #fff;
		border-radius: 16px;
		padding: 20px 24px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		margin-bottom: 24px;
	}

	.filters-row {
		display: flex;
		align-items: center;
		gap: 20px;
		flex-wrap: wrap;
	}

	.filter-group {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.filter-group i {
		color: #007A63;
		font-size: 16px;
	}

	.filter-group input[type="date"] {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 8px 12px;
		font-size: 13px;
		transition: all 0.3s ease;
	}

	.filter-group input[type="date"]:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.quick-filters {
		display: flex;
		gap: 8px;
		align-items: center;
		padding: 8px 16px;
		background: #f8f9fa;
		border-radius: 10px;
	}

	.quick-filter-btn {
		color: #6c757d;
		text-decoration: none;
		font-size: 13px;
		font-weight: 500;
		padding: 4px 12px;
		border-radius: 6px;
		transition: all 0.3s ease;
	}

	.quick-filter-btn:hover {
		color: #007A63;
		background: rgba(0, 122, 99, 0.08);
		text-decoration: none;
	}

	.filter-divider {
		color: #dee2e6;
		margin: 0 4px;
	}

	.comandas-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
		gap: 20px;
		margin-top: 20px;
	}

	.comanda-card {
		background: #fff;
		border-radius: 16px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		transition: all 0.3s ease;
		position: relative;
		cursor: pointer;
	}

	.comanda-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 8px 24px rgba(0, 122, 99, 0.15);
		border-color: #007A63;
	}

	.comanda-card.aberta {
		border-left: 4px solid #00d896;
	}

	.comanda-card.fechada {
		border-left: 4px solid #6c757d;
	}

	.comanda-status-badge {
		position: absolute;
		top: 16px;
		right: 16px;
		display: flex;
		align-items: center;
		gap: 6px;
		padding: 4px 12px;
		border-radius: 20px;
		font-size: 11px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.comanda-status-badge.aberta {
		background: rgba(0, 216, 150, 0.1);
		color: #007A63;
	}

	.comanda-status-badge.fechada {
		background: rgba(108, 117, 125, 0.1);
		color: #6c757d;
	}

	.comanda-status-icon {
		width: 8px;
		height: 8px;
		border-radius: 50%;
	}

	.comanda-status-badge.aberta .comanda-status-icon {
		background: #00d896;
	}

	.comanda-status-badge.fechada .comanda-status-icon {
		background: #6c757d;
	}

	.comanda-value {
		font-size: 28px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 12px 0 8px 0;
	}

	.comanda-cliente {
		font-size: 14px;
		color: #6c757d;
		margin-bottom: 16px;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.comanda-cliente i {
		color: #007A63;
		font-size: 13px;
	}

	.comanda-actions {
		display: flex;
		gap: 8px;
		margin-top: 16px;
		padding-top: 16px;
		border-top: 1px solid #f0f0f0;
	}

	.comanda-action-btn {
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
	}

	.comanda-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.comanda-action-btn.edit:hover {
		background: rgba(0, 122, 99, 0.2);
	}

	.comanda-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.comanda-action-btn.delete:hover {
		background: rgba(239, 83, 80, 0.2);
	}

	.comanda-action-btn.print {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.comanda-action-btn.print:hover {
		background: rgba(66, 165, 245, 0.2);
	}

	/* Modal Moderno */
	.modal-content {
		border-radius: 16px;
		border: none;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
	}

	.modal-header {
		background: #007A63;
		border-radius: 16px 16px 0 0;
		padding: 24px 28px;
		border: none;
	}

	.modal-header .modal-title {
		color: #fff;
		font-size: 20px;
		font-weight: 700;
		margin: 0;
	}

	.modal-header .close {
		color: #fff;
		opacity: 0.9;
		text-shadow: none;
		font-size: 32px;
		font-weight: 300;
		margin-top: -4px;
	}

	.modal-header .close:hover {
		opacity: 1;
	}

	.modal-body {
		padding: 28px;
	}

	.modal-body label {
		font-size: 13px;
		font-weight: 600;
		color: #495057;
		margin-bottom: 8px;
	}

	.modal-body .form-control {
		border-radius: 8px;
		border: 1px solid #e0e0e0;
		padding: 10px 14px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.modal-body .form-control:focus {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.modal-footer {
		border-top: 1px solid #f0f0f0;
		padding: 20px 28px;
		background: #fafafa;
		border-radius: 0 0 16px 16px;
	}

	.btn-salvar-comanda {
		background: #007A63;
		color: #fff;
		border: none;
		border-radius: 10px;
		padding: 12px 28px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.btn-salvar-comanda:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 16px rgba(0, 122, 99, 0.3);
	}

	.btn-fechar-comanda {
		background: #6c757d;
		color: #fff;
		border: none;
		border-radius: 10px;
		padding: 12px 28px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.btn-fechar-comanda:hover {
		background: #5a6268;
	}

	.section-divider {
		border-top: 2px solid #f0f0f0;
		margin: 20px 0;
		padding-top: 20px;
	}

	.section-title {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 16px;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.section-title i {
		color: #007A63;
	}

	/* Área de totais do modal */
	.totais-area {
		background: #f8f9fa;
		border-radius: 12px;
		padding: 20px;
	}

	.total-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 12px 0;
		border-bottom: 1px solid #e0e0e0;
	}

	.total-item:last-child {
		border-bottom: none;
		font-weight: 700;
		font-size: 18px;
		color: #007A63;
		padding-top: 16px;
		margin-top: 8px;
		border-top: 2px solid #007A63;
	}

	.total-label {
		font-size: 14px;
		color: #6c757d;
		font-weight: 500;
	}

	.total-value {
		font-size: 16px;
		color: #1a1a1a;
		font-weight: 600;
	}

	/* Tabelas dentro do modal */
	#listar_servicos table,
	#listar_produtos table {
		margin: 0;
	}

	#listar_servicos table th,
	#listar_produtos table th {
		background: #007A63;
		color: #fff;
		font-size: 12px;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		padding: 10px 12px;
		border: none;
	}

	#listar_servicos table tbody tr,
	#listar_produtos table tbody tr {
		border-bottom: 1px solid #e0e0e0;
	}

	#listar_servicos table tbody tr:hover,
	#listar_produtos table tbody tr:hover {
		background: rgba(0, 122, 99, 0.05);
	}

	#listar_servicos table td,
	#listar_produtos table td {
		padding: 12px;
		font-size: 13px;
		vertical-align: middle;
	}

	.table-action-btn {
		background: transparent;
		border: none;
		color: #ef5350;
		cursor: pointer;
		padding: 4px 8px;
		border-radius: 6px;
		transition: all 0.3s ease;
	}

	.table-action-btn:hover {
		background: rgba(239, 83, 80, 0.1);
	}

	@media (max-width: 768px) {
		.comanda-page-modern {
			padding: 16px;
		}

		.comanda-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.comanda-title-wrapper {
			gap: 10px;
		}

		.comanda-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.comanda-title {
			font-size: 20px;
		}

		.comanda-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.comanda-divider {
			margin-left: 46px;
			width: 100px;
		}

		.filters-row {
			flex-direction: column;
			align-items: stretch;
		}

		.comandas-grid {
			grid-template-columns: 1fr;
		}

		.quick-filters {
			width: 100%;
			justify-content: center;
		}
	}
</style>

<div class="comanda-page-modern">
	
	<div class="comanda-header">
		<div class="comanda-header-content">
			<div class="comanda-title-wrapper">
				<div class="comanda-title-icon">
					<i class="fa fa-file-text"></i>
			</div>
				<h1 class="comanda-title">Comandas</h1>
			</div>
			<p class="comanda-subtitle">Gerencie as comandas de atendimento e vendas do dia</p>
			<div class="comanda-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-nova-comanda">
			<i class="fa fa-plus"></i> Nova Comanda
		</button>
		</div>

	<div class="filters-card">
		<div class="filters-row">
			<div class="filter-group">
				<i class="fa fa-calendar"></i>
				<input type="date" class="form-control" name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_hoje ?>" required>
				<span style="color: #dee2e6; margin: 0 4px;">até</span>
				<input type="date" class="form-control" name="data-final" id="data-final-caixa" value="<?php echo $data_hoje ?>" required>
			</div>

			<div class="quick-filters">
				<a class="quick-filter-btn" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')">Ontem</a>
				<span class="filter-divider">/</span>
				<a class="quick-filter-btn" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')">Hoje</a>
				<span class="filter-divider">/</span>
				<a class="quick-filter-btn" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')">Mês</a>
			</div>

			<div class="quick-filters">
				<a class="quick-filter-btn" href="#" onclick="buscarContas('')">Todas</a>
				<span class="filter-divider">/</span>
				<a class="quick-filter-btn" href="#" onclick="buscarContas('Aberta')">Abertas</a>
				<span class="filter-divider">/</span>
				<a class="quick-filter-btn" href="#" onclick="buscarContas('Fechada')">Fechadas</a>
			</div>
		</div>

		<input type="hidden" id="buscar-contas" value="Aberta">
	</div>

	
	<div class="comandas-grid" id="listar">

	</div>
	
</div>






<!-- Modal Inserir-->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" style="">
	<div class="modal-dialog modal-xl" role="document" style="width:100%; " id="modal_scrol">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_comanda">Nova Comanda</span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form id="form_salvar">
				<div class="modal-body">
					<div class="row">
						<!-- Coluna Principal - Esquerda -->
						<div class="col-md-8" style="border-right: 1px solid #e0e0e0; padding-right: 24px;">

							<!-- Informações Básicas -->
					<div class="row">
							<div class="col-md-6">			
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

						<div class="col-md-6">						
							<div class="form-group"> 
										<label>Observações</label> 
										<input maxlength="1000" type="text" class="form-control" name="obs" id="obs2" placeholder="Observações gerais"> 
									</div>
							</div>						
						</div>

							<!-- Seção de Serviços -->
							<div class="section-divider" style="margin-top: 24px;">
								<div class="section-title">
									<i class="fa fa-scissors"></i> Adicionar Serviços
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group"> 
								<label>Serviço</label> 
											<select class="form-control sel2" id="servico" name="servico" style="width:100%;"> 
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

									<div class="col-md-5">
        <div class="form-group"> 
            <label>Profissional</label> 
											<select class="form-control sel2" id="funcionario" name="funcionario" style="width: 100%;"> 
                <?php 
                $query = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY nome asc");
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

									<div class="col-md-1" style="display: flex; align-items: flex-end;">
										<button type="button" onclick="inserirServico()" class="btn-salvar-comanda" style="padding: 10px 16px; width: 100%; min-width: auto;">
											<i class="fa fa-plus"></i>
										</button>
    </div> 
</div>

								<div id="listar_servicos" style="background: #f8f9fa; padding: 12px; border-radius: 8px; min-height: 60px; margin-top: 12px;">
								</div>
							</div>

							<!-- Seção de Produtos -->
							<div class="section-divider" style="margin-top: 24px;">
								<div class="section-title">
									<i class="fa fa-shopping-bag"></i> Adicionar Produtos
						</div>

								<div class="row">
									<div class="col-md-5">
        <div class="form-group"> 
											<label>Produto</label> 
											<select class="form-control sel2" id="produto" name="produto" style="width:100%;" onchange="listarServicos()"> 
                <?php 
                $query = $pdo->query("SELECT * FROM produtos where estoque > 0 ORDER BY nome asc");
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

									<div class="col-md-2">
        <div class="form-group"> 
            <label>Quantidade</label> 
											<input type="number" class="form-control" name="quantidade" id="quantidade" value="1" min="1"> 
        </div>						
    </div>

									<div class="col-md-4">
            <div class="form-group"> 
											<label>Vendedor</label> 
											<select class="form-control sel2" id="funcionario2" name="funcionario2" style="width:100%;" onchange="listarServicos()"> 
                    <option value="0">Nenhum</option>
                    <?php 
                    $query = $pdo->query("SELECT * FROM usuarios where nivel != 'Administrador' ORDER BY nome asc");
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

									<div class="col-md-1" style="display: flex; align-items: flex-end;">
										<button type="button" onclick="inserirProduto()" class="btn-salvar-comanda" style="padding: 10px 16px; width: 100%; min-width: auto;">
											<i class="fa fa-plus"></i>
										</button>
        </div> 
    </div>

								<div id="listar_produtos" style="background: #f8f9fa; padding: 12px; border-radius: 8px; min-height: 60px; margin-top: 12px;">
								</div>
</div>

						</div>

						<!-- Coluna Lateral - Direita (Resumo) -->
						<div class="col-md-4">
						<div class="totais-area">
							<div class="section-title" style="margin-bottom: 20px; font-size: 14px;">
								<i class="fa fa-calculator"></i> Resumo da Comanda
						</div>

							<div class="total-item" style="border-bottom: 1px solid #e0e0e0;">
								<span class="total-label">Valor Serviços</span>
								<input type="text" class="form-control total-value" name="valor_servicos_agd" id="valor_servicos_agd" readonly style="border: none; background: transparent; text-align: right; font-weight: 600; color: #1a1a1a; padding: 0; width: auto;">
						</div>

							<div class="total-item" style="border-bottom: 1px solid #e0e0e0;">
								<span class="total-label">Valor Produtos</span>
								<input type="text" class="form-control total-value" name="valor_produtos_agd" id="valor_produtos_agd" readonly style="border: none; background: transparent; text-align: right; font-weight: 600; color: #1a1a1a; padding: 0; width: auto;">
						</div>

							<div class="total-item" style="border-bottom: none; font-weight: 700; font-size: 18px; color: #007A63; padding-top: 16px; margin-top: 8px; border-top: 2px solid #007A63;">
								<span>TOTAL</span>
								<input type="text" class="form-control" name="total_comanda" id="total_comanda" readonly style="border: none; background: transparent; text-align: right; font-weight: 700; font-size: 20px; color: #007A63; padding: 0; width: auto;">
						</div>

							<div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #e0e0e0;">						
							<div class="form-group"> 
									<label>Data de Pagamento</label> 
								<input type="date" class="form-control inputs_form" name="data_pgto" id="data_pgto" value="<?php echo date('Y-m-d') ?>"> 
						</div>	

							<div class="form-group"> 
									<label>Forma de Pagamento</label> 
								<select class="form-control inputs_form" id="pgto" name="pgto" style="width:100%;" required> 

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


							

						
					



						<!-- Pagamento Parcelado (Opcional) -->
							<div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
								<div style="font-size: 13px; font-weight: 600; color: #6c757d; margin-bottom: 16px;">
									<i class="fa fa-credit-card"></i> Pagamento Parcial (Opcional)
								</div>

							<div class="form-group"> 
									<label>Valor Restante</label> 
									<input type="text" class="form-control inputs_form" name="valor_serv_agd_restante" id="valor_serv_agd_restante" onkeyup="abaterValor()" placeholder="R$ 0,00"> 
						</div>

							<div class="form-group"> 
									<label>Data Pagamento Restante</label> 
									<input type="date" class="form-control inputs_form" name="data_pgto_restante" id="data_pgto_restante"> 
						</div>

							<div class="form-group"> 
									<label>Forma Pagamento Restante</label> 
									<select class="form-control inputs_form" id="pgto_restante" name="pgto_restante" style="width:100%;"> 
										<option value="">Selecionar Forma</option>
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

							<!-- Fechar ao Salvar -->
							<div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
							<div class="form-group"> 
									<label>Fechar Comanda ao Salvar</label> 
									<select class="form-control inputs_form" id="salvar_comanda" name="salvar_comanda" style="width:100%;"> 
									<option value="">Não</option>
									<option value="Sim">Sim</option>
								</select>
							</div>						
						</div>

							<!-- Botões de Ação -->
							<div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #e0e0e0; display: flex; flex-direction: column; gap: 12px;">
								<button type="submit" class="btn-salvar-comanda" style="width: 100%; justify-content: center;">
									<i class="fa fa-save"></i> Salvar Comanda
								</button>
								<a href="#" id="btn_fechar_comanda" onclick="fecharComanda()" class="btn-fechar-comanda" style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
									<i class="fa fa-check-circle"></i> Fechar Comanda
								</a>
						</div>	

						</div>
					
					</div>

					

					
									

					


					
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="valor_servicos" id="valor_servicos">
					<input type="hidden" name="valor_produtos" id="valor_produtos">

					<div id="mensagem" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px; display: none;"></div>
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
				<h4 class="modal-title" id="exampleModalLabel">Informações da Comanda</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<div class="totais-area" style="margin-bottom: 20px;">
					<div class="total-item" style="border-bottom: 1px solid #e0e0e0;">
						<span class="total-label"><i class="fa fa-user"></i> Cliente</span>
						<span class="total-value" id="cliente_dados"></span>						
					</div>
					<div class="total-item" style="border-bottom: 1px solid #e0e0e0;">
						<span class="total-label"><i class="fa fa-id-badge"></i> Aberta Por</span>
						<span class="total-value" id="func_dados"></span>						
					</div>					
					<div class="total-item" style="border-bottom: 1px solid #e0e0e0;">
						<span class="total-label"><i class="fa fa-calendar"></i> Data</span>
						<span class="total-value" id="data_dados"></span>
				</div>
					<div class="total-item" style="border-bottom: none; font-weight: 700; font-size: 18px; color: #007A63; padding-top: 16px; margin-top: 8px; border-top: 2px solid #007A63;">
						<span><i class="fa fa-dollar"></i> VALOR TOTAL</span>
						<span id="valor_dados"></span>
					</div>
					</div>					

				<div class="section-divider">
					<div class="section-title">
						<i class="fa fa-scissors"></i> Serviços
				</div>
					<div id="listar_servicos_dados" style="background: #f8f9fa; padding: 12px; border-radius: 8px; min-height: 60px;">
						</div>
				</div>

				<div class="section-divider">
					<div class="section-title">
						<i class="fa fa-shopping-bag"></i> Produtos
						</div>
					<div id="listar_produtos_dados" style="background: #f8f9fa; padding: 12px; border-radius: 8px; min-height: 60px;">
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
		
		var id = $("#id").val();
		listarServicos(id)
		listarProdutos(id)
		calcular()

		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>



<script type="text/javascript">
	function valorData(dataInicio, dataFinal){
	 $('#data-inicial-caixa').val(dataInicio);
	 $('#data-final-caixa').val(dataFinal);	
	listar();
	
}
</script>



<script type="text/javascript">
	$('#data-inicial-caixa').change(function(){
			//$('#tipo-busca').val('');
			listar();
		});

		$('#data-final-caixa').change(function(){						
			//$('#tipo-busca').val('');
			listar();
		});	
</script>





<script type="text/javascript">
	function listar(){

	var dataInicial = $('#data-inicial-caixa').val();
	var dataFinal = $('#data-final-caixa').val();	
	var status = $('#buscar-contas').val();	
	
    $.ajax({
        url: 'paginas/' + pag + "/listar.php",
        method: 'POST',
        data: {dataInicial, dataFinal, status},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
            $('#mensagem-excluir').text('');
        }
    });
}
</script>



<script type="text/javascript">
	function buscarContas(status){
	 $('#buscar-contas').val(status);
	 listar();
	}
</script>






<script type="text/javascript">
	function calcular(){

		setTimeout(function() {
	  		var produtos = $('#valor_produtos').val();
			var servicos = $('#valor_servicos').val();

			// Converter para float (0 se vazio)
			produtos = parseFloat(produtos) || 0;
			servicos = parseFloat(servicos) || 0;

			var total = produtos + servicos;
			
			// Atualizar os campos de resumo
			$('#valor_servicos_agd').val('R$ ' + servicos.toFixed(2).replace('.', ','));
			$('#valor_produtos_agd').val('R$ ' + produtos.toFixed(2).replace('.', ','));
			$('#total_comanda').val('R$ ' + total.toFixed(2).replace('.', ','));
			$('#valor_serv').val(total.toFixed(2));

			console.log('Calculado - Serviços:', servicos, 'Produtos:', produtos, 'Total:', total); // Debug

			abaterValor();

		}, 500)



}
</script>


<script type="text/javascript">
	function inserirServico(){	
		$("#mensagem").text('');
		var servico = $("#servico").val();
		var funcionario = $("#funcionario").val();
		var cliente = $("#cliente").val();
		var id = $("#id").val();

		if(cliente == ""){
			alert("Selecione um Cliente")
			return;
		}

		if(servico == ""){
			alert("Selecione um Serviço")
			return;
		}
		$.ajax({
			url: 'paginas/' + pag + "/inserir_servico.php",
			method: 'POST',
			data: {servico, funcionario, cliente, id},
			dataType: "text",

			success:function(result){
				if(result.trim() === 'Salvo com Sucesso'){
					listarServicos(id)
					listarProdutos(id);
					calcular();
				}else{
					$("#mensagem").text(result);
				}
			}
		});
	}
</script>



<script type="text/javascript">
	function listarServicos(id){
	
		$.ajax({
			url: 'paginas/' + pag + "/listar_servicos.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){
				$("#listar_servicos").html(result);
				calcular(); // Recalcular totais após listar
			}
		});
	}
</script>



<script type="text/javascript">
	function inserirProduto(){	
		$("#mensagem").text('');
		var produto = $("#produto").val();
		var funcionario = $("#funcionario2").val();
		var cliente = $("#cliente").val();
		var quantidade = $("#quantidade").val();
		var id = $("#id").val();

		if(produto == ""){
			alert("Selecione um Produto")
			return;
		}
		$.ajax({
			url: 'paginas/' + pag + "/inserir_produto.php",
			method: 'POST',
			data: {produto, funcionario, cliente, quantidade, id},
			dataType: "text",

			success:function(result){
				if(result.trim() === 'Salvo com Sucesso'){
					listarProdutos(id);
					listarServicos(id);
					calcular();
					$("#quantidade").val('1');
				}else{
					$("#mensagem").text(result);
				}
			}
		});
	}
</script>



<script type="text/javascript">
	function listarProdutos(id){
	
		$.ajax({
			url: 'paginas/' + pag + "/listar_produtos.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){
				$("#listar_produtos").html(result);
				calcular(); // Recalcular totais após listar
			}
		});
	}
</script>


<script type="text/javascript">
	function fecharComanda(){

		var cliente = $("#cliente").val();
		
		// Pegar o valor total da comanda
		var valor = $("#total_comanda").val();
		
		// Remove R$, espaços e troca vírgula por ponto
		if(valor){
			valor = valor.replace('R$', '').replace(' ', '').replace(',', '.').trim();
		}
		
		// Se ainda estiver vazio, tentar pegar do valor_serv
		if(!valor || valor == "" || valor == "0"){
			valor = $("#valor_serv").val();
		}
		
		console.log('Valor total comanda:', valor); // Debug
		
		var valor_restante = $("#valor_serv_agd_restante").val();
		var data_pgto = $("#data_pgto").val();
		var data_pgto_restante = $("#data_pgto_restante").val();
		var pgto_restante = $("#pgto_restante").val();
		var pgto = $("#pgto").val();
		var id = $("#id").val();

		console.log('Valor enviado:', valor); // Debug

		// Validações
		if(!cliente || cliente == ""){
			alert('Selecione um cliente!');
			return;
		}

		if(!valor || valor == "" || valor == "0" || valor == "0.00"){
			alert('Adicione serviços ou produtos à comanda!');
			return;
		}

		if(!pgto || pgto == ""){
			alert('Selecione uma forma de pagamento!');
			return;
		}

		if(valor_restante > 0){
			if(data_pgto_restante == "" ||  pgto_restante == ""){
				alert('Preencha a Data de Pagamento Restante e o tipo de Pagamento Restante');
				return;
			}
		}

		// Desabilitar botão para evitar cliques duplos
		$('#btn_fechar_comanda').prop('disabled', true);
		$('#btn_fechar_comanda').html('<i class="fa fa-spinner fa-spin"></i> Fechando...');

		console.log('Dados enviados:', {id, valor, valor_restante, data_pgto, pgto, cliente}); // Debug

		$.ajax({
			url: 'paginas/' + pag + "/fechar_comanda.php",
			method: 'POST',
			data: {id, valor, valor_restante, data_pgto, data_pgto_restante, pgto_restante, pgto, cliente},
			dataType: "text",

			success:function(result){
				console.log('Resultado completo:', result); // Debug
				console.log('Resultado trim:', result.trim()); // Debug

				if(result.trim() === 'Salvo com Sucesso'){
					$('#btn-fechar').click();
					listar();	

					// Limpar campos
					$('#data_pgto').val('<?=$data_hoje?>');	
					$('#valor_serv_agd_restante').val('');
					$('#data_pgto_restante').val('');
					$('#pgto_restante').val('').change();

					// Resetar botão
					$('#btn_fechar_comanda').prop('disabled', false);
					$('#btn_fechar_comanda').html('<i class="fa fa-check-circle"></i> Fechar Comanda');
					
				}else{
					$("#mensagem").html('<div class="alert alert-danger">' + result + '</div>');
					$('#btn_fechar_comanda').prop('disabled', false);
					$('#btn_fechar_comanda').html('<i class="fa fa-check-circle"></i> Fechar Comanda');
				}
			},
			error: function(xhr, status, error){
				console.error('Erro:', error);
				alert('Erro ao fechar comanda. Tente novamente.');
				$('#btn_fechar_comanda').prop('disabled', false);
				$('#btn_fechar_comanda').html('<i class="fa fa-check-circle"></i> Fechar Comanda');
			}
		});
	}
</script>




<script type="text/javascript">
	function listarProdutosDados(id){
	
		$.ajax({
			url: 'paginas/' + pag + "/listar_produtos_dados.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){
				$("#listar_produtos_dados").html(result);
			}
		});
	}
</script>


<script type="text/javascript">
	function listarServicosDados(id){
	
		$.ajax({
			url: 'paginas/' + pag + "/listar_servicos_dados.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){
				$("#listar_servicos_dados").html(result);
			}
		});
	}
</script>


<script type="text/javascript">
	$("#form_salvar").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'paginas/' + pag + "/salvar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
        	var msg = mensagem.split("*");
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (msg[0].trim() == "Salvo com Sucesso") {

            	var salvar = $('#salvar_comanda').val();
            	
            	if(salvar == 'Sim'){
            		$("#id").val(msg[1]);            		
            		fecharComanda();
            	}
                $('#btn-fechar').click();
                listar();          

            } else {

                $('#mensagem').addClass('text-danger')
                $('#mensagem').text(msg[0])
            }


           


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>



<script type="text/javascript">
	function abaterValor(){

		var produtos = $('#valor_produtos').val();
		var servicos = $('#valor_servicos').val();

		var total_valores = parseFloat(produtos) + parseFloat(servicos);

		var valor = $("#valor_serv").val(); 
		var valor_rest = $("#valor_serv_agd_restante").val();

		if(valor == ""){
			valor = 0;
		} 

		if(valor_rest == ""){
			valor_rest = 0;
		} 

		var total = parseFloat(total_valores) - parseFloat(valor_rest);
			$('#valor_serv').val(total.toFixed(2));

	}
</script>