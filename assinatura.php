<?php 
require_once("cabecalho.php");
$data_atual = date('Y-m-d');
?>
<style type="text/css">
	.sub_page .hero_area {
		min-height: auto;
	}
</style>

</div>

<!-- Hero Section Assinaturas -->
<section class="subscription-hero">
	<div class="subscription-hero-content">
		<div class="hero-badge-sub">// ASSINATURAS VIP</div>
		<h1 class="hero-title-sub">
			Escolha um Plano e Faça Parte da <span class="highlight-sub">Assinatura!</span>
		</h1>
		<p class="hero-subtitle-sub">
			✂️ Assinatura VIP para Estilo Incomparável!
		</p>
		<div class="hero-description-sub">
			<p>Na <?php echo $nome_sistema ?>, acreditamos que estilo e cuidado são inseparáveis. Apresentamos as Assinaturas Exclusivas para quem busca uma experiência premium.</p>
			<p>Primeiro serviço por assinatura de tratamentos capilares, manutenção de prótese, corte de cabelo, barba, bigode, sobrancelha e manicure-pedicure.</p>
		</div>
	</div>
</section>

<style>
	/* Hero Assinaturas */
	.subscription-hero {
		background: linear-gradient(135deg, #006854 0%, #005246 100%);
		padding: 120px 0 80px;
		text-align: center;
		position: relative;
		overflow: hidden;
	}

	.subscription-hero::before {
		content: '';
		position: absolute;
		top: -50%;
		right: -10%;
		width: 600px;
		height: 600px;
		background: radial-gradient(circle, rgba(185, 228, 212, 0.1) 0%, transparent 70%);
		pointer-events: none;
	}

	.subscription-hero-content {
		max-width: 900px;
		margin: 0 auto;
		padding: 0 40px;
		position: relative;
		z-index: 2;
	}

	.hero-badge-sub {
		display: inline-block;
		padding: 8px 24px;
		background: rgba(185, 228, 212, 0.15);
		border: 1px solid rgba(185, 228, 212, 0.3);
		border-radius: 50px;
		color: #B9E4D4;
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 1.5px;
		margin-bottom: 28px;
		backdrop-filter: blur(10px);
	}

	.hero-title-sub {
		font-size: 64px;
		font-weight: 900;
		color: #FEFEFE;
		line-height: 1.1;
		margin-bottom: 24px;
		letter-spacing: -2px;
	}

	.highlight-sub {
		color: #B9E4D4;
	}

	.hero-subtitle-sub {
		font-size: 24px;
		color: rgba(254, 254, 254, 0.9);
		margin-bottom: 32px;
		font-weight: 600;
	}

	.hero-description-sub {
		font-size: 17px;
		color: rgba(254, 254, 254, 0.8);
		line-height: 1.8;
		max-width: 800px;
		margin: 0 auto;
	}

	.hero-description-sub p {
		margin-bottom: 16px;
	}

	@media (max-width: 768px) {
		.subscription-hero {
			padding: 80px 0 60px;
		}

		.hero-title-sub {
			font-size: 38px;
		}

		.hero-subtitle-sub {
			font-size: 20px;
		}

		.hero-description-sub {
			font-size: 16px;
		}
	}
</style>


<!-- Seção de Planos Modernos -->
<?php 
$query5 = $pdo->query("SELECT * FROM grupo_assinaturas where ativo = 'Sim' ORDER BY id asc");
$res5 = $query5->fetchAll(PDO::FETCH_ASSOC);
$total_reg5 = @count($res5);

if($total_reg5 > 0){
	for($i5=0; $i5 < $total_reg5; $i5++){
		$id_grupo = $res5[$i5]['id'];
		$nome_grupo = $res5[$i5]['nome'];	
		
		// Busca itens do grupo
		$query = $pdo->query("SELECT * FROM itens_assinaturas where grupo = '$id_grupo' and ativo = 'Sim' ORDER BY valor ASC");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = @count($res);
		
		if($total_reg > 0){
?>

<section class="plans-section">
	<div class="plans-container">
		<h2 class="plans-group-title"><?php echo $nome_grupo ?></h2>
		
		<div class="plans-grid">
			<?php 
			$plan_index = 0;
			for($i=0; $i < $total_reg; $i++){
				$id_item = $res[$i]['id'];
				$nome_item = $res[$i]['nome'];	
				$valor = $res[$i]['valor'];	
				$c1 = $res[$i]['c1'];
				$c2 = $res[$i]['c2'];
				$c3 = $res[$i]['c3'];
				$c4 = $res[$i]['c4'];
				$c5 = $res[$i]['c5'];
				$c6 = $res[$i]['c6'];
				$c7 = $res[$i]['c7'];
				$c8 = $res[$i]['c8'];
				$c9 = $res[$i]['c9'];
				$c10 = $res[$i]['c10'];
				$c11 = $res[$i]['c11'];
				$c12 = $res[$i]['c12'];

				if ((float)$valor - floor($valor) > 0) {
					$valorF = number_format($valor, 2, ',', '.');
				} else {
					$valorF = number_format($valor, 0, ',', '.');
				}
				
				$is_popular = ($plan_index == 1);
				$plan_index++;
			?>
			
			<div class="plan-card <?php echo $is_popular ? 'popular' : ''; ?>">
				<?php if($is_popular){ ?>
				<div class="plan-badge-popular">Mais Popular</div>
				<?php } ?>
				
				<div class="plan-header">
					<h3 class="plan-name"><?php echo $nome_item ?></h3>
					<div class="plan-price">
						<span class="currency">R$</span>
						<span class="value"><?php echo $valorF ?></span>
						<span class="period">/mês</span>
					</div>
				</div>

				<div class="plan-features">
					<ul>
						<?php if($c1 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c1 ?></span>
						</li>
						<?php } ?>
						<?php if($c2 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c2 ?></span>
						</li>
						<?php } ?>
						<?php if($c3 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c3 ?></span>
						</li>
						<?php } ?>
						<?php if($c4 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c4 ?></span>
						</li>
						<?php } ?>
						<?php if($c5 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c5 ?></span>
						</li>
						<?php } ?>
						<?php if($c6 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c6 ?></span>
						</li>
						<?php } ?>
						<?php if($c7 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c7 ?></span>
						</li>
						<?php } ?>
						<?php if($c8 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c8 ?></span>
						</li>
						<?php } ?>
						<?php if($c9 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c9 ?></span>
						</li>
						<?php } ?>
						<?php if($c10 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c10 ?></span>
						</li>
						<?php } ?>
						<?php if($c11 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c11 ?></span>
						</li>
						<?php } ?>
						<?php if($c12 != ""){ ?>
						<li>
							<i data-lucide="check"></i>
							<span><?php echo $c12 ?></span>
						</li>
						<?php } ?>
					</ul>
				</div>

				<div class="plan-action">
					<form action="assinar.php" method="POST" target="_blank">
						<input type="hidden" name="id_item" value="<?php echo $id_item ?>">
						<button class="btn-subscribe" type="submit">
							<i data-lucide="sparkles"></i>
							<span>Assinar Agora</span>
						</button>
					</form>
				</div>
			</div>
			
			<?php } ?>
		</div>
	</div>
</section>

<style>
	/* Plans Section */
	.plans-section {
		padding: 80px 0;
		background: #f3ede0;
	}

	.plans-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 0 40px;
	}

	.plans-group-title {
		text-align: center;
		font-size: 42px;
		font-weight: 800;
		color: #1a1a1a;
		margin-bottom: 60px;
		letter-spacing: -1px;
	}

	.plans-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
		gap: 32px;
		max-width: 1200px;
		margin: 0 auto;
	}

	.plan-card {
		background: #FEFEFE;
		border-radius: 24px;
		padding: 40px 32px;
		border: 3px solid transparent;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		position: relative;
		display: flex;
		flex-direction: column;
	}

	.plan-card:hover {
		transform: translateY(-12px);
		border-color: #007A63;
		box-shadow: 0 20px 60px rgba(0, 122, 99, 0.2);
	}

	.plan-card.popular {
		border-color: #007A63;
		box-shadow: 0 16px 48px rgba(0, 122, 99, 0.15);
	}

	.plan-badge-popular {
		position: absolute;
		top: -16px;
		left: 50%;
		transform: translateX(-50%);
		padding: 8px 24px;
		background: linear-gradient(135deg, #007A63 0%, #006854 100%);
		color: #FEFEFE;
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		border-radius: 50px;
		letter-spacing: 1px;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.3);
	}

	.plan-header {
		text-align: center;
		padding-bottom: 32px;
		border-bottom: 2px solid #f0f0f0;
		margin-bottom: 32px;
	}

	.plan-name {
		font-size: 28px;
		font-weight: 800;
		color: #1a1a1a;
		margin-bottom: 24px;
		letter-spacing: -0.5px;
	}

	.plan-price {
		display: flex;
		align-items: baseline;
		justify-content: center;
		gap: 4px;
	}

	.currency {
		font-size: 24px;
		font-weight: 700;
		color: #007A63;
	}

	.value {
		font-size: 56px;
		font-weight: 900;
		color: #007A63;
		line-height: 1;
	}

	.period {
		font-size: 16px;
		color: #666;
		font-weight: 500;
	}

	.plan-features {
		flex: 1;
		margin-bottom: 32px;
	}

	.plan-features ul {
		list-style: none;
		padding: 0;
		margin: 0;
		display: flex;
		flex-direction: column;
		gap: 16px;
	}

	.plan-features li {
		display: flex;
		align-items: center;
		gap: 12px;
		font-size: 15px;
		color: #555;
		line-height: 1.5;
	}

	.plan-features li i {
		width: 20px;
		height: 20px;
		color: #007A63;
		stroke: #007A63;
		flex-shrink: 0;
		stroke-width: 2.5px;
	}

	.plan-action {
		margin-top: auto;
	}

	.plan-action form {
		margin: 0;
	}

	.btn-subscribe {
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		padding: 18px 32px;
		background: #007A63 !important;
		color: #FEFEFE !important;
		border: none;
		border-radius: 12px;
		font-size: 16px;
		font-weight: 700;
		cursor: pointer;
		transition: all 0.3s ease;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.btn-subscribe:hover {
		background: #006654 !important;
		transform: translateY(-2px);
		box-shadow: 0 8px 24px rgba(0, 122, 99, 0.4);
	}

	.btn-subscribe i {
		width: 20px;
		height: 20px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	.plan-card.popular .btn-subscribe {
		background: linear-gradient(135deg, #007A63 0%, #006854 100%) !important;
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
	}

	@media (max-width: 768px) {
		.plans-section {
			padding: 60px 0;
		}

		.plans-container {
			padding: 0 20px;
		}

		.plans-group-title {
			font-size: 32px;
			margin-bottom: 40px;
		}

		.plans-grid {
			grid-template-columns: 1fr;
			gap: 24px;
		}

		.plan-card {
			padding: 32px 24px;
		}

		.plan-name {
			font-size: 24px;
		}

		.value {
			font-size: 48px;
		}
	}
</style>

<script>
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>

<?php } } } ?>
				
				


<!-- FAQ Section Modern -->
<section class="faq-section-modern">
	<div class="faq-container">
		<!-- Header -->
		<div class="faq-header">
			<div class="faq-badge">DÚVIDAS</div>
			<h2 class="faq-title">
				Perguntas <span class="faq-highlight">Frequentes</span>
			</h2>
			<p class="faq-description">
				Separamos as dúvidas mais comuns para você. Se não encontrar a resposta, é só chamar a gente!
			</p>
		</div>

		<!-- FAQ Grid -->
		<div class="faq-grid">
			<div class="faq-column">
				<div id="accordion" class="faq-accordion">
					<div class="faq-item-modern">
						<button class="faq-question" data-toggle="collapse" data-target="#faq1">
							<span>Como funciona exatamente?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq1" class="collapse faq-answer">
							<p>A assinatura recorrente para o salão de beleza. Ou seja, com um plano mensal, trimestral, semestral, você faz manutenção da sua prótese capilar, trata seu cabelo, corta o cabelo e faz a barba e sobrancelha com descontos exclusivos! Você impecável sempre!</p>
						</div>
					</div>

					<div class="faq-item-modern">
						<button class="faq-question collapsed" data-toggle="collapse" data-target="#faq2">
							<span>Benefícios na assinatura</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq2" class="collapse faq-answer">
							<p>Presentes exclusivos. Cabelo sempre alinhado! Fazer tratamento, manutenção de prótese capilar e barba em casa nunca mais! Imagem pessoal impecável. Pagamento automatizado. Desconto nos produtos e serviços. <a href="agendamentos">Agenda Online</a></p>
						</div>
					</div>

					<div class="faq-item-modern">
						<button class="faq-question collapsed" data-toggle="collapse" data-target="#faq3">
							<span>Existe um limite de vezes que posso utilizar por mês?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq3" class="collapse faq-answer">
							<p>Conforme seu combo! Acreditamos que sua imagem pessoal vem em primeiro lugar, e nós estamos aqui para isso, prestar o melhor serviço quantas vezes for necessário.</p>
						</div>
					</div>

					<div class="faq-item-modern">
						<button class="faq-question collapsed" data-toggle="collapse" data-target="#faq7">
							<span>Tem taxa de adesão e fidelidade na contratação?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq7" class="collapse faq-answer">
							<p>Sem cobranças adicionais, aqui você é livre! Afinal queremos você em nosso clube que compartilha do mesmo propósito que nós, apresentar nossa melhor versão sempre!</p>
						</div>
					</div>
				</div>
			</div>

			<div class="faq-column">
				<div id="accordion2" class="faq-accordion">
					<div class="faq-item-modern">
						<button class="faq-question" data-toggle="collapse" data-target="#faq4">
							<span>Eu tenho algum desconto além do plano contratado?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq4" class="collapse faq-answer">
							<p>Sim, os clientes ocupam posição privilegiada em tudo! Damos desconto em todos os produtos comercializados em nossas unidades, tais como pomada, óleo, balm e atendimento exclusivos.</p>
						</div>
					</div>

					<div class="faq-item-modern">
						<button class="faq-question collapsed" data-toggle="collapse" data-target="#faq5">
							<span>É só pelo site que assino?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq5" class="collapse faq-answer">
							<p>Não, você pode assinar no salão de beleza. Procure o gerente e receba todo o suporte e instrução para entrar no grupo seleto que preza por estilo e elegância a todo momento.</p>
						</div>
					</div>

					<div class="faq-item-modern">
						<button class="faq-question collapsed" data-toggle="collapse" data-target="#faq6">
							<span>Existe outra forma de pagamento que não seja cartão?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq6" class="collapse faq-answer">
							<p>Sim, disponibilizamos combos mensal, trimestrais e semestral de todos os planos. Esses combos são pagos via débito, pix, dinheiro e o cliente que adere por este modo usufrui das mesmas vantagens que os demais, alterando apenas a forma de pagamento.</p>
						</div>
					</div>

					<div class="faq-item-modern">
						<button class="faq-question collapsed" data-toggle="collapse" data-target="#faq8">
							<span>Se eu tiver dúvidas?</span>
							<i data-lucide="plus" class="faq-icon"></i>
						</button>
						<div id="faq8" class="collapse faq-answer">
							<p>Você pode tirar todas as dúvidas via e-mail ou WhatsApp sempre que precisar. Respondemos todas as suas questões relacionadas à sua agenda. <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" class="faq-link">Fale no WhatsApp</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<style>
	/* FAQ Section Modern */
	.faq-section-modern {
		padding: 100px 0;
		background: #007A63;
	}

	.faq-container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 40px;
	}

	.faq-header {
		text-align: center;
		margin-bottom: 70px;
	}

	.faq-badge {
		font-size: 12px;
		font-weight: 700;
		color: rgba(185, 228, 212, 0.9);
		text-transform: uppercase;
		letter-spacing: 2px;
		margin-bottom: 20px;
	}

	.faq-title {
		font-size: 48px;
		font-weight: 900;
		color: #FEFEFE;
		margin-bottom: 20px;
		letter-spacing: -1px;
	}

	.faq-highlight {
		color: #B9E4D4;
	}

	.faq-description {
		font-size: 17px;
		color: rgba(254, 254, 254, 0.8);
		line-height: 1.7;
		max-width: 700px;
		margin: 0 auto;
	}

	.faq-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 32px;
	}

	.faq-accordion {
		display: flex;
		flex-direction: column;
		gap: 16px;
	}

	.faq-item-modern {
		background: #B9E4D4;
		border-radius: 16px;
		overflow: hidden;
		transition: all 0.3s ease;
	}

	.faq-item-modern:hover {
		box-shadow: 0 8px 24px rgba(0, 122, 99, 0.15);
	}

	.faq-question {
		width: 100%;
		padding: 24px 28px;
		background: transparent;
		border: none;
		text-align: left;
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 20px;
		cursor: pointer;
		transition: all 0.3s ease;
		color: #1a1a1a;
		font-size: 16px;
		font-weight: 600;
		line-height: 1.4;
	}

	.faq-question:hover {
		background: rgba(0, 122, 99, 0.1);
	}

	.faq-icon {
		width: 22px;
		height: 22px;
		color: #007A63;
		stroke: #007A63;
		flex-shrink: 0;
		transition: transform 0.3s ease;
		stroke-width: 2.5px;
	}

	.faq-question:not(.collapsed) .faq-icon {
		transform: rotate(45deg);
	}

	.faq-answer {
		padding: 0 28px 28px;
		background: #B9E4D4;
	}

	.faq-answer p {
		font-size: 15px;
		color: #333;
		line-height: 1.7;
		margin: 0;
	}

	.faq-link {
		color: #007A63;
		font-weight: 600;
		text-decoration: underline;
		transition: color 0.3s ease;
	}

	.faq-link:hover {
		color: #006654;
	}

	@media (max-width: 768px) {
		.faq-section-modern {
			padding: 80px 0;
		}

		.faq-container {
			padding: 0 20px;
		}

		.faq-title {
			font-size: 36px;
		}

		.faq-description {
			font-size: 16px;
		}

		.faq-grid {
			grid-template-columns: 1fr;
			gap: 16px;
		}

		.faq-question {
			padding: 20px 24px;
			font-size: 15px;
		}

		.faq-answer {
			padding: 0 24px 24px;
		}
	}
