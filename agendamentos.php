<?php 
@session_start();
require_once("cabecalho.php");
$data_atual = date('Y-m-d');
unset($_SESSION['usuario_logado_pagina']);
$_SESSION['usuario_logado_pagina'] = true;
?>

<style type="text/css">
	.sub_page .hero_area {
		min-height: auto;
	}
</style>

</div>

<!-- Hero Section Agendamentos Minimal -->
<section class="booking-hero-minimal">
	<div class="booking-hero-wrapper">
		<h1 class="hero-title-minimal">Agende seu Atendimento</h1>
		<p class="hero-subtitle-minimal">Siga as etapas abaixo para agendar seu horário</p>
	</div>
</section>

<!-- Seção de Agendamento -->
<section class="booking-section">
	<div class="booking-container">
		<!-- Indicador de Etapas Moderno -->
		<div class="steps-progress">
			<div class="step-item active" id="step-indicator-1">
				<div class="step-circle">
					<span class="step-number">1</span>
					<i data-lucide="scissors" class="step-icon"></i>
				</div>
				<span class="step-label">Serviço</span>
			</div>
			<div class="progress-line"></div>
			<div class="step-item" id="step-indicator-2">
				<div class="step-circle">
					<span class="step-number">2</span>
					<i data-lucide="user" class="step-icon"></i>
				</div>
				<span class="step-label">Profissional</span>
			</div>
			<div class="progress-line"></div>
			<div class="step-item" id="step-indicator-3">
				<div class="step-circle">
					<span class="step-number">3</span>
					<i data-lucide="calendar" class="step-icon"></i>
				</div>
				<span class="step-label">Data e Hora</span>
			</div>
			<div class="progress-line"></div>
			<div class="step-item" id="step-indicator-4">
				<div class="step-circle">
					<span class="step-number">4</span>
					<i data-lucide="user-check" class="step-icon"></i>
				</div>
				<span class="step-label">Seus Dados</span>
			</div>
		</div>

		<!-- Formulário de Agendamento -->
		<form class="booking-form" id="form-agenda" method="post">
			
			<!-- Etapa 1: Escolha do Serviço -->
			<div class="form-step-modern active" id="step-1-content">
				<div class="step-header-modern">
					<h3 class="step-title-modern">Escolha o Serviço Desejado</h3>
					<p class="step-subtitle-modern">Selecione o serviço que você gostaria de agendar</p>
				</div>

				<?php 
				// Busca categorias
				$query_cat = $pdo->query("SELECT * FROM cat_servicos ORDER BY id asc");
				$res_cat = $query_cat->fetchAll(PDO::FETCH_ASSOC);
				$categorias = [];
				foreach($res_cat as $cat) {
					$categorias[] = [
						'id' => $cat['id'],
						'nome' => $cat['nome']
					];
				}

				// Busca serviços
				$query = $pdo->query("SELECT s.*, c.nome as categoria_nome FROM servicos s LEFT JOIN cat_servicos c ON s.categoria = c.id where s.ativo = 'Sim' ORDER BY s.nome asc");
				$res = $query->fetchAll(PDO::FETCH_ASSOC);
				$servicos_booking = [];
				foreach($res as $serv){
					$servicos_booking[] = [
						'id' => $serv['id'],
						'nome' => $serv['nome'],
						'valor' => $serv['valor'],
						'foto' => $serv['foto'],
						'tempo' => $serv['tempo'],
						'categoria' => $serv['categoria'] ?? 'all',
						'categoria_nome' => $serv['categoria_nome'] ?? 'Geral'
					];
				}
				?>
				
				<!-- Vue App para Filtro e Seleção -->
				<div id="booking-services-app" 
					 data-services='<?php echo json_encode($servicos_booking, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
					 data-categories='<?php echo json_encode($categorias, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
					
					<!-- Filtro por Categoria -->
					<div class="category-filter">
						<button @click="selectedCategory = 'all'" 
								:class="['category-btn', selectedCategory === 'all' ? 'active' : '']">
							<i data-lucide="grid-3x3"></i>
							<span>Todos</span>
						</button>
						<button v-for="cat in categories" :key="cat.id"
								@click="selectedCategory = cat.id" 
								:class="['category-btn', selectedCategory === cat.id ? 'active' : '']">
							<i :data-lucide="getCategoryIcon(cat.nome)"></i>
							<span>{{ cat.nome }}</span>
						</button>
					</div>

				<!-- Grid de Serviços -->
				<div class="services-grid-booking-modern">
					<div v-for="service in filteredServices" :key="service.id" 
						 @click="selectService(service)"
						 :class="['service-card-modern', selectedService?.id === service.id ? 'selected' : '']">
						<div class="service-image-modern">
							<img :src="'sistema/painel/img/servicos/' + service.foto" :alt="service.nome">
							<div class="service-check-badge">
								<i data-lucide="check-circle"></i>
							</div>
						</div>
						<div class="service-content-modern">
							<div class="service-info-left">
								<h4 class="service-name-modern">{{ service.nome }}</h4>
								<div class="service-meta-modern">
									<div class="service-duration">
										<i data-lucide="clock"></i>
										<span>{{ service.tempo }} min</span>
									</div>
								</div>
							</div>
							<div class="service-price-modern">
								<span class="price-label-modern">A partir de</span>
								<span class="price-value-modern">R$ {{ formatPrice(service.valor) }}</span>
							</div>
						</div>
						<!-- Botão Selecionar - apenas desktop -->
						<button type="button" class="btn-select-item" @click.stop="selectServiceAndNext(service)">
							<span>Selecionar</span>
							<i data-lucide="arrow-right"></i>
						</button>
					</div>
				</div>

					<!-- Botão Sticky Mobile (Aparece quando seleciona) -->
					<div class="sticky-footer-mobile" :class="{ 'active': selectedService }">
							<div v-if="selectedService" class="sticky-service-info">
								<div class="sticky-service-details">
									<span class="sticky-service-name">{{ selectedService.nome }}</span>
									<span class="sticky-service-time">
										<i data-lucide="clock"></i>
										{{ selectedService.tempo }} min
									</span>
								</div>
								<div class="sticky-service-price">R$ {{ formatPrice(selectedService.valor) }}</div>
							</div>
							<button v-if="selectedService" type="button" class="btn-sticky-continue" @click="goToNextStep">
								<span>Continuar</span>
								<i data-lucide="arrow-right"></i>
							</button>
					</div>
				</div>
				
				<!-- Campo oculto -->
				<select id="servico" name="servico" style="display: none;" required>
					<option value="">Selecione um Serviço</option>
					<?php 
					foreach($servicos_booking as $serv){
						$valor = $serv['valor'];
						$valorF = number_format($valor, 2, ',', '.');
						echo '<option value="'.$serv['id'].'" data-foto="'.$serv['foto'].'" data-tempo="'.$serv['tempo'].'">'.$serv['nome'].' - R$ '.$valorF.'</option>';
					}
					?>
				</select>
				
				<div class="step-actions-modern">
					<button type="button" class="btn-next-modern next-step" data-step="1">
						<span>Próximo</span>
						<i data-lucide="chevron-right"></i>
					</button>
				</div>
			</div>
          
          <!-- Etapa 2: Escolha do Profissional -->
          <div class="form-step-modern" id="step-2-content">
            <div class="step-header-modern">
              <div class="step-header-with-back">
                <button type="button" class="btn-back-arrow prev-step" data-step="2">
                  <i data-lucide="arrow-left"></i>
                </button>
                <div class="step-header-text">
                  <h3 class="step-title-modern">Escolha o Profissional</h3>
                  <p class="step-subtitle-modern">Selecione o profissional que irá realizar seu atendimento</p>
                </div>
              </div>
            </div>
            
            <!-- Grid de Profissionais -->
            <div class="professionals-grid" id="professional-cards-container">
              <!-- Os cards serão carregados via JavaScript -->
              <div class="loading-state">
                <div class="spinner-modern"></div>
                <p>Carregando profissionais...</p>
              </div>
            </div>
            
            <!-- Campo oculto -->
            <input type="hidden" id="funcionario" name="funcionario" required>
            
            <!-- Sticky Footer Mobile - Sem Profissionais -->
            <div class="sticky-footer-empty-2" style="display: none;">
              <button type="button" class="btn-sticky-back-full prev-step" data-step="2">
                <i data-lucide="arrow-left"></i>
                <span>Voltar</span>
              </button>
            </div>
            
            <!-- Sticky Footer Mobile Etapa 2 (Aparece quando seleciona profissional) -->
            <div class="sticky-footer-step-2" style="display: none;">
              <div class="sticky-service-info">
                <div class="sticky-service-details">
                  <span class="sticky-service-name" id="sticky-prof-name">-</span>
                  <span class="sticky-service-time">
                    <i data-lucide="user"></i>
                    Profissional
                  </span>
                </div>
              </div>
              <div class="sticky-actions-group">
                <button type="button" class="btn-sticky-back prev-step" data-step="2">
                  <i data-lucide="arrow-left"></i>
                  <span>Voltar</span>
                </button>
                <button type="button" class="btn-sticky-continue next-step" data-step="2">
                  <span>Continuar</span>
                  <i data-lucide="arrow-right"></i>
                </button>
              </div>
            </div>
            
            <div class="step-actions-modern">
              <button type="button" class="btn-prev-modern prev-step" data-step="2">
                <i data-lucide="chevron-left"></i>
                <span>Anterior</span>
              </button>
              <button type="button" class="btn-next-modern next-step" data-step="2">
                <span>Próximo</span>
                <i data-lucide="chevron-right"></i>
              </button>
            </div>
          </div>
          
          <!-- Etapa 3: Escolha da Data e Hora -->
          <div class="form-step-modern" id="step-3-content">
            <div class="step-header-modern">
              <h3 class="step-title-modern">Escolha a Data e Horário</h3>
              <p class="step-subtitle-modern">Selecione uma data e horário para seu atendimento</p>
            </div>

            <!-- Vue App para Calendário e Horários -->
            <div id="booking-datetime-app">
              <!-- Seletor de Mês -->
              <div class="month-selector">
                <button type="button" @click="changeMonth(-1)" class="month-nav-btn">
                  <i data-lucide="chevron-left"></i>
                </button>
                <div class="month-display">
                  <i data-lucide="calendar"></i>
                  <span>{{ currentMonthName }} de {{ currentYear }}</span>
                </div>
                <button type="button" @click="changeMonth(1)" class="month-nav-btn">
                  <i data-lucide="chevron-right"></i>
                </button>
              </div>

              <!-- Calendário Horizontal -->
              <div class="calendar-horizontal">
                <div v-for="day in daysInMonth" :key="day.date"
                     @click="selectDate(day)"
                     :class="['day-circle', selectedDate === day.dateStr ? 'selected' : '', day.isPast ? 'disabled' : '']">
                  <span class="day-number">{{ day.day }}</span>
                  <span class="day-name">{{ day.weekDay }}</span>
                </div>
              </div>

              <!-- Lista de Horários -->
              <div class="schedule-section">
                <div v-if="loadingTimes" class="loading-state">
                  <div class="spinner-modern"></div>
                  <p>Carregando horários...</p>
                </div>

                <div v-else-if="availableTimes.length === 0" class="empty-schedule">
                  <div class="empty-schedule-icon">
                    <i data-lucide="calendar-x"></i>
                  </div>
                  <h4 class="empty-schedule-title">O profissional selecionado não tem horários disponíveis nesta data</h4>
                  <p class="empty-schedule-subtitle">Disponível a partir de ter., 4 de nov.</p>
                  <button type="button" class="btn-next-available" @click="goToNextAvailable">
                    Ir para a próxima data disponível
                  </button>
                </div>

                <div v-else class="times-grid">
                  <div v-for="time in availableTimes" :key="time"
                       @click="selectTime(time)"
                       :class="['time-slot', selectedTime === time ? 'selected' : '']">
                    {{ time }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Campos ocultos -->
            <input onchange="mudarFuncionario()" type="date" name="data" id="data" value="<?php echo $data_atual ?>" style="display: none;" required />
            <input type="hidden" id="hora_rec" name="hora">
            <div id="listar-horarios" style="display: none;"></div>

            <!-- Sticky Footer Mobile - Sem Horários -->
            <div class="sticky-footer-empty-3" style="display: none;">
              <button type="button" class="btn-sticky-back-full prev-step" data-step="3">
                <i data-lucide="arrow-left"></i>
                <span>Voltar</span>
              </button>
            </div>

            <!-- Sticky Footer Mobile Etapa 3 (Aparece quando seleciona data E horário) -->
            <div class="sticky-footer-step-3" style="display: none;">
              <div class="sticky-service-info">
                <div class="sticky-service-details">
                  <span class="sticky-service-name" id="sticky-datetime-info">-</span>
                  <span class="sticky-service-time">
                    <i data-lucide="calendar"></i>
                    <span id="sticky-date-text">-</span>
                  </span>
                </div>
                <div class="sticky-service-price" id="sticky-time-text">-</div>
              </div>
              <div class="sticky-actions-group">
                <button type="button" class="btn-sticky-back prev-step" data-step="3">
                  <i data-lucide="arrow-left"></i>
                  <span>Voltar</span>
                </button>
                <button type="button" class="btn-sticky-continue next-step" data-step="3" id="btn-proximo-horario-mobile">
                  <span>Continuar</span>
                  <i data-lucide="arrow-right"></i>
                </button>
              </div>
            </div>

            <div class="step-actions-modern">
              <button type="button" class="btn-prev-modern prev-step" data-step="3">
                <i data-lucide="chevron-left"></i>
                <span>Anterior</span>
              </button>
              <button type="button" class="btn-next-modern next-step" data-step="3" id="btn-proximo-horario">
                <span>Próximo</span>
                <i data-lucide="chevron-right"></i>
              </button>
            </div>
          </div>
          
          <!-- Etapa 4: Dados Pessoais -->
          <div class="form-step-modern" id="step-4-content">
            <div class="step-header-modern">
              <h3 class="step-title-modern">Seus Dados</h3>
              <p class="step-subtitle-modern">Preencha seus dados para confirmar o agendamento</p>
            </div>
            
            <div class="form-grid-modern">
              <div class="form-group-step">
                <label class="label-step">
                  <i data-lucide="phone"></i>
                  <span>Telefone com DDD</span>
                </label>
                <input onkeyup="buscarNome()" class="input-modern-step" type="text" name="telefone" id="telefone" placeholder="(00) 00000-0000" required />
              </div>
              
              <div class="form-group-step">
                <label class="label-step">
                  <i data-lucide="user"></i>
                  <span>Nome Completo</span>
                </label>
                <input onclick="buscarNome()" class="input-modern-step" type="text" name="nome" id="nome" placeholder="Digite seu nome completo" required />
              </div>
              
              <div class="form-group-step full-width">
                <label class="label-step">
                  <i data-lucide="message-square"></i>
                  <span>Observações (opcional)</span>
                </label>
                <textarea maxlength="100" class="textarea-modern-step" name="obs" id="obs" placeholder="Alguma observação especial? (opcional)" rows="3"></textarea>
              </div>
            </div>

            <!-- Resumo do Agendamento -->
            <div class="summary-card-modern">
              <h4 class="summary-title">
                <i data-lucide="check-circle"></i>
                <span>Resumo do Agendamento</span>
              </h4>
              
              <div class="summary-items">
                <div class="summary-item">
                  <div class="summary-icon">
                    <i data-lucide="scissors"></i>
                  </div>
                  <div class="summary-info">
                    <span class="summary-label">Serviço</span>
                    <div class="summary-value-row">
                      <span class="summary-value" id="final-servico">-</span>
                      <span class="summary-price" id="final-servico-valor">-</span>
                    </div>
                  </div>
                </div>
                
                <div class="summary-item">
                  <div class="summary-icon">
                    <i data-lucide="user"></i>
                  </div>
                  <div class="summary-info">
                    <span class="summary-label">Profissional</span>
                    <span class="summary-value" id="final-profissional">-</span>
                  </div>
                </div>
                
                <div class="summary-item">
                  <div class="summary-icon">
                    <i data-lucide="calendar"></i>
                  </div>
                  <div class="summary-info">
                    <span class="summary-label">Data</span>
                    <span class="summary-value" id="final-data">-</span>
                  </div>
                </div>
                
                <div class="summary-item">
                  <div class="summary-icon">
                    <i data-lucide="clock"></i>
                  </div>
                  <div class="summary-info">
                    <span class="summary-label">Horário</span>
                    <span class="summary-value" id="final-horario">-</span>
                  </div>
                </div>
              </div>
            </div>

            <div id="mensagem" class="mensagem-step"></div>
            
            <!-- Sticky Footer Mobile Etapa 4 -->
            <div class="sticky-footer-step-4">
              <div class="sticky-actions-group">
                <button type="button" class="btn-sticky-back prev-step" data-step="4">
                  <i data-lucide="arrow-left"></i>
                  <span>Voltar</span>
                </button>
                <button class="btn-sticky-confirm" type="submit" id="btn_agendar_mobile">
                  <i data-lucide="calendar-check"></i>
                  <span id='botao_salvar_mobile'>Confirmar</span>
                </button>
              </div>
            </div>
            
            <div class="step-actions-modern">
              <button type="button" class="btn-prev-modern prev-step" data-step="4">
                <i data-lucide="chevron-left"></i>
                <span>Anterior</span>
              </button>
              <button class="btn-confirm-modern" type="submit" id="btn_agendar">
                <i data-lucide="calendar-check"></i>
                <span id='botao_salvar'>Confirmar Agendamento</span>
              </button>
            </div>
          </div>
          
          <!-- Mensagem de retorno -->
          <div class="mt-3">
            <div id="mensagem" align="center"></div>
          </div>
          
          <!-- Campos ocultos -->
          <input type="text" id="data_oculta" style="display: none">
          <input type="hidden" id="id" name="id">
          <input type="hidden" id="nome_func" name="nome_func">
          <input type="hidden" id="data_rec" name="data_rec">
          <input type="hidden" id="nome_serv" name="nome_serv">
        </div>
      </form>
      
		</form>
	</div>
</section>

<style>
	/* Hero Agendamentos Minimal */
	.booking-hero-minimal {
		background: linear-gradient(135deg, #006854 0%, #005246 100%);
		padding: 40px 0;
		text-align: center;
	}

	.booking-hero-wrapper {
		max-width: 700px;
		margin: 0 auto;
		padding: 0 20px;
	}

	.hero-title-minimal {
		font-size: 32px;
		font-weight: 800;
		color: #FEFEFE;
		margin-bottom: 12px;
		letter-spacing: -0.5px;
	}

	.hero-subtitle-minimal {
		font-size: 15px;
		color: rgba(254, 254, 254, 0.75);
		margin: 0;
		font-weight: 400;
	}

	/* Seção de Agendamento */
	.booking-section {
		padding: 40px 0;
		background: #f3ede0;
		min-height: auto;
	}

	.booking-container {
		max-width: 1000px;
		margin: 0 auto;
		padding: 0 40px;
	}

	/* Steps Progress Moderno */
	.steps-progress {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 40px;
		padding: 0 20px;
	}

	.step-item {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 12px;
		position: relative;
		z-index: 2;
	}

	.step-circle {
		width: 64px;
		height: 64px;
		border-radius: 50%;
		background: rgba(0, 0, 0, 0.1);
		border: 3px solid rgba(0, 0, 0, 0.1);
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		transition: all 0.3s ease;
	}

	.step-number {
		font-size: 20px;
		font-weight: 800;
		color: #999;
		transition: all 0.3s ease;
	}

	.step-icon {
		display: none;
		width: 28px;
		height: 28px;
		color: #FEFEFE;
		stroke: #FEFEFE;
		stroke-width: 2.5px;
	}

	.step-label {
		font-size: 14px;
		font-weight: 600;
		color: #999;
		text-align: center;
		transition: all 0.3s ease;
	}

	.step-item.active .step-circle {
		background: #007A63;
		border-color: #007A63;
		box-shadow: 0 4px 16px rgba(0, 122, 99, 0.3);
	}

	.step-item.active .step-number {
		display: none;
	}

	.step-item.active .step-icon {
		display: block;
	}

	.step-item.active .step-label {
		color: #007A63;
	}

	.step-item.completed .step-circle {
		background: #B9E4D4;
		border-color: #B9E4D4;
	}

	.step-item.completed .step-number {
		color: #007A63;
	}

	.step-item.completed .step-label {
		color: #007A63;
	}

	.progress-line {
		flex: 1;
		height: 3px;
		background: rgba(0, 0, 0, 0.1);
		position: relative;
		margin: 0 12px;
		border-radius: 3px;
	}

	.step-item.completed ~ .progress-line {
		background: #B9E4D4;
	}

	/* Formulário */
	.booking-form {
		background: #007A63;
		border-radius: 24px;
		padding: 48px;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
	}

	.form-step-modern {
		display: none;
	}

	.form-step-modern.active {
		display: block;
		animation: fadeIn 0.4s ease-out;
	}

	@keyframes fadeIn {
		from {
			opacity: 0;
			transform: translateY(20px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.step-header-modern {
		text-align: center;
		margin-bottom: 32px;
	}
	
	.step-header-with-back {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 20px;
		position: relative;
	}
	
	.btn-back-arrow {
		width: 48px;
		height: 48px;
		background: rgba(255, 255, 255, 0.1);
		border: 2px solid rgba(255, 255, 255, 0.2);
		border-radius: 50%;
		display: none; /* Oculto por padrão (mobile) */
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.3s ease;
		flex-shrink: 0;
	}
	
	.btn-back-arrow:hover {
		background: rgba(255, 255, 255, 0.15);
		border-color: #B9E4D4;
		transform: translateX(-4px);
	}
	
	.btn-back-arrow i {
		width: 24px;
		height: 24px;
		color: #FEFEFE;
		stroke: #FEFEFE;
		stroke-width: 2.5px;
	}
	
	.step-header-text {
		flex: 1;
		text-align: center;
	}

	.step-title-modern {
		font-size: 32px;
		font-weight: 800;
		color: #FEFEFE;
		margin-bottom: 12px;
		letter-spacing: -0.5px;
	}

	.step-subtitle-modern {
		font-size: 16px;
		color: rgba(254, 254, 254, 0.8);
	}

	/* Filtro de Categorias */
	.category-filter {
		display: flex;
		gap: 12px;
		margin-bottom: 40px;
		flex-wrap: nowrap;
		justify-content: center;
		overflow-x: auto;
		padding-bottom: 8px;
		-webkit-overflow-scrolling: touch;
	}

	.category-filter::-webkit-scrollbar {
		height: 6px;
	}

	.category-filter::-webkit-scrollbar-track {
		background: rgba(255, 255, 255, 0.1);
		border-radius: 3px;
	}

	.category-filter::-webkit-scrollbar-thumb {
		background: rgba(185, 228, 212, 0.4);
		border-radius: 3px;
	}

	.category-filter::-webkit-scrollbar-thumb:hover {
		background: #B9E4D4;
	}

	.category-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 24px;
		background: rgba(255, 255, 255, 0.1);
		border: 2px solid rgba(255, 255, 255, 0.2);
		border-radius: 50px;
		color: rgba(254, 254, 254, 0.8);
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.category-btn i {
		width: 18px;
		height: 18px;
		color: rgba(254, 254, 254, 0.8);
		stroke: rgba(254, 254, 254, 0.8);
	}

	.category-btn:hover {
		border-color: #B9E4D4;
		color: #B9E4D4;
		background: rgba(185, 228, 212, 0.1);
	}

	.category-btn:hover i {
		color: #B9E4D4;
		stroke: #B9E4D4;
	}

	.category-btn.active {
		background: #007A63;
		border-color: #B9E4D4;
		color: #FEFEFE;
	}

	.category-btn.active i {
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	/* Grid de Serviços Modernos - PADRÃO MOBILE (cards) */
	.services-grid-booking-modern {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 24px;
		margin-bottom: 40px;
	}

	.service-card-modern {
		background: #2a2a2a;
		border-radius: 20px;
		padding: 16px;
		cursor: pointer;
		border: 3px solid #2a2a2a;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		position: relative;
	}

	.service-card-modern:hover {
		transform: translateY(-8px);
		border-color: #B9E4D4;
		box-shadow: 0 12px 28px rgba(185, 228, 212, 0.15);
	}

	.service-card-modern.selected {
		border-color: #B9E4D4;
		box-shadow: 0 15px 40px rgba(185, 228, 212, 0.25);
	}

	.service-image-modern {
		position: relative;
		width: 100%;
		height: 240px;
		border-radius: 16px;
		overflow: hidden;
		background: #1a1a1a;
		margin-bottom: 16px;
	}

	.service-image-modern img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		transition: transform 0.5s ease;
	}

	.service-card-modern:hover .service-image-modern img {
		transform: scale(1.08);
	}

	.service-check-badge {
		position: absolute;
		top: 12px;
		right: 12px;
		width: 36px;
		height: 36px;
		background: #B9E4D4;
		border-radius: 50%;
		display: none;
		align-items: center;
		justify-content: center;
		box-shadow: 0 4px 12px rgba(185, 228, 212, 0.5);
	}

	.service-check-badge i {
		width: 22px;
		height: 22px;
		color: #1a1a1a;
		stroke: #1a1a1a;
		stroke-width: 2.5px;
	}

	.service-card-modern.selected .service-check-badge {
		display: flex;
		animation: scaleIn 0.3s ease-out;
	}

	@keyframes scaleIn {
		from {
			transform: scale(0);
		}
		to {
			transform: scale(1);
		}
	}

	.service-content-modern {
		padding: 0 6px;
	}

	.service-name-modern {
		font-size: 19px;
		font-weight: 700;
		color: #FEFEFE;
		margin-bottom: 14px;
		line-height: 1.3;
	}

	.service-meta-modern {
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
		gap: 12px;
	}

	.service-price-modern {
		display: flex;
		flex-direction: column;
		gap: 4px;
	}

	.price-label-modern {
		font-size: 11px;
		color: rgba(254, 254, 254, 0.6);
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.price-value-modern {
		font-size: 22px;
		font-weight: 800;
		color: #B9E4D4;
	}

	.service-duration {
		display: flex;
		align-items: center;
		gap: 6px;
		padding: 6px 12px;
		background: rgba(185, 228, 212, 0.15);
		border-radius: 8px;
		font-size: 13px;
		color: #B9E4D4;
		font-weight: 600;
	}

	.service-duration i {
		width: 16px;
		height: 16px;
		color: #B9E4D4;
		stroke: #B9E4D4;
	}
	
	/* Desktop: Formato de Lista */
	@media (min-width: 769px) {
		.services-grid-booking-modern {
			display: flex;
			flex-direction: column;
			gap: 12px;
		}

		.service-card-modern {
			display: flex;
			flex-direction: row;
			align-items: center;
			gap: 20px;
			padding: 20px 24px;
			border: 2px solid transparent;
		}

		.service-card-modern:hover {
			transform: translateX(4px);
			border-color: rgba(185, 228, 212, 0.3);
		}

		.service-image-modern {
			width: 90px;
			height: 90px;
			border-radius: 14px;
			margin-bottom: 0;
			flex-shrink: 0;
		}

		.service-content-modern {
			flex: 1;
			display: flex;
			align-items: center;
			gap: 24px;
		}

		.service-info-left {
			flex: 1;
		}

		.service-name-modern {
			font-size: 18px;
			margin-bottom: 6px;
		}

		.service-meta-modern {
			display: flex;
			align-items: center;
			gap: 20px;
		}

		.service-price-modern {
			display: flex;
			align-items: baseline;
			gap: 6px;
		}

		.price-value-modern {
			font-size: 24px;
		}

		.service-check-badge {
			top: -6px;
			right: -6px;
			width: 28px;
			height: 28px;
			border: 3px solid #2a2a2a;
		}

		.service-check-badge i {
			width: 16px;
			height: 16px;
		}
		
		/* Botão selecionar em formato lista (desktop) */
		.service-card-modern .btn-select-item {
			margin-top: 0;
			margin-left: auto;
			width: auto;
			min-width: 140px;
			display: inline-flex !important;
			justify-content: center;
			align-items: center;
		}
	}

	/* Botão Selecionar dentro dos cards - apenas desktop */
	.btn-select-item {
		display: none; /* Oculto por padrão (mobile) */
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 12px 24px;
		background: #007A63;
		color: #FEFEFE;
		border: none;
		border-radius: 10px;
		font-weight: 700;
		font-size: 14px;
		cursor: pointer;
		transition: all 0.3s ease;
		margin-top: 16px;
		width: 100%;
	}

	.btn-select-item:hover {
		background: #006854;
		transform: translateY(-2px);
		box-shadow: 0 6px 16px rgba(0, 122, 99, 0.4);
	}

	.btn-select-item i {
		width: 18px;
		height: 18px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	/* Desktop: mostra o botão selecionar */
	@media (min-width: 769px) {
		.btn-select-item {
			display: inline-flex !important;
		}
		
		/* Oculta botões de ação nas etapas 1 e 2 no desktop (temos a seta no topo) */
		#step-1-content .step-actions-modern,
		#step-2-content .step-actions-modern {
			display: none !important;
		}
		
		/* Mostra a seta de voltar apenas no desktop na etapa 2 */
		.btn-back-arrow {
			display: flex !important;
		}
	}

	/* Botões de Ação */
	.step-actions-modern {
		display: flex;
		justify-content: flex-end;
		gap: 12px;
		margin-top: 32px;
	}

	.btn-next-modern,
	.btn-prev-modern {
		display: inline-flex !important;
		align-items: center !important;
		gap: 10px !important;
		padding: 16px 32px !important;
		background: #B9E4D4 !important;
		color: #1a1a1a !important;
		border: none !important;
		border-radius: 12px !important;
		font-weight: 700 !important;
		font-size: 16px !important;
		cursor: pointer !important;
		transition: all 0.3s ease !important;
	}

	.btn-prev-modern {
		background: transparent !important;
		color: rgba(254, 254, 254, 0.8) !important;
		border: 2px solid rgba(255, 255, 255, 0.3) !important;
	}

	.btn-next-modern:hover {
		background: #a0d9c7 !important;
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(185, 228, 212, 0.4);
	}

	.btn-prev-modern:hover {
		background: rgba(255, 255, 255, 0.1) !important;
		border-color: rgba(255, 255, 255, 0.5) !important;
		color: #FEFEFE !important;
	}

	.btn-next-modern i,
	.btn-prev-modern i {
		width: 20px;
		height: 20px;
		color: #1a1a1a;
		stroke: #1a1a1a;
	}

	.btn-prev-modern i {
		color: rgba(254, 254, 254, 0.8);
		stroke: rgba(254, 254, 254, 0.8);
	}

	.btn-prev-modern:hover i {
		color: #FEFEFE;
		stroke: #FEFEFE;
	}
	
	/* Botão Próximo nas etapas 3 e 4 - cor escura */
	#step-3-content .btn-next-modern,
	#step-4-content .btn-next-modern {
		background: #2a2a2a !important;
		color: #FEFEFE !important;
	}
	
	#step-3-content .btn-next-modern:hover,
	#step-4-content .btn-next-modern:hover {
		background: #1a1a1a !important;
		box-shadow: 0 8px 20px rgba(42, 42, 42, 0.4);
	}
	
	#step-3-content .btn-next-modern i,
	#step-4-content .btn-next-modern i {
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	/* Sticky Footers - Ocultos SEMPRE no Desktop */
	.sticky-footer-mobile,
	.sticky-footer-step-2,
	.sticky-footer-step-3,
	.sticky-footer-step-4 {
		display: none !important;
	}

	/* Animação Slide Up */
	.slide-up-enter-active,
	.slide-up-leave-active {
		transition: all 0.3s ease-out;
	}

	.slide-up-enter-from {
		transform: translateY(100%);
		opacity: 0;
	}

	.slide-up-leave-to {
		transform: translateY(100%);
		opacity: 0;
	}

	/* Responsive */
	@media (max-width: 992px) {
		.services-grid-booking-modern {
			grid-template-columns: repeat(2, 1fr);
			gap: 20px;
		}

		.steps-progress {
			padding: 0 10px;
		}

		.step-circle {
			width: 56px;
			height: 56px;
		}

		.step-label {
			font-size: 12px;
		}
	}

	@media (max-width: 768px) {
		.booking-hero-minimal {
			padding: 20px 0;
		}

		.hero-title-minimal {
			font-size: 22px;
		}

		.hero-subtitle-minimal {
			font-size: 13px;
			display: none;
		}
		
		.booking-section {
			padding: 16px 0;
		}

		.booking-container {
			padding: 0 16px;
		}

		.booking-form {
			padding: 20px 16px;
			border-radius: 16px;
		}

		/* Mobile: Apenas ajusta grid para 1 coluna */
		.services-grid-booking-modern {
			grid-template-columns: 1fr;
			gap: 16px;
		}

		.service-image-modern {
			height: 200px;
		}
		
		/* Mobile: Duração e label na mesma linha, valor embaixo à direita */
		.service-meta-modern {
			display: flex !important;
			flex-direction: column !important;
			gap: 4px !important;
		}
		
		.service-duration {
			display: inline-flex !important;
			align-self: flex-start !important;
		}
		
		.service-price-modern {
			display: flex !important;
			flex-direction: column !important;
			align-items: flex-end !important;
			gap: 2px !important;
			margin-top: -20px !important;
		}
		
		.price-label-modern {
			font-size: 11px !important;
			text-align: right !important;
		}
		
		.price-value-modern {
			font-size: 22px !important;
			text-align: right !important;
		}

		.step-title-modern {
			font-size: 22px;
		}
		
		.step-subtitle-modern {
			font-size: 14px;
		}

		.steps-progress {
			overflow-x: auto;
			padding-bottom: 8px;
			margin-bottom: 16px;
			padding: 0 8px;
		}

		.step-item {
			min-width: 70px;
		}
		
		.step-circle {
			width: 48px;
			height: 48px;
		}
		
		.step-number {
			font-size: 16px;
		}
		
		.step-icon {
			width: 22px;
			height: 22px;
		}
		
		.step-label {
			font-size: 11px;
		}
		
		.progress-line {
			margin: 0 6px;
		}
		
		.step-header-modern {
			margin-bottom: 20px;
		}
		
		/* Oculta a seta de voltar no mobile */
		.btn-back-arrow {
			display: none !important;
		}
		
		/* Remove o layout flex quando não há seta */
		.step-header-with-back {
			display: block;
		}
		
		.step-header-text {
			text-align: center;
		}

		.category-filter {
			gap: 6px;
			flex-wrap: nowrap;
			justify-content: flex-start;
			padding: 0 16px 8px;
			margin: 0 -16px 20px;
		}

		.category-btn {
			padding: 8px 16px;
			font-size: 12px;
			flex-shrink: 0;
			white-space: nowrap;
		}
		
		.category-btn i {
			width: 16px;
			height: 16px;
		}

		/* Oculta botão "Próximo" padrão no mobile */
		.step-actions-modern {
			display: none;
		}

		/* Sticky Footer Mobile - Todas Etapas (começa oculto, JS controla exibição) */
		.sticky-footer-mobile,
		.sticky-footer-step-2,
		.sticky-footer-step-3,
		.sticky-footer-step-4,
		.sticky-footer-empty-2,
		.sticky-footer-empty-3 {
			display: none !important; /* Começa oculto */
			position: fixed;
			bottom: 0;
			left: 0;
			right: 0;
			background: #1a1a1a;
			padding: 16px 20px;
			box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
			z-index: 1000;
		}
		
		/* Quando ativado via JavaScript */
		.sticky-footer-mobile.active,
		.sticky-footer-step-2.active,
		.sticky-footer-step-3.active,
		.sticky-footer-step-4.active,
		.sticky-footer-empty-2.active,
		.sticky-footer-empty-3.active {
			display: block !important;
		}
		
		/* Etapa 4 sempre visível quando estiver na etapa */
		#step-4-content.active .sticky-footer-step-4 {
			display: block !important;
		}

		.sticky-service-info {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 12px;
		}

		.sticky-service-details {
			display: flex;
			flex-direction: column;
			gap: 4px;
		}

		.sticky-service-name {
			font-size: 15px;
			color: #FEFEFE;
			font-weight: 700;
		}

		.sticky-service-time {
			display: flex;
			align-items: center;
			gap: 6px;
			font-size: 14px;
			color: #B9E4D4;
			font-weight: 600;
		}

		.sticky-service-time i {
			width: 16px;
			height: 16px;
			color: #B9E4D4;
			stroke: #B9E4D4;
		}

		.sticky-service-price {
			font-size: 24px;
			font-weight: 800;
			color: #B9E4D4;
		}
		
		/* Ajuste para horário na Etapa 3 */
		#sticky-time-text {
			font-size: 20px;
			font-weight: 700;
			white-space: nowrap;
		}
		
		/* Container para agrupar botões Voltar + Continuar */
		.sticky-actions-group {
			display: flex;
			gap: 12px;
			margin-top: 12px;
		}

		.btn-sticky-continue {
			flex: 1;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 10px !important;
			padding: 16px 24px !important;
			background: #007A63 !important;
			color: #FEFEFE !important;
			border: none !important;
			border-radius: 12px !important;
			font-weight: 700 !important;
			font-size: 16px !important;
			cursor: pointer !important;
			transition: all 0.3s ease !important;
		}

		.btn-sticky-continue:hover {
			background: #006654 !important;
		}

		.btn-sticky-continue i {
			width: 20px;
			height: 20px;
			color: #FEFEFE;
			stroke: #FEFEFE;
		}
		
		/* Botão sticky continue da etapa 3 - cor escura */
		.sticky-footer-step-3 .btn-sticky-continue {
			background: #2a2a2a !important;
		}
		
		.sticky-footer-step-3 .btn-sticky-continue:hover {
			background: #1a1a1a !important;
		}
		
		.btn-sticky-back {
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 8px !important;
			padding: 16px 20px !important;
			background: transparent !important;
			color: rgba(254, 254, 254, 0.9) !important;
			border: 2px solid rgba(255, 255, 255, 0.3) !important;
			border-radius: 12px !important;
			font-weight: 700 !important;
			font-size: 16px !important;
			cursor: pointer !important;
			transition: all 0.3s ease !important;
			min-width: 100px;
		}

		.btn-sticky-back:hover {
			background: rgba(255, 255, 255, 0.1) !important;
			border-color: rgba(255, 255, 255, 0.5) !important;
		}

		.btn-sticky-back i {
			width: 20px;
			height: 20px;
			color: rgba(254, 254, 254, 0.9);
			stroke: rgba(254, 254, 254, 0.9);
		}
		
		/* Botão Voltar quando não há opções (largura total) */
		.btn-sticky-back-full {
			width: 100%;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 10px !important;
			padding: 16px 32px !important;
			background: transparent !important;
			color: rgba(254, 254, 254, 0.9) !important;
			border: 2px solid rgba(255, 255, 255, 0.3) !important;
			border-radius: 12px !important;
			font-weight: 700 !important;
			font-size: 16px !important;
			cursor: pointer !important;
			transition: all 0.3s ease !important;
		}

		.btn-sticky-back-full:hover {
			background: rgba(255, 255, 255, 0.1) !important;
			border-color: rgba(255, 255, 255, 0.5) !important;
		}

		.btn-sticky-back-full i {
			width: 20px;
			height: 20px;
			color: rgba(254, 254, 254, 0.9);
			stroke: rgba(254, 254, 254, 0.9);
		}

		.btn-sticky-confirm {
			flex: 1;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 10px !important;
			padding: 18px 24px !important;
			background: #2a2a2a !important;
			color: #FEFEFE !important;
			border: none !important;
			border-radius: 12px !important;
			font-weight: 700 !important;
			font-size: 16px !important;
			cursor: pointer !important;
			transition: all 0.3s ease !important;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.btn-sticky-confirm:hover {
			background: #1a1a1a !important;
		}

		.btn-sticky-confirm i {
			width: 20px;
			height: 20px;
			color: #FEFEFE;
			stroke: #FEFEFE;
		}

		/* Padding bottom para não cobrir conteúdo */
		.services-grid-booking-modern,
		.professionals-grid,
		.schedule-section,
		.form-grid-modern {
			padding-bottom: 20px;
		}
	}

	/* Etapa 2 - Profissionais */
	.professionals-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 24px;
		margin-bottom: 40px;
	}

	.professional-card-modern {
		background: #2a2a2a;
		border-radius: 20px;
		padding: 24px;
		cursor: pointer;
		border: 3px solid #2a2a2a;
		transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
		position: relative;
		text-align: center;
	}

	.professional-card-modern:hover {
		transform: translateY(-8px);
		border-color: #B9E4D4;
		box-shadow: 0 12px 28px rgba(185, 228, 212, 0.15);
	}

	.professional-card-modern.selected {
		border-color: #B9E4D4;
		box-shadow: 0 15px 40px rgba(185, 228, 212, 0.25);
	}

	.professional-photo {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		margin: 0 auto 20px;
		overflow: hidden;
		border: 4px solid rgba(185, 228, 212, 0.2);
		transition: all 0.3s ease;
		position: relative;
	}

	.professional-card-modern.selected .professional-photo {
		border-color: #B9E4D4;
		box-shadow: 0 0 0 4px rgba(185, 228, 212, 0.2);
	}

	.professional-photo img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.professional-check {
		position: absolute;
		top: -8px;
		right: -8px;
		width: 32px;
		height: 32px;
		background: #B9E4D4;
		border-radius: 50%;
		display: none;
		align-items: center;
		justify-content: center;
		box-shadow: 0 4px 12px rgba(185, 228, 212, 0.5);
	}

	.professional-check i {
		width: 18px;
		height: 18px;
		color: #1a1a1a;
		stroke: #1a1a1a;
		stroke-width: 3px;
	}

	.professional-card-modern.selected .professional-check {
		display: flex;
		animation: scaleIn 0.3s ease-out;
	}

	.professional-name {
		font-size: 18px;
		font-weight: 700;
		color: #FEFEFE;
		margin-bottom: 8px;
	}

	.professional-role {
		font-size: 13px;
		color: rgba(254, 254, 254, 0.6);
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	/* Loading State */
	.loading-state {
		text-align: center;
		padding: 60px 20px;
		grid-column: 1 / -1;
	}

	.spinner-modern {
		width: 48px;
		height: 48px;
		border: 4px solid rgba(185, 228, 212, 0.2);
		border-top-color: #B9E4D4;
		border-radius: 50%;
		animation: spin 0.8s linear infinite;
		margin: 0 auto 20px;
	}

	@keyframes spin {
		from {
			transform: rotate(0deg);
		}
		to {
			transform: rotate(360deg);
		}
	}

	.loading-state p {
		font-size: 15px;
		color: rgba(254, 254, 254, 0.7);
		margin: 0;
	}

	.empty-icon-prof {
		width: 80px;
		height: 80px;
		margin: 0 auto 24px;
		background: rgba(185, 228, 212, 0.1);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.empty-icon-prof i {
		width: 40px;
		height: 40px;
		color: rgba(254, 254, 254, 0.5);
		stroke: rgba(254, 254, 254, 0.5);
	}

	@media (min-width: 769px) {
		/* Botão selecionar nos cards de profissionais (desktop) */
		.professional-card-modern .btn-select-item {
			margin-top: 16px;
			width: 100%;
			display: inline-flex !important;
			justify-content: center;
			align-items: center;
		}
	}

	@media (max-width: 992px) {
		.professionals-grid {
			grid-template-columns: repeat(2, 1fr);
			gap: 20px;
		}
	}

	@media (max-width: 768px) {
		.professionals-grid {
			grid-template-columns: 1fr;
			gap: 12px;
		}

		.professional-card-modern {
			padding: 16px;
		}

		.professional-photo {
			width: 70px;
			height: 70px;
		}
		
		.professional-name {
			font-size: 16px;
		}
		
		.professional-role {
			font-size: 12px;
		}
	}

	/* Etapa 3 - Data e Horário */
	.month-selector {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
		padding: 0 20px;
	}

	.month-nav-btn {
		width: 44px;
		height: 44px;
		background: rgba(255, 255, 255, 0.1);
		border: 1px solid rgba(255, 255, 255, 0.2);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.month-nav-btn i {
		width: 20px;
		height: 20px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	.month-nav-btn:hover {
		background: rgba(185, 228, 212, 0.2);
		border-color: #B9E4D4;
	}

	.month-display {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 18px;
		font-weight: 700;
		color: #FEFEFE;
	}

	.month-display i {
		width: 22px;
		height: 22px;
		color: #B9E4D4;
		stroke: #B9E4D4;
	}

	/* Calendário Horizontal */
	.calendar-horizontal {
		display: flex;
		gap: 12px;
		overflow-x: auto;
		padding: 0 20px 16px;
		margin: 0 -20px 40px;
		-webkit-overflow-scrolling: touch;
	}

	.calendar-horizontal::-webkit-scrollbar {
		height: 6px;
	}

	.calendar-horizontal::-webkit-scrollbar-track {
		background: rgba(255, 255, 255, 0.1);
		border-radius: 3px;
	}

	.calendar-horizontal::-webkit-scrollbar-thumb {
		background: rgba(185, 228, 212, 0.4);
		border-radius: 3px;
	}

	.day-circle {
		min-width: 72px;
		height: 90px;
		background: rgba(255, 255, 255, 0.05);
		border: 2px solid rgba(255, 255, 255, 0.1);
		border-radius: 16px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 6px;
		cursor: pointer;
		transition: all 0.3s ease;
		flex-shrink: 0;
	}

	.day-circle:hover:not(.disabled) {
		background: rgba(185, 228, 212, 0.15);
		border-color: #B9E4D4;
		transform: translateY(-4px);
	}

	.day-circle.selected {
		background: #007A63;
		border-color: #007A63;
		box-shadow: 0 4px 16px rgba(0, 122, 99, 0.4);
	}

	.day-circle.disabled {
		opacity: 0.3;
		cursor: not-allowed;
	}

	.day-number {
		font-size: 24px;
		font-weight: 800;
		color: #FEFEFE;
	}

	.day-name {
		font-size: 12px;
		color: rgba(254, 254, 254, 0.7);
		text-transform: lowercase;
		font-weight: 500;
	}

	.day-circle.selected .day-name {
		color: #FEFEFE;
	}

	/* Seção de Horários */
	.schedule-section {
		margin-bottom: 32px;
	}

	.times-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 12px;
	}

	.time-slot {
		padding: 16px 20px;
		background: rgba(255, 255, 255, 0.05);
		border: 2px solid rgba(255, 255, 255, 0.1);
		border-radius: 12px;
		text-align: center;
		font-size: 18px;
		font-weight: 600;
		color: #FEFEFE;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.time-slot:hover {
		background: rgba(185, 228, 212, 0.15);
		border-color: #B9E4D4;
		transform: scale(1.05);
	}

	.time-slot.selected {
		background: #B9E4D4;
		border-color: #B9E4D4;
		color: #1a1a1a;
		box-shadow: 0 4px 12px rgba(185, 228, 212, 0.4);
	}

	/* Empty Schedule */
	.empty-schedule {
		text-align: center;
		padding: 60px 40px;
	}

	.empty-schedule-icon {
		width: 100px;
		height: 100px;
		margin: 0 auto 24px;
		background: rgba(185, 228, 212, 0.1);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.empty-schedule-icon i {
		width: 50px;
		height: 50px;
		color: rgba(254, 254, 254, 0.5);
		stroke: rgba(254, 254, 254, 0.5);
	}

	.empty-schedule-title {
		font-size: 18px;
		font-weight: 700;
		color: #FEFEFE;
		margin-bottom: 12px;
		line-height: 1.4;
	}

	.empty-schedule-subtitle {
		font-size: 15px;
		color: rgba(254, 254, 254, 0.6);
		margin-bottom: 24px;
	}

	.btn-next-available {
		padding: 14px 28px;
		background: rgba(255, 255, 255, 0.1);
		border: 1px solid rgba(255, 255, 255, 0.2);
		border-radius: 50px;
		color: #B9E4D4;
		font-size: 14px;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.btn-next-available:hover {
		background: rgba(185, 228, 212, 0.15);
		border-color: #B9E4D4;
	}

	@media (max-width: 768px) {
		.times-grid {
			grid-template-columns: repeat(2, 1fr);
			gap: 8px;
		}

		.time-slot {
			padding: 12px 14px;
			font-size: 15px;
		}
		
		.day-circle {
			min-width: 64px;
			height: 80px;
		}

		.day-number {
			font-size: 20px;
		}
		
		.empty-schedule {
			padding: 40px 20px;
		}
		
		.empty-schedule-title {
			font-size: 16px;
		}
		
		.empty-schedule-subtitle {
			font-size: 14px;
		}
	}

	/* Etapa 4 - Dados Pessoais */
	.form-grid-modern {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 24px;
		margin-bottom: 40px;
	}

	.form-group-step {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.form-group-step.full-width {
		grid-column: 1 / -1;
	}

	.label-step {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 13px;
		font-weight: 600;
		color: rgba(254, 254, 254, 0.9);
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.label-step i {
		width: 18px;
		height: 18px;
		color: #B9E4D4;
		stroke: #B9E4D4;
	}

	.input-modern-step,
	.textarea-modern-step {
		width: 100%;
		padding: 16px 20px;
		background: rgba(255, 255, 255, 0.95);
		border: 2px solid rgba(0, 122, 99, 0.2);
		border-radius: 12px;
		color: #1a1a1a;
		font-size: 15px;
		transition: all 0.3s ease;
		font-family: inherit;
		font-weight: 500;
	}

	.input-modern-step::placeholder,
	.textarea-modern-step::placeholder {
		color: rgba(26, 26, 26, 0.4);
	}

	.input-modern-step:focus,
	.textarea-modern-step:focus {
		outline: none;
		border-color: #007A63;
		background: rgba(255, 255, 255, 1);
		box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
	}
	
	/* Campos readonly (quando cliente está logado) */
	.input-modern-step[readonly],
	.textarea-modern-step[readonly] {
		background: rgba(185, 228, 212, 0.15);
		border-color: rgba(0, 122, 99, 0.3);
		color: #1a1a1a;
		cursor: not-allowed;
	}

	.textarea-modern-step {
		resize: vertical;
		min-height: 80px;
	}

	/* Resumo Card */
	.summary-card-modern {
		background: rgba(0, 0, 0, 0.2);
		border-radius: 20px;
		padding: 32px;
		margin-bottom: 32px;
		border: 1px solid rgba(255, 255, 255, 0.1);
	}

	.summary-title {
		display: flex;
		align-items: center;
		gap: 12px;
		font-size: 20px;
		font-weight: 800;
		color: #FEFEFE;
		margin-bottom: 28px;
		padding-bottom: 20px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
	}

	.summary-title i {
		width: 24px;
		height: 24px;
		color: #B9E4D4;
		stroke: #B9E4D4;
	}

	.summary-items {
		display: flex;
		flex-direction: column;
		gap: 20px;
	}

	.summary-item {
		display: flex;
		align-items: center;
		gap: 16px;
	}

	.summary-icon {
		width: 48px;
		height: 48px;
		background: rgba(185, 228, 212, 0.15);
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.summary-icon i {
		width: 24px;
		height: 24px;
		color: #B9E4D4;
		stroke: #B9E4D4;
	}

	.summary-info {
		display: flex;
		flex-direction: column;
		gap: 4px;
		flex: 1;
	}

	.summary-label {
		font-size: 12px;
		color: rgba(254, 254, 254, 0.6);
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.summary-value {
		font-size: 16px;
		color: #FEFEFE;
		font-weight: 600;
	}
	
	.summary-value-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		gap: 12px;
		width: 100%;
	}
	
	.summary-price {
		font-size: 18px;
		color: #B9E4D4;
		font-weight: 700;
		white-space: nowrap;
		flex-shrink: 0;
	}

	/* Botão Confirmar */
	.btn-confirm-modern {
		display: inline-flex !important;
		align-items: center !important;
		gap: 10px !important;
		padding: 18px 36px !important;
		background: #2a2a2a !important;
		color: #FEFEFE !important;
		border: none !important;
		border-radius: 12px !important;
		font-weight: 700 !important;
		font-size: 17px !important;
		cursor: pointer !important;
		transition: all 0.3s ease !important;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.btn-confirm-modern:hover {
		background: #1a1a1a !important;
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(42, 42, 42, 0.4);
	}

	.btn-confirm-modern i {
		width: 22px;
		height: 22px;
		color: #FEFEFE;
		stroke: #FEFEFE;
	}

	.mensagem-step {
		padding: 16px 24px;
		border-radius: 12px;
		margin-bottom: 24px;
		font-size: 15px;
		font-weight: 600;
		text-align: center;
		display: none;
	}

	.mensagem-step.text-success {
		display: block;
		background: rgba(185, 228, 212, 0.15);
		color: #B9E4D4;
		border: 1px solid rgba(185, 228, 212, 0.3);
	}

	.mensagem-step.text-danger {
		display: block;
		background: rgba(220, 53, 69, 0.15);
		color: #ff6b6b;
		border: 1px solid rgba(220, 53, 69, 0.3);
	}
	
	/* Overlay do Modal */
	.modal-overlay-custom {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.6);
		z-index: 9998;
		display: flex;
		align-items: center;
		justify-content: center;
		animation: fadeInOverlay 0.3s ease-out;
	}
	
	@keyframes fadeInOverlay {
		from {
			opacity: 0;
		}
		to {
			opacity: 1;
		}
	}
	
	/* Modal Agendamento Sucesso (Fallback sem SweetAlert) */
	.modal-agendamento-success {
		background: #FEFEFE;
		border-radius: 20px;
		padding: 40px 30px;
		text-align: center;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
		max-width: 500px;
		width: 90%;
		animation: modalScaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
		position: relative;
		z-index: 9999;
	}
	
	@keyframes modalScaleIn {
		from {
			opacity: 0;
			transform: scale(0.7);
		}
		to {
			opacity: 1;
			transform: scale(1);
		}
	}
	
	.modal-agendamento-icon {
		width: 80px;
		height: 80px;
		background: linear-gradient(135deg, #00c853, #00e676);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 0 auto 20px;
		font-size: 48px;
		color: #FEFEFE;
		font-weight: bold;
		box-shadow: 0 4px 20px rgba(0, 200, 83, 0.3);
	}
	
	.modal-agendamento-title {
		font-size: 24px;
		font-weight: 800;
		color: #1a1a1a;
		margin-bottom: 15px;
		letter-spacing: -0.5px;
	}
	
	.modal-agendamento-text {
		font-size: 16px;
		color: #666;
		margin-bottom: 10px;
	}
	
	.modal-agendamento-info {
		font-size: 14px;
		color: #999;
		margin-top: 10px;
	}
	
	/* Customização SweetAlert - Modal Centralizado */
	.swal-center-popup {
		border-radius: 20px !important;
		padding: 35px 30px !important;
		max-width: 500px !important;
		animation: modalScaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
	}
	
	.swal-agendamento-title {
		font-size: 26px !important;
		font-weight: 800 !important;
		color: #1a1a1a !important;
		letter-spacing: -0.5px !important;
		margin-bottom: 15px !important;
	}
	
	.swal-agendamento-content {
		font-size: 15px !important;
		line-height: 1.6 !important;
		color: #555 !important;
	}
	
	.swal-agendamento-btn {
		border-radius: 12px !important;
		padding: 14px 32px !important;
		font-weight: 700 !important;
		font-size: 16px !important;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		transition: all 0.3s ease !important;
	}
	
	.swal-agendamento-btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 20px rgba(0, 122, 99, 0.4) !important;
	}

	@media (max-width: 768px) {
		.form-grid-modern {
			grid-template-columns: 1fr;
			gap: 16px;
		}

		.summary-card-modern {
			padding: 20px;
		}
		
		.summary-title {
			font-size: 18px;
		}
		
		.summary-icon {
			width: 40px;
			height: 40px;
		}
		
		.summary-icon i {
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
			font-size: 16px;
		}
		
		.input-modern-step,
		.textarea-modern-step {
			font-size: 14px;
			padding: 14px 16px;
		}
		
		.label-step {
			font-size: 12px;
		}

		.btn-confirm-modern {
			width: 100%;
			justify-content: center !important;
		}
		
		/* Modal Agendamento - Mobile */
		.modal-agendamento-success {
			padding: 30px 20px;
			max-width: 90%;
			width: 90%;
		}
		
		.modal-agendamento-icon {
			width: 70px;
			height: 70px;
			font-size: 40px;
		}
		
		.modal-agendamento-title {
			font-size: 20px;
		}
		
		.modal-agendamento-text {
			font-size: 15px;
		}
		
		.modal-agendamento-info {
			font-size: 13px;
		}
		
		.swal-center-popup {
			max-width: 90% !important;
			width: 90% !important;
			padding: 30px 20px !important;
		}
		
		.swal-agendamento-title {
			font-size: 22px !important;
		}
		
		.swal-agendamento-content {
			font-size: 14px !important;
		}
		
		.swal-agendamento-btn {
			padding: 12px 24px !important;
			font-size: 14px !important;
		}
	}
	
	/* Ajustes específicos para telas menores (iPhone) */
	@media (max-width: 428px) {
		.booking-hero-minimal {
			padding: 16px 0;
		}
		
		.hero-title-minimal {
			font-size: 20px;
		}
		
		.booking-form {
			padding: 16px 12px;
		}
		
		.step-title-modern {
			font-size: 20px;
		}
		
		.step-subtitle-modern {
			font-size: 13px;
		}
		
		.step-circle {
			width: 44px;
			height: 44px;
		}
		
		.step-icon {
			width: 20px;
			height: 20px;
		}
		
		.step-label {
			font-size: 10px;
		}
		
		.service-image-modern {
			height: 180px;
		}
		
		.service-card-modern {
			padding: 14px;
		}
		
		.service-name-modern {
			font-size: 16px;
			margin-bottom: 12px;
		}
		
		.price-value-modern {
			font-size: 18px;
		}
		
		.service-duration {
			padding: 5px 10px;
			font-size: 12px;
		}
		
		.category-btn {
			padding: 6px 12px;
			font-size: 11px;
		}
		
		.month-display {
			font-size: 16px;
		}
		
		.month-display i {
			width: 20px;
			height: 20px;
		}
		
		.month-nav-btn {
			width: 40px;
			height: 40px;
		}
		
		.month-nav-btn i {
			width: 18px;
			height: 18px;
		}
		
		.day-circle {
			min-width: 58px;
			height: 72px;
		}
		
		.day-number {
			font-size: 18px;
		}
		
		.day-name {
			font-size: 11px;
		}
		
		.calendar-horizontal {
			gap: 8px;
			padding: 0 12px 12px;
			margin: 0 -12px 24px;
		}
		
		.month-selector {
			padding: 0 12px;
			margin-bottom: 20px;
		}
	}
</style>

<script>
	// Vue App para Filtro de Serviços
	(function() {
		const bookingServicesEl = document.getElementById('booking-services-app');
		if (!bookingServicesEl) return;

		const servicesData = JSON.parse(bookingServicesEl.dataset.services || '[]');
		const categoriesData = JSON.parse(bookingServicesEl.dataset.categories || '[]');

		const { createApp } = Vue;

		const BookingServicesApp = {
			data() {
				return {
					services: servicesData,
					categories: categoriesData,
					selectedCategory: 'all',
					selectedService: null
				}
			},
			computed: {
				filteredServices() {
					if (this.selectedCategory === 'all') {
						return this.services;
					}
					return this.services.filter(s => s.categoria == this.selectedCategory);
				}
			},
			methods: {
				formatPrice(value) {
					return parseFloat(value).toFixed(2).replace('.', ',');
				},
				selectService(service) {
					this.selectedService = service;
					document.getElementById('servico').value = service.id;
					
					// Atualiza campos ocultos e resumo
					const valorFormatado = this.formatPrice(service.valor);
					document.getElementById('nome_serv').value = service.nome + ' - R$ ' + valorFormatado;
					
					// Atualiza resumo final
					const finalServicoEl = document.getElementById('final-servico');
					const finalValorEl = document.getElementById('final-servico-valor');
					
					if (finalServicoEl) finalServicoEl.textContent = service.nome;
					if (finalValorEl) finalValorEl.textContent = 'R$ ' + valorFormatado;
					
					// Atualiza função mudarServico se existir
					if (typeof mudarServico === 'function') {
						mudarServico();
					}
				},
				selectServiceAndNext(service) {
					// Seleciona o serviço
					this.selectService(service);
					
					// Avança para a próxima etapa
					setTimeout(() => {
						this.goToNextStep();
					}, 100);
				},
				goToNextStep() {
					// Simula click no botão next-step da etapa 1
					const nextBtn = document.querySelector('.next-step[data-step="1"]');
					if (nextBtn) {
						nextBtn.click();
					}
				},
				getCategoryIcon(categoryName) {
					const icons = {
						'Corte': 'scissors',
						'Barba': 'wind',
						'Cabelo': 'sparkles',
						'Sobrancelha': 'scan-line',
						'Manicure': 'hand',
						'Pedicure': 'footprints',
						'Luzes': 'lightbulb',
						'Tratamento': 'droplet',
						'Coloração': 'palette',
						'Escova': 'flame',
						'Penteado': 'star'
					};
					
					// Verifica se contém alguma palavra-chave
					for (const [key, icon] of Object.entries(icons)) {
						if (categoryName.toLowerCase().includes(key.toLowerCase())) {
							return icon;
						}
					}
					
					return 'tag';
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
			},
			watch: {
				selectedService(newVal) {
					// Atualiza ícones quando serviço é selecionado
					this.$nextTick(() => {
						if (typeof lucide !== 'undefined') {
							setTimeout(() => {
								lucide.createIcons();
							}, 100);
						}
					});
				}
			}
		};

		createApp(BookingServicesApp).mount('#booking-services-app');
	})();

	// Vue App para Calendário e Horários (Etapa 3)
	(function() {
		const datetimeEl = document.getElementById('booking-datetime-app');
		if (!datetimeEl) return;

		const { createApp } = Vue;

		const BookingDateTimeApp = {
			data() {
				return {
					currentMonth: new Date().getMonth(),
					currentYear: new Date().getFullYear(),
					selectedDate: null,
					selectedTime: null,
					availableTimes: [],
					loadingTimes: false
				}
			},
			computed: {
				currentMonthName() {
					const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
									'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
					return months[this.currentMonth];
				},
				daysInMonth() {
					const year = this.currentYear;
					const month = this.currentMonth;
					const firstDay = new Date(year, month, 1);
					const lastDay = new Date(year, month + 1, 0);
					const days = [];
					const today = new Date();
					today.setHours(0, 0, 0, 0);

					for (let day = 1; day <= lastDay.getDate(); day++) {
						const date = new Date(year, month, day);
						const weekDays = ['dom.', 'seg.', 'ter.', 'qua.', 'qui.', 'sex.', 'sáb.'];
						
						days.push({
							day: day,
							date: date,
							dateStr: date.toISOString().split('T')[0],
							weekDay: weekDays[date.getDay()],
							isPast: date < today
						});
					}

					return days;
				}
			},
			methods: {
				changeMonth(direction) {
					this.currentMonth += direction;
					if (this.currentMonth > 11) {
						this.currentMonth = 0;
						this.currentYear++;
					} else if (this.currentMonth < 0) {
						this.currentMonth = 11;
						this.currentYear--;
					}
				},
				selectDate(day) {
					if (day.isPast) return;
					
					this.selectedDate = day.dateStr;
					this.selectedTime = null;
					
					// Atualiza o input oculto
					document.getElementById('data').value = day.dateStr;
					
					// Carrega horários via função existente
					if (typeof mudarFuncionario === 'function') {
						this.loadingTimes = true;
						mudarFuncionario();
						
						// Aguarda um pouco para pegar os horários gerados
						setTimeout(() => {
							this.loadAvailableTimes();
						}, 500);
					}
				},
				selectTime(time) {
					this.selectedTime = time;
					// Remove segundos se existirem (ex: 07:45:00 -> 07:45)
					const timeFormatted = time.substring(0, 5);
					document.getElementById('hora_rec').value = timeFormatted;
					
					// Atualiza resumo se função existir
					if (typeof atualizarResumo === 'function') {
						atualizarResumo();
					}
					
					// Mostra o sticky footer no mobile (Etapa 3) - só se data E horário estiverem selecionados
					const dataElement = document.getElementById('data');
					const horaElement = document.getElementById('hora_rec');
					
					if (dataElement && horaElement && dataElement.value && horaElement.value) {
						// Formata a data para exibição
						const partes = dataElement.value.split('-');
						const dataObj = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
						const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
						const meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
						const diaSemana = diasSemana[dataObj.getDay()];
						const dia = String(dataObj.getDate()).padStart(2, '0');
						const mes = meses[dataObj.getMonth()];
						
						const dataFormatada = diaSemana + '., ' + dia + ' de ' + mes + '.';
						
						// Atualiza o sticky footer com data e horário
						document.getElementById('sticky-datetime-info').textContent = 'Agendamento selecionado';
						document.getElementById('sticky-date-text').textContent = dataFormatada;
						document.getElementById('sticky-time-text').textContent = timeFormatted;
						
						const stickyFooter = document.querySelector('.sticky-footer-step-3');
						if (stickyFooter) {
							stickyFooter.classList.add('active');
							
							// Atualiza ícones Lucide
							if (typeof lucide !== 'undefined') {
								setTimeout(() => {
									lucide.createIcons();
								}, 100);
							}
						}
					}
				},
				loadAvailableTimes() {
					// Captura os horários do container existente
					const horariosContainer = document.getElementById('listar-horarios');
					if (!horariosContainer) {
						this.availableTimes = [];
						this.loadingTimes = false;
						return;
					}

					const horarioInputs = horariosContainer.querySelectorAll('input[name="hora"]');
					const times = [];
					
					// Verifica se a data selecionada é hoje
					const hoje = new Date();
					hoje.setHours(0, 0, 0, 0);
					const dataSelecionada = new Date(this.selectedDate + 'T00:00:00');
					const isToday = dataSelecionada.getTime() === hoje.getTime();
					
					// Hora atual em minutos
					const agora = new Date();
					const horaAtualMinutos = agora.getHours() * 60 + agora.getMinutes();
					
					horarioInputs.forEach(input => {
						// VERIFICAR SE O HORÁRIO ESTÁ VISÍVEL (não tem display: none)
						const parentDiv = input.closest('.col-3');
						if (!parentDiv) return;
						
						const displayStyle = window.getComputedStyle(parentDiv).display;
						if (displayStyle === 'none') return; // Pula horários ocultos
						
						// Remove segundos se existirem (ex: 07:45:00 -> 07:45)
						let timeValue = input.value;
						if (timeValue.length > 5) {
							timeValue = timeValue.substring(0, 5);
						}
						
						// Se for hoje, filtra apenas horários futuros
						if (isToday) {
							const [horas, minutos] = timeValue.split(':').map(Number);
							const horarioEmMinutos = horas * 60 + minutos;
							
							// Só adiciona se o horário ainda não passou (com margem de segurança de 15 minutos)
							if (horarioEmMinutos > (horaAtualMinutos + 15)) {
								times.push(timeValue);
							}
						} else {
							// Se não for hoje, adiciona apenas horários visíveis
							times.push(timeValue);
						}
					});

					this.availableTimes = times;
					this.loadingTimes = false;
					
					// Controla sticky footer baseado na disponibilidade
					if (times.length === 0 && this.selectedDate) {
						// Mostra botão voltar quando não há horários
						const stickyEmpty = document.querySelector('.sticky-footer-empty-3');
						const stickyNormal = document.querySelector('.sticky-footer-step-3');
						if (stickyEmpty) stickyEmpty.classList.add('active');
						if (stickyNormal) stickyNormal.classList.remove('active');
						
						// Atualiza ícones Lucide
						setTimeout(() => {
							if (typeof lucide !== 'undefined') {
								lucide.createIcons();
							}
						}, 100);
					} else {
						// Oculta botão voltar quando há horários
						const stickyEmpty = document.querySelector('.sticky-footer-empty-3');
						if (stickyEmpty) stickyEmpty.classList.remove('active');
					}
				},
				goToNextAvailable() {
					// Avança para o próximo dia não passado
					const nextDay = this.daysInMonth.find(d => !d.isPast && d.dateStr !== this.selectedDate);
					if (nextDay) {
						this.selectDate(nextDay);
					}
				}
			},
			mounted() {
				// Seleciona data atual por padrão
				const today = this.daysInMonth.find(d => !d.isPast);
				if (today) {
					this.selectedDate = today.dateStr;
					document.getElementById('data').value = today.dateStr;
				}

				this.$nextTick(() => {
					if (typeof lucide !== 'undefined') {
						lucide.createIcons();
					}
				});

				// Observer para detectar mudanças no container de horários
				const horariosContainer = document.getElementById('listar-horarios');
				if (horariosContainer) {
					const observer = new MutationObserver(() => {
						this.loadAvailableTimes();
					});
					observer.observe(horariosContainer, { childList: true, subtree: true });
				}
			},
			updated() {
				this.$nextTick(() => {
					if (typeof lucide !== 'undefined') {
						lucide.createIcons();
					}
				});
			}
		};

		createApp(BookingDateTimeApp).mount('#booking-datetime-app');
	})();

	// Inicializa ícones Lucide
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
	
	// Atualiza ícones quando o input de data é exibido
	setTimeout(() => {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	}, 100);

	// Observer para atualizar ícones quando footers sticky aparecem
	const observeSticky = new MutationObserver(() => {
		if (typeof lucide !== 'undefined') {
			setTimeout(() => {
				lucide.createIcons();
			}, 100);
		}
	});

	// Observa mudanças nos footers sticky
	document.querySelectorAll('.sticky-footer-step-2, .sticky-footer-step-3, .sticky-footer-step-4').forEach(el => {
		if (el) {
			observeSticky.observe(el, { attributes: true, childList: true, subtree: true });
		}
	});
</script>

      <div class="text-center mt-4">
        <a href="meus-agendamentos.php" class="botao-azul" id='botao_editar' style="display: none;">
          <i class="fas fa-list-alt mr-2"></i>
          Ver Meus Agendamentos
        </a>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Modal de Login do Cliente -->
<div class="modal fade" id="modalLoginCliente" tabindex="-1" role="dialog" aria-labelledby="modalLoginLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
			<div class="modal-header" style="background: linear-gradient(135deg, #007A63 0%, #006854 100%); border: none; padding: 24px 30px;">
				<div style="flex: 1;">
					<h5 class="modal-title" id="modalLoginLabel" style="color: #fff; font-weight: 700; font-size: 22px; margin: 0; display: flex; align-items: center; gap: 10px;">
						<i class="fa fa-user-circle" style="font-size: 24px;"></i>
						<span>Acesso do Cliente</span>
					</h5>
					<p style="color: rgba(255, 255, 255, 0.85); font-size: 13px; margin: 8px 0 0 0;">
						Faça login para continuar com seu agendamento
					</p>
				</div>
			</div>
			
			<form id="form-login-cliente">
				<div class="modal-body" style="padding: 30px;">
					<!-- Alerta de Primeiro Acesso -->
					<div class="alert" style="background: rgba(0, 122, 99, 0.08); border: 1px solid rgba(0, 122, 99, 0.2); border-radius: 12px; padding: 16px; margin-bottom: 24px;">
						<div style="display: flex; align-items: flex-start; gap: 12px;">
							<i class="fa fa-info-circle" style="color: #007A63; font-size: 20px; margin-top: 2px;"></i>
							<div>
								<strong style="color: #007A63; font-size: 14px; display: block; margin-bottom: 4px;">
									Primeiro acesso?
								</strong>
								<span style="color: #495057; font-size: 13px; line-height: 1.5;">
									Digite seu telefone e nome, a senha padrão é <strong>123</strong>. Você poderá alterá-la depois.
								</span>
							</div>
						</div>
					</div>
					
					<div class="form-group" style="margin-bottom: 20px;">
						<label style="font-weight: 600; color: #495057; font-size: 13px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
							<i class="fa fa-phone" style="color: #007A63;"></i>
							<span>Telefone</span>
						</label>
						<input type="text" class="form-control" id="telefone_login" name="telefone_login" placeholder="(00) 00000-0000" required 
							   style="border: 2px solid #e9ecef; border-radius: 10px; padding: 12px 16px; font-size: 15px; transition: all 0.3s ease;">
					</div>
					
					<div class="form-group" style="margin-bottom: 20px;" id="campo_nome_cadastro" style="display: none;">
						<label style="font-weight: 600; color: #495057; font-size: 13px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
							<i class="fa fa-user" style="color: #007A63;"></i>
							<span>Nome Completo</span>
						</label>
						<input type="text" class="form-control" id="nome_login" name="nome_login" placeholder="Digite seu nome completo"
							   style="border: 2px solid #e9ecef; border-radius: 10px; padding: 12px 16px; font-size: 15px; transition: all 0.3s ease;">
					</div>
					
					<div class="form-group" style="margin-bottom: 24px;">
						<label style="font-weight: 600; color: #495057; font-size: 13px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
							<i class="fa fa-lock" style="color: #007A63;"></i>
							<span>Senha</span>
						</label>
						<div style="position: relative;">
							<input type="password" class="form-control" id="senha_login" name="senha_login" placeholder="Digite sua senha" required 
								   style="border: 2px solid #e9ecef; border-radius: 10px; padding: 12px 16px; font-size: 15px; transition: all 0.3s ease; padding-right: 45px;">
							<i class="fa fa-eye" id="toggle_senha" 
							   style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #6c757d; cursor: pointer; font-size: 16px;"
							   onclick="toggleSenha()"></i>
						</div>
					</div>
					
					<div id="mensagem-login" style="margin-bottom: 16px; text-align: center;"></div>
				</div>
				
				<div class="modal-footer" style="border: none; padding: 0 30px 30px; gap: 12px;">
					<button type="button" class="btn btn-secondary" onclick="voltarParaEtapa2()" 
							style="border-radius: 10px; padding: 12px 24px; font-weight: 600; font-size: 14px; flex: 1; border: 2px solid #e9ecef; background: #fff; color: #6c757d;">
						<i class="fa fa-arrow-left mr-2"></i>
						Voltar
					</button>
					<button type="submit" class="btn btn-primary" id="btn_login" 
							style="border-radius: 10px; padding: 12px 24px; font-weight: 600; font-size: 14px; flex: 1; background: #007A63; border: none; box-shadow: 0 4px 12px rgba(0, 122, 99, 0.3);">
						<i class="fa fa-sign-in mr-2"></i>
						<span id="texto_btn_login">Entrar</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">
        <i class="fas fa-trash-alt text-danger mr-2"></i>
        Excluir Agendamento
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px" id="btn-fechar-excluir">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    
    <form id="form-excluir">
      <div class="modal-body">
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          <span id="msg-excluir"></span>
        </div>
        
        <input type="hidden" name="id" id="id_excluir">
        
        <small><div id="mensagem-excluir" align="center"></div></small>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-2"></i>
          Cancelar
        </button>
        <button type="submit" class="btn btn-danger">
          <i class="fas fa-trash-alt mr-2"></i>
          Confirmar Exclusão
        </button>
      </div>
    </form>
  </div>
</div>
</div>


<button id="botao-instalar" style="display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); padding: 12px 20px; background: #007bff; color: #fff; border: none; border-radius: 8px; z-index: 9999;">Instalar App</button>





  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap"></script>
  <!-- End Google Map -->

    <!-- Mascaras JS -->
<script type="text/javascript" src="sistema/painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 






<script type="text/javascript">
$(document).ready(function() {

	var nome_cl = localStorage.nome_cli_del;
      var tel_cl = localStorage.telefone_cli_del;
      $('#telefone').val(tel_cl);
      $('#nome').val(nome_cl);

      
  // Inicializa o botão de editar como oculto
  $("#botao_editar").hide();
  
  // Carrega os serviços em cards
  carregarServicosCards();
  
  // Máscara para telefone
  if($.fn.mask) {
    $('#telefone').mask('(00) 00000-0000');
  }
  
  // Efeito de destaque nos campos ao focar
  $('.inputs_agenda').focus(function() {
    $(this).css('border-bottom', '2px solid #2ecc71 !important');
  }).blur(function() {
    $(this).css('border-bottom', '2px solid #3498db !important');
  });
  


  // Navegação entre etapas
  $('.next-step').click(function() {
    var currentStep = parseInt($(this).data('step'));
    var nextStep = currentStep + 1;
    
    // Validações específicas para cada etapa
    if (currentStep === 1) {
      if ($('#servico').val() === '') {
        if(typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Por favor, selecione um serviço para continuar.',
            confirmButtonColor: '#3498db'
          });
        } else {
          alert('Por favor, selecione um serviço para continuar.');
        }
        return;
      }
      
      // Carrega os profissionais quando avança para a etapa 2
      carregarProfissionaisCards();
      
      // Oculta sticky footers da etapa 2
      $('.sticky-footer-step-2').removeClass('active');
      $('.sticky-footer-empty-2').removeClass('active');
    }
    
    if (currentStep === 2) {
      if ($('#funcionario').val() === '') {
        if(typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Por favor, selecione um profissional para continuar.',
            confirmButtonColor: '#3498db'
          });
        } else {
          alert('Por favor, selecione um profissional para continuar.');
        }
        return;
      }
      
      // MODIFICADO: Abre o modal de login ao invés de avançar direto
      abrirModalLogin();
      return; // Não avança para a etapa 3 ainda
    }
    
    if (currentStep === 3) {
      // Verifica se há um horário selecionado de forma mais robusta
      var horaSelecionada = $('#hora_rec').val();
      
      if (!horaSelecionada || horaSelecionada === '' || horaSelecionada === null) {
        if(typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Por favor, selecione um horário para continuar.',
            confirmButtonColor: '#3498db'
          });
        } else {
          alert('Por favor, selecione um horário para continuar.');
        }
        return;
      }
      
      // Atualiza o resumo final
      atualizarResumoFinal();
    }
    
    // Avança para a próxima etapa
    $('.form-step, .form-step-modern').removeClass('active');
    $('#step-' + nextStep + '-content').addClass('active');
    
    // Atualiza os indicadores de etapa (ambos step e step-item)
    $('.step, .step-item').removeClass('active');
    $('#step-' + nextStep + ', #step-indicator-' + nextStep).addClass('active');
    
    // Marca etapas anteriores como concluídas
    for (var i = 1; i < nextStep; i++) {
      $('#step-' + i + ', #step-indicator-' + i).addClass('completed');
    }

    
     // Agora sim: scrolla para o topo após ativar o conteúdo
  setTimeout(function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }, 100); // pequeno delay para garantir que o conteúdo novo esteja visível

  });
  
  $('.prev-step').click(function() {
    var currentStep = parseInt($(this).data('step'));
    var prevStep = currentStep - 1;
    
    // Volta para a etapa anterior
    $('.form-step, .form-step-modern').removeClass('active');
    $('#step-' + prevStep + '-content').addClass('active');
    
    // Atualiza os indicadores de etapa (ambos step e step-item)
    $('.step, .step-item').removeClass('active');
    $('#step-' + prevStep + ', #step-indicator-' + prevStep).addClass('active');
    
    // Mantém as etapas anteriores como concluídas
    for (var i = 1; i < prevStep; i++) {
      $('#step-' + i + ', #step-indicator-' + i).addClass('completed');
    }
    
    // Scroll para o topo
    setTimeout(function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }, 100);
  });
  
  // Verifica se a data selecionada é válida (não anterior à atual)
  $('#data').change(function() {
    var selectedDate = new Date($(this).val());
    var today = new Date();
    today.setHours(0,0,0,0);
    
    if (selectedDate < today) {
      if(typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'error',
          title: 'Data Inválida',
          text: 'Por favor, selecione uma data igual ou posterior a hoje.',
          confirmButtonColor: '#3498db'
        });
      } else {
        alert('Selecione uma data igual ou maior que hoje!');
      }
      
      var dt = new Date();
      var dia = String(dt.getDate()).padStart(2, '0');
      var mes = String(dt.getMonth() + 1).padStart(2, '0');
      var ano = dt.getFullYear();
      dataAtual = ano + '-' + mes + '-' + dia;
      $('#data').val(dataAtual);
    }
    
    // Atualiza os horários quando a data muda
    mudarFuncionario();
    
    // Atualiza o resumo
    atualizarResumo();
  });
});

// Função para carregar os serviços em cards
function carregarServicosCards() {
  var html = '';
  
  // Extrai os serviços do select original
  $('#servico option').each(function() {
    if ($(this).val() !== '') {
      var id = $(this).val();
      var texto = $(this).text();
      var partes = texto.split(' - R$ ');
      var nome = partes[0];
      var valor = partes.length > 1 ? partes[1] : '0,00';

      var foto = $(this).data('foto');
      var tempo = $(this).data('tempo');
            
      html += '<div class="service-card" data-id="' + id + '" onclick="selecionarServico(' + id + ', \'' + nome + '\', \'' + valor + '\')">';
      html += '<div class="card-img">';
      html += '<img src="sistema/painel/img/servicos/' + foto + '" alt="' + nome + '" onerror="this.src=\'sistema/painel/img/servicos/sem-foto.jpg\'">';
      html += '</div>';
      html += '<div class="card-body">';
      html += '<h5 class="card-title">' + nome + '</h5>';
      html += '<p class="card-text">'+tempo+' Minutos</p>';
      html += '<div class="card-price">R$ ' + valor + '</div>';
      html += '</div>';
      html += '</div>';
    }
  });
  
  if (html === '') {
    html = '<div class="text-center w-100 p-5">';
    html += '<i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>';
    html += '<p class="text-white">Nenhum serviço disponível no momento.</p>';
    html += '</div>';
  }
  
  $('#service-cards-container').html(html);
}

// Função para selecionar um serviço
function selecionarServico(id, nome, valor) {  

  // Remove a seleção de todos os cards
  $('.service-card').removeClass('selected');
  
  // Adiciona a classe selected ao card clicado
  $('.service-card[data-id="' + id + '"]').addClass('selected');
  
  // Atualiza o campo oculto
  $('#servico').val(id);
  $('#nome_serv').val(nome + ' - R$ ' + valor);
  
  // Atualiza o resumo
  $('#resumo-servico').text(nome + ' - R$ ' + valor);
  
  // Atualiza o resumo final separando nome e valor
  $('#final-servico').text(nome);
  $('#final-servico-valor').text('R$ ' + valor);
  
  // Chama a função original para mudar o serviço
  mudarServico();
}

// Função para carregar os profissionais em cards - CORRIGIDA
function carregarProfissionaisCards() {
  // Mostra um indicador de carregamento
  $("#professional-cards-container").html('<div class="text-center w-100 p-5"><div class="spinner-border text-info" role="status"></div><p class="mt-3 text-white-50">Carregando profissionais...</p></div>');
  
  var serv = $("#servico").val();
  
  $.ajax({
    url: "ajax/listar-funcionarios.php",
    method: 'POST',
    data: {serv},
    dataType: "text",
    
    success: function(result) {
      // Cria um elemento temporário para extrair os dados
      var tempDiv = document.createElement('div');
      tempDiv.innerHTML = result;
      var options = tempDiv.querySelectorAll('option');
      
      // Agora cria os cards com base nos dados extraídos
      var html = '';
      
      // Extrai os profissionais das opções
      options.forEach(function(option) {
        if (option.value !== '') {
          var id = option.value;
          var nome = option.textContent;
          var foto = option.dataset.foto;
          
          html += '<div class="professional-card-modern" data-id="' + id + '" onclick="selecionarProfissional(' + id + ', \'' + nome + '\')">';
          html += '<div class="professional-photo">';
          html += '<img src="sistema/painel/img/perfil/' + foto + '" alt="' + nome + '" onerror="this.src=\'sistema/painel/img/perfil/sem-foto.jpg\'">';
          html += '<div class="professional-check">';
          html += '<i data-lucide="check-circle"></i>';
          html += '</div>';
          html += '</div>';
          html += '<h4 class="professional-name">' + nome + '</h4>';
          html += '<p class="professional-role">Profissional</p>';
          html += '<button type="button" class="btn-select-item" onclick="event.stopPropagation(); selecionarProfissionalEAvancar(' + id + ', \'' + nome + '\')">';
          html += '<span>Selecionar</span>';
          html += '<i data-lucide="arrow-right"></i>';
          html += '</button>';
          html += '</div>';
        }
      });
      
      if (html === '') {
        html = '<div class="loading-state">';
        html += '<div class="empty-icon-prof">';
        html += '<i data-lucide="user-x"></i>';
        html += '</div>';
        html += '<p style="color: rgba(254, 254, 254, 0.7); font-size: 16px;">Nenhum profissional disponível para este serviço.</p>';
        html += '</div>';
        
        // Mostra sticky footer de voltar no mobile quando não há profissionais
        $('.sticky-footer-step-2').removeClass('active');
        $('.sticky-footer-empty-2').addClass('active');
        
        // Atualiza ícones Lucide no sticky footer
        setTimeout(() => {
          if (typeof lucide !== 'undefined') {
            lucide.createIcons();
          }
        }, 100);
      } else {
        // Oculta sticky footer de voltar se houver profissionais
        $('.sticky-footer-empty-2').removeClass('active');
      }
      
      $('#professional-cards-container').html(html);
      
      // Atualiza ícones Lucide
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    },
    error: function() {
      $('#professional-cards-container').html('<div class="alert alert-danger">Erro ao carregar profissionais. Tente novamente.</div>');
    }
  });


}

// Função para selecionar um profissional - MODIFICADA PARA ABRIR MODAL DE LOGIN
function selecionarProfissional(id, nome) {
  // Remove a seleção de todos os cards
  $('.professional-card-modern').removeClass('selected');
  
  // Adiciona a classe selected ao card clicado
  $('.professional-card-modern[data-id="' + id + '"]').addClass('selected');
  
  // Atualiza o campo oculto
  $('#funcionario').val(id);
  $('#nome_func').val(nome);
  
  // Atualiza o resumo
  $('#resumo-profissional').text(nome);
  $('#final-profissional').text(nome);
  
  // Atualiza o sticky footer com o nome do profissional
  $('#sticky-prof-name').text(nome);
  
  // Mostra o sticky footer no mobile (Etapa 2)
  $('.sticky-footer-step-2').addClass('active');
  
  // Atualiza ícones Lucide
  if (typeof lucide !== 'undefined') {
    setTimeout(() => {
      lucide.createIcons();
    }, 100);
  }
  
  // Chama a função original para mudar funcionário
  listarFuncionario();
  
  // NOVO: Abre o modal de login ao invés de avançar direto
  abrirModalLogin();
}

// Função para selecionar um profissional e avançar automaticamente - MODIFICADA
function selecionarProfissionalEAvancar(id, nome) {
  // Seleciona o profissional
  selecionarProfissional(id, nome);
  // O modal será aberto automaticamente pela função acima
}

// Função para atualizar o resumo
function atualizarResumo() {
  var servico = $('#nome_serv').val() || 'Nenhum serviço selecionado';
  var profissional = $('#nome_func').val() || 'Nenhum profissional selecionado';
  var data = $('#data').val();
  var horario = $('#hora_rec').val() || 'Nenhum horário selecionado';
  
  // Remove segundos do horário se existirem (ex: 07:45:00 -> 07:45)
  if (horario && horario !== 'Nenhum horário selecionado') {
    horario = horario.substring(0, 5);
  }
  
  // Formata a data
  if (data) {
    var dataObj = new Date(data);
    var dia = String(dataObj.getDate()).padStart(2, '0');
    var mes = String(dataObj.getMonth() + 1).padStart(2, '0');
    var ano = dataObj.getFullYear();
    var dataFormatada = dia + '/' + mes + '/' + ano;
    $('#resumo-data').text(dataFormatada);
    $('#data_rec').val(dataFormatada);
  } else {
    $('#resumo-data').text('Nenhuma data selecionada');
  }
  
  $('#resumo-servico').text(servico);
  $('#resumo-profissional').text(profissional);
  $('#resumo-horario').text(horario);
}

// Função para atualizar o resumo final
function atualizarResumoFinal() {
  var servico = $('#nome_serv').val() || 'Nenhum serviço selecionado';
  var profissional = $('#nome_func').val() || 'Nenhum profissional selecionado';
  var data = $('#data').val();
  var horario = $('#hora_rec').val() || 'Nenhum horário selecionado';
  
  // Remove segundos do horário se existirem (ex: 07:45:00 -> 07:45)
  if (horario && horario !== 'Nenhum horário selecionado') {
    horario = horario.substring(0, 5);
  }
  
  // Separa nome do serviço e valor
  if (servico && servico !== 'Nenhum serviço selecionado') {
    var partes = servico.split(' - R$ ');
    var nomeServico = partes[0];
    var valorServico = partes.length > 1 ? 'R$ ' + partes[1] : '';
    
    $('#final-servico').text(nomeServico);
    $('#final-servico-valor').text(valorServico);
  } else {
    $('#final-servico').text(servico);
    $('#final-servico-valor').text('');
  }
  
  // Formata a data
  if (data) {
  var partes = data.split('-'); // ["2025", "04", "24"]
  var ano = parseInt(partes[0], 10);
  var mes = parseInt(partes[1], 10) - 1; // mês começa do 0
  var dia = parseInt(partes[2], 10);
  
  var dataObj = new Date(ano, mes, dia);
  
  var diaStr = String(dataObj.getDate()).padStart(2, '0');
  var mesStr = String(dataObj.getMonth() + 1).padStart(2, '0');
  var anoStr = dataObj.getFullYear();
  
  var dataFormatada = diaStr + '/' + mesStr + '/' + anoStr;
  $('#final-data').text(dataFormatada);
  $('#data_rec').val(dataFormatada);
} else {
    $('#final-data').text('Nenhuma data selecionada');
  }
  
  $('#final-profissional').text(profissional);
  $('#final-horario').text(horario);
}

// Função para mudar funcionário
function mudarFuncionario(){
  var funcionario = $('#funcionario').val();
  var data = $('#data').val();    
  var hora = $('#hora_rec').val();
  
  // Mostra um indicador de carregamento
  $("#listar-horarios").html('<div class="text-center p-3"><div class="spinner-border text-info" role="status"></div><p class="mt-2 text-white-50">Carregando horários...</p></div>');
  
  listarHorarios(funcionario, data, hora);
  listarFuncionario();  
}

// Função para listar horários - CORRIGIDA
function listarHorarios(funcionario, data, hora){  
	var servico = $("#servico").val();
  $.ajax({
    url: "ajax/listar-horarios.php",
    method: 'POST',
    data: {funcionario, data, hora, servico},
    dataType: "text",
    
    success:function(result){
      if(result.trim() === '000'){
        if(typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Atenção',
            text: 'Selecione uma data igual ou maior que hoje!',
            confirmButtonColor: '#3498db'
          });
        } else {
          alert('Selecione uma data igual ou maior que hoje!');
        }
        
        var dt = new Date();
        var dia = String(dt.getDate()).padStart(2, '0');
        var mes = String(dt.getMonth() + 1).padStart(2, '0');
        var ano = dt.getFullYear();
        dataAtual = ano + '-' + mes + '-' + dia;
        $('#data').val(dataAtual);
        return;
      } else {
        $("#listar-horarios").html(result);
        
        // Adiciona evento de clique diretamente aos botões de horário
        $('.btn-horario').off('click').on('click', function() {
          // Remove a classe ativa de todos os botões
          $('.btn-horario').removeClass('btn-info').addClass('btn-outline-info');
          
          // Adiciona a classe ativa ao botão clicado
          $(this).removeClass('btn-outline-info').addClass('btn-info');
          
          // Armazena o horário selecionado nos campos necessários
          var horarioSelecionado = $(this).text().trim();
          // Remove segundos se existirem (ex: 07:45:00 -> 07:45)
          horarioSelecionado = horarioSelecionado.substring(0, 5);
          
          $('#hora_rec').val(horarioSelecionado);
          $('#resumo-horario').text(horarioSelecionado);
          $('#final-horario').text(horarioSelecionado);
          
          // Mostra o sticky footer no mobile (Etapa 3) - só se data E horário estiverem selecionados
          if ($('#data').val() && $('#hora_rec').val()) {
            // Formata a data para exibição
            var dataVal = $('#data').val();
            var partes = dataVal.split('-');
            var dataObj = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
            var diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            var meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
            var diaSemana = diasSemana[dataObj.getDay()];
            var dia = String(dataObj.getDate()).padStart(2, '0');
            var mes = meses[dataObj.getMonth()];
            
            var dataFormatada = diaSemana + '., ' + dia + ' de ' + mes + '.';
            
            // Atualiza o sticky footer com data e horário
            $('#sticky-datetime-info').text('Agendamento selecionado');
            $('#sticky-date-text').text(dataFormatada);
            $('#sticky-time-text').text(horarioSelecionado);
            
            $('.sticky-footer-step-3').addClass('active');
            
            // Atualiza ícones Lucide
            if (typeof lucide !== 'undefined') {
              setTimeout(() => {
                lucide.createIcons();
              }, 100);
            }
          }
        });
        
        // Se já havia um horário selecionado, reseleciona-o
        if(hora && hora !== '') {
          $('.btn-horario').each(function() {
            if($(this).text().trim() === hora) {
              $(this).click();
            }
          });
        }
      }
    },
    error: function() {
      $("#listar-horarios").html('<div class="alert alert-danger">Erro ao carregar horários. Tente novamente.</div>');
    }
  });
}

