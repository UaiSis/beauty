<!-- Tailwind CSS (Nativo) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
	tailwind.config = {
		theme: {
			extend: {
				colors: {
					primary: '#007A63',
					'primary-hover': '#006854',
					'primary-light': '#00d896'
				}
			}
		}
	}
</script>

<!-- Vue 3 Core -->
<script src="https://unpkg.com/vue@3.4.21/dist/vue.global.prod.js"></script>

<!-- Flatpickr - Date Picker moderno -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<!-- Axios para requisições AJAX -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
/* Cores personalizadas do sistema */
:root {
	--primary-color: #007A63;
	--primary-hover: #006854;
	--primary-light: #00d896;
}

/* Customizar Flatpickr com cores do sistema */
.flatpickr-day.selected {
	background: #007A63 !important;
	border-color: #007A63 !important;
}

.flatpickr-day.today {
	border-color: #007A63 !important;
}

.flatpickr-day:hover {
	background: rgba(0, 122, 99, 0.1) !important;
}

.flatpickr-current-month .flatpickr-monthDropdown-months:hover,
.flatpickr-current-month .numInputWrapper:hover {
	background: rgba(0, 122, 99, 0.1);
}
</style>

<script>
// Carregar componentes Vue
document.addEventListener('DOMContentLoaded', function() {
	const script = document.createElement('script');
	script.src = 'js/vue-components.js';
	document.head.appendChild(script);
});
</script>

