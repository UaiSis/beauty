<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

if(@$_SESSION['nivel'] != 'Administrador'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'registro_ponto';
?>

<style>
.ponto-page-modern {
	padding: 24px;
	background: #f8f9fa;
	min-height: 100vh;
}

.ponto-header {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	margin-bottom: 32px;
	flex-wrap: wrap;
	gap: 16px;
}

.ponto-header-content {
	flex: 1;
	min-width: 300px;
}

.ponto-title-wrapper {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 8px;
}

.ponto-title-icon {
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

.ponto-title {
	font-size: 24px;
	font-weight: 700;
	color: #1a1a1a;
	margin: 0;
}

.ponto-subtitle {
	font-size: 14px;
	color: #6c757d;
	margin: 0;
	padding-left: 52px;
}

.ponto-divider {
	height: 3px;
	background: linear-gradient(90deg, #007A63 0%, transparent 100%);
	width: 120px;
	margin-top: 8px;
	margin-left: 52px;
	border-radius: 2px;
}

.filtros-container {
	display: flex;
	gap: 12px;
	flex-wrap: wrap;
	align-items: center;
}

.filtro-input {
	padding: 10px 16px;
	border: 1px solid #e0e0e0;
	border-radius: 10px;
	font-size: 14px;
	transition: all 0.3s ease;
	background: #fff;
}

.filtro-input:focus {
	outline: none;
	border-color: #007A63;
	box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
}

.btn-filtrar {
	background: #007A63;
	color: #fff;
	border: none;
	border-radius: 10px;
	padding: 10px 20px;
	font-weight: 600;
	font-size: 14px;
	cursor: pointer;
	transition: all 0.3s ease;
	display: inline-flex;
	align-items: center;
	gap: 8px;
}

.btn-filtrar:hover {
	background: #006854;
	transform: translateY(-2px);
}

.stats-cards {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
	gap: 20px;
	margin-bottom: 32px;
}

.stat-card {
	background: #fff;
	border-radius: 16px;
	padding: 24px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
	transition: all 0.3s ease;
}

.stat-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

.stat-card-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin-bottom: 16px;
}

.stat-icon {
	width: 48px;
	height: 48px;
	border-radius: 12px;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 24px;
}

.stat-icon.green {
	background: rgba(76, 175, 80, 0.1);
	color: #4caf50;
}

.stat-icon.blue {
	background: rgba(33, 150, 243, 0.1);
	color: #2196f3;
}

.stat-icon.orange {
	background: rgba(255, 152, 0, 0.1);
	color: #ff9800;
}

.stat-icon.red {
	background: rgba(244, 67, 54, 0.1);
	color: #f44336;
}

.stat-value {
	font-size: 32px;
	font-weight: 700;
	color: #1a1a1a;
	margin: 8px 0;
}

.stat-label {
	font-size: 14px;
	color: #6c757d;
	font-weight: 500;
}

.table-card-modern {
	background: #fff;
	border-radius: 16px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
	overflow: hidden;
}

.table-card-header {
	padding: 24px;
	border-bottom: 1px solid #f0f0f0;
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: 16px;
}

.search-box-modern {
	position: relative;
	flex: 1;
	min-width: 250px;
	max-width: 400px;
}

.search-input-modern {
	width: 100%;
	padding: 12px 16px 12px 44px;
	border: 1px solid #e0e0e0;
	border-radius: 10px;
	font-size: 14px;
	transition: all 0.3s ease;
	background: #f8f9fa;
}

.search-input-modern:focus {
	outline: none;
	border-color: #007A63;
	background: #fff;
	box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
}

.search-icon-modern {
	position: absolute;
	left: 16px;
	top: 50%;
	transform: translateY(-50%);
	color: #6c757d;
	font-size: 16px;
	pointer-events: none;
}

.table-modern {
	width: 100%;
	border-collapse: separate;
	border-spacing: 0;
}

.table-modern thead {
	background: #f8f9fa;
}

.table-modern thead th {
	padding: 16px 20px;
	text-align: left;
	font-size: 13px;
	font-weight: 600;
	color: #6c757d;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	border-bottom: 2px solid #e0e0e0;
}

.table-modern tbody tr {
	transition: all 0.2s ease;
	border-bottom: 1px solid #f0f0f0;
}

.table-modern tbody tr:hover {
	background: #f8f9fa;
}

.table-modern tbody td {
	padding: 16px 20px;
	font-size: 14px;
	color: #1a1a1a;
	vertical-align: middle;
}

.user-info-cell {
	display: flex;
	align-items: center;
	gap: 12px;
}

.user-avatar {
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
}

.user-avatar img {
	width: 100%;
	height: 100%;
	border-radius: 10px;
	object-fit: cover;
}

.user-name {
	font-weight: 600;
	color: #1a1a1a;
	margin-bottom: 4px;
}

.user-email {
	font-size: 12px;
	color: #6c757d;
}

.badge-status {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 6px 12px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 600;
	white-space: nowrap;
}

.badge-status.presente {
	background: rgba(76, 175, 80, 0.1);
	color: #4caf50;
}

.badge-status.ausente {
	background: rgba(244, 67, 54, 0.1);
	color: #f44336;
}

.badge-status.almoco {
	background: rgba(255, 152, 0, 0.1);
	color: #ff9800;
}

.badge-status.encerrado {
	background: rgba(96, 125, 139, 0.1);
	color: #607d8b;
}

.badge-status.atestado {
	background: rgba(255, 152, 0, 0.15);
	color: #ff9800;
	border: 1px solid rgba(255, 152, 0, 0.3);
}

.badge-status.folga {
	background: rgba(33, 150, 243, 0.15);
	color: #2196f3;
	border: 1px solid rgba(33, 150, 243, 0.3);
}

.badge-status-dot {
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: currentColor;
}

.time-display {
	font-family: 'Courier New', monospace;
	font-weight: 600;
	color: #1a1a1a;
}

.actions-cell {
	display: flex;
	gap: 8px;
	align-items: center;
}

.btn-action {
	width: 36px;
	height: 36px;
	border-radius: 8px;
	border: none;
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: all 0.2s ease;
	font-size: 14px;
}

.btn-action.edit {
	background: rgba(33, 150, 243, 0.1);
	color: #2196f3;
}

.btn-action.edit:hover {
	background: rgba(33, 150, 243, 0.2);
	transform: scale(1.1);
}

.btn-action.delete {
	background: rgba(244, 67, 54, 0.1);
	color: #f44336;
}

.btn-action.delete:hover {
	background: rgba(244, 67, 54, 0.2);
	transform: scale(1.1);
}

.btn-action.view {
	background: rgba(156, 39, 176, 0.1);
	color: #9c27b0;
}

.btn-action.view:hover {
	background: rgba(156, 39, 176, 0.2);
	transform: scale(1.1);
}

.pagination-modern {
	padding: 20px 24px;
	border-top: 1px solid #f0f0f0;
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: 16px;
}

.pagination-info {
	font-size: 14px;
	color: #6c757d;
}

.pagination-controls {
	display: flex;
	gap: 8px;
	align-items: center;
}

.pagination-btn {
	padding: 8px 16px;
	border: 1px solid #e0e0e0;
	background: #fff;
	border-radius: 8px;
	cursor: pointer;
	font-size: 14px;
	font-weight: 500;
	color: #1a1a1a;
	transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
	border-color: #007A63;
	background: rgba(0, 122, 99, 0.05);
	color: #007A63;
}

.pagination-btn:disabled {
	opacity: 0.4;
	cursor: not-allowed;
}

@media (max-width: 768px) {
	.ponto-page-modern {
		padding: 16px;
	}
	
	.ponto-header {
		flex-direction: column;
	}
	
	.stats-cards {
		grid-template-columns: 1fr;
	}
	
	.table-card-header {
		flex-direction: column;
	}
	
	.search-box-modern {
		max-width: 100%;
	}
	
	.table-modern {
		font-size: 12px;
	}
	
	.table-modern thead th,
	.table-modern tbody td {
		padding: 12px 8px;
	}
	
	.actions-cell {
		flex-direction: column;
	}
}
</style>

<div class="ponto-page-modern">
	<div class="ponto-header">
		<div class="ponto-header-content">
			<div class="ponto-title-wrapper">
				<div class="ponto-title-icon">
					<i class="fa fa-clock-o"></i>
				</div>
				<h1 class="ponto-title">Registro de Ponto</h1>
			</div>
			<p class="ponto-subtitle">Gerencie o registro de ponto dos funcionários</p>
			<div class="ponto-divider"></div>
		</div>
		<div class="filtros-container">
			<input type="date" class="filtro-input" id="filtro-data" value="<?php echo date('Y-m-d'); ?>">
			<select class="filtro-input" id="filtro-usuario" style="min-width: 200px;">
				<option value="">Todos os Funcionários</option>
				<?php 
				$query = $pdo->query("SELECT * FROM usuarios WHERE atendimento = 'Sim' ORDER BY nome");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				foreach($res as $row){
					echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
				}
				?>
			</select>
			<button class="btn-filtrar" onclick="listar()">
				<i class="fa fa-filter"></i>
				Filtrar
			</button>
		</div>
	</div>

	<div class="stats-cards">
		<div class="stat-card">
			<div class="stat-card-header">
				<div class="stat-icon green">
					<i class="fa fa-check"></i>
				</div>
			</div>
			<div class="stat-value" id="total-presentes">0</div>
			<div class="stat-label">Presentes Hoje</div>
		</div>
		
		<div class="stat-card">
			<div class="stat-card-header">
				<div class="stat-icon blue">
					<i class="fa fa-clock-o"></i>
				</div>
			</div>
			<div class="stat-value" id="total-horas">0h</div>
			<div class="stat-label">Total de Horas Trabalhadas</div>
		</div>
		
		<div class="stat-card">
			<div class="stat-card-header">
				<div class="stat-icon orange">
					<i class="fa fa-plus"></i>
				</div>
			</div>
			<div class="stat-value" id="total-extras">0h</div>
			<div class="stat-label">Horas Extras</div>
		</div>
		
		<div class="stat-card">
			<div class="stat-card-header">
				<div class="stat-icon red">
					<i class="fa fa-times"></i>
				</div>
			</div>
			<div class="stat-value" id="total-ausentes">0</div>
			<div class="stat-label">Ausentes</div>
		</div>
	</div>

	<div class="table-card-modern">
		<div class="table-card-header">
			<div class="search-box-modern">
				<i class="fa fa-search search-icon-modern"></i>
				<input type="text" class="search-input-modern" id="buscar" placeholder="Buscar por nome ou cargo..." onkeyup="listar()">
			</div>
			<div style="display: flex; gap: 12px;">
				<button class="btn-filtrar" onclick="$('#modalAtestado').modal('show')" style="background: #ff9800;">
					<i class="fa fa-file-text-o"></i>
					Lançar Atestado
				</button>
				<button class="btn-filtrar" onclick="$('#modalFolga').modal('show')" style="background: #2196f3;">
					<i class="fa fa-calendar-check-o"></i>
					Lançar Folga
				</button>
				<button class="btn-filtrar" onclick="exportarRelatorio()">
					<i class="fa fa-file-pdf-o"></i>
					Exportar PDF
				</button>
			</div>
		</div>
		
		<div id="listar"></div>
		
		<div class="pagination-modern">
			<div class="pagination-info">
				Mostrando <span id="info-exibindo">0</span> de <span id="info-total">0</span> registros
			</div>
			<div class="pagination-controls">
				<button class="pagination-btn" id="btn-anterior" onclick="paginaAnterior()">
					<i class="fa fa-chevron-left"></i> Anterior
				</button>
				<span class="pagination-info" id="info-pagina">Página 1</span>
				<button class="pagination-btn" id="btn-proximo" onclick="proximaPagina()">
					Próximo <i class="fa fa-chevron-right"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalAjustar" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ajustar Ponto</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form id="form-ajustar">
				<div class="modal-body">
					<input type="hidden" id="ponto-id" name="ponto_id">
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Entrada</label>
								<input type="time" class="form-control" name="entrada" id="ajustar-entrada">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Saída Almoço</label>
								<input type="time" class="form-control" name="saida_almoco" id="ajustar-saida-almoco">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Retorno Almoço</label>
								<input type="time" class="form-control" name="retorno_almoco" id="ajustar-retorno-almoco">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Saída</label>
								<input type="time" class="form-control" name="saida" id="ajustar-saida">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Motivo do Ajuste</label>
						<textarea class="form-control" name="motivo" rows="3" required></textarea>
					</div>
					
					<small><div id="mensagem-ajustar" class="text-center"></div></small>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar Ajuste</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalDetalhes" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Detalhes do Ponto</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="detalhes-ponto">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalAtestado" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					<i class="fa fa-file-text-o" style="color: #ff9800;"></i>
					Lançar Atestado Médico
				</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form id="form-atestado">
				<div class="modal-body">
					<div class="form-group">
						<label>Funcionário *</label>
						<select class="form-control" name="usuario_id" required>
							<option value="">Selecione um funcionário</option>
							<?php 
							$query = $pdo->query("SELECT * FROM usuarios WHERE atendimento = 'Sim' AND ativo = 'Sim' ORDER BY nome");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							foreach($res as $row){
								echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
							}
							?>
						</select>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Início *</label>
								<input type="date" class="form-control" name="data_inicio" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Fim *</label>
								<input type="date" class="form-control" name="data_fim" required>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Observação</label>
						<textarea class="form-control" name="observacao" rows="3" placeholder="Ex: Atestado médico apresentado em 10/11/2025"></textarea>
					</div>
					
					<div class="alert alert-info" style="font-size: 13px;">
						<i class="fa fa-info-circle"></i>
						<strong>Atenção:</strong> Serão criados registros de atestado para todos os dias do período selecionado. Estes dias não contarão como falta e não exigirão cumprimento de horas.
					</div>
					
					<small><div id="mensagem-atestado" class="text-center"></div></small>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-warning">
						<i class="fa fa-check"></i> Lançar Atestado
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalFolga" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					<i class="fa fa-calendar-check-o" style="color: #2196f3;"></i>
					Lançar Folga / Descanso
				</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form id="form-folga">
				<div class="modal-body">
					<div class="form-group">
						<label>Funcionário *</label>
						<select class="form-control" name="usuario_id" required>
							<option value="">Selecione um funcionário</option>
							<?php 
							$query = $pdo->query("SELECT * FROM usuarios WHERE atendimento = 'Sim' AND ativo = 'Sim' ORDER BY nome");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							foreach($res as $row){
								echo '<option value="'.$row['id'].'">'.$row['nome'].'</option>';
							}
							?>
						</select>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Início *</label>
								<input type="date" class="form-control" name="data_inicio" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Data Fim *</label>
								<input type="date" class="form-control" name="data_fim" required>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Observação</label>
						<textarea class="form-control" name="observacao" rows="3" placeholder="Ex: Folga concedida por banco de horas"></textarea>
					</div>
					
					<div class="alert alert-info" style="font-size: 13px;">
						<i class="fa fa-info-circle"></i>
						<strong>Atenção:</strong> Serão criados registros de folga para todos os dias do período selecionado. Estes dias não contarão como falta e não exigirão cumprimento de horas.
					</div>
					
					<small><div id="mensagem-folga" class="text-center"></div></small>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-check"></i> Lançar Folga
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
let paginaAtual = 0;
let itensPorPagina = <?php echo $itens_pag ?>;

$(document).ready(function() {
	listar();
	atualizarEstatisticas();
	setInterval(atualizarEstatisticas, 60000);
});

function listar(){
	var data = $('#filtro-data').val();
	var usuario = $('#filtro-usuario').val();
	var busca = $('#buscar').val();
	
	$.ajax({
		url: 'paginas/registro_ponto/listar.php',
		method: 'POST',
		data: {
			data: data,
			usuario_id: usuario,
			busca: busca,
			pagina: paginaAtual,
			itens_por_pagina: itensPorPagina
		},
		dataType: "html",
		success: function(result){
			$("#listar").html(result);
		}
	});
}

function atualizarEstatisticas(){
	var data = $('#filtro-data').val();
	
	$.ajax({
		url: 'paginas/registro_ponto/estatisticas.php',
		method: 'POST',
		data: {data: data},
		dataType: "json",
		success: function(result){
			$('#total-presentes').text(result.presentes);
			$('#total-horas').text(result.horas);
			$('#total-extras').text(result.extras);
			$('#total-ausentes').text(result.ausentes);
		}
	});
}

function proximaPagina(){
	paginaAtual++;
	listar();
}

function paginaAnterior(){
	if(paginaAtual > 0){
		paginaAtual--;
		listar();
	}
}

function abrirAjustar(id, entrada, saida_almoco, retorno_almoco, saida){
	$('#ponto-id').val(id);
	$('#ajustar-entrada').val(entrada);
	$('#ajustar-saida-almoco').val(saida_almoco);
	$('#ajustar-retorno-almoco').val(retorno_almoco);
	$('#ajustar-saida').val(saida);
	$('#modalAjustar').modal('show');
}

$("#form-ajustar").submit(function(e){
	e.preventDefault();
	var formData = new FormData(this);
	
	$.ajax({
		url: 'paginas/registro_ponto/ajustar.php',
		type: 'POST',
		data: formData,
		success: function(mensagem){
			$('#mensagem-ajustar').text('');
			$('#mensagem-ajustar').removeClass();
			if(mensagem.trim() == "Ajustado com Sucesso"){
				$('#mensagem-ajustar').addClass('text-success');
				$('#mensagem-ajustar').text(mensagem);
				setTimeout(function(){
					$('#modalAjustar').modal('hide');
					listar();
				}, 1500);
			} else {
				$('#mensagem-ajustar').addClass('text-danger');
				$('#mensagem-ajustar').text(mensagem);
			}
		},
		cache: false,
		contentType: false,
		processData: false
	});
});

$("#form-atestado").submit(function(e){
	e.preventDefault();
	var formData = new FormData(this);
	
	$.ajax({
		url: 'paginas/registro_ponto/lancar_atestado.php',
		type: 'POST',
		data: formData,
		success: function(mensagem){
			$('#mensagem-atestado').text('');
			$('#mensagem-atestado').removeClass();
			if(mensagem.trim().includes('Sucesso')){
				$('#mensagem-atestado').addClass('text-success');
				$('#mensagem-atestado').text(mensagem);
				setTimeout(function(){
					$('#modalAtestado').modal('hide');
					$('#form-atestado')[0].reset();
					listar();
					atualizarEstatisticas();
				}, 2000);
			} else {
				$('#mensagem-atestado').addClass('text-danger');
				$('#mensagem-atestado').text(mensagem);
			}
		},
		cache: false,
		contentType: false,
		processData: false
	});
});

$("#form-folga").submit(function(e){
	e.preventDefault();
	var formData = new FormData(this);
	
	$.ajax({
		url: 'paginas/registro_ponto/lancar_folga.php',
		type: 'POST',
		data: formData,
		success: function(mensagem){
			$('#mensagem-folga').text('');
			$('#mensagem-folga').removeClass();
			if(mensagem.trim().includes('Sucesso')){
				$('#mensagem-folga').addClass('text-success');
				$('#mensagem-folga').text(mensagem);
				setTimeout(function(){
					$('#modalFolga').modal('hide');
					$('#form-folga')[0].reset();
					listar();
					atualizarEstatisticas();
				}, 2000);
			} else {
				$('#mensagem-folga').addClass('text-danger');
				$('#mensagem-folga').text(mensagem);
			}
		},
		cache: false,
		contentType: false,
		processData: false
	});
});

function verDetalhes(id){
	$.ajax({
		url: 'paginas/registro_ponto/detalhes.php',
		method: 'POST',
		data: {id: id},
		dataType: "html",
		success: function(result){
			$("#detalhes-ponto").html(result);
			$('#modalDetalhes').modal('show');
		}
	});
}

function excluirPonto(id){
	Swal.fire({
		title: 'Tem certeza?',
		text: "Deseja realmente excluir este registro de ponto?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, excluir!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: 'paginas/registro_ponto/excluir.php',
				method: 'POST',
				data: {id: id},
				success: function(mensagem){
					if(mensagem.trim() == "Excluído com Sucesso"){
						Swal.fire('Excluído!', mensagem, 'success');
						listar();
					} else {
						Swal.fire('Erro!', mensagem, 'error');
					}
				}
			});
		}
	});
}

function exportarRelatorio(){
	var data = $('#filtro-data').val();
	var usuario = $('#filtro-usuario').val();
	window.open('rel/rel_ponto_class.php?data='+data+'&usuario_id='+usuario, '_blank');
}
</script>

