<?php 
@session_start();
require_once("../sistema/conexao.php");
$tabela = 'receber';
$id_usuario = 0;

$query = $pdo->query("SELECT * from receber where pago != 'Sim' and pago is not null and ref_pix is not null ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_contas = @count($res);
for ($i = 0; $i < $total_contas; $i++) {
$id = $res[$i]['id'];
$ref_pix = $res[$i]['ref_pix'];

	

	require('../pagamentos/consultar_pagamento.php');     
	echo $status_api;


	if($status_api == 'approved'){
		require('../pagamentos/baixar_conta.php');  
	}

}

echo '<br>';
echo $total_contas;

 ?>