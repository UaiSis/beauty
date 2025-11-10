<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag_inicial = 'home';

$id_usuario = $_SESSION['id'];

// Se não tiver ID na sessão mas tiver cookie, restaura
if(($id_usuario == "" || $id_usuario == null) && isset($_COOKIE['id_cliente']) && $_COOKIE['id_cliente'] != ""){
	$id_cliente_cookie = $_COOKIE['id_cliente'];
	$query_temp = $pdo->query("SELECT * FROM clientes WHERE id = '$id_cliente_cookie' LIMIT 1");
	$res_temp = $query_temp->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($res_temp) > 0){
		$_SESSION['id'] = $res_temp[0]['id'];
		$_SESSION['nome'] = $res_temp[0]['nome'];
		$_SESSION['telefone'] = $res_temp[0]['telefone'];
		$_SESSION['nivel'] = 'Cliente';
		$_SESSION['aut_token_505052022'] = "fdsfdsafda885574125";
		$id_usuario = $_SESSION['id'];
	}
}

$query = $pdo->query("SELECT * from clientes where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	$nome_usuario = $res[0]['nome'];	
	$cpf_usuario = $res[0]['cpf'];
	$telefone_usuario = $res[0]['telefone'];
	$endereco_usuario = $res[0]['endereco'];	
	$cartoes = $res[0]['cartoes'];	
	
}


