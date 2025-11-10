<?php 
require_once("../../../conexao.php");
$tabela = 'clientes';
$data_atual = date('Y-m-d');

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


$query = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca' or cpf LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Cliente</th>	
	<th>Telefone</th> 
	<th>CPF</th> 	
	<th>Cadastro</th> 	
	<th>Nascimento</th> 
	<th>Retorno</th> 
	<th>Cartões</th> 
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$data_nasc = $res[$i]['data_nasc'];
	$data_cad = $res[$i]['data_cad'];	
	$telefone = $res[$i]['telefone'];
	$endereco = $res[$i]['endereco'];
	$cartoes = $res[$i]['cartoes'];
	$data_retorno = $res[$i]['data_retorno'];
	$ultimo_servico = $res[$i]['ultimo_servico'];
	$cpf = $res[$i]['cpf'];
	$marketing = $res[$i]['marketing'];

	if ($marketing == 'Não') {
			$ocultar_mark = 'ocultar';
		} else {
			$ocultar_mark = '';
		}

	$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));
	$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));
	$data_retornoF = implode('/', array_reverse(@explode('-', $data_retorno)));
	
	if($data_nascF == '00/00/0000'){
		$data_nascF = 'Sem Lançamento';
	}
	
	
	$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

	$query2 = $pdo->query("SELECT * FROM servicos where id = '$ultimo_servico'");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$nome_servico = $res2[0]['nome'];
	}else{
		$nome_servico = 'Nenhum!';
	}


	$query2 = $pdo->query("SELECT * FROM receber where pessoa = '$id' order by id desc limit 1");
	$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res2) > 0){
		$obs_servico = $res2[0]['obs'];
		$valor_servico = $res2[0]['valor'];
		$data_servico = $res2[0]['data_lanc'];
		$valor_servico = number_format($valor_servico, 2, ',', '.');
		$data_servico = implode('/', array_reverse(@explode('-', $data_servico)));
	}else{
		$obs_servico = '';
		$valor_servico = '';
		$data_servico = '';
	}

	
	

	if($data_retorno != "" and @strtotime($data_retorno) <  @strtotime($data_atual)){
		$classe_retorno = 'text-danger';
	}else{
		$classe_retorno = '';
	}
	
	 $query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca' or cpf LIKE '$busca'");
	    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	    $total_reg2 = @count($res2);

	     $num_paginas = ceil($total_reg2/$itens_por_pagina);

	// Gerar iniciais para o avatar
	$iniciais = '';
	$palavras = explode(' ', $nome);
	if(count($palavras) >= 2){
		$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
	}else{
		$iniciais = strtoupper(substr($nome, 0, 2));
	}

	// Usar cor verde padrão para todos os avatares
	$cor_avatar = '#007A63';

	$telefone_display = !empty($telefone) ? $telefone : '-';
	$cpf_display = !empty($cpf) ? $cpf : '-';

echo <<<HTML
<tr>
<td>
	<div class="client-info-cell">
		<div class="client-avatar" style="background: {$cor_avatar};">
			{$iniciais}
		</div>
		<div>
			<div class="client-name">{$nome}</div>
			<div class="client-cpf">{$cpf_display}</div>
		</div>
	</div>
</td>
<td>{$telefone_display}</td>
<td>{$cpf_display}</td>
<td>{$data_cadF}</td>
<td>{$data_nascF}</td>
<td class="{$classe_retorno}">{$data_retornoF}</td>
<td>{$cartoes}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$telefone}', '{$endereco}', '{$data_nasc}', '{$cartoes}', '{$cpf}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="mostrar('{$id}','{$nome}', '{$telefone}', '{$cartoes}', '{$data_cadF}', '{$data_nascF}', '{$endereco}', '{$data_retornoF}', '{$nome_servico}', '{$obs_servico}', '{$valor_servico}', '{$data_servico}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" class="table-action-icon whatsapp" title="WhatsApp">
			<i class="fa fa-whatsapp"></i>
		</a>

		<a href="#" onclick="contrato('{$id}','{$nome}')" class="table-action-icon contract" title="Gerar Contrato">
			<i class="fa fa-file-text"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoCliente('{$id}')" class="table-action-icon delete" title="Excluir">
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
<div align="center" id="mensagem-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>
HTML;

