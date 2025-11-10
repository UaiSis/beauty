<?php 
require_once("../../../conexao.php");
$tabela = 'pagar';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%'.@$_POST['status'].'%';

$total_pago = 0;
$total_a_pagar = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '$status' ORDER BY pago asc, data_venc asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Descrição</th>	
	<th>Valor</th> 	
	<th>Vencimento</th> 	
	<th>Status</th> 
	<th>Fornecedor</th>
	<th>Funcionário</th>	
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
	$funcionario = $res[$i]['funcionario'];
	$pgto = $res[$i]['pgto'];
	
	$valorF = @number_format($valor, 2, ',', '.');
	$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
	$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
	$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));

	// Fornecedor
	$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$pessoa'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_pessoa = $res2[0]['nome'];
		$telefone_pessoa = $res2[0]['telefone'];
		$chave_pix_forn = $res2[0]['chave_pix'];
		$tipo_chave_forn = $res2[0]['tipo_chave'];
		$classe_whats = '';
	}else{
		$nome_pessoa = 'Nenhum';
		$telefone_pessoa = '';
		$classe_whats = 'ocultar';
		$chave_pix_forn = '';
		$tipo_chave_forn = '';
	}

	// Funcionário
	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_reg2 = @count($res2);
	if($total_reg2 > 0){
		$nome_func = $res2[0]['nome'];
		$telefone_func = $res2[0]['telefone'];
		$chave_pix_func = $res2[0]['chave_pix'];
		$tipo_chave_func = $res2[0]['tipo_chave'];
	}else{
		$nome_func = 'Nenhum';
		$telefone_func = '';
		$chave_pix_func = '';
		$tipo_chave_func = '';
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

	// Determinar status
	$status_badge = '';
	$row_class = '';
	$visivel_baixar = 'ocultar';

	if($pago != 'Sim'){
		$data_pgtoF = 'Pendente';
		$total_a_pagar += $valor;
		$visivel_baixar = '';
		
		if($data_venc < $data_hoje){
			$status_badge = '<span class="status-badge vencido"><i class="fa fa-times-circle"></i> Vencido</span>';
			$row_class = 'vencido';
		}else{
			$status_badge = '<span class="status-badge pendente"><i class="fa fa-clock-o"></i> Pendente</span>';
			$row_class = 'pendente';
		}
	}else{
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

	// WhatsApp
	$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_pessoa);

	// Chave PIX
	if($nome_pessoa == 'Nenhum' and $nome_func != 'Nenhum'){
		$chave = 'Pix Funcionário : Tipo '.$tipo_chave_func.' - Chave '.$chave_pix_func;
	}else if($nome_func == 'Nenhum' and $nome_pessoa != 'Nenhum'){
		$chave = 'Pix Fornecedor : Tipo '.$tipo_chave_forn.' - Chave '.$chave_pix_forn;
	}else{
		$chave = 'Nenhuma';
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
<td>{$nome_func}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$descricao}', '{$pessoa}', '{$valor}', '{$data_venc}', '{$data_pgto}', '{$tumb_arquivo}', '{$funcionario}', '{$pgto}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}',  '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$nome_func}', '{$telefone_func}', '{$chave}', '{$pgto}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="#" onclick="baixar('{$id}', '{$valor}', '{$descricao}', '{$pgto}')" class="table-action-icon baixar {$visivel_baixar}" title="Baixar Conta">
			<i class="fa fa-check-square"></i>
		</a>

		<a href="http://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text=" target="_blank" class="table-action-icon whatsapp {$classe_whats}" title="Abrir WhatsApp">
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

$total_pagoF = number_format($total_pago, 2, ',', '.');
$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

echo <<<HTML
</tbody>
</table>

<div class="totalizadores">
	<div class="total-item">
		<div class="total-label">Total Pago</div>
		<div class="total-value pago">R$ {$total_pagoF}</div>
	</div>
	<div class="total-item">
		<div class="total-label">Total à Pagar</div>
		<div class="total-value pendente">R$ {$total_a_pagarF}</div>
	</div>
</div>

