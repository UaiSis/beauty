<?php 
require_once("../../../conexao.php");
$tabela = 'servicos';

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

if($tipo_comissao == 'Porcentagem'){
	$tipo_comissao = '%';
}

$query = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Serviço</th>	
	<th>Categoria</th> 	
	<th>Valor</th> 	
	<th>Dias Retorno</th> 
	<th>Comissão <small>({$tipo_comissao})</small></th>	
	<th>Tempo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$ativo = $res[$i]['ativo'];
	$categoria = $res[$i]['categoria'];
	$dias_retorno = $res[$i]['dias_retorno'];
	$valor = $res[$i]['valor'];
	$foto = $res[$i]['foto'];
	$comissao = $res[$i]['comissao'];
	$tempo = $res[$i]['tempo'];

	$valorF = number_format($valor, 2, ',', '.');

	
	if($ativo == 'Sim'){
			$icone = 'fa-check-square';
			$titulo_link = 'Desativar Item';
			$acao = 'Não';
			$classe_linha = '';
		}else{
			$icone = 'fa-square-o';
			$titulo_link = 'Ativar Item';
			$acao = 'Sim';
			$classe_linha = 'text-muted';
		}


		$query2 = $pdo->query("SELECT * FROM cat_servicos where id = '$categoria'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_cat = $res2[0]['nome'];
		}else{
			$nome_cat = 'Sem Referência!';
		}


		if($tipo_comissao == '%'){
			$comissaoF = number_format($comissao, 0, ',', '.').'%';
			
			}else{
				$comissaoF = 'R$ '.number_format($comissao, 2, ',', '.');
			}


$action_class = ($ativo == 'Sim') ? 'active' : 'inactive';
$tempo_display = $tempo > 0 ? $tempo.' min' : '-';

echo <<<HTML
<tr class="{$classe_linha}">
<td>
	<div class="servico-info-cell">
		<img src="img/servicos/{$foto}" class="servico-foto" alt="{$nome}">
		<div class="servico-name">{$nome}</div>
	</div>
</td>
<td>{$nome_cat}</td>
<td>R$ {$valorF}</td>
<td>{$dias_retorno} dias</td>
<td>{$comissaoF}</td>
<td>{$tempo_display}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$valor}', '{$categoria}', '{$dias_retorno}', '{$foto}', '{$comissao}', '{$tempo}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="mostrar('{$nome}', '{$valorF}', '{$nome_cat}', '{$dias_retorno}',  '{$ativo}', '{$foto}', '{$comissaoF}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="#" onclick="ativar('{$id}', '{$acao}')" class="table-action-icon {$action_class}" title="{$titulo_link}">
			<i class="fa {$icone}"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoServico('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca'");
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
			<a onclick="listarServicos(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarServicos({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarServicos({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
		<i class="fa fa-scissors"></i>
		<p>Nenhum serviço cadastrado</p>
		<small>Adicione o primeiro serviço clicando no botão "Novo Serviço"</small>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$nome = $res_mobile[$i]['nome'];
				$ativo = $res_mobile[$i]['ativo'];
				$categoria = $res_mobile[$i]['categoria'];
				$dias_retorno = $res_mobile[$i]['dias_retorno'];
				$valor = $res_mobile[$i]['valor'];
				$foto = $res_mobile[$i]['foto'];
				$comissao = $res_mobile[$i]['comissao'];
				$tempo = $res_mobile[$i]['tempo'];
				
				$valorF = number_format($valor, 2, ',', '.');
				
				if($ativo == 'Sim'){
					$acao = 'Não';
					$status_text = 'Ativo';
					$status_class = 'ativo';
				}else{
					$acao = 'Sim';
					$status_text = 'Inativo';
					$status_class = 'inativo';
				}
				
				$query_cat = $pdo->query("SELECT * FROM cat_servicos where id = '$categoria'");
				$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res_cat) > 0){
					$nome_cat = $res_cat[0]['nome'];
				}else{
					$nome_cat = 'Sem Categoria';
				}
				
				if($tipo_comissao == '%'){
					$comissaoF = number_format($comissao, 0, ',', '.').'%';
				}else{
					$comissaoF = 'R$ '.number_format($comissao, 2, ',', '.');
				}
				
				$tempo_display = $tempo > 0 ? $tempo.' min' : '-';
				
				echo "mobileHtml += `";
				echo "<div class='servico-card-mobile'>";
				echo "<div class='servico-card-header'>";
				echo "<img src='img/servicos/{$foto}' class='servico-card-foto' alt='{$nome}'>";
				echo "<div class='servico-card-info'>";
				echo "<div class='servico-card-name'>{$nome}</div>";
				echo "<div class='servico-card-category'>{$nome_cat}</div>";
				echo "</div>";
				echo "<span class='status-badge {$status_class}'>{$status_text}</span>";
				echo "</div>";
				
				echo "<div class='servico-card-details'>";
				echo "<div class='servico-card-detail-item'>";
				echo "<div class='servico-card-detail-label'>Valor</div>";
				echo "<div class='servico-card-detail-value'>R$ {$valorF}</div>";
				echo "</div>";
				echo "<div class='servico-card-detail-item'>";
				echo "<div class='servico-card-detail-label'>Comissão</div>";
				echo "<div class='servico-card-detail-value'>{$comissaoF}</div>";
				echo "</div>";
				echo "<div class='servico-card-detail-item'>";
				echo "<div class='servico-card-detail-label'>Dias Retorno</div>";
				echo "<div class='servico-card-detail-value'>{$dias_retorno} dias</div>";
				echo "</div>";
				echo "<div class='servico-card-detail-item'>";
				echo "<div class='servico-card-detail-label'>Tempo</div>";
				echo "<div class='servico-card-detail-value'>{$tempo_display}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='servico-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$valor}', '{$categoria}', '{$dias_retorno}', '{$foto}', '{$comissao}', '{$tempo}')\" class='servico-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"mostrar('{$nome}', '{$valorF}', '{$nome_cat}', '{$dias_retorno}', '{$ativo}', '{$foto}', '{$comissaoF}')\" class='servico-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoServico('{$id}')\" class='servico-card-action-btn delete'>";
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
	function confirmarExclusaoServico(id){
		$('#mensagem-excluir').text('Confirmar Exclusão?');
		$('#mensagem-excluir').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-excluir').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja excluir este serviço?</span>
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
	function editar(id, nome, valor, categoria, dias_retorno, foto, comissao, tempo){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor').val(valor);
		$('#categoria').val(categoria).change();
		$('#dias_retorno').val(dias_retorno);
		$('#comissao').val(comissao);
		$('#tempo').val(tempo);
				
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src','img/servicos/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#valor').val('');
		$('#dias_retorno').val('');		
		$('#comissao').val('');
		$('#foto').val('');
		$('#target').attr('src','img/servicos/sem-foto.jpg');
		$('#tempo').val('');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, valor, categoria, dias_retorno, ativo, foto, comissao){

		$('#nome_dados').text(nome);
		$('#valor_dados').text(valor);
		$('#categoria_dados').text(categoria);
		$('#dias_retorno_dados').text(dias_retorno);
		$('#ativo_dados').text(ativo);
		$('#comissao_dados').text(comissao);
		
		$('#target_mostrar').attr('src','img/servicos/' + foto);

		$('#modalDados').modal('show');
	}
</script>