<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

if(@$_SESSION['nivel'] != 'Administrador'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$pag = 'configuracoes_ponto';
?>

<style>
.config-page-modern {
	padding: 24px;
	background: #f8f9fa;
	min-height: 100vh;
}

.config-header {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	margin-bottom: 32px;
	flex-wrap: wrap;
	gap: 16px;
}

.config-header-content {
	flex: 1;
	min-width: 300px;
}

.config-title-wrapper {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 8px;
}

.config-title-icon {
	width: 40px;
	height: 40px;
	background: rgba(33, 150, 243, 0.12);
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	color: #2196f3;
	font-size: 20px;
	flex-shrink: 0;
}

.config-title {
	font-size: 24px;
	font-weight: 700;
	color: #1a1a1a;
	margin: 0;
}

.config-subtitle {
	font-size: 14px;
	color: #6c757d;
	margin: 0;
	padding-left: 52px;
}

.config-divider {
	height: 3px;
	background: linear-gradient(90deg, #2196f3 0%, transparent 100%);
	width: 120px;
	margin-top: 8px;
	margin-left: 52px;
	border-radius: 2px;
}

.btn-nova-config {
	background: #2196f3;
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
	box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
	cursor: pointer;
}

.btn-nova-config:hover {
	background: #1976d2;
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(33, 150, 243, 0.3);
	color: #fff;
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
	border-color: #2196f3;
	background: #fff;
	box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
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

.badge-ativo {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 6px 12px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 600;
	background: rgba(76, 175, 80, 0.1);
	color: #4caf50;
}

.badge-inativo {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 6px 12px;
	border-radius: 8px;
	font-size: 12px;
	font-weight: 600;
	background: rgba(244, 67, 54, 0.1);
	color: #f44336;
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

@media (max-width: 768px) {
	.config-page-modern {
		padding: 16px;
	}
	
	.config-header {
		flex-direction: column;
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

<div class="config-page-modern">
	<div class="config-header">
		<div class="config-header-content">
			<div class="config-title-wrapper">
				<div class="config-title-icon">
					<i class="fa fa-cog"></i>
				</div>
				<h1 class="config-title">Configurações de Horário</h1>
			</div>
			<p class="config-subtitle">Configure os horários de trabalho dos funcionários</p>
			<div class="config-divider"></div>
		</div>
		<button class="btn-nova-config" onclick="abrirModal()">
			<i class="fa fa-plus"></i>
			Nova Configuração
		</button>
	</div>

	<div class="table-card-modern">
		<div class="table-card-header">
			<div class="search-box-modern">
				<i class="fa fa-search search-icon-modern"></i>
				<input type="text" class="search-input-modern" id="buscar" placeholder="Buscar funcionário..." onkeyup="listar()">
			</div>
		</div>
		
		<div id="listar"></div>
	</div>
</div>

<div class="modal fade" id="modalConfig" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="titulo-modal">Nova Configuração</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form id="form-config">
				<div class="modal-body">
					<input type="hidden" id="config-id" name="id">
					
					<div class="form-group">
						<label>Funcionário *</label>
						<select class="form-control" name="usuario_id" id="usuario-select" required>
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
						<div class="col-md-4">
							<div class="form-group">
								<label>Hora Entrada *</label>
								<input type="time" class="form-control" name="hora_entrada" value="08:00" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Hora Saída *</label>
								<input type="time" class="form-control" name="hora_saida" value="18:00" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Horas Diárias *</label>
								<input type="number" class="form-control" name="horas_diarias" value="8" step="0.5" min="1" max="12" required>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Tolerância (min)</label>
								<input type="number" class="form-control" name="tolerancia_minutos" value="10" min="0" max="60">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Almoço Obrigatório</label>
								<select class="form-control" name="almoco_obrigatorio">
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Duração Almoço (min)</label>
								<input type="number" class="form-control" name="duracao_almoco" value="60" min="30" max="120">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label>Dias de Trabalho</label>
						<div style="display: flex; gap: 12px; flex-wrap: wrap;">
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="1" checked> Segunda
							</label>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="2" checked> Terça
							</label>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="3" checked> Quarta
							</label>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="4" checked> Quinta
							</label>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="5" checked> Sexta
							</label>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="6"> Sábado
							</label>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" name="dias[]" value="0"> Domingo
							</label>
						</div>
					</div>
					
					<div class="form-group">
						<label>Status</label>
						<select class="form-control" name="ativo">
							<option value="Sim">Ativo</option>
							<option value="Não">Inativo</option>
						</select>
					</div>
					
					<small><div id="mensagem-config" class="text-center"></div></small>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar Configuração</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	listar();
});

function listar(){
	var busca = $('#buscar').val();
	
	$.ajax({
		url: 'paginas/configuracoes_ponto/listar.php',
		method: 'POST',
		data: {busca: busca},
		dataType: "html",
		success: function(result){
			$("#listar").html(result);
		}
	});
}

function abrirModal(){
	$('#config-id').val('');
	$('#titulo-modal').text('Nova Configuração');
	$('#form-config')[0].reset();
	$('#modalConfig').modal('show');
}

function editarConfig(id){
	$.ajax({
		url: 'paginas/configuracoes_ponto/buscar.php',
		method: 'POST',
		data: {id: id},
		dataType: "json",
		success: function(result){
			$('#config-id').val(result.id);
			$('#titulo-modal').text('Editar Configuração');
			$('select[name="usuario_id"]').val(result.usuario_id);
			$('input[name="hora_entrada"]').val(result.hora_entrada);
			$('input[name="hora_saida"]').val(result.hora_saida);
			$('input[name="horas_diarias"]').val(result.horas_diarias);
			$('input[name="tolerancia_minutos"]').val(result.tolerancia_minutos);
			$('select[name="almoco_obrigatorio"]').val(result.almoco_obrigatorio);
			$('input[name="duracao_almoco"]').val(result.duracao_almoco);
			$('select[name="ativo"]').val(result.ativo);
			
			$('input[name="dias[]"]').prop('checked', false);
			var dias = result.dias_trabalho.split(',');
			dias.forEach(function(dia){
				$('input[name="dias[]"][value="'+dia+'"]').prop('checked', true);
			});
			
			$('#modalConfig').modal('show');
		}
	});
}

$("#form-config").submit(function(e){
	e.preventDefault();
	var formData = new FormData(this);
	
	$.ajax({
		url: 'paginas/configuracoes_ponto/salvar.php',
		type: 'POST',
		data: formData,
		success: function(mensagem){
			$('#mensagem-config').text('');
			$('#mensagem-config').removeClass();
			if(mensagem.trim() == "Salvo com Sucesso"){
				$('#mensagem-config').addClass('text-success');
				$('#mensagem-config').text(mensagem);
				setTimeout(function(){
					$('#modalConfig').modal('hide');
					listar();
				}, 1500);
			} else {
				$('#mensagem-config').addClass('text-danger');
				$('#mensagem-config').text(mensagem);
			}
		},
		cache: false,
		contentType: false,
		processData: false
	});
});

function excluirConfig(id){
	Swal.fire({
		title: 'Tem certeza?',
		text: "Deseja realmente excluir esta configuração?",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, excluir!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: 'paginas/configuracoes_ponto/excluir.php',
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
</script>

