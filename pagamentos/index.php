<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include("./config.php");
require("../sistema/conexao.php");

$id_conta = filter_var($_GET['id_conta'], @FILTER_SANITIZE_STRING);
$total = filter_var($_GET['total'], @FILTER_SANITIZE_STRING);

if($opcao_pagar == 'Não'){
    header("Location: {$url_sistema}pagamentos/pagamento_aprovado.php?id_agd={$id_conta}");
    exit;
}

if($pgto_api != 'Sim'){
    echo "<script>window.location='$url_sistema/pagamentos/pagamento_aprovado.php?id_agd=$id_conta'</script>";
}

$query = $pdo->prepare("SELECT * FROM agendamentos_temp where id = :id_conta");
$query->bindValue(":id_conta", "$id_conta");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$cliente = $res[0]['cliente'];
$servico = $res[0]['servico'];
$ref_pix = $res[0]['ref_pix'];
$data = $res[0]['data'];
$hora = $res[0]['hora'];
$funcionario = $res[0]['funcionario'];
$dataF = implode('/', array_reverse(explode('-', $data)));
$horaF = date("H:i", strtotime($hora));

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = $res[0]['nome'];
$valor = $res[0]['valor'];
$tempo_servico = $res[0]['tempo'];

if($porc_servico > 0 and $total != 100){
    $valor = $valor * $porc_servico / 100;
}

$valorF = number_format($valor, 2, ',', '.');

if($ref_pix != ""){
     require('consultar_pagamento.php');
     if($status_api == 'approved'){
         echo 'Essa conta Já foi Paga';  
         exit();  
        }
}

$query = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_cliente =  $res[0]['nome'];
$cpf_cliente =  '450.417.700-50';
$email_cliente =  'cliente@hotmail.com';