// Renderizar paginação se houver mais de uma página
if($num_paginas > 1){
echo <<<HTML
<div class="pagination-modern">
	<ul style="display: flex; gap: 8px; padding: 0; margin: 0; list-style: none;">
		<li class="page-item">
			<a onclick="listarClientes(0)" class="paginador" href="#" aria-label="Previous">
				<i class="fa fa-angle-double-left"></i>
			</a>
		</li>
HTML;

	for($i=0;$i<$num_paginas;$i++){
		$estilo = "";
		if($pagina >= ($i - 2) and $pagina <= ($i + 2)){
			if($pagina == $i)
				$estilo = "active";

			$pag = $i+1;
			$ultimo_reg = $num_paginas - 1;

echo <<<HTML
		<li class="page-item {$estilo}">
			<a onclick="listarClientes({$i})" class="paginador" href="#">{$pag}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarClientes({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
		<i class="fa fa-users"></i>
		<p>Nenhum cliente cadastrado</p>
		<small>Adicione o primeiro cliente clicando no botão "Novo Cliente"</small>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca' or cpf LIKE '$busca' ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$nome = $res_mobile[$i]['nome'];
				$telefone = $res_mobile[$i]['telefone'];
				$cpf = $res_mobile[$i]['cpf'];
				$cartoes = $res_mobile[$i]['cartoes'];
				$data_cad = $res_mobile[$i]['data_cad'];
				$data_nasc = $res_mobile[$i]['data_nasc'];
				$endereco = $res_mobile[$i]['endereco'];
				$data_retorno = $res_mobile[$i]['data_retorno'];
				$ultimo_servico = $res_mobile[$i]['ultimo_servico'];
				
				$data_cadF = implode('/', array_reverse(@explode('-', $data_cad)));
				$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));
				$data_retornoF = implode('/', array_reverse(@explode('-', $data_retorno)));
				
				if($data_nascF == '00/00/0000'){
					$data_nascF = 'Sem Lançamento';
				}
				
				$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
				
				// Buscar nome do serviço
				$query_serv = $pdo->query("SELECT * FROM servicos where id = '$ultimo_servico'");
				$res_serv = $query_serv->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res_serv) > 0){
					$nome_servico = $res_serv[0]['nome'];
				}else{
					$nome_servico = 'Nenhum!';
				}
				
				// Buscar último recebimento
				$query_rec = $pdo->query("SELECT * FROM receber where pessoa = '$id' order by id desc limit 1");
				$res_rec = $query_rec->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res_rec) > 0){
					$obs_servico = $res_rec[0]['obs'];
					$valor_servico = $res_rec[0]['valor'];
					$data_servico = $res_rec[0]['data_lanc'];
					$valor_servico = number_format($valor_servico, 2, ',', '.');
					$data_servico = implode('/', array_reverse(@explode('-', $data_servico)));
				}else{
					$obs_servico = '';
					$valor_servico = '';
					$data_servico = '';
				}
				
				$telefone_display = !empty($telefone) ? $telefone : '-';
				$cpf_display = !empty($cpf) ? $cpf : '-';
				
				// Gerar iniciais
				$iniciais = '';
				$palavras = explode(' ', $nome);
				if(count($palavras) >= 2){
					$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
				}else{
					$iniciais = strtoupper(substr($nome, 0, 2));
				}
				
				echo "mobileHtml += `";
				echo "<div class='client-card-mobile'>";
				echo "<div class='client-card-header'>";
				echo "<div class='client-card-avatar'>{$iniciais}</div>";
				echo "<div class='client-card-info'>";
				echo "<div class='client-card-name'>{$nome}</div>";
				echo "<div class='client-card-phone'>{$telefone_display}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='client-card-details'>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>CPF</div>";
				echo "<div class='client-card-detail-value'>{$cpf_display}</div>";
				echo "</div>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Cartões</div>";
				echo "<div class='client-card-detail-value'>{$cartoes}</div>";
				echo "</div>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Cadastro</div>";
				echo "<div class='client-card-detail-value'>{$data_cadF}</div>";
				echo "</div>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Retorno</div>";
				echo "<div class='client-card-detail-value'>{$data_retornoF}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='client-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$telefone}', '{$endereco}', '{$data_nasc}', '{$cartoes}', '{$cpf}')\" class='client-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"mostrar('{$id}','{$nome}', '{$telefone}', '{$cartoes}', '{$data_cadF}', '{$data_nascF}', '{$endereco}', '{$data_retornoF}', '{$nome_servico}', '{$obs_servico}', '{$valor_servico}', '{$data_servico}')\" class='client-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				echo "<a href='http://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text=' target='_blank' class='client-card-action-btn whatsapp'>";
				echo "<i class='fa fa-whatsapp'></i> WhatsApp";
				echo "</a>";
				echo "<a href='#' onclick=\"contrato('{$id}','{$nome}')\" class='client-card-action-btn contract'>";
				echo "<i class='fa fa-file-text'></i> Contrato";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoCliente('{$id}')\" class='client-card-action-btn delete' style='background: rgba(239, 83, 80, 0.1); color: #ef5350;'>";
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
	function confirmarExclusaoCliente(id){
		$('#mensagem-excluir').text('Confirmar Exclusão?');
		$('#mensagem-excluir').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-excluir').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja excluir este cliente?</span>
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
	function editar(id, nome, telefone, endereco, data_nasc, cartoes, cpf){
		$('#id').val(id);
		$('#nome').val(nome);		
		$('#telefone').val(telefone);		
		$('#endereco').val(endereco);
		$('#data_nasc').val(data_nasc);
		$('#cartao').val(cartoes);
		$('#cpf').val(cpf);

		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#endereco').val('');
		$('#data_nasc').val('');
		$('#cartao').val('0');
		$('#cpf').val('');
	}
</script>



<script type="text/javascript">
	function mostrar(id, nome, telefone, cartoes, data_cad, data_nasc, endereco, retorno, servico, obs, valor, data){

		$('#nome_dados').text(nome);		
		$('#data_cad_dados').text(data_cad);
		$('#data_nasc_dados').text(data_nasc);
		$('#cartoes_dados').text(cartoes);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#retorno_dados').text(retorno);		
		$('#servico_dados').text(servico);
		$('#obs_dados_tab').text(obs);
		$('#servico_dados_tab').text(servico);
		$('#data_dados_tab').text(data);
		$('#valor_dados_tab').text(valor);

		$('#modalDados').modal('show');
		listarDebitos(id)
	}
</script>



<script type="text/javascript">
	function contrato(id, nome){		
		$('#titulo_contrato').text(nome);
		$('#id_contrato').val(id);		
		$('#modalContrato').modal('show');
		listarTextoContrato(id);
		
	}



</script>




<script>
	// EXCLUIR MARKETING #######################################
	function excluirMarketing(id) {
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
				cancelButton: "btn btn-danger me-1"
			},
			buttonsStyling: false
		});
		swalWithBootstrapButtons.fire({
			title: "Excluir do Marketing?",
			text: "Ele não receberá mais mensagens!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Sim, Excluir!",
			cancelButtonText: "Não, Cancelar!",
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'paginas/' + pag + "/excluir_marketing.php",
					method: 'POST',
					data: { id },
					dataType: "html",

					success: function (mensagem) {
						if (mensagem.trim() == "Excluído com Sucesso") {

							// Ação de exclusão aqui
							Swal.fire({
								title: 'Excluido com Sucesso!',
								text: 'Fecharei em 1 segundo.',
								icon: "success",
								timer: 1000
							})
							//excluido();
							var pagina = $("#pagina").val();
							listarClientes(pagina);


						} else {
							$('#mensagem-excluir').addClass('text-danger')
							$('#mensagem-excluir').text(mensagem)
						}
					}
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				swalWithBootstrapButtons.fire({
					title: "Cancelado",
					text: "Fecharei em 1 segundo.",
					icon: "error",
					timer: 1000,
					timerProgressBar: true,
				});
			}
		});
	}
</script>