<?php 
@session_start();

// Limpa todas as variáveis de sessão
unset($_SESSION['id']);
unset($_SESSION['nome']);
unset($_SESSION['telefone']);
unset($_SESSION['nivel']);
unset($_SESSION['aut_token_505052022']);

// Limpa os cookies (usando o mesmo caminho que foi definido ao criar)
setcookie('id_cliente', '', time() - 3600, '/');
setcookie('nome_cliente', '', time() - 3600, '/');
setcookie('telefone_cliente', '', time() - 3600, '/');

// Destrói a sessão completamente
session_destroy();
?>
<script>
// Limpa o localStorage do frontend principal
if (typeof(Storage) !== "undefined") {
    localStorage.removeItem('cliente_logado');
}

// Redireciona para a tela de login
window.location.href = '../acesso.php';
</script>