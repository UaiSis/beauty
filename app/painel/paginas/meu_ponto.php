<?php
require_once("cabecalho.php");
require_once("rodape.php");

$data_atual = date('Y-m-d');
$pag = 'meu_ponto';

$query_ponto = $pdo->query("SELECT * FROM pontos WHERE usuario_id = '$id_usuario' AND data = '$data_atual'");
$res_ponto = $query_ponto->fetchAll(PDO::FETCH_ASSOC);
$tem_ponto = @count($res_ponto);

if($tem_ponto > 0){
	$ponto_id = $res_ponto[0]['id'];
	$entrada = $res_ponto[0]['entrada'];
	$saida_almoco = $res_ponto[0]['saida_almoco'];
	$retorno_almoco = $res_ponto[0]['retorno_almoco'];
	$saida = $res_ponto[0]['saida'];
	$horas_trabalhadas = $res_ponto[0]['horas_trabalhadas'];
	$horas_extras = $res_ponto[0]['horas_extras'];
	$status = $res_ponto[0]['status'];
} else {
	$ponto_id = '';
	$entrada = '';
	$saida_almoco = '';
	$retorno_almoco = '';
	$saida = '';
	$horas_trabalhadas = 0;
	$horas_extras = 0;
	$status = '';
}

?>

<style>
.ponto-card {
	background: #fff;
	border-radius: 15px;
	padding: 20px;
	margin-bottom: 15px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.ponto-card-title {
	font-size: 16px;
	font-weight: 700;
	color: #1a1a1a;
	margin-bottom: 15px;
	display: flex;
	align-items: center;
	gap: 10px;
}

.clock-display {
	text-align: center;
	padding: 20px;
	background: linear-gradient(135deg, #007A63 0%, #00a682 100%);
	border-radius: 15px;
	color: #fff;
	margin-bottom: 20px;
}

.clock-time {
	font-size: 36px;
	font-weight: 700;
	font-family: 'Courier New', monospace;
	margin-bottom: 5px;
}

.clock-date {
	font-size: 14px;
	opacity: 0.9;
}

.btn-ponto {
	width: 100%;
	padding: 15px;
	border-radius: 12px;
	border: none;
	font-size: 15px;
	font-weight: 600;
	margin-bottom: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
	transition: all 0.3s ease;
}

.btn-ponto i {
	font-size: 18px;
}

.btn-ponto.entrada {
	background: #4caf50;
	color: #fff;
}

.btn-ponto.almoco {
	background: #ff9800;
	color: #fff;
}

.btn-ponto.retorno {
	background: #2196f3;
	color: #fff;
}

.btn-ponto.saida {
	background: #f44336;
	color: #fff;
}

.btn-ponto:disabled {
	background: #e0e0e0;
	color: #9e9e9e;
	opacity: 0.6;
}

.time-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 10px;
	margin-bottom: 15px;
}

.time-item {
	background: #f8f9fa;
	padding: 12px;
	border-radius: 10px;
	text-align: center;
}

.time-label {
	font-size: 11px;
	color: #6c757d;
	margin-bottom: 5px;
	text-transform: uppercase;
}

.time-value {
	font-size: 18px;
	font-weight: 700;
	color: #1a1a1a;
	font-family: 'Courier New', monospace;
}

.time-value.empty {
	color: #ccc;
}

.stat-item {
	background: #f8f9fa;
	padding: 15px;
	border-radius: 10px;
	text-align: center;
	margin-bottom: 10px;
}

.stat-value {
	font-size: 24px;
	font-weight: 700;
	color: #007A63;
	margin-bottom: 5px;
}

.stat-label {
	font-size: 12px;
	color: #6c757d;
}

.historico-item {
	background: #f8f9fa;
	padding: 15px;
	border-radius: 10px;
	margin-bottom: 10px;
}

.historico-date {
	font-weight: 600;
	color: #1a1a1a;
	margin-bottom: 8px;
	font-size: 14px;
}

.historico-times {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 10px;
	font-size: 12px;
}

.historico-time-item {
	color: #6c757d;
}

.historico-time-value {
	font-weight: 600;
	color: #1a1a1a;
	font-family: 'Courier New', monospace;
}
</style>

<div class="page-content header-clear-medium">

	<div class="ponto-card">
		<div class="clock-display" id="relogio">
			<div class="clock-time" id="hora-atual">00:00:00</div>
			<div class="clock-date" id="data-atual">--</div>
		</div>

		<?php if(!$entrada){ ?>
			<button class="btn-ponto entrada" onclick="registrarPonto('entrada')">
				<i class="fa fa-sign-in"></i>
				Registrar Entrada
			</button>
		<?php } else { ?>
			<button class="btn-ponto entrada" disabled>
				<i class="fa fa-check"></i>
				Entrada: <?php echo date('H:i', strtotime($entrada)) ?>
			</button>
		<?php } ?>

		<?php if($entrada && !$saida_almoco){ ?>
			<button class="btn-ponto almoco" onclick="registrarPonto('saida_almoco')">
				<i class="fa fa-utensils"></i>
				Sair para Almoço
			</button>
		<?php } elseif($saida_almoco) { ?>
			<button class="btn-ponto almoco" disabled>
				<i class="fa fa-check"></i>
				Saída Almoço: <?php echo date('H:i', strtotime($saida_almoco)) ?>
			</button>
		<?php } else { ?>
			<button class="btn-ponto almoco" disabled>
				<i class="fa fa-utensils"></i>
				Sair para Almoço
			</button>
		<?php } ?>

		<?php if($saida_almoco && !$retorno_almoco){ ?>
			<button class="btn-ponto retorno" onclick="registrarPonto('retorno_almoco')">
				<i class="fa fa-undo"></i>
				Retornar do Almoço
			</button>
		<?php } elseif($retorno_almoco) { ?>
			<button class="btn-ponto retorno" disabled>
				<i class="fa fa-check"></i>
				Retorno: <?php echo date('H:i', strtotime($retorno_almoco)) ?>
			</button>
		<?php } else { ?>
			<button class="btn-ponto retorno" disabled>
				<i class="fa fa-undo"></i>
				Retornar do Almoço
			</button>
		<?php } ?>

		<?php if($entrada && !$saida){ ?>
			<button class="btn-ponto saida" onclick="registrarPonto('saida')">
				<i class="fa fa-sign-out"></i>
				Registrar Saída
			</button>
		<?php } elseif($saida) { ?>
			<button class="btn-ponto saida" disabled>
				<i class="fa fa-check"></i>
				Saída: <?php echo date('H:i', strtotime($saida)) ?>
			</button>
		<?php } else { ?>
			<button class="btn-ponto saida" disabled>
				<i class="fa fa-sign-out"></i>
				Registrar Saída
			</button>
		<?php } ?>
	</div>

	<div class="ponto-card">
		<div class="ponto-card-title">
			<i class="fa fa-clock-o" style="color: #007A63;"></i>
			Horários de Hoje
		</div>
		<div class="time-grid">
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

	<div class="ponto-card">
		<div class="ponto-card-title">
			<i class="fa fa-history" style="color: #007A63;"></i>
			Últimos 7 Dias
		</div>
		<div id="historico-lista">
			<div style="text-align: center; padding: 20px; color: #6c757d;">
				<i class="fa fa-spinner fa-spin"></i> Carregando...
			</div>
		</div>
	</div>

</div>

<input type="hidden" id="ponto-id" value="<?php echo $ponto_id; ?>">
<input type="hidden" id="usuario-id" value="<?php echo $id_usuario; ?>">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
	
	document.getElementById('hora-atual').textContent = horas + ':' + minutos + ':' + segundos;
	document.getElementById('data-atual').textContent = diaSemana + ', ' + dia + ' de ' + mes + ' de ' + ano;
}

setInterval(atualizarRelogio, 1000);
atualizarRelogio();

function registrarPonto(tipo){
	const ponto_id = document.getElementById('ponto-id').value;
	const usuario_id = document.getElementById('usuario-id').value;
	
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
	fetch('../../sistema/painel/paginas/meu_ponto/registrar.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: 'tipo=' + tipo + '&latitude=' + latitude + '&longitude=' + longitude + '&ponto_id=' + ponto_id + '&usuario_id=' + usuario_id
	})
	.then(response => response.text())
	.then(mensagem => {
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
	})
	.catch(error => {
		Swal.fire('Erro', 'Ocorreu um erro ao registrar o ponto!', 'error');
	});
}

function carregarHistorico(){
	const usuario_id = document.getElementById('usuario-id').value;
	
	fetch('../../sistema/painel/paginas/meu_ponto/historico.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: 'usuario_id=' + usuario_id
	})
	.then(response => response.text())
	.then(data => {
		document.getElementById('historico-lista').innerHTML = data;
	});
}

carregarHistorico();
</script>

