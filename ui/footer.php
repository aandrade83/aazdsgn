    <footer class="footer __js_fixed-footer">
      <div class="footer__inner container">
        <div class="row">
          <div class="footer__column col-12 col-sm">
            <a class="footer__logo logo--borderless logo">
              <span class="logo__large">AAZ DSGN</span>
              <span class="logo__small">Architecture studio</span>
            </a>
          </div>
          <!-- Copyrights-->
          <div class="footer__column col-12 col-lg order-2 order-lg-0">
            <div class="footer__copyright">
               <script>document.write(new Date().getFullYear())</script> &copy;  AAZDSGN 
              <br> All Rights Resevered
            </div>
            <!-- Footer menu-->
          </div>
          <div class="footer__column col-6 col-sm">
            <ul class="footer__column-menu">
              <li class="footer__column-item">
                <a class="footer__column-link" href="<?php echo $base_url; ?>/sitemap.xml">Site Map</a>
              </li>
              <? /*
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Terms &amp; Conditions</a>
              </li>
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Privacy Policy</a>
              </li>
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Help</a>
              </li>
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Affiliatep</a>
              </li>
              */?>
            </ul>
          </div>
          <!-- Footer menu-->
          <div class="footer__column col-6 col-sm">
            <ul class="footer__column-menu">
              <? /*
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Our Location</a>
              </li>
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Career</a>
              </li>
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">About</a>
              </li>
              <li class="footer__column-item">
                <a class="footer__column-link animsition-link" href="#">Contact</a>
              </li>
               */ ?>
            </ul>
          </div>
          <div class="footer__column col-12 col-lg-1 order-md-1 order-lg-0">
            <ul class="footer__lang-switcher lang-switcher lang-switcher--footer">
              <li class="lang-switcher__item">
                <a class="lang-switcher__link <? if($lang == '_esp') { ?> lang-switcher__link--current <? } ?>" href="<?php echo $base_url; ?>/?l=_esp">SPA</a>
              </li>
              <li class="lang-switcher__item">
                <a class="lang-switcher__link <? if($lang == '_en') { ?> lang-switcher__link--current <? } ?> " href="<?php echo $base_url; ?>/?l=_en">ENG</a>
              </li>
              
            </ul>
          </div>
          <div class="footer__column footer__column col-12 col-md col-lg-3 col-xl-2">
            <!-- Social-->
            <ul class="footer__social social">
              <li class="social__item">
                <a class="social__link" href="https://www.facebook.com/profile.php?id=100066516921872" target="_blank">
                  <svg width="20" height="20" aria-label="facebook icon">
                    <use xlink:href="#facebook"></use>
                  </svg>
                  <span class="visually-hidden">facebook</span>
                </a>
              </li>
              <? /*
              <li class="social__item">
                <a class="social__link" href="#" target="_blank">
                  <svg width="20" height="20" aria-label="twitter icon">
                    <use xlink:href="#twitter"></use>
                  </svg>
                  <span class="visually-hidden">twitter</span>
                </a>
              </li>
              */ ?>
             
              
              <li class="social__item">
                <a class="social__link" href="https://www.instagram.com/aaz.dsgn.cr" target="_blank">
                  <svg width="20" height="20" aria-label="instagram icon">
                    <use xlink:href="#instagram"></use>
                  </svg>
                  <span class="visually-hidden">instagram</span>
                </a>
              </li>

               <li class="social__item">
                <a class="social__link" href="#" target="_blank">
                  <svg width="20" height="20" aria-label="whatsapp icon">
                    <use xlink:href="whatsapp "></use>
                  </svg>
                  <span class="visually-hidden">whatsapp </span>
                </a>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </footer>
    <!-- Popup thanks-->
    <section class="popup popup--thanks" id="thanks" style="display: none">
      <div class="popup__title">Thanks!</div>
    </section>
  </div>
  <!-- Optional JavaScript-->
  <script src="<?php echo $base_url; ?>/js/jquery-3.5.1.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/jquery.fancybox.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/jquery.pagepiling.js"></script>
  <script src="<?php echo $base_url; ?>/js/aos.js"></script>
  <script src="<?php echo $base_url; ?>/js/jquery.easy_number_animate.js"></script>
  <script src="<?php echo $base_url; ?>/js/isotope.pkgd.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/packery-mode.pkgd.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/swiper-bundle.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/animsition.min.js"></script>
  <!-- JavaScript-->
  <script src="<?php echo $base_url; ?>/js/main.js"></script>

</body>

</html>