// Função para buscar nome
function buscarNome(){
  var tel = $('#telefone').val();  
  
  if(tel.length < 8) return; // Só busca se tiver pelo menos 8 dígitos
  
  $.ajax({
    url: "ajax/listar-nome.php",
    method: 'POST',
    data: {tel},
    dataType: "text",
    
    success:function(result){
      var split = result.split("*");
      
      if(split[2] == "" || split[2] == undefined){
        // Nada a fazer
      }else{
        //$("#funcionario").val(parseInt(split[2]));
        //alert(parseInt(split[2]))
        
        // Atualiza a seleção visual do card do profissional
       // $('.professional-card').removeClass('selected');
        //$('.professional-card[data-id="' + parseInt(split[2]) + '"]').addClass('selected');
      }
      
      if(split[5] == "" || split[5] == undefined){
        $("#botao_editar").hide();          
      }else{
        $("#servico").val(parseInt(split[5]));
        
        // Atualiza a seleção visual do card do serviço
        $('.service-card').removeClass('selected');
        $('.service-card[data-id="' + parseInt(split[5]) + '"]').addClass('selected');
        
        $("#botao_editar").show();          
        $("#botao_salvar").text('Novo Agendamento');
      }
      
      $("#nome").val(split[0]);
      
      $("#msg-excluir").text('Deseja Realmente excluir esse agendamento feito para o dia ' + split[7] + ' às ' + split[4]);
      
      mudarFuncionario();
    }
  });  
}

