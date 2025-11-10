<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag_inicial = 'home';

$id_usuario = $_SESSION['id'];

$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	$nome_usuario = $res[0]['nome'];
	$email_usuario = $res[0]['email'];
	$cpf_usuario = $res[0]['cpf'];
	$senha_usuario = $res[0]['senha'];
	$nivel_usuario = $res[0]['nivel'];
	$telefone_usuario = $res[0]['telefone'];
	$endereco_usuario = $res[0]['endereco'];
	$foto_usuario = $res[0]['foto'];
	$atendimento = $res[0]['atendimento'];
	$intervalo_horarios = $res[0]['intervalo'];
}

if(@$_SESSION['nivel'] != 'Administrador'){
	require_once("verificar-permissoes.php");
}

if(@$_GET['pag'] == ""){
	$pag = $pag_inicial;
}else{
	$pag = $_GET['pag'];
}


$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_mes = $ano_atual."-".$mes_atual."-01";
$data_ano = $ano_atual."-01-01";


$partesInicial = explode('-', $data_atual);
$dataDiaInicial = $partesInicial[2];
$dataMesInicial = $partesInicial[1];



?>

<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<link rel="icon" type="image/png" href="../img/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>



	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />



	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link href="css/modal-relatorios.css" rel='stylesheet' type='text/css' />

	<!-- font-awesome icons CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- //font-awesome icons CSS-->

	<!-- side nav css file -->
	<link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
	<!-- //side nav css file -->

	<link rel="stylesheet" href="css/monthly.css">

	<!-- js-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr.custom.js"></script>

	<!--webfonts-->
	<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
	<!--//webfonts--> 

	<!-- chart -->
	<script src="js/Chart.js"></script>
	<!-- //chart -->

	<!-- Metis Menu -->
	<script src="js/metisMenu.min.js"></script>
	<script src="js/custom.js"></script>
	<link href="css/custom.css" rel="stylesheet">
	<!--//Metis Menu -->
	<style>
		#chartdiv {
			width: 100%;
			height: 295px;
		}

		/* Scrollbar Nativo Moderno - Substitui nicescroll */
		::-webkit-scrollbar {
			width: 8px;
			height: 8px;
		}

		::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 10px;
		}

		::-webkit-scrollbar-thumb {
			background: #007A63;
			border-radius: 10px;
			transition: all 0.3s ease;
		}

		::-webkit-scrollbar-thumb:hover {
			background: #006854;
		}

		/* Firefox */
		* {
			scrollbar-width: thin;
			scrollbar-color: #007A63 #f1f1f1;
		}

		/* Smooth scroll para toda a página */
		html {
			scroll-behavior: smooth;
		}

		/* Sidebar Moderna - Estados Expandido/Minimizado */
		.cbp-spmenu-left {
			width: 240px;
			transition: all 0.3s ease;
		}

		.sidebar-left {
			background: #2a2a2a !important;
			border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
			box-shadow: none !important;
			width: 240px;
			transition: all 0.3s ease;
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			z-index: 1001;
		}

		.sidebar-collapsed .sidebar-left {
			width: 80px;
		}

		.sidebar-collapsed .cbp-spmenu-left {
			width: 80px;
		}

		.sidebar-left .navbar-brand {
			background: transparent;
			border-bottom: none;
			margin-bottom: 25px;
			padding: 24px 20px 20px 20px;
			display: flex;
			align-items: center;
			justify-content: center;
			height: auto;
			overflow: hidden;
		}

	/* Botão Hamburger no Sidebar */


	#sidebar-toggle {
		position: absolute;
		top: 75px;
		right: 16px;
		background: rgba(255, 255, 255, 0.1);
		border: none;
		width: 28px;
		height: 28px;
		border-radius: 6px;
		color: rgba(255, 255, 255, 0.7);
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		gap: 3px;
		padding: 6px;
		transition: all 0.3s ease;
		z-index: 200;
	}

	#sidebar-toggle:hover {
		background: rgba(255, 255, 255, 0.15);
	}

	#sidebar-toggle span {
		width: 16px;
		height: 1.5px;
		background: rgba(255, 255, 255, 0.7);
		border-radius: 2px;
		transition: all 0.3s ease;
	}

		.sidebar-logo-sistema {
			max-width: 140px;
			max-height: 60px;
			width: auto;
			height: auto;
			object-fit: contain;
			transition: all 0.3s ease;
			filter: brightness(0) invert(1);
			opacity: 1;
		}

		.sidebar-collapsed .sidebar-logo-sistema {
			max-width: 40px;
			max-height: 40px;
		}

		.sidebar-collapsed .navbar-brand {
			padding: 24px 10px 20px 10px;
		}

		.sidebar-left .navbar-brand:hover .sidebar-logo-sistema {
			filter: brightness(0) invert(1) opacity(0.85);
			transform: translateY(-2px);
		}

		span.dashboard_text {
			display: none !important;
		}

		.navbar-header {
			position: relative;
		}

		.navbar-brand {
			position: relative;
			z-index: 10;
		}

		.sidebar-menu {
			padding: 0 16px 20px 16px;
			transition: all 0.3s ease;
		}

		.sidebar-collapsed .sidebar-menu {
			padding: 0 8px 20px 8px;
		}

		.sidebar-menu li.header {
			padding: 20px 14px 10px 14px;
			font-size: 10px;
			color: rgba(255, 255, 255, 0.35);
			background: transparent !important;
			letter-spacing: 1.5px;
			font-weight: 700;
			text-transform: uppercase;
			margin-top: 0;
			transition: opacity 0.3s ease;
			border-top: 1px solid rgba(255, 255, 255, 0.06);
		}

		.sidebar-menu li.header:first-of-type {
			border-top: none;
			padding-top: 8px;
		}

		.sidebar-collapsed .sidebar-menu li.header {
			opacity: 0;
			height: 0;
			padding: 0;
			margin: 0;
			overflow: hidden;
		}

		.sidebar-menu > li > a {
			padding: 13px 16px;
			margin: 3px 0;
			border-radius: 10px;
			border-left: none !important;
			transition: all 0.25s ease;
			color: rgba(255, 255, 255, 0.75) !important;
			font-size: 14.5px;
			position: relative;
			background: transparent !important;
			font-weight: 500;
			display: flex;
			align-items: center;
			white-space: nowrap;
		}

		.sidebar-collapsed .sidebar-menu > li > a {
			justify-content: center;
		}

		.sidebar-menu > li > a i.fa {
			width: 22px;
			margin-right: 14px;
			font-size: 17px;
			text-align: center;
			opacity: 0.95;
			transition: all 0.25s ease;
			flex-shrink: 0;
		}

		.sidebar-collapsed .sidebar-menu > li > a i.fa {
			margin-right: 0;
		}

		.sidebar-menu > li > a span {
			transition: opacity 0.3s ease, width 0.3s ease;
			opacity: 1;
		}

		.sidebar-collapsed .sidebar-menu > li > a span {
			opacity: 0;
			width: 0;
			overflow: hidden;
		}

		.sidebar-menu > li > a:hover {
			background: rgba(255, 255, 255, 0.08) !important;
			color: #ffffff !important;
			transform: translateX(2px);
		}

		.sidebar-menu > li > a:hover i.fa {
			color: #00d896;
			transform: scale(1.08);
		}

		.sidebar-menu > li.active > a,
		.sidebar-menu > li.treeview.active > a,
		.sidebar-menu > li.has-active-child > a,
		.sidebar-menu > li:has(.treeview-menu .active) > a {
			background: rgba(0, 122, 99, 0.20) !important;
			color: #ffffff !important;
			box-shadow: inset 0 0 0 1px rgba(0, 209, 150, 0.3);
			font-weight: 600;
		}

		.sidebar-menu > li.active > a i.fa,
		.sidebar-menu > li.treeview.active > a i.fa,
		.sidebar-menu > li.has-active-child > a i.fa,
		.sidebar-menu > li:has(.treeview-menu .active) > a i.fa {
			color: #00d896;
			opacity: 1;
		}

		.sidebar-menu > li.has-active-child > a .fa-angle-left,
		.sidebar-menu > li:has(.treeview-menu .active) > a .fa-angle-left {
			transform: rotate(-90deg);
			opacity: 0.8;
			color: #00d896;
		}

		/* Garantir que submenu fique visível quando tem filho ativo */
		.sidebar-menu > li.treeview:has(.treeview-menu .active) > .treeview-menu {
			display: block !important;
		}

		.sidebar-menu > li > a .fa-angle-left {
			transition: transform 0.25s ease;
			opacity: 0.5;
			font-size: 14px;
			margin-left: auto;
		}

		.sidebar-collapsed .sidebar-menu > li.treeview > a::after {
			content: '';
			position: absolute;
			right: 8px;
			top: 50%;
			transform: translateY(-50%);
			width: 4px;
			height: 4px;
			background: rgba(255, 255, 255, 0.5);
			border-radius: 50%;
		}

		.sidebar-collapsed .sidebar-menu > li > a .fa-angle-left {
			display: none;
		}

		.sidebar-menu > li.active > a .fa-angle-left {
			transform: rotate(-90deg);
			opacity: 0.8;
			color: #00d896;
		}

		.sidebar-collapsed .sidebar-menu > li.active > a .fa-angle-left {
			opacity: 0;
		}

		.sidebar-menu > li > .treeview-menu {
			background: transparent !important;
			margin: 4px 0 8px 0 !important;
			padding: 0;
			border-radius: 0;
			border-left: none;
			margin-left: 0 !important;
			display: none;
		}

		.sidebar-menu > li.has-active-child > .treeview-menu,
		.sidebar-menu > li:has(.treeview-menu .active) > .treeview-menu {
			display: block !important;
		}

		.sidebar-menu .treeview-menu > li {
			margin: 0;
		}

		.sidebar-menu .treeview-menu > li > a {
			padding: 10px 16px 10px 52px;
			color: rgba(255, 255, 255, 0.60) !important;
			font-size: 13.5px;
			transition: all 0.25s ease;
			border-radius: 8px;
			margin: 2px 0;
			background: transparent !important;
			font-weight: 400;
			white-space: nowrap;
			position: relative;
		}

		.sidebar-menu .treeview-menu > li > a::before {
			content: '';
			position: absolute;
			left: 38px;
			width: 4px;
			height: 4px;
			background: rgba(255, 255, 255, 0.3);
			border-radius: 50%;
			top: 50%;
			transform: translateY(-50%);
			transition: all 0.25s ease;
		}

		.sidebar-menu .treeview-menu > li > a:hover::before {
			background: #00d896;
			transform: translateY(-50%) scale(1.3);
		}

		/* Submenu Flyout quando minimizado */
		.sidebar-collapsed .sidebar-menu > li.treeview {
			position: relative;
		}

		.sidebar-collapsed .sidebar-menu .treeview-menu {
			display: none !important;
			position: fixed;
			left: 80px;
			background: #2a2a2a !important;
			min-width: 220px;
			border-radius: 0 12px 12px 0;
			box-shadow: 4px 0 20px rgba(0, 0, 0, 0.4);
			padding: 12px 0;
			z-index: 1002;
			border: 1px solid rgba(255, 255, 255, 0.1);
			border-left: 3px solid #007A63;
		}

		.sidebar-collapsed .sidebar-menu > li.treeview:hover .treeview-menu,
		.sidebar-collapsed .sidebar-menu > li.treeview.flyout-open .treeview-menu {
			display: block !important;
		}

		.sidebar-collapsed .sidebar-menu .treeview-menu > li > a {
			padding: 10px 20px;
			margin: 2px 8px;
			opacity: 1;
			width: auto;
		}

		.sidebar-collapsed .sidebar-menu .treeview-menu > li > a:hover {
			padding-left: 24px;
			background: rgba(0, 122, 99, 0.12) !important;
		}

		.sidebar-collapsed .sidebar-menu .treeview-menu > li > a i {
			opacity: 1 !important;
			margin-right: 10px !important;
		}

		.sidebar-menu .treeview-menu > li > a i {
			width: 18px;
			margin-right: 10px;
			font-size: 13px;
			text-align: center;
			opacity: 0.7;
			transition: all 0.25s ease;
		}

		.sidebar-menu .treeview-menu > li.active > a i {
			opacity: 1;
			color: #00d896;
		}

		.sidebar-menu .treeview-menu > li > a:hover {
			background: rgba(255, 255, 255, 0.06) !important;
			color: #ffffff !important;
			transform: translateX(2px);
		}

		.sidebar-menu .treeview-menu > li > a:hover i {
			color: #00d896;
		}

		.sidebar-menu .treeview-menu > li.active > a {
			background: rgba(0, 122, 99, 0.15) !important;
			color: #ffffff !important;
			font-weight: 500;
		}

		.sidebar-menu .treeview-menu > li.active > a::before {
			background: #00d896;
			transform: translateY(-50%) scale(1.5);
		}

		.sidebar-menu .treeview-menu > li.active > a i {
			color: #00d896;
		}

		aside.sidebar-left {
			scrollbar-width: thin;
			scrollbar-color: rgba(0, 122, 99, 0.2) transparent;
		}

		aside.sidebar-left::-webkit-scrollbar {
			width: 5px;
		}

		aside.sidebar-left::-webkit-scrollbar-track {
			background: transparent;
		}

		aside.sidebar-left::-webkit-scrollbar-thumb {
			background: rgba(0, 122, 99, 0.2);
			border-radius: 10px;
		}

		aside.sidebar-left::-webkit-scrollbar-thumb:hover {
			background: rgba(0, 122, 99, 0.3);
		}

		/* Header Moderno */
		.sticky-header {
			background: #fff !important;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
    border-bottom: 1px solid #f0f0f0;
    /* width: 500px; */
		height: 71px;
		}

	.nofitications-dropdown {
		display: flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
	}

	/* Ajustar posição quando sidebar minimizada */
	.sidebar-collapsed .nofitications-dropdown {
		margin-left: -160px;
	}

	.nofitications-dropdown > li > a {
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 10px;
		background: #f8f9fa;
		transition: all 0.3s ease;
		position: relative;
	}

		.nofitications-dropdown > li > a:hover {
			background: rgba(0, 122, 99, 0.1);
		}

		.nofitications-dropdown > li > a i {
			font-size: 18px;
			color: #1a1a1a;
		}

		.nofitications-dropdown .badge {
			position: absolute;
			top: -4px;
			right: -4px;
			min-width: 20px;
			height: 20px;
			border-radius: 10px;
			font-size: 11px;
			font-weight: 700;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 0 6px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.profile_details_drop > a {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 6px 12px;
			border-radius: 12px;
			transition: all 0.3s ease;
		}

		.profile_details_drop > a:hover {
			background: #f8f9fa;
		}

		.prfil-img img {
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid #e8f5f3;
		}

		.user-name p {
			font-size: 14px;
			font-weight: 600;
			color: #1a1a1a;
			margin: 0;
		}

		.user-name span {
			font-size: 12px;
			color: #6c757d;
			font-weight: 400;
		}

		/* Notificações Dropdown Moderno */
		.nofitications-dropdown .dropdown-menu {
			border: none;
			border-radius: 12px;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
			padding: 0;
			min-width: 320px;
			margin-top: 8px;
		}

		.notification_header {
			background: linear-gradient(135deg, #007A63 0%, #00a078 100%);
			color: #fff;
			padding: 18px 20px;
			border-radius: 16px 16px 0 0;
			box-shadow: 0 2px 8px rgba(0, 122, 99, 0.15);
		}

		.notification_header h3 {
			font-size: 15px;
			font-weight: 600;
			margin: 0;
			color: #fff;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.notification_header h3 i {
			font-size: 16px;
		}

		.notification_bottom {
			padding: 14px 20px;
			border-radius: 0 0 16px 16px;
			text-align: center;
			background: #f8f9fa;
			border-top: 1px solid #e8e8e8;
		}

		.notification_bottom a {
			color: #007A63;
			font-size: 13px;
			font-weight: 600;
			text-decoration: none;
			transition: all 0.3s ease;
			display: inline-flex;
			align-items: center;
			gap: 6px;
		}

		.notification_bottom a:hover {
			color: #006854;
			text-decoration: none;
		}

		.notification_bottom a i {
			transition: transform 0.3s ease;
		}

		.notification_bottom a:hover i {
			transform: translateX(2px);
		}

		/* Botão de Notificações */
		.notifications-bell {
			display: flex;
			align-items: center;
			margin-right: 12px;
		}

		.bell-icon {
			width: 36px;
			height: 36px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 8px;
			background: rgba(0, 122, 99, 0.08);
			transition: all 0.25s ease;
			position: relative;
			color: #007A63;
			text-decoration: none;
			border: 1px solid transparent;
		}

		.bell-icon:hover {
			background: rgba(0, 122, 99, 0.15);
			color: #007A63;
			text-decoration: none;
			transform: scale(1.05);
			border-color: rgba(0, 122, 99, 0.2);
		}

		.bell-icon:focus {
			outline: none;
			box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
		}

		.bell-icon i {
			font-size: 16px;
		}

		.badge-notification {
			position: absolute;
			top: -6px;
			right: -6px;
			min-width: 18px;
			height: 18px;
			border-radius: 9px;
			background: #ef5350;
			color: #fff;
			font-size: 10px;
			font-weight: 700;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 0 5px;
			box-shadow: 0 2px 6px rgba(239, 83, 80, 0.4);
			border: 2px solid #fff;
			animation: pulse-badge 2s infinite;
		}

		@keyframes pulse-badge {
			0%, 100% { transform: scale(1); }
			50% { transform: scale(1.15); }
		}

		/* Dropdown de Notificações */
		.dropdown-notifications {
			min-width: 480px;
			max-width: 480px;
			border: none;
			border-radius: 16px;
			box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
			padding: 0;
			margin-top: 8px;
			max-height: 650px;
			overflow: hidden;
		}

		.notifications-content {
			max-height: 520px;
			overflow-y: auto;
			padding: 0;
		}

		.notifications-content::-webkit-scrollbar {
			width: 6px;
		}

		.notifications-content::-webkit-scrollbar-track {
			background: #f8f9fa;
		}

		.notifications-content::-webkit-scrollbar-thumb {
			background: #007A63;
			border-radius: 10px;
		}

		/* Seção de Notificação */
		.notification-section {
			padding: 18px 20px;
			border-bottom: 1px solid #e8e8e8;
		}

		.notification-section:first-child {
			padding-top: 20px;
		}

		.notification-section:last-child {
			border-bottom: none;
			padding-bottom: 20px;
		}

		.notification-section-header {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 16px;
			padding-bottom: 10px;
			border-bottom: 2px solid #f0f0f0;
		}

		.notification-section-header i {
			font-size: 18px;
			width: 24px;
			text-align: center;
			flex-shrink: 0;
		}

		.notification-section-header span {
			font-size: 13px;
			font-weight: 600;
			color: #1a1a1a;
			flex: 1;
		}

		.notification_desc {
			padding: 10px 12px;
			border-bottom: 1px solid #f8f9fa;
			transition: all 0.2s ease;
			border-radius: 8px;
			margin-bottom: 6px;
		}

		.notification_desc:last-of-type {
			margin-bottom: 0;
		}

		.notification_desc:hover {
			background: #f8f9fa;
			transform: translateX(2px);
		}

		.notification_desc p {
			margin: 0;
			font-size: 13px;
			color: #1a1a1a;
			line-height: 1.5;
			display: flex;
			align-items: center;
		}

		.notification_desc p i {
			flex-shrink: 0;
		}

		.notification_desc p b {
			color: #007A63;
			font-weight: 600;
		}

		.notification-section-footer {
			margin-top: 14px;
			padding-top: 12px;
			border-top: 1px solid #f0f0f0;
			text-align: center;
		}

		.notification-section-footer a {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 6px;
			font-size: 12px;
			color: #007A63;
			font-weight: 600;
			text-decoration: none;
			padding: 8px 16px;
			border-radius: 8px;
			background: rgba(0, 122, 99, 0.08);
			transition: all 0.3s ease;
			width: 100%;
		}

		.notification-section-footer a:hover {
			background: rgba(0, 122, 99, 0.15);
			text-decoration: none;
			transform: translateY(-1px);
			box-shadow: 0 2px 6px rgba(0, 122, 99, 0.15);
		}

		.notification-section-footer a i {
			font-size: 11px;
		}

		/* Estado vazio de notificações */
		.notifications-content .empty-state {
			text-align: center;
			padding: 48px 24px;
			color: #6c757d;
		}

		.notifications-content .empty-state i {
			font-size: 56px;
			opacity: 0.25;
			margin-bottom: 16px;
			display: block;
			color: #007A63;
		}

		.notifications-content .empty-state p:first-of-type {
			font-size: 15px;
			font-weight: 600;
			margin: 0 0 6px 0;
			color: #1a1a1a;
		}

		.notifications-content .empty-state p:last-of-type {
			font-size: 13px;
			margin: 0;
			opacity: 0.7;
		}

		/* Header Right Layout */
		.header-right {
			display: flex;
			align-items: center;
			gap: 8px;
		}

		/* Mobile */
		#mobile-menu-toggle {
			display: none;
		}

		/* Mobile Overlay */
		.mobile-overlay {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0, 0, 0, 0.5);
			z-index: 1000;
			opacity: 0;
			transition: opacity 0.3s ease;
		}

		.sidebar-open .mobile-overlay {
			display: block;
			opacity: 1;
		}

		@media (max-width: 768px) {
			.sidebar-menu > li > a {
				padding: 10px 12px;
			}

			#mobile-menu-toggle {
				display: flex;
				background: transparent;
				border: none;
				width: 40px;
				height: 40px;
				border-radius: 10px;
				color: #1a1a1a;
				cursor: pointer;
				align-items: center;
				justify-content: center;
				transition: all 0.3s ease;
				margin-right: 16px;
			}

			#mobile-menu-toggle:hover {
				background: #f8f9fa;
			}

			/* Notificações no Mobile */
			.notifications-bell {
				margin-right: 6px;
			}

			.bell-icon {
				width: 34px;
				height: 34px;
			}

			.bell-icon i {
				font-size: 15px;
			}

			.badge-notification {
				top: -5px;
				right: -5px;
				min-width: 16px;
				height: 16px;
				font-size: 9px;
			}

			.dropdown-notifications {
				min-width: 95vw;
				max-width: 95vw;
				left: 2.5vw !important;
				right: auto !important;
				margin-top: 8px;
			}

			.notification-section {
				padding: 12px 16px;
			}

			.user-name {
				display: none !important;
			}

			#sidebar-toggle {
				display: none !important;
			}

			.cbp-spmenu-left {
				width: 280px !important;
			}

			.sidebar-left {
				position: fixed !important;
				left: 0 !important;
				top: 0 !important;
				z-index: 1001 !important;
				width: 280px !important;
				height: 100vh !important;
				transform: translateX(-100%);
				transition: transform 0.3s ease !important;
			}

			.sidebar-open .sidebar-left {
				transform: translateX(0) !important;
			}

			.cbp-spmenu-push div#page-wrapper {
				margin-left: 0 !important;
				margin-top: 70px !important;
			}

			.sticky-header {
				position: fixed !important;
				top: 0 !important;
				left: 0 !important;
				right: 0 !important;
				width: 100% !important;
				z-index: 999 !important;
			}

			.sidebar-open body {
				overflow: hidden;
			}

			.sidebar-collapsed .sidebar-left {
				width: 280px !important;
			}

			.sidebar-collapsed .cbp-spmenu-push div#page-wrapper {
				margin-left: 0 !important;
			}

			.sidebar-collapsed .sticky-header {
				left: 0 !important;
			}
		}

		/* Desktop - Ajustar conteúdo quando sidebar minimizado */
		.sidebar-collapsed #page-wrapper {
			margin-left: 80px !important;
		}

		.sidebar-collapsed .sticky-header {
			left: 80px !important;
		}

		/* Footer Moderno */
		.footer-modern {
			background: #fff;
			padding: 20px 32px;
			width: 100%;
			border-top: 1px solid #e8e8e8;
			margin-top: 40px;
		}

		.footer-content {
			display: flex;
			align-items: center;
			justify-content: space-between;
			max-width: 1400px;
			margin: 0 auto;
		}

		.footer-left p,
		.footer-right p {
			margin: 0;
			font-size: 13px;
			color: #6c757d;
		}

		.footer-right a {
			color: #007A63;
			text-decoration: none;
			font-weight: 600;
			transition: all 0.3s ease;
		}

		.footer-right a:hover {
			color: #00d896;
			text-decoration: none;
		}

		.footer-right i.fa-heart {
			animation: heartbeat 1.5s ease-in-out infinite;
			display: inline-block;
		}

		@keyframes heartbeat {
			0%, 100% { transform: scale(1); }
			10%, 30% { transform: scale(0.9); }
			20%, 40% { transform: scale(1.1); }
		}

		@media (max-width: 768px) {
			.footer-content {
				flex-direction: column;
				gap: 12px;
				text-align: center;
			}
		}

		@media (max-width: 768px) {
			.sidebar-menu > li > a {
				padding: 10px 12px;
			}
		}
	</style>
	<!--pie-chart --><!-- index page sales reviews visitors pie chart -->
	<script src="js/pie-chart.js" type="text/javascript"></script>
	<script type="text/javascript">

		$(document).ready(function () {
			$('#demo-pie-1').pieChart({
				barColor: '#2dde98',
				trackColor: '#eee',
				lineCap: 'round',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-2').pieChart({
				barColor: '#8e43e7',
				trackColor: '#eee',
				lineCap: 'butt',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

			$('#demo-pie-3').pieChart({
				barColor: '#e32424',
				trackColor: '#eee',
				lineCap: 'square',
				lineWidth: 8,
				onStep: function (from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});


		});

	</script>
	<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->


	<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
 	<script type="text/javascript" src="DataTables/datatables.min.js"></script>

 	


	
</head> 
<body class="cbp-spmenu-push">
	<!-- Mobile Overlay -->
	<div class="mobile-overlay" onclick="toggleMobileSidebar()"></div>

	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left" style="overflow: scroll; height:100%; scrollbar-width: thin;">
				<nav class="navbar navbar-inverse" >
					<div class="navbar-header">
						<!-- Botão Hamburger -->
						<button id="sidebar-toggle" onclick="toggleSidebar()">
							<span></span>
							<span></span>
							<span></span>
						</button>

						<h1><a class="navbar-brand" href="index.php">
							<img src="../img/logo.png" alt="<?php echo $nome_sistema ?>" class="sidebar-logo-sistema">
						</a></h1>
					</div>
					<div  id="bs-example-navbar-collapse-1">
						<ul class="sidebar-menu">
							<li class="header">NAVEGAÇÃO</li>

							<li class="treeview <?php echo @$home ?>">
								<a href="index.php">
									<i class="fa fa-home"></i> <span>Dashboard</span>
								</a>
							</li>

							<li class="treeview <?php echo @$menu_vendas ?>">
								<a href="#">
									<i class="fa fa-shopping-cart"></i>
									<span>Vendas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$comanda ?>"><a href="comanda"><i class="fa fa-file-text"></i>Nova Comanda</a></li>
									<li class="<?php echo @$vendas ?>"><a href="vendas"><i class="fa fa-shopping-bag"></i>Histórico de Vendas</a></li>
									<li class="<?php echo @$planos ?>"><a href="planos"><i class="fa fa-star"></i>Planos / Assinaturas</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo @$menu_agendamentos ?>">
								<a href="#">
									<i class="fa fa-calendar"></i>
									<span>Agendamentos</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$agendamentos ?>"><a href="agendamentos"><i class="fa fa-calendar-plus-o"></i>Agendamentos</a></li>
									<li class="<?php echo @$servicos_agenda ?>"><a href="servicos_agenda"><i class="fa fa-list-ul"></i>Serviços Agendados</a></li>
									<li class="<?php echo @$calendario ?>"><a href="calendario"><i class="fa fa-calendar-o"></i>Calendário</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo @$menu_pessoas ?>">
								<a href="#">
									<i class="fa fa-users"></i>
									<span>Pessoas</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$usuarios ?>"><a href="usuarios"><i class="fa fa-user-plus"></i>Usuários</a></li>
									<li class="<?php echo @$funcionarios ?>"><a href="funcionarios"><i class="fa fa-user-md"></i>Funcionários</a></li>
									<li class="<?php echo @$clientes ?>"><a href="clientes"><i class="fa fa-user"></i>Clientes</a></li>
									<li class="<?php echo @$clientes_retorno ?>"><a href="clientes_retorno"><i class="fa fa-history"></i>Clientes Retorno</a></li>
									<li class="<?php echo @$fornecedores ?>"><a href="fornecedores"><i class="fa fa-truck"></i>Fornecedores</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo @$menu_financeiro ?>">
								<a href="#">
									<i class="fa fa-usd"></i>
									<span>Financeiro</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$receber ?>"><a href="receber"><i class="fa fa-money"></i>Contas à Receber</a></li>
									<li class="<?php echo @$receber_vencidas ?>"><a href="receber_vencidas"><i class="fa fa-calendar-times-o"></i>Recebimentos Vencidos</a></li>
									<li class="<?php echo @$pagar ?>"><a href="pagar"><i class="fa fa-credit-card-alt"></i>Contas à Pagar</a></li>
									<li class="<?php echo @$compras ?>"><a href="compras"><i class="fa fa-shopping-bag"></i>Compras</a></li>
									<li class="<?php echo @$comissoes ?>"><a href="comissoes"><i class="fa fa-percent"></i>Comissões</a></li>
									<li class="<?php echo @$caixas ?>"><a href="caixas"><i class="fa fa-server"></i>Caixas</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo @$menu_produtos ?>">
								<a href="#">
									<i class="fa fa-cube"></i>
									<span>Produtos</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$produtos ?>"><a href="produtos"><i class="fa fa-cubes"></i>Produtos</a></li>
									<li class="<?php echo @$estoque ?>"><a href="estoque"><i class="fa fa-exclamation-triangle"></i>Estoque Baixo</a></li>
									<li class="<?php echo @$entradas ?>"><a href="entradas"><i class="fa fa-arrow-up"></i>Entradas</a></li>
									<li class="<?php echo @$saidas ?>"><a href="saidas"><i class="fa fa-arrow-down"></i>Saídas</a></li>
									<li class="<?php echo @$cat_produtos ?>"><a href="cat_produtos"><i class="fa fa-tag"></i>Categorias</a></li>
								</ul>
							</li>

							<?php if(@$atendimento == 'Sim'){ ?>
							<li class="header">MEU ESPAÇO</li>

							<li class="treeview <?php echo @$meu_ponto ?>">
								<a href="meu_ponto">
									<i class="fa fa-clock-o"></i> <span>Meu Ponto</span>
								</a>
							</li>

							<li class="treeview">
								<a href="agenda">
									<i class="fa fa-calendar-check-o"></i> <span>Minha Agenda</span>
								</a>
							</li>

							<li class="treeview">
								<a href="meus_servicos">
									<i class="fa fa-scissors"></i> <span>Meus Serviços</span>
								</a>
							</li>

							<li class="treeview">
								<a href="minhas_comissoes">
									<i class="fa fa-percent"></i> <span>Minhas Comissões</span>
								</a>
							</li>

							<li class="treeview">
								<a href="#">
									<i class="fa fa-calendar-o"></i>
									<span>Meus Horários</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li><a href="dias"><i class="fa fa-calendar"></i>Horários / Dias</a></li>
									<li><a href="servicos_func"><i class="fa fa-tasks"></i>Lançar Serviços</a></li>
									<li><a href="dias_bloqueio_func"><i class="fa fa-lock"></i>Bloqueio de Dias</a></li>
								</ul>
							</li>
							<?php } ?>

							<li class="header">CONTROLE</li>

							<li class="treeview <?php echo @$menu_ponto ?>">
								<a href="#">
									<i class="fa fa-clock-o"></i>
									<span>Ponto</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$registro_ponto ?>"><a href="registro_ponto"><i class="fa fa-list"></i>Registro de Ponto</a></li>
									<li class="<?php echo @$configuracoes_ponto ?>"><a href="configuracoes_ponto"><i class="fa fa-cog"></i>Configurações</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo @$menu_relatorio ?>">
								<a href="#">
									<i class="fa fa-bar-chart"></i>
									<span>Relatórios</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$rel_produtos ?>"><a href="rel/rel_produtos_class.php" target="_blank"><i class="fa fa-cube"></i>Produtos</a></li>
									<li class="<?php echo @$rel_entradas ?>"><a href="#" data-toggle="modal" data-target="#RelEntradas"><i class="fa fa-arrow-circle-up"></i>Entradas / Ganhos</a></li>
									<li class="<?php echo @$rel_saidas ?>"><a href="#" data-toggle="modal" data-target="#RelSaidas"><i class="fa fa-arrow-circle-down"></i>Saídas / Despesas</a></li>
									<li class="<?php echo @$rel_comissoes ?>"><a href="#" data-toggle="modal" data-target="#RelComissoes"><i class="fa fa-percent"></i>Comissões</a></li>
									<li class="<?php echo @$rel_contas ?>"><a href="#" data-toggle="modal" data-target="#RelCon"><i class="fa fa-bar-chart"></i>Contas</a></li>
									<li class="<?php echo @$rel_servicos ?>"><a href="#" data-toggle="modal" data-target="#RelServicos"><i class="fa fa-scissors"></i>Serviços</a></li>
									<li class="<?php echo @$rel_aniv ?>"><a href="#" data-toggle="modal" data-target="#RelAniv"><i class="fa fa-birthday-cake"></i>Aniversariantes</a></li>
									<li class="<?php echo @$rel_lucro ?>"><a href="#" data-toggle="modal" data-target="#RelLucro"><i class="fa fa-line-chart"></i>Demonstrativo Lucro</a></li>
									<li class="<?php echo @$rel_ina ?>"><a target="_blank" href="rel/sintetico_inadimplentes_class.php"><i class="fa fa-exclamation-circle"></i>Inadimplentes</a></li>
								</ul>
							</li>

							<li class="header">SISTEMA</li>

							<li class="treeview <?php echo @$menu_cadastros ?>">
								<a href="#">
									<i class="fa fa-cogs"></i>
									<span>Configurações</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$servicos ?>"><a href="servicos"><i class="fa fa-scissors"></i>Serviços</a></li>
									<li class="<?php echo @$cat_servicos ?>"><a href="cat_servicos"><i class="fa fa-tags"></i>Categoria Serviços</a></li>
									<li class="<?php echo @$cargos ?>"><a href="cargos"><i class="fa fa-briefcase"></i>Cargos</a></li>
									<li class="<?php echo @$pgto ?>"><a href="pgto"><i class="fa fa-credit-card"></i>Formas de Pagamento</a></li>
									<li class="<?php echo @$dias_bloqueio ?>"><a href="dias_bloqueio"><i class="fa fa-ban"></i>Bloqueio de Dias</a></li>
									<li class="<?php echo @$assinaturas ?>"><a href="assinaturas"><i class="fa fa-pencil-square-o"></i>Assinaturas</a></li>
									<li class="<?php echo @$frequencias ?>"><a href="frequencias"><i class="fa fa-clock-o"></i>Frequências</a></li>
									<?php if($modo_teste != 'Sim'){ ?>
									<li class="<?php echo @$grupos ?>"><a href="grupos"><i class="fa fa-users"></i>Grupo Acessos</a></li>
									<li class="<?php echo @$acessos ?>"><a href="acessos"><i class="fa fa-key"></i>Acessos</a></li>
									<?php } ?>
								</ul>
							</li>

							<li class="treeview <?= @$marketing ?>">
								<a href="marketingp">
									<i class="fa fa-paper-plane"></i><span>Marketing</span>
								</a>
							</li>

							<li class="treeview <?php echo @$menu_site ?>">
								<a href="#">
									<i class="fa fa-globe"></i>
									<span>Site</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="<?php echo @$textos_index ?>"><a href="textos_index"><i class="fa fa-file-text-o"></i>Textos Index</a></li>
									<li class="<?php echo @$comentarios ?>"><a href="comentarios"><i class="fa fa-comments-o"></i>Comentários</a></li>
								</ul>
							</li>

							<li class="treeview <?php echo @$verificar_pgtos ?>">
								<a href="#" onclick="verificarPg()">
									<i class="fa fa-refresh"></i> <span>Verificar Pagamentos</span>
								</a>
							</li>

							<li class="treeview <?php echo @$dispositivos ?>">
								<a href="dispositivos">
									<i class="fa fa-mobile"></i> <span>Conectar Dispositivo</span>
								</a>
							</li>

						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</nav>
			</aside>
		</div>
		<!--left-fixed -navigation-->
		
		<!-- header-starts -->
		<div class="sticky-header header-section ">
			<div class="header-left">
				<div class="profile_details_left"><!--notifications of menu start -->
					<ul class="nofitications-dropdown">
						<!-- Botão Mobile -->
						<button id="mobile-menu-toggle" onclick="toggleMobileSidebar()" style="background: transparent; border: none; color: #007A63; font-size: 20px; padding: 10px; cursor: pointer;">
							<i class="fa fa-bars"></i>
						</button>


					</ul>
					<div class="clearfix"> </div>
				</div>
				
			</div>
			<div class="header-right">
				
				<?php 
				// Calcular total de notificações
				$total_notificacoes = 0;
				
				// Agendamentos do usuário
				if($atendimento == 'Sim'){
					$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() and funcionario = '$id_usuario' and status = 'Agendado'");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$total_agendamentos_hoje_usuario_pendentes = @count($res);
					$total_notificacoes += $total_agendamentos_hoje_usuario_pendentes;
					$agendamentos_notif = $res;
				} else {
					$total_agendamentos_hoje_usuario_pendentes = 0;
					$agendamentos_notif = [];
				}
				
				// Aniversariantes
				if(@$rel_aniv == ''){
					$query = $pdo->query("SELECT * FROM clientes where month(data_nasc) = '$dataMesInicial' and day(data_nasc) = '$dataDiaInicial' order by data_nasc asc, id asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$total_aniversariantes_hoje = @count($res);
					$total_notificacoes += $total_aniversariantes_hoje;
					$aniversariantes_notif = $res;
				} else {
					$total_aniversariantes_hoje = 0;
					$aniversariantes_notif = [];
				}
				
				// Clientes com retorno
				if(@$clientes_retorno == ''){
					$query = $pdo->query("SELECT * FROM clientes where alertado != 'Sim' and data_retorno < curDate() ORDER BY data_retorno asc");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$total_clientes_retorno = @count($res);
					$total_notificacoes += $total_clientes_retorno;
					$clientes_retorno_notif = $res;
				} else {
					$total_clientes_retorno = 0;
					$clientes_retorno_notif = [];
				}
				
				// Comentários
				if(@$comentarios == ''){
					$query = $pdo->query("SELECT * FROM comentarios where ativo != 'Sim'");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$total_comentarios = @count($res);
					$total_notificacoes += $total_comentarios;
					$comentarios_notif = $res;
				} else {
					$total_comentarios = 0;
					$comentarios_notif = [];
				}
				?>
				
				<!-- Botão de Notificações -->
				<div class="notifications-bell dropdown">
					<a href="#" class="dropdown-toggle bell-icon" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-bell"></i>
						<?php if($total_notificacoes > 0){ ?>
						<span class="badge-notification"><?php echo $total_notificacoes ?></span>
						<?php } ?>
					</a>
					<ul class="dropdown-menu dropdown-notifications">
							<div class="notification_header">
								<h3>
									<i class="fa fa-bell" style="margin-right: 8px;"></i>
									Notificações (<?php echo $total_notificacoes ?>)
								</h3>
							</div>
							
							<div class="notifications-content">
								<!-- Agendamentos -->
								<?php if($total_agendamentos_hoje_usuario_pendentes > 0){ ?>
								<div class="notification-section">
									<div class="notification-section-header">
										<i class="fa fa-calendar-check-o" style="color: #007A63;"></i>
										<span>Agendamentos Pendentes (<?php echo $total_agendamentos_hoje_usuario_pendentes ?>)</span>
									</div>
									<?php 
									$count = 0;
									foreach($agendamentos_notif as $agenda){
										if($count >= 3) break; // Mostrar apenas 3
										$cliente = $agenda['cliente'];
										$hora = $agenda['hora'];
										$horaF = date("H:i", strtotime($hora));
										
										$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
										$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
										$nome_cliente = @count($res2) > 0 ? $res2[0]['nome'] : 'Sem Cliente';
									?>
									<div class="notification_desc">
										<p>
											<i class="fa fa-clock-o" style="color: #007A63; margin-right: 6px;"></i>
											<b><?php echo $horaF ?></b> - <?php echo $nome_cliente ?>
										</p>
									</div>
									<?php 
										$count++;
									}
									?>
									<div class="notification-section-footer">
										<a href="agenda">Ver todos os agendamentos <i class="fa fa-arrow-right"></i></a>
									</div>
								</div>
								<?php } ?>
								
								<!-- Aniversariantes -->
								<?php if($total_aniversariantes_hoje > 0){ ?>
								<div class="notification-section">
									<div class="notification-section-header">
										<i class="fa fa-birthday-cake" style="color: #9c27b0;"></i>
										<span>Aniversariantes de Hoje (<?php echo $total_aniversariantes_hoje ?>)</span>
									</div>
									<?php 
									$count = 0;
									foreach($aniversariantes_notif as $aniv){
										if($count >= 3) break; // Mostrar apenas 3
										$nome = $aniv['nome'];
										$telefone = $aniv['telefone'];
									?>
									<div class="notification_desc">
										<p>
											<i class="fa fa-user" style="color: #9c27b0; margin-right: 6px;"></i>
											<b><?php echo $nome ?></b>
										</p>
										<p style="font-size: 12px; color: #6c757d; margin-top: 4px;">
											<i class="fa fa-phone" style="margin-right: 4px;"></i><?php echo $telefone ?>
										</p>
									</div>
									<?php 
										$count++;
									}
									?>
									<div class="notification-section-footer">
										<a href="#" data-toggle="modal" data-target="#RelAniv">Ver relatório completo <i class="fa fa-arrow-right"></i></a>
									</div>
								</div>
								<?php } ?>
								
								<!-- Clientes com Retorno -->
								<?php if($total_clientes_retorno > 0){ ?>
								<div class="notification-section">
									<div class="notification-section-header">
										<i class="fa fa-history" style="color: #ff9800;"></i>
										<span>Clientes com Retorno (<?php echo $total_clientes_retorno ?>)</span>
									</div>
									<?php 
									$count = 0;
									foreach($clientes_retorno_notif as $cret){
										if($count >= 3) break; // Mostrar apenas 3
										$nome = $cret['nome'];
										$telefone = $cret['telefone'];
									?>
									<div class="notification_desc">
										<p>
											<i class="fa fa-user" style="color: #ff9800; margin-right: 6px;"></i>
											<b><?php echo $nome ?></b>
										</p>
										<p style="font-size: 12px; color: #6c757d; margin-top: 4px;">
											<i class="fa fa-phone" style="margin-right: 4px;"></i><?php echo $telefone ?>
										</p>
									</div>
									<?php 
										$count++;
									}
									?>
									<div class="notification-section-footer">
										<a href="clientes_retorno">Ver todos os retornos <i class="fa fa-arrow-right"></i></a>
									</div>
								</div>
								<?php } ?>
								
								<!-- Comentários -->
								<?php if($total_comentarios > 0){ ?>
								<div class="notification-section">
									<div class="notification-section-header">
										<i class="fa fa-comment" style="color: #42a5f5;"></i>
										<span>Depoimentos Pendentes (<?php echo $total_comentarios ?>)</span>
									</div>
									<?php 
									$count = 0;
									foreach($comentarios_notif as $coment){
										if($count >= 3) break; // Mostrar apenas 3
										$nome = $coment['nome'];
									?>
									<div class="notification_desc">
										<p>
											<i class="fa fa-user" style="color: #42a5f5; margin-right: 6px;"></i>
											<b><?php echo $nome ?></b>
										</p>
										<p style="font-size: 12px; color: #6c757d; margin-top: 4px;">
											<i class="fa fa-star-o" style="margin-right: 4px;"></i>Aguardando aprovação
										</p>
									</div>
									<?php 
										$count++;
									}
									?>
									<div class="notification-section-footer">
										<a href="comentarios">Ver todos os depoimentos <i class="fa fa-arrow-right"></i></a>
									</div>
								</div>
								<?php } ?>
								
								<!-- Mensagem quando não há notificações -->
								<?php if($total_notificacoes == 0){ ?>
								<div class="empty-state">
									<i class="fa fa-bell-slash-o"></i>
									<p>Nenhuma notificação no momento</p>
									<p>Você está em dia com tudo!</p>
								</div>
								<?php } ?>
							</div>
							
							<?php if($total_notificacoes > 0){ ?>
							<div class="notification_bottom">
								<a href="index.php">
									<i class="fa fa-check-circle" style="margin-right: 6px;"></i>
									Ver Dashboard Completo
								</a>
							</div>
							<?php } ?>
						</ul>
				</div>
				
				<div class="profile_details">		
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img"><img src="img/perfil/<?php echo $foto_usuario ?>" alt="" width="50" height="50"> </span> 
									<div class="user-name esc">
										<p><?php echo $nome_usuario ?></p>
										<span><?php echo $nivel_usuario ?></span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>	
								</div>	
							</a>
							<ul class="dropdown-menu drp-mnu">
								<?php if(@$configuracoes == ''){ ?>
								<li> <a href="configuracoes" ><i class="fa fa-cog"></i> Configurações</a> </li> 	
								<?php } ?>

								<li> <a href="" data-toggle="modal" data-target="#modalPerfil"><i class="fa fa-suitcase"></i> Editar Perfil</a> </li> 
								<li> <a href="logout.php"><i class="fa fa-sign-out"></i> Sair</a> </li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="clearfix"> </div>				
			</div>
			<div class="clearfix"> </div>	
		</div>
		<!-- //header-ends -->








		<!-- main content start-->
		<div id="page-wrapper">
			<?php require_once("paginas/".$pag.'.php') ?>
		</div>









		<!--footer-->
		<div class="footer-modern">
			<div class="footer-content">
				<div class="footer-left">
					<p>&copy; UAI Sistemas - Todos os direitos reservados.</p>
				</div>
				<div class="footer-right">
					<p style="display: flex; align-items: center; gap: 8px; margin: 0;">
						<span>Desenvolvido por</span>
						<a href="https://uaisis.com/" target="_blank" style="display: flex; align-items: center;">
							<img src="../../images/uai.png" alt="UAI Sistemas" style="height: 24px; width: auto;" onerror="this.style.display='none'">
						</a>
					</p>
				</div>
			</div>
		</div>
		<!--//footer-->
	</div>




	<!-- Classie --><!-- for toggle left push menu script -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			if(showLeftPush) {
				showLeftPush.onclick = function() {
					classie.toggle( this, 'active' );
					classie.toggle( body, 'cbp-spmenu-push-toright' );
					classie.toggle( menuLeft, 'cbp-spmenu-open' );
					disableOther( 'showLeftPush' );
				};
			}
			

			function disableOther( button ) {
				if( button !== 'showLeftPush' && showLeftPush ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}


		var showLeftPush2 = document.getElementById( 'showLeftPush2' );
		
		if(showLeftPush2) {
			showLeftPush2.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther2( 'showLeftPush2' );
			};
		}


		function disableOther2( button ) {
			if( button !== 'showLeftPush2' && showLeftPush2 ) {
				classie.toggle( showLeftPush2, 'disabled' );
			}
		}

		</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->


	<!--scrolling js-->
	<!-- <script src="js/jquery.nicescroll.js"></script> -->
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
		// Inicializar SidebarNav com callback
		$('.sidebar-menu').SidebarNav();
		
		// Após inicialização, garantir que menu pai fique aberto
		setTimeout(function() {
			if(typeof ativarMenuPaiEFilho === 'function') {
				ativarMenuPaiEFilho();
			}
		}, 200);
	</script>
	<!-- //side nav js -->

	<!-- Sidebar Toggle Script -->
	<script>
	// Função melhorada para filtros de data nos modais
	function datas(data, filtro, modal) {
		$('#dataInicialRel-' + modal).val(data);
		$('#dataFinalRel-' + modal).val(data);
		
		// Ativar botão selecionado
		document.querySelectorAll('.btn-filtro').forEach(btn => {
			btn.classList.remove('active');
		});
		document.getElementById('btn-' + filtro).classList.add('active');
	}

	// Toggle Sidebar Expandido/Minimizado
	function toggleSidebar() {
		document.body.classList.toggle('sidebar-collapsed');
		
		// Salvar estado no localStorage
		const isCollapsed = document.body.classList.contains('sidebar-collapsed');
		localStorage.setItem('sidebarCollapsed', isCollapsed);
		
		// Re-aplicar estado do menu pai/filho após toggle
		setTimeout(function() {
			ativarMenuPaiEFilho();
		}, 100);
	}

	// Toggle Mobile Sidebar
	function toggleMobileSidebar() {
		document.body.classList.toggle('sidebar-open');
		
		// Bloquear scroll quando menu aberto
		if(document.body.classList.contains('sidebar-open')) {
			document.body.style.overflow = 'hidden';
		} else {
			document.body.style.overflow = '';
		}
	}

	// Manter menu pai aberto quando filho está ativo
	function ativarMenuPaiEFilho() {
		// Encontrar todos os itens filhos ativos
		const filhosAtivos = document.querySelectorAll('.sidebar-menu .treeview-menu > li.active');
		
		filhosAtivos.forEach(filho => {
			// Encontrar o menu pai (li.treeview)
			const menuPai = filho.closest('li.treeview');
			
			if(menuPai) {
				// Adicionar classe para manter aberto
				menuPai.classList.add('has-active-child');
				
				// Forçar exibição do submenu
				const submenu = menuPai.querySelector('.treeview-menu');
				if(submenu) {
					submenu.style.display = 'block';
				}
				
				// No modo minimizado, manter flyout aberto
				if(document.body.classList.contains('sidebar-collapsed')) {
					menuPai.classList.add('flyout-open');
					const rect = menuPai.getBoundingClientRect();
					submenu.style.top = rect.top + 'px';
				}
			}
		});
	}

	// Restaurar estado do sidebar ao carregar página
	document.addEventListener('DOMContentLoaded', function() {
		const savedState = localStorage.getItem('sidebarCollapsed');
		if(savedState === 'true') {
			document.body.classList.add('sidebar-collapsed');
		}

		// Ativar menu pai e filho
		ativarMenuPaiEFilho();

		// Adicionar comportamento de flyout nos submenus quando minimizado
		const treeviewItems = document.querySelectorAll('.sidebar-menu > li.treeview');
		treeviewItems.forEach(item => {
			const submenu = item.querySelector('.treeview-menu');
			const link = item.querySelector('a');
			
			if(submenu) {
				// Posicionar flyout ao passar mouse
				item.addEventListener('mouseenter', function() {
					if(document.body.classList.contains('sidebar-collapsed')) {
						const rect = item.getBoundingClientRect();
						submenu.style.top = rect.top + 'px';
					}
				});

				// Fixar flyout ao clicar (apenas no link principal, não no submenu)
				if(link) {
					link.addEventListener('click', function(e) {
						if(document.body.classList.contains('sidebar-collapsed')) {
							// Apenas prevenir se o link for "#" (tem submenu)
							if(link.getAttribute('href') === '#' || link.getAttribute('href') === '') {
								e.preventDefault();
								e.stopPropagation();
								
								// Fechar outros flyouts abertos
								document.querySelectorAll('.sidebar-menu > li.treeview').forEach(other => {
									if(other !== item) {
										other.classList.remove('flyout-open');
									}
								});
								
								// Toggle este flyout
								item.classList.toggle('flyout-open');
							}
						}
					});
				}
			}
		});

		// Permitir navegação nos links do submenu
		document.querySelectorAll('.sidebar-menu .treeview-menu a').forEach(link => {
			link.addEventListener('click', function(e) {
				// Links de submenu sempre navegam normalmente
				e.stopPropagation();
				
				// No modo minimizado, manter flyout aberto até navegação completa
				// (permite clicar nos links)
			});
		});

		// Manter flyout aberto se há item ativo dentro dele
		function manterFlyoutSeAtivo() {
			if(document.body.classList.contains('sidebar-collapsed')) {
				document.querySelectorAll('.sidebar-menu > li.treeview').forEach(item => {
					const temFilhoAtivo = item.querySelector('.treeview-menu .active');
					if(temFilhoAtivo) {
						item.classList.add('flyout-open');
					}
				});
			}
		}

		// Executar ao carregar
		manterFlyoutSeAtivo();

		// Fechar flyout ao clicar fora (exceto se tem filho ativo)
		document.addEventListener('click', function(e) {
			if(document.body.classList.contains('sidebar-collapsed')) {
				const sidebar = document.querySelector('.sidebar-left');
				if(!sidebar.contains(e.target)) {
					document.querySelectorAll('.sidebar-menu > li.treeview.flyout-open').forEach(item => {
						// Não fechar se tem filho ativo
						const temFilhoAtivo = item.querySelector('.treeview-menu .active');
						if(!temFilhoAtivo) {
							item.classList.remove('flyout-open');
						}
					});
				}
			}
		});
	});

	// Fechar sidebar mobile ao pressionar ESC
	document.addEventListener('keydown', function(event) {
		if(event.key === 'Escape' && document.body.classList.contains('sidebar-open')) {
			toggleMobileSidebar();
		}
	});
	</script>
	
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->
	
</body>
</html>


<!-- SweetAlert JS -->
<script src="js/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="js/sweetalert1.min.css">
<script src="js/alertas.js"></script>


<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 




<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
		.select2-selection__rendered {
			line-height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}

		.select2-selection {
			height: 36px !important;
			font-size:16px !important;
			color:#666666 !important;

		}
	</style>  


<!-- Modal Perfil-->
<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Perfil</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form method="post" id="form-perfil">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome-perfil" name="nome" placeholder="Nome" value="<?php echo $nome_usuario ?>" required>    
							</div> 	
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="exampleInputEmail1">Email</label>
								<input type="email" class="form-control" id="email-perfil" name="email" placeholder="Email" value="<?php echo $email_usuario ?>" required>    
							</div> 	
						</div>
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Telefone</label>
								<input type="text" class="form-control" id="telefone-perfil" name="telefone" placeholder="Telefone" value="<?php echo $telefone_usuario ?>" >    
							</div> 	
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label for="exampleInputEmail1">CPF</label>
								<input type="text" class="form-control" id="cpf-perfil" name="cpf" placeholder="CPF" value="<?php echo $cpf_usuario ?>">    
							</div> 	
						</div>
					</div>


					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Senha</label>
								<input type="password" class="form-control" id="senha-perfil" name="senha" placeholder="Senha" value="<?php echo $senha_usuario ?>" required>    
							</div> 	
						</div>
						<div class="col-md-4">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Confirmar Senha</label>
								<input type="password" class="form-control" id="conf-senha-perfil" name="conf_senha" placeholder="Confirmar Senha" required>    
							</div> 	
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Atendimento</label>
								<select class="form-control" name="atendimento" id="atendimento-perfil">
									<option <?php if($atendimento == 'Sim'){ ?> selected <?php } ?> value="Sim">Sim</option>
									<option <?php if($atendimento == 'Não'){ ?> selected <?php } ?> value="Não">Não</option>
								</select>  
							</div> 	
						</div>

					</div>


					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço</label>
								<input type="text" class="form-control" id="endereco-perfil" name="endereco" placeholder="Rua X Número 1 Bairro xxx" value="<?php echo $endereco_usuario ?>" >    
							</div> 	
						</div>

							<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Intervalo Minutos</label>
								<input type="number" class="form-control" id="intervalo_perfil" name="intervalo" placeholder="Intervalo Horários" value="<?php echo $intervalo_horarios ?>" required>    
							</div> 	
						</div>
						
					</div>





						<div class="row">
							<div class="col-md-8">						
								<div class="form-group"> 
									<label>Foto</label> 
									<input class="form-control" type="file" name="foto" onChange="carregarImgPerfil();" id="foto-usu">
								</div>						
							</div>
							<div class="col-md-4">
								<div id="divImg">
									<img src="img/perfil/<?php echo $foto_usuario ?>"  width="80px" id="target-usu">									
								</div>
							</div>

						</div>


					
						<input type="hidden" name="id" value="<?php echo $id_usuario ?>">

					<br>
					<small><div id="mensagem-perfil" align="center"></div></small>
				</div>
				<div class="modal-footer">      
					<button type="submit" class="btn btn-primary">Editar Perfil</button>
				</div>
			</form>
		</div>
	</div>
</div>









<!-- Modal Config-->
<div class="modal fade" id="modalConfig" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Editar Configurações</h4>
				<button id="btn-fechar-config" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			
		</div>
	</div>
</div>











	<!-- Modal Rel Entradas / Ganhos -->
	<div class="modal fade" id="RelEntradas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Relatório de Ganhos
						<small>(
							<a href="#" onclick="datas('1980-01-01', 'tudo-Ent', 'Ent')">
								<span style="color:#000" id="tudo-Ent">Tudo</span>
							</a> / 
							<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Ent', 'Ent')">
								<span id="hoje-Ent">Hoje</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Ent', 'Ent')">
								<span style="color:#000" id="mes-Ent">Mês</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Ent', 'Ent')">
								<span style="color:#000" id="ano-Ent">Ano</span>
							</a> 
						)</small>



					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_entradas_class.php" target="_blank">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Ent" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Ent" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>

							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Entradas / Ganhos</label> 
									<select class="form-control sel13" name="filtro" style="width:100%;">
										<option value="">Todas</option>
										<option value="Venda">Vendas</option>
										<option value="Serviço">Serviços</option>
										<option value="Conta">Demais Ganhos</option>
										
									</select> 
								</div>						
							</div>


							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Selecionar Cliente</label> 
									<select class="form-control selcli" name="cliente" style="width:100%;" > 
									<option value="">Todos</option>
									<?php 
									$query = $pdo->query("SELECT * FROM clientes");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
								</div>						
							</div>


						</div>


						

					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Gerar Relatório</button>
					</div>
				</form>

			</div>
		</div>
	</div>









	<!-- Modal Rel Saidas / Despesas -->
	<div class="modal fade" id="RelSaidas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Relatório de Saídas
						<small>(
							<a href="#" onclick="datas('1980-01-01', 'tudo-Saida', 'Saida')">
								<span style="color:#000" id="tudo-Saida">Tudo</span>
							</a> / 
							<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Saida', 'Saida')">
								<span id="hoje-Saida">Hoje</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Saida', 'Saida')">
								<span style="color:#000" id="mes-Saida">Mês</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Saida', 'Saida')">
								<span style="color:#000" id="ano-Saida">Ano</span>
							</a> 
						)</small>



					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_saidas_class.php" target="_blank">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Saida" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-4">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Saida" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Saídas / Despesas</label> 
									<select class="form-control sel13" name="filtro" style="width:100%;">
										<option value="">Todas</option>
										<option value="Conta">Despesas</option>
										<option value="Comissão">Comissões</option>
										<option value="Compra">Compras</option>
										
									</select> 
								</div>						
							</div>

						</div>


						

					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Gerar Relatório</button>
					</div>
				</form>

			</div>
		</div>
	</div>










	<!-- Modal Rel Comissoes -->
	<div class="modal fade" id="RelComissoes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Relatório de Comissões
						<small>(
							<a href="#" onclick="datas('1980-01-01', 'tudo-Com', 'Com')">
								<span style="color:#000" id="tudo-Com">Tudo</span>
							</a> / 
							<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Com', 'Com')">
								<span id="hoje-Com">Hoje</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Com', 'Com')">
								<span style="color:#000" id="mes-Com">Mês</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Com', 'Com')">
								<span style="color:#000" id="ano-Com">Ano</span>
							</a> 
						)</small>



					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_comissoes_class.php" target="_blank">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Com" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-4">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Com" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>

								<div class="col-md-4">						
								<div class="form-group"> 
									<label>Pago</label> 
									<select class="form-control " name="pago" style="width:100%;">
										<option value="">Todas</option>
										<option value="Sim">Somente Pagas</option>
										<option value="Não">Pendentes</option>
										
									</select> 
								</div>						
							</div>

						</div>

						<div class="row">
							<div class="col-md-12">						
								<div class="form-group"> 
									<label>Funcionário</label> 
									<select class="form-control sel15" name="funcionario" style="width:100%;">
										<option value="">Todos</option>
										<?php 
				$query = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY id desc");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$total_reg = @count($res);
				if($total_reg > 0){
					for($i=0; $i < $total_reg; $i++){
						foreach ($res[$i] as $key => $value){}
							echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
					}
				}?>
										
									</select> 
								</div>						
							</div>	
						</div>


						

					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Gerar Relatório</button>
					</div>
				</form>

			</div>
		</div>
	</div>








	<!-- Modal Rel Contas -->
	<div class="modal fade" id="RelCon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Relatório de Contas
						<small>(
							<a href="#" onclick="datas('1980-01-01', 'tudo-Con', 'Con')">
								<span style="color:#000" id="tudo-Con">Tudo</span>
							</a> / 
							<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Con', 'Con')">
								<span id="hoje-Con">Hoje</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Con', 'Con')">
								<span style="color:#000" id="mes-Con">Mês</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Con', 'Con')">
								<span style="color:#000" id="ano-Con">Ano</span>
							</a> 
						)</small>



					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_contas_class.php" target="_blank">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Con" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-4">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Con" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Pago</label> 
									<select class="form-control" name="pago" style="width:100%;">
										<option value="">Todas</option>
										<option value="Sim">Somente Pagas</option>
										<option value="Não">Pendentes</option>
										
									</select> 
								</div>						
							</div>

						</div>



							<div class="row">
							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Pagar / Receber</label> 
									<select class="form-control sel13" name="tabela" style="width:100%;">
										<option value="pagar">Contas à Pagar</option>
										<option value="receber">Contas à Receber</option>
																				
									</select> 
								</div>						
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
									<label>Consultar Por</label> 
									<select class="form-control sel13" name="busca" style="width:100%;">
										<option value="data_venc">Data de Vencimento</option>
										<option value="data_pgto">Data de Pagamento</option>
																				
									</select>
								</div>
							</div>

							

						</div>


						

					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Gerar Relatório</button>
					</div>
				</form>

			</div>
		</div>
	</div>








	<!-- Modal Rel Lucro -->
	<div class="modal fade" id="RelLucro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Demonstrativo de Lucro
						<small>(
							<a href="#" onclick="datas('1980-01-01', 'tudo-Lucro', 'Lucro')">
								<span style="color:#000" id="tudo-Lucro">Tudo</span>
							</a> / 
							<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Lucro', 'Lucro')">
								<span id="hoje-Lucro">Hoje</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Lucro', 'Lucro')">
								<span style="color:#000" id="mes-Lucro">Mês</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Lucro', 'Lucro')">
								<span style="color:#000" id="ano-Lucro">Ano</span>
							</a> 
						)</small>



					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_lucro_class.php" target="_blank">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Lucro" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-4">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Lucro" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>						

						</div>


						

					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Gerar Relatório</button>
					</div>
				</form>

			</div>
		</div>
	</div>










	<!-- Modal Rel Aniversariantes -->
	<div class="modal fade" id="RelAniv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title">
						<div class="modal-title-main">
							<div class="modal-title-icon">
								<i class="fa fa-birthday-cake"></i>
							</div>
							<span>Relatório de Aniversariantes</span>
						</div>
						<div class="modal-subtitle">
							Gere relatórios de aniversariantes por período
						</div>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_aniv_class.php" target="_blank">
					<div class="modal-body">

						<!-- Filtros Rápidos -->
						<div class="form-group">
							<label style="font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 12px; display: block;">
								Filtros Rápidos
							</label>
							<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px;">
								<button type="button" onclick="datas('1980-01-01', 'tudo-Aniv', 'Aniv')" 
										class="btn-filtro active" id="btn-tudo-Aniv">
									<i class="fa fa-calendar"></i>
									<span>Tudo</span>
								</button>
								<button type="button" onclick="datas('<?php echo $data_atual ?>', 'hoje-Aniv', 'Aniv')" 
										class="btn-filtro" id="btn-hoje-Aniv">
									<i class="fa fa-calendar-o"></i>
									<span>Hoje</span>
								</button>
								<button type="button" onclick="datas('<?php echo $data_mes ?>', 'mes-Aniv', 'Aniv')" 
										class="btn-filtro" id="btn-mes-Aniv">
									<i class="fa fa-calendar-check-o"></i>
									<span>Este Mês</span>
								</button>
								<button type="button" onclick="datas('<?php echo $data_ano ?>', 'ano-Aniv', 'Aniv')" 
										class="btn-filtro" id="btn-ano-Aniv">
									<i class="fa fa-calendar-plus-o"></i>
									<span>Este Ano</span>
								</button>
							</div>
						</div>

						<div class="modal-section-divider"></div>

						<!-- Datas Personalizadas -->
						<div class="form-group">
							<label style="font-size: 14px; font-weight: 600; color: #1a1a1a; margin-bottom: 4px; display: block;">
								Período Personalizado
							</label>
							<p style="font-size: 12px; color: #6c757d; margin-bottom: 16px;">
								Selecione um intervalo de datas específico
							</p>
						</div>

						<div class="row">
							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Aniv" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Aniv" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>						
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn-cancel" data-dismiss="modal">
							Cancelar
						</button>
						<button type="submit" class="btn-submit">
							<i class="fa fa-file-pdf-o"></i>
							Gerar PDF
						</button>
					</div>
				</form>

			</div>
		</div>
	</div>







	<!-- Modal Rel Entradas / Ganhos -->
	<div class="modal fade" id="RelServicos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Relatório de Serviços
						<small>(
							<a href="#" onclick="datas('1980-01-01', 'tudo-Ser', 'Ser')">
								<span style="color:#000" id="tudo-Ser">Tudo</span>
							</a> / 
							<a href="#" onclick="datas('<?php echo $data_atual ?>', 'hoje-Ser', 'Ser')">
								<span id="hoje-Ser">Hoje</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_mes ?>', 'mes-Ser', 'Ser')">
								<span style="color:#000" id="mes-Ser">Mês</span>
							</a> /
							<a href="#" onclick="datas('<?php echo $data_ano ?>', 'ano-Ser', 'Ser')">
								<span style="color:#000" id="ano-Ser">Ano</span>
							</a> 
						)</small>



					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="rel/rel_servicos_class.php" target="_blank">
					<div class="modal-body">

						<div class="row">
							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Data Inicial</label> 
									<input type="date" class="form-control" name="dataInicial" id="dataInicialRel-Ser" value="<?php echo date('Y-m-d') ?>" required> 
								</div>						
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
									<label>Data Final</label> 
									<input type="date" class="form-control" name="dataFinal" id="dataFinalRel-Ser" value="<?php echo date('Y-m-d') ?>" required> 
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Forma de Pagamento</label> 
									<select class="form-control" name="pgto" style="width:100%;" > 
									<option value="">Selecionar Pagamento</option>
									<?php 
									$query = $pdo->query("SELECT * FROM formas_pgto");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['nome'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
								</div>						
							</div>


							<div class="col-md-6">						
								<div class="form-group"> 
									<label>Selecionar Serviço</label> 
									<select class="form-control" name="servico" style="width:100%;" > 
									<option value="">Selecionar Serviço</option>
									<?php 
									$query = $pdo->query("SELECT * FROM servicos");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = @count($res);
									if($total_reg > 0){
										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){}
												echo '<option value="'.$res[$i]['id'].'">'.$res[$i]['nome'].'</option>';
										}
									}
									?>


								</select>    
								</div>						
							</div>

						</div>


						

					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Gerar Relatório</button>
					</div>
				</form>

			</div>
		</div>
	</div>









<!-- Modal Verificar pgtos pendentes -->
<div class="modal fade" id="modalVerificar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Verificando Pagamentos</h4>
				<button id="btn-fechar-pgtos" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">	
				<div id="verificar_pagamentos">
					<div align="center" id="loading_img"><img src="images/loading.gif"></div>
					<div align="center" id="textos_verificar" style="display:none"></div>
				</div>

				
			</div>						
	
			
		</div>
	</div>
</div>







<script type="text/javascript">
	$(document).ready(function() {		
		$('.sel15').select2({	
			dropdownParent: $('#RelComissoes')		
		});

		$('.selcli').select2({	
			dropdownParent: $('#RelEntradas')		
		});
	});
</script>


 <script type="text/javascript">
	$("#form-perfil").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-perfil.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-perfil').text('');
				$('#mensagem-perfil').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-perfil').click();
					location.reload();			
					
				} else {

					$('#mensagem-perfil').addClass('text-danger')
					$('#mensagem-perfil').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>








<script type="text/javascript">
	function carregarImgPerfil() {
    var target = document.getElementById('target-usu');
    var file = document.querySelector("#foto-usu").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>







 <script type="text/javascript">
	$("#form-config").submit(function () {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "editar-config.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-config').text('');
				$('#mensagem-config').removeClass()
				if (mensagem.trim() == "Editado com Sucesso") {

					$('#btn-fechar-config').click();
					location.reload();			
					
				} else {

					$('#mensagem-config').addClass('text-danger')
					$('#mensagem-config').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>




<script type="text/javascript">
	function carregarImgLogo() {
    var target = document.getElementById('target-logo');
    var file = document.querySelector("#foto-logo").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgLogoRel() {
    var target = document.getElementById('target-logo-rel');
    var file = document.querySelector("#foto-logo-rel").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgIcone() {
    var target = document.getElementById('target-icone');
    var file = document.querySelector("#foto-icone").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgIconeSite() {
    var target = document.getElementById('target-icone-site');
    var file = document.querySelector("#foto-icone-site").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>






<script type="text/javascript">
	function carregarImgBannerIndex() {
    var target = document.getElementById('target-banner-index');
    var file = document.querySelector("#foto-banner-index").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>





<script type="text/javascript">
	function carregarImgSobre() {
    var target = document.getElementById('target-sobre');
    var file = document.querySelector("#foto-sobre").files[0];
    
        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }
</script>




	<script type="text/javascript">
		function datas(data, id, campo){		

			var data_atual = "<?=$data_atual?>";
			var separarData = data_atual.split("-");
			var mes = separarData[1];
			var ano = separarData[0];

			var separarId = id.split("-");

			if(separarId[0] == 'tudo'){
				data_atual = '2100-12-31';
			}

			if(separarId[0] == 'ano'){
				data_atual = ano + '-12-31';
			}

			if(separarId[0] == 'mes'){
				if(mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12){
					data_atual = ano + '-'+ mes + '-31';
				}else if (mes == 4 || mes == 6 || mes == 9 || mes == 11){
					data_atual = ano + '-'+ mes + '-30';
				}else{
					data_atual = ano + '-'+ mes + '-28';
				}

			}

			$('#dataInicialRel-'+campo).val(data);
			$('#dataFinalRel-'+campo).val(data_atual);

			document.getElementById('hoje-'+campo).style.color = "#000";
			document.getElementById('mes-'+campo).style.color = "#000";
			document.getElementById(id).style.color = "blue";	
			document.getElementById('tudo-'+campo).style.color = "#000";
			document.getElementById('ano-'+campo).style.color = "#000";
			document.getElementById(id).style.color = "blue";		
		}
	</script>



<script type="text/javascript">
	function verificarPg(){
		$('#modalVerificar').modal('show');
		$('#loading_img').show();
		$('#textos_verificar').hide();

		$.ajax({
        url: 'verificar_pgtos.php',
        method: 'POST',
        data: {},
        dataType: "html",

        success:function(mensagem){
            $('#loading_img').hide();
            $('#textos_verificar').html(mensagem);
            $('#textos_verificar').show();
        }

	});

	}
</script>