$query = $pdo->query("SELECT * FROM usuarios where id = '$funcionario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_funcionario = @$res[0]['nome'];

$token_valor = ($valor!="")? sha1($valor) : "";
$doc = $cpf_cliente;
$doc =  str_replace(array(",", ".", "-", "/", " "), "", $doc);
$ref = $_REQUEST["ref"];
$email = $email_cliente;
$gerarDireto = $_REQUEST["gerarDireto"];
$descricao = $descricao;
$nome = $nome_cliente;
$sobrenome = $_REQUEST["sobrenome"];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Finalizar Pagamento</title>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- MercadoPago SDK -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    
    <!-- jQuery -->
    <script src="./assets/jquery-3.6.4.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f3ede0;
            min-height: 100vh;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Header */
        .payment-header {
            background: linear-gradient(135deg, #006854 0%, #005246 100%);
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(185, 228, 212, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border: 3px solid rgba(185, 228, 212, 0.3);
        }
        
        .header-icon i,
        .header-icon svg {
            width: 40px;
            height: 40px;
            color: #B9E4D4 !important;
            stroke: #B9E4D4 !important;
        }
        
        .header-title {
            font-size: 32px;
            font-weight: 800;
            color: #FEFEFE;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }
        
        .header-subtitle {
            font-size: 16px;
            color: rgba(254, 254, 254, 0.75);
        }
        
        /* Grid Layout */
        .payment-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        
        /* Cards */
        .payment-card {
            background: #007A63;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }
        
        .card-icon {
            width: 56px;
            height: 56px;
            background: rgba(185, 228, 212, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .card-icon i,
        .card-icon svg {
            width: 28px;
            height: 28px;
            color: #B9E4D4 !important;
            stroke: #B9E4D4 !important;
        }
        
        .card-icon.green {
            background: rgba(185, 228, 212, 0.15);
        }
        
        .card-icon.green i {
            color: #B9E4D4;
            stroke: #B9E4D4;
        }
        
        .card-title-group {
            flex: 1;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 800;
            color: #FEFEFE;
            margin-bottom: 4px;
        }
        
        .card-subtitle {
            font-size: 13px;
            color: rgba(254, 254, 254, 0.6);
        }
        
        /* Summary Items */
        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .summary-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .summary-icon {
            width: 44px;
            height: 44px;
            background: rgba(185, 228, 212, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .summary-icon i {
            width: 22px;
            height: 22px;
            color: #B9E4D4 !important;
            stroke: #B9E4D4 !important;
        }
        
        .summary-icon svg {
            color: #B9E4D4 !important;
            stroke: #B9E4D4 !important;
        }
        
        .summary-content {
            flex: 1;
        }
        
        .summary-label {
            font-size: 12px;
            color: rgba(254, 254, 254, 0.6);
            margin-bottom: 4px;
        }
        
        .summary-value {
            font-size: 16px;
            color: #FEFEFE;
            font-weight: 600;
        }
        
        .summary-price {
            font-size: 24px;
            font-weight: 800;
            color: #B9E4D4;
            white-space: nowrap;
        }
        
        /* Payment Container */
        #paymentBrick_container,
        #statusScreenBrick_container {
            margin-top: 20px;
        }
        
        /* Customização do MercadoPago Payment Brick */
        #paymentBrick_container .mp-checkout-bricks__payment-form,
        #paymentBrick_container form {
            background: #2a2a2a !important;
            border-radius: 16px !important;
            padding: 24px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Título do formulário */
        #paymentBrick_container h1,
        #paymentBrick_container .mp-checkout-bricks__heading {
            color: #FEFEFE !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            margin-bottom: 20px !important;
        }
        
        /* Inputs */
        #paymentBrick_container input,
        #paymentBrick_container select {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 8px !important;
            color: #FEFEFE !important;
        }
        
        #paymentBrick_container input:focus,
        #paymentBrick_container select:focus {
            border-color: #B9E4D4 !important;
            box-shadow: 0 0 0 3px rgba(185, 228, 212, 0.1) !important;
        }
        
        /* Labels */
        #paymentBrick_container label,
        #paymentBrick_container .mp-checkout-bricks__label {
            color: rgba(254, 254, 254, 0.8) !important;
            font-weight: 600 !important;
        }
        
        /* Botões de seleção (Radio buttons) */
        #paymentBrick_container .mp-checkout-bricks__selector {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 2px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 12px !important;
        }
        
        #paymentBrick_container .mp-checkout-bricks__selector:hover {
            border-color: rgba(185, 228, 212, 0.4) !important;
            background: rgba(0, 0, 0, 0.3) !important;
        }
        
        /* Botão Pagar */
        #paymentBrick_container button[type="submit"] {
            background: #007A63 !important;
            color: #FEFEFE !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 16px 32px !important;
            font-weight: 700 !important;
            font-size: 16px !important;
            transition: all 0.3s ease !important;
        }
        
        #paymentBrick_container button[type="submit"]:hover {
            background: #006654 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(0, 122, 99, 0.4) !important;
        }
        
        /* Textos do formulário */
        #paymentBrick_container p,
        #paymentBrick_container span,
        #paymentBrick_container div[role="text"] {
            color: rgba(254, 254, 254, 0.8) !important;
        }
        
        /* Ícones dentro do formulário */
        #paymentBrick_container svg {
            fill: #B9E4D4 !important;
        }
        
        #paymentBrick_container button[type="submit"] svg {
            fill: #FEFEFE !important;
        }
        
        /* Seletor de método de pagamento selecionado */
        #paymentBrick_container input[type="radio"]:checked + div,
        #paymentBrick_container .mp-checkout-bricks__selector[aria-checked="true"] {
            border-color: #B9E4D4 !important;
            background: rgba(185, 228, 212, 0.15) !important;
        }
        
        /* Placeholders */
        #paymentBrick_container input::placeholder {
            color: rgba(254, 254, 254, 0.4) !important;
        }
        
        /* Error messages */
        #paymentBrick_container .mp-checkout-bricks__error {
            color: #ff6b6b !important;
        }
        
        /* Links */
        #paymentBrick_container a {
            color: #B9E4D4 !important;
        }
        
        /* Customização do Status Screen Brick */
        #statusScreenBrick_container > div {
            background: #2a2a2a !important;
            border-radius: 16px !important;
            padding: 24px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        #statusScreenBrick_container h1,
        #statusScreenBrick_container h2,
        #statusScreenBrick_container h3 {
            color: #FEFEFE !important;
        }
        
        #statusScreenBrick_container p,
        #statusScreenBrick_container span {
            color: rgba(254, 254, 254, 0.8) !important;
        }
        
        /* Skip Payment Button */
        .skip-payment {
            text-align: center;
            margin-top: 20px;
        }
        
        .skip-payment-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            background: #B9E4D4;
            border: none;
            border-radius: 12px;
            color: #1a1a1a;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .skip-payment-btn:hover {
            background: #a0d9c7;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185, 228, 212, 0.4);
        }
        
        .skip-payment-btn i,
        .skip-payment-btn svg {
            width: 20px;
            height: 20px;
            color: #1a1a1a !important;
            stroke: #1a1a1a !important;
        }
        
        /* Payment Options */
        .payment-options {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .payment-option {
            flex: 1;
            padding: 12px 20px;
            background: rgba(185, 228, 212, 0.1);
            border: 2px solid rgba(185, 228, 212, 0.3);
            border-radius: 12px;
            color: #B9E4D4;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover {
            background: rgba(185, 228, 212, 0.2);
            border-color: #B9E4D4;
            transform: translateY(-2px);
        }
        
        .payment-info-text {
            font-size: 14px;
            color: rgba(254, 254, 254, 0.7);
            text-align: center;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        /* Success Screen */
        .success-screen {
            background: #007A63;
            border-radius: 24px;
            padding: 48px 32px;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 28px;
        }
        
        .success-title {
            font-size: 32px;
            font-weight: 800;
            color: #B9E4D4;
            margin-bottom: 16px;
        }
        
        .success-message {
            font-size: 16px;
            color: rgba(254, 254, 254, 0.8);
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .payment-id-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(185, 228, 212, 0.15);
            border-radius: 12px;
            font-size: 14px;
            color: #B9E4D4;
            font-weight: 600;
        }
        
        .payment-id-badge i,
        .payment-id-badge svg {
            width: 18px;
            height: 18px;
            color: #B9E4D4 !important;
            stroke: #B9E4D4 !important;
        }
        
        /* Força cor dos ícones globalmente */
        i, svg {
            color: inherit;
        }
        
        /* Remove qualquer stroke preto */
        svg[stroke="#000"],
        svg[stroke="#000000"],
        svg[stroke="black"] {
            stroke: #B9E4D4 !important;
        }
        
        /* Remove qualquer fill preto de ícones */
        svg[fill="#000"],
        svg[fill="#000000"],
        svg[fill="black"] {
            fill: #B9E4D4 !important;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .payment-grid {
                grid-template-columns: 1fr;
            }
            
            .payment-card {
                padding: 24px 20px;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 0;
            }
            
            .payment-header {
                padding: 24px 16px;
                margin-bottom: 24px;
            }
            
            .header-icon {
                width: 64px;
                height: 64px;
            }
            
            .header-icon i,
            .header-icon svg {
                width: 32px;
                height: 32px;
            }
            
            .header-title {
                font-size: 24px;
            }
            
            .header-subtitle {
                font-size: 14px;
            }
            
            .payment-grid {
                padding: 0 16px 24px;
            }
            
            .payment-card {
                padding: 20px 16px;
            }
            
            .card-header {
                flex-direction: row;
                text-align: left;
                margin-bottom: 20px;
            }
            
            .card-icon {
                width: 48px;
                height: 48px;
            }
            
            .card-icon i,
            .card-icon svg {
                width: 24px;
                height: 24px;
            }
            
            .card-title {
                font-size: 18px;
            }
            
            .card-subtitle {
                font-size: 12px;
            }
            
            .summary-row {
                padding: 12px;
            }
            
            .summary-icon {
                width: 40px;
                height: 40px;
            }
            
            .summary-icon i,
            .summary-icon svg {
                width: 20px;
                height: 20px;
            }
            
            .summary-label {
                font-size: 11px;
            }
            
            .summary-value {
                font-size: 15px;
            }
            
            .summary-price {
                font-size: 18px;
            }
            
            .payment-options {
                flex-direction: column;
            }
            
            .payment-info-text {
                font-size: 13px;
            }
            
            .success-screen {
                padding: 32px 20px;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
            }
            
            .success-title {
                font-size: 26px;
            }
            
            .success-message {
                font-size: 15px;
            }
        }
        
        /* iPhone e telas menores */
        @media (max-width: 428px) {
            .payment-header {
                padding: 20px 12px;
            }
            
            .header-icon {
                width: 56px;
                height: 56px;
            }
            
            .header-icon i,
            .header-icon svg {
                width: 28px;
                height: 28px;
            }
            
            .header-title {
                font-size: 22px;
            }
            
            .header-subtitle {
                font-size: 13px;
            }
            
            .payment-grid {
                padding: 0 12px 20px;
                gap: 16px;
            }
            
            .payment-card {
                padding: 16px 12px;
                border-radius: 20px;
            }
            
            .card-header {
                margin-bottom: 16px;
            }
            
            .card-icon {
                width: 44px;
                height: 44px;
            }
            
            .card-icon i,
            .card-icon svg {
                width: 22px;
                height: 22px;
            }
            
            .card-title {
                font-size: 17px;
            }
            
            .summary-row {
                padding: 10px;
                gap: 12px;
            }
            
            .summary-icon {
                width: 36px;
                height: 36px;
            }
            
            .summary-icon i,
            .summary-icon svg {
                width: 18px;
                height: 18px;
            }
            
            .summary-label {
                font-size: 10px;
            }
            
            .summary-value {
                font-size: 14px;
            }
            
            .summary-price {
                font-size: 16px;
            }
            
            .skip-payment-btn {
                padding: 12px 20px;
                font-size: 14px;
            }
            
            .payment-option {
                padding: 10px 16px;
                font-size: 13px;
            }
            
            /* MercadoPago Brick no mobile */
            #paymentBrick_container .mp-checkout-bricks__payment-form,
            #paymentBrick_container form {
                padding: 16px 12px !important;
            }
            
            #paymentBrick_container h1 {
                font-size: 16px !important;
                margin-bottom: 16px !important;
            }
            
            #paymentBrick_container input,
            #paymentBrick_container select {
                font-size: 14px !important;
                padding: 12px 14px !important;
            }
            
            #paymentBrick_container .mp-checkout-bricks__selector {
                padding: 10px !important;
            }
            
            #paymentBrick_container button[type="submit"] {
                padding: 14px 24px !important;
                font-size: 15px !important;
            }
        }
    </style>