// Função para salvar
function salvar(){
  $('#id').val('');
}

// Função para listar funcionário - CORRIGIDA
function listarFuncionario(){  
  var func = $("#funcionario").val();
  
  $.ajax({
    url: "ajax/listar-funcionario.php",
    method: 'POST',
    data: {func},
    dataType: "text",
    
    success:function(result){
      $("#nome_func").val(result);
      $('#resumo-profissional').text(result);
      //$('#final-profissional').text(result);
    }
  });
}

// Função para mudar serviço - CORRIGIDA
function mudarServico(){
  // Chama listarFuncionarios diretamente sem encadear outras funções
  listarFuncionarios();  
  
  var serv = $("#servico").val();
  
  $.ajax({
    url: "ajax/listar-servico.php",
    method: 'POST',
    data: {serv},
    dataType: "text",
    
    success:function(result){
      $("#nome_serv").val(result);
      $('#resumo-servico').text(result);
      
      // Separa nome do serviço e valor para o resumo final
      if (result && result !== '') {
        var partes = result.split(' - R$ ');
        var nomeServico = partes[0];
        var valorServico = partes.length > 1 ? 'R$ ' + partes[1] : '';
        
        $('#final-servico').text(nomeServico);
        $('#final-servico-valor').text(valorServico);
      } else {
        $('#final-servico').text(result);
        $('#final-servico-valor').text('');
      }
    }
  });
}

