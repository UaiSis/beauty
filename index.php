<?php 
@session_start();
require_once("cabecalho.php");

// Verifica se o usuário está logado (exemplo: verifica se existe uma sessão)
if (!isset($_SESSION['usuario_logado_pagina'])) {
    if($entrada == 'Login'){
    echo '<script>window.location="sistema/acesso"</script>';
  }

  if($entrada == 'Agendamento'){
    echo '<script>window.location="agendamento"</script>';
  }


}

 ?>




<?php 
$query = $pdo->query("SELECT * FROM textos_index ORDER BY id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if($total_reg > 0){
 ?>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- slider section -->
    <section class="slider_section modern-hero">
      <div class="hero-overlay"></div>
      <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
          <?php 
          for($i=0; $i < $total_reg; $i++){
            foreach ($res[$i] as $key => $value){}
            $id = $res[$i]['id'];
            $titulo = $res[$i]['titulo'];
            $descricao = $res[$i]['descricao'];
          ?>
          <div class="swiper-slide">
            <div class="hero-content">
              <div class="container">
                <div class="row">
                  <div class="col-lg-7 col-md-10">
                    <div class="hero-text">
                      <h1 class="hero-title"><?php echo $titulo ?></h1>
                      <p class="hero-description"><?php echo $descricao ?></p>
                      <div class="hero-actions">
                        <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" class="btn-primary-hero">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                          </svg>
                          <span>Fale Conosco</span>
                        </a>
                        <a href="agendamentos" class="btn-secondary-hero">
                          <span>Agendar Agora</span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                          </svg>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
        
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
      </div>
    </section>
    <!-- end slider section -->

<style>
      /* Modern Hero Section */
      .modern-hero {
        position: relative;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden;
        z-index: auto;
      }

      .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.4) 100%);
        z-index: 1;
        pointer-events: none;
      }

      .heroSwiper {
        width: 100%;
        height: 100vh;
        min-height: 700px;
      }

      .heroSwiper .swiper-slide {
    display: flex;
    align-items: center;
        justify-content: center;
      }

      .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 100px 0;
      }

      .hero-text {
        animation: fadeInUp 1s ease-out;
      }

      .hero-title {
        font-size: 72px !important;
        font-weight: 900 !important;
        color: #FEFEFE !important;
        line-height: 1.1 !important;
        margin-bottom: 28px !important;
        letter-spacing: -2px;
        text-shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
      }

      .hero-description {
        font-size: 22px !important;
        color: rgba(254, 254, 254, 0.9) !important;
        line-height: 1.7 !important;
        margin-bottom: 40px !important;
        max-width: 580px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      }

      .hero-actions {
    display: flex;
        gap: 16px;
        flex-wrap: wrap;
      }

      /* Botão Primary Verde - Com !important para sobrescrever CSS antigo */
      .btn-primary-hero {
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding: 18px 32px !important;
        background: #007A63 !important;
        color: #FEFEFE !important;
        border: none !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        font-weight: 600 !important;
        font-size: 16px !important;
        transition: all 0.3s ease !important;
        position: relative;
        overflow: hidden;
      }

      .btn-primary-hero:hover {
        background: #006654 !important;
        color: #FEFEFE !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 122, 99, 0.3);
      }

      .btn-primary-hero svg {
        transition: transform 0.3s ease;
      }

      .btn-primary-hero:hover svg {
        transform: scale(1.1);
      }

      /* Botão Secondary */
      .btn-secondary-hero {
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding: 18px 32px !important;
        background: transparent !important;
        color: #FEFEFE !important;
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px !important;
        text-decoration: none !important;
        font-weight: 600 !important;
        font-size: 16px !important;
        transition: all 0.3s ease !important;
        backdrop-filter: blur(10px);
      }

      .btn-secondary-hero:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: #FEFEFE !important;
        color: #FEFEFE !important;
        transform: translateY(-2px);
      }

      .btn-secondary-hero svg {
        transition: transform 0.3s ease;
      }

      .btn-secondary-hero:hover svg {
        transform: translateX(5px);
      }

      /* Setas Modernas */
      .heroSwiper .swiper-button-next,
      .heroSwiper .swiper-button-prev {
        width: 60px;
        height: 60px;
        background: rgba(0, 122, 99, 0.9);
        border-radius: 50%;
        color: #FEFEFE;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
      }

      .heroSwiper .swiper-button-next:after,
      .heroSwiper .swiper-button-prev:after {
        font-size: 24px;
        font-weight: bold;
      }

      .heroSwiper .swiper-button-next:hover,
      .heroSwiper .swiper-button-prev:hover {
        background: #007A63;
    transform: scale(1.1);
  }

      /* Paginação */
      .heroSwiper .swiper-pagination {
        bottom: 50px;
      }

      .heroSwiper .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 1;
        transition: all 0.3s ease;
      }

      .heroSwiper .swiper-pagination-bullet-active {
        background: #007A63;
        width: 36px;
        border-radius: 6px;
      }

      /* Animações */
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .heroSwiper .swiper-slide-active .hero-title {
        animation: fadeInUp 0.8s ease-out;
      }

      .heroSwiper .swiper-slide-active .hero-description {
        animation: fadeInUp 0.8s ease-out 0.15s both;
      }

      .heroSwiper .swiper-slide-active .hero-actions {
        animation: fadeInUp 0.8s ease-out 0.3s both;
      }

      /* Responsive */
      @media (max-width: 992px) {
        .heroSwiper {
          min-height: 600px;
        }

        .hero-title {
          font-size: 56px !important;
        }

        .hero-description {
          font-size: 20px !important;
        }
      }

      @media (max-width: 768px) {
        .heroSwiper {
          min-height: 550px;
        }

        .hero-content {
    padding: 80px 0;
        }

        .hero-title {
          font-size: 42px !important;
          letter-spacing: -1px;
        }

        .hero-description {
          font-size: 17px !important;
          margin-bottom: 32px !important;
        }

        .hero-actions {
          flex-direction: column;
          gap: 12px;
        }

        .btn-primary-hero,
        .btn-secondary-hero {
          width: 100%;
          justify-content: center !important;
          padding: 16px 28px !important;
          font-size: 15px !important;
        }

        .heroSwiper .swiper-button-next,
        .heroSwiper .swiper-button-prev {
          width: 48px;
          height: 48px;
        }

        .heroSwiper .swiper-button-next:after,
        .heroSwiper .swiper-button-prev:after {
          font-size: 20px;
        }

        .heroSwiper .swiper-pagination {
          bottom: 30px;
        }
      }
    </style>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
      var swiper = new Swiper(".heroSwiper", {
        loop: true,
        speed: 1000,
        effect: 'fade',
        fadeEffect: {
          crossFade: true
        },
        autoplay: {
          delay: 6000,
          disableOnInteraction: false,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
          dynamicBullets: false,
        },
      });
    </script>

  <?php } ?>

  </div>


  <!-- product section -->
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

  <section class="services-section">
    <div class="services-container">
      <!-- Header da Seção -->
      <div class="services-header">
        <div class="top-badge">Serviços Premium</div>
        <h2 class="section-title">Nossos Serviços</h2>
        <p class="section-subtitle">
          <?php echo implode(' • ', $categorias); ?>
        </p>
      </div>

      <!-- Vue App para Serviços -->
      <div id="services-app" 
           data-services='<?php echo json_encode($servicos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
        <div class="services-grid">
          <div v-for="service in services.slice(0, 4)" :key="service.id" class="service-card">
            <div class="card-inner">
              <div class="service-image-wrapper">
                <img :src="'sistema/painel/img/servicos/' + service.foto" :alt="service.nome" class="service-img">
              </div>
              <div class="service-info">
                <h3 class="service-title">{{ service.nome }}</h3>
                <div class="service-footer">
                  <div class="service-price">
                    <span class="price-label">A partir de</span>
                    <span class="price-value">R$ {{ formatPrice(service.valor) }}</span>
                  </div>
                  <a href="agendamentos" class="service-arrow">
                    <i data-lucide="arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="view-more-container">
          <a href="servicos" class="btn-view-more">
            <span>Ver Todos os Serviços</span>
            <i data-lucide="chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <style>
    /* Services Section Ultra Modern */
    .services-section {
      padding: 120px 0;
      background: linear-gradient(180deg, #006B56 0%, #005246 100%);
      position: relative;
    }

    .services-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 40px;
    }

    .services-header {
      text-align: center;
      margin-bottom: 70px;
    }

    .top-badge {
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
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 52px;
      font-weight: 900;
      color: #FEFEFE;
      margin-bottom: 16px;
      letter-spacing: -1.5px;
    }

    .section-subtitle {
      font-size: 18px;
      color: rgba(254, 254, 254, 0.8);
      font-weight: 400;
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 28px;
      margin-bottom: 60px;
    }

    .service-card {
      background: #FEFEFE;
      border-radius: 20px;
      padding: 16px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      border: 3px solid transparent;
    }

    .service-card:hover {
      transform: translateY(-8px);
      border-color: #B9E4D4;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
    }

    .card-inner {
      position: relative;
    }

    .service-image-wrapper {
      width: 100%;
      height: 240px;
      border-radius: 16px;
    overflow: hidden;
      background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
      margin-bottom: 16px;
      position: relative;
    }

    .service-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .service-card:hover .service-img {
      transform: scale(1.08);
    }

    .service-info {
      padding: 0 6px;
    }

    .service-title {
      font-size: 19px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 14px;
      line-height: 1.3;
    }

    .service-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .service-price {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .price-label {
      font-size: 11px;
      color: #999;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .price-value {
      font-size: 22px;
      font-weight: 800;
      color: #007A63;
    }

    .service-arrow {
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

    .service-arrow i {
      color: white;
      stroke: white;
      width: 20px;
      height: 20px;
    }

    .service-card:hover .service-arrow {
    background: #006654;
      transform: scale(1.15) rotate(-45deg);
    }

    .view-more-container {
      text-align: center;
      margin-top: 60px;
    }

    .btn-view-more {
      display: inline-flex !important;
      align-items: center !important;
      gap: 12px !important;
      padding: 18px 40px !important;
      background: transparent !important;
      color: #FEFEFE !important;
      border: 2px solid rgba(255, 255, 255, 0.3) !important;
      border-radius: 50px !important;
      text-decoration: none !important;
      font-weight: 600 !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
    }

    .btn-view-more:hover {
      background: rgba(255, 255, 255, 0.1) !important;
      border-color: #FEFEFE !important;
      color: #FEFEFE !important;
      transform: translateY(-2px);
    }

    .btn-view-more i {
      color: #FEFEFE;
      stroke: #FEFEFE;
      width: 20px;
      height: 20px;
      transition: transform 0.3s ease;
    }

    .btn-view-more:hover i {
      transform: translateX(5px);
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .services-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
      }
    }

    @media (max-width: 992px) {
      .services-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
      }

      .section-title {
        font-size: 42px;
      }

      .service-image-wrapper {
        height: 220px;
      }
    }

  @media (max-width: 768px) {
      .services-section {
        padding: 80px 0;
      }

      .services-container {
        padding: 0 20px;
      }

      .section-title {
        font-size: 36px;
      }

      .section-subtitle {
        font-size: 16px;
      }

      .services-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .service-card {
        padding: 14px;
      }

      .service-image-wrapper {
      height: 200px;
    }

      .service-title {
        font-size: 18px;
        margin-bottom: 12px;
      }

      .price-value {
        font-size: 20px;
      }

      .service-arrow {
        width: 38px;
        height: 38px;
      }

      .service-arrow i {
        width: 18px;
        height: 18px;
      }
    }
  </style>

  <script>
    (function() {
      const servicesEl = document.getElementById('services-app');
      if (!servicesEl) return;

      const servicesData = JSON.parse(servicesEl.dataset.services || '[]');

      const { createApp } = Vue;

      const ServicesApp = {
        data() {
          return {
            services: servicesData,
            loading: false
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

      createApp(ServicesApp).mount('#services-app');
    })();
  </script>

  <?php } ?>

  <!-- product section ends -->

  <!-- about section -->
  <?php 
  $tem_imagem = !empty($imagem_sobre);
  $tem_video = !empty($url_video) && $posicao_video == 'sobre';
  $tem_ambos = $tem_imagem && $tem_video;
  ?>
  
  <section class="about-section-modern">
    <div class="about-container">
      <div class="about-content">
        <div class="about-grid">
          <!-- Imagem/Vídeo -->
          <div class="about-image-side <?php echo $tem_ambos ? 'has-both' : ''; ?>">
            
            <?php if($tem_ambos){ ?>
              <!-- Quando tem AMBOS: Imagem pequena + Vídeo grande -->
              <div class="about-card about-card-small">
                <img src="images/<?php echo $imagem_sobre ?>" class="about-img-small" alt="Sobre Nós">
                <div class="about-social-icons">
                  <a href="<?php echo $instagram_sistema ?>" target="_blank" class="social-icon">
                    <i data-lucide="instagram"></i>
                  </a>
                  <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                      </a>
                    </div>
                  </div>

              <div class="about-card about-card-large">
                <div class="about-video-wrapper">
                  <iframe width="100%" height="100%" src="<?php echo $url_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
              </div>

            <?php }else if($tem_video){ ?>
              <!-- Quando tem APENAS VÍDEO -->
              <div class="about-card">
                <div class="about-video-wrapper">
                  <iframe width="100%" height="100%" src="<?php echo $url_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
                <div class="about-social-icons">
                  <a href="<?php echo $instagram_sistema ?>" target="_blank" class="social-icon">
                    <i data-lucide="instagram"></i>
                  </a>
                  <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                  </a>
          </div>
              </div>

            <?php }else if($tem_imagem){ ?>
              <!-- Quando tem APENAS IMAGEM -->
              <div class="about-card">
                <img src="images/<?php echo $imagem_sobre ?>" class="about-img" alt="Sobre Nós">
                <div class="about-social-icons">
                  <a href="<?php echo $instagram_sistema ?>" target="_blank" class="social-icon">
                    <i data-lucide="instagram"></i>
                  </a>
                  <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" class="social-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                  </a>
        </div>
              </div>
            <?php } ?>
          </div>

          <!-- Texto -->
          <div class="about-text-side">
            <div class="about-badge">// Sobre Nós</div>
            <h2 class="about-title">
              <span class="title-highlight">Experiências</span> que transformam seu visual
            </h2>
            <p class="about-description">
              <?php echo $texto_sobre ?>
            </p>
            <div class="about-actions">
              <a href="agendamentos" class="btn-about-primary">
                <span>Agendar Agora</span>
                <i data-lucide="calendar"></i>
              </a>
              <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" class="btn-about-secondary">
                <span>Fale Conosco</span>
              </a>
            </div>
          </div>
        </div>
          </div>
        </div>
        
    <?php if($url_video != "" and $posicao_video == 'abaixo'){ ?>
    <div class="about-video-bottom">
      <div class="container">
        <iframe class="video-responsive" width="100%" src="<?php echo $url_video ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>
    </div>
    <?php } ?>
    </section>

  <style>
    /* About Section Modern */
    .about-section-modern {
      background: #f3ede0;
      padding: 120px 0;
      position: relative;
    }

    .about-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 40px;
    }

    .about-grid {
      display: grid;
      grid-template-columns: 45% 55%;
      gap: 80px;
      align-items: center;
    }

    /* Lado da Imagem */
    .about-image-side {
      position: relative;
    }

    /* Quando tem ambos - layout em coluna */
    .about-image-side.has-both {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .about-card {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      background: #1a1a1a;
      padding: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    /* Card pequeno (imagem quando tem ambos) */
    .about-card-small {
      height: 280px;
    }

    .about-img-small {
      width: 100%;
      height: 240px;
      object-fit: cover;
      border-radius: 16px;
      display: block;
    }

    /* Card grande (vídeo quando tem ambos) */
    .about-card-large {
      flex: 1;
      min-height: 320px;
    }

    .about-card-large .about-video-wrapper {
      height: 280px;
    }

    /* Quando tem apenas um (imagem OU vídeo) */
    .about-img {
      width: 100%;
      height: 600px;
      object-fit: cover;
      border-radius: 16px;
      display: block;
    }

    .about-video-wrapper {
      width: 100%;
      height: 600px;
      border-radius: 16px;
      overflow: hidden;
    }

    .about-video-wrapper iframe {
      width: 100%;
      height: 100%;
    }

    .about-social-icons {
      position: absolute;
      bottom: 40px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 16px;
      z-index: 10;
    }

    .social-icon {
      width: 48px;
      height: 48px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .social-icon i {
      color:rgb(209, 71, 71);
      stroke: #FEFEFE;
      width: 20px;
      height: 20px;
    }

    .social-icon svg {
      fill: #FEFEFE;
    }

    .social-icon:hover {
      background: #007A63;
      border-color: #007A63;
      transform: translateY(-4px);
    }

    /* Lado do Texto */
    .about-text-side {
      padding: 40px 0;
    }

    .about-badge {
      font-size: 13px;
      font-weight: 700;
      color: #666;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 20px;
    }

    .about-title {
      font-size: 56px;
      font-weight: 900;
      color: #1a1a1a;
      line-height: 1.15;
      margin-bottom: 32px;
      letter-spacing: -1.5px;
    }

    .about-title .title-highlight {
      color: #006854;
      display: block;
    }

    .about-description {
      font-size: 18px;
      color: #555;
      line-height: 1.8;
      margin-bottom: 40px;
    }

    .about-actions {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }

    .btn-about-primary {
      display: inline-flex !important;
      align-items: center !important;
      gap: 10px !important;
      padding: 18px 36px !important;
      background: #007A63 !important;
      color: #FEFEFE !important;
      border: none !important;
      border-radius: 12px !important;
      text-decoration: none !important;
      font-weight: 600 !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
    }

    .btn-about-primary:hover {
      background: #006654 !important;
      color: #FEFEFE !important;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 122, 99, 0.3);
    }

    .btn-about-primary i {
      color: #FEFEFE;
      stroke: #FEFEFE;
      width: 20px;
      height: 20px;
    }

    .btn-about-secondary {
      display: inline-flex !important;
      align-items: center !important;
      gap: 10px !important;
      padding: 18px 36px !important;
      background: transparent !important;
      color: #007A63 !important;
      border: 2px solid #007A63 !important;
      border-radius: 12px !important;
      text-decoration: none !important;
      font-weight: 600 !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
    }

    .btn-about-secondary:hover {
      background: #007A63 !important;
      color: #FEFEFE !important;
      transform: translateY(-2px);
    }

    .about-video-bottom {
      margin-top: 60px;
      padding: 0 40px;
    }

    .video-responsive {
      width: 100%;
      height: 500px;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    /* Responsive */
    @media (max-width: 992px) {
      .about-grid {
        grid-template-columns: 1fr;
        gap: 60px;
      }

      .about-title {
        font-size: 44px;
      }

      .about-img {
        height: 500px;
      }

      .about-card-large .about-video-wrapper {
        height: 350px;
      }
    }

    @media (max-width: 768px) {
      .about-section-modern {
        padding: 80px 0;
      }

      .about-container {
        padding: 0 20px;
      }

      .about-title {
        font-size: 36px;
      }

      .about-description {
        font-size: 16px;
      }

      .about-img {
        height: 400px;
      }

      .about-video-wrapper {
        height: 400px;
      }

      .about-card-small {
        height: 240px;
      }

      .about-img-small {
        height: 200px;
      }

      .about-card-large .about-video-wrapper {
        height: 300px;
      }

      .about-actions {
        flex-direction: column;
      }

      .btn-about-primary,
      .btn-about-secondary {
        width: 100%;
        justify-content: center !important;
      }

      .video-responsive {
        height: 300px;
      }
    }
  </style>

  <script>
    if (typeof lucide !== 'undefined') {
      document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
      });
    }
  </script>

  <!-- about section ends -->

  <!-- product section -->
  <?php 
  $query = $pdo->query("SELECT * FROM produtos where estoque > 0 and valor_venda > 0 ORDER BY id desc limit 8");
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

  <section class="products-section">
    <div class="products-container">
      <!-- Header da Seção -->
      <div class="products-header">
        <div class="top-badge-products">Produtos de Qualidade</div>
        <h2 class="section-title-products">Nossos Produtos</h2>
        <p class="section-subtitle-products">Produtos selecionados para você</p>
          </div>

      <!-- Vue App para Produtos -->
      <div id="products-app" 
           data-products='<?php echo json_encode($produtos, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
           data-whatsapp="<?php echo $tel_whatsapp; ?>">
        <div class="products-grid">
          <div v-for="product in products.slice(0, 4)" :key="product.id" class="product-card">
            <div class="card-inner">
              <div class="product-image-wrapper">
                <img :src="'sistema/painel/img/produtos/' + product.foto" :alt="product.nome" :title="product.descricao" class="product-img">
        </div>
              <div class="product-info">
                <h3 class="product-title">{{ product.nome }}</h3>
                <div class="product-footer">
                  <div class="product-price">
                    <span class="price-label">Apenas</span>
                    <span class="price-value">R$ {{ formatPrice(product.valor) }}</span>
            </div>
                  <a :href="getWhatsappLink(product)" target="_blank" class="product-arrow">
                    <i data-lucide="shopping-bag"></i>
            </a>
          </div>
              </div>
            </div>
          </div>
        </div>

        <div class="view-more-container">
          <a href="produtos" class="btn-view-more-products">
            <span>Ver Todos os Produtos</span>
            <i data-lucide="arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <style>
    /* Products Section - Mesmo padrão de Services */
    .products-section {
      padding: 120px 0;
      background: #006854;
      position: relative;
    }

    .products-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 40px;
    }

    .products-header {
      text-align: center;
      margin-bottom: 70px;
    }

    .top-badge-products {
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
      margin-bottom: 20px;
    }

    .section-title-products {
      font-size: 52px;
      font-weight: 900;
      color: #FEFEFE;
      margin-bottom: 16px;
      letter-spacing: -1.5px;
    }

    .section-subtitle-products {
      font-size: 18px;
      color: rgba(254, 254, 254, 0.8);
      font-weight: 400;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 28px;
      margin-bottom: 60px;
    }

    .product-card {
      background: #FEFEFE;
      border-radius: 20px;
      padding: 16px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      border: 3px solid transparent;
    }

    .product-card:hover {
      transform: translateY(-8px);
      border-color: #B9E4D4;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }

    .card-inner {
      position: relative;
    }

    .product-image-wrapper {
      width: 100%;
      height: 240px;
      border-radius: 16px;
      overflow: hidden;
      background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
      margin-bottom: 16px;
      position: relative;
    }

    .product-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .product-card:hover .product-img {
      transform: scale(1.08);
    }

    .product-info {
      padding: 0 6px;
    }

    .product-title {
      font-size: 19px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 14px;
      line-height: 1.3;
    }

    .product-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .product-price {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .price-label {
      font-size: 11px;
      color: #999;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .price-value {
      font-size: 22px;
      font-weight: 800;
      color: #007A63;
    }

    .product-arrow {
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

    .product-arrow i {
      color: white;
      stroke: white;
      width: 20px;
      height: 20px;
    }

    .product-card:hover .product-arrow {
      background: #006654;
      transform: scale(1.15) rotate(-45deg);
    }

    .view-more-container {
      text-align: center;
      margin-top: 60px;
    }

    .btn-view-more-products {
      display: inline-flex !important;
      align-items: center !important;
      gap: 12px !important;
      padding: 18px 40px !important;
      background: transparent !important;
      color: #FEFEFE !important;
      border: 2px solid rgba(255, 255, 255, 0.3) !important;
      border-radius: 50px !important;
      text-decoration: none !important;
      font-weight: 600 !important;
      font-size: 16px !important;
      transition: all 0.3s ease !important;
    }

    .btn-view-more-products:hover {
      background: rgba(255, 255, 255, 0.1) !important;
      border-color: #FEFEFE !important;
      color: #FEFEFE !important;
      transform: translateY(-2px);
    }

    .btn-view-more-products i {
      color: #FEFEFE;
      stroke: #FEFEFE;
      width: 20px;
      height: 20px;
      transition: transform 0.3s ease;
    }

    .btn-view-more-products:hover i {
      transform: translateX(5px);
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
      }
    }

    @media (max-width: 992px) {
      .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
      }

      .section-title-products {
        font-size: 42px;
      }

      .product-image-wrapper {
        height: 220px;
      }
    }

    @media (max-width: 768px) {
      .products-section {
        padding: 80px 0;
      }

      .products-container {
        padding: 0 20px;
      }

      .section-title-products {
        font-size: 36px;
      }

      .section-subtitle-products {
        font-size: 16px;
      }

      .products-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .product-card {
        padding: 14px;
      }

      .product-image-wrapper {
        height: 200px;
      }

      .product-title {
        font-size: 18px;
      }

      .price-value {
        font-size: 20px;
      }

      .product-arrow {
        width: 38px;
        height: 38px;
      }

      .product-arrow i {
        width: 18px;
        height: 18px;
      }
    }
  </style>

  <script>
    (function() {
      const productsEl = document.getElementById('products-app');
      if (!productsEl) return;

      const productsData = JSON.parse(productsEl.dataset.products || '[]');
      const whatsappNumber = productsEl.dataset.whatsapp || '';

      const { createApp } = Vue;

      const ProductsApp = {
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

      createApp(ProductsApp).mount('#products-app');
    })();
  </script>

<?php } ?>

  <!-- product section ends -->


  <!-- contact section -->
  <section class="contact-section-modern">
    <div class="contact-container">
      <div class="contact-grid">
        <!-- Lado Esquerdo - Info -->
        <div class="contact-left">
          <div class="contact-badge">// CONTATO</div>
          <h2 class="contact-title">Entre em Contato</h2>
          <p class="contact-description">Sem demoras, sem respostas vagas — respondemos em até 24 horas para agendar sua visita personalizada.</p>
          
          <!-- Mapa -->
          <div class="contact-map-wrapper">
            <?php echo $mapa ?>
      </div>
              </div>

        <!-- Lado Direito - Formulário -->
        <div class="contact-right">
          <form id="form-email" class="contact-form-modern">
            <div class="form-group-modern">
              <label class="form-label-modern">Nome</label>
              <input type="text" name="nome" placeholder="Seu nome" class="form-input-modern" required/>
              </div>

            <div class="form-group-modern">
              <label class="form-label-modern">Email</label>
              <input type="email" name="email" placeholder="Digite seu email" class="form-input-modern" required />
              </div>

            <div class="form-group-modern">
              <label class="form-label-modern">Telefone</label>
              <input type="text" name="telefone" id="telefone" placeholder="Digite seu telefone" class="form-input-modern" required />
              </div>

            <div class="form-group-modern">
              <label class="form-label-modern">Mensagem</label>
              <textarea name="mensagem" placeholder="Digite sua mensagem" class="form-textarea-modern" rows="5" required></textarea>
              </div>

            <button type="submit" class="btn-submit-contact">
              Enviar mensagem
            </button>

            <div id="mensagem" class="contact-message"></div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <style>
    /* Contact Section Modern */
    .contact-section-modern {
      padding: 120px 0;
      background: #f3ede0;
      position: relative;
    }

    .contact-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 40px;
    }

    .contact-grid {
      display: grid;
      grid-template-columns: 40% 60%;
      gap: 80px;
      align-items: start;
    }

    /* Lado Esquerdo */
    .contact-left {
      position: relative;
    }

    .contact-badge {
      font-size: 13px;
      font-weight: 700;
      color: #666;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 20px;
    }

    .contact-title {
      font-size: 56px;
      font-weight: 900;
      color: #1a1a1a;
      line-height: 1.15;
      margin-bottom: 28px;
      letter-spacing: -1.5px;
    }

    .contact-description {
      font-size: 17px;
      color: #555;
      line-height: 1.7;
      margin-bottom: 40px;
    }

    .contact-map-wrapper {
      width: 100%;
      height: 380px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
      background: #1a1a1a;
      padding: 16px;
      border: 3px solid transparent;
      transition: all 0.3s ease;
    }

    .contact-map-wrapper:hover {
      border-color: #007A63;
      box-shadow: 0 20px 50px rgba(0, 122, 99, 0.2);
    }

    .contact-map-wrapper iframe {
      width: 100%;
      height: 100%;
      border: none;
      border-radius: 12px;
    }

    /* Lado Direito - Formulário */
    .contact-right {
      background: #2a2a2a;
      padding: 48px;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .contact-form-modern {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .form-group-modern {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-label-modern {
      font-size: 12px;
      font-weight: 600;
      color: rgba(254, 254, 254, 0.8);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .form-input-modern,
    .form-textarea-modern {
      width: 100%;
      padding: 16px 20px;
      background: rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 12px;
      color: #FEFEFE;
      font-size: 15px;
      font-weight: 400;
      transition: all 0.3s ease;
      font-family: inherit;
    }

    .form-input-modern::placeholder,
    .form-textarea-modern::placeholder {
      color: rgba(254, 254, 254, 0.5);
    }

    .form-input-modern:focus,
    .form-textarea-modern:focus {
      outline: none;
      border-color: #B9E4D4;
      background: rgba(0, 0, 0, 0.3);
      box-shadow: 0 0 0 3px rgba(185, 228, 212, 0.1);
    }

    .form-textarea-modern {
      resize: vertical;
      min-height: 120px;
    }

    .btn-submit-contact {
      width: 100%;
      padding: 18px 32px;
      background: #007A63 !important;
      color: #FEFEFE !important;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 8px;
    }

    .btn-submit-contact:hover {
      background: #006654 !important;
      color: #FEFEFE !important;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 122, 99, 0.3);
    }

    .contact-message {
      margin-top: 16px;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
    }

    .contact-message.text-success {
      background: rgba(0, 122, 99, 0.1);
      color: #007A63;
      border: 1px solid rgba(0, 122, 99, 0.3);
    }

    .contact-message.text-danger {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      border: 1px solid rgba(220, 53, 69, 0.3);
    }

    /* Responsive */
    @media (max-width: 992px) {
      .contact-grid {
        grid-template-columns: 1fr;
        gap: 60px;
      }

      .contact-title {
        font-size: 44px;
      }

      .contact-map-wrapper {
        height: 320px;
      }
    }

    @media (max-width: 768px) {
      .contact-section-modern {
        padding: 80px 0;
      }

      .contact-container {
        padding: 0 20px;
      }

      .contact-title {
        font-size: 36px;
      }

      .contact-description {
        font-size: 16px;
        margin-bottom: 32px;
      }

      .contact-right {
        padding: 36px 28px;
      }

      .contact-map-wrapper {
        height: 280px;
        padding: 12px;
      }

      .form-input-modern,
      .form-textarea-modern {
        padding: 14px 18px;
        font-size: 14px;
      }
    }
  </style>
  <!-- end contact section -->

  <!-- client section -->
<?php 
$query = $pdo->query("SELECT * FROM comentarios where ativo = 'Sim' ORDER BY id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
  $comentarios = [];
  foreach($res as $comentario) {
    $comentarios[] = [
      'id' => $comentario['id'],
      'nome' => $comentario['nome'],
      'texto' => $comentario['texto'],
      'foto' => $comentario['foto']
    ];
  }

  if(count($comentarios) > 0){ 
  ?>
  
  <section class="reviews-section">
    <div class="reviews-container">
      <div class="reviews-layout">
        <!-- Lado Esquerdo - Título -->
        <div class="reviews-left">
          <div class="reviews-badge">// DEPOIMENTOS</div>
          <h2 class="reviews-title">
            O que nossos<br>
            <span class="title-highlight">Clientes</span> dizem
        </h2>
          <p class="reviews-description">Resultados reais, não apenas promessas vazias — construímos experiências que transformam e encantam nossos clientes.</p>
      </div>

        <!-- Lado Direito - Carrossel -->
        <div class="reviews-right">
          <div class="swiper reviewsSwiper">
            <div class="swiper-wrapper">
            <?php 
              foreach($comentarios as $comentario){
              ?>
              <div class="swiper-slide">
                <div class="review-card">
                  <div class="review-stars">
                    <i data-lucide="star" class="star filled"></i>
                    <i data-lucide="star" class="star filled"></i>
                    <i data-lucide="star" class="star filled"></i>
                    <i data-lucide="star" class="star filled"></i>
                    <i data-lucide="star" class="star filled"></i>
                </div>
                  <p class="review-text">"<?php echo $comentario['texto'] ?>"</p>
                  <div class="review-author">
                    <img src="sistema/painel/img/comentarios/<?php echo $comentario['foto'] ?>" alt="<?php echo $comentario['nome'] ?>" class="author-photo">
                    <div class="author-info">
                      <h4 class="author-name"><?php echo $comentario['nome'] ?></h4>
                      <p class="author-role">Cliente</p>
                </div>
              </div>
            </div>
              </div>
<?php } ?>
            </div>
            <div class="swiper-pagination-reviews"></div>
          </div>

          <div class="reviews-add-btn">
            <a href="" data-toggle="modal" data-target="#modalComentario" class="btn-add-review">
              <i data-lucide="plus-circle"></i>
              <span>Adicionar Depoimento</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <style>
    /* Reviews Section Modern */
    .reviews-section {
      padding: 120px 0;
      background: #006854;
      position: relative;
    }

    .reviews-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 40px;
    }

    .reviews-layout {
      display: grid;
      grid-template-columns: 35% 65%;
      gap: 80px;
      align-items: start;
    }

    /* Lado Esquerdo */
    .reviews-left {
      position: relative;
    }

    .reviews-badge {
      font-size: 12px;
      font-weight: 700;
      color: rgba(185, 228, 212, 0.8);
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 24px;
    }

    .reviews-title {
      font-size: 56px;
      font-weight: 900;
      color: #FEFEFE;
      line-height: 1.15;
      margin-bottom: 28px;
      letter-spacing: -1.5px;
    }

    .title-highlight {
      color: #B9E4D4;
    }

    .reviews-description {
      font-size: 17px;
      color: rgba(254, 254, 254, 0.7);
      line-height: 1.7;
    }

    /* Carrossel de Reviews */
    .reviewsSwiper {
      width: 100%;
      padding-bottom: 60px;
    }

    .reviewsSwiper .swiper-slide {
      height: auto;
    }

    .review-card {
      background: #1a1a1a;
      border-radius: 20px;
      padding: 36px;
      height: 100%;
      display: flex;
      flex-direction: column;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
    }

    .review-card:hover {
      border-color: rgba(185, 228, 212, 0.3);
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .review-stars {
      display: flex;
      gap: 6px;
      margin-bottom: 24px;
    }

    .star {
      width: 18px;
      height: 18px;
      color: #FFD700;
      stroke: #FFD700;
      fill: #FFD700;
    }

    .review-text {
      font-size: 17px;
      color: rgba(254, 254, 254, 0.9);
      line-height: 1.7;
      margin-bottom: 32px;
      flex: 1;
      font-style: italic;
    }

    .review-author {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .author-photo {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(185, 228, 212, 0.3);
    }

    .author-info {
      flex: 1;
    }

    .author-name {
      font-size: 16px;
      font-weight: 700;
      color: #FEFEFE;
      margin-bottom: 4px;
    }

    .author-role {
      font-size: 13px;
      color: rgba(254, 254, 254, 0.6);
      margin: 0;
    }

    /* Paginação Custom */
    .swiper-pagination-reviews {
      bottom: 0 !important;
      display: flex;
      gap: 8px;
      justify-content: center;
    }

    .reviewsSwiper .swiper-pagination-bullet {
      width: 10px;
      height: 10px;
      background: rgba(255, 255, 255, 0.3);
      opacity: 1;
      transition: all 0.3s ease;
    }

    .reviewsSwiper .swiper-pagination-bullet-active {
      background: #B9E4D4;
      width: 32px;
      border-radius: 5px;
    }

    /* Botão Adicionar Depoimento */
    .reviews-add-btn {
      margin-top: 40px;
      text-align: center;
    }

    .btn-add-review {
      display: inline-flex !important;
      align-items: center !important;
      gap: 10px !important;
      padding: 16px 32px !important;
      background: transparent !important;
      color: #FEFEFE !important;
      border: 2px solid rgba(255, 255, 255, 0.3) !important;
      border-radius: 50px !important;
      text-decoration: none !important;
      font-weight: 600 !important;
      font-size: 15px !important;
      transition: all 0.3s ease !important;
    }

    .btn-add-review:hover {
      background: rgba(255, 255, 255, 0.1) !important;
      border-color: #B9E4D4 !important;
      color: #B9E4D4 !important;
    }

    .btn-add-review i {
      width: 20px;
      height: 20px;
      color: #FEFEFE;
      stroke: #FEFEFE;
    }

    .btn-add-review:hover i {
      color: #B9E4D4;
      stroke: #B9E4D4;
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .reviews-layout {
        grid-template-columns: 1fr;
        gap: 60px;
      }

      .reviews-left {
        text-align: center;
      }
    }

    @media (max-width: 768px) {
      .reviews-section {
        padding: 80px 0;
      }

      .reviews-container {
        padding: 0 20px;
      }

      .reviews-title {
        font-size: 36px;
      }

      .reviews-description {
        font-size: 16px;
      }

      .review-card {
        padding: 28px 24px;
      }

      .review-text {
        font-size: 16px;
      }
    }
  </style>

  <script>
    var reviewsSwiper = new Swiper(".reviewsSwiper", {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      speed: 800,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination-reviews",
        clickable: true,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
        1024: {
          slidesPerView: 2,
          spaceBetween: 28,
        }
      }
    });

    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>

<?php } ?>

  <!-- end client section -->

  <?php require_once("rodape.php") ?>










  <!-- Modal Depoimentos Moderno -->
  <div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content modal-modern">
        <div class="modal-header-modern">
          <div>
            <div class="modal-badge">// DEPOIMENTO</div>
            <h3 class="modal-title-modern">Compartilhe sua Experiência</h3>
            <p class="modal-subtitle">Seu feedback é muito importante para nós</p>
          </div>
          <button type="button" class="btn-close-modern" data-dismiss="modal" aria-label="Close">
            <i data-lucide="x"></i>
          </button>
        </div>
        
        <form id="form">
          <div class="modal-body-modern">
            <div class="form-grid-modal">
              <div class="form-group-modal full-width">
                <label class="label-modal">
                  <i data-lucide="user"></i>
                  <span>Seu Nome</span>
                </label>
                <input type="text" class="input-modal" id="nome_cliente" name="nome" placeholder="Digite seu nome completo" required>    
              </div>

              <div class="form-group-modal full-width">
                <label class="label-modal">
                  <i data-lucide="message-square"></i>
                  <span>Seu Depoimento</span>
                  <small class="char-count">(Até 500 caracteres)</small>
                </label>
                <textarea maxlength="500" class="textarea-modal" id="texto_cliente" name="texto" placeholder="Conte-nos sobre sua experiência..." rows="5" required></textarea>   
              </div>  

              <div class="form-group-modal">
                <label class="label-modal">
                  <i data-lucide="camera"></i>
                  <span>Sua Foto</span>
                </label>
                <input class="input-file-modal" type="file" name="foto" onChange="carregarImg();" id="foto" accept="image/*">
            </div>

              <div class="form-group-modal">
                <label class="label-modal">Preview</label>
                <div class="preview-photo">
                  <img src="sistema/painel/img/comentarios/sem-foto.jpg" class="preview-img" id="target">
              </div>  
            </div>
          </div>

            <input type="hidden" name="id" id="id">
            <input type="hidden" name="cliente" value="1">

            <div id="mensagem-comentario" class="mensagem-modal"></div>
          </div>

          <div class="modal-footer-modern">
            <button type="button" class="btn-cancel-modal" data-dismiss="modal">
              Cancelar
            </button>
            <button type="submit" class="btn-submit-modal">
              <i data-lucide="send"></i>
              <span>Enviar Depoimento</span>
            </button>
                </div>            
        </form>
              </div>
                </div>
              </div>

  <style>
    /* Modal Moderno */
    .modal-modern {
      border-radius: 24px;
      border: none;
      background: #1a1a1a;
      overflow: hidden;
    }

    .modal-header-modern {
      background: linear-gradient(135deg, #006854 0%, #005246 100%);
      padding: 36px 40px;
      border: none;
      display: flex;
      justify-content: space-between;
      align-items: start;
    }

    .modal-badge {
      font-size: 11px;
      font-weight: 700;
      color: rgba(185, 228, 212, 0.9);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin-bottom: 12px;
    }

    .modal-title-modern {
      font-size: 28px;
      font-weight: 800;
      color: #FEFEFE;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .modal-subtitle {
      font-size: 14px;
      color: rgba(254, 254, 254, 0.7);
      margin: 0;
    }

    .btn-close-modern {
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

    .btn-close-modern i {
      width: 20px;
      height: 20px;
      color: #FEFEFE;
      stroke: #FEFEFE;
    }

    .btn-close-modern:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: rotate(90deg);
    }

    .modal-body-modern {
      padding: 40px;
      background: #1a1a1a;
    }

    .form-grid-modal {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
    }

    .form-group-modal {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .form-group-modal.full-width {
      grid-column: 1 / -1;
    }

    .label-modal {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
      color: rgba(254, 254, 254, 0.8);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .label-modal i {
      width: 16px;
      height: 16px;
      color: #007A63;
      stroke: #007A63;
    }

    .char-count {
      margin-left: auto;
      font-size: 11px;
      color: rgba(254, 254, 254, 0.5);
      text-transform: none;
      font-weight: 500;
    }

    .input-modal,
    .textarea-modal {
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

    .input-modal::placeholder,
    .textarea-modal::placeholder {
      color: rgba(254, 254, 254, 0.4);
    }

    .input-modal:focus,
    .textarea-modal:focus {
      outline: none;
      border-color: #007A63;
      background: #2a2a2a;
      box-shadow: 0 0 0 3px rgba(0, 122, 99, 0.1);
    }

    .textarea-modal {
      resize: vertical;
      min-height: 120px;
    }

    .input-file-modal {
      padding: 14px 18px;
      background: #252525;
      border: 2px dashed rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      color: #FEFEFE;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .input-file-modal:hover {
      border-color: #007A63;
      background: #2a2a2a;
    }

    .input-file-modal::file-selector-button {
      padding: 8px 16px;
      background: #007A63;
      color: #FEFEFE;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      margin-right: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .input-file-modal::file-selector-button:hover {
      background: #006654;
    }

    .preview-photo {
      width: 100%;
      height: 140px;
      background: #252525;
      border: 2px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px;
    }

    .preview-img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      border-radius: 8px;
    }

    .mensagem-modal {
      margin-top: 20px;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-align: center;
      display: none;
    }

    .mensagem-modal.text-success {
      display: block;
      background: rgba(0, 122, 99, 0.15);
      color: #00d896;
      border: 1px solid rgba(0, 122, 99, 0.3);
    }

    .mensagem-modal.text-danger {
      display: block;
      background: rgba(220, 53, 69, 0.15);
      color: #ff6b6b;
      border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .modal-footer-modern {
      padding: 28px 40px;
      background: #1a1a1a;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      display: flex;
      gap: 12px;
      justify-content: flex-end;
    }

    .btn-cancel-modal {
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

    .btn-cancel-modal:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #FEFEFE;
      border-color: rgba(255, 255, 255, 0.3);
    }

    .btn-submit-modal {
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

    .btn-submit-modal:hover {
      background: #006654;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 122, 99, 0.3);
    }

    .btn-submit-modal i {
      width: 18px;
      height: 18px;
      color: #FEFEFE;
      stroke: #FEFEFE;
    }

    /* Backdrop escuro */
    .modal-backdrop.show {
      opacity: 0.85;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .modal-header-modern {
        padding: 28px 24px;
      }

      .modal-title-modern {
        font-size: 24px;
      }

      .modal-body-modern {
        padding: 28px 24px;
      }

      .form-grid-modal {
        grid-template-columns: 1fr;
      }

      .modal-footer-modern {
        padding: 20px 24px;
        flex-direction: column-reverse;
      }

      .btn-cancel-modal,
      .btn-submit-modal {
        width: 100%;
        justify-content: center;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#modalComentario').on('shown.bs.modal', function() {
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
      });
    });
  </script>








<script type="text/javascript">
  
$("#form-email").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'ajax/enviar-email.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Enviado com Sucesso") {
               $('#mensagem').addClass('text-success')
                $('#mensagem').text(mensagem)

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
  function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("#foto").files[0];
    
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
  
$("#form").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);


    $.ajax({
        url: 'sistema/painel/paginas/comentarios/salvar.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-comentario').text('');
            $('#mensagem-comentario').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
            
            $('#mensagem-comentario').addClass('text-success')
                $('#mensagem-comentario').text('Comentário Enviado para Aprovação!')
                 $('#nome_cliente').val('');
                  $('#texto_cliente').val('');

            } else {

                $('#mensagem-comentario').addClass('text-danger')
                $('#mensagem-comentario').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>