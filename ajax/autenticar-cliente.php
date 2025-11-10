<?php 
require_once("../sistema/conexao.php");

$telefone = $_POST['telefone'];
$senha = @$_POST['senha'];
$buscar_dados = @$_POST['buscar_dados']; // Parâmetro para apenas buscar dados sem autenticar

// Remove caracteres especiais do telefone
$telefone = preg_replace('/[^0-9]/', '', $telefone);

// Busca o cliente pelo telefone
$query = $pdo->prepare("SELECT * FROM clientes WHERE telefone = :telefone LIMIT 1");
$query->bindValue(":telefone", $telefone);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(count($res) > 0){
    $senha_crip = @$res[0]['senha_crip'];
    $nome = $res[0]['nome'];
    $id = $res[0]['id'];
    $email = @$res[0]['email'];
    
    // Se for apenas para buscar dados (verificar se existe)
    if($buscar_dados == 'sim'){
        echo "Existe*".$id."*".$nome."*".$telefone."*".$email;
        exit();
    }
    
    // Verifica se a senha está correta usando password_verify
    if($senha && $senha_crip && password_verify($senha, $senha_crip)){
        // Retorna os dados do cliente
        echo "Autenticado*".$id."*".$nome."*".$telefone."*".$email;
    } else {
        echo "Senha incorreta!";
    }
} else {
    // Cliente não existe - primeiro acesso
    echo "Primeiro Acesso";
}
?>

