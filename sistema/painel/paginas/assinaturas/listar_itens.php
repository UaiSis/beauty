<?php 
require_once("../../../conexao.php");
$tabela = 'itens_assinaturas';

$id_grupo = $_POST['id'];

$query = $pdo->query("SELECT * FROM $tabela where grupo = '$id_grupo' ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

echo <<<HTML
	<table class="table-modern" style="margin-top: 20px;">
	<thead> 
	<tr> 
	<th>Nome do Plano</th>	
	<th>Valor</th>
	<th>Características</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];	
	$valor = $res[$i]['valor'];	
	$ativo = $res[$i]['ativo'];
	$c1 = $res[$i]['c1'];
	$c2 = $res[$i]['c2'];
	$c3 = $res[$i]['c3'];
	$c4 = $res[$i]['c4'];
	$c5 = $res[$i]['c5'];
	$c6 = $res[$i]['c6'];
	$c7 = $res[$i]['c7'];
	$c8 = $res[$i]['c8'];
	$c9 = $res[$i]['c9'];
	$c10 = $res[$i]['c10'];
	$c11 = $res[$i]['c11'];
	$c12 = $res[$i]['c12'];


	
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
		
		$valorF = number_format($valor, 2, ',', '.');

		$carac = $c1.' / '.$c2.' / '.$c3.' / '.$c4.' / '.$c5.' / '.$c6.' / '.$c7.' / '.$c8.' / '.$c9.' / '.$c10.' / '.$c11.' / '.$c12;

		 $caracF = mb_strimwidth($carac, 0, 60, "...");
		 
		 $action_class = ($ativo == 'Sim') ? 'active' : 'inactive';




echo <<<HTML
<input type="hidden" id="c1_{$id}" value="{$c1}">
<input type="hidden" id="c2_{$id}" value="{$c2}">
<input type="hidden" id="c3_{$id}" value="{$c3}">
<input type="hidden" id="c4_{$id}" value="{$c4}">
<input type="hidden" id="c5_{$id}" value="{$c5}">
<input type="hidden" id="c6_{$id}" value="{$c6}">
<input type="hidden" id="c7_{$id}" value="{$c7}">
<input type="hidden" id="c8_{$id}" value="{$c8}">
<input type="hidden" id="c9_{$id}" value="{$c9}">
<input type="hidden" id="c10_{$id}" value="{$c10}">
<input type="hidden" id="c11_{$id}" value="{$c11}">
<input type="hidden" id="c12_{$id}" value="{$c12}">
<tr class="{$classe_linha}">
<td><strong>{$nome}</strong></td>
<td>R$ {$valorF}</td>
<td><small style="color: #6c757d;">{$caracF}</small></td>
<td>
	<div class="table-actions-cell">
		<a href="#" onclick="editarItens('{$id}','{$nome}','{$valor}')" class="table-action-icon edit" title="Editar">
			<i class="fa fa-edit"></i>
		</a>

		<a href="#" onclick="ativarItens('{$id}', '{$acao}')" class="table-action-icon {$action_class}" title="{$titulo_link}">
			<i class="fa {$icone}"></i>
		</a>

		<a href="#" onclick="confirmarExclusaoItem('{$id}')" class="table-action-icon delete" title="Excluir">
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
<div align="center" id="mensagem-excluir-itens" style="padding: 12px; margin: 16px 0; border-radius: 8px; display: none;"></div>
HTML;


}else{
	echo '<div style="text-align: center; padding: 30px 20px; color: #6c757d;">
		<i class="fa fa-bookmark" style="font-size: 36px; color: #dee2e6; margin-bottom: 12px;"></i>
		<p style="font-size: 14px; margin: 0;">Nenhum plano cadastrado neste grupo</p>
		<small style="font-size: 12px; color: #adb5bd; margin-top: 6px; display: block;">Use o formulário acima para adicionar</small>
	</div>';
}

?>

