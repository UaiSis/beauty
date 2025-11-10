<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

//verificar se ele tem a permissão de estar nessa página
if(@$home == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}




$data_hoje = date('Y-m-d');
$data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data_hoje)));

//deletar agendamentos temp com mais de 2 dias
$query = $pdo->query("DELETE FROM agendamentos_temp where data_lanc < '$data_ontem'");


$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";

if($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11'){
    $dia_final_mes = '30';
}else if($mes_atual == '2'){
    $dia_final_mes = '28';
}else{
    $dia_final_mes = '31';
}

$data_final_mes = $ano_atual."-".$mes_atual."-".$dia_final_mes;

// Verificar período selecionado
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'semana';

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
        
        // Serviços
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Serviço' AND DATE(data_lanc) = '$data'");
        $dados_servicos[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Vendas
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Venda' AND DATE(data_lanc) = '$data'");
        $dados_vendas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Despesas
        $query = $pdo->query("SELECT COUNT(*) as total FROM pagar WHERE tipo = 'Conta' AND DATE(data_lanc) = '$data'");
        $dados_despesas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
    }
} else if($periodo == 'mes') {
    // Últimos 30 dias
    for($i = 29; $i >= 0; $i--) {
        $data = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('d', strtotime($data));
        
        // Serviços
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Serviço' AND DATE(data_lanc) = '$data'");
        $dados_servicos[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Vendas
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Venda' AND DATE(data_lanc) = '$data'");
        $dados_vendas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Despesas
        $query = $pdo->query("SELECT COUNT(*) as total FROM pagar WHERE tipo = 'Conta' AND DATE(data_lanc) = '$data'");
        $dados_despesas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
    }
} else if($periodo == 'ano') {
    // 12 meses do ano
    $meses_labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    for($m = 1; $m <= 12; $m++) {
        $mes_num = str_pad($m, 2, '0', STR_PAD_LEFT);
        $labels[] = $meses_labels[$m-1];
        
        // Serviços
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Serviço' AND YEAR(data_lanc) = '$ano_atual' AND MONTH(data_lanc) = '$mes_num'");
        $dados_servicos[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Vendas
        $query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE tipo = 'Venda' AND YEAR(data_lanc) = '$ano_atual' AND MONTH(data_lanc) = '$mes_num'");
        $dados_vendas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Despesas
        $query = $pdo->query("SELECT COUNT(*) as total FROM pagar WHERE tipo = 'Conta' AND YEAR(data_lanc) = '$ano_atual' AND MONTH(data_lanc) = '$mes_num'");
        $dados_despesas[] = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
    }
}



$query = $pdo->query("SELECT * FROM clientes ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_clientes = @count($res);

$query = $pdo->query("SELECT * FROM pagar where data_venc = curDate() and pago != 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_hoje = @count($res);


$query = $pdo->query("SELECT * FROM receber where data_venc = curDate() and pago != 'Sim' and valor > 0 ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_hoje = @count($res);


$query = $pdo->query("SELECT * FROM produtos");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$estoque_baixo = 0;
if($total_reg > 0){
    for($i=0; $i < $total_reg; $i++){
    foreach ($res[$i] as $key => $value){}
        $estoque = $res[$i]['estoque'];
        $nivel_estoque = $res[$i]['nivel_estoque'];

        if($nivel_estoque >= $estoque){
            $estoque_baixo += 1;
        }
    }
}


//totalizando agendamentos
$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_agendamentos_hoje = @count($res);

$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() and status = 'Concluído'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_agendamentos_concluido_hoje = @count($res);


if($total_agendamentos_concluido_hoje > 0 and $total_agendamentos_hoje > 0){
    $porcentagemAgendamentos = ($total_agendamentos_concluido_hoje / $total_agendamentos_hoje) * 100;
}else{
    $porcentagemAgendamentos = 0;
}





//totalizando agendamentos pagos
$query = $pdo->query("SELECT * FROM receber where data_lanc = curDate() and tipo = 'Serviço'  ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_servicos_hoje = @count($res);

$query = $pdo->query("SELECT * FROM receber where data_lanc = curDate() and tipo = 'Serviço' and pago = 'Sim'  ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_servicos_pago_hoje = @count($res);


if($total_servicos_pago_hoje > 0 and $total_servicos_hoje > 0){
    $porcentagemServicos = ($total_servicos_pago_hoje / $total_servicos_hoje) * 100;
}else{
    $porcentagemServicos = 0;
}




//totalizando comissoes pagas mes
$query = $pdo->query("SELECT * FROM pagar where data_lanc >= '$data_inicio_mes' and data_lanc <= '$data_final_mes' and tipo = 'Comissão' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_comissoes_mes = @count($res);

$query = $pdo->query("SELECT * FROM pagar where data_lanc >= '$data_inicio_mes' and data_lanc <= '$data_final_mes' and tipo = 'Comissão' and pago = 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_comissoes_mes_pagas = @count($res);


if($total_comissoes_mes_pagas > 0 and $total_comissoes_mes > 0){
    $porcentagemComissoes = ($total_comissoes_mes_pagas / $total_comissoes_mes) * 100;
}else{
    $porcentagemComissoes = 0;
}






//TOTALIZAR CONTAS DO DIA
$total_debitos_dia = 0;
$query = $pdo->query("SELECT * FROM pagar where data_pgto = curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
for($i=0; $i < @count($res); $i++){
    foreach ($res[$i] as $key => $value){}
        $total_debitos_dia += $res[$i]['valor'];
    }
}

$total_ganhos_dia = 0;
$query = $pdo->query("SELECT * FROM receber where data_pgto = curDate() and valor > 0 ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) > 0){
for($i=0; $i < @count($res); $i++){
    foreach ($res[$i] as $key => $value){}
        $total_ganhos_dia += $res[$i]['valor'];
    }
}

$saldo_total_dia = $total_ganhos_dia - $total_debitos_dia;
$saldo_total_diaF = number_format($saldo_total_dia, 2, ',', '.');

if($saldo_total_dia < 0){
    $classe_saldo_dia = 'user1';
}else{
    $classe_saldo_dia = 'dollar2';
}

// Calcular Ticket Médio do Mês (Serviços + Vendas + Comandas)
// Faturamento de Serviços e Vendas
$query = $pdo->query("SELECT SUM(valor) as total FROM receber WHERE pago = 'Sim' AND data_pgto >= '$data_inicio_mes' AND data_pgto <= '$data_final_mes' AND (tipo = 'Serviço' OR tipo = 'Venda')");
$faturamento_servicos_vendas = $query->fetch(PDO::FETCH_ASSOC)['total'] ?: 0;

// Faturamento de Comandas
$query = $pdo->query("SELECT SUM(valor) as total FROM comandas WHERE status = 'Fechada' AND data >= '$data_inicio_mes' AND data <= '$data_final_mes'");
$faturamento_comandas = $query->fetch(PDO::FETCH_ASSOC)['total'] ?: 0;

$total_faturamento_mes = $faturamento_servicos_vendas + $faturamento_comandas;

// Contagem de atendimentos
$query = $pdo->query("SELECT COUNT(*) as total FROM receber WHERE pago = 'Sim' AND data_pgto >= '$data_inicio_mes' AND data_pgto <= '$data_final_mes' AND (tipo = 'Serviço' OR tipo = 'Venda')");
$atendimentos_servicos_vendas = $query->fetch(PDO::FETCH_ASSOC)['total'] ?: 0;

// Contagem de Comandas
$query = $pdo->query("SELECT COUNT(*) as total FROM comandas WHERE status = 'Fechada' AND data >= '$data_inicio_mes' AND data <= '$data_final_mes'");
$atendimentos_comandas = $query->fetch(PDO::FETCH_ASSOC)['total'] ?: 0;

$total_atendimentos_mes = $atendimentos_servicos_vendas + $atendimentos_comandas;

$ticket_medio = $total_atendimentos_mes > 0 ? $total_faturamento_mes / $total_atendimentos_mes : 0;
$ticket_medioF = number_format($ticket_medio, 2, ',', '.');









 ?>

<?php require_once("vue-libs.php"); ?>


<style>
	/* Dashboard Moderno */
	.dashboard-modern {
		padding: 24px;
		background: #f8f9fa;
	}

	.dashboard-header {
		margin-bottom: 32px;
	}

	.dashboard-title {
		font-size: 28px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 8px;
	}

	.dashboard-subtitle {
		font-size: 14px;
		color: #6c757d;
	}

	/* Alerta Moderno */
	.alert-modern {
		background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
		border: none;
		border-radius: 12px;
		padding: 16px 20px;
		margin-bottom: 24px;
		box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.alert-modern i {
		font-size: 24px;
		color: #fff;
	}

	.alert-modern-content {
		flex: 1;
		color: #fff;
		font-size: 14px;
		line-height: 1.5;
	}

	/* Cards Estatísticas Modernos */
	.stats-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		gap: 20px;
		margin-bottom: 32px;
	}

	.stat-card-modern {
		background: #fff;
		border-radius: 16px;
		padding: 24px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		transition: all 0.3s ease;
		text-decoration: none;
		color: inherit;
		display: block;
		border: 1px solid #f0f0f0;
	}

	.stat-card-modern:hover {
		transform: translateY(-4px);
		box-shadow: 0 8px 24px rgba(0, 122, 99, 0.15);
		text-decoration: none;
		border-color: #007A63;
	}

	.stat-card-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 16px;
	}

	.stat-icon {
		width: 48px;
		height: 48px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
		color: #fff;
	}

	.stat-icon.green {
		background: linear-gradient(135deg, #00d896 0%, #007A63 100%);
	}

	.stat-icon.orange {
		background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
	}

	.stat-icon.red {
		background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
	}

	.stat-icon.blue {
		background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
	}

	.stat-value {
		font-size: 32px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 4px;
	}

	.stat-label {
		font-size: 13px;
		color: #6c757d;
		font-weight: 500;
	}

	.stat-trend {
		font-size: 12px;
		color: #007A63;
		font-weight: 600;
		margin-top: 8px;
	}

	/* Cards de Gráficos Circulares */
	.pie-cards-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
		gap: 20px;
		margin-bottom: 32px;
	}

	.pie-card-modern {
		background: #fff;
		border-radius: 16px;
		padding: 24px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.pie-card-modern h5 {
		font-size: 15px;
		font-weight: 600;
		color: #1a1a1a;
		margin-bottom: 20px;
	}

	.pie-card-content {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.pie-card-info {
		flex: 1;
	}

	.pie-card-info label {
		font-size: 28px;
		font-weight: 700;
		color: #007A63;
		margin: 0;
	}

</style>

<style>
	.dashboard-header-modern {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
	}

	.dashboard-header-content {
		flex: 1;
	}

	.dashboard-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.dashboard-title-icon {
		width: 40px;
		height: 40px;
		background: rgba(0, 122, 99, 0.12);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 20px;
		flex-shrink: 0;
	}

	.dashboard-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.dashboard-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.dashboard-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	/* Charts Cards Mini */
	.chart-card-mini {
		background: #fff;
		border-radius: 16px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
	}

	.chart-card-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 20px;
	}

	.chart-card-title {
		font-size: 16px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0 0 8px 0;
	}

	.chart-period-select {
		border: 1px solid #e0e0e0;
		border-radius: 6px;
		padding: 4px 8px;
		font-size: 12px;
		color: #6c757d;
		background: #fff;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.chart-period-select:focus {
		outline: none;
		border-color: #007A63;
	}

	/* Tooltip customizado */
	.info-tooltip {
		position: relative;
		cursor: help;
	}

	.info-tooltip .tooltiptext {
		visibility: hidden;
		width: 280px;
		background-color: #1a1a1a;
		color: #fff;
		text-align: left;
		border-radius: 8px;
		padding: 12px 16px;
		position: absolute;
		z-index: 1000;
		bottom: 125%;
		left: 50%;
		margin-left: -140px;
		opacity: 0;
		transition: opacity 0.3s;
		font-size: 12px;
		line-height: 1.6;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
	}

	.info-tooltip .tooltiptext::after {
		content: "";
		position: absolute;
		top: 100%;
		left: 50%;
		margin-left: -5px;
		border-width: 5px;
		border-style: solid;
		border-color: #1a1a1a transparent transparent transparent;
	}

	.info-tooltip:hover .tooltiptext {
		visibility: visible;
		opacity: 1;
	}

	.metric-info {
		color: #007A63;
		cursor: help;
		margin-left: 6px;
		font-size: 14px;
	}

	.agenda-item:hover {
		background: #f8f9fa;
	}

	@media (max-width: 1200px) {
		[style*="grid-template-columns: 400px 1fr"] {
			grid-template-columns: 1fr !important;
		}
	}
</style>

<!-- Chart.js para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>


<div id="app-dashboard" class="dashboard-modern">

	<!-- Header -->
	<div class="dashboard-header-modern">
		<div class="dashboard-header-content">
			<div class="dashboard-title-wrapper">
				<div class="dashboard-title-icon">
					<i class="fa fa-tachometer"></i>
                    </div>
				<h1 class="dashboard-title">Dashboard</h1>
			</div>
			<p class="dashboard-subtitle">Visão geral do seu negócio em tempo real</p>
			<div class="dashboard-divider"></div>
		</div>
	</div>

	<?php if($ativo_sistema == ''){ ?>
	<div class="alert-modern" v-show="true">
		<i class="fa fa-exclamation-triangle"></i>
		<div class="alert-modern-content">
			<strong>Aviso:</strong> Prezado Cliente, não identificamos o pagamento de sua última mensalidade. Entre em contato conosco o mais rápido possível para regularizar o pagamento, caso contrário seu acesso ao sistema será desativado.
		</div>
	</div>
	<?php } ?>

	<!-- Cards de Estatísticas -->
	<div class="stats-grid">
		<a href="clientes" class="stat-card-modern">
			<div class="stat-card-header">
				<div class="stat-icon green">
					<i class="fa fa-users"></i>
				</div>
			</div>
			<div class="stat-value"><?php echo $total_clientes ?></div>
			<div class="stat-label">Total de Clientes</div>
		</a>

		<a href="pagar" class="stat-card-modern">
			<div class="stat-card-header">
				<div class="stat-icon orange">
					<i class="fa fa-money"></i>
				</div>
			</div>
			<div class="stat-value"><?php echo $contas_pagar_hoje ?></div>
			<div class="stat-label">Contas à Pagar Hoje</div>
		</a>

		<a href="receber" class="stat-card-modern">
			<div class="stat-card-header">
				<div class="stat-icon green">
					<i class="fa fa-usd"></i>
                    </div>
            </div>
			<div class="stat-value"><?php echo $contas_receber_hoje ?></div>
			<div class="stat-label">Contas à Receber Hoje</div>
		</a>

		<a href="estoque" class="stat-card-modern">
			<div class="stat-card-header">
				<div class="stat-icon red">
					<i class="fa fa-exclamation-triangle"></i>
        </div>
			</div>
			<div class="stat-value"><?php echo $estoque_baixo ?></div>
			<div class="stat-label">Produtos Estoque Baixo</div>
		</a>

		<div class="stat-card-modern">
			<div class="stat-card-header">
				<div class="stat-icon <?php echo $saldo_total_dia < 0 ? 'red' : 'green' ?>">
					<i class="fa fa-dollar"></i>
				</div>
			</div>
			<div class="stat-value">R$ <?php echo @$saldo_total_diaF ?></div>
			<div class="stat-label">Saldo do Dia</div>
		</div>
	</div>

	<div class="pie-cards-grid">
		<div class="pie-card-modern">
			<h5>Agendamentos do Dia</h5>
			<div class="pie-card-content">
				<div class="pie-card-info">
					<label><?php echo $total_agendamentos_hoje ?>+</label>
					<div style="font-size: 12px; color: #6c757d; margin-top: 4px;">Total de agendamentos</div>
				</div>
				<div>
					<div id="demo-pie-1" class="pie-title-center" data-percent="<?php echo $porcentagemAgendamentos ?>">
						<span class="pie-value"></span>
                </div>
                </div>
            </div>
        </div>

		<div class="pie-card-modern">
                    <h5>Serviços Pagos Hoje</h5>
			<div class="pie-card-content">
				<div class="pie-card-info">
                    <label><?php echo $total_servicos_hoje ?>+</label>
					<div style="font-size: 12px; color: #6c757d; margin-top: 4px;">Total de serviços</div>
                    </div>
				<div>
					<div id="demo-pie-2" class="pie-title-center" data-percent="<?php echo $porcentagemServicos ?>">
						<span class="pie-value"></span>
            </div>
        </div>
            </div>
        </div>

		<div class="pie-card-modern">
			<h5>Comissões Pagas do Mês</h5>
			<div class="pie-card-content">
				<div class="pie-card-info">
                    <label><?php echo $total_comissoes_mes ?>+</label>
					<div style="font-size: 12px; color: #6c757d; margin-top: 4px;">Total de comissões</div>
                    </div>
				<div>
					<div id="demo-pie-3" class="pie-title-center" data-percent="<?php echo $porcentagemComissoes ?>">
						<span class="pie-value"></span>
					</div>
                </div>
                </div>
            </div>
        </div>

		<div class="pie-card-modern">
			<h5>Ticket Médio do Mês</h5>
			<div class="pie-card-content">
				<div style="flex: 1;">
					<div style="font-size: 32px; font-weight: 700; color: #42a5f5; margin-bottom: 8px;">
						R$ <?php echo $ticket_medioF ?>
                    </div>
					<div style="font-size: 13px; color: #6c757d; margin-bottom: 4px;">
						<i class="fa fa-chart-line" style="margin-right: 4px;"></i>
						Valor médio por atendimentos concluídos
			</div>
					<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; padding-top: 12px; border-top: 1px solid #f0f0f0;">
						<div>
							<div style="font-size: 11px; color: #6c757d; margin-bottom: 4px;">Faturamento Total</div>
							<div style="font-size: 14px; font-weight: 600; color: #007A63;">R$ <?php echo number_format($total_faturamento_mes, 2, ',', '.'); ?></div>
		</div>
						<div>
							<div style="font-size: 11px; color: #6c757d; margin-bottom: 4px;">Total Atendimentos</div>
							<div style="font-size: 14px; font-weight: 600; color: #1a1a1a;"><?php echo $total_atendimentos_mes; ?></div>
						</div>
						<div>
							<div style="font-size: 11px; color: #6c757d; margin-bottom: 4px;">
								<i class="fa fa-scissors" style="font-size: 10px; margin-right: 2px;"></i> Serviços/Vendas
							</div>
							<div style="font-size: 13px; font-weight: 600; color: #1a1a1a;"><?php echo $atendimentos_servicos_vendas; ?></div>
						</div>
						<div>
							<div style="font-size: 11px; color: #6c757d; margin-bottom: 4px;">
								<i class="fa fa-file-text" style="font-size: 10px; margin-right: 2px;"></i> Comandas
							</div>
							<div style="font-size: 13px; font-weight: 600; color: #1a1a1a;"><?php echo $atendimentos_comandas; ?></div>
						</div>
					</div>
				</div>
			</div>
        </div>

	</div>

	<!-- Gráficos Semanais -->
	<div class="charts-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-top: 32px;">
		
		<!-- Card 1: Serviços -->
		<div class="chart-card-mini">
			<div class="chart-card-header">
				<div>
					<h3 class="chart-card-title">Serviços</h3>
					<select class="chart-period-select select-periodo" onchange="atualizarTodosGraficos(this.value)">
						<option value="semana" <?php echo $periodo == 'semana' ? 'selected' : ''; ?>>esta semana</option>
						<option value="mes" <?php echo $periodo == 'mes' ? 'selected' : ''; ?>>este mês</option>
						<option value="ano" <?php echo $periodo == 'ano' ? 'selected' : ''; ?>>este ano</option>
					</select>
				</div>
			</div>
			<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
				<div style="font-size: 24px; font-weight: 700; color: #007A63;"><?php echo array_sum($dados_servicos); ?></div>
				<div style="font-size: 12px; color: #6c757d;">Total no período</div>
			</div>
			<canvas id="chartServicos" style="max-height: 180px;"></canvas>
		</div>

		<!-- Card 2: Vendas -->
		<div class="chart-card-mini">
			<div class="chart-card-header">
				<div>
					<h3 class="chart-card-title">Vendas</h3>
					<!-- <select class="chart-period-select select-periodo" onchange="atualizarTodosGraficos(this.value)">
						<option value="semana" <?php echo $periodo == 'semana' ? 'selected' : ''; ?>>esta semana</option>
						<option value="mes" <?php echo $periodo == 'mes' ? 'selected' : ''; ?>>este mês</option>
						<option value="ano" <?php echo $periodo == 'ano' ? 'selected' : ''; ?>>este ano</option>
					</select> -->
                    </div>
			</div>
			<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
				<div style="font-size: 24px; font-weight: 700; color:rgb(82, 150, 126);"><?php echo array_sum($dados_vendas); ?></div>
				<div style="font-size: 12px; color: #6c757d;">Total no período</div>
		</div>
			<canvas id="chartVendas" style="max-height: 180px;"></canvas>
	</div>

		<!-- Card 3: Despesas -->
		<div class="chart-card-mini">
			<div class="chart-card-header">
				<div>
					<h3 class="chart-card-title">Despesas</h3>
					<!-- <select class="chart-period-select select-periodo" onchange="atualizarTodosGraficos(this.value)">
						<option value="semana" <?php echo $periodo == 'semana' ? 'selected' : ''; ?>>esta semana</option>
						<option value="mes" <?php echo $periodo == 'mes' ? 'selected' : ''; ?>>este mês</option>
						<option value="ano" <?php echo $periodo == 'ano' ? 'selected' : ''; ?>>este ano</option>
					</select> -->
				</div>
			</div>
			<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
				<div style="font-size: 24px; font-weight: 700; color: #ef5350;"><?php echo array_sum($dados_despesas); ?></div>
				<div style="font-size: 12px; color: #6c757d;">Total no período</div>
			</div>
			<canvas id="chartDespesas" style="max-height: 180px;"></canvas>
		</div>

	</div>

	<!-- Card de Avaliação Geral e Agendamentos -->
	<div style="margin-top: 32px; display: grid; grid-template-columns: 400px 1fr; gap: 20px;">
		
		<!-- Coluna 1: Desempenho Geral -->
		<div class="chart-card-mini">
			<div class="chart-card-header" style="margin-bottom: 24px;">
				<div style="display: flex; align-items: center; gap: 8px;">
					<h3 class="chart-card-title">Desempenho Geral Da Semana</h3>
					<div style="position: relative; display: inline-block;">
						<i class="fa fa-info-circle" style="color: #007A63; font-size: 16px; cursor: help;" 
						   title="Performance calculada com base em: Agendamentos Concluídos, Serviços Pagos e Comissões. As métricas são atualizadas em tempo real conforme você registra dados no sistema."></i>
					</div>
				</div>
				<!-- <select class="chart-period-select select-periodo" onchange="atualizarTodosGraficos(this.value)">
					<option value="semana" <?php echo $periodo == 'semana' ? 'selected' : ''; ?>>Esta Semana</option>
					<option value="mes" <?php echo $periodo == 'mes' ? 'selected' : ''; ?>>Este Mês</option>
					<option value="ano" <?php echo $periodo == 'ano' ? 'selected' : ''; ?>>Este Ano</option>
				</select> -->
			</div>

			<!-- Gráfico Circular de Performance -->
			<div style="text-align: center; margin-bottom: 32px;">
				<div style="position: relative; width: 200px; height: 200px; margin: 0 auto;">
					<canvas id="chartPerformance"></canvas>
					<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
						<div style="font-size: 12px; color: #6c757d; margin-bottom: 4px;">
							Performance
							<i class="fa fa-info-circle" style="color: #007A63; font-size: 12px; cursor: help;" 
							   title="Média geral calculada com base nos 3 indicadores principais do sistema"></i>
                </div>
						<div style="font-size: 32px; font-weight: 700; color: #007A63;" id="performance-value">0%</div>
						<div style="font-size: 11px; color: #00d896; font-weight: 600;" id="performance-change"></div>
                </div>
            </div>
        </div>

			<!-- Métricas Detalhadas -->
			<div style="display: grid; gap: 16px;">
				
				<!-- Agendamentos Concluídos -->
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 600;">
							Agendamentos Concluídos
							<i class="fa fa-question-circle metric-info" title="Percentual de agendamentos marcados como 'Concluído' hoje"></i>
						</span>
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 700;"><?php echo $total_agendamentos_concluido_hoje; ?>/<?php echo $total_agendamentos_hoje; ?></span>
                </div>
					<div style="width: 100%; height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
						<div style="width: <?php echo $porcentagemAgendamentos; ?>%; height: 100%; background: #007A63; border-radius: 10px; transition: width 0.3s ease;"></div>
                </div>
				</div>

				<!-- Serviços Pagos -->
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 600;">
							Serviços Pagos
							<i class="fa fa-question-circle metric-info" title="Percentual de serviços lançados hoje que foram pagos"></i>
						</span>
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 700;"><?php echo $total_servicos_pago_hoje; ?>/<?php echo $total_servicos_hoje; ?></span>
					</div>
					<div style="width: 100%; height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
						<div style="width: <?php echo $porcentagemServicos; ?>%; height: 100%; background: #42a5f5; border-radius: 10px; transition: width 0.3s ease;"></div>
            </div>
        </div>

				<!-- Comissões Pagas -->
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 600;">
							Comissões Pagas do Mês
							<i class="fa fa-question-circle metric-info" title="Percentual de comissões dos funcionários que foram pagas neste mês"></i>
						</span>
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 700;"><?php echo $total_comissoes_mes_pagas; ?>/<?php echo $total_comissoes_mes; ?></span>
                </div>
					<div style="width: 100%; height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
						<div style="width: <?php echo $porcentagemComissoes; ?>%; height: 100%; background:rgb(39, 171, 176); border-radius: 10px; transition: width 0.3s ease;"></div>
                </div>
				</div>

				<!-- Taxa de Ocupação -->
				<?php 
				$taxa_ocupacao = $total_agendamentos_hoje > 0 ? ($total_agendamentos_hoje / 20) * 100 : 0;
				$taxa_ocupacao = min($taxa_ocupacao, 100);
				?>
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 600;">
							Taxa de Ocupação
							<i class="fa fa-question-circle metric-info" title="Percentual de ocupação baseado em 20 agendamentos/dia como capacidade máxima"></i>
						</span>
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 700;"><?php echo round($taxa_ocupacao); ?>%</span>
					</div>
					<div style="width: 100%; height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
						<div style="width: <?php echo $taxa_ocupacao; ?>%; height: 100%; background: #ff9800; border-radius: 10px; transition: width 0.3s ease;"></div>
            </div>
        </div>

				<!-- Produtos com Estoque -->
				<?php 
				$query = $pdo->query("SELECT COUNT(*) as total FROM produtos");
				$total_produtos = (int)$query->fetch(PDO::FETCH_ASSOC)['total'];
				$produtos_ok = $total_produtos - $estoque_baixo;
				$percentual_estoque = $total_produtos > 0 ? ($produtos_ok / $total_produtos) * 100 : 0;
				?>
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 600;">
							Produtos com Estoque
							<i class="fa fa-question-circle metric-info" title="Percentual de produtos que ainda têm estoque disponível (acima do nível mínimo)"></i>
						</span>
						<span style="font-size: 13px; color: #1a1a1a; font-weight: 700;"><?php echo $produtos_ok; ?>/<?php echo $total_produtos; ?></span>
					</div>
					<div style="width: 100%; height: 8px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
						<div style="width: <?php echo $percentual_estoque; ?>%; height: 100%; background: #00d896; border-radius: 10px; transition: width 0.3s ease;"></div>
					</div>
    </div>

			</div>
                    </div>
					
		<!-- Coluna 2: Agendamentos -->
		<div class="chart-card-mini">
			<div style="margin-bottom: 20px;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
					<h3 class="chart-card-title">
						<span id="titulo-agendamentos">Agendamentos de Hoje</span>
						<span id="total-agendamentos" style="font-size: 13px; font-weight: 400; color: #6c757d;">(<?php echo $total_agendamentos_hoje; ?> agendamentos)</span>
					</h3>
					<div style="display: flex; gap: 8px;">
						<input type="text" id="data-agendamentos" value="<?php echo date('d/m/Y'); ?>" 
							   style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 6px 12px; font-size: 13px; cursor: pointer; width: 140px; background: #fff;"
							   placeholder="Selecionar data"
							   readonly>
						<select id="select-funcionario" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 6px 12px; font-size: 13px;" 
								onchange="atualizarAgendamentos()">
							<option value="">Todos os Profissionais</option>
							<?php 
							$query = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY nome asc");
							$funcionarios = $query->fetchAll(PDO::FETCH_ASSOC);
							foreach($funcionarios as $func){
								echo '<option value="'.$func['id'].'">'.$func['nome'].'</option>';
							}
							?>
						</select>
					</div>
						</div>
						
				<!-- Tabs de Filtro -->
				<div style="display: flex; gap: 8px; margin-bottom: 16px; border-bottom: 2px solid #f0f0f0; padding-bottom: 8px;">
					<button onclick="filtrarStatus('todos')" id="tab-todos" 
							style="padding: 6px 16px; border: none; background: transparent; font-size: 13px; font-weight: 600; color: #007A63; border-bottom: 2px solid #007A63; cursor: pointer;">
						Todos
					</button>
					<button onclick="filtrarStatus('Agendado')" id="tab-agendado"
							style="padding: 6px 16px; border: none; background: transparent; font-size: 13px; font-weight: 600; color: #6c757d; cursor: pointer;">
						Pendentes
					</button>
					<button onclick="filtrarStatus('Concluído')" id="tab-concluido"
							style="padding: 6px 16px; border: none; background: transparent; font-size: 13px; font-weight: 600; color: #6c757d; cursor: pointer;">
						Concluídos
					</button>
					<button onclick="filtrarStatus('Cancelado')" id="tab-cancelado"
							style="padding: 6px 16px; border: none; background: transparent; font-size: 13px; font-weight: 600; color: #6c757d; cursor: pointer;">
						Cancelados
					</button>
				</div>

				<!-- Busca -->
				<div style="margin-bottom: 16px;">
					<input type="text" id="busca-agendamentos" placeholder="Buscar por nome ou telefone..." 
						   style="width: 100%; padding: 10px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px;"
						   onkeyup="buscarAgendamentos()">
				</div>
		</div>

			<!-- Lista de Agendamentos -->
			<div id="lista-agendamentos" style="max-height: 500px; overflow-y: auto;">
				<?php 
				$data_filtro = isset($_GET['data_agenda']) ? $_GET['data_agenda'] : $data_hoje;
				$func_filtro = isset($_GET['funcionario']) ? $_GET['funcionario'] : '';
				
				$where_func = $func_filtro ? "AND funcionario = '$func_filtro'" : '';
				$query = $pdo->query("SELECT * FROM agendamentos WHERE data = '$data_filtro' $where_func ORDER BY hora asc");
				$agendamentos = $query->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($agendamentos) > 0){
					foreach($agendamentos as $agenda){
						$id_agenda = $agenda['id'];
						$cliente_id = $agenda['cliente'];
						$hora = substr($agenda['hora'], 0, 5);
						$status = $agenda['status'];
						$obs = $agenda['obs'];
						$funcionario_id = $agenda['funcionario'];
						$servico_id = $agenda['servico'];
						
						// Buscar cliente
						$query2 = $pdo->query("SELECT * FROM clientes WHERE id = '$cliente_id'");
						$cliente = $query2->fetch(PDO::FETCH_ASSOC);
						$nome_cliente = $cliente ? $cliente['nome'] : 'Cliente não encontrado';
						$telefone_cliente = $cliente ? $cliente['telefone'] : '';
						
						// Buscar serviço
						$query2 = $pdo->query("SELECT * FROM servicos WHERE id = '$servico_id'");
						$servico = $query2->fetch(PDO::FETCH_ASSOC);
						$nome_servico = $servico ? $servico['nome'] : 'Serviço';
						
						// Buscar funcionário
						$query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '$funcionario_id'");
						$funcionario = $query2->fetch(PDO::FETCH_ASSOC);
						$nome_funcionario = $funcionario ? $funcionario['nome'] : 'Profissional';
						
						// Status badge
						$status_color = '#ff9800';
						$status_bg = 'rgba(255, 152, 0, 0.1)';
						if($status == 'Concluído'){
							$status_color = '#00d896';
							$status_bg = 'rgba(0, 216, 150, 0.1)';
						} else if($status == 'Cancelado'){
							$status_color = '#ef5350';
							$status_bg = 'rgba(239, 83, 80, 0.1)';
						}
						
						// Avatar com iniciais
						$iniciais = '';
						$palavras = explode(' ', $nome_cliente);
						if(count($palavras) >= 2){
							$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
						}else{
							$iniciais = strtoupper(substr($nome_cliente, 0, 2));
						}
						
						$cores_avatar = ['#007A63', '#42a5f5', '#9c27b0', '#ff9800', '#ef5350', '#00d896'];
						$cor_avatar = $cores_avatar[$id_agenda % count($cores_avatar)];
						
						echo '<div class="agenda-item" data-status="'.$status.'" style="display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f0f0f0; transition: all 0.2s ease;">
							<div style="width: 44px; height: 44px; border-radius: 50%; background: '.$cor_avatar.'; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: #fff; margin-right: 12px; flex-shrink: 0;">
								'.$iniciais.'
</div>
							<div style="flex: 1; min-width: 0;">
								<div style="font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 2px;">'.$nome_cliente.'</div>
								<div style="font-size: 12px; color: #6c757d;">'.$telefone_cliente.'</div>
</div>
							<div style="text-align: right; margin-right: 12px;">
								<div style="font-size: 13px; font-weight: 600; color: #1a1a1a;">'.$hora.'</div>
								<div style="font-size: 11px; color: #6c757d;">'.$nome_servico.'</div>
							</div>
							<div>
								<span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: '.$status_bg.'; color: '.$status_color.';">
									'.$status.'
								</span>
							</div>
							<div style="margin-left: 12px; display: flex; gap: 4px;">
								<a href="agendamentos" class="table-action-icon view" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: rgba(66, 165, 245, 0.08); color: #42a5f5; text-decoration: none;">
									<i class="fa fa-eye"></i>
								</a>
							</div>
						</div>';
					}
				} else {
					echo '<div style="text-align: center; padding: 40px 20px; color: #6c757d;">
						<i class="fa fa-calendar-o" style="font-size: 32px; margin-bottom: 12px; opacity: 0.3;"></i>
						<p style="font-size: 14px; margin: 0;">Nenhum agendamento para esta data</p>
					</div>';
				}
				?>
			</div>
		</div>

</div>

	<!-- Abas de Notificações -->
	<?php 
	// Buscar dados para as abas
	$partesInicial = explode('-', $data_hoje);
	$dataDiaInicial = $partesInicial[2];
	$dataMesInicial = $partesInicial[1];
	
	// Agendamentos do dia do usuário
	if($atendimento == 'Sim'){ 
		$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() and funcionario = '$id_usuario' and status = 'Agendado'");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_agendamentos_hoje_usuario_pendentes = @count($res);
		$agendamentos_usuario = $res;
	} else {
		$total_agendamentos_hoje_usuario_pendentes = 0;
		$agendamentos_usuario = [];
	}
	
	// Aniversariantes do dia
	$query = $pdo->query("SELECT * FROM clientes where month(data_nasc) = '$dataMesInicial' and day(data_nasc) = '$dataDiaInicial' order by data_nasc asc, id asc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_aniversariantes_hoje = @count($res);
	$aniversariantes = $res;
	
	// Clientes com retorno
	$query = $pdo->query("SELECT * FROM clientes where alertado != 'Sim' and data_retorno < curDate() ORDER BY data_retorno asc");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_clientes_retorno = @count($res);
	$clientes_retorno = $res;
	
	// Comentários pendentes
	$query = $pdo->query("SELECT * FROM comentarios where ativo != 'Sim'");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_comentarios = @count($res);
	$comentarios_pendentes = $res;
	?>
	
	<div class="notifications-tabs-container" style="margin-top: 32px;">
		<div class="notifications-tabs-header">
			<h3 style="font-size: 16px; font-weight: 600; color: #1a1a1a; margin: 0;">Notificações e Alertas</h3>
		</div>
		
		<div class="notifications-tabs">
			<?php if($atendimento == 'Sim' && $total_agendamentos_hoje_usuario_pendentes > 0){ ?>
			<button class="notification-tab active" onclick="mostrarAba('agendamentos')">
				<div class="tab-icon" style="background: linear-gradient(135deg, #007A63 0%, #00d896 100%);">
					<i class="fa fa-calendar-check-o"></i>
				</div>
				<div class="tab-content">
					<div class="tab-title">Agendamentos</div>
					<div class="tab-count"><?php echo $total_agendamentos_hoje_usuario_pendentes ?> pendente<?php echo $total_agendamentos_hoje_usuario_pendentes > 1 ? 's' : ''; ?></div>
				</div>
				<?php if($total_agendamentos_hoje_usuario_pendentes > 0){ ?>
				<div class="tab-badge" style="background: #ef5350;"><?php echo $total_agendamentos_hoje_usuario_pendentes ?></div>
				<?php } ?>
			</button>
			<?php } ?>
			
			<?php if($total_aniversariantes_hoje > 0){ ?>
			<button class="notification-tab <?php echo ($atendimento != 'Sim' || $total_agendamentos_hoje_usuario_pendentes == 0) ? 'active' : ''; ?>" onclick="mostrarAba('aniversariantes')">
				<div class="tab-icon" style="background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);">
					<i class="fa fa-birthday-cake"></i>
				</div>
				<div class="tab-content">
					<div class="tab-title">Aniversariantes</div>
					<div class="tab-count"><?php echo $total_aniversariantes_hoje ?> hoje</div>
				</div>
				<?php if($total_aniversariantes_hoje > 0){ ?>
				<div class="tab-badge" style="background: #9c27b0;"><?php echo $total_aniversariantes_hoje ?></div>
				<?php } ?>
			</button>
			<?php } ?>
			
			<?php if($total_clientes_retorno > 0){ ?>
			<button class="notification-tab <?php echo ($atendimento != 'Sim' || $total_agendamentos_hoje_usuario_pendentes == 0) && $total_aniversariantes_hoje == 0 ? 'active' : ''; ?>" onclick="mostrarAba('retorno')">
				<div class="tab-icon" style="background: linear-gradient(135deg, #ff9800 0%, #ffa726 100%);">
					<i class="fa fa-history"></i>
				</div>
				<div class="tab-content">
					<div class="tab-title">Clientes Retorno</div>
					<div class="tab-count"><?php echo $total_clientes_retorno ?> cliente<?php echo $total_clientes_retorno > 1 ? 's' : ''; ?></div>
				</div>
				<?php if($total_clientes_retorno > 0){ ?>
				<div class="tab-badge" style="background: #ff9800;"><?php echo $total_clientes_retorno ?></div>
				<?php } ?>
			</button>
			<?php } ?>
			
			<?php if($total_comentarios > 0){ ?>
			<button class="notification-tab <?php echo ($atendimento != 'Sim' || $total_agendamentos_hoje_usuario_pendentes == 0) && $total_aniversariantes_hoje == 0 && $total_clientes_retorno == 0 ? 'active' : ''; ?>" onclick="mostrarAba('comentarios')">
				<div class="tab-icon" style="background: linear-gradient(135deg, #42a5f5 0%, #64b5f6 100%);">
					<i class="fa fa-comment"></i>
				</div>
				<div class="tab-content">
					<div class="tab-title">Comentários</div>
					<div class="tab-count"><?php echo $total_comentarios ?> pendente<?php echo $total_comentarios > 1 ? 's' : ''; ?></div>
				</div>
				<?php if($total_comentarios > 0){ ?>
				<div class="tab-badge" style="background: #42a5f5;"><?php echo $total_comentarios ?></div>
				<?php } ?>
			</button>
			<?php } ?>
		</div>
		
		<!-- Conteúdo das abas -->
		<div class="notifications-tab-content">
			<!-- Agendamentos -->
			<?php if($atendimento == 'Sim' && $total_agendamentos_hoje_usuario_pendentes > 0){ ?>
			<div id="aba-agendamentos" class="aba-content active">
				<div class="aba-header">
					<h4>
						<i class="fa fa-calendar-check-o" style="margin-right: 8px; color: #007A63;"></i>
						Meus Agendamentos Pendentes Hoje
					</h4>
					<a href="agenda" class="btn-view-all">
						<i class="fa fa-eye"></i> Ver Todos
					</a>
				</div>
				<div class="aba-list">
					<?php 
					foreach($agendamentos_usuario as $agenda){
						$id = $agenda['id'];								
						$cliente = $agenda['cliente'];
						$hora = $agenda['hora'];
						$servico = $agenda['servico'];
						$horaF = date("H:i", strtotime($hora));
						
						$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if(@count($res2) > 0){
							$nome_serv = $res2[0]['nome'];
						}else{
							$nome_serv = 'Não Lançado';
						}
						
						$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
						$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
						if(@count($res2) > 0){
							$nome_cliente = $res2[0]['nome'];
							$telefone_cliente = $res2[0]['telefone'];
						}else{
							$nome_cliente = 'Sem Cliente';
							$telefone_cliente = '';
						}
					?>
					<div class="aba-list-item">
						<div class="aba-item-icon" style="background: rgba(0, 122, 99, 0.1); color: #007A63;">
							<i class="fa fa-clock-o"></i>
						</div>
						<div class="aba-item-content">
							<div class="aba-item-title"><?php echo $horaF ?> - <?php echo $nome_cliente ?></div>
							<div class="aba-item-subtitle">
								<i class="fa fa-scissors" style="margin-right: 4px;"></i><?php echo $nome_serv ?>
								<?php if($telefone_cliente){ ?>
								<span style="margin-left: 12px;"><i class="fa fa-phone" style="margin-right: 4px;"></i><?php echo $telefone_cliente ?></span>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<!-- Aniversariantes -->
			<?php if($total_aniversariantes_hoje > 0){ ?>
			<div id="aba-aniversariantes" class="aba-content <?php echo ($atendimento != 'Sim' || $total_agendamentos_hoje_usuario_pendentes == 0) ? 'active' : ''; ?>">
				<div class="aba-header">
					<h4>
						<i class="fa fa-birthday-cake" style="margin-right: 8px; color: #9c27b0;"></i>
						Aniversariantes de Hoje
					</h4>
					<a href="#" data-toggle="modal" data-target="#RelAniv" class="btn-view-all">
						<i class="fa fa-file-text-o"></i> Relatório Completo
					</a>
				</div>
				<div class="aba-list">
					<?php 
					foreach($aniversariantes as $aniv){
						$nome = $aniv['nome'];	
						$telefone = $aniv['telefone'];
					?>
					<div class="aba-list-item">
						<div class="aba-item-icon" style="background: rgba(156, 39, 176, 0.1); color: #9c27b0;">
							<i class="fa fa-user"></i>
						</div>
						<div class="aba-item-content">
							<div class="aba-item-title"><?php echo $nome ?></div>
							<div class="aba-item-subtitle">
								<i class="fa fa-phone" style="margin-right: 4px;"></i><?php echo $telefone ?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<!-- Clientes Retorno -->
			<?php if($total_clientes_retorno > 0){ ?>
			<div id="aba-retorno" class="aba-content <?php echo ($atendimento != 'Sim' || $total_agendamentos_hoje_usuario_pendentes == 0) && $total_aniversariantes_hoje == 0 ? 'active' : ''; ?>">
				<div class="aba-header">
					<h4>
						<i class="fa fa-history" style="margin-right: 8px; color: #ff9800;"></i>
						Clientes com Retorno Pendente
					</h4>
					<a href="clientes_retorno" class="btn-view-all">
						<i class="fa fa-list"></i> Ver Todos
					</a>
				</div>
				<div class="aba-list">
					<?php 
					foreach($clientes_retorno as $cretorno){
						$nome = $cretorno['nome'];	
						$telefone = $cretorno['telefone'];
					?>
					<div class="aba-list-item">
						<div class="aba-item-icon" style="background: rgba(255, 152, 0, 0.1); color: #ff9800;">
							<i class="fa fa-user"></i>
						</div>
						<div class="aba-item-content">
							<div class="aba-item-title"><?php echo $nome ?></div>
							<div class="aba-item-subtitle">
								<i class="fa fa-phone" style="margin-right: 4px;"></i><?php echo $telefone ?>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			
			<!-- Comentários -->
			<?php if($total_comentarios > 0){ ?>
			<div id="aba-comentarios" class="aba-content <?php echo ($atendimento != 'Sim' || $total_agendamentos_hoje_usuario_pendentes == 0) && $total_aniversariantes_hoje == 0 && $total_clientes_retorno == 0 ? 'active' : ''; ?>">
				<div class="aba-header">
					<h4>
						<i class="fa fa-comment-o" style="margin-right: 8px; color: #42a5f5;"></i>
						Depoimentos Pendentes de Aprovação
					</h4>
					<a href="comentarios" class="btn-view-all">
						<i class="fa fa-eye"></i> Ver Todos
					</a>
				</div>
				<div class="aba-list">
					<?php 
					foreach($comentarios_pendentes as $coment){
						$nome = $coment['nome'];
					?>
					<div class="aba-list-item">
						<div class="aba-item-icon" style="background: rgba(66, 165, 245, 0.1); color: #42a5f5;">
							<i class="fa fa-user"></i>
						</div>
						<div class="aba-item-content">
							<div class="aba-item-title"><?php echo $nome ?></div>
							<div class="aba-item-subtitle">
								<i class="fa fa-star-o" style="margin-right: 4px;"></i>Aguardando aprovação
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<style>
		/* Container das Abas */
		.notifications-tabs-container {
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
			border: 1px solid #f0f0f0;
			overflow: hidden;
		}
		
		.notifications-tabs-header {
			padding: 20px 24px;
			border-bottom: 1px solid #f0f0f0;
		}
		
		.notifications-tabs {
			display: flex;
			gap: 0;
			background: #f8f9fa;
			padding: 0;
			overflow-x: auto;
			border-bottom: 2px solid #e0e0e0;
		}
		
		.notification-tab {
			flex: 1;
			min-width: 200px;
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 16px 20px;
			background: transparent;
			border: none;
			border-bottom: 3px solid transparent;
			cursor: pointer;
			transition: all 0.3s ease;
			position: relative;
		}
		
		.notification-tab:hover {
			background: rgba(0, 122, 99, 0.05);
		}
		
		.notification-tab.active {
			background: #fff;
			border-bottom-color: #007A63;
		}
		
		.tab-icon {
			width: 44px;
			height: 44px;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 18px;
			color: #fff;
			flex-shrink: 0;
		}
		
		.tab-content {
			flex: 1;
			text-align: left;
		}
		
		.tab-title {
			font-size: 14px;
			font-weight: 600;
			color: #1a1a1a;
			margin-bottom: 2px;
		}
		
		.tab-count {
			font-size: 12px;
			color: #6c757d;
		}
		
		.tab-badge {
			min-width: 24px;
			height: 24px;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 11px;
			font-weight: 700;
			color: #fff;
			padding: 0 8px;
		}
		
		/* Conteúdo das Abas */
		.notifications-tab-content {
			padding: 24px;
		}
		
		.aba-content {
			display: none;
		}
		
		.aba-content.active {
			display: block;
		}
		
		.aba-header {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 20px;
			padding-bottom: 16px;
			border-bottom: 2px solid #f0f0f0;
		}
		
		.aba-header h4 {
			font-size: 16px;
			font-weight: 600;
			color: #1a1a1a;
			margin: 0;
			display: flex;
			align-items: center;
		}
		
		.btn-view-all {
			padding: 8px 16px;
			border-radius: 8px;
			background: rgba(0, 122, 99, 0.08);
			color: #007A63;
			font-size: 13px;
			font-weight: 600;
			text-decoration: none;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 6px;
		}
		
		.btn-view-all:hover {
			background: #007A63;
			color: #fff;
			text-decoration: none;
		}
		
		.aba-list {
			display: grid;
			gap: 12px;
		}
		
		.aba-list-item {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 12px;
			border-radius: 12px;
			background: #f8f9fa;
			transition: all 0.3s ease;
		}
		
		.aba-list-item:hover {
			background: #f0f0f0;
			transform: translateX(4px);
		}
		
		.aba-item-icon {
			width: 40px;
			height: 40px;
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 16px;
			flex-shrink: 0;
		}
		
		.aba-item-content {
			flex: 1;
		}
		
		.aba-item-title {
			font-size: 14px;
			font-weight: 600;
			color: #1a1a1a;
			margin-bottom: 4px;
		}
		
		.aba-item-subtitle {
			font-size: 12px;
			color: #6c757d;
		}
		
		/* Responsivo */
		@media (max-width: 768px) {
			.notifications-tabs {
				flex-wrap: nowrap;
				overflow-x: auto;
			}
			
			.notification-tab {
				min-width: 160px;
			}
			
			.tab-icon {
				width: 36px;
				height: 36px;
				font-size: 16px;
			}
			
			.aba-header {
				flex-direction: column;
				align-items: flex-start;
				gap: 12px;
			}
		}
	</style>
	
	<script>
		function mostrarAba(aba) {
			// Remover active de todas as abas
			document.querySelectorAll('.notification-tab').forEach(tab => {
				tab.classList.remove('active');
			});
			
			// Remover active de todos os conteúdos
			document.querySelectorAll('.aba-content').forEach(content => {
				content.classList.remove('active');
			});
			
			// Ativar aba clicada
			event.currentTarget.classList.add('active');
			
			// Mostrar conteúdo correspondente
			document.getElementById('aba-' + aba).classList.add('active');
		}
	</script>

</div>

<!-- Inicializar Flatpickr no Date Picker -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Inicializar Flatpickr
	flatpickr("#data-agendamentos", {
		locale: "pt",
		dateFormat: "d/m/Y",
		defaultDate: "<?php echo date('d/m/Y'); ?>",
		onChange: function(selectedDates, dateStr, instance) {
			atualizarAgendamentos();
		},
		theme: "material_green"
	});
});

// Atualizar agendamentos
function atualizarAgendamentos() {
	const input = document.getElementById('data-agendamentos');
	const dataStr = input.value;
	
	// Converter DD/MM/YYYY para YYYY-MM-DD
	const partes = dataStr.split('/');
	const data = partes[2] + '-' + partes[1] + '-' + partes[0];
	
	const funcionario = document.getElementById('select-funcionario').value;

	// Atualizar título
	const hoje = new Date().toISOString().split('T')[0];
	const titulo = document.getElementById('titulo-agendamentos');
	
	if(data === hoje) {
		titulo.textContent = 'Agendamentos de Hoje';
	} else {
		const dataSelecionada = new Date(data + 'T00:00:00');
		const opcoes = { day: '2-digit', month: 'long' };
		const dataFormatada = dataSelecionada.toLocaleDateString('pt-BR', opcoes);
		titulo.textContent = 'Agendamentos de ' + dataFormatada;
	}

	// Buscar agendamentos
	fetch('paginas/home/listar-agendamentos.php', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'data=' + data + '&funcionario=' + funcionario
	})
	.then(response => response.text())
	.then(html => {
		document.getElementById('lista-agendamentos').innerHTML = html;
		const totalAgendamentos = html.match(/class="agenda-item"/g)?.length || 0;
		document.getElementById('total-agendamentos').textContent = '(' + totalAgendamentos + ' agendamentos)';
		if(typeof filtrarStatus === 'function') filtrarStatus('todos');
	})
	.catch(error => console.error('Erro ao atualizar agendamentos:', error));
}
</script>

<!-- Espaço para novos componentes -->










<!-- Inicializar Gráficos com Dados Reais -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	
	// Dados reais do PHP
	const labelsServicos = <?php echo json_encode($labels); ?>;
	const dadosServicos = <?php echo json_encode($dados_servicos); ?>;
	const dadosVendas = <?php echo json_encode($dados_vendas); ?>;
	const dadosDespesas = <?php echo json_encode($dados_despesas); ?>;
	
	// Gráfico 1: Serviços (Barras Verde)
	const ctxServicos = document.getElementById('chartServicos');
	if(ctxServicos) {
		chartServicos = new Chart(ctxServicos, {
			type: 'bar',
			data: {
				labels: labelsServicos,
				datasets: [{
					data: dadosServicos,
					backgroundColor: '#007A63',
					borderRadius: 8,
					barThickness: 24
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: { legend: { display: false } },
				scales: {
					y: { 
						beginAtZero: true,
						grid: { color: '#f0f0f0', drawBorder: false },
						ticks: { 
							color: '#6c757d', 
							font: { size: 11 },
							stepSize: 1
						}
					},
					x: { 
						grid: { display: false, drawBorder: false },
						ticks: { color: '#6c757d', font: { size: 11 } }
					}
				}
			}
		});
	}

	// Gráfico 2: Vendas (Linha Verde Claro)
	const ctxVendas = document.getElementById('chartVendas');
	if(ctxVendas) {
		chartVendas = new Chart(ctxVendas, {
			type: 'line',
			data: {
				labels: labelsServicos,
				datasets: [{
					data: dadosVendas,
					borderColor: '#C0EEDE',
					backgroundColor: 'rgba(192, 238, 222, 0.2)',
					tension: 0.4,
					fill: true,
					pointRadius: 5,
					pointBackgroundColor: '#C0EEDE',
					pointBorderWidth: 2,
					pointBorderColor: '#fff',
					borderWidth: 3
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: { legend: { display: false } },
				scales: {
					y: { 
						beginAtZero: true,
						grid: { color: '#f0f0f0', drawBorder: false },
						ticks: { 
							color: '#6c757d', 
							font: { size: 11 },
							stepSize: 1
						}
					},
					x: { 
						grid: { display: false, drawBorder: false },
						ticks: { color: '#6c757d', font: { size: 11 } }
					}
				}
			}
		});
	}

	// Gráfico 3: Despesas (Barras Vermelha)
	const ctxDespesas = document.getElementById('chartDespesas');
	if(ctxDespesas) {
		chartDespesas = new Chart(ctxDespesas, {
			type: 'bar',
			data: {
				labels: labelsServicos,
				datasets: [{
					data: dadosDespesas,
					backgroundColor: '#ef5350',
					borderRadius: 8,
					barThickness: 24
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: { legend: { display: false } },
				scales: {
					y: { 
						beginAtZero: true,
						grid: { color: '#f0f0f0', drawBorder: false },
						ticks: { 
							color: '#6c757d', 
							font: { size: 11 },
							stepSize: 1
						}
					},
					x: { 
						grid: { display: false, drawBorder: false },
						ticks: { color: '#6c757d', font: { size: 11 } }
					}
				}
			}
		});
	}

	// Gráfico Circular de Performance Geral
	const ctxPerformance = document.getElementById('chartPerformance');
	if(ctxPerformance) {
		// Calcular performance média
		const agendamentos = <?php echo $porcentagemAgendamentos; ?>;
		const servicos = <?php echo $porcentagemServicos; ?>;
		const comissoes = <?php echo $porcentagemComissoes; ?>;
		const performance = Math.round((agendamentos + servicos + comissoes) / 3);
		
		document.getElementById('performance-value').textContent = performance + '%';
		document.getElementById('performance-change').textContent = '+' + Math.round(performance * 0.31) + '%';
		
		new Chart(ctxPerformance, {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [performance, 100 - performance],
					backgroundColor: ['#007A63', '#f0f0f0'],
					borderWidth: 0,
					circumference: 180,
					rotation: 270
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				cutout: '75%',
				plugins: { legend: { display: false }, tooltip: { enabled: false } }
			}
		});
	}
});

// Filtrar agendamentos por status
function filtrarStatus(status) {
	const items = document.querySelectorAll('.agenda-item');
	const tabs = document.querySelectorAll('[id^="tab-"]');
	
	// Reset tabs
	tabs.forEach(tab => {
		tab.style.color = '#6c757d';
		tab.style.borderBottom = 'none';
	});
	
	// Ativar tab selecionada
	const tabAtiva = document.getElementById('tab-' + status.toLowerCase());
	if(tabAtiva) {
		tabAtiva.style.color = '#007A63';
		tabAtiva.style.borderBottom = '2px solid #007A63';
	}
	
	// Filtrar items
	items.forEach(item => {
		if(status === 'todos') {
			item.style.display = 'flex';
		} else {
			item.style.display = item.dataset.status === status ? 'flex' : 'none';
		}
	});
}

// Buscar agendamentos
function buscarAgendamentos() {
	const busca = document.getElementById('busca-agendamentos').value.toLowerCase();
	const items = document.querySelectorAll('.agenda-item');
	
	items.forEach(item => {
		const texto = item.textContent.toLowerCase();
		item.style.display = texto.includes(busca) ? 'flex' : 'none';
	});
}

// Variáveis globais dos gráficos
let chartServicos, chartVendas, chartDespesas;

// Atualizar todos os gráficos via AJAX
function atualizarTodosGraficos(periodo) {
	// Sincronizar todos os selects
	document.querySelectorAll('.select-periodo').forEach(select => {
		select.value = periodo;
	});

	// Buscar novos dados
	fetch('paginas/home/atualizar-graficos.php', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'periodo=' + periodo
	})
	.then(response => response.json())
	.then(data => {
		// Atualizar gráfico de Serviços
		if(chartServicos) {
			chartServicos.data.labels = data.labels;
			chartServicos.data.datasets[0].data = data.servicos;
			chartServicos.update();
			document.querySelector('#chartServicos').parentElement.querySelector('[style*="font-size: 24px"]').textContent = data.total_servicos;
		}

		// Atualizar gráfico de Vendas
		if(chartVendas) {
			chartVendas.data.labels = data.labels;
			chartVendas.data.datasets[0].data = data.vendas;
			chartVendas.update();
			document.querySelector('#chartVendas').parentElement.querySelector('[style*="font-size: 24px"]').textContent = data.total_vendas;
		}

		// Atualizar gráfico de Despesas
		if(chartDespesas) {
			chartDespesas.data.labels = data.labels;
			chartDespesas.data.datasets[0].data = data.despesas;
			chartDespesas.update();
			document.querySelector('#chartDespesas').parentElement.querySelector('[style*="font-size: 24px"]').textContent = data.total_despesas;
		}
	})
	.catch(error => console.error('Erro ao atualizar gráficos:', error));
}


    </script>
	
	