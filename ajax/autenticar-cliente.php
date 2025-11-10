<?php 
require_once("../sistema/conexao.php");

$telefone = $_POST['telefone'];
$senha = @$_POST['senha'];
$buscar_dados = @$_POST['buscar_dados']; // Parâmetro para apenas buscar dados sem autenticar

// Remove caracteres especiais do telefone (apenas números)
$telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);

// Busca TODOS os clientes possíveis com aquele telefone (pode haver múltiplos com formatações diferentes)
// Usa um array para armazenar todos os IDs encontrados e evitar duplicatas
$todos_clientes = array();
$ids_encontrados = array();

// 1. Busca exata (sem formatação)
$query = $pdo->prepare("SELECT * FROM clientes WHERE telefone = :telefone ORDER BY id ASC");
$query->bindValue(":telefone", $telefone_limpo);
$query->execute();
$res1 = $query->fetchAll(PDO::FETCH_ASSOC);
foreach($res1 as $cli){
    if(!in_array($cli['id'], $ids_encontrados)){
        $todos_clientes[] = $cli;
        $ids_encontrados[] = $cli['id'];
    }
}

// 2. Busca removendo formatação do banco (pode encontrar clientes com formatação diferente)
$query = $pdo->query("SELECT * FROM clientes WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', ''), ' ', ''), '.', '') = '$telefone_limpo' ORDER BY id ASC");
$res2 = $query->fetchAll(PDO::FETCH_ASSOC);
foreach($res2 as $cli){
    if(!in_array($cli['id'], $ids_encontrados)){
        $todos_clientes[] = $cli;
        $ids_encontrados[] = $cli['id'];
    }
}

// 3. Se ainda não encontrou, tenta buscar apenas os últimos dígitos (caso tenha DDD diferente)
if(count($todos_clientes) == 0 && strlen($telefone_limpo) >= 9){
    // Pega os últimos 9 dígitos (sem DDD)
    $telefone_sem_ddd = substr($telefone_limpo, -9);
    $query = $pdo->query("SELECT * FROM clientes WHERE telefone LIKE '%$telefone_sem_ddd%' ORDER BY id ASC");
    $res3 = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach($res3 as $cli){
        if(!in_array($cli['id'], $ids_encontrados)){
            $todos_clientes[] = $cli;
            $ids_encontrados[] = $cli['id'];
        }
    }
}

$res = $todos_clientes;

// Se encontrou múltiplos clientes, busca todos e escolhe o que tem mais agendamentos
if(count($res) > 0){
    // Se encontrou apenas um, usa ele
    if(count($res) == 1){
        $cliente_selecionado = $res[0];
    } else {
        // Se encontrou múltiplos, busca qual tem mais agendamentos
        $melhor_cliente = null;
        $max_agendamentos = -1;
        
        foreach($res as $cliente){
            $id_cli = $cliente['id'];
            $query_agd = $pdo->query("SELECT COUNT(*) as total FROM agendamentos WHERE cliente = '$id_cli'");
            $res_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
            $total_agd = @$res_agd[0]['total'];
            
            if($total_agd > $max_agendamentos){
                $max_agendamentos = $total_agd;
                $melhor_cliente = $cliente;
            }
        }
        
        // Se nenhum tem agendamentos, usa o mais antigo (menor ID)
        if($max_agendamentos == 0){
            $cliente_selecionado = $res[0]; // Já está ordenado por ID ASC
        } else {
            $cliente_selecionado = $melhor_cliente;
        }
    }
    
    $senha_crip = @$cliente_selecionado['senha_crip'];
    $nome = $cliente_selecionado['nome'];
    $id = $cliente_selecionado['id'];
    $email = @$cliente_selecionado['email'];
    
    // Se for apenas para buscar dados (verificar se existe)
    if($buscar_dados == 'sim'){
        // Retorna o telefone do banco (formato correto)
        $telefone_retorno = $cliente_selecionado['telefone'];
        echo "Existe*".$id."*".$nome."*".$telefone_retorno."*".$email;
        exit();
    }
    
    // Verifica se a senha está correta usando password_verify
    if($senha && $senha_crip && password_verify($senha, $senha_crip)){
        // Retorna os dados do cliente com telefone do banco (formato correto)
        $telefone_retorno = $cliente_selecionado['telefone'];
        echo "Autenticado*".$id."*".$nome."*".$telefone_retorno."*".$email;
    } else {
        echo "Senha incorreta!";
    }
} else {
    // Cliente não existe - primeiro acesso
    echo "Primeiro Acesso";
}
?>