</head>
<body>

<form action="agendamento_confirmado" method="post" style="display:none">
    <input type="hidden" name="id" value="<?=$id_conta;?>">
    <input type="hidden" name="enviar" value="Sim">
    <button id="btn_form" type="submit"></button>
</form>

<!-- Header -->
<div class="payment-header">
    <div class="header-icon">
        <i data-lucide="credit-card"></i>
    </div>
    <h1 class="header-title">Finalizar Pagamento</h1>
    <p class="header-subtitle">Revise os dados do seu agendamento e complete o pagamento</p>
</div>

<!-- Payment Grid -->
<div class="payment-grid">
    
    <!-- Left Column: Summary -->
    <div class="payment-card">
        <div class="card-header">
            <div class="card-icon">
                <i data-lucide="calendar-check"></i>
            </div>
            <div class="card-title-group">
                <h2 class="card-title">Resumo do Agendamento</h2>
                <p class="card-subtitle">Verifique os detalhes</p>
            </div>
        </div>
        
        <div class="summary-list">
            <!-- Service -->
            <div class="summary-row">
                <div class="summary-icon">
                    <i data-lucide="scissors"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Serviço selecionado</div>
                    <div class="summary-value"><?=$nome_servico;?></div>
                </div>
                <div class="summary-price">R$ <?=$valorF;?></div>
            </div>
            
            <!-- Professional -->
            <?php if($nome_funcionario){ ?>
            <div class="summary-row">
                <div class="summary-icon">
                    <i data-lucide="user"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Profissional</div>
                    <div class="summary-value"><?=$nome_funcionario;?></div>
                </div>
            </div>
            <?php } ?>
            
            <!-- Date & Time -->
            <div class="summary-row">
                <div class="summary-icon">
                    <i data-lucide="calendar"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Data</div>
                    <div class="summary-value"><?=$dataF;?></div>
                </div>
                <div class="summary-icon" style="margin-left: auto;">
                    <i data-lucide="clock"></i>
                </div>
                <div class="summary-content" style="max-width: 100px;">
                    <div class="summary-label">Horário</div>
                    <div class="summary-value"><?=$horaF;?></div>
                </div>
            </div>
            
            <!-- Duration -->
            <?php if($tempo_servico){ ?>
            <div class="summary-row">
                <div class="summary-icon">
                    <i data-lucide="timer"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Duração estimada</div>
                    <div class="summary-value"><?=$tempo_servico;?> minutos</div>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <!-- Skip Payment Option -->
        <?php if($porc_servico <= 0){ ?>
        <div class="skip-payment">
            <a onclick="clique()" href="<?php echo $url_sistema ?>pagamentos/pagamento_aprovado.php?id_agd=<?php echo $id_conta ?>" class="skip-payment-btn">
                <i data-lucide="check-circle"></i>
                <span id="clique_aqui">Confirmar e Pagar no Local</span>
            </a>
        </div>
        <?php } ?>
    </div>
    
    <!-- Right Column: Payment Method -->
    <div class="payment-card">
        <div class="card-header">
            <div class="card-icon green">
                <i data-lucide="credit-card"></i>
            </div>
            <div class="card-title-group">
                <h2 class="card-title">Método de Pagamento</h2>
                <p class="card-subtitle">Escolha como deseja pagar</p>
            </div>
        </div>
        
        <!-- Payment Options -->
        <?php if($porc_servico > 0 && $porc_servico != 100){ ?>
        <div class="payment-options">
            <a href="<?php echo $id_conta ?>/<?php echo $porc_servico ?>" class="payment-option">
                Pagar <?=$porc_servico;?>%
            </a>
            <a href="<?php echo $id_conta ?>/100" class="payment-option">
                Pagar 100%
            </a>
        </div>
        <?php } ?>
        
        <!-- Payment Info Text -->
        <?php if($porc_servico > 0){ ?>
            <?php if($porc_servico == 100){ ?>
            <p class="payment-info-text">Efetue o pagamento para confirmar seu agendamento!</p>
            <?php } else { ?>
            <p class="payment-info-text">Efetue o pagamento de pelo menos <?php echo $porc_servico ?>% do valor para confirmar seu agendamento!</p>
            <?php } ?>
        <?php } ?>
        
        <!-- MercadoPago Payment Brick -->
        <div id="paymentBrick_container"></div>
        
        <!-- Status Screen -->
        <div id="statusScreenBrick_container"></div>
    </div>
    
</div>

<!-- Success Message (Full Width) -->
<div class="success-screen" id="form-pago" style="display:none;">
    <img src="<?=$url_sistema;?>pagamentos/assets/check_ok.png" alt="Sucesso" class="success-icon">
    <h1 class="success-title">Obrigado!</h1>
    <p class="success-message"><?=$MSG_APOS_PAGAMENTO;?></p>
    
    <?php if($_GET["id"]){ ?>
    <div class="payment-id-badge">
        <i data-lucide="hash"></i>
        <span>Código do pagamento: <?php echo $_GET["id"]; ?></span>
    </div>
    <?php } ?>
</div>

<script>
    // Inicializa ícones Lucide quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Inicializa novamente após pequeno delay
    setTimeout(() => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }, 100);
    
    var payment_check;
    const mp = new MercadoPago('<?=$TOKEN_MERCADO_PAGO_PUBLICO;?>', {
        locale: 'pt-BR'
    });
    const bricksBuilder = mp.bricks();
    
    const renderPaymentBrick = async (bricksBuilder) => {
        const settings = {
            initialization: {
                amount: '<?=$valor;?>',
                payer: {
                    firstName: "<?=$nome;?>",
                    lastName: "<?=$sobrenome;?>",
                    email: "<?=$email;?>",
                    identification: {
                        type: '<?=(strlen($doc)>11? "CNPJ" : "CPF");?>',
                        number: '<?=$doc;?>',
                    },
                    address: {
                        zipCode: '',
                        federalUnit: '',
                        city: '',
                        neighborhood: '',
                        streetName: '',
                        streetNumber: '',
                        complement: '',
                    }
                },
            },
            customization: {
                visual: {
                    style: {
                        theme: "dark",
                    },
                },
                paymentMethods: {
                    <?php if($ATIVAR_CARTAO_CREDITO=="1"){?>creditCard: "all",<?php } ?>
                    <?php if($ATIVAR_CARTAO_DEBIDO=="1"){?>debitCard: "all",<?php } ?>
                    <?php if($ATIVAR_BOLETO=="1"){?>ticket: "all",<?php } ?>
                    <?php if($ATIVAR_PIX=="1"){?>bankTransfer: "all",<?php } ?>
                    maxInstallments: 12
                },
            },
            callbacks: {
                onReady: () => {
                    // Atualiza ícones após renderizar o brick
                    setTimeout(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }, 200);
                },
                onSubmit: ({ selectedPaymentMethod, formData }) => {
                    formData.external_reference = '<?=$ref;?>';
                    formData.description = '<?=$descricao;?>';
                    var id_conta = '<?=$id_conta;?>';

                    return new Promise((resolve, reject) => {
                        fetch("<?=$url_sistema;?>pagamentos/process_payment.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(formData),
                        })
                        .then((response) => response.json())
                        .then((response) => {
                            if(response.status==true){
                                window.location.href = "<?=$url_sistema;?>pagamentos/index.php?id="+response.id+'&id_conta='+id_conta;
                            }
                            if(response.status!=true){
                                alert(response.message);
                            }
                            resolve();
                        })
                        .catch((error) => {
                            reject();
                        });
                    });
                },
                onError: (error) => {
                    console.error(error);
                },
            },
        };
        window.paymentBrickController = await bricksBuilder.create(
            "payment",
            "paymentBrick_container",
            settings
        );
    };

    const renderStatusScreenBrick = async (bricksBuilder) => {
        const settings = {
            initialization: {
                paymentId: '<?=$_GET["id"];?>',
            },
            customization: {
                visual: {
                    hideStatusDetails: false,
                    hideTransactionDate: false,
                    style: {
                        theme: 'dark',
                    }
                },
                backUrls: {}
            },
            callbacks: {
                onReady: () => {
                    check("<?=$_GET["id"];?>", "<?=$_GET["id_conta"];?>");
                },
                onError: (error) => {
                },
            },
        };
        window.statusScreenBrickController = await bricksBuilder.create('statusScreen', 'statusScreenBrick_container', settings);
    };

    <?php if($_GET["id"]!=""){ ?>
        renderStatusScreenBrick(bricksBuilder);
    <?php } else { ?>
        <?php if($valor==""){ ?>
            alert("O valor do pagamento está vazio.");
        <?php } ?>
        renderPaymentBrick(bricksBuilder);
    <?php } ?>
    
    var redi = "<?=$URL_REDIRECIONAR;?>";
    function check(id, id_conta) {
        var settings = {
            "url": "<?=$url_sistema;?>pagamentos/process_payment.php?acc=check&id=" + id + "&id_conta=" + id_conta,
            "method": "GET",
            "timeout": 0
        };
        $.ajax(settings).done(function(response) {
            try {
                if (response.status == "pago") {
                    $(".payment-grid").slideUp("fast");
                    $("#statusScreenBrick_container").slideUp("fast");
                    $("#form-pago").slideDown("fast");
                    
                    // Atualiza ícones Lucide
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    
                    if (redi.trim() == "Sim") {
                        setTimeout(() => {
                            window.location = "../sistema/painel_cliente";
                        }, 6000);
                    }
                } else {
                    setTimeout(() => {
                        check(id)
                    }, 3000);
                }
            } catch (error) {
                alert("Erro ao localizar o pagamento, contacte com o suporte");
            }
        });
    }
    
    function clique(){       
        document.getElementById("clique_aqui").style.display = 'none';
    }
</script>

</body>
</html>
