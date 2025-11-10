<?php 
require_once("../sistema/conexao.php");

$telefone = $_POST['telefone'];
$nome = $_POST['nome'];
$senha = '123'; // Senha padrão para primeiro acesso
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

// Remove caracteres especiais do telefone
$telefone = preg_replace('/[^0-9]/', '', $telefone);

// Verifica se já existe um cliente com este telefone
$query = $pdo->prepare("SELECT * FROM clientes WHERE telefone = :telefone LIMIT 1");
$query->bindValue(":telefone", $telefone);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if(count($res) > 0){
    echo "Cliente já cadastrado!";
    exit();
}

// Cadastra o novo cliente (apenas com as colunas que existem na tabela)
$query = $pdo->prepare("INSERT INTO clientes SET nome = :nome, telefone = :telefone, senha_crip = :senha_crip, data_cad = curDate(), alertado = 'Não'");
$query->bindValue(":nome", $nome);
$query->bindValue(":telefone", $telefone);
$query->bindValue(":senha_crip", $senha_crip);
$query->execute();

$id = $pdo->lastInsertId();

// Retorna os dados do novo cliente
echo "Cadastrado*".$id."*".$nome."*".$telefone;
?>

