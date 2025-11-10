<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$pagar == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'pagar';

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
	/* Página de Contas a Pagar Moderna */
	.pagar-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.pagar-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.pagar-header-content {
		flex: 1;
	}

	.pagar-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.pagar-title-icon {
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

	.pagar-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.pagar-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.pagar-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.btn-nova-conta {
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

	.btn-nova-conta:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
		color: #fff;
	}

	/* Filtros Modernos */
	.filters-card {
		background: #fff;
		border-radius: 12px;
		padding: 24px;
		margin-bottom: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.filters-row {
		display: flex;
		gap: 20px;
		align-items: flex-start;
		flex-wrap: wrap;
	}

	.filter-section {
		display: flex;
		flex-direction: column;
		gap: 12px;
	}

	.filter-section-label {
		font-size: 12px;
		color: #6c757d;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.filter-periodo {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.filter-input {
		padding: 8px 12px;
		border: 2px solid #e9ecef;
		border-radius: 8px;
		font-size: 13px;
		color: #495057;
		background: #fff;
		transition: all 0.3s ease;
		min-width: 140px;
	}

	.filter-input:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.quick-filters {
		display: flex;
		gap: 6px;
		flex-wrap: wrap;
	}

	.quick-filter-btn {
		padding: 6px 12px;
		border-radius: 8px;
		font-size: 12px;
		font-weight: 600;
		border: 2px solid #e9ecef;
		background: #fff;
		color: #6c757d;
		cursor: pointer;
		transition: all 0.3s ease;
		text-decoration: none;
		white-space: nowrap;
	}

	.quick-filter-btn:hover {
		border-color: #007A63;
		color: #007A63;
		background: rgba(0, 122, 99, 0.05);
		text-decoration: none;
	}

	.quick-filter-btn.active {
		border-color: #007A63;
		background: #007A63;
		color: #fff;
	}

	.status-filters {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.status-filter-btn {
		padding: 8px 16px;
		border-radius: 8px;
		font-size: 13px;
		font-weight: 600;
		border: 2px solid #e9ecef;
		background: #fff;
		color: #6c757d;
		cursor: pointer;
		transition: all 0.3s ease;
		text-decoration: none;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		white-space: nowrap;
	}

	.status-filter-btn:hover {
		border-color: #007A63;
		color: #007A63;
		background: rgba(0, 122, 99, 0.05);
		text-decoration: none;
	}

	.status-filter-btn.active {
		border-color: #007A63;
		background: #007A63;
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
		border-left: 3px solid transparent;
	}

	.table-modern tbody tr:nth-child(even) {
		background: #fafbfc;
	}

	.table-modern tbody tr:hover {
		background: #f5f9f8;
		border-left-color: #007A63;
	}

	.table-modern tbody tr.pago {
		background: rgba(0, 216, 150, 0.05);
	}

	.table-modern tbody tr.pendente {
		background: rgba(255, 152, 0, 0.05);
	}

	.table-modern tbody tr.vencido {
		background: rgba(239, 83, 80, 0.05);
		border-left-color: #ef5350;
	}

	.table-modern tbody td {
		padding: 16px 20px;
		vertical-align: middle;
		font-size: 14px;
		color: #1a1a1a;
		border: none;
	}

	.status-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
	}

	.status-badge.pago {
		background: rgba(0, 216, 150, 0.12);
		color: #00d896;
	}

	.status-badge.pendente {
		background: rgba(255, 152, 0, 0.12);
		color: #ff9800;
	}

	.status-badge.vencido {
		background: rgba(239, 83, 80, 0.12);
		color: #ef5350;
	}

	.valor-cell {
		font-weight: 700;
		font-size: 15px;
		color: #007A63;
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

	.table-action-icon.baixar {
		color: #00d896;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.baixar:hover {
		background: rgba(0, 216, 150, 0.15);
	}

	.table-action-icon.whatsapp {
		color: #25D366;
		background: rgba(37, 211, 102, 0.08);
	}

	.table-action-icon.whatsapp:hover {
		background: rgba(37, 211, 102, 0.15);
	}

	/* Totalizadores */
	.totalizadores {
		display: flex;
		justify-content: flex-end;
		gap: 24px;
		padding: 20px;
		background: #fafbfc;
		border-radius: 0 0 16px 16px;
		border-top: 2px solid #f0f0f0;
	}

	.total-item {
		display: flex;
		flex-direction: column;
		align-items: flex-end;
	}

	.total-label {
		font-size: 12px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.total-value {
		font-size: 18px;
		font-weight: 700;
	}

	.total-value.pago {
		color: #00d896;
	}

	.total-value.pendente {
		color: #ef5350;
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
	.pagar-mobile-list {
		display: none;
	}

	.conta-card-mobile {
		background: #fff;
		border-radius: 12px;
		padding: 16px;
		margin-bottom: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		border-left: 4px solid #007A63;
	}

	.conta-card-mobile.pago {
		border-left-color: #00d896;
	}

	.conta-card-mobile.pendente {
		border-left-color: #ff9800;
	}

	.conta-card-mobile.vencido {
		border-left-color: #ef5350;
	}

	.conta-card-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 12px;
		padding-bottom: 12px;
		border-bottom: 1px solid #f0f0f0;
	}

	.conta-card-title {
		font-size: 15px;
		font-weight: 700;
		color: #1a1a1a;
	}

	.conta-card-details {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-bottom: 12px;
	}

	.conta-card-detail-item {
		display: flex;
		flex-direction: column;
	}

	.conta-card-detail-label {
		font-size: 11px;
		color: #6c757d;
		margin-bottom: 4px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.conta-card-detail-value {
		font-size: 13px;
		color: #1a1a1a;
		font-weight: 600;
	}

	.conta-card-actions {
		display: flex;
		gap: 8px;
		margin-top: 12px;
		padding-top: 12px;
		border-top: 1px solid #f0f0f0;
	}

	.conta-card-action-btn {
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

	.conta-card-action-btn.edit {
		background: rgba(0, 122, 99, 0.1);
		color: #007A63;
	}

	.conta-card-action-btn.view {
		background: rgba(66, 165, 245, 0.1);
		color: #42a5f5;
	}

	.conta-card-action-btn.baixar {
		background: rgba(0, 216, 150, 0.1);
		color: #00d896;
	}

	.conta-card-action-btn.delete {
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
	}

	.conta-card-action-btn.whatsapp {
		background: rgba(37, 211, 102, 0.1);
		color: #25D366;
	}

	.btn-nova-conta-mobile {
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

	.btn-nova-conta-mobile:hover {
		transform: scale(1.1);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.4);
	}

	@media (max-width: 768px) {
		.pagar-page-modern {
			padding: 16px;
		}

		.pagar-header {
			flex-direction: column;
			align-items: flex-start;
			gap: 16px;
		}

		.pagar-title-wrapper {
			gap: 10px;
		}

		.pagar-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.pagar-title {
			font-size: 20px;
		}

		.pagar-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.pagar-divider {
			margin-left: 46px;
			width: 100px;
		}

		.btn-nova-conta {
			display: none;
		}

		.btn-nova-conta-mobile {
			display: flex;
		}

		.table-card-modern {
			display: none;
		}

		.pagar-mobile-list {
			display: block;
		}

		.filters-row {
			flex-direction: column;
		}

		.filter-section {
			width: 100%;
		}

		.filter-periodo {
			flex-direction: column;
			align-items: stretch;
		}

		.filter-input {
			width: 100%;
			min-width: 100%;
		}

		.quick-filters {
			justify-content: stretch;
		}

		.quick-filter-btn {
			flex: 1;
			justify-content: center;
		}

		.status-filters {
			justify-content: stretch;
		}

		.status-filter-btn {
			flex: 1;
			justify-content: center;
		}

		.totalizadores {
			flex-direction: column;
			align-items: stretch;
			gap: 12px;
		}

		.total-item {
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
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

<div class="pagar-page-modern">
	
	<div class="pagar-header">
		<div class="pagar-header-content">
			<div class="pagar-title-wrapper">
				<div class="pagar-title-icon">
					<i class="fa fa-credit-card"></i>
				</div>
				<h1 class="pagar-title">Contas a Pagar</h1>
			</div>
			<p class="pagar-subtitle">Gerencie todas as contas e despesas a pagar</p>
			<div class="pagar-divider"></div>
		</div>
		<button onclick="inserir()" class="btn-nova-conta">
			<i class="fa fa-plus"></i> Nova Conta
		</button>
	</div>

	<!-- Filtros Modernos -->
	<div class="filters-card">
		<div class="filters-row">
			<!-- Seção de Período -->
			<div class="filter-section" style="flex: 1; min-width: 300px;">
				<div class="filter-section-label">
					<i class="fa fa-calendar"></i>
					Período
				</div>
				<div class="filter-periodo">
					<input type="date" class="filter-input" id="data-inicial-caixa" value="<?php echo $data_inicio_mes ?>" required>
					<span style="color: #6c757d; font-weight: 600;">até</span>
					<input type="date" class="filter-input" id="data-final-caixa" value="<?php echo $data_final_mes ?>" required>
				</div>
				<div class="quick-filters">
					<a href="#" class="quick-filter-btn" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>'); return false;">
						Ontem
					</a>
					<a href="#" class="quick-filter-btn" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>'); return false;">
						Hoje
					</a>
					<a href="#" class="quick-filter-btn" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>'); return false;">
						Este Mês
					</a>
				</div>
			</div>

			<!-- Seção de Status -->
			<div class="filter-section" style="flex: 1; min-width: 280px;">
				<div class="filter-section-label">
					<i class="fa fa-filter"></i>
					Status
				</div>
				<div class="status-filters">
					<a href="#" class="status-filter-btn active" id="filter-todas" onclick="buscarContas(''); return false;">
						<i class="fa fa-list"></i> Todas
					</a>
					<a href="#" class="status-filter-btn" id="filter-pendentes" onclick="buscarContas('Não'); return false;">
						<i class="fa fa-clock-o"></i> Pendentes
					</a>
					<a href="#" class="status-filter-btn" id="filter-pagas" onclick="buscarContas('Sim'); return false;">
						<i class="fa fa-check-circle"></i> Pagas
					</a>
				</div>
			</div>
		</div>

		<input type="hidden" id="buscar-contas">
	</div>

	<div class="table-card-modern">
		<div id="listar"></div>
	</div>

	<div class="pagar-mobile-list">
		<div id="listar-mobile"></div>
	</div>

	<button onclick="inserir()" class="btn-nova-conta-mobile">
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
							<i class="fa fa-credit-card"></i>
						</div>
						<span id="titulo_inserir">Nova Conta</span>
					</div>
					<div class="modal-subtitle">
						Registre uma nova conta a pagar no sistema
					</div>
				</div>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">

					<!-- Descrição e Forma de Pagamento -->
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Descrição <span class="required">*</span></label>
								<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da conta" required>    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma de Pagamento <span class="required">*</span></label>
								<select class="form-control" id="pgto" name="pgto" style="width:100%;" required> 
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
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Fornecedor e Funcionário -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Fornecedor</label>
								<select class="form-control sel2" id="pessoa" name="pessoa" style="width:100%;" > 
									<?php 
									$query = $pdo->query("SELECT * FROM fornecedores ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									
									echo '<option value="0">Selecione um Fornecedor</option>';

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
								<label>Funcionário</label>
								<select class="form-control sel2" id="funcionario" name="funcionario" style="width:100%;" > 
									<?php 
									$query = $pdo->query("SELECT * FROM usuarios ORDER BY nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									
									echo '<option value="0">Selecione um Funcionário</option>';

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
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Valores e Datas -->
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Valor <span class="required">*</span></label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="R$ 0,00" required>    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Vencimento</label>
								<input type="date" class="form-control" id="data_venc" name="data_venc" value="<?php echo $data_hoje ?>" >    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Data Pagamento</label>
								<input type="date" class="form-control" id="data_pgto" name="data_pgto"  >    
							</div> 	
						</div>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Arquivo/Comprovante -->
					<div class="row">
						<div class="col-md-8">						
							<div class="form-group"> 
								<label>Anexar Comprovante</label> 
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>						
						</div>
						<div class="col-md-4">
							<div class="foto-preview-container">
								<img src="img/contas/sem-foto.jpg" class="foto-preview" id="target">
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
						<span id="btn-text">Salvar Conta</span>
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
						Detalhes completos da conta
					</div>
				</div>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">

				<!-- Informações do Valor -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-dollar" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Valor e Pagamento</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 14px; color: #6c757d; margin-bottom: 8px;">Valor Total</div>
							<div style="font-size: 28px; font-weight: 700; color: #007A63;">R$ <span id="valor_dados"></span></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 16px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Forma de Pagamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="pgto_dados"></div>
						</div>
					</div>
				</div>

				<!-- Informações de Datas -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-calendar" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Datas</span>
					</div>

					<div class="row">
						<div class="col-md-4" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Lançamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="data_lanc_dados"></div>
						</div>
						<div class="col-md-4" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Vencimento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="data_venc_dados"></div>
						</div>
						<div class="col-md-4" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Pagamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #00d896;" id="data_pgto_dados"></div>
						</div>
					</div>
				</div>

				<!-- Informações de Usuários -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-users" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Usuários</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Lançamento</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="usuario_lanc_dados"></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Baixa</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="usuario_baixa_dados"></div>
						</div>
					</div>
				</div>

				<!-- Informações de Fornecedor/Funcionário -->
				<div style="background: #fafbfc; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
					<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
						<i class="fa fa-building" style="color: #007A63;"></i>
						<span style="font-size: 14px; font-weight: 600; color: #1a1a1a;">Beneficiário</span>
					</div>

					<div class="row">
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Fornecedor</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="pessoa_dados"></div>
						</div>
						<div class="col-md-6" style="margin-bottom: 12px;">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Funcionário</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;" id="nome_func_dados"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Chave PIX</div>
							<div style="font-size: 14px; font-weight: 600; color: #007A63;" id="chave_dados"></div>
						</div>
					</div>
				</div>

				<!-- Comprovante/Arquivo -->
				<div style="text-align: center; margin-top: 24px;">
					<div style="font-size: 12px; color: #6c757d; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Comprovante</div>
					<a id="link_mostrar" target="_blank" title="Clique para abrir o arquivo!">	
						<img id="target_mostrar" style="max-width: 300px; border-radius: 12px; border: 3px solid #e0e0e0; cursor: pointer; transition: all 0.3s ease;">
					</a>
				</div>

			</div>
		</div>
	</div>
</div>







<!-- Modal Baixar-->
<div class="modal fade" id="modalBaixar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-main">
						<div class="modal-title-icon" style="background: rgba(0, 216, 150, 0.1);">
							<i class="fa fa-check-square" style="color: #00d896;"></i>
						</div>
						<span id="titulo_baixar"></span>
					</div>
					<div class="modal-subtitle">
						Confirme o pagamento da conta
					</div>
				</div>
				<button id="btn-fechar-baixar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-baixar">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Valor <span class="required">*</span></label>
								<input type="text" class="form-control" id="valor_baixar" name="valor" placeholder="R$ 0,00" required>    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma de Pagamento <span class="required">*</span></label>
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
								<label>Pago Em <span class="required">*</span></label>
								<input type="date" class="form-control" id="data_pgto_baixar" name="data_pgto"  value="<?php echo $data_hoje ?>" required>    
							</div> 	
						</div>
					</div>

					<input type="hidden" name="id" id="id_baixar">
					<div id="mensagem-baixar" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px;"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn-cancel" data-dismiss="modal">
						Cancelar
					</button>
					<button id="btn_baixar" type="submit" class="btn-submit" style="background: #00d896;">
						<i class="fa fa-check"></i>
						<span>Confirmar Pagamento</span>
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
		listar();

		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});

		$('#data-inicial-caixa').change(function(){
			listar();
		});

		$('#data-final-caixa').change(function(){						
			listar();
		});
	});

	function inserir(){
		$('#mensagem').text('');
		$('#titulo_inserir').text('Nova Conta');
		$('#btn-text').text('Salvar Conta');
		limparCampos();
		$('#modalForm').modal('show');
	}

	function limparCampos(){
		$('#id').val('');
		$('#descricao').val('');
		$('#valor').val('');
		$('#data_pgto').val('');
		$('#data_venc').val('<?=$data_hoje?>');		
		$('#foto').val('');
		$('#target').attr('src','img/contas/sem-foto.jpg');
		$('#titulo_inserir').text('Nova Conta');
		$('#btn-text').text('Salvar Conta');
	}
</script>

<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];

		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if(resultado[1] === 'pdf'){
			$('#target').attr('src', "img/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target').attr('src', "img/rar.png");
			return;
		}

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
	function valorData(dataInicio, dataFinal){
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);	
		listar();
	}
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
		
		// Atualizar estado visual dos filtros
		$('.status-filter-btn').removeClass('active');
		if(status === ''){
			$('#filter-todas').addClass('active');
		}else if(status === 'Não'){
			$('#filter-pendentes').addClass('active');
		}else if(status === 'Sim'){
			$('#filter-pagas').addClass('active');
		}
		
		listar();
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