// Função para listar funcionários - CORRIGIDA
function listarFuncionarios(){  
  var serv = $("#servico").val();
  
  $.ajax({
    url: "ajax/listar-funcionarios.php",
    method: 'POST',
    data: {serv},
    dataType: "text",
    
    success:function(result){
      // Armazenamos os dados, mas não exibimos o select
      var tempDiv = document.createElement('div');
      tempDiv.innerHTML = result;
      var options = tempDiv.querySelectorAll('option');
      
      // Limpa o valor atual
      $('#funcionario').val('');
    }
  });
}

// IMPORTANTE: Removendo o evento de clique global para evitar conflitos
// Agora o evento é adicionado diretamente na função listarHorarios

// Função para submeter o formulário
$("#form-agenda").submit(function () {
  event.preventDefault();
  
  // Limpa o ID (função salvar)
  $('#id').val('');

  var nome = $('#nome').val();
  var telefone = $('#telefone').val();
  localStorage.setItem('nome_cli_del', nome);
  localStorage.setItem('telefone_cli_del', telefone);

  var mensagem_pagamento = 'Você será redirecionado para a página de pagamento.';
  var opcao_pagar = "<?=$opcao_pagar?>";
  if(opcao_pagar == "Não"){
    mensagem_pagamento = 'Você será redirecionado para a lista de agendamentos!';
  }
  
  // Desabilita ambos os botões (desktop e mobile)
  $('#btn_agendar').prop('disabled', true);
  $('#btn_agendar_mobile').prop('disabled', true);
  $('#mensagem').html('<div class="text-center"><div class="spinner-border text-light" role="status"></div><p class="mt-2 text-white">Processando agendamento...</p></div>');
  
  var formData = new FormData(this);
  
  $.ajax({
    url: "ajax/agendar_temp.php",
    type: 'POST',
    data: formData,
    
    success: function (mensagem) {
      var msg = mensagem.split('*');
      var id_agd = msg[1];
      
      $('#mensagem').text('');
      $('#mensagem').removeClass();
      
      if (msg[0].trim() == "Pré Agendado") {                    
        buscarNome();
        
        if(typeof Swal !== 'undefined') {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Agendamento Pré Realizado',
            html: '<div style="text-align: center; padding: 10px;">' +
                  '<p style="font-size: 16px; margin-bottom: 10px; color: #555;">Seu horário foi reservado.</p>' +
                  '<p style="font-size: 14px; color: #888;">' + mensagem_pagamento + '</p>' +
                  '</div>',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#007A63',
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: 'rgba(0, 0, 0, 0.6)',
            customClass: {
              popup: 'swal-agendamento-popup swal-center-popup',
              title: 'swal-agendamento-title',
              htmlContainer: 'swal-agendamento-content',
              confirmButton: 'swal-agendamento-btn'
            }
          }).then(() => {
            // Redireciona para a tela de pagamento
            window.location="pagamento/"+id_agd+"/100";
          });
        } else {
          // Fallback sem SweetAlert - Cria overlay e modal centralizado
          $('body').append(
            '<div class="modal-overlay-custom">' +
            '<div class="modal-agendamento-success modal-agendamento-center">' +
            '<div class="modal-agendamento-icon">✓</div>' +
            '<h3 class="modal-agendamento-title">Agendamento Pré Realizado</h3>' +
            '<p class="modal-agendamento-text">Seu horário foi reservado.</p>' +
            '<p class="modal-agendamento-info">' + mensagem_pagamento + '</p>' +
            '</div>' +
            '</div>'
          );
          setTimeout(() => {
            // Redireciona para a tela de pagamento
            window.location="pagamento/"+id_agd+"/100";
          }, 3000);
        }
      } else {
        // Mensagem de erro
        if(typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Erro ao Agendar',
            text: msg[0],
            confirmButtonText: 'Tentar Novamente',
            confirmButtonColor: '#dc3545',
            customClass: {
              popup: 'swal-agendamento-popup',
              confirmButton: 'swal-agendamento-btn'
            }
          });
        } else {
          $('#mensagem').html('<div class="alert alert-danger">' + msg[0] + '</div>');
        }
      }
      
      // Reabilita ambos os botões
      $('#btn_agendar').prop('disabled', false);
      $('#btn_agendar_mobile').prop('disabled', false);
    },
    
    error: function() {
      $('#mensagem').html('<div class="alert alert-danger">Erro ao processar agendamento. Tente novamente.</div>');
      // Reabilita ambos os botões
      $('#btn_agendar').prop('disabled', false);
      $('#btn_agendar_mobile').prop('disabled', false);
    },
    
    cache: false,
    contentType: false,
    processData: false,
  });
});
</script>





