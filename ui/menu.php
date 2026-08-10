  <?
   $menu =  get_page_data(1,$lang);
   
   $total = count($menu);
  
  ?>

  <header class="header __js_fixed-header" id="header">
      <div class="header__inner">
        <!-- Logo-->
        <a class="header__logo logo">
          <span class="logo__large">AAZ DSGN</span>
          <span class="logo__small">Architecture studio</span>
        </a>
        <div class="container"></div>
        <a class="header__phone" target="_blank" href="<? echo site_url($menu[$total-1]->vars['link'])?>"><? echo $menu[$total-1]->vars['descripcion']?></a>
        <!-- Burger-->
        <button class="header__menu-toggle menu-toggle" type="button">
          <span class="visually-hidden">Menu</span>
        </button>
      </div>
    </header>
    <!-- Site menu-->
    <div class="mobile-canvas __js_mobile-canvas">
      <button class="mobile-canvas__close" type="button">
        <svg width="24" height="24">
          <use xlink:href="#close"></use>
        </svg>
        <span class="visually-hidden">Close menu</span>
      </button>
      <!-- Lang switcher-->
      <ul class="mobile-canvas__lang-switcher lang-switcher lang-switcher--menu">
        <li class="lang-switcher__item">
          <a class="lang-switcher__link lang-switcher__link--current" href="<?php echo $base_url; ?>/?l=_esp">Spa</a>
        </li>
        <li class="lang-switcher__item">
          <a class="lang-switcher__link" href="<?php echo $base_url; ?>/?l=_en">Eng</a>
        </li>
        
      </ul>
      <nav class="mobile-canvas__nav navigation">
        <ul class="navigation__list">
        
           <? $i=0;
           foreach ($menu as $m) { ?>
            <? if($i == $total -1){break;}?>
            <? //if ($i <> 1 ){ ?>
            <li class="navigation__item <? if($i==0){ ?> navigation__item--current <? } ?> ">
            <a class="navigation__link" target="_self" href="<? echo site_url($m->vars['link'])?>"><? echo $m->vars['descripcion']?></a>
            </li>
           <? //} ?>

          <? $i++; }?>       
        </ul>
      </nav>
      <div class="mobile-canvas__bottom">
        <a class="mobile-canvas__phone" href="<? echo $menu[$total-1]->vars['link']?>"><? echo $menu[$total-1]->vars['descripcion']?></a>
        <div class="mobile-canvas__copy">
          © 2021 <span>AAZDSGN</span> All Rights Reserved.
          <br>Development by <span>AAZDSGN</span>
        </div>
        <!-- Social-->
        <ul class="mobile-canvas__social social">
          <li class="social__item">
            <a class="social__link" href="#" target="_blank">
              <svg width="20" height="20" aria-label="facebook icon">
                <use xlink:href="#facebook"></use>
              </svg>
              <span class="visually-hidden">facebook</span>
            </a>
          </li>
          <li class="social__item">
            <a class="social__link" href="#" target="_blank">
              <svg width="20" height="20" aria-label="twitter icon">
                <use xlink:href="#twitter"></use>
              </svg>
              <span class="visually-hidden">twitter</span>
            </a>
          </li>
          <li class="social__item">
            <a class="social__link" href="#" target="_blank">
              <svg width="20" height="20" aria-label="google-plus icon">
                <use xlink:href="#google-plus"></use>
              </svg>
              <span class="visually-hidden">google-plus</span>
            </a>
          </li>
          <li class="social__item">
            <a class="social__link" href="#" target="_blank">
              <svg width="20" height="20" aria-label="linkedin-original icon">
                <use xlink:href="#linkedin-original"></use>
              </svg>
              <span class="visually-hidden">linkedin-original</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
   