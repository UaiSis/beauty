<?php
require_once("../../../conexao.php");

$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : 'semana';
$ano_atual = date('Y');

$dados_servicos = [];
$dados_vendas = [];
$dados_despesas = [];
$labels = [];

if($periodo == 'semana') {
    // Últimos 7 dias
    for($i = 6; $i >= 0; $i--) {
        $data = date('Y-m-d', strtotime("-$i days"));
        $dia_semana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'][date('w', strtotime($data))];
        $labels[] = $dia_semana;
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Serviço' AND DATE(data_lanc) = '$data'");
        $dados_servicos[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Venda' AND DATE(data_lanc) = '$data'");
        $dados_vendas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM pagar WHERE tipo = 'Conta' AND DATE(data_lanc) = '$data'");
        $dados_despesas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
    }
} else if($periodo == 'mes') {
    // Últimos 30 dias
    for($i = 29; $i >= 0; $i--) {
        $data = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('d', strtotime($data));
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Serviço' AND DATE(data_lanc) = '$data'");
        $dados_servicos[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Venda' AND DATE(data_lanc) = '$data'");
        $dados_vendas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM pagar WHERE tipo = 'Conta' AND DATE(data_lanc) = '$data'");
        $dados_despesas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
    }
} else if($periodo == 'ano') {
    // 12 meses do ano
    $meses_labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    for($m = 1; $m <= 12; $m++) {
        $mes_num = str_pad($m, 2, '0', STR_PAD_LEFT);
        $labels[] = $meses_labels[$m-1];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Serviço' AND YEAR(data_lanc) = '$ano_atual' AND MONTH(data_lanc) = '$mes_num'");
        $dados_servicos[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Venda' AND YEAR(data_lanc) = '$ano_atual' AND MONTH(data_lanc) = '$mes_num'");
        $dados_vendas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        $query = $pdo->query("SELECT COUNT(*) as total FROM pagar WHERE tipo = 'Conta' AND YEAR(data_lanc) = '$ano_atual' AND MONTH(data_lanc) = '$mes_num'");
        $dados_despesas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
    }
}

echo json_encode([
    'labels' => $labels,
    'servicos' => $dados_servicos,
    'vendas' => $dados_vendas,
    'despesas' => $dados_despesas,
    'total_servicos' => array_sum($dados_servicos),
    'total_vendas' => array_sum($dados_vendas),
    'total_despesas' => array_sum($dados_despesas)
]);
?>


