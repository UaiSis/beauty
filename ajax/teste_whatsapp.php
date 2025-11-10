<?php 
require_once("../sistema/conexao.php");
$api_whatsapp = $api;
$telefone_sistema = $_POST['telefone_sistema'];

$telefone_sistemaF = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_sistema);

$mensagem_whatsapp = 'Testando o disparo da api '.$api_whatsapp;

if($api_whatsapp == 'menuia'){
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://chatbot.menuia.com/api/create-message',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => array(
	  'appkey' => $token_whatsapp,
	  'authkey' => $instancia_whatsapp,
	  'to' => $telefone_sistemaF,
	  'message' => $mensagem_whatsapp,
	  'licence' => 'hugocursos',
	  'sandbox' => 'false'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo $response;
}else{
	$url = "http://api.wordmensagens.com.br/send-text";

	$data = array('instance' => $instancia_whatsapp,
	              'to' => $telefone_sistemaF,
	              'token' => $token_whatsapp,
	              'message' => $mensagem_whatsapp);

	$options = array('http' => array(
	               'method' => 'POST',
	               'content' => http_build_query($data)
	));

	$stream = stream_context_create($options);

	$result = @file_get_contents($url, false, $stream);

	echo $result;

}




 ?>