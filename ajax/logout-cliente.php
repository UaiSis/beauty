<?php 
@session_start();

// Limpa a sessão
unset($_SESSION['id']);
unset($_SESSION['nome']);
unset($_SESSION['telefone']);
unset($_SESSION['nivel']);
unset($_SESSION['aut_token_505052022']);

// Limpa os cookies
setcookie('id_cliente', '', time() - 3600, '/');
setcookie('nome_cliente', '', time() - 3600, '/');
setcookie('telefone_cliente', '', time() - 3600, '/');

// Destrói a sessão completamente
session_destroy();

echo "Logout realizado com sucesso!";
?>

