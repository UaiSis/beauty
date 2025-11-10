<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'meu_ponto';
$id_usuario = $_SESSION['id'];
$data_hoje = date('Y-m-d');

$query = $pdo->query("SELECT * FROM pontos WHERE usuario_id = '$id_usuario' AND data = '$data_hoje'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$tem_ponto_hoje = @count($res) > 0;

if($tem_ponto_hoje){
	$ponto_id = $res[0]['id'];
	$entrada = $res[0]['entrada'];
	$saida_almoco = $res[0]['saida_almoco'];
	$retorno_almoco = $res[0]['retorno_almoco'];
	$saida = $res[0]['saida'];
	$status = $res[0]['status'];
	$horas_trabalhadas = $res[0]['horas_trabalhadas'];
	$horas_extras = $res[0]['horas_extras'];
} else {
	$ponto_id = '';
	$entrada = '';
	$saida_almoco = '';
	$retorno_almoco = '';
	$saida = '';
	$status = '';
	$horas_trabalhadas = 0;
	$horas_extras = 0;
}

$query_config = $pdo->query("SELECT * FROM configuracoes_ponto WHERE usuario_id = '$id_usuario' AND ativo = 'Sim'");
$res_config = $query_config->fetchAll(PDO::FETCH_ASSOC);
$tem_configuracao = @count($res_config) > 0;

if($tem_configuracao){
	$hora_entrada_esperada = $res_config[0]['hora_entrada'];
	$hora_saida_esperada = $res_config[0]['hora_saida'];
	$almoco_obrigatorio = $res_config[0]['almoco_obrigatorio'];
} else {
	$hora_entrada_esperada = '08:00:00';
	$hora_saida_esperada = '18:00:00';
	$almoco_obrigatorio = 'Sim';
}
?>

<style>
.ponto-page {
	padding: 24px;
	background: #f8f9fa;
	min-height: 100vh;
}

.ponto-container {
	max-width: 1200px;
	margin: 0 auto;
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

.ponto-header h1 {
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

.clock-display {
	background: #fff;
	border-radius: 16px;
	padding: 32px;
	margin-bottom: 32px;
	text-align: center;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
	border: 1px solid #e0e0e0;
}

.current-time {
	font-size: 64px;
	font-weight: 700;
	color: #007A63;
	font-family: 'Courier New', monospace;
	margin-bottom: 8px;
}

.current-date {
	font-size: 18px;
	color: #6c757d;
	font-weight: 500;
}

.status-card {
	background: #fff;
	border-radius: 20px;
	padding: 32px;
	margin-bottom: 24px;
	box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.status-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin-bottom: 24px;
}

.status-badge {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 10px 20px;
	border-radius: 12px;
	font-size: 16px;
	font-weight: 600;
}

.status-badge.trabalhando {
	background: rgba(76, 175, 80, 0.1);
	color: #4caf50;
}

.status-badge.almoco {
	background: rgba(255, 152, 0, 0.1);
	color: #ff9800;
}

.status-badge.encerrado {
	background: rgba(96, 125, 139, 0.1);
	color: #607d8b;
}

.status-badge.pendente {
	background: rgba(33, 150, 243, 0.1);
	color: #2196f3;
}

.status-dot {
	width: 12px;
	height: 12px;
	border-radius: 50%;
	background: currentColor;
	animation: pulse 2s infinite;
}

@keyframes pulse {
	0%, 100% { opacity: 1; }
	50% { opacity: 0.5; }
}

.action-buttons {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 16px;
	margin-bottom: 32px;
}

.btn-ponto {
	padding: 20px;
	border: none;
	border-radius: 16px;
	font-size: 16px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.3s ease;
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 12px;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.btn-ponto:hover:not(:disabled) {
	transform: translateY(-4px);
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.btn-ponto:disabled {
	opacity: 0.5;
	cursor: not-allowed;
}

.btn-ponto i {
	font-size: 32px;
}

.btn-entrada {
	background: linear-gradient(135deg, #4caf50, #45a049);
	color: #fff;
}

.btn-saida-almoco {
	background: linear-gradient(135deg, #ff9800, #f57c00);
	color: #fff;
}

.btn-retorno-almoco {
	background: linear-gradient(135deg, #2196f3, #1976d2);
	color: #fff;
}

.btn-saida {
	background: linear-gradient(135deg, #f44336, #d32f2f);
	color: #fff;
}

.time-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
	gap: 16px;
	margin-bottom: 24px;
}

.time-item {
	background: #f8f9fa;
	padding: 20px;
	border-radius: 12px;
	text-align: center;
}

.time-label {
	font-size: 12px;
	color: #6c757d;
	font-weight: 600;
	text-transform: uppercase;
	margin-bottom: 8px;
}

.time-value {
	font-size: 24px;
	font-weight: 700;
	color: #1a1a1a;
	font-family: 'Courier New', monospace;
}

.time-value.empty {
	color: #ccc;
}

.stats-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 16px;
}

.stat-item {
	background: #fff;
	padding: 24px;
	border-radius: 12px;
	text-align: center;
	border: 2px solid #007A63;
}

.stat-value {
	font-size: 32px;
	font-weight: 700;
	margin-bottom: 8px;
	color: #007A63;
}

.stat-label {
	font-size: 14px;
	color: #6c757d;
	font-weight: 500;
}

.historico-card {
	background: #fff;
	border-radius: 20px;
	padding: 32px;
	box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
	margin-top: 24px;
}

.historico-header {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-bottom: 24px;
}

.historico-header h3 {
	font-size: 20px;
	font-weight: 700;
	color: #1a1a1a;
	margin: 0;
}

@media (max-width: 768px) {
	.ponto-page {
		padding: 16px;
	}
	
	.current-time {
		font-size: 48px;
	}
	
	.action-buttons {
		grid-template-columns: 1fr;
	}
	
	.time-grid {
		grid-template-columns: 1fr 1fr;
	}
}
</style>

<div class="ponto-page">
	<div class="ponto-container">
		<div class="ponto-header">
			<div class="ponto-header-content">
				<div class="ponto-title-wrapper">
					<div class="ponto-title-icon">
						<i class="fa fa-clock-o"></i>
					</div>
					<h1>Meu Ponto</h1>
				</div>
				<p class="ponto-subtitle">Registre sua jornada de trabalho</p>
				<div class="ponto-divider"></div>
			</div>
		</div>

		<div class="clock-display">
			<div class="current-time" id="hora-atual">--:--:--</div>
			<div class="current-date" id="data-atual">--</div>
		</div>

		<div class="status-card">
			<div class="status-header">
				<h3 style="margin: 0; color: #1a1a1a;">Status do Dia</h3>
				<div id="status-badge">
					<?php if(!$tem_ponto_hoje){ ?>
						<span class="status-badge pendente">
							<span class="status-dot"></span>
							Aguardando Entrada
						</span>
					<?php } elseif($status == 'aberto'){ ?>
						<span class="status-badge trabalhando">
							<span class="status-dot"></span>
							Trabalhando
						</span>
					<?php } elseif($status == 'almoco'){ ?>
						<span class="status-badge almoco">
							<span class="status-dot"></span>
							Em Almoço
						</span>
					<?php } else { ?>
						<span class="status-badge encerrado">
							<span class="status-dot"></span>
							Dia Encerrado
						</span>
					<?php } ?>
				</div>
			</div>

			<div class="action-buttons">
				<button class="btn-ponto btn-entrada" id="btn-entrada" onclick="registrarPonto('entrada')" 
					<?php echo $entrada ? 'disabled' : ''; ?>>
					<i class="fa fa-sign-in"></i>
					<span>Registrar Entrada</span>
					<small><?php echo date('H:i', strtotime($hora_entrada_esperada)); ?></small>
				</button>

				<button class="btn-ponto btn-saida-almoco" id="btn-saida-almoco" onclick="registrarPonto('saida_almoco')" 
					<?php echo (!$entrada || $saida_almoco || $saida) ? 'disabled' : ''; ?>>
					<i class="fa fa-utensils"></i>
					<span>Sair para Almoço</span>
				</button>

				<button class="btn-ponto btn-retorno-almoco" id="btn-retorno-almoco" onclick="registrarPonto('retorno_almoco')" 
					<?php echo (!$saida_almoco || $retorno_almoco || $saida) ? 'disabled' : ''; ?>>
					<i class="fa fa-undo"></i>
					<span>Retornar do Almoço</span>
				</button>

				<button class="btn-ponto btn-saida" id="btn-saida" onclick="registrarPonto('saida')" 
					<?php echo (!$entrada || $saida) ? 'disabled' : ''; ?>>
					<i class="fa fa-sign-out"></i>
					<span>Registrar Saída</span>
					<small><?php echo date('H:i', strtotime($hora_saida_esperada)); ?></small>
				</button>
			</div>

			<div class="time-grid" id="horarios-registrados">
				<div class="time-item">
					<div class="time-label">Entrada</div>
					<div class="time-value <?php echo !$entrada ? 'empty' : ''; ?>">
						<?php echo $entrada ? date('H:i', strtotime($entrada)) : '--:--'; ?>
					</div>
				</div>
				<div class="time-item">
					<div class="time-label">Saída Almoço</div>
					<div class="time-value <?php echo !$saida_almoco ? 'empty' : ''; ?>">
						<?php echo $saida_almoco ? date('H:i', strtotime($saida_almoco)) : '--:--'; ?>
					</div>
				</div>
				<div class="time-item">
					<div class="time-label">Retorno</div>
					<div class="time-value <?php echo !$retorno_almoco ? 'empty' : ''; ?>">
						<?php echo $retorno_almoco ? date('H:i', strtotime($retorno_almoco)) : '--:--'; ?>
					</div>
				</div>
				<div class="time-item">
					<div class="time-label">Saída</div>
					<div class="time-value <?php echo !$saida ? 'empty' : ''; ?>">
						<?php echo $saida ? date('H:i', strtotime($saida)) : '--:--'; ?>
					</div>
				</div>
			</div>

			<div class="stats-grid">
				<div class="stat-item">
					<div class="stat-value"><?php echo number_format($horas_trabalhadas, 2); ?>h</div>
					<div class="stat-label">Horas Trabalhadas</div>
				</div>
				<?php if($horas_extras > 0){ ?>
				<div class="stat-item">
					<div class="stat-value">+<?php echo number_format($horas_extras, 2); ?>h</div>
					<div class="stat-label">Horas Extras</div>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="historico-card">
			<div class="historico-header">
				<i class="fa fa-history" style="font-size: 24px; color: #667eea;"></i>
				<h3>Histórico dos Últimos 7 Dias</h3>
			</div>
			<div id="historico-lista"></div>
		</div>
	</div>
</div>

<input type="hidden" id="ponto-id" value="<?php echo $ponto_id; ?>">
<input type="hidden" id="usuario-id" value="<?php echo $id_usuario; ?>">

<script>
function atualizarRelogio(){
	const now = new Date();
	const horas = String(now.getHours()).padStart(2, '0');
	const minutos = String(now.getMinutes()).padStart(2, '0');
	const segundos = String(now.getSeconds()).padStart(2, '0');
	
	const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
	const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
	
	const diaSemana = diasSemana[now.getDay()];
	const dia = now.getDate();
	const mes = meses[now.getMonth()];
	const ano = now.getFullYear();
	
	$('#hora-atual').text(horas + ':' + minutos + ':' + segundos);
	$('#data-atual').text(diaSemana + ', ' + dia + ' de ' + mes + ' de ' + ano);
}

function registrarPonto(tipo){
	const ponto_id = $('#ponto-id').val();
	const usuario_id = $('#usuario-id').val();
	
	if (!navigator.geolocation) {
		registrarPontoAjax(tipo, 0, 0, ponto_id, usuario_id);
		return;
	}
	
	Swal.fire({
		title: 'Registrando ponto...',
		text: 'Aguarde um momento',
		allowOutsideClick: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});
	
	const geoOptions = {
		enableHighAccuracy: true,
		timeout: 5000,
		maximumAge: 0
	};
	
	let geolocalizacaoObtida = false;
	
	navigator.geolocation.getCurrentPosition(function(position){
		if(!geolocalizacaoObtida){
			geolocalizacaoObtida = true;
			const latitude = position.coords.latitude;
			const longitude = position.coords.longitude;
			registrarPontoAjax(tipo, latitude, longitude, ponto_id, usuario_id);
		}
	}, function(error){
		if(geolocalizacaoObtida) return;
		
		const geoOptionsFallback = {
			enableHighAccuracy: false,
			timeout: 3000,
			maximumAge: 60000
		};
		
		navigator.geolocation.getCurrentPosition(function(position){
			if(!geolocalizacaoObtida){
				geolocalizacaoObtida = true;
				const latitude = position.coords.latitude;
				const longitude = position.coords.longitude;
				registrarPontoAjax(tipo, latitude, longitude, ponto_id, usuario_id);
			}
		}, function(errorFallback){
			if(!geolocalizacaoObtida){
				geolocalizacaoObtida = true;
				console.log('Geolocalização não disponível, registrando apenas com IP');
				registrarPontoAjax(tipo, 0, 0, ponto_id, usuario_id);
			}
		}, geoOptionsFallback);
	}, geoOptions);
	
	setTimeout(function(){
		if(!geolocalizacaoObtida){
			geolocalizacaoObtida = true;
			console.log('Timeout de geolocalização, registrando apenas com IP');
			registrarPontoAjax(tipo, 0, 0, ponto_id, usuario_id);
		}
	}, 8000);
}

function registrarPontoAjax(tipo, latitude, longitude, ponto_id, usuario_id){
	$.ajax({
		url: 'paginas/meu_ponto/registrar.php',
		method: 'POST',
		data: {
			tipo: tipo,
			latitude: latitude,
			longitude: longitude,
			ponto_id: ponto_id,
			usuario_id: usuario_id
		},
		success: function(mensagem){
			mensagem = mensagem.trim();
			if(mensagem.toLowerCase().includes('sucesso')){
				Swal.fire({
					title: 'Sucesso!',
					text: mensagem,
					icon: 'success',
					showConfirmButton: false,
					timer: 1500
				}).then(() => {
					location.reload();
				});
			} else {
				Swal.fire('Erro', mensagem, 'error');
			}
		},
		error: function(){
			Swal.fire('Erro', 'Ocorreu um erro ao registrar o ponto!', 'error');
		}
	});
}

function carregarHistorico(){
	const usuario_id = $('#usuario-id').val();
	
	$.ajax({
		url: 'paginas/meu_ponto/historico.php',
		method: 'POST',
		data: {usuario_id: usuario_id},
		dataType: "html",
		success: function(result){
			$("#historico-lista").html(result);
		}
	});
}

$(document).ready(function(){
	atualizarRelogio();
	setInterval(atualizarRelogio, 1000);
	carregarHistorico();
});
</script>