<style>
	.table-modern {
		margin: 0;
		width: 100%;
		background: #fff;
		border-radius: 8px;
		overflow: hidden;
	}

	.table-modern thead th {
		background: #007A63;
		color: #ffffff;
		font-weight: 600;
		text-transform: uppercase;
		font-size: 11px;
		letter-spacing: 0.5px;
		padding: 12px 16px;
		border: none;
		white-space: nowrap;
	}

	.table-modern tbody td {
		padding: 12px 16px;
		vertical-align: middle;
		border-bottom: 1px solid #f0f0f0;
		font-size: 13px;
		color: #495057;
	}

	.table-modern tbody tr:last-child td {
		border-bottom: none;
	}

	.table-modern tbody tr:hover {
		background: rgba(0, 122, 99, 0.02);
	}

	.table-actions-cell {
		display: flex;
		gap: 6px;
		align-items: center;
		justify-content: flex-start;
	}

	.table-action-icon {
		width: 28px;
		height: 28px;
		border-radius: 6px;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.2s ease;
		cursor: pointer;
		border: none;
		background: transparent;
		font-size: 13px;
		text-decoration: none;
	}

	.table-action-icon.edit {
		color: #007A63;
		background: rgba(0, 122, 99, 0.08);
	}

	.table-action-icon.edit:hover {
		background: rgba(0, 122, 99, 0.15);
		transform: scale(1.1);
	}

	.table-action-icon.active {
		color: #00d896;
		background: rgba(0, 216, 150, 0.08);
	}

	.table-action-icon.active:hover {
		background: rgba(0, 216, 150, 0.15);
		transform: scale(1.1);
	}

	.table-action-icon.inactive {
		color: #9e9e9e;
		background: rgba(158, 158, 158, 0.08);
	}

	.table-action-icon.inactive:hover {
		background: rgba(158, 158, 158, 0.15);
		transform: scale(1.1);
	}

	.table-action-icon.delete {
		color: #ef5350;
		background: rgba(239, 83, 80, 0.08);
	}

	.table-action-icon.delete:hover {
		background: rgba(239, 83, 80, 0.15);
		transform: scale(1.1);
	}
</style>

<script type="text/javascript">
	function confirmarExclusaoItem(id){
		$('#mensagem-excluir-itens').text('Confirmar Exclusão?');
		$('#mensagem-excluir-itens').css({
			'background': 'rgba(239, 83, 80, 0.1)',
			'color': '#ef5350',
			'display': 'block'
		});
		
		setTimeout(function(){
			$('#mensagem-excluir-itens').html(`
				<span style="margin-right: 16px;">Tem certeza que deseja excluir este item?</span>
				<button onclick="excluirItens(${id})" style="
					background: #ef5350;
					color: white;
					border: none;
					padding: 6px 16px;
					border-radius: 6px;
					cursor: pointer;
					font-weight: 600;
					margin-right: 8px;
				">Sim, Excluir</button>
				<button onclick="$('#mensagem-excluir-itens').hide()" style="
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

	function editarItens(id, nome, valor){

		var c1 = $('#c1_'+id).val();
		var c2 = $('#c2_'+id).val();
		var c3 = $('#c3_'+id).val();
		var c4 = $('#c4_'+id).val();
		var c5 = $('#c5_'+id).val();
		var c6 = $('#c6_'+id).val();
		var c7 = $('#c7_'+id).val();
		var c8 = $('#c8_'+id).val();
		var c9 = $('#c9_'+id).val();
		var c10 = $('#c10_'+id).val();
		var c11 = $('#c11_'+id).val();
		var c12 = $('#c12_'+id).val();

		$('#id_do_item').val(id);
		$('#nome_item').val(nome);
		$('#valor').val(valor);
		$('#c1').val(c1);
		$('#c2').val(c2);
		$('#c3').val(c3);
		$('#c4').val(c4);
		$('#c5').val(c5);
		$('#c6').val(c6);
		$('#c7').val(c7);
		$('#c8').val(c8);
		$('#c9').val(c9);
		$('#c10').val(c10);
		$('#c11').val(c11);
		$('#c12').val(c12);		
	}

function excluirItens(id){
    $.ajax({
        url: 'paginas/' + pag + "/excluir-itens.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {   
             
            if (mensagem.trim() == "Excluído com Sucesso") {                
                listar();    
                listarItens();
                $('#mensagem-excluir-itens').hide();
            } else {
                $('#mensagem-excluir-itens').css({
                	'background': 'rgba(239, 83, 80, 0.1)',
                	'color': '#ef5350',
                	'display': 'block'
                });
                $('#mensagem-excluir-itens').text(mensagem);
            }

        },      

    });
}

function ativarItens(id, acao){
    $.ajax({
        url: 'paginas/' + pag + "/mudar-status-itens.php",
        method: 'POST',
        data: {id, acao},
        dataType: "text",

        success: function (mensagem) {            
            if (mensagem.trim() == "Alterado com Sucesso") {                
                listar(); 
                listarItens();               
            } else {
                $('#mensagem-excluir-itens').css({
                	'background': 'rgba(239, 83, 80, 0.1)',
                	'color': '#ef5350',
                	'display': 'block'
                });
                $('#mensagem-excluir-itens').text(mensagem);
            }

        },      

    });
}
</script>


