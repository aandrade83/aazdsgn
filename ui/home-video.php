    <section class="video-section">
      <div class="video-section__inner container">

<? /*
        <video autoplay loop muted>
          <source src="../video/video.mp4" type="video/mp4">
        </video>

*/ ?>

<video id="myVideo" muted loop preload="none" poster="../video/posted.png">
    <source data-src="../video/video.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var video = document.getElementById('myVideo');
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






        <!--<a class="video-section__logo logo--large logo--white logo">
          <span class="logo__large">AAZDSGN</span>
          <span class="logo__small">Architecture studios</span>
        </a> -->
        <div class="video-section__bottom" data-aos="fade">
          <div class="video-section__copy">© AAZDSGN 2024.</div>
          <ul class="video-section__lang-switcherul lang-switcher lang-switcher--line">
            <li class="lang-switcher__item">
              <a id="l_spa"  class="lang-switcher__link <? if($lang == '_esp') { ?> lang-switcher__link--current <? } ?>" href="<?php echo $base_url; ?>/?l=_esp">SPA</a>
            </li>
            <li class="lang-switcher__item">
              <a  id="l_eng" class="lang-switcher__link<? if($lang == '_en') { ?> lang-switcher__link--current <? } ?> " href="<?php echo $base_url; ?>/?l=_en">ENG</a>
            </li>
            
          </ul>
        </div>
      </div>
    </section>
   