if(@$_GET['pag'] == ""){
	$pag = 'agendamentos';
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

		/* Sidebar Moderna - Cores Verde #007A63 */
		.sidebar-left {
			background: #2a2a2a !important;
			border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
			box-shadow: none !important;
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
		}

		.sidebar-logo-sistema {
			max-width: 140px;
			max-height: 60px;
			width: auto;
			height: auto;
			object-fit: contain;
			transition: all 0.3s ease;
			filter: brightness(0) invert(1);
		}

		.sidebar-left .navbar-brand:hover .sidebar-logo-sistema {
			filter: brightness(0) invert(1) opacity(0.85);
			transform: translateY(-2px);
		}

		span.dashboard_text {
			display: none !important;
		}

		.sidebar-menu {
			padding: 0 16px 20px 16px;
		}

		.sidebar-menu li.header {
			padding: 0 8px 12px 8px;
			font-size: 11px;
			color: rgba(255, 255, 255, 0.4);
			background: transparent !important;
			letter-spacing: 0.5px;
			font-weight: 600;
			text-transform: capitalize;
			margin-top: 0;
		}

		.sidebar-menu > li > a {
			padding: 11px 14px;
			margin: 4px 0;
			border-radius: 12px;
			border-left: none !important;
			transition: all 0.25s ease;
			color: rgba(255, 255, 255, 0.65) !important;
			font-size: 14.5px;
			position: relative;
			background: transparent !important;
			font-weight: 400;
			display: flex;
			align-items: center;
		}

		.sidebar-menu > li > a i.fa {
			width: 20px;
			margin-right: 12px;
			font-size: 15px;
			text-align: center;
			opacity: 1;
			transition: all 0.25s ease;
		}

		.sidebar-menu > li > a:hover {
			background: rgba(0, 122, 99, 0.08) !important;
			color: rgba(255, 255, 255, 0.95) !important;
			transform: none;
		}

		.sidebar-menu > li > a:hover i.fa {
			transform: scale(1.05);
		}

		.sidebar-menu > li.active > a,
		.sidebar-menu > li.treeview.active > a,
		.sidebar-menu > li:has(.treeview-menu .active) > a {
			background: linear-gradient(135deg, rgba(0, 122, 99, 0.15) 0%, rgba(0, 104, 84, 0.12) 100%) !important;
			color: #ffffff !important;
			box-shadow: 0 0 0 1px rgba(0, 122, 99, 0.2);
			font-weight: 500;
		}

		.sidebar-menu > li.active > a i.fa,
		.sidebar-menu > li.treeview.active > a i.fa,
		.sidebar-menu > li:has(.treeview-menu .active) > a i.fa {
			color: #00d896;
			opacity: 1;
		}

		.sidebar-menu > li:has(.treeview-menu .active) > a .fa-angle-left {
			transform: rotate(-90deg);
			opacity: 0.8;
			color: #00d896;
		}

		.sidebar-menu > li > a .fa-angle-left {
			transition: transform 0.25s ease;
			opacity: 0.5;
			font-size: 14px;
			margin-left: auto;
		}

		.sidebar-menu > li.active > a .fa-angle-left {
			transform: rotate(-90deg);
			opacity: 0.8;
			color: #00d896;
		}

		.sidebar-menu > li > .treeview-menu {
			background: transparent !important;
			margin: 4px 0 8px 0 !important;
			padding: 0;
			border-radius: 0;
			border-left: none;
			margin-left: 0 !important;
		}

		.sidebar-menu .treeview-menu > li {
			margin: 0;
		}

		.sidebar-menu .treeview-menu > li > a {
			padding: 9px 14px 9px 46px;
			color: rgba(255, 255, 255, 0.55) !important;
			font-size: 13.5px;
			transition: all 0.25s ease;
			border-radius: 12px;
			margin: 3px 0;
			background: transparent !important;
			font-weight: 400;
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
			background: rgba(0, 122, 99, 0.08) !important;
			color: rgba(255, 255, 255, 0.9) !important;
			transform: none;
			padding-left: 46px;
		}

		.sidebar-menu .treeview-menu > li.active > a {
			background: linear-gradient(135deg, rgba(0, 122, 99, 0.12) 0%, rgba(0, 104, 84, 0.1) 100%) !important;
			color: #ffffff !important;
			font-weight: 500;
			box-shadow: 0 0 0 1px rgba(0, 122, 99, 0.15);
			border-left: none;
			padding-left: 46px;
		}

		.sidebar-menu .treeview-menu > li.active > a::before {
			content: '';
			position: absolute;
			left: 14px;
			top: 50%;
			transform: translateY(-50%);
			width: 3px;
			height: 18px;
			background: #00d896;
			border-radius: 2px;
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

		@media (max-width: 768px) {
			.sidebar-menu > li > a {
				padding: 10px 12px;
			}
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
	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left" style="overflow: scroll; height:100%; scrollbar-width: thin;">
				<nav class="navbar navbar-inverse" >
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false" id="showLeftPush2">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1><a class="navbar-brand" href="index.php">
							<img src="../img/logo.png" alt="<?php echo $nome_sistema ?>" class="sidebar-logo-sistema">
						</a></h1>
					</div>
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="sidebar-menu">
							<li class="header">MENU DE NAVEGAÇÃO</li>


							<li class="treeview <">
								<a href="index.php">
									<i class="fa fa-calendar-o"></i> <span>Agendamentos</span>
								</a>
							</li>

						
							<li class="treeview <?php echo @$planos ?>">
								<a href="planos">
									<i class="fa fa-credit-card-alt"></i> <span>Planos / Assinaturas</span>
								</a>
							</li>


						

								<li class="treeview ">
								<a href="receber">
									<i class="fa fa-usd"></i> <span>Meus Pagamentos</span>
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
				<!--toggle button start-->
				<button id="showLeftPush" data-toggle="collapse" data-target=".collapse"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				<div class="profile_details_left"><!--notifications of menu start -->
					<ul class="nofitications-dropdown">



						


					</ul>
					<div class="clearfix"> </div>
				</div>
				
			</div>
			<div class="header-right">
				
				
				
				
				<div class="profile_details">		
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img"><img src="img/perfil/sem-foto.jpg" alt="" width="50" height="50"> </span> 
									<div class="user-name esc">
										<p><?php echo $nome_usuario ?></p>
										<span>Cliente</span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>	
								</div>	
							</a>
							<ul class="dropdown-menu drp-mnu">								

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
			<div class="footer-content" style="flex-direction: column; gap: 20px;">
				<div style="text-align: center;">
					<div style="display: flex; gap: 8px; justify-content: center; align-items: center; margin-bottom: 12px;">
						<?php 
						for($i=1; $i<=$quantidade_cartoes; $i++){ 
							if($cartoes >= $i){
								$valor = 0;
								$opacity = 1;
							}else{
								$valor = 1;
								$opacity = 0.4;
							}
						?>
						<div style="display: inline-block;">
							<img src="../../images/favicon.png" width="32px" style="filter: grayscale(<?php echo $valor ?>); filter: opacity(<?php echo $opacity ?>); transition: all 0.3s ease;">
						</div>
						<?php } ?>
					</div>
					<p style="margin: 0; font-size: 13px; color: #6c757d;">
						<i class="fa fa-star" style="color: #007A63;"></i> Você possui <strong><?php echo $cartoes ?></strong> de <strong><?php echo $quantidade_cartoes ?></strong> cartões Fidelidade
					</p>
				</div>
				<div style="text-align: center; padding-top: 12px; border-top: 1px solid #e8e8e8;">
					<p style="margin: 0; font-size: 13px; color: #6c757d;">
						&copy; <?php echo date('Y') ?> <?php echo $nome_sistema ?>. Todos os direitos reservados.
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
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			

			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}


		showLeftPush2 = document.getElementById( 'showLeftPush2' ),
		
		showLeftPush2.onclick = function() {
			classie.toggle( this, 'active' );
			classie.toggle( body, 'cbp-spmenu-push-toright' );
			classie.toggle( menuLeft, 'cbp-spmenu-open' );
			disableOther2( 'showLeftPush2' );
		};


		function disableOther2( button ) {
			if( button !== 'showLeftPush2' ) {
				classie.toggle( showLeftPush2, 'disabled' );
			}
		}

		</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->


	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
		$('.sidebar-menu').SidebarNav()
	</script>
	<!-- //side nav js -->
	
	
	
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->
	
</body>
</html>





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
						<div class="col-md-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome-perfil" name="nome" placeholder="Nome" value="<?php echo $nome_usuario ?>" required>    
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
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Senha</label>
								<input type="password" class="form-control" id="senha-perfil" name="senha" placeholder="Senha" value="" required>    
							</div> 	
						</div>
						<div class="col-md-6">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Confirmar Senha</label>
								<input type="password" class="form-control" id="conf-senha-perfil" name="conf_senha" placeholder="Confirmar Senha" required>    
							</div> 	
						</div>

					

					</div>


					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço</label>
								<input type="text" class="form-control" id="endereco-perfil" name="endereco" placeholder="Rua X Número 1 Bairro xxx" value="<?php echo $endereco_usuario ?>" >    
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










