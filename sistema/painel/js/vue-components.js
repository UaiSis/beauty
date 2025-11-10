// Componentes Vue Globais Reutilizáveis

// Componente de Card Estatística
const StatCard = {
	name: 'StatCard',
	props: {
		title: String,
		value: [String, Number],
		icon: String,
		iconColor: { type: String, default: '#007A63' },
		link: String
	},
	template: `
		<a :href="link" class="stat-card" style="text-decoration: none; color: inherit;">
			<div class="stat-card-content">
				<div class="stat-icon" :style="{ background: iconColor + '20', color: iconColor }">
					<i :class="'fa fa-' + icon"></i>
				</div>
				<div class="stat-info">
					<div class="stat-value">{{ value }}</div>
					<div class="stat-label">{{ title }}</div>
				</div>
			</div>
		</a>
	`
};

// Componente de Header de Página
const PageHeader = {
	name: 'PageHeader',
	props: {
		title: String,
		subtitle: String,
		icon: String
	},
	template: `
		<div class="page-header">
			<div class="page-header-content">
				<div class="page-title-wrapper">
					<div class="page-title-icon">
						<i :class="'fa fa-' + icon"></i>
					</div>
					<h1 class="page-title">{{ title }}</h1>
				</div>
				<p class="page-subtitle">{{ subtitle }}</p>
				<div class="page-divider"></div>
			</div>
			<slot name="actions"></slot>
		</div>
	`
};

// Exportar componentes
if (typeof window !== 'undefined') {
	window.VueComponents = {
		StatCard,
		PageHeader
	};
}


