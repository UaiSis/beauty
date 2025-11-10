<?php require_once("sistema/conexao.php") ?>
<!DOCTYPE html>
<html>
<script>
window.appConfig = {
  nomeSistema: "<?php echo $nome_sistema ?>",
  iconeSite: "<?php echo $icone_site ?>",
  imgBanner: "<?php echo $img_banner_index ?>",
  telWhatsapp: "<?php echo $tel_whatsapp ?>",
  instagram: "<?php echo $instagram_sistema ?>"
};
</script>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="barbearia freitas, salão de beleza" />
  <meta name="description" content="Fazemos todo tipo de serviço ..." />
  <meta name="author" content="Hugo Vasconcelos" />
  <link rel="shortcut icon" href="images/<?php echo $icone_site ?>" type="image/x-icon">

  <title><?php echo $nome_sistema ?></title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
    <link href="css/pricing-table.css" rel="stylesheet" />  
  <link href="css/pricing.css" rel="stylesheet" />

  <!-- Vue 3 -->
  <script src="https://unpkg.com/vue@3.4.21/dist/vue.global.prod.js"></script>
  
  <!-- Element Plus -->
  <link rel="stylesheet" href="https://unpkg.com/element-plus@2.5.6/dist/index.css" />
  <script src="https://unpkg.com/element-plus@2.5.6/dist/index.full.js"></script>
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@0.344.0/dist/umd/lucide.js"></script>

  <style>
    /* Custom Color Palette */
    :root {
      --primary-green: #007A63;
      --primary-mint: #B9E4D4;
      --primary-white: #FEFEFE;
      --text-dark: #333333;
    }

    /* Modern Header Styles */
    .header_section {
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(10px);
      position: sticky;
      top: 0;
      z-index: 100;
      transition: all 0.3s ease;
    }

    .header-wrapper {
      display: flex;
      align-items: center;
      width: 100%;
      padding: 10px 60px;
      justify-content: center;
      max-width: 1400px;
      margin: 0 auto;
    }

    .modern-menu {
      border-bottom: none !important;
      background: transparent !important;
      display: flex;
      align-items: center;
      flex: 1;
      padding: 0 !important;
      justify-content: center;
    }

    .modern-menu .el-menu-item {
      border-bottom: none !important;
      font-weight: 500;
      font-size: 15px;
      color: var(--primary-white);
      transition: all 0.3s ease;
      margin: 0 4px;
      border-radius: 8px;
      padding: 0 16px;
      height: 48px;
      line-height: 48px;
    }

    .modern-menu .el-menu-item:hover {
      background: var(--primary-green) !important;
      color: var(--primary-white) !important;
      transform: translateY(-2px);
    }

    .modern-menu .el-menu-item.is-active {
      background: var(--primary-green) !important;
      color: var(--primary-white) !important;
      border-bottom: none !important;
    }

    .logo-container {
      display: flex;
      align-items: center;
      margin-right: 50px;
      padding: 0;
      transition: all 0.3s ease;
      flex-shrink: 0;
    }

    .logo-img {
      width: 75px;
      height: auto;
      filter: brightness(0) invert(1);
      transition: transform 0.3s ease;
    }

    .logo-img:hover {
      transform: scale(1.05);
    }

    .flex-grow {
      flex: 0.5;
    }

    .menu-icon {
      width: 18px;
      height: 18px;
      margin-right: 8px;
      vertical-align: middle;
      color: var(--primary-white);
      stroke: var(--primary-white);
    }

    .menu-icon-only {
      width: 20px;
      height: 20px;
      color: var(--primary-white);
      stroke: var(--primary-white);
    }

    svg.menu-icon-only {
      width: 20px;
      height: 20px;
    }

    .icon-item {
      min-width: 48px !important;
      width: 48px !important;
      padding: 0 !important;
      justify-content: center;
      margin-left: 12px !important;
    }

    .icon-item:first-of-type {
      margin-left: 30px !important;
    }

    .icon-item:last-child {
      margin-right: 0 !important;
    }

    [data-lucide] {
      color: var(--primary-white);
      stroke: var(--primary-white);
    }

    .mobile-menu-btn {
      display: none;
      margin-left: auto;
    }

    .mobile-menu-btn .el-button {
      background: transparent;
      border: 2px solid var(--primary-white);
      color: var(--primary-white);
      width: 48px;
      height: 48px;
    }

    .mobile-menu-btn .el-button:hover {
      transform: scale(1.05);
      background: var(--primary-green);
      border-color: var(--primary-green);
    }

    .mobile-menu {
      border-right: none !important;
      background: transparent !important;
    }

    .mobile-menu .el-menu-item {
      margin: 0;
      padding: 16px 12px;
      border-radius: 8px;
      transition: all 0.3s ease;
      color: var(--primary-white);
      font-weight: 400;
      font-size: 16px;
      border-bottom: none;
      background: transparent !important;
      height: auto;
      line-height: 1.5;
      margin-bottom: 4px;
    }

    .mobile-menu .el-menu-item:hover {
      background: var(--primary-green) !important;
      color: var(--primary-white);
    }

    .mobile-menu .el-menu-item.is-active {
      background: var(--primary-green) !important;
      color: var(--primary-white);
    }

    .mobile-menu .el-menu-item i.menu-icon {
      display: none;
    }

    .mobile-menu .el-menu-item i.menu-icon-only {
      color: var(--primary-white);
      stroke: var(--primary-white);
      width: 24px;
      height: 24px;
      margin-right: 0;
    }

    .mobile-menu .el-menu-item svg.menu-icon-only {
      width: 24px;
      height: 24px;
      margin-right: 0;
    }

    .mobile-menu .el-menu-item:hover i.menu-icon-only,
    .mobile-menu .el-menu-item.is-active i.menu-icon-only {
      color: var(--primary-mint);
      stroke: var(--primary-mint);
    }

    .mobile-menu .el-menu-item:hover svg.menu-icon-only,
    .mobile-menu .el-menu-item.is-active svg.menu-icon-only {
      fill: var(--primary-mint);
    }

    .mobile-social-icons {
      display: flex;
      gap: 20px;
      margin-top: 30px;
      padding: 0;
    }

    .mobile-social-icons .el-menu-item {
      border: none;
      padding: 0;
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: transparent !important;
      border-radius: 50%;
      margin: 0;
    }

  

    .mobile-social-icons .el-menu-item:hover {
      background: var(--primary-green) !important;
    }

    .mobile-social-icons .el-menu-item.is-active {
      background: var(--primary-green) !important;
    }

    .el-drawer {
      background: rgba(0, 0, 0, 0.9) !important;
      backdrop-filter: blur(10px);
      z-index: 99999 !important;
    }
    
    .el-drawer__wrapper {
      z-index: 99999 !important;
      position: fixed !important;
    }
    
    .el-overlay {
      z-index: 99998 !important;
      position: fixed !important;
    }

    .el-drawer__header {
      background: transparent;
      color: var(--primary-white);
      margin-bottom: 0;
      padding: 20px;
      border-bottom: none;
    }

    .el-drawer__title {
      display: none;
    }

    .el-drawer__close-btn {
      color: var(--primary-white) !important;
      font-size: 24px;
    }

    .el-drawer__body {
      padding: 20px;
      background: transparent;
    }

    .el-divider {
      margin: 30px 0;
      background-color: rgba(255, 255, 255, 0.1);
      height: 1px;
    }

    /* Logo hover effect */
    .logo-container:hover {
      transform: translateY(-2px);
    }

    /* Icon color for mobile menu button */
    .mobile-menu-btn .el-button i {
      color: var(--primary-white);
      stroke: var(--primary-white);
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
      .menu-item-desktop {
        display: none !important;
      }

      .mobile-menu-btn {
        display: flex !important;
      }

      .logo-img {
        width: 90px;
      }

      .logo-container {
        margin-right: 20px;
      }

      .header-wrapper {
        padding: 10px 15px;
        max-width: 100%;
        justify-content: flex-start;
      }

      .modern-menu {
        justify-content: flex-end;
      }
    }

    @media (min-width: 993px) {
      .mobile-menu-btn {
        display: none !important;
      }
    }

    /* Hero Area adjustments */
    .hero_area {
      position: relative;
      z-index: auto;
    }

    .hero_bg_box {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .hero_bg_box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }

    /* Element Plus overrides for modern look */
    .el-menu--horizontal > .el-menu-item:not(.is-disabled):hover,
    .el-menu--horizontal > .el-menu-item:not(.is-disabled):focus {
      background-color: transparent;
    }

    /* Animation for menu items */
    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modern-menu .el-menu-item {
      animation: slideInDown 0.5s ease-out;
      animation-fill-mode: both;
    }

    .modern-menu .el-menu-item:nth-child(1) { animation-delay: 0.1s; }
    .modern-menu .el-menu-item:nth-child(2) { animation-delay: 0.15s; }
    .modern-menu .el-menu-item:nth-child(3) { animation-delay: 0.2s; }
    .modern-menu .el-menu-item:nth-child(4) { animation-delay: 0.25s; }
    .modern-menu .el-menu-item:nth-child(5) { animation-delay: 0.3s; }
    .modern-menu .el-menu-item:nth-child(6) { animation-delay: 0.35s; }
    .modern-menu .el-menu-item:nth-child(7) { animation-delay: 0.4s; }
    .modern-menu .el-menu-item:nth-child(8) { animation-delay: 0.45s; }
    .modern-menu .el-menu-item:nth-child(9) { animation-delay: 0.5s; }
    .modern-menu .el-menu-item:nth-child(10) { animation-delay: 0.55s; }
    
    /* Force drawer to be on top of everything */
    body > .el-overlay,
    body > .el-drawer__wrapper,
    .el-overlay-drawer {
      z-index: 99999 !important;
      position: fixed !important;
    }
    
    .el-drawer.rtl,
    .el-drawer.ltr,
    .el-drawer.ttb,
    .el-drawer.btt {
      z-index: 99999 !important;
    }
    
    .drawer-modal {
      z-index: 99999 !important;
    }
    
    /* Ensure all Element Plus overlay components are on top */
    [class*="el-overlay"],
    [class*="el-drawer"] {
      z-index: 99999 !important;
    }
  </style>

