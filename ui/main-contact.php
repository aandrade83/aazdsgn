<main>
      <article class="article">
        <header class="article__header">
          <div class="container">
            <h1 class="article__heading heading heading--size-large"><?php echo $lang === '_en' ? 'Contact' : 'Contacto'; ?></h1>
          </div>
        </header>
        <div class="article__map map">
          <video id="contactVideo" muted loop playsinline webkit-playsinline preload="none" poster="/video/posted.png">
            <source data-src="/video/aazdsgn-contact-hero-web-16x9<?php echo $lang; ?>.mp4" type="video/mp4">
            Your browser does not support the video tag.
          </video>
          <script>
            document.addEventListener("DOMContentLoaded", function() {
              var video = document.getElementById('contactVideo');
              var observer = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                  if (entry.isIntersecting) {
                    var source = video.querySelector('source');
                    source.src = source.getAttribute('data-src');
                    video.load();
                    video.play();
                    observer.disconnect();
                  }
                });
              }, { threshold: 0.25 });
              observer.observe(video);
            });
          </script>
        </div>
        <ul class="contact-block__social social--contact social container" style="justify-content: space-around; align-items: center; margin: 24px auto;">
          <li class="social__item">
            <a class="social__link" href="https://wa.me/50688907236" target="_blank">
              <svg width="90" height="90" aria-label="whatsapp icon">
                <use xlink:href="#whatsapp"></use>
              </svg>
              <span class="visually-hidden">whatsapp</span>
            </a>
          </li>
          <li class="social__item">
            <a class="social__link" href="https://www.instagram.com/aaz.dsgn.cr" target="_blank">
              <svg width="90" height="90" aria-label="instagram icon">
                <use xlink:href="#instagram"></use>
              </svg>
              <span class="visually-hidden">instagram</span>
            </a>
          </li>
          <li class="social__item">
            <a class="social__link" href="https://www.facebook.com/profile.php?id=100066516921872" target="_blank">
              <svg width="90" height="90" aria-label="facebook icon">
                <use xlink:href="#facebook"></use>
              </svg>
              <span class="visually-hidden">facebook</span>
            </a>
          </li>
        </ul>
        <div class="article__main container">
          <section class="article__feedback feedback">
            <? if($lang == "_en"){ ?>
            <h2 class="feedback__heading heading" data-aos="fade">Let’s explore how we can  
              <br>bring <span class="color-yellow">your vision to life</span>
            </h2>
            <?} else { ?>
            <h2 class="feedback__heading heading" data-aos="fade">Hablemos de cómo podemos dar 
              <br>vida <span class="color-yellow">a tu visión.</span>
            </h2>
            <? } ?>
            <form id="contactForm" class="js-form-validate" action="<?php echo $base_url; ?>/process/actions/mail.php" method="POST">
              <div class="row">
                <?php
                  $i18n_name    = $lang === '_en' ? 'Name' : 'Nombre';
                  $i18n_email   = 'Email*';
                  $i18n_subject = $lang === '_en' ? 'Subject' : 'Asunto';
                  $i18n_message = $lang === '_en' ? 'Message*' : 'Mensaje*';
                ?>
                <div class="feedback__field-wrapper col-12 col-md-6 col-lg-4" data-aos="fade">
                  <label class="field" for="contact-name" aria-label="<?php echo $i18n_name; ?>">
                    <input id="contact-name" type="text" name="name" placeholder="<?php echo $i18n_name; ?>">
                  </label>
                  <div class="field-error" style="display: none"></div>
                </div>
                <div class="feedback__field-wrapper col-12 col-md-6 col-lg-4" data-aos="fade">
                  <label class="field" for="contact-email" aria-label="<?php echo $i18n_email; ?>">
                    <input id="contact-email" type="email" name="email" placeholder="<?php echo $i18n_email; ?>" required>
                  </label>
                  <div class="field-error" style="display: none"></div>
                </div>
                <div class="feedback__field-wrapper col-12 col-lg-4" data-aos="fade">
                  <label class="field" for="contact-subject" aria-label="<?php echo $i18n_subject; ?>">
                    <input id="contact-subject" type="text" name="subject" placeholder="<?php echo $i18n_subject; ?>">
                  </label>
                  <div class="field-error" style="display: none"></div>
                </div>
                <div class="feedback__field-wrapper col-12" data-aos="fade">
                  <label class="field" for="contact-message" aria-label="<?php echo $i18n_message; ?>">
                    <textarea id="contact-message" name="message" placeholder="<?php echo $i18n_message; ?>" required></textarea>
                  </label>
                  <div class="field-error" style="display: none"></div>
                </div>
              </div>
              <? if($lang == "_en"){ ?>
              <button class="btn" type="submit" data-aos="fade">Submit</button>
              <? } else { ?>
                <button class="btn" type="submit" data-aos="fade">Enviar</button>
             <? }?>
            </form>
          </section>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('contactForm');
            if (!form) return;

            var i18n = <?php echo $lang === '_en'
              ? '{"sending":"Sending...","successTitle":"Message sent","successText":"Thanks for reaching out, we will get back to you soon.","errorTitle":"Something went wrong","errorText":"Please try again later."}'
              : '{"sending":"Enviando...","successTitle":"Mensaje enviado","successText":"Gracias por contactarnos, te responderemos pronto.","errorTitle":"Algo salió mal","errorText":"Por favor intenta de nuevo más tarde."}'; ?>;

            // Legacy js-form-validate binds its own submit handler (fancybox "thanks" + old php/mail.php)
            // on every click; stop it from running so only the SweetAlert flow below fires.
            form.addEventListener('submit', function (e) {
              e.preventDefault();
              e.stopImmediatePropagation();

              var submitBtn = form.querySelector('button[type="submit"]');
              var originalText = submitBtn.textContent;
              submitBtn.disabled = true;
              submitBtn.textContent = i18n.sending;

              fetch(form.getAttribute('action'), {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' }
              })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                  if (data.success) {
                    Swal.fire({
                      icon: 'success',
                      title: i18n.successTitle,
                      text: i18n.successText
                    });
                    form.reset();
                  } else {
                    Swal.fire({
                      icon: 'error',
                      title: i18n.errorTitle,
                      text: i18n.errorText
                    });
                  }
                })
                .catch(function () {
                  Swal.fire({
                    icon: 'error',
                    title: i18n.errorTitle,
                    text: i18n.errorText
                  });
                })
                .finally(function () {
                  submitBtn.disabled = false;
                  submitBtn.textContent = originalText;
                });
            });
          });
        </script>
    </article>
    </main>