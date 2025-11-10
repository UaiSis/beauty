<?php 
@session_start();
$nivel_usu = @$_SESSION['nivel'];
$id_usu = @$_SESSION['id'];
require_once("../../../conexao.php");
$tabela = 'receber';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%'.@$_POST['status'].'%';

$total_pago = 0;
$total_a_pagar = 0;

if($nivel_usu == "Administrador"){
	$query = $pdo->query("SELECT * FROM $tabela where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '$status' and produto != 0 ORDER BY pago asc, data_venc asc");
}else{
	$query = $pdo->query("SELECT * FROM $tabela where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '$status' and produto != 0 and usuario_lanc = '$id_usu' ORDER BY pago asc, data_venc asc");
}

$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Produto</th>	
	<th>Valor</th> 	
	<th>Vencimento</th> 	
	<th>Status</th> 
	<th>Cliente</th>
	<th>Vendedor</th>	
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
	$produto = $res[$i]['produto'];
	$pago = $res[$i]['pago'];
	$funcionario = $res[$i]['funcionario'];
	$valor2 = $res[$i]['valor2'];

	if($valor <= 0){
		$valor = $valor2;
	}
	
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
	}else{
		$nome_pessoa = 'Nenhum';
		$telefone_pessoa = 'Nenhum';
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

	// Vendedor
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_vendedor = $res2[0]['nome'];
	}else{
		$nome_vendedor = '';
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

	// Determinar status
	$status_class = '';
	$status_badge = '';
	$status_icon = '';
	$row_class = '';
	$visivel_baixar = 'ocultar';
	$visivel_comprovante = '';

	if($data_pgto == '0000-00-00'){
		$data_pgtoF = 'Pendente';
		$total_a_pagar += $valor;
		$visivel_baixar = '';
		$visivel_comprovante = 'ocultar';
		
		if($data_venc < $data_hoje){
			$status_class = 'vencido';
			$status_badge = '<span class="status-badge vencido"><i class="fa fa-times-circle"></i> Vencido</span>';
			$row_class = 'vencido';
		}else{
			$status_class = 'pendente';
			$status_badge = '<span class="status-badge pendente"><i class="fa fa-clock-o"></i> Pendente</span>';
			$row_class = 'pendente';
		}
	}else{
		$status_class = 'pago';
		$status_badge = '<span class="status-badge pago"><i class="fa fa-check-circle"></i> Pago</span>';
		$total_pago += $valor;
		$row_class = 'pago';
	}

	// Extensão do arquivo
	$ext = pathinfo($foto, PATHINFO_EXTENSION);
	if($ext == 'pdf'){
		$tumb_arquivo = 'pdf.png';
	}else if($ext == 'rar' || $ext == 'zip'){
		$tumb_arquivo = 'rar.png';
	}else{
		$tumb_arquivo = $foto;
	}

echo <<<HTML
<tr class="{$row_class}">
<td>
	<div style="display: flex; align-items: center; gap: 8px;">
		{$status_badge}
		<span style="font-weight: 600;">{$descricao}</span>
	</div>
</td>
<td><span class="valor-cell">R$ {$valorF}</span></td>
<td>{$data_vencF}</td>
<td>{$data_pgtoF}</td>
<td>{$nome_pessoa}</td>
<td>{$nome_vendedor}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}',  '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="#" onclick="confirmarBaixa('{$id}')" class="table-action-icon baixar {$visivel_baixar}" title="Baixar Conta">
			<i class="fa fa-check-square"></i>
		</a>

		<a href="#" onclick="gerarComprovante('{$id}')" class="table-action-icon comprovante {$visivel_comprovante}" title="Gerar Comprovante">
			<i class="fa fa-file-pdf-o"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoVenda('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

$total_pagoF = number_format($total_pago, 2, ',', '.');
$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

echo <<<HTML
</tbody>
</table>

<div class="totalizadores">
	<div class="total-item">
		<div class="total-label">Total Recebido</div>
		<div class="total-value recebido">R$ {$total_pagoF}</div>
	</div>
	<div class="total-item">
		<div class="total-label">Total à Receber</div>
		<div class="total-value pendente">R$ {$total_a_pagarF}</div>
	</div>
</div>

<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-shopping-cart" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhuma venda encontrada</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Ajuste os filtros ou registre uma nova venda</p>
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
		if($nivel_usu == "Administrador"){
			$query_mobile = $pdo->query("SELECT * FROM receber where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '$status' and produto != 0 ORDER BY pago asc, data_venc asc");
		}else{
			$query_mobile = $pdo->query("SELECT * FROM receber where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '$status' and produto != 0 and usuario_lanc = '$id_usu' ORDER BY pago asc, data_venc asc");
		}
		
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
				$funcionario = $res[$i]['funcionario'];
				$valor2 = $res[$i]['valor2'];

				if($valor <= 0){
					$valor = $valor2;
				}

				$valorF = number_format($valor, 2, ',', '.');
				$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
				$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
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

				// Vendedor
				$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_vendedor = $res2[0]['nome'];
				}else{
					$nome_vendedor = '';
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

				// Status
				$card_status = '';
				$status_text = '';
				
				if($data_pgto == '0000-00-00'){
					$data_pgtoF = 'Pendente';
					if($data_venc < $data_hoje){
						$card_status = 'vencido';
						$status_text = 'VENCIDO';
					}else{
						$card_status = 'pendente';
						$status_text = 'PENDENTE';
					}
				}else{
					$card_status = 'pago';
					$status_text = 'PAGO';
				}

				$ext = pathinfo($foto, PATHINFO_EXTENSION);
				if($ext == 'pdf'){
					$tumb_arquivo = 'pdf.png';
				}else if($ext == 'rar' || $ext == 'zip'){
					$tumb_arquivo = 'rar.png';
				}else{
					$tumb_arquivo = $foto;
				}
				
				echo "mobileHtml += `";
				echo "<div class='venda-card-mobile {$card_status}'>";
				echo "<div class='venda-card-header'>";
				echo "<div class='venda-card-title'>{$descricao}</div>";
				echo "<span class='status-badge {$card_status}'><i class='fa fa-circle'></i> {$status_text}</span>";
				echo "</div>";
				
				echo "<div class='venda-card-details'>";
				echo "<div class='venda-card-detail-item'>";
				echo "<div class='venda-card-detail-label'>Valor</div>";
				echo "<div class='venda-card-detail-value' style='color: #007A63; font-size: 16px;'>R$ {$valorF}</div>";
				echo "</div>";
				echo "<div class='venda-card-detail-item'>";
				echo "<div class='venda-card-detail-label'>Vencimento</div>";
				echo "<div class='venda-card-detail-value'>{$data_vencF}</div>";
				echo "</div>";
				echo "<div class='venda-card-detail-item'>";
				echo "<div class='venda-card-detail-label'>Cliente</div>";
				echo "<div class='venda-card-detail-value'>{$nome_pessoa}</div>";
				echo "</div>";
				echo "<div class='venda-card-detail-item'>";
				echo "<div class='venda-card-detail-label'>Vendedor</div>";
				echo "<div class='venda-card-detail-value'>{$nome_vendedor}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='venda-card-actions'>";
				echo "<a href='#' onclick=\"mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}', '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}')\" class='venda-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				
				if($card_status != 'pago'){
					echo "<a href='#' onclick=\"confirmarBaixa('{$id}')\" class='venda-card-action-btn baixar'>";
					echo "<i class='fa fa-check'></i> Baixar";
					echo "</a>";
				}else{
					echo "<a href='#' onclick=\"gerarComprovante('{$id}')\" class='venda-card-action-btn view' style='background: rgba(156, 39, 176, 0.1); color: #9c27b0;'>";
					echo "<i class='fa fa-file-pdf-o'></i> PDF";
					echo "</a>";
				}
				
				echo "<a href='#' onclick=\"confirmarExclusaoVenda('{$id}')\" class='venda-card-action-btn delete' style='flex: 0;'>";
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
function confirmarExclusaoVenda(id) {
	$('#mensagem-excluir').text('Confirmar Exclusão?');
	$('#mensagem-excluir').css({
		'background': 'rgba(239, 83, 80, 0.1)',
		'color': '#ef5350',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Tem certeza que deseja excluir esta venda?</span>
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

function confirmarBaixa(id) {
	$('#mensagem-excluir').text('Confirmar Baixa?');
	$('#mensagem-excluir').css({
		'background': 'rgba(0, 216, 150, 0.1)',
		'color': '#00d896',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Confirmar baixa desta venda?</span>
			<button onclick="baixar(${id})" style="
				background: #00d896;
				color: white;
				border: none;
				padding: 6px 16px;
				border-radius: 6px;
				cursor: pointer;
				font-weight: 600;
				margin-right: 8px;
			">Sim, Baixar</button>
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
	function editar(id, produto, pessoa, valor, data_venc, data_pgto, foto){
		$('#id').val(id);
		$('#produto').val(produto).change();
		$('#pessoa').val(pessoa).change();
		$('#valor').val(valor);
		$('#data_venc').val(data_venc);
		$('#data_pgto').val(data_pgto);
						
		$('#titulo_inserir').text('Editar Venda');
		$('#btn-text').text('Salvar Alterações');
		$('#modalForm').modal('show');

		$('#target').attr('src','img/contas/' + foto);
	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, foto, pessoa, link, telefone){
		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#pessoa_dados').text(pessoa);
		$('#telefone_dados').text(telefone);
		
		$('#link_mostrar').attr('href','img/contas/' + link);
		$('#target_mostrar').attr('src','img/contas/' + foto);

		$('#modalDados').modal('show');
	}
</script>
