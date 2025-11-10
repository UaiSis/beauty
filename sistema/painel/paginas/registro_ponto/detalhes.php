<?php 
require_once("../../../conexao.php");

$id = $_POST['id'];

$query = $pdo->query("SELECT p.*, u.nome as usuario_nome, u.foto, u.telefone, u.nivel as cargo_nome
	FROM pontos p
	LEFT JOIN usuarios u ON p.usuario_id = u.id
	WHERE p.id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(@count($res) == 0){
	echo '<div class="alert alert-danger">Registro não encontrado!</div>';
	exit();
}

$usuario_id = $res[0]['usuario_id'];
$usuario_nome = $res[0]['usuario_nome'];
$foto = $res[0]['foto'];
$telefone = $res[0]['telefone'];
$cargo_nome = $res[0]['cargo_nome'];
$data = $res[0]['data'];
$entrada = $res[0]['entrada'];
$saida_almoco = $res[0]['saida_almoco'];
$retorno_almoco = $res[0]['retorno_almoco'];
$saida = $res[0]['saida'];
$horas_trabalhadas = $res[0]['horas_trabalhadas'];
$horas_extras = $res[0]['horas_extras'];
$observacao = $res[0]['observacao'];
$ip_entrada = $res[0]['ip_entrada'];
$ip_saida_almoco = $res[0]['ip_saida_almoco'];
$ip_retorno_almoco = $res[0]['ip_retorno_almoco'];
$ip_saida = $res[0]['ip_saida'];
$latitude_entrada = $res[0]['latitude_entrada'];
$longitude_entrada = $res[0]['longitude_entrada'];
$latitude_saida_almoco = $res[0]['latitude_saida_almoco'];
$longitude_saida_almoco = $res[0]['longitude_saida_almoco'];
$latitude_retorno_almoco = $res[0]['latitude_retorno_almoco'];
$longitude_retorno_almoco = $res[0]['longitude_retorno_almoco'];
$latitude_saida = $res[0]['latitude_saida'];
$longitude_saida = $res[0]['longitude_saida'];
$status = $res[0]['status'];

$dataF = implode('/', array_reverse(explode('-', $data)));
$entradaF = $entrada ? date("H:i", strtotime($entrada)) : '-';
$saida_almocoF = $saida_almoco ? date("H:i", strtotime($saida_almoco)) : '-';
$retorno_almocoF = $retorno_almoco ? date("H:i", strtotime($retorno_almoco)) : '-';
$saidaF = $saida ? date("H:i", strtotime($saida)) : '-';
$horas_trabalhadasF = number_format($horas_trabalhadas, 2);
$horas_extrasF = number_format($horas_extras, 2);

$tem_foto = ($foto != 'sem-foto.jpg' && !empty($foto));

function obterLocalizacaoPorIP($ip) {
	if(empty($ip) || $ip == '0.0.0.0' || $ip == '127.0.0.1') {
		return '';
	}
	
	$cache_file = '../../../cache_ip_' . md5($ip) . '.txt';
	
	if(file_exists($cache_file) && (time() - filemtime($cache_file)) < 86400) {
		return file_get_contents($cache_file);
	}
	
	try {
		$url = "http://ip-api.com/json/{$ip}?fields=status,city,district,regionName,country,countryCode,zip,lat,lon,isp";
		$context = stream_context_create([
			'http' => [
				'timeout' => 3,
				'ignore_errors' => true
			]
		]);
		
		$response = @file_get_contents($url, false, $context);
		
		if($response === false) {
			return '';
		}
		
		$data = json_decode($response, true);
		
		if($data && $data['status'] == 'success') {
			$location_parts = [];
			
			if(!empty($data['district'])) {
				$location_parts[] = $data['district'];
			}
			
			if(!empty($data['city'])) {
				$location_parts[] = $data['city'];
			}
			
			if(!empty($data['regionName'])) {
				$estados = [
					'Acre' => 'AC', 'Alagoas' => 'AL', 'Amapá' => 'AP', 'Amazonas' => 'AM',
					'Bahia' => 'BA', 'Ceará' => 'CE', 'Distrito Federal' => 'DF', 'Espírito Santo' => 'ES',
					'Goiás' => 'GO', 'Maranhão' => 'MA', 'Mato Grosso' => 'MT', 'Mato Grosso do Sul' => 'MS',
					'Minas Gerais' => 'MG', 'Pará' => 'PA', 'Paraíba' => 'PB', 'Paraná' => 'PR',
					'Pernambuco' => 'PE', 'Piauí' => 'PI', 'Rio de Janeiro' => 'RJ', 'Rio Grande do Norte' => 'RN',
					'Rio Grande do Sul' => 'RS', 'Rondônia' => 'RO', 'Roraima' => 'RR', 'Santa Catarina' => 'SC',
					'São Paulo' => 'SP', 'Sergipe' => 'SE', 'Tocantins' => 'TO'
				];
				$uf = isset($estados[$data['regionName']]) ? $estados[$data['regionName']] : $data['regionName'];
				$location_parts[] = $uf;
			}
			
			if(!empty($data['countryCode']) && $data['countryCode'] != 'BR' && !empty($data['country'])) {
				$location_parts[] = $data['country'];
			}
			
			$location = implode(' - ', $location_parts);
			
			if(!empty($data['zip'])) {
				$location .= ' (CEP: ' . $data['zip'] . ')';
			}
			
			if(!empty($data['isp'])) {
				$location .= ' | ' . $data['isp'];
			}
			
			if(!empty($location)) {
				@file_put_contents($cache_file, $location);
				return $location;
			}
		}
	} catch(Exception $e) {
		return '';
	}
	
	return '';
}

$localizacao_ip_entrada = obterLocalizacaoPorIP($ip_entrada);
$localizacao_ip_saida_almoco = obterLocalizacaoPorIP($ip_saida_almoco);
$localizacao_ip_retorno_almoco = obterLocalizacaoPorIP($ip_retorno_almoco);
$localizacao_ip_saida = obterLocalizacaoPorIP($ip_saida);

?>

<style>
.detalhes-container {
	padding: 20px;
}

.detalhes-header {
	display: flex;
	align-items: center;
	gap: 16px;
	padding-bottom: 20px;
	border-bottom: 2px solid #f0f0f0;
	margin-bottom: 20px;
}

.detalhes-avatar {
	width: 80px;
	height: 80px;
	border-radius: 16px;
	background: #007A63;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 28px;
	font-weight: 700;
	color: #fff;
}

.detalhes-avatar img {
	width: 100%;
	height: 100%;
	border-radius: 16px;
	object-fit: cover;
}

.detalhes-info h3 {
	margin: 0;
	font-size: 24px;
	font-weight: 700;
	color: #1a1a1a;
}

.detalhes-info p {
	margin: 4px 0 0;
	color: #6c757d;
	font-size: 14px;
}

.detalhes-section {
	margin-bottom: 24px;
}

.section-title {
	font-size: 16px;
	font-weight: 700;
	color: #1a1a1a;
	margin-bottom: 12px;
	display: flex;
	align-items: center;
	gap: 8px;
}

.section-title i {
	color: #007A63;
}

.info-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 16px;
}

