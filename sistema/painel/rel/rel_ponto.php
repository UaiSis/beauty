<?php 
if($token_rel != 'A5030'){
echo '<script>window.location="../../"</script>';
exit();
}

$data = @$_GET['data'];
$usuario_id = @$_GET['usuario_id'];

if($data == ""){
	$data = date('Y-m-d');
}

$dataF = implode('/', array_reverse(explode('-', $data)));

$where = "WHERE p.data = '$data'";
if($usuario_id != ""){
	$where .= " AND p.usuario_id = '$usuario_id'";
}

?>

<!DOCTYPE html>
<html>
<head>

<style>

@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left: 0px; top: -110px; bottom: 100px; right: 0px; height: 35px; text-align: center; padding-bottom: 100px; }
#content {margin-top: 0px;}
#footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 80px; }
#footer .page:after {content: counter(page, my-sec-counter);}
body {font-family: 'Tw Cen MT', sans-serif;}

.marca{
	position:fixed;
	left:50;
	top:100;
	width:80%;
	opacity:8%;
}

</style>

</head>
<body>

<div id="header" >

	<div style="border-style: solid; font-size: 10px; height: 50px;">
		<table style="width: 100%; border: 0px solid #ccc;">
			<tr>
				<td style="border: 1px; solid #000; width: 37%; text-align: left;">
					<img style="margin-top: 7px; margin-left: 7px;" id="imag" src="<?php echo $url_sistema ?>sistema/img/logo_rel.jpg" width="80">
				</td>
				<td style="width: 1%; text-align: center; font-size: 13px;"></td>
				<td style="width: 47%; text-align: right; font-size: 9px;padding-right: 10px;">
					<b><big>REGISTRO DE PONTO</big></b><br> Data: <?php echo $dataF ?>
				</td>
			</tr>		
		</table>
	</div>

<br>

	<table id="cabecalhotabela" style="border-bottom-style: solid; font-size: 8px; margin-bottom:10px; width: 100%; table-layout: fixed;">
		<thead>				
			<tr id="cabeca" style="margin-left: 0px; background-color:#CCC">
				<td style="width:20%">FUNCIONÁRIO</td>
				<td style="width:12%">ENTRADA</td>
				<td style="width:12%">SAÍDA ALMOÇO</td>
				<td style="width:12%">RETORNO</td>
				<td style="width:12%">SAÍDA</td>
				<td style="width:10%">HORAS</td>
				<td style="width:10%">EXTRAS</td>
				<td style="width:12%">STATUS</td>
			</tr>
		</thead>
	</table>

</div>

<div id="footer" class="row">
<hr style="margin-bottom: 0;">
	<table style="width:100%;">
		<tr style="width:100%;">
			<td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $whatsapp_sistema ?></td>
			<td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
		</tr>
	</table>
</div>

<div id="content" style="margin-top: 0;">

	<table style="width: 100%; table-layout: fixed; font-size:8px; text-transform: uppercase;">
		<thead>
			<tbody>

<?php
$query = $pdo->query("SELECT p.*, u.nome as usuario_nome, u.nivel as cargo_nome
	FROM pontos p
	LEFT JOIN usuarios u ON p.usuario_id = u.id
	$where
	ORDER BY p.id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
	$total_horas = 0;
	$total_extras = 0;
	
	for($i=0; $i < $total_reg; $i++){
		$usuario_nome = $res[$i]['usuario_nome'];
		$cargo_nome = $res[$i]['cargo_nome'];
		$entrada = $res[$i]['entrada'];
		$saida_almoco = $res[$i]['saida_almoco'];
		$retorno_almoco = $res[$i]['retorno_almoco'];
		$saida = $res[$i]['saida'];
		$horas_trabalhadas = $res[$i]['horas_trabalhadas'];
		$horas_extras = $res[$i]['horas_extras'];
		$status = $res[$i]['status'];
		
		$entradaF = $entrada ? date("H:i", strtotime($entrada)) : '-';
		$saida_almocoF = $saida_almoco ? date("H:i", strtotime($saida_almoco)) : '-';
		$retorno_almocoF = $retorno_almoco ? date("H:i", strtotime($retorno_almoco)) : '-';
		$saidaF = $saida ? date("H:i", strtotime($saida)) : '-';
		$horas_trabalhadasF = number_format($horas_trabalhadas, 2);
		$horas_extrasF = number_format($horas_extras, 2);
		
		$total_horas += $horas_trabalhadas;
		$total_extras += $horas_extras;
		
		if($status == 'aberto'){
			$statusF = 'Trabalhando';
		} elseif($status == 'almoco'){
			$statusF = 'Almoço';
		} elseif($status == 'encerrado'){
			$statusF = 'Encerrado';
		} else {
			$statusF = 'Ausente';
		}
		
		$cargo_display = $cargo_nome ? $cargo_nome : 'Sem cargo';
		
		echo <<<HTML
		<tr>
			<td style="width:20%">
				<b>{$usuario_nome}</b><br>
				<small style="color: #666;">{$cargo_display}</small>
			</td>
			<td style="width:12%">{$entradaF}</td>
			<td style="width:12%">{$saida_almocoF}</td>
			<td style="width:12%">{$retorno_almocoF}</td>
			<td style="width:12%">{$saidaF}</td>
			<td style="width:10%">{$horas_trabalhadasF}h</td>
			<td style="width:10%">{$horas_extrasF}h</td>
			<td style="width:12%">{$statusF}</td>
		</tr>
HTML;
	}
	
	$total_horasF = number_format($total_horas, 2);
	$total_extrasF = number_format($total_extras, 2);
	
	echo <<<HTML
		<tr style="background-color:#f0f0f0; font-weight: bold; border-top: 2px solid #000;">
			<td colspan="5" style="text-align: right; padding-right: 10px;">TOTAIS:</td>
			<td style="width:10%">{$total_horasF}h</td>
			<td style="width:10%">{$total_extrasF}h</td>
			<td style="width:12%"></td>
		</tr>
HTML;
	
} else {
	echo '<tr><td colspan="8" style="text-align: center; padding: 20px;">Nenhum registro encontrado</td></tr>';
}
?>

			</tbody>
		</thead>
	</table>

</div>

</body>
</html>

