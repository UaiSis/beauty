<?php 
@session_start();
require_once("../../../conexao.php");
$tabela = 'assinaturas';

$data_atual = date('Y-m-d');

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){

for($i=0; $i < $total_reg; $i++){
	foreach ($res[$i] as $key => $value){}
	$id = $res[$i]['id'];
	$cliente = $res[$i]['cliente'];	
	$data = $res[$i]['data'];
	$grupo = $res[$i]['grupo'];	
	$item = $res[$i]['item'];	
	$pago = $res[$i]['pago'];
	$valor = $res[$i]['valor'];	
	$ref_pix = $res[$i]['ref_pix'];	
	$frequencia = $res[$i]['frequencia'];
	$vencimento = $res[$i]['vencimento'];	
	$cancelado = $res[$i]['cancelado'];	
	
	
		$query2 = $pdo->query("SELECT * FROM grupo_assinaturas where id = '$grupo'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_grupo = $res2[0]['nome'];
		}else{
			$nome_grupo = 'Nenhum!';
		}

		$query2 = $pdo->query("SELECT * FROM itens_assinaturas where id = '$item'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_item = $res2[0]['nome'];
		}else{
			$nome_item = 'Nenhum!';
		}

		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_cliente = $res2[0]['nome'];
		}else{
			$nome_cliente = 'Nenhum!';
		}
	
		$valorF = number_format($valor, 2, ',', '.');
	$dataF = implode('/', array_reverse(@explode('-', $data)));

	if($pago != "Sim"){
			$classe_alerta = 'text-danger';			
			$visivel = '';			
		}else{
			$classe_alerta = 'verde';
			$visivel = 'ocultar';			
		}

		$texto_cancelado = '';
		$ocultar_cancelar = '';
		if($cancelado == 'Sim'){
			$ocultar_cancelar = 'ocultar';
			$classe_alerta = 'text-warning';
			$texto_cancelado = '<span class="text-danger"> (Cancelado) </span>';	
		}


		//verificar se o pagamento está aprovado
		if($pago == 'Não' and $ref_pix != ""){
			require_once("../../../../pagamentos/consultar_pagamento.php");
			if($status_api == 'approved'){
					$id_usuario = $_SESSION['id'];
					$id = $res[$i]['id'];
					$valor = $res[$i]['valor'];
					$forma_pgto = 'MP';
					$data_pgto = $data_atual;
					require_once("aprovar_plano.php");
				}				
		}


		//buscar a ultima conta do plano em aberto para ser excluida se o plano for cancelado
		$query2 = $pdo->query("SELECT * FROM receber where tipo = 'Assinatura' and referencia = '$id' and pago != 'Sim' order by id desc limit 1");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$id_conta = @$res2[0]['id'];



$status_class = 'pendente';
$status_text = 'PENDENTE';
if($pago == "Sim"){
	$status_class = 'pago';
	$status_text = 'PAGO';
}
if($cancelado == 'Sim'){
	$status_class = 'cancelado';
	$status_text = 'CANCELADO';
}

echo <<<HTML
<div class="plano-card {$status_class}">
	<div class="plano-status-badge {$status_class}">
		{$status_text}
	</div>

	<div class="plano-cliente">{$nome_cliente}</div>
	
	<div class="plano-info">
		<div class="plano-info-item">
			<i class="fa fa-star"></i>
			<span><strong>Assinatura:</strong> {$nome_grupo}</span>
		</div>
		<div class="plano-info-item">
			<i class="fa fa-bookmark"></i>
			<span><strong>Plano:</strong> {$nome_item}</span>
		</div>
		<div class="plano-info-item">
			<i class="fa fa-calendar"></i>
			<span><strong>Data:</strong> {$dataF}</span>
		</div>
	</div>

	<div class="plano-valor">R$ {$valorF}</div>

	<div class="plano-actions">
		<button onclick="editar('{$id}','{$cliente}', '{$grupo}', '{$item}', '{$valor}', '{$frequencia}', '{$vencimento}')" class="plano-action-btn edit" title="Editar">
			<i class="fa fa-edit"></i> Editar
		</button>
		<button onclick="confirmarExclusaoPlano('{$id}')" class="plano-action-btn delete" title="Excluir">
			<i class="fa fa-trash"></i>
		</button>
		<button class="{$visivel}" onclick="baixar('{$id}', '{$valor}')" class="plano-action-btn baixar" title="Baixar">
			<i class="fa fa-check"></i>
		</button>
		<button class="{$ocultar_cancelar}" onclick="confirmarCancelamento('{$id_conta}', '{$id}')" class="plano-action-btn cancel" title="Cancelar">
			<i class="fa fa-ban"></i>
		</button>
	</div>
</div>
HTML;

}

}else{
	echo '<div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
		<i class="fa fa-star" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhum plano cadastrado</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Crie um novo plano para começar</p>
	</div>';
}

?>

<style>
	.plano-action-btn {
		text-decoration: none !important;
	}
	.ocultar {
		display: none !important;
	}
</style>

<script type="text/javascript">
	function confirmarExclusaoPlano(id) {
		if(confirm('Confirmar exclusão deste plano?')) {
			excluir(id);
		}
	}

	function confirmarCancelamento(id_conta, id) {
		if(confirm('Cancelar este plano? A última conta em aberto será excluída!')) {
			cancelar(id_conta, id);
		}
	}
</script>


<script type="text/javascript">
	function editar(id, cliente, grupo, item, valor, frequencia, vencimento){
		$('#id').val(id);
		$('#cliente').val(cliente).change();
		$('#grupo').val(grupo).change();	
		$('#frequencia').val(frequencia).change();	
		$('#vencimento').val(vencimento);	
			

		setTimeout(function() {		  
		 	listarItens();
		}, 400)

		setTimeout(function() {		  
		  $('#item').val(item).change();
		}, 700)

		setTimeout(function() {		  
		  $('#valor').val(valor);
		}, 900)
		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');

		
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#chave').val('');
		$('#frequencia').val('30').change();	
	}

function baixar(id, valor){

		$('#id_baixar').val(id);
		$('#valor_baixar').val(valor);			
		$('#modalBaixar').modal('show');
	}


function cancelar(id, id_plano){
    $.ajax({
        url: 'paginas/' + pag + "/cancelar.php",
        method: 'POST',
        data: {id, id_plano},
        dataType: "text",

        success: function (mensagem) {   
             
            if (mensagem.trim() == "Excluído com Sucesso") {                
                listar();                
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }

        },      

    });
}

</script>

