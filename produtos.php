<?php require_once("cabecalho.php") ?>
<style type="text/css">
	.sub_page .hero_area {
		min-height: auto;
	}
</style>

</div>

<?php 
$query = $pdo->query("SELECT * FROM produtos where estoque > 0 and valor_venda > 0 ORDER BY id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$produtos = [];
foreach($res as $produto) {
	$produtos[] = [
		'id' => $produto['id'],
		'nome' => $produto['nome'],
		'valor' => $produto['valor_venda'],
		'foto' => $produto['foto'],
		'descricao' => $produto['descricao']
	];
}

if(count($produtos) > 0){ 
?>

<!-- Hero Section Produtos -->
<section class="products-hero">
	<div class="products-hero-content">
		<div class="hero-badge-products">// PRODUTOS</div>
		<h1 class="hero-title-products">
			Nossos <span class="highlight-products">Produtos</span>
		</h1>
		<p class="hero-description-products">
			Confira nossa seleção completa de produtos profissionais. Oferecemos descontos especiais para compras em grande quantidade.
		</p>
	</div>
</section>

<!-- Produtos Grid -->
<section class="products-catalog">
	<div class="catalog-container" id="products-catalog-app" 
		 data-products='<?php echo json_encode($produtos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
		 data-whatsapp="<?php echo $tel_whatsapp; ?>">
		
		<!-- Grid de Produtos -->
		<div v-if="products.length > 0" class="products-catalog-grid">
			<div v-for="product in products" :key="product.id" class="product-catalog-card">
				<div class="product-catalog-inner">
					<div class="product-catalog-image">
						<img :src="'sistema/painel/img/produtos/' + product.foto" :alt="product.nome" :title="product.descricao" class="catalog-img">
						<div class="product-catalog-overlay">
							<a :href="getWhatsappLink(product)" target="_blank" class="catalog-buy-link">
								<i data-lucide="shopping-bag"></i>
								<span>Comprar</span>
							</a>
						</div>
					</div>
					<div class="product-catalog-content">
						<h3 class="product-catalog-title">{{ product.nome }}</h3>
						<div class="product-catalog-footer">
							<div class="product-catalog-price">
								<span class="catalog-price-value">R$ {{ formatPrice(product.valor) }}</span>
							</div>
							<a :href="getWhatsappLink(product)" target="_blank" class="catalog-arrow">
								<i data-lucide="shopping-cart"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Empty State -->
		<div v-else class="empty-state-products">
			<div class="empty-icon">
				<i data-lucide="package-x"></i>
			</div>
			<h3 class="empty-title">Nenhum Produto Disponível</h3>
			<p class="empty-description">
				No momento não há produtos cadastrados em nosso catálogo. Em breve teremos novidades para você!
			</p>
			<a href="index" class="btn-empty-home">
				<i data-lucide="home"></i>
				<span>Voltar para Home</span>
			</a>
		</div>
	</div>
</section>

<style>
	/* Hero Produtos */
	.products-hero {
		background: linear-gradient(135deg, #006854 0%, #005246 100%);
		padding: 120px 0 80px;
		text-align: center;
		position: relative;
		overflow: hidden;
	}

	.products-hero::before {
		content: '';
		position: absolute;
		top: -50%;
		left: -10%;
		width: 600px;
		height: 600px;
		background: radial-gradient(circle, rgba(185, 228, 212, 0.1) 0%, transparent 70%);
		pointer-events: none;
	}

	.products-hero-content {
		max-width: 900px;
		margin: 0 auto;
		padding: 0 40px;
		position: relative;
		z-index: 2;
	}

	.hero-badge-products {
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

	.hero-title-products {
		font-size: 64px;
		font-weight: 900;
		color: #FEFEFE;
		line-height: 1.1;
		margin-bottom: 24px;
		letter-spacing: -2px;
	}

	.highlight-products {
		color: #B9E4D4;
	}

	.hero-description-products {
		font-size: 20px;
		color: rgba(254, 254, 254, 0.85);
		line-height: 1.7;
		max-width: 700px;
		margin: 0 auto;
	}

	/* Catálogo de Produtos */
	.products-catalog {
		padding: 100px 0;
		background: #f3ede0;
	}

	.catalog-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 0 40px;
	}

	.products-catalog-grid {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
		gap: 28px;
	}

	.product-catalog-card {
		background: #FEFEFE;
		border-radius: 20px;
		padding: 16px;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		cursor: pointer;
		border: 3px solid transparent;
	}

	.product-catalog-card:hover {
		transform: translateY(-8px);
		border-color: #007A63;
		box-shadow: 0 15px 40px rgba(0, 122, 99, 0.2);
	}

	.product-catalog-inner {
		position: relative;
	}

	.product-catalog-image {
		width: 100%;
		height: 280px;
		border-radius: 16px;
		overflow: hidden;
		background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
		margin-bottom: 16px;
		position: relative;
	}

	.catalog-img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}

	.product-catalog-card:hover .catalog-img {
		transform: scale(1.08);
	}

	.product-catalog-overlay {
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

	.product-catalog-card:hover .product-catalog-overlay {
		opacity: 1;
	}

	.catalog-buy-link {
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

	.product-catalog-overlay:hover .catalog-buy-link {
		transform: translateY(0);
	}

	.catalog-buy-link:hover {
		background: #B9E4D4 !important;
		color: #006654 !important;
		transform: scale(1.05);
	}

	.catalog-buy-link i {
		width: 18px;
		height: 18px;
	}

	.product-catalog-content {
		padding: 0 6px;
	}

	.product-catalog-title {
		font-size: 19px;
		font-weight: 700;
		color: #1a1a1a;
		margin-bottom: 14px;
		line-height: 1.3;
		min-height: 50px;
	}

	.product-catalog-footer {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.product-catalog-price {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.catalog-price-value {
		font-size: 22px;
		font-weight: 800;
		color: #007A63;
	}

	.catalog-arrow {
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

	.catalog-arrow i {
		color: white;
		stroke: white;
		width: 20px;
		height: 20px;
	}

	.product-catalog-card:hover .catalog-arrow {
		background: #006654;
		transform: scale(1.15) rotate(-45deg);
	}

	/* Empty State */
	.empty-state-products {
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
		.products-catalog-grid {
			grid-template-columns: repeat(3, 1fr);
			gap: 24px;
		}
	}

	@media (max-width: 992px) {
		.products-catalog-grid {
			grid-template-columns: repeat(2, 1fr);
			gap: 24px;
		}

		.hero-title-products {
			font-size: 48px;
		}

		.product-catalog-image {
			height: 240px;
		}
	}

	@media (max-width: 768px) {
		.products-hero {
			padding: 80px 0 60px;
		}

		.hero-title-products {
			font-size: 38px;
		}

		.hero-description-products {
			font-size: 17px;
		}

		.products-catalog {
			padding: 80px 0;
		}

		.catalog-container {
			padding: 0 20px;
		}

		.products-catalog-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}

		.product-catalog-card {
			padding: 14px;
		}

		.product-catalog-image {
			height: 220px;
		}

		.product-catalog-title {
			font-size: 18px;
			min-height: auto;
		}

		.catalog-price-value {
			font-size: 20px;
		}

		.catalog-arrow {
			width: 38px;
			height: 38px;
		}

		.catalog-arrow i {
			width: 18px;
			height: 18px;
		}
	}
</style>

<script>
	(function() {
		const catalogEl = document.getElementById('products-catalog-app');
		if (!catalogEl) return;

		const productsData = JSON.parse(catalogEl.dataset.products || '[]');
		const whatsappNumber = catalogEl.dataset.whatsapp || '';

		const { createApp } = Vue;

		const ProductsCatalogApp = {
			data() {
				return {
					products: productsData,
					whatsappNumber: whatsappNumber
				}
			},
			methods: {
				formatPrice(value) {
					return parseFloat(value).toFixed(2).replace('.', ',');
				},
				getWhatsappLink(product) {
					const message = encodeURIComponent(`Olá, gostaria de saber mais informações sobre o produto ${product.nome}`);
					return `http://api.whatsapp.com/send?1=pt_BR&phone=${this.whatsappNumber}&text=${message}`;
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

		createApp(ProductsCatalogApp).mount('#products-catalog-app');
	})();
</script>

<?php } ?>

<?php require_once("rodape.php") ?>