<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

}else{
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-credit-card" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhuma conta encontrada</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Ajuste os filtros ou registre uma nova conta</p>
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
		$query_mobile = $pdo->query("SELECT * FROM pagar where data_venc >= '$dataInicial' and data_venc <= '$dataFinal' and pago LIKE '$status' ORDER BY pago asc, data_venc asc");
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
				$pago = $res[$i]['pago'];
				$pgto = $res[$i]['pgto'];

				$valorF = @number_format($valor, 2, ',', '.');
				$data_lancF = implode('/', array_reverse(@explode('-', $data_lanc)));
				$data_pgtoF = implode('/', array_reverse(@explode('-', $data_pgto)));
				$data_vencF = implode('/', array_reverse(@explode('-', $data_venc)));

				// Fornecedor
				$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$pessoa'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_pessoa = $res2[0]['nome'];
					$telefone_pessoa = $res2[0]['telefone'];
					$chave_pix_forn = $res2[0]['chave_pix'];
					$tipo_chave_forn = $res2[0]['tipo_chave'];
				}else{
					$nome_pessoa = 'Nenhum';
					$telefone_pessoa = '';
					$chave_pix_forn = '';
					$tipo_chave_forn = '';
				}

				// Funcionário
				$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) > 0){
					$nome_func = $res2[0]['nome'];
					$telefone_func = $res2[0]['telefone'];
					$chave_pix_func = $res2[0]['chave_pix'];
					$tipo_chave_func = $res2[0]['tipo_chave'];
				}else{
					$nome_func = 'Nenhum';
					$telefone_func = '';
					$chave_pix_func = '';
					$tipo_chave_func = '';
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
				
				if($pago != 'Sim'){
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

				// Chave PIX
				if($nome_pessoa == 'Nenhum' and $nome_func != 'Nenhum'){
					$chave = 'Pix Funcionário : Tipo '.$tipo_chave_func.' - Chave '.$chave_pix_func;
				}else if($nome_func == 'Nenhum' and $nome_pessoa != 'Nenhum'){
					$chave = 'Pix Fornecedor : Tipo '.$tipo_chave_forn.' - Chave '.$chave_pix_forn;
				}else{
					$chave = 'Nenhuma';
				}
				
				echo "mobileHtml += `";
				echo "<div class='conta-card-mobile {$card_status}'>";
				echo "<div class='conta-card-header'>";
				echo "<div class='conta-card-title'>{$descricao}</div>";
				echo "<span class='status-badge {$card_status}'><i class='fa fa-circle'></i> {$status_text}</span>";
				echo "</div>";
				
				echo "<div class='conta-card-details'>";
				echo "<div class='conta-card-detail-item'>";
				echo "<div class='conta-card-detail-label'>Valor</div>";
				echo "<div class='conta-card-detail-value' style='color: #007A63; font-size: 16px;'>R$ {$valorF}</div>";
				echo "</div>";
				echo "<div class='conta-card-detail-item'>";
				echo "<div class='conta-card-detail-label'>Vencimento</div>";
				echo "<div class='conta-card-detail-value'>{$data_vencF}</div>";
				echo "</div>";
				echo "<div class='conta-card-detail-item'>";
				echo "<div class='conta-card-detail-label'>Fornecedor</div>";
				echo "<div class='conta-card-detail-value'>{$nome_pessoa}</div>";
				echo "</div>";
				echo "<div class='conta-card-detail-item'>";
				echo "<div class='conta-card-detail-label'>Funcionário</div>";
				echo "<div class='conta-card-detail-value'>{$nome_func}</div>";
				echo "</div>";
				echo "</div>";
				
				$whats_mobile = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_pessoa);
				$tem_whatsapp = ($nome_pessoa != 'Nenhum' && !empty($telefone_pessoa));
				
				echo "<div class='conta-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$descricao}', '{$pessoa}', '{$valor}', '{$data_venc}', '{$data_pgto}', '{$tumb_arquivo}', '{$funcionario}', '{$pgto}')\" class='conta-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				
				echo "<a href='#' onclick=\"mostrar('{$descricao}', '{$valorF}', '{$data_lancF}', '{$data_vencF}', '{$data_pgtoF}', '{$nome_usuario_lanc}', '{$nome_usuario_pgto}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$nome_func}', '{$telefone_func}', '{$chave}', '{$pgto}')\" class='conta-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				
				if($pago != 'Sim'){
					echo "<a href='#' onclick=\"baixar('{$id}', '{$valor}', '{$descricao}', '{$pgto}')\" class='conta-card-action-btn baixar' style='flex: 0;'>";
					echo "<i class='fa fa-check'></i>";
					echo "</a>";
				}
				
				if($tem_whatsapp){
					echo "<a href='http://api.whatsapp.com/send?1=pt_BR&phone={$whats_mobile}&text=' target='_blank' class='conta-card-action-btn' style='background: rgba(37, 211, 102, 0.1); color: #25D366; flex: 0;'>";
					echo "<i class='fa fa-whatsapp'></i>";
					echo "</a>";
				}
				
				echo "<a href='#' onclick=\"confirmarExclusaoConta('{$id}')\" class='conta-card-action-btn delete' style='flex: 0;'>";
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
	function editar(id, descricao, pessoa, valor, data_venc, data_pgto, foto, func, pgto){
		$('#id').val(id);
		$('#descricao').val(descricao);
		$('#pessoa').val(pessoa).change();
		$('#valor').val(valor);
		$('#data_venc').val(data_venc);
		$('#data_pgto').val(data_pgto);
		$('#funcionario').val(func).change();
		$('#pgto').val(pgto).change();
						
		$('#titulo_inserir').text('Editar Conta');
		$('#btn-text').text('Salvar Alterações');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src','img/contas/' + foto);
	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lanc, data_venc, data_pgto, usuario_lanc, usuario_pgto, foto, pessoa, link, nome_func, tel_func, chave, pgto){
		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lanc_dados').text(data_lanc);
		$('#data_venc_dados').text(data_venc);
		$('#data_pgto_dados').text(data_pgto);
		$('#usuario_lanc_dados').text(usuario_lanc);
		$('#usuario_baixa_dados').text(usuario_pgto);
		$('#pessoa_dados').text(pessoa);
		$('#nome_func_dados').text(nome_func);
		$('#tel_func_dados').text(tel_func);
		$('#chave_dados').text(chave);
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