<script type="text/javascript">
  function irParaProximaEtapa(etapaAtual) {
  $('.next-step[data-step="' + etapaAtual + '"]').click();
}
</script>

<script type="text/javascript">
// ============================================
// SISTEMA DE LOGIN DO CLIENTE
// ============================================

// Variável global para armazenar dados do cliente logado
var clienteLogado = {
  id: null,
  nome: null,
  telefone: null,
  email: null
};

// Função para toggle de senha
function toggleSenha() {
  var senhaInput = document.getElementById('senha_login');
  var toggleIcon = document.getElementById('toggle_senha');
  
  if (senhaInput.type === 'password') {
    senhaInput.type = 'text';
    toggleIcon.className = 'fa fa-eye-slash';
  } else {
    senhaInput.type = 'password';
    toggleIcon.className = 'fa fa-eye';
  }
}

// Função para voltar para a etapa 2
function voltarParaEtapa2() {
  $('#modalLoginCliente').modal('hide');
  // Limpa os campos do modal
  $('#telefone_login').val('');
  $('#nome_login').val('');
  $('#senha_login').val('');
  $('#mensagem-login').html('');
  $('#campo_nome_cadastro').hide();
}

// Máscara para telefone no modal de login e verificação de sessão
$(document).ready(function() {
  if($.fn.mask) {
    $('#telefone_login').mask('(00) 00000-0000');
  }
  
  // Efeito de foco nos inputs do modal
  $('#telefone_login, #nome_login, #senha_login').focus(function() {
    $(this).css('border-color', '#007A63');
    $(this).css('box-shadow', '0 0 0 3px rgba(0, 122, 99, 0.1)');
  }).blur(function() {
    $(this).css('border-color', '#e9ecef');
    $(this).css('box-shadow', 'none');
  });
  
  // Verifica se já está logado ao carregar a página
  verificarSessaoCliente();
  
  // Adiciona indicador de cliente logado na etapa 4 após um delay
  setTimeout(function() {
    if (clienteLogado && clienteLogado.id) {
      var indicadorLogado = '<div class="alert alert-success" style="background: #2a2a2a; border: 1px solid #2a2a2a; border-radius: 12px; padding: 12px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">' +
        '<div style="display: flex; align-items: center; gap: 10px;">' +
        '<i class="fa fa-user-check" style="color:rgb(87, 87, 87); font-size: 18px;"></i>' +
        '<span style="color:rgb(220, 221, 220); font-weight: 600; font-size: 14px;">Você está logado como ' + clienteLogado.nome + '</span>' +
        '</div>' +
        '<button type="button" class="btn btn-sm btn-outline-danger" onclick="fazerLogout()" style="border-radius: 8px; font-size: 12px; padding: 6px 12px;">' +
        '<i class="fa fa-sign-out mr-1"></i> Sair' +
        '</button>' +
        '</div>';
      
      $('.form-grid-modern').before(indicadorLogado);
    }
  }, 500);
});

