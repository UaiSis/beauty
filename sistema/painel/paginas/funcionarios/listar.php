<?php 
require_once("../../../conexao.php");
$tabela = 'usuarios';

if($tipo_comissao == 'Porcentagem'){
		$tipo_comissao = '%';
	}

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

$query = $pdo->query("SELECT * FROM $tabela where nivel != 'Administrador' and (nome LIKE '$busca' or email LIKE '$busca' or telefone LIKE '$busca') ORDER BY id desc LIMIT $limite, $itens_por_pagina");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" id="tabela">
	<thead> 
	<tr> 
	<th>Funcionário</th>	
	<th>Email</th> 	
	<th>Telefone</th> 	
	<th>Cargo</th>
	<th>Comissão</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$email = $res[$i]['email'];
	$cpf = $res[$i]['cpf'];
	$senha = $res[$i]['senha'];
	$nivel = $res[$i]['nivel'];
	$data = $res[$i]['data'];
	$ativo = $res[$i]['ativo'];
	$telefone = $res[$i]['telefone'];
	$endereco = $res[$i]['endereco'];
	$foto = $res[$i]['foto'];
	$atendimento = $res[$i]['atendimento'];
	$tipo_chave = $res[$i]['tipo_chave'];
	$chave_pix = $res[$i]['chave_pix'];
	$intervalo = $res[$i]['intervalo'];
	$comissao = $res[$i]['comissao'];

	$dataF = implode('/', array_reverse(explode('-', $data)));
	
	
	$senha = '*******';
	

	$dataF = implode('/', array_reverse(explode('-', $data)));
	$telefone_display = !empty($telefone) ? $telefone : '-';

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

	$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
	$action_class = ($ativo == 'Sim') ? 'inactive' : 'active';

	if($tipo_comissao == '%'){
		$comissaoF = @number_format($comissao, 0, ',', '.').'%';
	}else{
		$comissaoF = 'R$ '.@number_format($comissao, 2, ',', '.');
	}

	if($comissao == "" || $comissao == 0){
		$comissaoF = "-";
	}


$iniciais = '';
$palavras = explode(' ', $nome);
if(count($palavras) >= 2){
	$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
}else{
	$iniciais = strtoupper(substr($nome, 0, 2));
}

$cores_avatar = ['#007A63', '#42a5f5', '#9c27b0', '#ff9800', '#ef5350', '#00d896'];
$cor_index = $id % count($cores_avatar);
$cor_avatar = $cores_avatar[$cor_index];

$tem_foto = ($foto != 'sem-foto.jpg' && !empty($foto));

echo <<<HTML
<tr>
<td>
	<div class="user-info-cell">
		<div class="user-avatar" style="background: {$cor_avatar};">
HTML;

if($tem_foto){
	echo "<img src='img/perfil/{$foto}' alt='{$nome}'>";
}else{
	echo $iniciais;
}

$badge_class = 'default';
$nivel_lower = strtolower($nivel);
if(strpos($nivel_lower, 'admin') !== false){
	$badge_class = 'admin';
} else if(strpos($nivel_lower, 'barb') !== false){
	$badge_class = 'barbeiro';
} else if(strpos($nivel_lower, 'cabel') !== false){
	$badge_class = 'cabeleireira';
} else if(strpos($nivel_lower, 'recep') !== false){
	$badge_class = 'recepcionista';
} else if(strpos($nivel_lower, 'gerente') !== false || strpos($nivel_lower, 'manager') !== false){
	$badge_class = 'gerente';
}

echo <<<HTML
		</div>
		<span class="user-name">{$nome}</span>
	</div>
</td>
<td>{$email}</td>
<td>{$telefone_display}</td>
<td><span class="nivel-badge {$badge_class}">{$nivel}</span></td>
<td>{$comissaoF}</td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editar('{$id}','{$nome}', '{$email}', '{$telefone}', '{$cpf}', '{$nivel}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}', '{$intervalo}', '{$comissao}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="mostrar('{$nome}', '{$email}', '{$cpf}', '{$senha}', '{$nivel}', '{$dataF}', '{$ativo}', '{$telefone}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}')" class="table-action-icon view" title="Visualizar">
			<i class="fa fa-eye"></i>
		</a>

		<a href="#" onclick="dias('{$id}', '{$nome}')" class="table-action-icon" style="color: #ff9800; background: rgba(255, 152, 0, 0.08);" title="Ver Dias">
			<i class="fa fa-calendar"></i>
		</a>

		<a href="#" onclick="servico('{$id}', '{$nome}')" class="table-action-icon" style="color: #9c27b0; background: rgba(156, 39, 176, 0.08);" title="Definir Serviços">
			<i class="fa fa-scissors"></i>
		</a>

		<a href="http://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text=" target="_blank" class="table-action-icon" style="color: #25d366; background: rgba(37, 211, 102, 0.08);" title="Abrir WhatsApp">
			<i class="fa fa-whatsapp"></i>
		</a>

		<a href="#" onclick="ativar('{$id}', '{$acao}')" class="table-action-icon {$action_class}" title="{$titulo_link}">
			<i class="fa {$icone}"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoFuncionario('{$id}')" class="table-action-icon delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</a>
	</div>