</style>

<script>
	// Atualiza ícones quando accordion abre/fecha
	$('.faq-question').on('click', function() {
		setTimeout(function() {
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}
		}, 50);
	});

	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>

		<?php require_once("rodape.php") ?>



			<!-- Modal Depoimentos -->
			<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Excluir Agendamento
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px" id="btn-fechar-excluir">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<form id="form-excluir">
							<div class="modal-body">

								<span id="msg-excluir"></span>

								<input type="hidden" name="id" id="id_excluir">


								<br>
								<small><div id="mensagem-excluir" align="center"></div></small>
							</div>

							<div class="modal-footer">      
								<button type="submit" class="btn btn-danger">Excluir</button>
							</div>
						</form>

					</div>
				</div>
			</div>






			<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

			<style type="text/css">
				.select2-selection__rendered {
					line-height: 45px !important;
					font-size:16px !important;
					color:#000 !important;

				}

				.select2-selection {
					height: 45px !important;
					font-size:16px !important;
					color:#000 !important;

				}
			</style>  



			<script type="text/javascript">
				$(document).ready(function() {
					document.getElementById("botao_editar").style.display = "none";		
					$('.sel2').select2({

					});

					listarFuncionarios();
				});
			</script>


			<script type="text/javascript">

				function mudarFuncionario(){
					var funcionario = $('#funcionario').val();
					var data = $('#data').val();		
					var hora = $('#hora_rec').val();

					listarHorarios(funcionario, data, hora);
					listarFuncionario();	

				}
			</script>



			<script type="text/javascript">
				function listarHorarios(funcionario, data, hora){	


					$.ajax({
						url: "ajax/listar-horarios.php",
						method: 'POST',
						data: {funcionario, data, hora},
						dataType: "text",

						success:function(result){
							if(result.trim() === '000'){
								alert('Selecione uma data igual ou maior que hoje!');

								var dt = new Date();
								var dia = String(dt.getDate()).padStart(2, '0');
								var mes = String(dt.getMonth() + 1).padStart(2, '0');
								var ano = dt.getFullYear();
								dataAtual = ano + '-' + mes + '-' + dia;
								$('#data').val(dataAtual);
								return;
							}else{
								$("#listar-horarios").html(result);
							}

						}
					});
				}
			</script>



			<script type="text/javascript">

				function buscarNome(){
					var tel = $('#telefone').val();	
					listarCartoes(tel);	

					$.ajax({
						url: "ajax/listar-nome.php",
						method: 'POST',
						data: {tel},
						dataType: "text",

						success:function(result){
							var split = result.split("*");
							console.log(split[3])

							if(split[2] == "" || split[2] == undefined){

							}else{
								$("#funcionario").val(parseInt(split[2])).change();
							}


							if(split[5] == "" || split[5] == undefined){
								document.getElementById("botao_editar").style.display = "none";					
							}else{
								$("#servico").val(parseInt(split[5])).change();
								document.getElementById("botao_editar").style.display = "block";					
								$("#botao_salvar").text('Novo Agendamento');
							}

							$("#nome").val(split[0]);


							$("#msg-excluir").text('Deseja Realmente excluir esse agendamento feito para o dia ' + split[7] + ' às ' + split[4]);


							mudarFuncionario()



						}
					});	




				}
			</script>



			<script type="text/javascript">

				function salvar(){
					$('#id').val('');
				}
			</script>




			<script>

				$("#form-agenda").submit(function () {
					event.preventDefault();

					$('#btn_agendar').hide();
					$('#mensagem').text('Carregando!');

					var formData = new FormData(this);

					$.ajax({
						url: "ajax/agendar.php",
						type: 'POST',
						data: formData,

						success: function (mensagem) {

							$('#mensagem').text('');
							$('#mensagem').removeClass()
							if (mensagem.trim() == "Agendado com Sucesso") {                    
								$('#mensagem').text(mensagem)
								buscarNome()

								var nome = $('#nome').val();
								var data = $('#data').val();
								var hora = document.querySelector('input[name="hora"]:checked').value;
								var obs = $('#obs').val();
								var nome_func = $('#nome_func').val();
								var nome_serv = $('#nome_serv').val();

								var dataF = data.split("-");
								var dia = dataF[2];
								var mes = dataF[1];
								var ano = dataF[0];
								var dataFormatada = dia + '/' + mes + '/' + ano;

								var horaF = hora.split(':');
								var horaH = horaF[0];
								var horaM = horaF[1];
								var horaFormatada = horaH + ':' + horaM;


								window.location="meus-agendamentos.php";	

								var msg_agendamento = "<?=$msg_agendamento?>";

								if(msg_agendamento == 'Sim'){

									let a= document.createElement('a');
									a.target= '_blank';
									a.href= 'http://api.whatsapp.com/send?1=pt_BR&phone=<?=$tel_whatsapp?>&text= _Novo Agendamento_ %0A Funcionário: *' + nome_func + '* %0A Serviço: *' + nome_serv + '* %0A Data: *' + dataFormatada + '* %0A Hora: *' + horaFormatada + '* %0A Cliente: *' + nome + '*  %0A %0A ' + obs;
									a.click();
									return;		

								}


							}



							else {
					//$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}

				$('#btn_agendar').show();

			},

			cache: false,
			contentType: false,
			processData: false,

		});

				});

			</script>





			<script type="text/javascript">

				function listarCartoes(tel){

					$.ajax({
						url: "ajax/listar-cartoes.php",
						method: 'POST',
						data: {tel},
						dataType: "text",

						success:function(result){
							$("#listar-cartoes").html(result);
						}
					});

				}
			</script>





			<script type="text/javascript">
				function listarFuncionario(){	
					var func = $("#funcionario").val();

					$.ajax({
						url: "ajax/listar-funcionario.php",
						method: 'POST',
						data: {func},
						dataType: "text",

						success:function(result){
							$("#nome_func").val(result);
						}
					});
				}
			</script>


			<script type="text/javascript">
				function mudarServico(){
					listarFuncionarios()	
					var serv = $("#servico").val();

					$.ajax({
						url: "ajax/listar-servico.php",
						method: 'POST',
						data: {serv},
						dataType: "text",

						success:function(result){
							$("#nome_serv").val(result);
						}
					});
				}
			</script>


			<script type="text/javascript">
				function listarFuncionarios(){	
					var serv = $("#servico").val();

					$.ajax({
						url: "ajax/listar-funcionarios.php",
						method: 'POST',
						data: {serv},
						dataType: "text",

						success:function(result){
							$("#funcionario").html(result);
						}
					});
				}
			</script>