// Função para verificar se já existe sessão do cliente
function verificarSessaoCliente() {
  var clienteStorage = localStorage.getItem('cliente_logado');
  if (clienteStorage) {
    try {
      clienteLogado = JSON.parse(clienteStorage);
      if (clienteLogado && clienteLogado.id) {
        // Verifica se os dados estão com URL encoding e corrige
        if (clienteLogado.nome && clienteLogado.nome.includes('%')) {
          clienteLogado.nome = decodeURIComponent(clienteLogado.nome);
        }
        if (clienteLogado.telefone && clienteLogado.telefone.includes('%')) {
          clienteLogado.telefone = decodeURIComponent(clienteLogado.telefone);
        }
        // Atualiza o localStorage com dados corrigidos
        localStorage.setItem('cliente_logado', JSON.stringify(clienteLogado));
        
        // Cliente já está logado, preenche os dados
        preencherDadosCliente();
      }
    } catch (e) {
      console.log('Erro ao recuperar sessão do cliente');
    }
  }
}

// Função para preencher dados do cliente na etapa 4
function preencherDadosCliente() {
  if (clienteLogado && clienteLogado.id) {
    // Formata o telefone antes de preencher
    var telefoneFormatado = clienteLogado.telefone;
    
    // Se o telefone não estiver formatado, formata
    if (telefoneFormatado && telefoneFormatado.indexOf('(') === -1) {
      // Remove tudo que não for número
      var numeros = telefoneFormatado.replace(/[^0-9]/g, '');
      
      // Formata: (XX) XXXXX-XXXX
      if (numeros.length === 11) {
        telefoneFormatado = '(' + numeros.substr(0, 2) + ') ' + numeros.substr(2, 5) + '-' + numeros.substr(7, 4);
      } else if (numeros.length === 10) {
        telefoneFormatado = '(' + numeros.substr(0, 2) + ') ' + numeros.substr(2, 4) + '-' + numeros.substr(6, 4);
      }
    }
    
    $('#telefone').val(telefoneFormatado);
    $('#nome').val(clienteLogado.nome);
    
    // Desabilita os campos para que o cliente não altere
    $('#telefone').attr('readonly', true);
    $('#nome').attr('readonly', true);
  }
}

