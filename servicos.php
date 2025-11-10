<?php require_once("cabecalho.php") ?>
<style type="text/css">
	.sub_page .hero_area {
		min-height: auto;
	}
</style>

</div>

<?php 
// Busca categorias de serviços
$query = $pdo->query("SELECT * FROM cat_servicos ORDER BY id asc");
$res_cat = $query->fetchAll(PDO::FETCH_ASSOC);
$categorias = [];
foreach($res_cat as $cat) {
	$categorias[] = $cat['nome'];
}

// Busca serviços ativos
$query = $pdo->query("SELECT * FROM servicos where ativo = 'Sim' ORDER BY id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$servicos = [];
foreach($res as $servico) {
	$servicos[] = [
		'id' => $servico['id'],
		'nome' => $servico['nome'],
		'valor' => $servico['valor'],
		'foto' => $servico['foto']
	];
}

if(count($servicos) > 0){ 
?>

<!-- Hero Section Serviços -->
<section class="services-hero">
	<div class="services-hero-content">
		<div class="hero-badge-services">// SERVIÇOS</div>
		<h1 class="hero-title-services">
			Nossos <span class="highlight-services">Serviços</span>
		</h1>
		<p class="hero-subtitle-services">
			<?php echo implode(' • ', $categorias); ?>
		</p>
		<p class="hero-description-services">
			Explore nossa completa linha de serviços profissionais. Cada atendimento é uma experiência única focada em realçar seu estilo e personalidade.
		</p>
	</div>
</section>

<!-- Serviços Grid -->
<section class="services-catalog">
	<div class="catalog-services-container" id="services-catalog-app" 
		 data-services='<?php echo json_encode($servicos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
		
		<!-- Grid de Serviços -->
		<div v-if="services.length > 0" class="services-catalog-grid">
			<div v-for="service in services" :key="service.id" class="service-catalog-card">
				<div class="service-catalog-inner">
					<div class="service-catalog-image">
						<img :src="'sistema/painel/img/servicos/' + service.foto" :alt="service.nome" class="catalog-service-img">
						<div class="service-catalog-overlay">
							<a href="agendamentos" class="catalog-schedule-link">
								<i data-lucide="calendar-check"></i>
								<span>Agendar</span>
							</a>
						</div>
					</div>
					<div class="service-catalog-content">
						<h3 class="service-catalog-title">{{ service.nome }}</h3>
						<div class="service-catalog-footer">
							<div class="service-catalog-price">
								<span class="catalog-service-label">A partir de</span>
								<span class="catalog-service-value">R$ {{ formatPrice(service.valor) }}</span>
							</div>
							<a href="agendamentos" class="catalog-service-arrow">
								<i data-lucide="arrow-right"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Empty State -->
		<div v-else class="empty-state-services">
			<div class="empty-icon">
				<i data-lucide="scissors"></i>
			</div>
			<h3 class="empty-title">Nenhum Serviço Disponível</h3>
			<p class="empty-description">
				No momento não há serviços cadastrados. Em breve teremos novidades para você!
			</p>
			<a href="index" class="btn-empty-home">
				<i data-lucide="home"></i>
				<span>Voltar para Home</span>
			</a>
		</div>
	</div>
</section>

<style>
	/* Hero Serviços */
	.services-hero {
		background: linear-gradient(135deg, #006854 0%, #005246 100%);
		padding: 120px 0 80px;
		text-align: center;
		position: relative;
		overflow: hidden;
	}

	.services-hero::before {
		content: '';
		position: absolute;
		top: -50%;
		right: -10%;
		width: 600px;
		height: 600px;
		background: radial-gradient(circle, rgba(185, 228, 212, 0.1) 0%, transparent 70%);
		pointer-events: none;
	}

	.services-hero-content {
		max-width: 900px;
		margin: 0 auto;
		padding: 0 40px;
		position: relative;
		z-index: 2;
	}

	.hero-badge-services {
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
	}

	.hero-title-services {
		font-size: 64px;
		font-weight: 900;
		color: #FEFEFE;
		line-height: 1.1;
		margin-bottom: 24px;
		letter-spacing: -2px;
	}

	.highlight-services {
		color: #B9E4D4;
	}

	.hero-subtitle-services {
		font-size: 18px;
		color: rgba(254, 254, 254, 0.7);
		margin-bottom: 24px;
		font-weight: 500;
	}

	.hero-description-services {
		font-size: 20px;
		color: rgba(254, 254, 254, 0.85);
		line-height: 1.7;
		max-width: 700px;
		margin: 0 auto;
	}

	/* Catálogo de Serviços */
	.services-catalog {
		padding: 100px 0;
		background: #f3ede0;
	}

	.catalog-services-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 0 40px;
	}

	.services-catalog-grid {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 28px;
	}

	.service-catalog-card {
		background: #FEFEFE;
		border-radius: 20px;
		padding: 16px;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		cursor: pointer;
		border: 3px solid transparent;
	}

	.service-catalog-card:hover {
		transform: translateY(-8px);
		border-color: #007A63;
		box-shadow: 0 15px 40px rgba(0, 122, 99, 0.2);
	}

	.service-catalog-inner {
		position: relative;
	}

	.service-catalog-image {
		width: 100%;
		height: 280px;
		border-radius: 16px;
		overflow: hidden;
		background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
		margin-bottom: 16px;
		position: relative;
	}

	.catalog-service-img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}

	.service-catalog-card:hover .catalog-service-img {
		transform: scale(1.08);
	}

	.service-catalog-overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 122, 99, 0.9);
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.service-catalog-card:hover .service-catalog-overlay {
		opacity: 1;
	}

	.catalog-schedule-link {
		display: inline-flex !important;
		align-items: center !important;
		gap: 10px !important;
		padding: 14px 28px !important;
		background: white !important;
		color: #007A63 !important;
		border-radius: 50px !important;
		text-decoration: none !important;
		font-weight: 600 !important;
		font-size: 15px !important;
		transition: all 0.3s ease !important;
		transform: translateY(10px);
	}

	.service-catalog-overlay:hover .catalog-schedule-link {
		transform: translateY(0);
	}

	.catalog-schedule-link:hover {
		background: #B9E4D4 !important;
		color: #006654 !important;
		transform: scale(1.05);
	}

	.catalog-schedule-link i {
		width: 18px;
		height: 18px;
		color: #007A63;
		stroke: #007A63;
	}

	.catalog-schedule-link:hover i {
		color: #006654;
		stroke: #006654;
	}

	.service-catalog-content {
		padding: 0 6px;
	}

	.service-catalog-title {
		font-size: 19px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 14px;
		line-height: 1.3;
	}

	.service-catalog-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.service-catalog-price {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.catalog-service-label {
		font-size: 11px;
		color: #999;
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.catalog-service-value {
		font-size: 22px;
		font-weight: 800;
		color: #007A63;
	}

	.catalog-service-arrow {
		width: 42px;
		height: 42px;
		background: #007A63;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.3s ease;
		text-decoration: none;
		flex-shrink: 0;
	}

	.catalog-service-arrow i {
		color: white;
		stroke: white;
		width: 20px;
		height: 20px;
	}

	.service-catalog-card:hover .catalog-service-arrow {
		background: #006654;
		transform: scale(1.15) rotate(-45deg);
	}

	/* Empty State */
	.empty-state-services {
		text-align: center;
		padding: 100px 40px;
		max-width: 600px;
		margin: 0 auto;
	}

	.empty-icon {
		width: 120px;
		height: 120px;
		margin: 0 auto 32px;
		background: rgba(0, 122, 99, 0.1);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.empty-icon i {
		width: 60px;
		height: 60px;
		color: #007A63;
		stroke: #007A63;
		stroke-width: 1.5px;
	}

	.empty-title {
		font-size: 32px;
		font-weight: 800;
		color: #1a1a1a;
		margin-bottom: 16px;
		letter-spacing: -0.5px;
	}

	.empty-description {
		font-size: 17px;
		color: #666;
		line-height: 1.7;
		margin-bottom: 36px;
	}

	.btn-empty-home {
		display: inline-flex !important;
		align-items: center !important;
		gap: 10px !important;
		padding: 16px 32px !important;
		background: #007A63 !important;
		color: #FEFEFE !important;
		border: none !important;
		border-radius: 12px !important;
		text-decoration: none !important;
		font-weight: 600 !important;
		font-size: 16px !important;
		transition: all 0.3s ease !important;
	}

	.btn-empty-home:hover {
		background: #006654 !important;
		color: #FEFEFE !important;
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(0, 122, 99, 0.3);
	}

	.btn-empty-home i {
		width: 20px;
		height: 20px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	/* Responsive */
	@media (max-width: 1200px) {
		.services-catalog-grid {
			grid-template-columns: repeat(3, 1fr);
			gap: 24px;
		}
	}

	@media (max-width: 992px) {
		.services-catalog-grid {
			grid-template-columns: repeat(2, 1fr);
			gap: 24px;
		}

		.hero-title-services {
			font-size: 48px;
		}

		.service-catalog-image {
			height: 240px;
		}
	}

	@media (max-width: 768px) {
		.services-hero {
			padding: 80px 0 60px;
		}

		.hero-title-services {
			font-size: 38px;
		}

		.hero-description-services {
			font-size: 17px;
		}

		.services-catalog {
			padding: 80px 0;
		}

		.catalog-services-container {
			padding: 0 20px;
		}

		.services-catalog-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}

		.service-catalog-card {
			padding: 14px;
		}

		.service-catalog-image {
			height: 220px;
		}

		.service-catalog-title {
			font-size: 18px;
		}

		.catalog-service-value {
			font-size: 20px;
		}

		.catalog-service-arrow {
			width: 38px;
			height: 38px;
		}

		.catalog-service-arrow i {
			width: 18px;
			height: 18px;
		}
	}
</style>

<script>
	(function() {
		const catalogEl = document.getElementById('services-catalog-app');
		if (!catalogEl) return;

		const servicesData = JSON.parse(catalogEl.dataset.services || '[]');

		const { createApp } = Vue;

		const ServicesCatalogApp = {
			data() {
				return {
					services: servicesData
				}
			},
			methods: {
				formatPrice(value) {
					return parseFloat(value).toFixed(2).replace('.', ',');
				}
			},
			mounted() {
				this.$nextTick(() => {
					if (typeof lucide !== 'undefined') {
						lucide.createIcons();
					}
				});
			},
			updated() {
				this.$nextTick(() => {
					if (typeof lucide !== 'undefined') {
						lucide.createIcons();
					}
				});
			}
		};

		createApp(ServicesCatalogApp).mount('#services-catalog-app');
	})();
</script>

<?php } ?>

<?php require_once("rodape.php") ?>