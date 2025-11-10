<?php 
require_once("verificar.php");
require_once("../conexao.php");
$pag = 'agendamentos';
$data_atual = date('Y-m-d');

//verificar se ele tem a permissão de estar nessa página
if(@$agendamentos == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

//verificar pagamentos
$query = $pdo->query("SELECT * FROM agendamentos_temp where ref_pix is not null");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
	for($i=0; $i < $total_reg; $i++){
		$ref_pix = $res[$i]['ref_pix'];
		require("../../pagamentos/consultar_pagamento.php");
		if($status_api == 'approved'){
			require("../../pagamentos/pagamento_aprovado.php");
		}
	}
}
?>

<style>
	/* Página de Agendamentos Moderna */
	.agendamentos-page-modern {
		padding: 24px;
		background: #f8f9fa;
		min-height: 100vh;
	}

	.agendamentos-header {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		margin-bottom: 32px;
		flex-wrap: wrap;
		gap: 16px;
	}

	.agendamentos-header-content {
		flex: 1;
		min-width: 250px;
	}

	.agendamentos-title-wrapper {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-bottom: 8px;
	}

	.agendamentos-title-icon {
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

	.agendamentos-title {
		font-size: 24px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
	}

	.agendamentos-subtitle {
		font-size: 14px;
		color: #6c757d;
		margin: 0;
		padding-left: 52px;
	}

	.agendamentos-divider {
		height: 3px;
		background: linear-gradient(90deg, #007A63 0%, transparent 100%);
		width: 120px;
		margin-top: 8px;
		margin-left: 52px;
		border-radius: 2px;
	}

	.agendamentos-actions {
		display: flex;
		gap: 12px;
		align-items: center;
		flex-wrap: wrap;
	}

	.btn-novo-agendamento {
		background: #007A63;
		color: #fff;
		border: none;
		border-radius: 12px;
		padding: 12px 24px;
		font-weight: 600;
		font-size: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(0, 122, 99, 0.2);
	}

	.btn-novo-agendamento:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(0, 122, 99, 0.3);
		color: #fff;
	}

	.filter-profissional {
		min-width: 250px;
		flex: 1;
		max-width: 350px;
	}

	.filter-profissional .form-control {
		padding: 10px 14px;
		border: 2px solid #e9ecef;
		border-radius: 10px;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.filter-profissional .form-control:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	/* Grid Calendário + Listagem */
	.agenda-grid {
		display: grid;
		grid-template-columns: 380px 1fr;
		gap: 20px;
		margin-top: 24px;
	}

	.calendar-card {
		background: #fff;
		border-radius: 16px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		height: fit-content;
		position: sticky;
		top: 20px;
	}

	.agenda-list-card {
		background: #fff;
		border-radius: 16px;
		padding: 20px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		min-height: 600px;
	}

	/* Modal Moderno */
	.modal-content {
		border: none;
		border-radius: 12px;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
	}

	.modal-header {
		background: #fff;
		border-bottom: 1px solid #f0f0f0;
		padding: 24px 28px;
		border-radius: 12px 12px 0 0;
	}

	.modal-header .modal-title {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 20px;
		font-weight: 700;
		color: #1a1a1a;
	}

	.modal-title-icon {
		width: 40px;
		height: 40px;
		background: rgba(0, 122, 99, 0.1);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #007A63;
		font-size: 18px;
	}

	.modal-header .close {
		background: #f0f0f0;
		border-radius: 8px;
		width: 32px;
		height: 32px;
		display: flex;
		align-items: center;
		justify-content: center;
		opacity: 1;
		margin: 0;
		padding: 0;
		transition: all 0.2s ease;
	}

	.modal-header .close:hover {
		background: #e0e0e0;
	}

	.modal-body {
		padding: 28px;
		background: #fff;
	}

	.form-group {
		margin-bottom: 20px;
	}

	.form-group label {
		font-size: 13px;
		font-weight: 600;
		color: #1a1a1a;
		margin-bottom: 8px;
		display: block;
	}

	.form-group label .required {
		color: #ef5350;
		margin-left: 4px;
	}

	.form-control {
		width: 100%;
		padding: 10px 14px;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		font-size: 14px;
		transition: all 0.3s ease;
		background: #fff;
	}

	.form-control:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	.form-control::placeholder {
		color: #adb5bd;
	}

	.form-divider {
		height: 1px;
		background: #f0f0f0;
		margin: 28px 0;
	}

	.modal-footer {
		background: #fafafa;
		border-top: 1px solid #f0f0f0;
		padding: 20px 28px;
		border-radius: 0 0 12px 12px;
		display: flex;
		gap: 12px;
		justify-content: flex-end;
	}

	.btn-cancel {
		background: #fff;
		color: #6c757d;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 10px 24px;
		font-weight: 600;
		font-size: 14px;
		transition: all 0.3s ease;
	}

	.btn-cancel:hover {
		background: #f8f9fa;
		border-color: #6c757d;
	}

	.btn-submit {
		background: #007A63;
		color: #fff;
		border: none;
		border-radius: 8px;
		padding: 10px 24px;
		font-weight: 600;
		font-size: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: all 0.3s ease;
	}

	.btn-submit:hover {
		background: #006854;
	}

	/* Horários Grid */
	#listar-horarios {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
		margin-top: 12px;
	}

	.horario-btn {
		padding: 10px 16px;
		border: 2px solid #e9ecef;
		border-radius: 8px;
		font-size: 13px;
		font-weight: 600;
		background: #fff;
		color: #495057;
		cursor: pointer;
		transition: all 0.3s ease;
		text-align: center;
		min-width: 80px;
	}

	.horario-btn:hover {
		border-color: #007A63;
		color: #007A63;
		background: rgba(0, 122, 99, 0.05);
	}

	.horario-btn.selected {
		border-color: #007A63;
		background: #007A63;
		color: #fff;
	}

	.horario-btn.ocupado {
		border-color: #ef5350;
		background: rgba(239, 83, 80, 0.1);
		color: #ef5350;
		cursor: not-allowed;
	}

	/* Select Profissional Customizado com Avatares */
	.profissional-select-wrapper {
		position: relative;
	}

	.profissional-select-custom {
		width: 100%;
		padding: 12px 40px 12px 60px;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		font-size: 14px;
		font-weight: 500;
		color: #495057;
		background: #fff;
		cursor: pointer;
		transition: all 0.3s ease;
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
	}

	.profissional-select-custom:hover {
		border-color: #c0c0c0;
	}

	.profissional-select-custom:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.08);
	}

	.profissional-avatar {
		position: absolute;
		left: 12px;
		top: 50%;
		transform: translateY(-50%);
		width: 36px;
		height: 36px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid #e8f5f3;
		pointer-events: none;
		z-index: 1;
	}

	.profissional-select-arrow {
		position: absolute;
		right: 14px;
		top: 50%;
		transform: translateY(-50%);
		width: 0;
		height: 0;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		border-top: 6px solid #6c757d;
		pointer-events: none;
	}

	/* Dropdown customizado */
	.profissional-dropdown {
		position: absolute;
		top: calc(100% + 4px);
		left: 0;
		right: 0;
		background: #fff;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		max-height: 300px;
		overflow-y: auto;
		z-index: 1000;
		display: none;
	}

	.profissional-dropdown.show {
		display: block;
	}

	.profissional-search {
		padding: 12px;
		border-bottom: 1px solid #f0f0f0;
		background: #fafbfc;
		border-radius: 10px 10px 0 0;
	}

	.profissional-search input {
		width: 100%;
		padding: 8px 12px 8px 36px;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		font-size: 13px;
		background: #fff url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%236c757d" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>') no-repeat 10px center;
	}

	.profissional-search input:focus {
		outline: none;
		border-color: #007A63;
	}

	.profissional-option {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 12px 16px;
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.profissional-option:hover {
		background: rgba(0, 122, 99, 0.08);
	}

	.profissional-option.selected {
		background: rgba(0, 122, 99, 0.12);
	}

	.profissional-option-avatar {
		width: 40px;
		height: 40px;
		border-radius: 50%;
		object-fit: cover;
		border: 2px solid #e8f5f3;
		flex-shrink: 0;
	}

	.profissional-option-info {
		flex: 1;
	}

	.profissional-option-name {
		font-size: 14px;
		font-weight: 600;
		color: #1a1a1a;
	}

	.profissional-option-role {
		font-size: 12px;
		color: #6c757d;
	}

	.profissional-option-check {
		color: #007A63;
		font-size: 16px;
		display: none;
	}

	.profissional-option.selected .profissional-option-check {
		display: block;
	}

	/* Select Cliente Customizado com Avatares de Iniciais */
	.cliente-select-wrapper {
		position: relative;
	}

	.cliente-select-custom {
		width: 100%;
		padding: 12px 40px 12px 60px;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		font-size: 14px;
		font-weight: 500;
		color: #495057;
		background: #fff;
		cursor: pointer;
		transition: all 0.3s ease;
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
	}

	.cliente-select-custom:hover {
		border-color: #c0c0c0;
	}

	.cliente-select-custom:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.08);
	}

	.cliente-avatar {
		position: absolute;
		left: 12px;
		top: 50%;
		transform: translateY(-50%);
		width: 36px;
		height: 36px;
		border-radius: 50%;
		background: #007A63;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-weight: 700;
		font-size: 14px;
		pointer-events: none;
		z-index: 1;
	}

	.cliente-select-arrow {
		position: absolute;
		right: 14px;
		top: 50%;
		transform: translateY(-50%);
		width: 0;
		height: 0;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		border-top: 6px solid #6c757d;
		pointer-events: none;
	}

	/* Dropdown de clientes */
	.cliente-dropdown {
		position: absolute;
		top: calc(100% + 4px);
		left: 0;
		right: 0;
		background: #fff;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		max-height: 320px;
		overflow-y: auto;
		z-index: 1000;
		display: none;
	}

	.cliente-dropdown.show {
		display: block;
	}

	.cliente-search {
		padding: 12px;
		border-bottom: 1px solid #f0f0f0;
		background: #fafbfc;
		border-radius: 10px 10px 0 0;
	}

	.cliente-search input {
		width: 100%;
		padding: 8px 12px 8px 36px;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		font-size: 13px;
		background: #fff url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%236c757d" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>') no-repeat 10px center;
	}

	.cliente-search input:focus {
		outline: none;
		border-color: #007A63;
	}

	.cliente-option {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 12px 16px;
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.cliente-option:hover {
		background: rgba(0, 122, 99, 0.08);
	}

	.cliente-option.selected {
		background: rgba(0, 122, 99, 0.12);
	}

	.cliente-option-avatar {
		width: 40px;
		height: 40px;
		border-radius: 50%;
		background: #007A63;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-weight: 700;
		font-size: 15px;
		flex-shrink: 0;
		border: 2px solid #e8f5f3;
	}

	.cliente-option-info {
		flex: 1;
	}

	.cliente-option-name {
		font-size: 14px;
		font-weight: 600;
		color: #1a1a1a;
	}

	.cliente-option-phone {
		font-size: 12px;
		color: #6c757d;
	}

	.cliente-option-check {
		color: #007A63;
		font-size: 16px;
		display: none;
	}

	.cliente-option.selected .cliente-option-check {
		display: block;
	}

	/* Select Serviço Customizado com Tags/Etiquetas */
	.servico-select-wrapper {
		position: relative;
	}

	.servico-select-custom {
		width: 100%;
		padding: 12px 40px 12px 48px;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		font-size: 14px;
		font-weight: 500;
		color: #495057;
		background: #fff;
		cursor: pointer;
		transition: all 0.3s ease;
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
	}

	.servico-select-custom:hover {
		border-color: #c0c0c0;
	}

	.servico-select-custom:focus {
		outline: none;
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.08);
	}

	.servico-tag-selected {
		position: absolute;
		left: 12px;
		top: 50%;
		transform: translateY(-50%);
		padding: 4px 8px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 700;
		display: flex;
		align-items: center;
		gap: 4px;
		pointer-events: none;
		z-index: 1;
	}

	.servico-select-arrow {
		position: absolute;
		right: 14px;
		top: 50%;
		transform: translateY(-50%);
		width: 0;
		height: 0;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		border-top: 6px solid #6c757d;
		pointer-events: none;
	}

	/* Dropdown de serviços */
	.servico-dropdown {
		position: absolute;
		top: calc(100% + 4px);
		left: 0;
		right: 0;
		background: #fff;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		max-height: 350px;
		overflow-y: auto;
		z-index: 1000;
		display: none;
	}

	.servico-dropdown.show {
		display: block;
	}

	.servico-search {
		padding: 12px;
		border-bottom: 1px solid #f0f0f0;
		background: #fafbfc;
		border-radius: 10px 10px 0 0;
	}

	.servico-search input {
		width: 100%;
		padding: 8px 12px 8px 36px;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		font-size: 13px;
		background: #fff url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%236c757d" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>') no-repeat 10px center;
	}

	.servico-search input:focus {
		outline: none;
		border-color: #007A63;
	}

	.servico-option {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 12px 16px;
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.servico-option:hover {
		background: rgba(0, 122, 99, 0.08);
	}

	.servico-option.selected {
		background: rgba(0, 122, 99, 0.12);
	}

	.servico-tag {
		padding: 6px 10px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 700;
		display: flex;
		align-items: center;
		gap: 6px;
		flex-shrink: 0;
		min-width: 90px;
		justify-content: center;
	}

	/* Cores das tags de serviço */
	.servico-tag.corte { background: #fef3c7; color: #92400e; }
	.servico-tag.barba { background: #dbeafe; color: #1e40af; }
	.servico-tag.coloracao { background: #fce7f3; color: #9f1239; }
	.servico-tag.hidratacao { background: #d1fae5; color: #065f46; }
	.servico-tag.manicure { background: #ede9fe; color: #5b21b6; }
	.servico-tag.pedicure { background: #fce7f3; color: #831843; }
	.servico-tag.pacote { background: #ddd6fe; color: #4c1d95; }
	.servico-tag.massagem { background: #ccfbf1; color: #134e4a; }
	.servico-tag.default { background: #e0f2f1; color: #00695c; }

	.servico-option-info {
		flex: 1;
	}

	.servico-option-name {
		font-size: 14px;
		font-weight: 600;
		color: #1a1a1a;
		margin-bottom: 2px;
	}

	.servico-option-price {
		font-size: 13px;
		color: #007A63;
		font-weight: 600;
	}

	.servico-option-check {
		color: #007A63;
		font-size: 16px;
		display: none;
	}

	.servico-option.selected .servico-option-check {
		display: block;
	}

	/* Date Picker Customizado */
	.data-picker-wrapper {
		position: relative;
	}

	.data-picker-display {
		width: 100%;
		padding: 12px 44px 12px 16px;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		font-size: 14px;
		font-weight: 500;
		color: #495057;
		background: #fff;
		cursor: pointer;
		transition: all 0.3s ease;
		display: flex;
		align-items: center;
		gap: 10px;
	}

	.data-picker-display:hover {
		border-color: #c0c0c0;
	}

	.data-picker-display.open {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.08);
	}

	.data-picker-icon {
		color: #6c757d;
		font-size: 16px;
	}

	.data-picker-text {
		flex: 1;
		color: #adb5bd;
	}

	.data-picker-text.selected {
		color: #495057;
	}

	.data-picker-arrow {
		position: absolute;
		right: 14px;
		top: 50%;
		transform: translateY(-50%);
		color: #6c757d;
		font-size: 12px;
		pointer-events: none;
	}

	/* Dropdown do calendário */
	.calendar-dropdown {
		position: absolute;
		top: calc(100% + 4px);
		left: 0;
		right: 0;
		background: #fff;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		z-index: 1000;
		display: none;
		padding: 16px;
	}

	.calendar-dropdown.show {
		display: block;
	}

	/* Header do calendário */
	.calendar-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 16px;
	}

	.calendar-month-selector {
		display: flex;
		gap: 12px;
	}

	.calendar-select {
		padding: 6px 10px;
		border: 1px solid #e0e0e0;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 600;
		color: #495057;
		background: #fff;
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.calendar-select:hover {
		border-color: #007A63;
	}

	.calendar-select:focus {
		outline: none;
		border-color: #007A63;
	}

	.calendar-nav-btn {
		width: 32px;
		height: 32px;
		border: 1px solid #e0e0e0;
		border-radius: 6px;
		background: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.2s ease;
		color: #6c757d;
	}

	.calendar-nav-btn:hover {
		background: rgba(0, 122, 99, 0.08);
		border-color: #007A63;
		color: #007A63;
	}

	/* Grid do calendário */
	.calendar-weekdays {
		display: grid;
		grid-template-columns: repeat(7, 1fr);
		gap: 4px;
		margin-bottom: 8px;
	}

	.calendar-weekday {
		text-align: center;
		font-size: 11px;
		font-weight: 700;
		color: #6c757d;
		text-transform: uppercase;
		padding: 8px 0;
	}

	.calendar-days {
		display: grid;
		grid-template-columns: repeat(7, 1fr);
		gap: 4px;
	}

	.calendar-day {
		aspect-ratio: 1;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 13px;
		font-weight: 600;
		color: #495057;
		border-radius: 8px;
		cursor: pointer;
		transition: all 0.2s ease;
		border: 1px solid transparent;
	}

	.calendar-day:hover:not(.disabled):not(.other-month) {
		background: rgba(0, 122, 99, 0.08);
		border-color: #007A63;
	}

	.calendar-day.selected {
		background: #007A63;
		color: #fff;
		border-color: #007A63;
	}

	.calendar-day.today {
		border-color: #007A63;
		color: #007A63;
		font-weight: 700;
	}

	.calendar-day.disabled {
		color: #dee2e6;
		cursor: not-allowed;
		opacity: 0.5;
	}

	.calendar-day.other-month {
		color: #dee2e6;
		opacity: 0.4;
	}

	/* Estilo para inputs de texto também */
	.modal-body input[type="text"].form-control {
		border: 2px solid #e9ecef;
		border-radius: 10px;
		padding: 11px 14px;
		font-size: 14px;
		font-weight: 500;
		transition: all 0.3s ease;
	}

	.modal-body input[type="text"].form-control:hover {
		border-color: #007A63;
	}

	.modal-body input[type="text"].form-control:focus {
		border-color: #007A63;
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}

	/* Label com ícone decorativo */
	.form-group label {
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.form-group label::before {
		content: '';
		width: 3px;
		height: 14px;
		background: #007A63;
		border-radius: 2px;
		display: none;
	}

	/* Seções de pagamento com ícones */
	.payment-section {
		background: rgba(0, 122, 99, 0.05);
		border-radius: 12px;
		padding: 20px;
		margin-bottom: 20px;
		border: 2px solid rgba(0, 122, 99, 0.1);
		transition: all 0.3s ease;
	}

	.payment-section:hover {
		border-color: rgba(0, 122, 99, 0.2);
		box-shadow: 0 2px 8px rgba(0, 122, 99, 0.1);
	}

	.payment-section-header {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 14px;
		font-weight: 600;
		color: #1a1a1a;
		margin-bottom: 16px;
		padding-bottom: 12px;
		border-bottom: 2px solid rgba(0, 122, 99, 0.1);
	}

	.payment-section-icon {
		width: 32px;
		height: 32px;
		background: #007A63;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-size: 16px;
	}

	.payment-section.opcional {
		background: rgba(66, 165, 245, 0.05);
		border-color: rgba(66, 165, 245, 0.1);
	}

	.payment-section.opcional:hover {
		border-color: rgba(66, 165, 245, 0.2);
		box-shadow: 0 2px 8px rgba(66, 165, 245, 0.1);
	}

	.payment-section.opcional .payment-section-icon {
		background: #42a5f5;
	}

	.payment-section.opcional .payment-section-header {
		border-bottom-color: rgba(66, 165, 245, 0.1);
	}

	/* Cards de Agendamento Modernos */
	.agendamento-card {
		background: #fff;
		border-radius: 12px;
		padding: 20px;
		margin-bottom: 16px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		border: 1px solid #f0f0f0;
		transition: all 0.3s ease;
		border-left: 4px solid #007A63;
	}

	.agendamento-card:hover {
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
		transform: translateY(-2px);
	}

	.agendamento-card.confirmado {
		border-left-color: #00d896;
	}

	.agendamento-card.pendente {
		border-left-color: #ff9800;
	}

	.agendamento-card.cancelado {
		border-left-color: #ef5350;
		opacity: 0.7;
	}

	.agendamento-card-header {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		margin-bottom: 16px;
		padding-bottom: 16px;
		border-bottom: 1px solid #f0f0f0;
	}

	.agendamento-time-section {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.agendamento-time-icon {
		width: 44px;
		height: 44px;
		background: rgba(66, 165, 245, 0.12);
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #42a5f5;
		font-size: 18px;
		flex-shrink: 0;
	}

	.agendamento-time-info {
		display: flex;
		flex-direction: column;
	}

	.agendamento-hora {
		font-size: 18px;
		font-weight: 700;
		color: #1a1a1a;
		line-height: 1;
		margin-bottom: 4px;
	}

	.agendamento-duracao {
		font-size: 12px;
		color: #6c757d;
	}

	.agendamento-status-actions {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.agendamento-badge {
		padding: 6px 12px;
		border-radius: 20px;
		font-size: 11px;
		font-weight: 700;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.agendamento-badge.confirmado {
		background: rgba(0, 216, 150, 0.12);
		color: #00d896;
	}

	.agendamento-badge.pendente {
		background: rgba(255, 152, 0, 0.12);
		color: #ff9800;
	}

	.agendamento-badge.cancelado {
		background: rgba(239, 83, 80, 0.12);
		color: #ef5350;
	}

	.agendamento-menu {
		position: relative;
	}

	.agendamento-menu-btn {
		width: 32px;
		height: 32px;
		border: none;
		background: #f8f9fa;
		border-radius: 6px;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.2s ease;
		color: #6c757d;
	}

	.agendamento-menu-btn:hover {
		background: #e9ecef;
		color: #1a1a1a;
	}

	.agendamento-menu-dropdown {
		position: absolute;
		top: calc(100% + 4px);
		right: 0;
		background: #fff;
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		min-width: 180px;
		z-index: 100;
		display: none;
	}

	.agendamento-menu-dropdown.show {
		display: block;
	}

	.agendamento-menu-dropdown a {
		display: flex;
		align-items: center;
		gap: 10px;
		padding: 10px 14px;
		color: #495057;
		text-decoration: none;
		font-size: 13px;
		font-weight: 500;
		transition: all 0.2s ease;
		border-bottom: 1px solid #f0f0f0;
	}

	.agendamento-menu-dropdown a:last-child {
		border-bottom: none;
	}

	.agendamento-menu-dropdown a:hover {
		background: rgba(0, 122, 99, 0.08);
		color: #007A63;
	}

	.agendamento-menu-dropdown a i {
		width: 16px;
		text-align: center;
	}

	.agendamento-card-body {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.agendamento-cliente {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 15px;
		font-weight: 600;
		color: #1a1a1a;
	}

	.agendamento-cliente i {
		color: #6c757d;
		font-size: 14px;
	}

	.agendamento-servico {
		font-size: 14px;
		color: #6c757d;
	}

	.agendamento-profissional {
		font-size: 13px;
		color: #6c757d;
	}

	.agendamento-profissional strong {
		color: #007A63;
		font-weight: 600;
	}

	@media (max-width: 992px) {
		.agendamentos-page-modern {
			padding: 16px;
		}

		.agendamentos-title-wrapper {
			gap: 10px;
		}

		.agendamentos-title-icon {
			width: 36px;
			height: 36px;
			font-size: 18px;
		}

		.agendamentos-title {
			font-size: 20px;
		}

		.agendamentos-subtitle {
			padding-left: 46px;
			font-size: 13px;
		}

		.agendamentos-divider {
			margin-left: 46px;
			width: 100px;
		}

		.agenda-grid {
			grid-template-columns: 1fr;
			gap: 16px;
		}

		.calendar-card {
			position: relative;
			top: auto;
		}

		.filter-profissional {
			max-width: 100%;
		}

		.modal-dialog {
			margin: 8px;
		}

		.modal-body {
			padding: 20px;
		}

		.agendamento-card-header {
			flex-direction: column;
			gap: 12px;
			align-items: stretch;
		}

		.agendamento-time-section {
			width: 100%;
		}

		.agendamento-status-actions {
			width: 100%;
			justify-content: space-between;
		}

		.agendamento-menu-dropdown {
			right: 0;
		}
	}
</style>

<div class="agendamentos-page-modern">
	
	<div class="agendamentos-header">
		<div class="agendamentos-header-content">
			<div class="agendamentos-title-wrapper">
				<div class="agendamentos-title-icon">
					<i class="fa fa-calendar"></i>
				</div>
				<h1 class="agendamentos-title">Agendamentos</h1>
			</div>
			<p class="agendamentos-subtitle">Gerencie todos os agendamentos e horários da sua equipe</p>
			<div class="agendamentos-divider"></div>
		</div>
		
		<div class="agendamentos-actions">
			<div class="filter-profissional">
				<select class="form-control sel2" id="funcionario" name="funcionario" style="width:100%;" onchange="mudarFuncionario()"> 
					<option value="">Todos os Profissionais</option>
					<?php 
					$query = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY nome asc");
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

			<button onclick="inserir()" type="button" class="btn-novo-agendamento">
				<i class="fa fa-plus"></i> Novo Agendamento
			</button>
		</div>
	</div>

	<input type="hidden" name="data_agenda" id="data_agenda" value="<?php echo date('Y-m-d') ?>"> 

	<div class="agenda-grid">
		<!-- Calendário -->
		<div class="calendar-card">
			<div class="agile-calendar">
				<div class="calendar-widget">
					<div class="agile-calendar-grid">
						<div class="page">
							<div class="w3l-calendar-left">
								<div class="calendar-heading"></div>
								<div class="monthly" id="mycalendar"></div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Listagem de Agendamentos -->
		<div class="agenda-list-card">
			<div id="listar"></div>
		</div>
	</div>

</div>






<!-- Modal Novo Agendamento -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-icon">
						<i class="fa fa-calendar-plus-o"></i>
					</div>
					<span id="titulo_inserir">Novo Agendamento</span>
				</div>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="form-text">
				<div class="modal-body">

					<!-- Cliente e Funcionário -->
					<div class="row">
						<div class="col-md-6">						
							<div class="form-group"> 
								<label>Cliente <span class="required">*</span></label>
								<div class="cliente-select-wrapper">
									<div class="cliente-avatar" id="cliente-avatar-selected">--</div>
									<input type="text" class="cliente-select-custom" id="cliente-display" placeholder="Selecione um Cliente" readonly onclick="toggleClienteDropdown()">
									<div class="cliente-select-arrow"></div>
									<input type="hidden" id="cliente" name="cliente" required>
									
									<div class="cliente-dropdown" id="cliente-dropdown">
										<div class="cliente-search">
											<input type="text" id="cliente-search-input" placeholder="Buscar cliente..." onkeyup="filterClientes()">
										</div>
										<div id="cliente-options">
											<?php 
											$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_reg = @count($res);
											if($total_reg > 0){
												for($i=0; $i < $total_reg; $i++){
													$id_cli = $res[$i]['id'];
													$nome_cli = $res[$i]['nome'];
													$telefone_cli = $res[$i]['telefone'];
													
													// Gerar iniciais
													$palavras = explode(' ', $nome_cli);
													if(count($palavras) >= 2){
														$iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[1], 0, 1));
													}else{
														$iniciais = strtoupper(substr($nome_cli, 0, 2));
													}
													
													$tel_display = !empty($telefone_cli) ? $telefone_cli : 'Sem telefone';
													
													echo '<div class="cliente-option" data-id="'.$id_cli.'" data-nome="'.$nome_cli.'" data-iniciais="'.$iniciais.'" data-telefone="'.$tel_display.'" onclick="selectCliente(this)">';
													echo '<div class="cliente-option-avatar">'.$iniciais.'</div>';
													echo '<div class="cliente-option-info">';
													echo '<div class="cliente-option-name">'.$nome_cli.'</div>';
													echo '<div class="cliente-option-phone">'.$tel_display.'</div>';
													echo '</div>';
													echo '<i class="fa fa-check cliente-option-check"></i>';
													echo '</div>';
												}
											}
											?>
										</div>
									</div>
								</div>
							</div>						
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Profissional <span class="required">*</span></label>
								<div class="profissional-select-wrapper">
									<img src="img/perfil/sem-foto.jpg" class="profissional-avatar" id="profissional-avatar-selected">
									<input type="text" class="profissional-select-custom" id="profissional-display" placeholder="Selecione um Profissional" readonly onclick="toggleProfissionalDropdown()">
									<div class="profissional-select-arrow"></div>
									<input type="hidden" id="funcionario_modal" name="funcionario" required>
									
									<div class="profissional-dropdown" id="profissional-dropdown">
										<div class="profissional-search">
											<input type="text" id="profissional-search-input" placeholder="Buscar profissional..." onkeyup="filterProfissionais()">
										</div>
										<div id="profissional-options">
											<?php 
											$query = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY nome asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_reg = @count($res);
											if($total_reg > 0){
												for($i=0; $i < $total_reg; $i++){
													$id_prof = $res[$i]['id'];
													$nome_prof = $res[$i]['nome'];
													$foto_prof = $res[$i]['foto'];
													$nivel_prof = $res[$i]['nivel'];
													
													echo '<div class="profissional-option" data-id="'.$id_prof.'" data-nome="'.$nome_prof.'" data-foto="'.$foto_prof.'" onclick="selectProfissional(this)">';
													echo '<img src="img/perfil/'.$foto_prof.'" class="profissional-option-avatar">';
													echo '<div class="profissional-option-info">';
													echo '<div class="profissional-option-name">'.$nome_prof.'</div>';
													echo '<div class="profissional-option-role">'.$nivel_prof.'</div>';
													echo '</div>';
													echo '<i class="fa fa-check profissional-option-check"></i>';
													echo '</div>';
												}
											}
											?>
										</div>
									</div>
								</div>
							</div> 	
						</div>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Serviço e Data -->
					<div class="row">
						<div class="col-md-8">						
							<div class="form-group"> 
								<label>Serviço <span class="required">*</span></label>
								<div class="servico-select-wrapper">
									<div class="servico-tag-selected servico-tag default" id="servico-tag-selected" style="display: none;">
										<i class="fa fa-tag"></i>
									</div>
									<input type="text" class="servico-select-custom" id="servico-display" placeholder="Selecione um Serviço" readonly onclick="toggleServicoDropdown()">
									<div class="servico-select-arrow"></div>
									<input type="hidden" id="servico" name="servico" required>
									
									<div class="servico-dropdown" id="servico-dropdown">
										<div class="servico-search">
											<input type="text" id="servico-search-input" placeholder="Buscar serviço..." onkeyup="filterServicos()">
										</div>
										<div id="servico-options">
											<!-- Será preenchido dinamicamente via AJAX -->
										</div>
									</div>
								</div>
							</div>						
						</div>

						<div class="col-md-4">						
							<div class="form-group"> 
								<label>Data <span class="required">*</span></label>
								<div class="data-picker-wrapper">
									<div class="data-picker-display" onclick="toggleCalendarDropdown()">
										<i class="fa fa-calendar data-picker-icon"></i>
										<span class="data-picker-text" id="data-display-text">Selecione a data</span>
										<i class="fa fa-chevron-down data-picker-arrow"></i>
									</div>
									<input type="hidden" id="data-modal" name="data" required>
									
									<div class="calendar-dropdown" id="calendar-dropdown">
										<div class="calendar-header">
											<div class="calendar-month-selector">
												<select class="calendar-select" id="calendar-month" onchange="renderCalendar()">
													<option value="0">Janeiro</option>
													<option value="1">Fevereiro</option>
													<option value="2">Março</option>
													<option value="3">Abril</option>
													<option value="4">Maio</option>
													<option value="5">Junho</option>
													<option value="6">Julho</option>
													<option value="7">Agosto</option>
													<option value="8">Setembro</option>
													<option value="9">Outubro</option>
													<option value="10">Novembro</option>
													<option value="11">Dezembro</option>
												</select>
												<select class="calendar-select" id="calendar-year" onchange="renderCalendar()">
													<!-- Será preenchido via JS -->
												</select>
											</div>
											<button type="button" class="calendar-nav-btn" onclick="goToToday()">
												<i class="fa fa-calendar-check-o"></i>
											</button>
										</div>
										
										<div class="calendar-weekdays">
											<div class="calendar-weekday">D</div>
											<div class="calendar-weekday">S</div>
											<div class="calendar-weekday">T</div>
											<div class="calendar-weekday">Q</div>
											<div class="calendar-weekday">Q</div>
											<div class="calendar-weekday">S</div>
											<div class="calendar-weekday">S</div>
										</div>
										
										<div class="calendar-days" id="calendar-days">
											<!-- Dias serão gerados via JS -->
										</div>
									</div>
								</div>
							</div>						
						</div>
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Seleção de Horário -->
					<div class="row">
						<div class="col-md-12">						
							<div class="form-group"> 
								<label>Selecione o Horário <span class="required">*</span></label>
								<div id="listar-horarios" style="min-height: 60px;">
									<small style="color: #6c757d;">Selecione um profissional e uma data</small>
								</div>
							</div>						
						</div>					
					</div>

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Observações -->
					<div class="form-group"> 
						<label>Observações <small>(Máx 100 Caracteres)</small></label> 
						<input maxlength="100" type="text" class="form-control" name="obs" id="obs" placeholder="Observações do agendamento">
					</div>

					<input type="hidden" name="id" id="id">
					<input type="hidden" name="id_funcionario" id="id_funcionario"> 
					<div id="mensagem" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px;"></div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn-cancel" data-dismiss="modal">
						Cancelar
					</button>
					<button type="submit" class="btn-submit">
						<i class="fa fa-check"></i>
						<span>Confirmar Agendamento</span>
					</button>
				</div>

			</form>

		</div>
	</div>
</div>







<!-- Modal Serviço/Pagamento -->
<div class="modal fade" id="modalServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">
					<div class="modal-title-icon">
						<i class="fa fa-dollar"></i>
					</div>
					<span>Serviço: <span id="titulo_servico"></span></span>
				</div>
				<button id="btn-fechar-servico" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="form-servico">
				<div class="modal-body">

					<!-- Funcionário -->
					<div class="form-group"> 
						<label>Profissional <span class="required">*</span></label> 
						<select class="form-control sel4" id="funcionario_agd" name="funcionario_agd" style="width:100%;" required> 
							<?php 
							$query = $pdo->query("SELECT * FROM usuarios where atendimento = 'Sim' ORDER BY nome asc");
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

					<!-- Divisor -->
					<div class="form-divider"></div>

					<!-- Pagamento Principal -->
					<div class="payment-section">
						<div class="payment-section-header">
							<div class="payment-section-icon">
								<i class="fa fa-credit-card"></i>
							</div>
							<span>Pagamento Principal</span>
						</div>

						<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Valor <span class="required">*</span></label> 
									<input type="text" class="form-control" name="valor_serv_agd" id="valor_serv_agd" placeholder="R$ 0,00" required> 
								</div>						
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Data PGTO</label> 
									<input type="date" class="form-control" name="data_pgto" id="data_pgto" value="<?php echo $data_atual ?>"> 
								</div>						
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Forma PGTO <span class="required">*</span></label> 
									<select class="form-control" id="pgto" name="pgto" style="width:100%;" required> 
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
						</div>
					</div>

					<!-- Pagamento Restante (Opcional) -->
					<div class="payment-section opcional">
						<div class="payment-section-header">
							<div class="payment-section-icon">
								<i class="fa fa-plus-circle"></i>
							</div>
							<span>Pagamento Adicional (Opcional)</span>
						</div>

						<div class="row">
							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Valor Restante</label> 
									<input type="text" class="form-control" name="valor_serv_agd_restante" id="valor_serv_agd_restante" placeholder="R$ 0,00"> 
								</div>						
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Data PGTO Restante</label> 
									<input type="date" class="form-control" name="data_pgto_restante" id="data_pgto_restante"> 
								</div>						
							</div>

							<div class="col-md-4">						
								<div class="form-group"> 
									<label>Forma PGTO Restante</label> 
									<select class="form-control" id="pgto_restante" name="pgto_restante" style="width:100%;" > 
										<option value="">Selecionar</option>
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
						</div>
					</div>

					<!-- Observações -->
					<div class="form-group"> 
						<label>Observações <small>(Máx 1000 Caracteres)</small></label> 
						<input maxlength="1000" type="text" class="form-control" name="obs" id="obs2" placeholder="Observações do serviço"> 
					</div>

					<input type="hidden" name="id_agd" id="id_agd"> 
					<input type="hidden" name="cliente_agd" id="cliente_agd"> 
					<input type="hidden" name="servico_agd" id="servico_agd">
					<input type="hidden" name="descricao_serv_agd" id="descricao_serv_agd">
					
					<div id="mensagem-servico" style="margin-top: 16px; text-align: center; padding: 12px; border-radius: 8px;"></div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn-cancel" data-dismiss="modal">
						Cancelar
					</button>
					<button type="submit" class="btn-submit">
						<i class="fa fa-check"></i>
						<span>Salvar</span>
					</button>
				</div>

			</form>

		</div>
	</div>
</div>






<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<!-- calendar -->
<script type="text/javascript" src="js/monthly.js"></script>
<script type="text/javascript">
	$(window).load( function() {

		$('#mycalendar').monthly({
			mode: 'event',

		});

		$('#mycalendar2').monthly({
			mode: 'picker',
			target: '#mytarget',
			setWidth: '250px',
			startHidden: true,
			showTrigger: '#mytarget',
			stylePast: true,
			disablePast: true
		});

		switch(window.location.protocol) {
			case 'http:':
			case 'https:':
		// running on a server, should be good.
		break;
		case 'file:':
		alert('Just a heads-up, events will not work when run locally.');
	}

});
</script>
<!-- //calendar -->

<script type="text/javascript">
	$(document).ready(function() {
		
		$('.sel3').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			
		});
	});
</script>


<script type="text/javascript">
	$(document).ready(function() {
		
		$('.sel4').select2({
			dropdownParent: $('#modalServico')
		});
	});
</script>



<script>

	$("#form-text").submit(function () {
		$('#mensagem').text('Carregando...');
		event.preventDefault();
		
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag +  "/inserir.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {                    
					$('#btn-fechar').click();
					listar();
					listarHorarios();
				} else {
					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});

</script>




<script type="text/javascript">
	function listar(){

		var funcionario = $('#funcionario_modal').val();

		var data = $("#data_agenda").val();	
		$("#data-modal").val(data);


		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {data, funcionario},
			dataType: "text",

			success:function(result){
				$("#listar").html(result);
			}
		});
	}
</script>




<script type="text/javascript">
	
	function limparCampos(){
		$('#id').val('');		
		$('#obs').val('');
		$('#hora').val('');				
		$('#data').val('<?=$data_atual?>');	

	}
</script>


<script type="text/javascript">
	
	function mudarFuncionario(){
		var funcionario = $('#funcionario').val();
		$('#id_funcionario').val(funcionario);	
		$('#funcionario_modal').val(funcionario).change();

		listar();	
		listarHorarios();
		listarServicos(funcionario);

	}
</script>



<script type="text/javascript">
	
	function mudarFuncionarioModal(){	
		var func = $('#funcionario_modal').val();	
		listar();	
		listarHorarios();
		listarServicos(func);
	}
</script>



<script type="text/javascript">
	
	function mudarData(){
		var data = $('#data-modal').val();			
		$('#data_agenda').val(data).change();

		listar();	
		listarHorarios();

	}
</script>



<script type="text/javascript">
	function listarHorarios(){

		var funcionario = $('#funcionario_modal').val();	
		var data = $('#data_agenda').val();	

		
		$.ajax({
			url: 'paginas/' + pag + "/listar-horarios.php",
			method: 'POST',
			data: {funcionario, data},
			dataType: "text",

			success:function(result){	

				$("#listar-horarios").html(result);
			}
		});
	}
</script>






<script>

	$("#form-servico").submit(function () {
		event.preventDefault();
		
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag +  "/inserir-servico.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-servico').text('');
				$('#mensagem-servico').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {                    
					$('#btn-fechar-servico').click();
					listar();
				} else {
					$('#mensagem-servico').addClass('text-danger')
					$('#mensagem-servico').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});

</script>



<script type="text/javascript">
	function listarServicos(func){	
		$.ajax({
			url: 'paginas/' + pag +  "/listar-servicos.php",
			method: 'POST',
			data: {func: func, is_painel: true},
			dataType: "json",

			success:function(servicos){
				const container = document.getElementById('servico-options');
				container.innerHTML = '';
				
				servicos.forEach(servico => {
					// Determinar classe da tag baseado no nome
					let tagClass = 'default';
					let tagLabel = 'Não informado';
					const nomeServico = servico.nome.toLowerCase();
					
					if(nomeServico.includes('corte')) {
						tagClass = 'corte';
						tagLabel = 'Corte';
					}
					else if(nomeServico.includes('barba')) {
						tagClass = 'barba';
						tagLabel = 'Barba';
					}
					else if(nomeServico.includes('coloração') || nomeServico.includes('coloracao')) {
						tagClass = 'coloracao';
						tagLabel = 'Coloração';
					}
					else if(nomeServico.includes('hidratação') || nomeServico.includes('hidratacao')) {
						tagClass = 'hidratacao';
						tagLabel = 'Hidratação';
					}
					else if(nomeServico.includes('manicure')) {
						tagClass = 'manicure';
						tagLabel = 'Manicure';
					}
					else if(nomeServico.includes('pedicure')) {
						tagClass = 'pedicure';
						tagLabel = 'Pedicure';
					}
					else if(nomeServico.includes('pacote')) {
						tagClass = 'pacote';
						tagLabel = 'Pacote';
					}
					else if(nomeServico.includes('massagem')) {
						tagClass = 'massagem';
						tagLabel = 'Massagem';
					}
					
					// Determinar ícone
					let icone = 'fa-tag';
					if(nomeServico.includes('corte')) icone = 'fa-scissors';
					else if(nomeServico.includes('barba')) icone = 'fa-cut';
					else if(nomeServico.includes('coloração') || nomeServico.includes('coloracao')) icone = 'fa-tint';
					else if(nomeServico.includes('hidratação') || nomeServico.includes('hidratacao')) icone = 'fa-shower';
					else if(nomeServico.includes('manicure') || nomeServico.includes('unha')) icone = 'fa-hand-paper-o';
					else if(nomeServico.includes('massagem')) icone = 'fa-heart';
					else if(nomeServico.includes('pacote')) icone = 'fa-gift';
					
					const valorF = 'R$ ' + parseFloat(servico.valor).toFixed(2).replace('.', ',');
					
					container.innerHTML += `
						<div class="servico-option" data-id="${servico.id}" data-nome="${servico.nome}" data-valor="${valorF}" data-tag="${tagClass}" data-icone="${icone}" data-tag-label="${tagLabel}" onclick="selectServico(this)">
							<div class="servico-tag ${tagClass}">
								<i class="fa ${icone}"></i>
								${tagLabel}
							</div>
							<div class="servico-option-info">
								<div class="servico-option-name">${servico.nome}</div>
								<div class="servico-option-price">${valorF}</div>
							</div>
							<i class="fa fa-check servico-option-check"></i>
						</div>
					`;
				});
			}
		});
	}
</script>

<script type="text/javascript">
	// Select Customizado de Profissional
	function toggleProfissionalDropdown() {
		const dropdown = document.getElementById('profissional-dropdown');
		dropdown.classList.toggle('show');
		
		if(dropdown.classList.contains('show')) {
			document.getElementById('profissional-search-input').focus();
		}
	}

	function selectProfissional(element) {
		const id = element.getAttribute('data-id');
		const nome = element.getAttribute('data-nome');
		const foto = element.getAttribute('data-foto');
		
		// Atualizar campo hidden
		document.getElementById('funcionario_modal').value = id;
		
		// Atualizar display
		document.getElementById('profissional-display').value = nome;
		
		// Atualizar avatar
		document.getElementById('profissional-avatar-selected').src = 'img/perfil/' + foto;
		
		// Remover seleção anterior
		document.querySelectorAll('.profissional-option').forEach(opt => {
			opt.classList.remove('selected');
		});
		
		// Adicionar seleção atual
		element.classList.add('selected');
		
		// Fechar dropdown
		document.getElementById('profissional-dropdown').classList.remove('show');
		
		// Chamar função original se existir
		if(typeof mudarFuncionarioModal === 'function') {
			mudarFuncionarioModal();
		}
	}

	function filterProfissionais() {
		const searchValue = document.getElementById('profissional-search-input').value.toLowerCase();
		const options = document.querySelectorAll('.profissional-option');
		
		options.forEach(option => {
			const nome = option.getAttribute('data-nome').toLowerCase();
			if(nome.includes(searchValue)) {
				option.style.display = 'flex';
			} else {
				option.style.display = 'none';
			}
		});
	}

	// Fechar dropdown ao clicar fora
	document.addEventListener('click', function(event) {
		const profWrapper = document.querySelector('.profissional-select-wrapper');
		const profDropdown = document.getElementById('profissional-dropdown');
		const clienteWrapper = document.querySelector('.cliente-select-wrapper');
		const clienteDropdown = document.getElementById('cliente-dropdown');
		const servicoWrapper = document.querySelector('.servico-select-wrapper');
		const servicoDropdown = document.getElementById('servico-dropdown');
		
		if(profWrapper && profDropdown && !profWrapper.contains(event.target)) {
			profDropdown.classList.remove('show');
		}
		
		if(clienteWrapper && clienteDropdown && !clienteWrapper.contains(event.target)) {
			clienteDropdown.classList.remove('show');
		}
		
		if(servicoWrapper && servicoDropdown && !servicoWrapper.contains(event.target)) {
			servicoDropdown.classList.remove('show');
		}
	});

	// Select Customizado de Cliente
	function toggleClienteDropdown() {
		const dropdown = document.getElementById('cliente-dropdown');
		dropdown.classList.toggle('show');
		
		if(dropdown.classList.contains('show')) {
			document.getElementById('cliente-search-input').focus();
		}
	}

	function selectCliente(element) {
		const id = element.getAttribute('data-id');
		const nome = element.getAttribute('data-nome');
		const iniciais = element.getAttribute('data-iniciais');
		
		// Atualizar campo hidden
		document.getElementById('cliente').value = id;
		
		// Atualizar display
		document.getElementById('cliente-display').value = nome;
		
		// Atualizar avatar com iniciais
		document.getElementById('cliente-avatar-selected').textContent = iniciais;
		
		// Remover seleção anterior
		document.querySelectorAll('.cliente-option').forEach(opt => {
			opt.classList.remove('selected');
		});
		
		// Adicionar seleção atual
		element.classList.add('selected');
		
		// Fechar dropdown
		document.getElementById('cliente-dropdown').classList.remove('show');
	}

	function filterClientes() {
		const searchValue = document.getElementById('cliente-search-input').value.toLowerCase();
		const options = document.querySelectorAll('.cliente-option');
		
		options.forEach(option => {
			const nome = option.getAttribute('data-nome').toLowerCase();
			const telefone = option.getAttribute('data-telefone').toLowerCase();
			if(nome.includes(searchValue) || telefone.includes(searchValue)) {
				option.style.display = 'flex';
			} else {
				option.style.display = 'none';
			}
		});
	}

	// Select Customizado de Serviço
	function toggleServicoDropdown() {
		const dropdown = document.getElementById('servico-dropdown');
		dropdown.classList.toggle('show');
		
		if(dropdown.classList.contains('show')) {
			document.getElementById('servico-search-input').focus();
		}
	}

	function selectServico(element) {
		const id = element.getAttribute('data-id');
		const nome = element.getAttribute('data-nome');
		const valor = element.getAttribute('data-valor');
		const tagClass = element.getAttribute('data-tag');
		const tagLabel = element.getAttribute('data-tag-label');
		const icone = element.getAttribute('data-icone');
		
		// Atualizar campo hidden
		document.getElementById('servico').value = id;
		
		// Atualizar display
		document.getElementById('servico-display').value = nome + ' - ' + valor;
		
		// Atualizar tag
		const tagSelected = document.getElementById('servico-tag-selected');
		tagSelected.className = 'servico-tag-selected servico-tag ' + tagClass;
		tagSelected.innerHTML = '<i class="fa ' + icone + '"></i> ' + tagLabel;
		tagSelected.style.display = 'flex';
		
		// Remover seleção anterior
		document.querySelectorAll('.servico-option').forEach(opt => {
			opt.classList.remove('selected');
		});
		
		// Adicionar seleção atual
		element.classList.add('selected');
		
		// Fechar dropdown
		document.getElementById('servico-dropdown').classList.remove('show');
	}

	function filterServicos() {
		const searchValue = document.getElementById('servico-search-input').value.toLowerCase();
		const options = document.querySelectorAll('.servico-option');
		
		options.forEach(option => {
			const nome = option.getAttribute('data-nome').toLowerCase();
			if(nome.includes(searchValue)) {
				option.style.display = 'flex';
			} else {
				option.style.display = 'none';
			}
		});
	}

	// Date Picker Customizado
	let currentCalendarDate = new Date();
	let selectedCalendarDate = null;

	function toggleCalendarDropdown() {
		const dropdown = document.getElementById('calendar-dropdown');
		const display = document.querySelector('.data-picker-display');
		
		dropdown.classList.toggle('show');
		display.classList.toggle('open');
		
		if(dropdown.classList.contains('show')) {
			// Inicializar calendário se necessário
			initializeCalendar();
		}
	}

	function initializeCalendar() {
		// Preencher anos (ano atual até +5 anos)
		const yearSelect = document.getElementById('calendar-year');
		if(yearSelect.options.length === 0) {
			const currentYear = new Date().getFullYear();
			for(let year = currentYear; year <= currentYear + 5; year++) {
				const option = document.createElement('option');
				option.value = year;
				option.textContent = year;
				yearSelect.appendChild(option);
			}
		}
		
		// Definir mês e ano atuais
		const now = new Date();
		document.getElementById('calendar-month').value = now.getMonth();
		document.getElementById('calendar-year').value = now.getFullYear();
		
		renderCalendar();
	}

	function renderCalendar() {
		const month = parseInt(document.getElementById('calendar-month').value);
		const year = parseInt(document.getElementById('calendar-year').value);
		const daysContainer = document.getElementById('calendar-days');
		
		currentCalendarDate = new Date(year, month, 1);
		
		// Limpar dias
		daysContainer.innerHTML = '';
		
		// Primeiro dia do mês e último dia do mês
		const firstDay = new Date(year, month, 1);
		const lastDay = new Date(year, month + 1, 0);
		const prevLastDay = new Date(year, month, 0);
		
		const firstDayWeek = firstDay.getDay();
		const lastDayDate = lastDay.getDate();
		const prevLastDayDate = prevLastDay.getDate();
		
		const today = new Date();
		today.setHours(0, 0, 0, 0);
		
		// Dias do mês anterior
		for(let i = firstDayWeek - 1; i >= 0; i--) {
			const dayNum = prevLastDayDate - i;
			const dayEl = document.createElement('div');
			dayEl.className = 'calendar-day other-month';
			dayEl.textContent = dayNum;
			daysContainer.appendChild(dayEl);
		}
		
		// Dias do mês atual
		for(let day = 1; day <= lastDayDate; day++) {
			const date = new Date(year, month, day);
			date.setHours(0, 0, 0, 0);
			
			const dayEl = document.createElement('div');
			dayEl.className = 'calendar-day';
			dayEl.textContent = day;
			
			// Marcar hoje
			if(date.getTime() === today.getTime()) {
				dayEl.classList.add('today');
			}
			
			// Desabilitar datas passadas
			if(date < today) {
				dayEl.classList.add('disabled');
			}
			
			// Marcar data selecionada
			if(selectedCalendarDate) {
				const selected = new Date(selectedCalendarDate);
				selected.setHours(0, 0, 0, 0);
				if(date.getTime() === selected.getTime()) {
					dayEl.classList.add('selected');
				}
			}
			
			// Adicionar evento de clique
			if(date >= today) {
				dayEl.onclick = function() {
					selectCalendarDate(year, month, day);
				};
			}
			
			daysContainer.appendChild(dayEl);
		}
		
		// Dias do próximo mês (para completar a grid)
		const lastDayWeek = lastDay.getDay();
		const remainingDays = 6 - lastDayWeek;
		
		for(let i = 1; i <= remainingDays; i++) {
			const dayEl = document.createElement('div');
			dayEl.className = 'calendar-day other-month';
			dayEl.textContent = i;
			daysContainer.appendChild(dayEl);
		}
	}

	function selectCalendarDate(year, month, day) {
		const date = new Date(year, month, day);
		const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
		
		selectedCalendarDate = dateStr;
		
		// Atualizar campo hidden
		document.getElementById('data-modal').value = dateStr;
		
		// Atualizar display
		const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
		const displayText = day + ' de ' + meses[month] + ' de ' + year;
		
		const textEl = document.getElementById('data-display-text');
		textEl.textContent = displayText;
		textEl.classList.add('selected');
		
		// Fechar dropdown
		document.getElementById('calendar-dropdown').classList.remove('show');
		document.querySelector('.data-picker-display').classList.remove('open');
		
		// Chamar função original se existir
		if(typeof mudarData === 'function') {
			mudarData();
		}
		
		// Re-renderizar para mostrar seleção
		renderCalendar();
	}

	function goToToday() {
		const today = new Date();
		document.getElementById('calendar-month').value = today.getMonth();
		document.getElementById('calendar-year').value = today.getFullYear();
		renderCalendar();
	}

	// Fechar calendário ao clicar fora
	document.addEventListener('click', function(event) {
		const wrapper = document.querySelector('.data-picker-wrapper');
		const dropdown = document.getElementById('calendar-dropdown');
		
		if(wrapper && dropdown && !wrapper.contains(event.target)) {
			dropdown.classList.remove('show');
			const display = document.querySelector('.data-picker-display');
			if(display) display.classList.remove('open');
		}
	});
</script>