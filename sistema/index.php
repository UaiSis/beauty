<?php 
require_once("conexao.php");

//INSERIR UM USUÁRIO ADMINISTRADOR CASO NÃO EXISTA
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);

$query = $pdo->query("SELECT * from usuarios where nivel = 'Administrador'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg == 0){
	$pdo->query("INSERT INTO usuarios SET nome = 'Hugo Vasconcelos', email = 'contato@hugocursos.com.br', cpf = '000.000.000-00', senha = '', senha_crip = '$senha_crip', nivel = 'Administrador', data = curDate(), ativo = 'Sim', foto = 'sem-foto.jpg'");
}


$query = $pdo->query("SELECT * from cargos");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg == 0){
	$pdo->query("INSERT INTO cargos SET nome = 'Administrador'");
}


//EXCLUIR HORÁRIOS TEMPORÁRIOS
$pdo->query("DELETE FROM horarios where data < curDate() and data is not null ");


//APAGAR AGENDAENTOS ANTERIORES
$data_atual = date('Y-m-d');
$data_anterior = date('Y-m-d', strtotime("-$agendamento_dias days",strtotime($data_atual)));

$query = $pdo->query("SELECT * FROM agendamentos WHERE data < '$data_anterior'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
 for($i=0; $i < $total_reg; $i++){
    foreach ($res[$i] as $key => $value){}
        $id = $res[$i]['id'];
    	$pdo->query("DELETE FROM agendamentos WHERE id = '$id'");
}
}

 ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $nome_sistema ?> - Login</title>
	
	<!-- Bootstrap 4 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	
	<!-- Lucide Icons -->
	<script src="https://unpkg.com/lucide@latest"></script>
	
	<link rel="icon" type="image/png" href="img/favicon.ico">
</head>

<body class="login-body">
	<div class="login-split-container">
		<!-- Lado Esquerdo - Formulário -->
		<div class="login-left-panel">
			<div class="login-content-wrapper">
				<!-- Logo do Sistema Cliente -->
				<div class="login-logo-sistema">
					<img src="img/logo.png" alt="<?php echo $nome_sistema ?>" onerror="this.style.display='none'">
				</div>
				
				<!-- Welcome Section -->
				<div class="login-welcome">
					<h1 class="welcome-title">Bem-vindo a <?php echo $nome_sistema ?></h1>
					<p class="welcome-subtitle">Acesse sua conta para continuar</p>
				</div>

				<!-- Formulário -->
				<form class="login-form-modern" action="autenticar.php" method="post">
					<div class="form-group-modern">
						<label class="form-label-modern">E-mail ou CPF</label>
						<input 
							class="form-input-modern" 
							type="text" 
							name="email" 
							placeholder="Digite seu e-mail ou CPF" 
							value="<?php if($modo_teste == 'Sim'){ ?>contato@hugocursos.com.br<?php } ?>" 
							required 
							autocomplete="username"
						/>
					</div>
					
					<div class="form-group-modern">
						<label class="form-label-modern">Senha</label>
						<input 
							class="form-input-modern" 
							type="password" 
							name="senha" 
							placeholder="Digite sua senha" 
							value="<?php if($modo_teste == 'Sim'){ ?>123<?php } ?>" 
							required 
							autocomplete="current-password"
						/>
					</div>
					
					<button class="btn-login-modern" type="submit">
						Login
					</button>
				</form>
				
				<!-- Register Link -->
				<div class="login-register-link">
					<span class="register-text">Esqueceu sua senha?</span>
					<a href="#" class="register-link" data-toggle="modal" data-target="#modalRecuperar">Recuperar Senha</a>
				</div>
				
				<!-- Powered by UAI -->
				<div class="powered-by">
					<span>Powered by</span>
					<img src="../images/uai.png" alt="UAI Sistemas" onerror="this.style.display='none'">
				</div>
				
				<!-- Footer -->
				<div class="login-footer-brand">
					<p>UaiSistemas © <?php echo date('Y') ?></p>
				</div>
			</div>
		</div>
		
		<!-- Lado Direito - Imagem/Visual -->
		<div class="login-right-panel <?php echo ($fundo_login != "" and $fundo_login != "sem-foto.png") ? 'with-image' : 'default-bg'; ?>" <?php if($fundo_login != "" and $fundo_login != "sem-foto.png"){ ?>style="background-image: url('img/<?php echo $fundo_login ?>');"<?php } ?>>
			<div class="login-visual-overlay"></div>
			<div class="login-visual-content">
				<div class="visual-badge">
					<span> O sistema n°1 de Gestão para sua empresa</span>
				</div>
				<h2 class="visual-title">Gerencie seu negócio de forma profissional</h2>
				<p class="visual-subtitle">Agendamentos, clientes, serviços e muito mais em um só lugar</p>
				
				<!-- Features -->
				<div class="visual-features">
					<div class="feature-item">
						<div class="feature-icon">
							<i data-lucide="calendar-check"></i>
						</div>
						<div class="feature-text">
							<h4>Agendamentos Online</h4>
							<p>Gestão completa de horários</p>
						</div>
					</div>
					<div class="feature-item">
						<div class="feature-icon">
							<i data-lucide="users"></i>
						</div>
						<div class="feature-text">
							<h4>Controle de Clientes</h4>
							<p>Histórico e preferências</p>
						</div>
					</div>
					<div class="feature-item">
						<div class="feature-icon">
							<i data-lucide="trending-up"></i>
						</div>
						<div class="feature-text">
							<h4>Relatórios e Análises</h4>
							<p>Acompanhe seu crescimento</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

