<?php 
require_once("../../../conexao.php");
$tabela = 'receber';
$data_hoje = date('Y-m-d');

$total_vencido = 0;
$total_contas = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_venc < curDate() and pago = 'Não' and valor > 0 ORDER BY data_venc asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

// Contar estatísticas
$total_contas = $total_reg;
foreach($res as $conta){
	$total_vencido += $conta['valor'];
}

$total_vencidoF = number_format($total_vencido, 2, ',', '.');

// Renderizar cards de estatísticas
echo "<script>
document.getElementById('stats-container').innerHTML = `
	<div class='stat-card alert'>
		<div class='stat-icon alert'>
			<i class='fa fa-exclamation-triangle'></i>
		</div>
		<div class='stat-content'>
			<div class='stat-label'>Contas Vencidas</div>
			<div class='stat-value'>{$total_contas}</div>
		</div>
	</div>
	<div class='stat-card alert'>
		<div class='stat-icon alert'>
			<i class='fa fa-dollar'></i>
		</div>
		<div class='stat-content'>
			<div class='stat-label'>Total Vencido</div>
			<div class='stat-value' style='color: #ef5350;'>R$ {$total_vencidoF}</div>
		</div>
	</div>
`;
</script>";

if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Descrição</th>	
	<th>Valor</th> 	
	<th>Venceu em</th> 	
	<th>Cliente</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];	
	$descricao = $res[$i]['descricao'];
	$tipo = $res[$i]['tipo'];
	$valor = $res[$i]['valor'];
	$data_lanc = $res[$i]['data_lanc'];
	$data_pgto = $res[$i]['data_pgto'];
	$data_venc = $res[$i]['data_venc'];
	$usuario_lanc = $res[$i]['usuario_lanc'];
	$usuario_baixa = $res[$i]['usuario_baixa'];
	$foto = $res[$i]['foto'];
	$pessoa = $res[$i]['pessoa'];
	$pago = $res[$i]['pago'];
	$pgto = $res[$i]['pgto'];
	
	$valorF = number_format($valor, 2, ',', '.');
	$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
	$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
	$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));

	// Cliente
	$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_pessoa = $res2[0]['nome'];
		$telefone_pessoa = $res2[0]['telefone'];
		$classe_whats = '';
	}else{
		$nome_pessoa = 'Nenhum';
		$telefone_pessoa = '';
		$classe_whats = 'ocultar';
	}

	// Usuário Baixa
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_usuario_pgto = $res2[0]['nome'];
	}else{
		$nome_usuario_pgto = 'Nenhum';
	}

	// Usuário Lançamento
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_usuario_lanc = $res2[0]['nome'];
	}else{
		$nome_usuario_lanc = 'Sem Referência';
	}

	$data_pgtoF = 'Pendente';
	$visivel = '';

	// Extensão do arquivo
	$ext = pathinfo($foto, PATHINFO_EXTENSION);
	if($ext == 'pdf'){
		$tumb_arquivo = 'pdf.png';
	}else if($ext == 'rar' || $ext == 'zip'){
		$tumb_arquivo = 'rar.png';
	}else{
		$tumb_arquivo = $foto;
	}

	// WhatsApp
	$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_pessoa);

	// Calcular dias de atraso
	$data_venc_obj = new DateTime($data_venc);
	$data_hoje_obj = new DateTime($data_hoje);
	$diferenca = $data_venc_obj->diff($data_hoje_obj);
	$dias_atraso = $diferenca->days;

echo <<<HTML
<tr>
<td>
	<div style="display: flex; align-items: center; gap: 8px;">
		<span class="status-badge vencido">
			<i class="fa fa-exclamation-circle"></i> {$dias_atraso} dias
		</span>
		<span style="font-weight: 600;">{$descricao}</span>
	</div>