// Verifica telefone ao sair do campo
$('#telefone_login').blur(function() {
  var telefone = $(this).val();
  if (telefone.length >= 14) { // (00) 00000-0000
    verificarTelefoneExistente(telefone);
  }
});

// Função para verificar se o telefone já existe
function verificarTelefoneExistente(telefone) {
  var tel = telefone.replace(/[^0-9]/g, '');
  
  $.ajax({
    url: "ajax/autenticar-cliente.php",
    method: 'POST',
    data: {telefone: tel, buscar_dados: 'sim'},
    dataType: "text",
    
    success: function(result) {
      if (result.trim() === 'Primeiro Acesso') {
        // Mostra o campo de nome para cadastro
        $('#campo_nome_cadastro').show();
        $('#nome_login').val('').prop('required', true);
        $('#texto_btn_login').text('Cadastrar e Entrar');
        
        $('#mensagem-login').html(
          '<div class="alert alert-info" style="background: rgba(0, 123, 255, 0.08); border: 1px solid rgba(0, 123, 255, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-info-circle mr-2"></i>' +
          '<small>Primeiro acesso detectado. Preencha seu nome para criar sua conta.</small>' +
          '</div>'
        );
      } else if (result.includes('Existe')) {
        // Telefone já existe - preenche os dados automaticamente
        var dados = result.split('*');
        var nome = decodeURIComponent(dados[2]);
        
        // Preenche o campo nome automaticamente
        $('#nome_login').val(nome);
        
        // Oculta o campo de nome (só mostra senha)
        $('#campo_nome_cadastro').hide();
        $('#nome_login').prop('required', false);
        $('#texto_btn_login').text('Entrar');
        
        // Mostra mensagem informativa
        $('#mensagem-login').html(
          '<div class="alert alert-success" style="background: rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-check-circle mr-2"></i>' +
          '<small>Bem-vindo(a) de volta, <strong>' + nome + '</strong>! Digite sua senha para continuar.</small>' +
          '</div>'
        );
      }
    }
  });
}