<style>
	/* Reset e Base */
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}
	
	.login-body {
		min-height: 100vh;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
		overflow: hidden;
	}
	
	/* Container Split Screen */
	.login-split-container {
		display: flex;
		min-height: 100vh;
	}
	
	/* Painel Esquerdo - Formulário */
	.login-left-panel {
		flex: 1;
		background: #FFFFFF;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 60px;
		overflow-y: auto;
	}
	
	.login-content-wrapper {
		width: 100%;
		max-width: 480px;
		animation: fadeInLeft 0.6s ease-out;
	}
	
	@keyframes fadeInLeft {
		from {
			opacity: 0;
			transform: translateX(-30px);
		}
		to {
			opacity: 1;
			transform: translateX(0);
		}
	}
	
	/* Logo do Sistema Cliente */
	.login-logo-sistema {
		margin-bottom: 48px;
		text-align: center;
	}
	
	.login-logo-sistema img {
		max-height: 100px;
		max-width: 280px;
		width: auto;
		height: auto;
	}
	
	/* Welcome Section */
	.login-welcome {
		margin-bottom: 40px;
	}
	
	.welcome-title {
		font-size: 42px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 12px;
		line-height: 1.2;
	}
	
	.welcome-subtitle {
		font-size: 17px;
		color: #666;
		font-weight: 400;
	}
	
	/* Divider */
	.login-divider {
		text-align: center;
		margin: 32px 0;
		position: relative;
	}
	
	.login-divider::before,
	.login-divider::after {
		content: '';
		position: absolute;
		top: 50%;
		width: 45%;
		height: 1px;
		background: #e0e0e0;
	}
	
	.login-divider::before {
		left: 0;
	}
	
	.login-divider::after {
		right: 0;
	}
	
	.login-divider span {
		background: #FFFFFF;
		padding: 0 16px;
		color: #999;
		font-size: 14px;
		font-weight: 500;
	}
	
	/* Formulário Moderno */
	.login-form-modern {
		display: flex;
		flex-direction: column;
		gap: 24px;
	}
	
	.form-group-modern {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}
	
	.form-label-modern {
		font-size: 15px;
		font-weight: 600;
		color: #333;
	}
	
	.form-input-modern {
		width: 100%;
		padding: 16px 18px;
		background: #f5f5f5;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		color: #1a1a1a;
		font-size: 16px;
		transition: all 0.3s ease;
		font-family: inherit;
	}
	
	.form-input-modern::placeholder {
		color: #999;
	}
	
	.form-input-modern:focus {
		outline: none;
		border-color: #1a1a1a;
		background: #FFFFFF;
		box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
	}
	
	/* Botão Login Moderno */
	.btn-login-modern {
		width: 100%;
		padding: 18px 28px;
		background: #1a1a1a;
		color: #FFFFFF;
		border: none;
		border-radius: 8px;
		font-size: 17px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
		margin-top: 12px;
	}
	
	.btn-login-modern:hover {
		background: #000000;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
	}
	
	.btn-login-modern:active {
		transform: translateY(0);
	}
	
	/* Register Link */
	.login-register-link {
		text-align: center;
		margin-top: 24px;
		font-size: 14px;
	}
	
	.register-text {
		color: #666;
		margin-right: 6px;
	}
	
	.register-link {
		color: #1a1a1a;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	
	.register-link:hover {
		color: #007A63;
		text-decoration: none;
	}
	
	/* Powered by UAI */
	.powered-by {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		margin-top: 40px;
		padding-top: 24px;
		border-top: 1px solid #e0e0e0;
	}
	
	.powered-by span {
		font-size: 13px;
		color: #999;
		font-weight: 500;
	}
	
	.powered-by img {
		height: 24px;
		width: auto;
	}
	
	/* Footer Brand */
	.login-footer-brand {
		text-align: center;
		margin-top: 16px;
	}
	
	.login-footer-brand p {
		font-size: 12px;
		color: #ccc;
		font-weight: 400;
	}
	
	/* Painel Direito - Visual */
	.login-right-panel {
		flex: 1;
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 60px;
		overflow: hidden;
	}
	
	.login-right-panel.with-image {
		background-size: cover;
		background-position: center center;
		background-repeat: no-repeat;
	}
	
	.login-right-panel.default-bg {
		background: linear-gradient(135deg, #006854 0%, #005246 100%);
	}
	
	.login-visual-overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: linear-gradient(135deg, rgba(0, 104, 84, 0.95) 0%, rgba(0, 82, 70, 0.97) 100%);
		z-index: 1;
	}
	
	.login-visual-content {
		position: relative;
		z-index: 2;
		max-width: 600px;
		animation: fadeInRight 0.8s ease-out;
	}
	
	@keyframes fadeInRight {
		from {
			opacity: 0;
			transform: translateX(30px);
		}
		to {
			opacity: 1;
			transform: translateX(0);
		}
	}
	
	/* Visual Badge */
	.visual-badge {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		background: rgba(185, 228, 212, 0.15);
		border: 1px solid rgba(185, 228, 212, 0.3);
		border-radius: 50px;
		color: #B9E4D4;
		font-size: 13px;
		font-weight: 600;
		margin-bottom: 24px;
	}
	
	.visual-badge i,
	.visual-badge svg {
		width: 16px;
		height: 16px;
		color: #FFFFFF !important;
	}
	
	.visual-badge svg path,
	.visual-badge svg rect,
	.visual-badge svg circle,
	.visual-badge svg line,
	.visual-badge svg polyline,
	.visual-badge svg polygon {
		stroke: #FFFFFF !important;
		fill: none !important;
	}
	
	.visual-title {
		font-size: 42px;
		font-weight: 800;
		color: #FEFEFE;
		line-height: 1.2;
		margin-bottom: 20px;
		letter-spacing: -1px;
	}
	
	.visual-subtitle {
		font-size: 17px;
		color: rgba(254, 254, 254, 0.85);
		line-height: 1.6;
		margin-bottom: 56px;
	}
	
	/* Visual Features */
	.visual-features {
		display: flex;
		flex-direction: column;
		gap: 24px;
	}
	
	.feature-item {
		display: flex;
		align-items: center;
		gap: 20px;
		padding: 28px;
		background: rgba(255, 255, 255, 0.05);
		backdrop-filter: blur(10px);
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: 16px;
		transition: all 0.3s ease;
	}
	
	.feature-item:hover {
		background: rgba(255, 255, 255, 0.08);
		border-color: rgba(185, 228, 212, 0.3);
		transform: translateX(8px);
	}
	
	.feature-icon {
		width: 48px;
		height: 48px;
		background: rgba(255, 255, 255, 0.15);
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}
	
	.feature-icon i,
	.feature-icon svg {
		width: 24px;
		height: 24px;
		color: #FFFFFF !important;
	}
	
	.feature-icon svg path,
	.feature-icon svg rect,
	.feature-icon svg circle,
	.feature-icon svg line,
	.feature-icon svg polyline,
	.feature-icon svg polygon {
		stroke: #FFFFFF !important;
		fill: none !important;
	}
	
	.feature-text {
		flex: 1;
	}
	
	.feature-text h4 {
		font-size: 19px;
		font-weight: 700;
		color: #FEFEFE;
		margin-bottom: 6px;
		line-height: 1.3;
	}
	
	.feature-text p {
		font-size: 15px;
		color: rgba(254, 254, 254, 0.7);
		margin: 0;
		line-height: 1.5;
	}
	
	/* Responsive */
	@media (max-width: 1024px) {
		.login-right-panel {
			padding: 40px;
		}
		
		.visual-title {
			font-size: 40px;
		}
		
		.visual-subtitle {
			font-size: 16px;
		}
	}
	
	@media (max-width: 768px) {
		.login-split-container {
			flex-direction: column;
		}
		
		.login-left-panel {
			max-width: 100%;
			padding: 32px 24px;
		}
		
		.login-right-panel {
			display: none;
		}
		
		.welcome-title {
			font-size: 28px;
		}
		
		.login-logo-sistema img {
			max-height: 60px;
			max-width: 200px;
		}
		
		.powered-by img {
			height: 20px;
		}
	}
	
	@media (max-width: 480px) {
		.login-left-panel {
			padding: 24px 20px;
		}
		
		.login-content-wrapper {
			max-width: 100%;
		}
		
		.welcome-title {
			font-size: 24px;
		}
		
		.welcome-subtitle {
			font-size: 14px;
		}
		
		.form-input-modern {
			padding: 12px 14px;
			font-size: 14px;
		}
		
		.btn-login-modern {
			padding: 14px 20px;
			font-size: 15px;
		}
	}
</style>

<script>
	// Inicializa ícones Lucide
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>

</html>




<!-- Modal Recuperar Senha Moderno -->
<div class="modal fade" id="modalRecuperar" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content modal-modern-login">
			<div class="modal-header-modern-login">
				<div>
					<div class="modal-badge-login">// RECUPERAR SENHA</div>
					<h3 class="modal-title-modern-login">Esqueceu sua senha?</h3>
					<p class="modal-subtitle-login">Digite seu e-mail para receber instruções</p>
				</div>
				<button type="button" class="btn-close-modern-login" data-dismiss="modal" aria-label="Close">
					<i data-lucide="x"></i>
				</button>
			</div>
			
			<form method="post" id="form-recuperar">
				<div class="modal-body-modern-login">
					<div class="form-group-modal-login">
						<label class="label-modal-login">
							<i data-lucide="mail"></i>
							<span>E-mail</span>
						</label>
						<input 
							placeholder="Digite seu e-mail cadastrado" 
							class="input-modal-login" 
							type="email" 
							name="email" 
							id="email-recuperar" 
							required
						/>
					</div>
					
					<div id="mensagem-recuperar" class="mensagem-modal-login"></div>
				</div>
				
				<div class="modal-footer-modern-login">
					<button type="button" class="btn-cancel-modal-login" data-dismiss="modal">
						Cancelar
					</button>
					<button type="submit" class="btn-submit-modal-login">
						<i data-lucide="send"></i>
						<span>Enviar</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
	/* Modal Moderno - Login */
	.modal-modern-login {
		border-radius: 24px;
		border: none;
		background: #1a1a1a;
		overflow: hidden;
	}
	
	.modal-header-modern-login {
		background: linear-gradient(135deg, #006854 0%, #005246 100%);
		padding: 36px 40px;
		border: none;
		display: flex;
		justify-content: space-between;
		align-items: start;
	}
	
	.modal-badge-login {
		font-size: 11px;
		font-weight: 700;
		color: rgba(185, 228, 212, 0.9);
		text-transform: uppercase;
		letter-spacing: 1.5px;
		margin-bottom: 12px;
	}
	
	.modal-title-modern-login {
		font-size: 28px;
		font-weight: 800;
		color: #FEFEFE;
		margin-bottom: 8px;
		letter-spacing: -0.5px;
	}
	
	.modal-subtitle-login {
		font-size: 14px;
		color: rgba(254, 254, 254, 0.7);
		margin: 0;
	}
	
	.btn-close-modern-login {
		background: rgba(255, 255, 255, 0.1);
		border: none;
		width: 40px;
		height: 40px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.3s ease;
		padding: 0;
	}
	
	.btn-close-modern-login i {
		width: 20px;
		height: 20px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}
	
	.btn-close-modern-login:hover {
		background: rgba(255, 255, 255, 0.2);
		transform: rotate(90deg);
	}
	
	.modal-body-modern-login {
		padding: 40px;
		background: #1a1a1a;
	}
	
	.form-group-modal-login {
		display: flex;
		flex-direction: column;
		gap: 10px;
		margin-bottom: 24px;
	}
	
	.label-modal-login {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 13px;
		font-weight: 600;
		color: rgba(254, 254, 254, 0.8);
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	.label-modal-login i {
		width: 16px;
		height: 16px;
		color: #007A63;
		stroke: #007A63;
	}
	
	.input-modal-login {
		width: 100%;
		padding: 14px 18px;
		background: #252525;
		border: 1px solid rgba(255, 255, 255, 0.1);
		border-radius: 12px;
		color: #FEFEFE;
		font-size: 15px;
		transition: all 0.3s ease;
		font-family: inherit;
	}
	
	.input-modal-login::placeholder {
		color: rgba(254, 254, 254, 0.4);
	}
	
	.input-modal-login:focus {
		outline: none;
		border-color: #007A63;
		background: #2a2a2a;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}
	
	.mensagem-modal-login {
		margin-top: 20px;
		padding: 12px 16px;
		border-radius: 8px;
		font-size: 14px;
		font-weight: 600;
		text-align: center;
		display: none;
	}
	
	.mensagem-modal-login.text-success {
		display: block;
		background: rgba(0, 122, 99, 0.15);
		color: #00d896;
		border: 1px solid rgba(0, 122, 99, 0.3);
	}
	
	.mensagem-modal-login.text-danger {
		display: block;
		background: rgba(220, 53, 69, 0.15);
		color: #ff6b6b;
		border: 1px solid rgba(220, 53, 69, 0.3);
	}
	
	.modal-footer-modern-login {
		padding: 28px 40px;
		background: #1a1a1a;
		border-top: 1px solid rgba(255, 255, 255, 0.05);
		display: flex;
		gap: 12px;
		justify-content: flex-end;
	}
	
	.btn-cancel-modal-login {
		padding: 14px 28px;
		background: transparent;
		color: rgba(254, 254, 254, 0.7);
		border: 1px solid rgba(255, 255, 255, 0.2);
		border-radius: 12px;
		font-weight: 600;
		font-size: 15px;
		cursor: pointer;
		transition: all 0.3s ease;
	}
	
	.btn-cancel-modal-login:hover {
		background: rgba(255, 255, 255, 0.05);
		color: #FEFEFE;
		border-color: rgba(255, 255, 255, 0.3);
	}
	
	.btn-submit-modal-login {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		padding: 14px 28px;
		background: #007A63;
		color: #FEFEFE;
		border: none;
		border-radius: 12px;
		font-weight: 600;
		font-size: 15px;
		cursor: pointer;
		transition: all 0.3s ease;
	}
	
	.btn-submit-modal-login:hover {
		background: #006654;
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(0, 122, 99, 0.3);
	}
	
	.btn-submit-modal-login i {
		width: 18px;
		height: 18px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}
	
	/* Responsive Modal */
	@media (max-width: 576px) {
		.modal-header-modern-login {
			padding: 28px 24px;
		}
		
		.modal-title-modern-login {
			font-size: 24px;
		}
		
		.modal-body-modern-login {
			padding: 28px 24px;
		}
		
		.modal-footer-modern-login {
			padding: 20px 24px;
			flex-direction: column-reverse;
		}
		
		.btn-cancel-modal-login,
		.btn-submit-modal-login {
			width: 100%;
			justify-content: center;
		}
	}
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
	$("#form-recuperar").submit(function () {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "recuperar-senha.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-recuperar').text('');
				$('#mensagem-recuperar').removeClass()
				if (mensagem.trim() == "Recuperado com Sucesso") {
					$('#email-recuperar').val('');
					$('#mensagem-recuperar').addClass('text-success')
					$('#mensagem-recuperar').text('Sua senha foi enviada para o e-mail')
				} else {
					$('#mensagem-recuperar').addClass('text-danger')
					$('#mensagem-recuperar').text(mensagem)
				}
			},

			cache: false,
			contentType: false,
			processData: false,
		});
	});
	
	// Atualiza ícones Lucide quando o modal é aberto
	$('#modalRecuperar').on('shown.bs.modal', function() {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
</script>