</td>
<td><span class="valor-cell">R$ {$valorF}</span></td>
<td style="color: #ef5350; font-weight: 600;">{$data_vencF}</td>
<td>{$nome_pessoa}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}',  '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$pgto}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="#" onclick="baixar('{$id}', '{$valor}', '{$descricao}', '{$pgto}')" class="table-action-icon baixar" title="Baixar Conta">
			<i class="fa fa-check-square"></i>
		</a>

		<a href="#" onclick="cobrar('{$id}', '{$valor}', '{$data_venc}', '{$telefone_pessoa}', '{$descricao}')" class="table-action-icon whatsapp {$classe_whats}" title="Gerar Cobrança WhatsApp">
			<i class="fa fa-whatsapp"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoConta('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

echo <<<HTML
</tbody>
</table>

<div class="totalizadores">
	<div class="total-item">
		<div class="total-label">Total de Contas Vencidas</div>
		<div class="total-value vencido">{$total_contas}</div>
	</div>
	<div class="total-item">
		<div class="total-label">Valor Total em Atraso</div>
		<div class="total-value vencido">R$ {$total_vencidoF}</div>
	</div>
</div>

<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-check-circle" style="font-size: 48px; color: #00d896; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #00a574; font-weight: 500; margin: 0;">Nenhuma conta vencida!</p>
		<p style="font-size: 13px; color: #6c757d; margin-top: 8px;">Todas as contas estão em dia</p>
	</div>';
}

?>

<script type="text/javascript">
	// Renderizar versão mobile
	function renderMobileCards() {
		var mobileListElement = document.getElementById('listar-mobile');
		if (!mobileListElement) return;
		
		var mobileHtml = '';
		<?php 
		$query_mobile = $pdo->query("SELECT * FROM receber where data_venc < curDate() and pago = 'Não' and valor > 0 ORDER BY data_venc asc");
		$res = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		if($total_reg > 0){
			for($i=0; $i < $total_reg; $i++){
				$id = $res[$i]['id'];
				$descricao = $res[$i]['descricao'];
				$valor = $res[$i]['valor'];
				$data_lanc = $res[$i]['data_lanc'];
				$data_pgto = $res[$i]['data_pgto'];
				$data_venc = $res[$i]['data_venc'];
				$usuario_lanc = $res[$i]['usuario_lanc'];
				$usuario_baixa = $res[$i]['usuario_baixa'];
				$foto = $res[$i]['foto'];
				$pessoa = $res[$i]['pessoa'];
				$pgto = $res[$i]['pgto'];

				$valorF = number_format($valor, 2, ',', '.');
				$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
				$data_pgtoF = 'Pendente';
				$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));

				// Cliente
				$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_pessoa = $res2[0]['nome'];
					$telefone_pessoa = $res2[0]['telefone'];
				}else{
					$nome_pessoa = 'Nenhum';
					$telefone_pessoa = '';
				}

				// Usuário Lançamento
				$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_usuario_lanc = $res2[0]['nome'];
				}else{
					$nome_usuario_lanc = 'Sem Referência';
				}

				// Usuário Baixa
				$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_usuario_pgto = $res2[0]['nome'];
				}else{
					$nome_usuario_pgto = 'Nenhum';
				}

				$ext = pathinfo($foto, PATHINFO_EXTENSION);
				if($ext == 'pdf'){
					$tumb_arquivo = 'pdf.png';
				}else if($ext == 'rar' || $ext == 'zip'){
					$tumb_arquivo = 'rar.png';
				}else{
					$tumb_arquivo = $foto;
				}

				// Calcular dias de atraso
				$data_venc_obj = new DateTime($data_venc);
				$data_hoje_obj = new DateTime(date('Y-m-d'));
				$diferenca = $data_venc_obj->diff($data_hoje_obj);
				$dias_atraso = $diferenca->days;

				$whats_mobile = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_pessoa);
				$tem_whatsapp = ($nome_pessoa != 'Nenhum' && !empty($telefone_pessoa));
				
				echo "mobileHtml += `";
				echo "<div class='vencida-card-mobile'>";
				echo "<div class='alert-badge-mobile'>VENCIDO</div>";
				echo "<div class='vencida-card-header'>";
				echo "<div class='vencida-card-title'>{$descricao}</div>";
				echo "</div>";
				
				echo "<div class='vencida-card-details'>";
				echo "<div class='vencida-card-detail-item'>";
				echo "<div class='vencida-card-detail-label'>Valor</div>";
				echo "<div class='vencida-card-detail-value alert' style='font-size: 16px;'>R$ {$valorF}</div>";
				echo "</div>";
				echo "<div class='vencida-card-detail-item'>";
				echo "<div class='vencida-card-detail-label'>Venceu em</div>";
				echo "<div class='vencida-card-detail-value alert'>{$data_vencF}</div>";
				echo "</div>";
				echo "<div class='vencida-card-detail-item'>";
				echo "<div class='vencida-card-detail-label'>Cliente</div>";
				echo "<div class='vencida-card-detail-value'>{$nome_pessoa}</div>";
				echo "</div>";
				echo "<div class='vencida-card-detail-item'>";
				echo "<div class='vencida-card-detail-label'>Dias de Atraso</div>";
				echo "<div class='vencida-card-detail-value alert'>{$dias_atraso} dias</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='vencida-card-actions'>";
				
				echo "<a href='#' onclick=\"mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}', '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$pgto}')\" class='vencida-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				
				echo "<a href='#' onclick=\"baixar('{$id}', '{$valor}', '{$descricao}', '{$pgto}')\" class='vencida-card-action-btn baixar' style='flex: 0;'>";
				echo "<i class='fa fa-check'></i>";
				echo "</a>";
				
				if($tem_whatsapp){
					echo "<a href='#' onclick=\"cobrar('{$id}', '{$valor}', '{$data_venc}', '{$telefone_pessoa}', '{$descricao}')\" class='vencida-card-action-btn whatsapp' style='flex: 0;' title='Enviar cobrança'>";
					echo "<i class='fa fa-whatsapp'></i>";
					echo "</a>";
				}
				
				echo "<a href='#' onclick=\"confirmarExclusaoConta('{$id}')\" class='vencida-card-action-btn delete' style='flex: 0;'>";
				echo "<i class='fa fa-trash'></i>";
				echo "</a>";
				echo "</div>";
				echo "</div>";
				echo "`;\n";
			}
		}
		?>
		
		mobileListElement.innerHTML = mobileHtml;
	}
	
	// Executar ao carregar
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', renderMobileCards);
	} else {
		renderMobileCards();
	}