</head>

<body class="sub_page">
  <div class="hero_area">
    <div class="hero_bg_box">
      <img src="images/<?php echo $img_banner_index ?>" alt="">
    </div>
    
    <!-- header section starts -->
    <div id="app">
      <header class="header_section">
        <div class="container">
          <div class="header-wrapper">
            <!-- Logo -->
            <div class="logo-container">
              <img src="sistema/img/logo.png" class="logo-img" alt="Logo">
            </div>

            <el-menu 
              mode="horizontal" 
              :default-active="activeIndex"
              class="modern-menu"
              :ellipsis="false"
              @select="handleSelect">
              
              <!-- Menu Items - Desktop -->
              <el-menu-item index="index" class="menu-item-desktop">
              <i data-lucide="home" class="menu-icon"></i>
              <span>Home</span>
            </el-menu-item>
            
            <el-menu-item index="agendamentos" class="menu-item-desktop">
              <i data-lucide="calendar-days" class="menu-icon"></i>
              <span>Agendamentos</span>
            </el-menu-item>
            
            <el-menu-item index="assinatura" class="menu-item-desktop">
              <i data-lucide="file-signature" class="menu-icon"></i>
              <span>Assinaturas</span>
            </el-menu-item>
            
            <el-menu-item index="produtos" class="menu-item-desktop">
              <i data-lucide="shopping-bag" class="menu-icon"></i>
              <span>Produtos</span>
            </el-menu-item>
            
            <el-menu-item index="servicos" class="menu-item-desktop">
              <i data-lucide="scissors" class="menu-icon"></i>
              <span>Serviços</span>
            </el-menu-item>
            
            <!-- Mostra "Acesso Cliente" e "Sistema" apenas se NÃO estiver logado -->
            <el-menu-item v-if="!clienteLogado" index="sistema/acesso" class="menu-item-desktop">
              <i data-lucide="log-in" class="menu-icon"></i>
              <span>Acesso Cliente</span>
            </el-menu-item>
            
            <!-- Mostra "Minha Área" se estiver logado -->
            <el-menu-item v-if="clienteLogado" index="sistema/painel_cliente" class="menu-item-desktop">
              <i data-lucide="layout-dashboard" class="menu-icon"></i>
              <span>Minha Área</span>
            </el-menu-item>

            <!-- Spacer to push icons to the right -->
            <div class="flex-grow"></div>
            
            <!-- Mostra nome do cliente se estiver logado -->
            <el-menu-item v-if="clienteLogado" index="sistema/painel_cliente" class="menu-item-desktop" style="font-weight: 600; color: #B9E4D4 !important;">
              <i data-lucide="user-check" class="menu-icon" style="color: #B9E4D4; stroke: #B9E4D4;"></i>
             Olá, <span>{{ clienteLogado.nome }}</span>
            </el-menu-item>
            
            <!-- Botão de Logout se estiver logado -->
            <el-menu-item v-if="clienteLogado" index="logout" class="icon-item menu-item-desktop" title="Sair da Conta">
              <i data-lucide="log-out" class="menu-icon-only"></i>
            </el-menu-item>

            <!-- Social Icons -->
            <el-menu-item v-if="!clienteLogado" index="sistema" class="icon-item menu-item-desktop" title="Ir para o Sistema">
              <i data-lucide="user-circle" class="menu-icon-only"></i>
            </el-menu-item>
            
            <el-menu-item :index="whatsappUrl" class="icon-item menu-item-desktop" title="Ir para o Whatsapp">
              <svg class="menu-icon-only" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
            </el-menu-item>
            
            <el-menu-item :index="instagramUrl" class="icon-item menu-item-desktop" title="Ver Instagram">
              <i data-lucide="instagram" class="menu-icon-only"></i>
            </el-menu-item>

            <!-- Mobile Menu Button -->
            <div class="mobile-menu-btn">
              <el-button @click="drawerVisible = true" circle>
                <i data-lucide="menu"></i>
              </el-button>
            </div>
          </el-menu>
          </div>
        </div>

        <!-- Mobile Drawer -->
        <el-drawer
          v-model="drawerVisible"
          title="Menu"
          direction="rtl"
          size="280px"
          :z-index="99999"
          append-to-body
          modal-class="drawer-modal">
          <el-menu
            :default-active="activeIndex"
            class="mobile-menu"
            @select="handleMobileSelect">
            
            <el-menu-item index="index">
              <i data-lucide="home" class="menu-icon"></i>
              <span>Home</span>
            </el-menu-item>
            
            <el-menu-item index="agendamentos">
              <i data-lucide="calendar-days" class="menu-icon"></i>
              <span>Agendamentos</span>
            </el-menu-item>
            
            <el-menu-item index="assinatura">
              <i data-lucide="file-signature" class="menu-icon"></i>
              <span>Assinaturas</span>
            </el-menu-item>
            
            <el-menu-item index="produtos">
              <i data-lucide="shopping-bag" class="menu-icon"></i>
              <span>Produtos</span>
            </el-menu-item>
            
            <el-menu-item index="servicos">
              <i data-lucide="scissors" class="menu-icon"></i>
              <span>Serviços</span>
            </el-menu-item>
            
            <!-- Mostra "Acesso Cliente" apenas se NÃO estiver logado -->
            <el-menu-item v-if="!clienteLogado" index="sistema/acesso">
              <i data-lucide="log-in" class="menu-icon"></i>
              <span>Acesso</span>
            </el-menu-item>
            
            <!-- Mostra "Minha Área" se estiver logado -->
            <el-menu-item v-if="clienteLogado" index="sistema/painel_cliente">
              <i data-lucide="layout-dashboard" class="menu-icon"></i>
              <span>Minha Área</span>
            </el-menu-item>
            
            <!-- Mostra informações do cliente logado -->
            <el-menu-item v-if="clienteLogado" index="sistema/painel_cliente" style="background: rgba(185, 228, 212, 0.1) !important; margin-top: 8px;">
              <i data-lucide="user-check" class="menu-icon" style="color: #B9E4D4; stroke: #B9E4D4;"></i>
              <span style="color: #B9E4D4; font-weight: 600;">{{ clienteLogado.nome }}</span>
            </el-menu-item>
            
            <!-- Botão de Logout se estiver logado -->
            <el-menu-item v-if="clienteLogado" index="logout" style="background: rgba(220, 53, 69, 0.1) !important;">
              <i data-lucide="log-out" class="menu-icon" style="color: #ff6b6b; stroke: #ff6b6b;"></i>
              <span style="color: #ff6b6b; font-weight: 600;">Sair</span>
            </el-menu-item>

            <el-divider></el-divider>

            <div class="mobile-social-icons">
              <!-- Mostra ícone do sistema apenas se NÃO estiver logado -->
              <el-menu-item v-if="!clienteLogado" index="sistema" class="social-icon-item">
                <i data-lucide="user-circle" class="menu-icon-only"></i>
              </el-menu-item>
              
              <el-menu-item :index="whatsappUrl" class="social-icon-item">
                <svg class="menu-icon-only" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
              </el-menu-item>
              
              <el-menu-item :index="instagramUrl" class="social-icon-item">
                <i data-lucide="instagram" class="menu-icon-only"></i>
              </el-menu-item>
            </div>
          </el-menu>
        </el-drawer>
      </header>
    </div>
    <!-- end header section -->

    <script>
      const { createApp } = Vue;
      const { ElMenu, ElMenuItem, ElButton, ElDrawer, ElDivider } = ElementPlus;
      
      function getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop().replace('.php', '') || 'index';
        
        if (page === 'index' || page === '') return 'index';
        if (page === 'agendamentos') return 'agendamentos';
        if (page === 'assinatura') return 'assinatura';
        if (page === 'produtos') return 'produtos';
        if (page === 'servicos') return 'servicos';
        if (path.includes('sistema/acesso')) return 'sistema/acesso';
        
        return 'index';
      }
      
      const App = {
        components: {
          ElMenu,
          ElMenuItem,
          ElButton,
          ElDrawer,
          ElDivider
        },
        data() {
          return {
            activeIndex: getCurrentPage(),
            drawerVisible: false,
            config: window.appConfig || {},
            clienteLogado: null
          }
        },
        computed: {
          whatsappUrl() {
            return `http://api.whatsapp.com/send?1=pt_BR&phone=${this.config.telWhatsapp}`;
          },
          instagramUrl() {
            return this.config.instagram || '#';
          }
        },
        watch: {
          drawerVisible(newVal) {
            if (newVal) {
              this.$nextTick(() => {
                setTimeout(() => {
                  lucide.createIcons();
                }, 100);
              });
            }
          }
        },
        methods: {
          handleSelect(index) {
            if (index === 'logout') {
              this.fazerLogout();
            } else if (index === 'sistema/painel_cliente') {
              // Ao clicar em "Minha Área", salva sessão antes de redirecionar
              this.acessarMinhaArea();
            } else if (index.startsWith('http')) {
              window.open(index, '_blank');
            } else if (index === 'sistema') {
              window.open(index, '_blank');
            } else {
              window.location.href = index;
            }
          },
          acessarMinhaArea() {
            // Redireciona para página de auto-login que fará a verificação
            window.location.href = 'sistema/auto-login-cliente.php';
          },
          handleMobileSelect(index) {
            this.drawerVisible = false;
            this.handleSelect(index);
          },
          verificarClienteLogado() {
            // Verifica se tem nos cookies primeiro (mais confiável)
            const cookieId = this.getCookie('id_cliente');
            const cookieNome = this.getCookie('nome_cliente');
            const cookieTelefone = this.getCookie('telefone_cliente');
            
            // Se não tiver cookies, limpa localStorage também (logout foi feito)
            if (!cookieId || !cookieNome) {
              localStorage.removeItem('cliente_logado');
              this.clienteLogado = null;
              return;
            }
            
            // Se tiver cookies, verifica localStorage
            const clienteStorage = localStorage.getItem('cliente_logado');
            if (clienteStorage) {
              try {
                const cliente = JSON.parse(clienteStorage);
                // Verifica se o ID do localStorage corresponde ao cookie
                if (cliente.id === cookieId) {
                  this.clienteLogado = cliente;
                  return;
                } else {
                  // IDs não correspondem, limpa e recria
                  localStorage.removeItem('cliente_logado');
                }
              } catch (e) {
                console.log('Erro ao recuperar sessão do cliente');
                localStorage.removeItem('cliente_logado');
              }
            }
            
            // Se chegou aqui, tem cookies mas não tem localStorage válido
            // Restaura do cookie
            this.clienteLogado = {
              id: cookieId,
              nome: cookieNome,
              telefone: cookieTelefone,
              email: ''
            };
            // Atualiza o localStorage também
            localStorage.setItem('cliente_logado', JSON.stringify(this.clienteLogado));
          },
          getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
          },
          fazerLogout() {
            if (confirm('Deseja realmente sair da sua conta?')) {
              // Remove localStorage
              localStorage.removeItem('cliente_logado');
              this.clienteLogado = null;
              
              // Remove cookies via AJAX para sincronizar com sessão PHP
              fetch('ajax/logout-cliente.php', {
                method: 'POST'
              }).then(() => {
                // Redireciona para a home
                window.location.href = 'index.php';
              }).catch(() => {
                // Se der erro, redireciona mesmo assim
                window.location.href = 'index.php';
              });
            }
          }
        },
        mounted() {
          this.verificarClienteLogado();
          
          // Atualiza a cada 1 segundo para detectar mudanças no login
          setInterval(() => {
            this.verificarClienteLogado();
          }, 1000);
          
          this.$nextTick(() => {
            setTimeout(() => {
              lucide.createIcons();
            }, 100);
          });
        },
        updated() {
          this.$nextTick(() => {
            lucide.createIcons();
          });
        }
      };

      createApp(App).use(ElementPlus).mount('#app');
    </script>

    