.info-item {
	background: #f8f9fa;
	padding: 16px;
	border-radius: 12px;
}

.info-label {
	font-size: 12px;
	color: #6c757d;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	margin-bottom: 6px;
}

.info-value {
	font-size: 18px;
	font-weight: 700;
	color: #1a1a1a;
}

.info-value.highlight {
	color: #007A63;
}

.info-value.warning {
	color: #ff9800;
}

.map-link {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	color: #2196f3;
	text-decoration: none;
	font-size: 14px;
	font-weight: 600;
	margin-top: 8px;
}

.map-link:hover {
	color: #1976d2;
}
</style>

<div class="detalhes-container">
	<div class="detalhes-header">
		<div class="detalhes-avatar">
			<?php 
			if($tem_foto){
				echo '<img src="img/perfil/'.$foto.'" alt="'.$usuario_nome.'">';
			} else {
				$iniciais = '';
				$palavras = explode(' ', $usuario_nome);
				if(count($palavras) >= 2){
					$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
				}else{
					$iniciais = strtoupper(substr($usuario_nome, 0, 2));
				}
				echo $iniciais;
			}
			?>
		</div>
		<div class="detalhes-info">
			<h3><?php echo $usuario_nome ?></h3>
			<p><i class="fa fa-briefcase"></i> <?php echo $cargo_nome ? $cargo_nome : 'Sem cargo' ?></p>
			<p><i class="fa fa-phone"></i> <?php echo $telefone ?></p>
		</div>
	</div>

	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-calendar"></i>
			Informações do Dia
		</div>
		<div class="info-grid">
			<div class="info-item">
				<div class="info-label">Data</div>
				<div class="info-value"><?php echo $dataF ?></div>
			</div>
			<div class="info-item">
				<div class="info-label">Horas Trabalhadas</div>
				<div class="info-value highlight"><?php echo $horas_trabalhadasF ?>h</div>
			</div>
			<?php if($horas_extras > 0){ ?>
			<div class="info-item">
				<div class="info-label">Horas Extras</div>
				<div class="info-value warning">+<?php echo $horas_extrasF ?>h</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-clock-o"></i>
			Horários Registrados
		</div>
		<div class="info-grid">
			<div class="info-item">
				<div class="info-label">Entrada</div>
				<div class="info-value"><?php echo $entradaF ?></div>
			</div>
			<div class="info-item">
				<div class="info-label">Saída Almoço</div>
				<div class="info-value"><?php echo $saida_almocoF ?></div>
			</div>
			<div class="info-item">
				<div class="info-label">Retorno Almoço</div>
				<div class="info-value"><?php echo $retorno_almocoF ?></div>
			</div>
			<div class="info-item">
				<div class="info-label">Saída</div>
				<div class="info-value"><?php echo $saidaF ?></div>
			</div>
		</div>
	</div>

	<?php if($latitude_entrada || $ip_entrada){ ?>
	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-map-marker"></i>
			Localização Entrada
		</div>
		<div class="info-grid">
		<?php if($ip_entrada){ ?>
		<div class="info-item">
			<div class="info-label">IP</div>
			<div class="info-value" style="font-size: 14px;">
				<?php echo $ip_entrada ?>
				<?php if($localizacao_ip_entrada){ ?>
					<span style="color: #007A63; font-weight: 500;"> - <?php echo $localizacao_ip_entrada ?></span>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
			<?php if($latitude_entrada && $longitude_entrada){ ?>
			<div class="info-item">
				<div class="info-label">Coordenadas</div>
				<div class="info-value" style="font-size: 14px;">
					<?php echo number_format($latitude_entrada, 6) ?>, <?php echo number_format($longitude_entrada, 6) ?>
				</div>
				<a href="https://www.google.com/maps?q=<?php echo $latitude_entrada ?>,<?php echo $longitude_entrada ?>" target="_blank" class="map-link">
					<i class="fa fa-map"></i> Ver no Google Maps
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<?php if($latitude_saida_almoco || $ip_saida_almoco){ ?>
	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-map-marker"></i>
			Localização Saída Almoço
		</div>
		<div class="info-grid">
		<?php if($ip_saida_almoco){ ?>
		<div class="info-item">
			<div class="info-label">IP</div>
			<div class="info-value" style="font-size: 14px;">
				<?php echo $ip_saida_almoco ?>
				<?php if($localizacao_ip_saida_almoco){ ?>
					<span style="color: #007A63; font-weight: 500;"> - <?php echo $localizacao_ip_saida_almoco ?></span>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
			<?php if($latitude_saida_almoco && $longitude_saida_almoco){ ?>
			<div class="info-item">
				<div class="info-label">Coordenadas</div>
				<div class="info-value" style="font-size: 14px;">
					<?php echo number_format($latitude_saida_almoco, 6) ?>, <?php echo number_format($longitude_saida_almoco, 6) ?>
				</div>
				<a href="https://www.google.com/maps?q=<?php echo $latitude_saida_almoco ?>,<?php echo $longitude_saida_almoco ?>" target="_blank" class="map-link">
					<i class="fa fa-map"></i> Ver no Google Maps
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<?php if($latitude_retorno_almoco || $ip_retorno_almoco){ ?>
	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-map-marker"></i>
			Localização Retorno Almoço
		</div>
		<div class="info-grid">
		<?php if($ip_retorno_almoco){ ?>
		<div class="info-item">
			<div class="info-label">IP</div>
			<div class="info-value" style="font-size: 14px;">
				<?php echo $ip_retorno_almoco ?>
				<?php if($localizacao_ip_retorno_almoco){ ?>
					<span style="color: #007A63; font-weight: 500;"> - <?php echo $localizacao_ip_retorno_almoco ?></span>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
			<?php if($latitude_retorno_almoco && $longitude_retorno_almoco){ ?>
			<div class="info-item">
				<div class="info-label">Coordenadas</div>
				<div class="info-value" style="font-size: 14px;">
					<?php echo number_format($latitude_retorno_almoco, 6) ?>, <?php echo number_format($longitude_retorno_almoco, 6) ?>
				</div>
				<a href="https://www.google.com/maps?q=<?php echo $latitude_retorno_almoco ?>,<?php echo $longitude_retorno_almoco ?>" target="_blank" class="map-link">
					<i class="fa fa-map"></i> Ver no Google Maps
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<?php if($latitude_saida || $ip_saida){ ?>
	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-map-marker"></i>
			Localização Saída
		</div>
		<div class="info-grid">
		<?php if($ip_saida){ ?>
		<div class="info-item">
			<div class="info-label">IP</div>
			<div class="info-value" style="font-size: 14px;">
				<?php echo $ip_saida ?>
				<?php if($localizacao_ip_saida){ ?>
					<span style="color: #007A63; font-weight: 500;"> - <?php echo $localizacao_ip_saida ?></span>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
			<?php if($latitude_saida && $longitude_saida){ ?>
			<div class="info-item">
				<div class="info-label">Coordenadas</div>
				<div class="info-value" style="font-size: 14px;">
					<?php echo number_format($latitude_saida, 6) ?>, <?php echo number_format($longitude_saida, 6) ?>
				</div>
				<a href="https://www.google.com/maps?q=<?php echo $latitude_saida ?>,<?php echo $longitude_saida ?>" target="_blank" class="map-link">
					<i class="fa fa-map"></i> Ver no Google Maps
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<?php if($observacao){ ?>
	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-comment"></i>
			Observações
		</div>
		<div class="info-item">
			<p style="margin: 0; color: #1a1a1a;"><?php echo nl2br($observacao) ?></p>
		</div>
	</div>
	<?php } ?>

	<?php
	$query_ajustes = $pdo->query("SELECT a.*, u.nome as usuario_nome 
		FROM ajustes_ponto a
		LEFT JOIN usuarios u ON a.usuario_ajuste = u.id
		WHERE a.ponto_id = '$id'
		ORDER BY a.data_ajuste DESC");
	$res_ajustes = $query_ajustes->fetchAll(PDO::FETCH_ASSOC);
	
	if(@count($res_ajustes) > 0){
	?>
	<div class="detalhes-section">
		<div class="section-title">
			<i class="fa fa-history"></i>
			Histórico de Ajustes
		</div>
		<?php 
		foreach($res_ajustes as $ajuste){
			$data_ajusteF = date('d/m/Y H:i', strtotime($ajuste['data_ajuste']));
			?>
			<div class="info-item" style="margin-bottom: 12px;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
					<strong><?php echo $ajuste['usuario_nome'] ?></strong>
					<small style="color: #6c757d;"><?php echo $data_ajusteF ?></small>
				</div>
				<p style="margin: 4px 0; font-size: 14px;">
					<strong>Campo:</strong> <?php echo ucfirst(str_replace('_', ' ', $ajuste['campo_ajustado'])) ?><br>
					<strong>De:</strong> <?php echo $ajuste['valor_anterior'] ? date('H:i', strtotime($ajuste['valor_anterior'])) : '-' ?> 
					<strong>Para:</strong> <?php echo $ajuste['valor_novo'] ? date('H:i', strtotime($ajuste['valor_novo'])) : '-' ?><br>
					<strong>Motivo:</strong> <?php echo $ajuste['motivo'] ?>
				</p>
			</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>