</script>

<script type="text/javascript">
function confirmarExclusaoConta(id) {
	$('#mensagem-excluir').text('Confirmar Exclusão?');
	$('#mensagem-excluir').css({
		'background': 'rgba(239, 83, 80, 0.1)',
		'color': '#ef5350',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Tem certeza que deseja excluir esta conta?</span>
			<button onclick="excluir(${id})" style="
				background: #ef5350;
				color: white;
				border: none;
				padding: 6px 16px;
				border-radius: 6px;
				cursor: pointer;
				font-weight: 600;
				margin-right: 8px;
			">Sim, Excluir</button>
			<button onclick="$('#mensagem-excluir').hide()" style="
				background: #6c757d;
				color: white;
				border: none;
				padding: 6px 16px;
				border-radius: 6px;
				cursor: pointer;
				font-weight: 600;
			">Cancelar</button>
		`);
	}, 300);
}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, foto, pessoa, link, pgto){
		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#pessoa_dados').text(pessoa);
		$('#pgto_dados').text(pgto);
		
		$('#link_mostrar').attr('href','img/contas/' + link);
		$('#target_mostrar').attr('src','img/contas/' + foto);

		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	function baixar(id, valor, descricao, pgto){
		$('#id_baixar').val(id);
		$('#valor_baixar').val(valor);			
		$('#titulo_baixar').text(descricao);
		$('#pgto_baixar').val(pgto).change();

		$('#modalBaixar').modal('show');
	}
</script>