// Form submit do modal de login
$("#form-login-cliente").submit(function(event) {
  event.preventDefault();
  
  var telefone = $('#telefone_login').val();
  var senha = $('#senha_login').val();
  var nome = $('#nome_login').val();
  
  if (!telefone || !senha) {
    $('#mensagem-login').html(
      '<div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.08); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
      '<i class="fa fa-exclamation-circle mr-2"></i>' +
      '<small>Preencha todos os campos obrigatórios!</small>' +
      '</div>'
    );
    return;
  }
  
  // Desabilita o botão
  $('#btn_login').prop('disabled', true);
  $('#mensagem-login').html(
    '<div class="text-center">' +
    '<div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">' +
    '<span class="sr-only">Carregando...</span>' +
    '</div>' +
    '</div>'
  );
  
  var tel = telefone.replace(/[^0-9]/g, '');
  
  $.ajax({
    url: "ajax/autenticar-cliente.php",
    method: 'POST',
    data: {telefone: tel, senha: senha},
    dataType: "text",
    
    success: function(result) {
      if (result.includes('Autenticado')) {
        // Login bem-sucedido
        var dados = result.split('*');
        clienteLogado = {
          id: dados[1],
          nome: decodeURIComponent(dados[2]),
          telefone: decodeURIComponent(dados[3]),
          email: dados[4] || ''
        };
        
        // Salva no localStorage
        localStorage.setItem('cliente_logado', JSON.stringify(clienteLogado));
        
        // Salva na sessão PHP (cookie por 30 dias)
        $.ajax({
          url: "ajax/salvar-sessao-cliente.php",
          method: 'POST',
          data: {
            id: clienteLogado.id,
            nome: clienteLogado.nome,
            telefone: clienteLogado.telefone
          },
          dataType: "text"
        });
        
        // Preenche os dados na etapa 4
        preencherDadosCliente();
        
        // Mostra mensagem de sucesso
        $('#mensagem-login').html(
          '<div class="alert alert-success" style="background: rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-check-circle mr-2"></i>' +
          '<small>Login realizado com sucesso!</small>' +
          '</div>'
        );
        
        // Fecha o modal e avança para a etapa 3
        setTimeout(function() {
          $('#modalLoginCliente').modal('hide');
          avancarParaEtapa3();
        }, 1000);
        
      } else if (result.trim() === 'Primeiro Acesso') {
        // Primeiro acesso - precisa cadastrar
        if (!nome) {
          $('#mensagem-login').html(
            '<div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
            '<i class="fa fa-exclamation-triangle mr-2"></i>' +
            '<small>Digite seu nome para criar sua conta!</small>' +
            '</div>'
          );
          $('#btn_login').prop('disabled', false);
          return;
        }
        
        // Cadastra o novo cliente
        cadastrarNovoCliente(tel, nome, senha);
        
      } else {
        // Senha incorreta ou outro erro
        $('#mensagem-login').html(
          '<div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.08); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-times-circle mr-2"></i>' +
          '<small>' + result + '</small>' +
          '</div>'
        );
        $('#btn_login').prop('disabled', false);
      }
    },
    
    error: function() {
      $('#mensagem-login').html(
        '<div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.08); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
        '<i class="fa fa-times-circle mr-2"></i>' +
        '<small>Erro ao processar login. Tente novamente.</small>' +
        '</div>'
      );
      $('#btn_login').prop('disabled', false);
    }
  });
});

// Função para cadastrar novo cliente
function cadastrarNovoCliente(telefone, nome, senha) {
  $.ajax({
    url: "ajax/cadastrar-cliente-rapido.php",
    method: 'POST',
    data: {telefone: telefone, nome: nome},
    dataType: "text",
    
    success: function(result) {
      if (result.includes('Cadastrado')) {
        // Cadastro bem-sucedido
        var dados = result.split('*');
        clienteLogado = {
          id: dados[1],
          nome: decodeURIComponent(dados[2]),
          telefone: decodeURIComponent(dados[3]),
          email: ''
        };
        
        // Salva no localStorage
        localStorage.setItem('cliente_logado', JSON.stringify(clienteLogado));
        
        // Salva na sessão PHP (cookie por 30 dias)
        $.ajax({
          url: "ajax/salvar-sessao-cliente.php",
          method: 'POST',
          data: {
            id: clienteLogado.id,
            nome: clienteLogado.nome,
            telefone: clienteLogado.telefone
          },
          dataType: "text"
        });
        
        // Preenche os dados na etapa 4
        preencherDadosCliente();
        
        // Mostra mensagem de sucesso
        $('#mensagem-login').html(
          '<div class="alert alert-success" style="background: rgba(40, 167, 69, 0.08); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-check-circle mr-2"></i>' +
          '<small>Conta criada com sucesso! Bem-vindo(a)!</small>' +
          '</div>'
        );
        
        // Fecha o modal e avança para a etapa 3
        setTimeout(function() {
          $('#modalLoginCliente').modal('hide');
          avancarParaEtapa3();
        }, 1500);
        
      } else if (result.includes('Cliente já cadastrado')) {
        // Cliente já existe - usa os dados do cliente existente
        var dados = result.split('*');
        clienteLogado = {
          id: dados[1],
          nome: decodeURIComponent(dados[2]),
          telefone: decodeURIComponent(dados[3]),
          email: ''
        };
        
        // Salva no localStorage
        localStorage.setItem('cliente_logado', JSON.stringify(clienteLogado));
        
        // Salva na sessão PHP (cookie por 30 dias)
        $.ajax({
          url: "ajax/salvar-sessao-cliente.php",
          method: 'POST',
          data: {
            id: clienteLogado.id,
            nome: clienteLogado.nome,
            telefone: clienteLogado.telefone
          },
          dataType: "text"
        });
        
        // Preenche os dados na etapa 4
        preencherDadosCliente();
        
        // Mostra mensagem informativa
        $('#mensagem-login').html(
          '<div class="alert alert-info" style="background: rgba(0, 123, 255, 0.08); border: 1px solid rgba(0, 123, 255, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-info-circle mr-2"></i>' +
          '<small>Cliente encontrado! Bem-vindo(a) de volta, <strong>' + clienteLogado.nome + '</strong>!</small>' +
          '</div>'
        );
        
        // Fecha o modal e avança para a etapa 3
        setTimeout(function() {
          $('#modalLoginCliente').modal('hide');
          avancarParaEtapa3();
        }, 1500);
        
      } else {
        $('#mensagem-login').html(
          '<div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.08); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
          '<i class="fa fa-times-circle mr-2"></i>' +
          '<small>' + result + '</small>' +
          '</div>'
        );
      }
      $('#btn_login').prop('disabled', false);
    },
    
    error: function() {
      $('#mensagem-login').html(
        '<div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.08); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 10px; padding: 12px; margin: 0;">' +
        '<i class="fa fa-times-circle mr-2"></i>' +
        '<small>Erro ao criar conta. Tente novamente.</small>' +
        '</div>'
      );
      $('#btn_login').prop('disabled', false);
    }
  });
}

// Função para avançar para a etapa 3 (após login)
function avancarParaEtapa3() {
  // Carrega os horários quando avança para a etapa 3
  mudarFuncionario();
  
  // Oculta sticky footers da etapa 3
  $('.sticky-footer-step-3').removeClass('active');
  $('.sticky-footer-empty-3').removeClass('active');
  
  // Avança para a próxima etapa
  $('.form-step, .form-step-modern').removeClass('active');
  $('#step-3-content').addClass('active');
  
  // Atualiza os indicadores de etapa
  $('.step, .step-item').removeClass('active');
  $('#step-3, #step-indicator-3').addClass('active');
  
  // Marca etapas anteriores como concluídas
  $('#step-1, #step-indicator-1').addClass('completed');
  $('#step-2, #step-indicator-2').addClass('completed');
  
  // Scroll para o topo
  setTimeout(function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }, 100);
}

// Função para fazer logout
function fazerLogout() {
  if(confirm('Deseja realmente sair da sua conta?')) {
    // Limpa os dados
    clienteLogado = {
      id: null,
      nome: null,
      telefone: null,
      email: null
    };
    localStorage.removeItem('cliente_logado');
    
    // Remove cookies
    document.cookie = 'id_cliente=; Max-Age=0; path=/;';
    document.cookie = 'nome_cliente=; Max-Age=0; path=/;';
    document.cookie = 'telefone_cliente=; Max-Age=0; path=/;';
    
    // Limpa os campos
    $('#telefone').val('').attr('readonly', false);
    $('#nome').val('').attr('readonly', false);
    
    // Remove o indicador
    $('.alert-success').remove();
    
    // Volta para a etapa 1
    $('.form-step, .form-step-modern').removeClass('active');
    $('#step-1-content').addClass('active');
    
    $('.step, .step-item').removeClass('active completed');
    $('#step-1, #step-indicator-1').addClass('active');
    
    window.scrollTo({top: 0, behavior: 'smooth'});
  }
}

// Função para abrir o modal de login
function abrirModalLogin() {
  // Verifica se o cliente já está logado
  if (clienteLogado && clienteLogado.id) {
    // Cliente já está logado, avança direto para a etapa 3
    avancarParaEtapa3();
    return;
  }
  
  // Limpa os campos
  $('#telefone_login').val('');
  $('#nome_login').val('');
  $('#senha_login').val(''); // Campo senha vazio para o usuário digitar
  $('#mensagem-login').html('');
  $('#campo_nome_cadastro').hide();
  $('#texto_btn_login').text('Entrar');
  
  // Abre o modal
  $('#modalLoginCliente').modal('show');
}
</script>


<script>
    // Registra o SW
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/sw.js')
        .then(reg => console.log('✅ SW registrado:', reg.scope))
        .catch(err => console.error('❌ Erro ao registrar SW:', err));
    }

    // Gerencia instalação
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPrompt = e;
      const btn = document.getElementById('botao-instalar');
      btn.style.display = 'block';

      btn.addEventListener('click', () => {
        btn.style.display = 'none';
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((result) => {
          console.log('Resultado da instalação:', result.outcome);
          deferredPrompt = null;
        });
      });
    });

    window.addEventListener('appinstalled', () => {
      console.log('✅ App instalado!');
    });
  </script>


  <script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('sw.js')
      .then(reg => {
        console.log('✅ Service Worker registrado:', reg.scope);
      })
      .catch(err => {
        console.error('❌ Erro ao registrar Service Worker:', err);
      });
  }
</script>


 
<script src="app/js/custom.js"></script>