</td>
</tr>
HTML;

}

// Calcular paginação
$query2 = $pdo->query("SELECT * FROM $tabela where nivel != 'Administrador' and (nome LIKE '$busca' or email LIKE '$busca' or telefone LIKE '$busca')");
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
			<a onclick="listarFuncionarios(0)" class="paginador" href="#" aria-label="Previous">
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
			<a onclick="listarFuncionarios({$i})" class="paginador" href="#">{$pag_num}</a>
		</li>
HTML;
		} 
	} 

echo <<<HTML
		<li class="page-item">
			<a onclick="listarFuncionarios({$ultimo_reg})" class="paginador" href="#" aria-label="Next">
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
	echo '<div style="text-align: center; padding: 60px 20px;">
		<i class="fa fa-user-md" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum funcionário cadastrado</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Adicione o primeiro funcionário</p>
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
		$query_mobile = $pdo->query("SELECT * FROM usuarios where nivel != 'Administrador' and (nome LIKE '$busca' or email LIKE '$busca' or telefone LIKE '$busca') ORDER BY id desc LIMIT $limite, $itens_por_pagina");
		$res = $query_mobile->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		if($total_reg > 0){
			for($i=0; $i < $total_reg; $i++){
				$id = $res[$i]['id'];
				$nome = $res[$i]['nome'];
				$email = $res[$i]['email'];
				$cpf = $res[$i]['cpf'];
				$senha = '*******';
				$nivel = $res[$i]['nivel'];
				$data = $res[$i]['data'];
				$ativo = $res[$i]['ativo'];
				$telefone = $res[$i]['telefone'];
				$endereco = $res[$i]['endereco'];
				$foto = $res[$i]['foto'];
				$atendimento = $res[$i]['atendimento'];
				$tipo_chave = $res[$i]['tipo_chave'];
				$chave_pix = $res[$i]['chave_pix'];
				$intervalo = $res[$i]['intervalo'];
				$comissao = $res[$i]['comissao'];
				
				if($ativo == 'Sim'){
					$icone = 'fa-check-square';
					$titulo_link = 'Desativar Item';
					$acao = 'Não';
				}else{
					$icone = 'fa-square-o';
					$titulo_link = 'Ativar Item';
					$acao = 'Sim';
				}
				
				$action_class = ($ativo == 'Sim') ? 'inactive' : 'active';
				$telefone_display = !empty($telefone) ? $telefone : '-';
				$dataF = implode('/', array_reverse(explode('-', $data)));
				
				if($tipo_comissao == '%'){
					$comissaoF = @number_format($comissao, 0, ',', '.').'%';
				}else{
					$comissaoF = 'R$ '.@number_format($comissao, 2, ',', '.');
				}
				if($comissao == "" || $comissao == 0){
					$comissaoF = "-";
				}
				
				echo "mobileHtml += `";
				echo "<div class='funcionario-card-mobile'>";
				echo "<div class='funcionario-card-header'>";
				echo "<img src='img/perfil/{$foto}' class='funcionario-card-avatar' alt='{$nome}'>";
				echo "<div class='funcionario-card-info'>";
				echo "<div class='funcionario-card-name'>{$nome}</div>";
				echo "<div class='funcionario-card-email'>{$email}</div>";
				echo "</div>";
				echo "<div class='funcionario-card-badge'>{$nivel}</div>";
				echo "</div>";
				
				echo "<div class='funcionario-card-details'>";
				echo "<div class='funcionario-card-detail-item'>";
				echo "<div class='funcionario-card-detail-label'>Telefone</div>";
				echo "<div class='funcionario-card-detail-value'>{$telefone_display}</div>";
				echo "</div>";
				echo "<div class='funcionario-card-detail-item'>";
				echo "<div class='funcionario-card-detail-label'>Comissão</div>";
				echo "<div class='funcionario-card-detail-value'>{$comissaoF}</div>";
				echo "</div>";
				echo "</div>";
				
				echo "<div class='funcionario-card-actions'>";
				echo "<a href='#' onclick=\"editar('{$id}','{$nome}', '{$email}', '{$telefone}', '{$cpf}', '{$nivel}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}', '{$intervalo}', '{$comissao}')\" class='funcionario-card-action-btn edit'>";
				echo "<i class='fa fa-edit'></i> Editar";
				echo "</a>";
				echo "<a href='#' onclick=\"mostrar('{$nome}', '{$email}', '{$cpf}', '{$senha}', '{$nivel}', '{$dataF}', '{$ativo}', '{$telefone}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}')\" class='funcionario-card-action-btn view'>";
				echo "<i class='fa fa-eye'></i> Ver";
				echo "</a>";
				echo "</div>";
				
				echo "<div style='margin-top: 8px; display: flex; gap: 8px;'>";
				echo "<a href='#' onclick=\"dias('{$id}', '{$nome}')\" class='funcionario-card-action-btn' style='flex: 1; background: rgba(255, 152, 0, 0.1); color: #ff9800;'>";
				echo "<i class='fa fa-calendar'></i> Dias";
				echo "</a>";
				echo "<a href='#' onclick=\"servico('{$id}', '{$nome}')\" class='funcionario-card-action-btn' style='flex: 1; background: rgba(156, 39, 176, 0.1); color: #9c27b0;'>";
				echo "<i class='fa fa-scissors'></i> Serviços";
				echo "</a>";
				echo "</div>";
				
				echo "<div style='margin-top: 8px; display: flex; gap: 8px;'>";
				echo "<a href='http://api.whatsapp.com/send?1=pt_BR&phone={$whats}&text=' target='_blank' class='funcionario-card-action-btn' style='flex: 1; background: rgba(37, 211, 102, 0.1); color: #25d366;'>";
				echo "<i class='fa fa-whatsapp'></i> WhatsApp";
				echo "</a>";
				echo "<a href='#' onclick=\"ativar('{$id}', '{$acao}')\" class='funcionario-card-action-btn {$action_class}' style='flex: 1;'>";
				echo "<i class='fa {$icone}'></i>";
				echo "</a>";
				echo "<a href='#' onclick=\"confirmarExclusaoFuncionario('{$id}')\" class='funcionario-card-action-btn delete' style='flex: 0; min-width: 42px;'>";
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

<style>
	#tabela_wrapper {
		padding: 20px;
	}

	#tabela_filter input {
		border-radius: 8px;
		border: 1px solid #e0e0e0;
		padding: 8px 16px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	#tabela_filter input:focus {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
		outline: none;
	}

	#tabela_length select {
		border-radius: 8px;
		border: 1px solid #e0e0e0;
		padding: 6px 12px;
		font-size: 13px;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button {
		border-radius: 8px !important;
		margin: 0 2px;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button.current {
		background: #007A63 !important;
		border-color: #007A63 !important;
		color: #fff !important;
	}

	.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
		background: rgba(0, 122, 99, 0.1) !important;
		border-color: #007A63 !important;
		color: #007A63 !important;
	}
