<?php 
require_once("../../../conexao.php");
$tabela = 'comandas';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%'.@$_POST['status'].'%';
$status2 = @$_POST['status'];

@session_start();
$usuario_logado = @$_SESSION['id'];

$query = $pdo->query("SELECT * FROM $tabela where data >= '$dataInicial' and data <= '$dataFinal' and status LIKE '$status' and funcionario = '$usuario_logado' ORDER BY id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
for($i=0; $i < $total_reg; $i++){
	$id = $res[$i]['id'];
	$cliente = $res[$i]['cliente'];
	$valor = $res[$i]['valor'];
	$data = $res[$i]['data'];
	$funcionario = $res[$i]['funcionario'];
	$status = $res[$i]['status'];
	$obs = $res[$i]['obs'];

	$dataF = implode('/', array_reverse(@explode('-', $data)));
	$valorF = number_format($valor, 2, ',', '.');

	$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_pessoa = $res2[0]['nome'];			
		}else{
			$nome_pessoa = '';			
		}


		$query2 = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_reg2 = @count($res2);
		if($total_reg2 > 0){
			$nome_funcionario = $res2[0]['nome'];
		}else{
			$nome_funcionario = 'Nenhum!';
		}

		if($status == 'Aberta'){
	$imagem = 'aberta.png';
	$classe_status = '';
	$classe_imp = 'ocultar';	
}else{
	$imagem = 'fechada.png';
	$classe_status = 'ocultar';
	$classe_imp = '';
}



$status_class = strtolower($status);
$status_text = $status;
$status_icon_color = ($status == 'Aberta') ? '#00d896' : '#6c757d';

echo <<<HTML
	<div class="comanda-card {$status_class}">
		<div class="comanda-status-badge {$status_class}">
			<span class="comanda-status-icon"></span>
			{$status_text}
		</div>

		<div onclick="editar('{$id}', '{$valor}', '{$cliente}', '{$obs}', '{$status}', '{$nome_pessoa}', '{$nome_funcionario}', '{$dataF}')">
			<div class="comanda-value">R$ {$valorF}</div>
			<div class="comanda-cliente">
				<i class="fa fa-user"></i>
				<span>{$nome_pessoa}</span>
			</div>
			<div class="comanda-cliente">
				<i class="fa fa-calendar"></i>
				<span>{$dataF}</span>
			</div>
		</div>

		<div class="comanda-actions">
			<button onclick="editar('{$id}', '{$valor}', '{$cliente}', '{$obs}', '{$status}', '{$nome_pessoa}', '{$nome_funcionario}', '{$dataF}')" class="comanda-action-btn edit {$classe_status}">
				<i class="fa fa-edit"></i> Editar
			</button>
			<a href="rel/comprovante_comanda.php?id={$id}" target="_blank" class="comanda-action-btn print {$classe_imp}">
				<i class="fa fa-print"></i> Imprimir
			</a>
			<button onclick="confirmarExclusao('{$id}')" class="comanda-action-btn delete {$classe_status}">
				<i class="fa fa-trash"></i>
			</button>
		</div>
	</div>
HTML;
}
}else{
	echo '<div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
		<i class="fa fa-file-text" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px;"></i>
		<p style="font-size: 16px; color: #6c757d; font-weight: 500; margin: 0;">Nenhuma comanda '.$status2.' encontrada</p>
		<p style="font-size: 13px; color: #adb5bd; margin-top: 8px;">Crie uma nova comanda para começar</p>
	</div>';
}

?>

<style>
	.comanda-action-btn {
		text-decoration: none;
	}
	.comanda-action-btn:hover {
		text-decoration: none;
	}
</style>

<script type="text/javascript">
	function confirmarExclusao(id) {
		if(confirm('Confirmar exclusão desta comanda?')) {
			excluirComanda(id);
		}
	}

	function editar(id, valor, cliente, obs, status, nome_cliente, nome_func, data){

		
		if(status.trim() === 'Fechada'){
			
			$('#cliente_dados').text(nome_cliente);
			$('#valor_dados').text(valor);
			$('#data_dados').text(data);
			$('#func_dados').text(nome_func);
			$('#modalDados').modal('show');

			listarServicosDados(id)
			listarProdutosDados(id)

		}else{
			$('#id').val(id);
		$('#cliente').val(cliente).change();		
		$('#valor_serv').val(valor);
		$('#obs').val(obs);

		$('#valor_serv_agd_restante').val('');
										
		$('#titulo_comanda').text('Editar Comanda Aberta');
		$('#btn_fechar_comanda').show();
		$('#modalForm').modal('show');

		listarServicos(id)
		listarProdutos(id)

		setTimeout(() => {
                    calcular();
                }, 650);	
		
		


		}
		
		


		
	}

	function limparCampos(){
		
		$('#btn_fechar_comanda').hide();
		$('#titulo_comanda').text('Nova Comanda');
		$('#id').val('');		
		$('#valor_serv').val('');
		
		$('#cliente').val('').change();

		$('#salvar_comanda').val('').change();	
				

		$('#data_pgto').val('<?=$data_hoje?>');	

		$('#valor_serv_agd_restante').val('');
		$('#data_pgto_restante').val('');
		$('#pgto_restante').val('').change();
		listarServicos()
		listarProdutos()	
		calcular();
		
	}

	function excluirComanda(id){
    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {   
             
            if (mensagem.trim() == "Excluído com Sucesso") {                
                listar();                
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                alert(mensagem)
            }

        },      

    });
}
</script>


