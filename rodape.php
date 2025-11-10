<?php require_once("sistema/conexao.php") ?>
<!-- footer section -->
<footer class="footer-modern">
  <div class="footer-container">
    <div class="footer-grid">
      <!-- Coluna 1 - Logo e Descrição -->
      <div class="footer-column">
        <div class="footer-logo-area">
          <img src="images/uai.png" alt="UAI Sistemas" class="footer-logo">
        </div>
        <p class="footer-description">
          © Copyright by UaiSistemas 2025 - Sistema de Gestão Empresarial
        </p>
      </div>

      <!-- Coluna 2 - Navegação -->
      <div class="footer-column">
        <h4 class="footer-title">Navegação</h4>
        <ul class="footer-links">
          <li><a href="index">Home</a></li>
          <li><a href="agendamentos">Agendamentos</a></li>
          <li><a href="servicos">Serviços</a></li>
          <li><a href="produtos">Produtos</a></li>
        </ul>
      </div>

      <!-- Coluna 3 - Informações -->
      <div class="footer-column">
        <h4 class="footer-title">Informações</h4>
        <ul class="footer-links">
          <li><a href="assinatura">Assinaturas</a></li>
          <li><a href="sistema/acesso">Acesso Cliente</a></li>
          <li>
            <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>">
              WhatsApp: <?php echo $whatsapp_sistema ?>
            </a>
          </li>
          <li>
            <a href="mailto:<?php echo $email_sistema ?>">
              Email: <?php echo $email_sistema ?>
            </a>
          </li>
        </ul>
      </div>

      <!-- Coluna 4 - Siga-nos -->
      <div class="footer-column">
        <h4 class="footer-title">Siga-nos</h4>
        <div class="footer-social">
          <a href="<?php echo $instagram_sistema ?>" target="_blank"  title="Instagram">
            <i data-lucide="instagram"></i>
          </a>
          <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $tel_whatsapp ?>" target="_blank" " title="WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="white">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
          </a>
          <a href="sistema" target="_blank"  title="Sistema">
            <i data-lucide="user-circle"></i>
          </a>
        </div>
        <p class="footer-address">
          <i data-lucide="map-pin"></i>
          <span><?php echo $endereco_sistema ?></span>
        </p>
      </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
      <p class="copyright-text">
        Desenvolvido por <strong>UAI Sistemas</strong> - Todos os direitos reservados
      </p>
    </div>
  </div>
</footer>

<style>
  /* Footer Modern */
  .footer-modern {
    background: #2a2a2a;
    padding: 80px 0 0;
    color: #FEFEFE;
  }

  .footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
  }

  .footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 60px;
    padding-bottom: 60px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }

  /* Coluna da Logo */
  .footer-logo-area {
    margin-bottom: 24px;
  }

  .footer-logo {
    height: 50px;
    width: auto;
    filter: brightness(0) invert(1);
  }

  .footer-description {
    font-size: 15px;
    color: rgba(254, 254, 254, 0.6);
    line-height: 1.7;
    margin: 0;
  }

  /* Colunas de Links */
  .footer-column {
    display: flex;
    flex-direction: column;
  }

  .footer-title {
    font-size: 14px;
    font-weight: 700;
    color: #FEFEFE;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 24px;
  }

  .footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  .footer-links li {
    margin: 0;
  }

  .footer-links a {
    color: rgba(254, 254, 254, 0.7);
    text-decoration: none;
    font-size: 15px;
    transition: all 0.3s ease;
    display: inline-block;
  }

  .footer-links a:hover {
    color: #007A63;
    transform: translateX(4px);
  }

  /* Ícones Sociais */
  .footer-social {
    display: flex;
    gap: 12px;
    margin-bottom: 28px;
  }

  .social-link {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .social-link i {
    width: 20px;
    height: 20px;
    color: rgba(254, 254, 254, 0.8);
    stroke: rgba(254, 254, 254, 0.8);
  }

  .social-link svg {
    fill: rgba(254, 254, 254, 0.8);
  }

  .social-link:hover {
    background: #007A63;
    border-color: #007A63;
    transform: translateY(-4px);
  }

  .social-link:hover i,
  .social-link:hover svg {
    color: #FEFEFE;
    stroke: #FEFEFE;
    fill: #FEFEFE;
  }

  .footer-address {
    display: flex;
    align-items: start;
    gap: 10px;
    font-size: 14px;
    color: rgba(254, 254, 254, 0.6);
    line-height: 1.6;
    margin: 0;
  }

  .footer-address i {
    width: 18px;
    height: 18px;
    color: #007A63;
    stroke: #007A63;
    flex-shrink: 0;
    margin-top: 2px;
  }

  /* Copyright */
  .footer-bottom {
    padding: 32px 0;
    text-align: center;
  }

  .copyright-text {
    font-size: 14px;
    color: rgba(254, 254, 254, 0.5);
    margin: 0;
  }

  /* Responsive */
  @media (max-width: 992px) {
    .footer-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 40px;
    }
  }

  @media (max-width: 768px) {
    .footer-modern {
      padding: 60px 0 0;
    }

    .footer-container {
      padding: 0 20px;
    }

    .footer-grid {
      grid-template-columns: 1fr;
      gap: 36px;
      padding-bottom: 40px;
    }

    .footer-title {
      font-size: 13px;
      margin-bottom: 20px;
    }

    .footer-links {
      gap: 12px;
    }

    .footer-links a {
      font-size: 14px;
    }

    .footer-bottom {
      padding: 24px 0;
    }

    .copyright-text {
      font-size: 13px;
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
<!-- footer section -->

  <!-- jQery -->
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


</body>

</html>


<script type="text/javascript">
  
$("#form_cadastro").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'ajax/cadastrar.php',
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-rodape').text('');
            $('#mensagem-rodape').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
               //$('#mensagem-rodape').addClass('text-success')
                $('#mensagem-rodape').text(mensagem)

            } else {

                //$('#mensagem-rodape').addClass('text-danger')
                $('#mensagem-rodape').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});


</script>