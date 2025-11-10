<?php 
require_once("../../../conexao.php");
$tabela = 'clientes';
$data_atual = date('Y-m-d');

$query = $pdo->query("SELECT * FROM $tabela where alertado != 'Sim' and data_retorno < curDate() ORDER BY data_retorno asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Cliente</th>	
	<th>Telefone</th> 		
	<th>Data Retorno</th> 
	<th>Último Serviço</th>
	<th>Dias Atraso</th> 	
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

	if($data_retorno != "" and @@strtotime($data_retorno) <  @@strtotime($data_atual)){
		$classe_retorno = 'text-danger';
	}else{
		$classe_retorno = '';
	}


//diferença de dias
$data_inicio = new DateTime($data_retorno);
$data_fim = new DateTime($data_atual);
$intvl = $data_inicio->diff($data_fim);

//echo $intvl->y . " year, " . $intvl->m." months and ".$intvl->d." day"; 
//echo "\n";
// Total amount of days
//echo $intvl->days . " days ";	
$dias = $intvl->days;

$url_agendamento = $url_sistema.'agendamento';

	// Gerar iniciais para o avatar
	$iniciais = '';
	$palavras = explode(' ', $nome);
	if(count($palavras) >= 2){
		$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
	}else{
		$iniciais = strtoupper(substr($nome, 0, 2));
	}

	$telefone_display = !empty($telefone) ? $telefone : '-';

echo <<<HTML
<tr>
<td>
	<div class="client-info-cell">
		<div class="client-avatar">
			{$iniciais}
		</div>
		<div>
			<div class="client-name">{$nome}</div>
			<div class="client-phone">{$telefone_display}</div>
		</div>
	</div>
</td>
<td>{$telefone_display}</td>
<td class="{$classe_retorno}">{$data_retornoF}</td>
<td>{$nome_servico}</td>
<td>
	<span class="dias-badge atrasado">{$dias} dias</span>
</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="mostrar('{$nome}', '{$telefone}', '{$cartoes}', '{$data_cadF}', '{$data_nascF}', '{$endereco}', '{$data_retornoF}', '{$nome_servico}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a onclick="alertar('{$id}')" href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=Olá $nome, já faz $dias dias que você não vem dar um trato no visual, caso queira fazer um novo agendamento é só acessar nosso site $url_agendamento, será um prazer atendê-lo novamente!!" target="_blank" class="table-action-icon whatsapp" title="Enviar WhatsApp">
			<i class="fa fa-whatsapp"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

echo <<<HTML
</tbody>
</table>
HTML;


}else{
	echo '<div class="empty-state">
		<i class="fa fa-smile-o"></i>
		<p>Nenhum cliente com retorno atrasado</p>
		<small>Todos os clientes estão em dia!</small>
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
		$query_mobile = $pdo->query("SELECT * FROM $tabela where alertado != 'Sim' and data_retorno < curDate() ORDER BY data_retorno asc");
		$res_mobile = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg_mobile = @count($res_mobile);
		if($total_reg_mobile > 0){
			for($i=0; $i < $total_reg_mobile; $i++){
				$id = $res_mobile[$i]['id'];
				$nome = $res_mobile[$i]['nome'];
				$telefone = $res_mobile[$i]['telefone'];
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
				
				// Calcular dias
				$data_inicio = new DateTime($data_retorno);
				$data_fim = new DateTime($data_atual);
				$intvl = $data_inicio->diff($data_fim);
				$dias = $intvl->days;
				
				$url_agendamento = $url_sistema.'agendamento';
				$telefone_display = !empty($telefone) ? $telefone : '-';
				
				// Gerar iniciais
				$iniciais = '';
				$palavras = explode(' ', $nome);
				if(count($palavras) >= 2){
					$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
				}else{
					$iniciais = strtoupper(substr($nome, 0, 2));
				}
				
				echo "mobileHtml += `";
				echo "<div class='client-retorno-card-mobile'>";
				echo "<div class='client-card-header'>";
				echo "<div class='client-card-avatar'>{$iniciais}</div>";
				echo "<div class='client-card-info'>";
				echo "<div class='client-card-name'>{$nome}</div>";
				echo "<div class='client-card-phone'>{$telefone_display}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='client-card-details'>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Data Retorno</div>";
				echo "<div class='client-card-detail-value danger'>{$data_retornoF}</div>";
				echo "</div>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Dias Atraso</div>";
				echo "<div class='client-card-detail-value danger'>{$dias} dias</div>";
				echo "</div>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Último Serviço</div>";
				echo "<div class='client-card-detail-value'>{$nome_servico}</div>";
				echo "</div>";
				echo "<div class='client-card-detail-item'>";
				echo "<div class='client-card-detail-label'>Cartões</div>";
				echo "<div class='client-card-detail-value'>{$cartoes}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='client-card-actions'>";
				echo "<a href='#' onclick=\"mostrar('{$nome}', '{$telefone}', '{$cartoes}', '{$data_cadF}', '{$data_nascF}', '{$endereco}', '{$data_retornoF}', '{$nome_servico}')\" class='client-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver Detalhes";
				echo "</a>";
				echo "<a onclick=\"alertar('{$id}')\" href='http://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text=Olá {$nome}, já faz {$dias} dias que você não vem dar um trato no visual, caso queira fazer um novo agendamento é só acessar nosso site {$url_agendamento}, será um prazer atendê-lo novamente!!' target='_blank' class='client-card-action-btn whatsapp'>";
				echo "<i class='fa fa-whatsapp'></i> Enviar WhatsApp";
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
	function editar(id, nome, telefone, endereco, data_nasc, cartoes){
		$('#id').val(id);
		$('#nome').val(nome);		
		$('#telefone').val(telefone);		
		$('#endereco').val(endereco);
		$('#data_nasc').val(data_nasc);
		$('#cartao').val(cartoes);

		
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
	}
</script>



<script type="text/javascript">
	function mostrar(nome, telefone, cartoes, data_cad, data_nasc, endereco, retorno, servico){

		$('#nome_dados').text(nome);		
		$('#data_cad_dados').text(data_cad);
		$('#data_nasc_dados').text(data_nasc);
		$('#cartoes_dados').text(cartoes);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#retorno_dados').text(retorno);		
		$('#servico_dados').text(servico);

		$('#modalDados').modal('show');
	}
</script>



<script type="text/javascript">
	function alertar(id){
		
    $.ajax({
        url: 'paginas/' + pag + "/alertar.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success:function(mensagem){
             if (mensagem.trim() === "Salvo com Sucesso") {
             	
                //$('#btn-fechar-horarios').click();
                listar(); 
            } 
        }
    });
	}
</script>