</style>

<script type="text/javascript">
function confirmarExclusaoFuncionario(id) {
	$('#mensagem-excluir').text('Confirmar Exclusão?');
	$('#mensagem-excluir').css({
		'background': 'rgba(239, 83, 80, 0.1)',
		'color': '#ef5350',
		'display': 'block'
	});
	
	setTimeout(function(){
		$('#mensagem-excluir').html(`
			<span style="margin-right: 16px;">Tem certeza que deseja excluir este funcionário?</span>
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
	function editar(id, nome, email, telefone, cpf, nivel, endereco, foto, atendimento, tipo_chave, chave_pix, intervalo, comissao){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#cpf').val(cpf);
		$('#cargo').val(nivel).change();
		$('#endereco').val(endereco);
		$('#atendimento').val(atendimento).change();
		$('#chave_pix').val(chave_pix);
		$('#tipo_chave').val(tipo_chave).change();
		$('#intervalo').val(intervalo);
		$('#comissao').val(comissao);
		
		$('#titulo_inserir').text('Editar Funcionário');
		$('#btn-text').text('Salvar Alterações');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src','img/perfil/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#email').val('');
		$('#cpf').val('');
		$('#endereco').val('');
		$('#foto').val('');
		$('#chave_pix').val('');
		$('#tipo_chave').val('');
		$('#atendimento').val('Sim');
		$('#target').attr('src','img/perfil/sem-foto.jpg');
		$('#intervalo').val('');
		$('#comissao').val('');
		$('#titulo_inserir').text('Novo Funcionário');
		$('#btn-text').text('Criar Funcionário');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, email, cpf, senha, nivel, data, ativo, telefone, endereco, foto, atendimento, tipo_chave, chave_pix){

		$('#nome_dados').text(nome);
		$('#email_dados').text(email);
		$('#cpf_dados').text(cpf);
		$('#senha_dados').text(senha);
		$('#nivel_dados').text(nivel);
		$('#data_dados').text(data);
		$('#ativo_dados').text(ativo);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#atendimento_dados').text(atendimento);
		$('#tipo_chave_dados').text(tipo_chave);
		$('#chave_pix_dados').text(chave_pix);

		$('#target_mostrar').attr('src','img/perfil/' + foto);

		$('#modalDados').modal('show');
	}
</script>




<script type="text/javascript">
	function horarios(id, nome){

		$('#nome_horarios').text(nome);		
		$('#id_horarios').val(id);		

		$('#modalHorarios').modal('show');
		listarHorarios(id);
	}
</script>


<script type="text/javascript">
	function dias(id, nome){

		$('#nome_dias').text(nome);		
		$('#id_dias').val(id);		

		$('#modalDias').modal('show');
		listarDias(id);
	}
</script>



<script type="text/javascript">
	function servico(id, nome){

		$('#nome_servico').text(nome);		
		$('#id_servico').val(id);		

		$('#modalServicos').modal('show');
		listarServicos(id);
	}
</script>
