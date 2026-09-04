    <footer class="footer __js_fixed-footer">
      <div class="footer__inner container">
        <div class="row">
          <div class="footer__column col-12 col-sm">
            <a class="footer__logo logo--borderless logo" href="<?php echo $base_url; ?>/">
              <span class="logo__large">AAZ DSGN</span>
              <span class="logo__small">Architecture studio</span>
            </a>
          </div>

          <!-- Copyrights -->
          <div class="footer__column col-12 col-lg order-2 order-lg-0">
            <div class="footer__copyright">
              <script>document.write(new Date().getFullYear())</script> &copy; AAZ DSGN
              <br>All Rights Reserved
            </div>
          </div>

          <!-- Footer menu -->
          <div class="footer__column col-6 col-sm">
            <ul class="footer__column-menu">
              <li class="footer__column-item">
                <a class="footer__column-link" href="<?php echo $base_url; ?>/sitemap.xml">
                  Sitemap
                </a>
              </li>
            </ul>
          </div>

          <!-- Footer menu placeholder -->
          <div class="footer__column col-6 col-sm">
            <ul class="footer__column-menu">
            </ul>
          </div>

          <!-- Language switcher -->
          <div class="footer__column col-12 col-lg-1 order-md-1 order-lg-0">
            <ul class="footer__lang-switcher lang-switcher lang-switcher--footer">
              <li class="lang-switcher__item">
                <a class="lang-switcher__link <?php if ($lang == '_esp') { ?> lang-switcher__link--current <?php } ?>" href="<?php echo $base_url; ?>/?l=_esp">SPA</a>
              </li>
              <li class="lang-switcher__item">
                <a class="lang-switcher__link <?php if ($lang == '_en') { ?> lang-switcher__link--current <?php } ?>" href="<?php echo $base_url; ?>/?l=_en">ENG</a>
              </li>
            </ul>
          </div>

          <!-- Social -->
          <div class="footer__column col-12 col-md col-lg-3 col-xl-2">
            <ul class="footer__social social">
              <li class="social__item">
                <a class="social__link" href="https://www.facebook.com/profile.php?id=100066516921872" target="_blank" rel="noopener noreferrer">
                  <svg width="20" height="20" aria-label="facebook icon">
                    <use xlink:href="#facebook"></use>
                  </svg>
                  <span class="visually-hidden">facebook</span>
                </a>
              </li>

              <li class="social__item">
                <a class="social__link" href="https://www.instagram.com/aaz.dsgn.cr" target="_blank" rel="noopener noreferrer">
                  <svg width="20" height="20" aria-label="instagram icon">
                    <use xlink:href="#instagram"></use>
                  </svg>
                  <span class="visually-hidden">instagram</span>
                </a>
              </li>

              <li class="social__item">
                <a class="social__link" href="https://wa.me/50688907236" target="_blank" rel="noopener noreferrer">
                  <svg width="20" height="20" aria-label="whatsapp icon">
                    <use xlink:href="#whatsapp"></use>
                  </svg>
                  <span class="visually-hidden">whatsapp</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <!-- Popup thanks -->
    <section class="popup popup--thanks" id="thanks" style="display: none">
      <div class="popup__title">Thanks!</div>
    </section>
  </div>

  <!-- Optional JavaScript -->
  <script src="<?php echo $base_url; ?>/js/jquery-3.5.1.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/jquery.fancybox.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/jquery.pagepiling.js"></script>
  <script src="<?php echo $base_url; ?>/js/aos.js"></script>
  <script src="<?php echo $base_url; ?>/js/jquery.easy_number_animate.js"></script>
  <script src="<?php echo $base_url; ?>/js/isotope.pkgd.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/packery-mode.pkgd.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/swiper-bundle.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/animsition.min.js"></script>

  <!-- JavaScript -->
  <script src="<?php echo $base_url; ?>/js/main.js"></script>

</body>
</html>