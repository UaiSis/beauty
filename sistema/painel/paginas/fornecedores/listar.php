<?php 
require_once("../../../conexao.php");
$tabela = 'fornecedores';

$busca = '%'.@$_POST['busca'].'%';

// pegar a pagina atual
if(@$_POST['pagina'] == ""){
    @$_POST['pagina'] = 0;
}

// pegar itens por página
$itens_por_pagina = @$_POST['itens_por_pagina'];
if($itens_por_pagina == ""){
	$itens_por_pagina = $itens_pag;
}

$pagina = intval(@$_POST['pagina']);
$limite = $pagina * $itens_por_pagina;

$query = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Fornecedor</th>	
	<th>Telefone</th> 	
	<th>Cadastro</th>
	<th>Tipo Chave Pix</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	
	$data_cad = $res[$i]['data_cad'];	
	$telefone = $res[$i]['telefone'];
	$endereco = $res[$i]['endereco'];
	$tipo_chave = $res[$i]['tipo_chave'];
	$chave_pix = $res[$i]['chave_pix'];

	

	$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
	
	$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

	// Gerar iniciais para o avatar
	$iniciais = '';
	$palavras = explode(' ', $nome);
	if(count($palavras) >= 2){
		$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
	}else{
		$iniciais = strtoupper(substr($nome, 0, 2));
	}

	$telefone_display = !empty($telefone) ? $telefone : '-';
	$tipo_chave_display = !empty($tipo_chave) ? $tipo_chave : '-';

echo <<<HTML
<tr>
<td>
	<div class="fornecedor-info-cell">
		<div class="fornecedor-avatar">
			{$iniciais}
		</div>
		<div>
			<div class="fornecedor-name">{$nome}</div>
			<div class="fornecedor-phone">{$telefone_display}</div>
		</div>
	</div>
</td>
<td>{$telefone_display}</td>
<td>{$data_cadF}</td>
<td>{$tipo_chave_display}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$telefone}', '{$endereco}', '{$tipo_chave}', '{$chave_pix}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="mostrar('{$nome}', '{$telefone}', '{$data_cadF}', '{$endereco}', '{$tipo_chave}', '{$chave_pix}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" class="table-action-icon whatsapp" title="WhatsApp">
			<i class="fa fa-whatsapp"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoFornecedor('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_reg2 = @count($res2);

$num_paginas = ceil($total_reg2/$itens_por_pagina);

echo <<<HTML
</tbody>
</table>
<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarFornecedores(0)" class="paginador" href="#" aria-label="Previous">
				<i class="fa fa-angle-double-left"></i>
			</a>
		</li>
HTML;

	for($i=0;$i<$num_paginas;$i++){
		$estilo = "";
		if($pagina >= ($i - 2) and $pagina <= ($i + 2)){
			if($pagina == $i)
				$estilo = "active";

			$pag_num = $i+1;
			$ultimo_reg = $num_paginas - 1;

echo <<<HTML
		<li class="page-item {$estilo}">
			<a onclick="listarFornecedores({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarFornecedores({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
				<i class="fa fa-angle-double-right"></i>
			</a>
		</li>
	</ul>
</div>
HTML;
}

echo <<<HTML

HTML;  


}else{
	echo '<div class="empty-state">
		<i class="fa fa-truck"></i>
		<p>Nenhum fornecedor cadastrado</p>
		<small>Adicione o primeiro fornecedor clicando no botão "Novo Fornecedor"</small>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$nome = $res_mobile[$i]['nome'];
				$telefone = $res_mobile[$i]['telefone'];
				$endereco = $res_mobile[$i]['endereco'];
				$tipo_chave = $res_mobile[$i]['tipo_chave'];
				$chave_pix = $res_mobile[$i]['chave_pix'];
				$data_cad = $res_mobile[$i]['data_cad'];
				
				$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
				$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
				
				$telefone_display = !empty($telefone) ? $telefone : '-';
				$tipo_chave_display = !empty($tipo_chave) ? $tipo_chave : '-';
				$chave_pix_display = !empty($chave_pix) ? $chave_pix : '-';
				
				// Gerar iniciais
				$iniciais = '';
				$palavras = explode(' ', $nome);
				if(count($palavras) >= 2){
					$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
				}else{
					$iniciais = strtoupper(substr($nome, 0, 2));
				}
				
				echo "mobileHtml += `";
				echo "<div class='fornecedor-card-mobile'>";
				echo "<div class='fornecedor-card-header'>";
				echo "<div class='fornecedor-card-avatar'>{$iniciais}</div>";
				echo "<div class='fornecedor-card-info'>";
				echo "<div class='fornecedor-card-name'>{$nome}</div>";
				echo "<div class='fornecedor-card-phone'>{$telefone_display}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='fornecedor-card-details'>";
				echo "<div class='fornecedor-card-detail-item'>";
				echo "<div class='fornecedor-card-detail-label'>Cadastro</div>";
				echo "<div class='fornecedor-card-detail-value'>{$data_cadF}</div>";
				echo "</div>";
				echo "<div class='fornecedor-card-detail-item'>";
				echo "<div class='fornecedor-card-detail-label'>Tipo Chave</div>";
				echo "<div class='fornecedor-card-detail-value'>{$tipo_chave_display}</div>";
				echo "</div>";
				echo "<div class='fornecedor-card-detail-item' style='grid-column: span 2;'>";
				echo "<div class='fornecedor-card-detail-label'>Chave Pix</div>";
				echo "<div class='fornecedor-card-detail-value'>{$chave_pix_display}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='fornecedor-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$telefone}', '{$endereco}', '{$tipo_chave}', '{$chave_pix}')\" class='fornecedor-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"mostrar('{$nome}', '{$telefone}', '{$data_cadF}', '{$endereco}', '{$tipo_chave}', '{$chave_pix}')\" class='fornecedor-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				echo "<a href='http://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text=' target='_blank' class='fornecedor-card-action-btn whatsapp'>";
				echo "<i class='fa fa-whatsapp'></i> WhatsApp";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoFornecedor('{$id}')\" class='fornecedor-card-action-btn delete'>";
				echo "<i class='fa fa-trash'></i> Excluir";
				echo "</a>";
				echo "</div>";
				
				echo "</div>";
				echo "`;";
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
	function confirmarExclusaoFornecedor(id){
		$('#mensagem-excluir').text('Confirmar Exclusão?');
		$('#mensagem-excluir').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-excluir').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja excluir este fornecedor?</span>
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
	function editar(id, nome, telefone, endereco, tipo_chave, chave_pix){
		$('#id').val(id);
		$('#nome').val(nome);		
		$('#telefone').val(telefone);		
		$('#endereco').val(endereco);
		$('#chave_pix').val(chave_pix);
		$('#tipo_chave').val(tipo_chave).change();
				
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#endereco').val('');
		$('#chave_pix').val('');
		
	}
</script>



<script type="text/javascript">
	function mostrar(nome, telefone, data_cad, endereco, tipo_chave, chave_pix){

		$('#nome_dados').text(nome);		
		$('#data_cad_dados').text(data_cad);
		
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#tipo_chave_dados').text(tipo_chave);
		$('#chave_pix_dados').text(chave_pix);		

		$('#modalDados').modal('show');
	}
</script>