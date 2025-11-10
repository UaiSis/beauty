<?php 
require_once("../../../conexao.php");

$usuario_id = $_POST['usuario_id'];

$query = $pdo->query("SELECT * FROM pontos 
	WHERE usuario_id = '$usuario_id' 
	AND data >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
	AND data <= CURDATE()
	ORDER BY data DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
?>
<style>
.historico-table {
	width: 100%;
	border-collapse: separate;
	border-spacing: 0 8px;
}

.historico-row {
	background: #f8f9fa;
	border-radius: 12px;
	transition: all 0.2s ease;
}

.historico-row:hover {
	background: #e9ecef;
	transform: translateX(4px);
}

.historico-row td {
	padding: 16px;
	vertical-align: middle;
}

.historico-row td:first-child {
	border-radius: 12px 0 0 12px;
}

.historico-row td:last-child {
	border-radius: 0 12px 12px 0;
}

.historico-date {
	font-weight: 600;
	color: #1a1a1a;
	margin-bottom: 4px;
}

.historico-dia {
	font-size: 12px;
	color: #6c757d;
}

.historico-time {
	font-family: 'Courier New', monospace;
	font-weight: 600;
	color: #1a1a1a;
	font-size: 14px;
}

.historico-label {
	font-size: 11px;
	color: #6c757d;
	text-transform: uppercase;
	margin-bottom: 4px;
}
</style>

<table class="historico-table">
	<?php 
	$dias_semana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
	
	for($i=0; $i < $total_reg; $i++){
		$data = $res[$i]['data'];
		$entrada = $res[$i]['entrada'];
		$saida_almoco = $res[$i]['saida_almoco'];
		$retorno_almoco = $res[$i]['retorno_almoco'];
		$saida = $res[$i]['saida'];
		$horas_trabalhadas = $res[$i]['horas_trabalhadas'];
		$horas_extras = $res[$i]['horas_extras'];
		
		$dataF = implode('/', array_reverse(explode('-', $data)));
		$dia_semana = $dias_semana[date('w', strtotime($data))];
		
		$entradaF = $entrada ? date('H:i', strtotime($entrada)) : '--:--';
		$saida_almocoF = $saida_almoco ? date('H:i', strtotime($saida_almoco)) : '--:--';
		$retorno_almocoF = $retorno_almoco ? date('H:i', strtotime($retorno_almoco)) : '--:--';
		$saidaF = $saida ? date('H:i', strtotime($saida)) : '--:--';
		
		$horas_trabalhadasF = number_format($horas_trabalhadas, 2);
	?>
	<tr class="historico-row">
		<td style="width: 20%;">
			<div class="historico-date"><?php echo $dataF ?></div>
			<div class="historico-dia"><?php echo $dia_semana ?></div>
		</td>
		<td>
			<div class="historico-label">Entrada</div>
			<div class="historico-time"><?php echo $entradaF ?></div>
		</td>
		<td>
			<div class="historico-label">Saída Almoço</div>
			<div class="historico-time"><?php echo $saida_almocoF ?></div>
		</td>
		<td>
			<div class="historico-label">Retorno</div>
			<div class="historico-time"><?php echo $retorno_almocoF ?></div>
		</td>
		<td>
			<div class="historico-label">Saída</div>
			<div class="historico-time"><?php echo $saidaF ?></div>
		</td>
		<td>
			<div class="historico-label">Total</div>
			<div class="historico-time"><?php echo $horas_trabalhadasF ?>h</div>
			<?php if($horas_extras > 0){ ?>
			<div style="font-size: 11px; color: #ff9800; font-weight: 600; margin-top: 4px;">
				+<?php echo number_format($horas_extras, 2) ?>h extras
			</div>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
</table>
<?php
} else {
	echo '<div style="text-align: center; padding: 40px;">
		<i class="fa fa-inbox" style="font-size: 48px; color: #e0e0e0; margin-bottom: 16px;"></i>
		<h4 style="color: #6c757d;">Sem histórico recente</h4>
		<p style="color: #6c757d; font-size: 14px;">Você não possui registros nos últimos 7 dias.</p>
	</div>';
}
?>

