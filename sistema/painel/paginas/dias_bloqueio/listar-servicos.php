<?php 
require_once("../../../conexao.php");
$tabela = 'dias_bloqueio';

$id_func = $_POST['func'];

$pdo->query("DELETE FROM $tabela where data < curDate()");

$query = $pdo->query("SELECT * FROM $tabela where funcionario = 0 order by data asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">';

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$data = $res[$i]['data'];
	$usuario = $res[$i]['usuario'];
	
	
$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);	
$nome_usuario = @$res2[0]['nome'];

$dataF = implode('/', array_reverse(@explode('-', $data)));

echo <<<HTML
<div class="bloqueio-card">
	<div class="bloqueio-card-status">
		<i class="fa fa-ban"></i> BLOQUEADO
	</div>

	<div class="bloqueio-card-date-big">{$dataF}</div>
	
	<div class="bloqueio-card-info">
		<div class="bloqueio-card-info-item">
			<i class="fa fa-user"></i>
			<span><strong>Bloqueado por:</strong> {$nome_usuario}</span>
		</div>
	</div>

	<div class="bloqueio-card-actions">
		<button onclick="confirmarExclusaoBloqueio('{$id}', '{$id_func}')" class="bloqueio-action-btn delete" title="Desbloquear">
			<i class="fa fa-trash"></i> Desbloquear
		</button>
	</div>
</div>
HTML;

}

echo '</div>';
echo '<div align="center" id="mensagem-servico-excluir" style="padding: 12px; margin: 16px; border-radius: 8px; display: none;"></div>';


}else{
	echo '<div class="empty-state">
		<i class="fa fa-calendar-times-o"></i>
		<p>Nenhuma data bloqueada</p>
		<small>Adicione datas de bloqueio usando o formulário acima</small>
	</div>';
}

?>

<script type="text/javascript">
	function confirmarExclusaoBloqueio(id, func){
		$('#mensagem-servico-excluir').text('Confirmar Desbloqueio?');
		$('#mensagem-servico-excluir').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-servico-excluir').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja desbloquear esta data?</span>
				<button onclick="excluirServico(${id}, ${func})" style="
					background: #ef5350;
					color: white;
					border: none;
					padding: 6px 16px;
					border-radius: 6px;
					cursor: pointer;
					font-weight: 600;
					margin-right: 8px;
				">Sim, Desbloquear</button>
				<button onclick="$('#mensagem-servico-excluir').hide()" style="
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

	function excluirServico(id, func){
		$.ajax({
			url: 'paginas/' + pag + "/excluir-servico.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success: function (mensagem) {            
				if (mensagem.trim() == "Excluído com Sucesso") {   
					listarServicos(func);
					$('#mensagem-servico-excluir').hide();
				} else {
					$('#mensagem-servico-excluir').css({
						'background': 'rgba(239, 83, 80, 0.1)',
						'color': '#ef5350',
						'display': 'block'
					});
					$('#mensagem-servico-excluir').text(mensagem);
				}
			}
		});
	}
</script>