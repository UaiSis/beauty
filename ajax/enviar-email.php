<?php 
require_once("../sistema/conexao.php");

$remetente = $email_sistema;
$assunto = 'Contato - ' .$nome_sistema;

$nome = filter_var($_POST['nome'], @FILTER_SANITIZE_STRING);
$telefone = filter_var($_POST['telefone'], @FILTER_SANITIZE_STRING);
$mensagem = filter_var($_POST['mensagem'], @FILTER_SANITIZE_STRING);

$mensagem = utf8_decode('Nome: '.$nome. "\r\n"."\r\n" . 'Telefone: '.$telefone. "\r\n"."\r\n" . 'Mensagem: ' . "\r\n"."\r\n" .$mensagem);
$dest = filter_var($_POST['email'], @FILTER_SANITIZE_STRING);
$cabecalhos = "From: " .$dest;

mail($remetente, $assunto, $mensagem, $cabecalhos);

echo 'Enviado com Sucesso';

 ?>