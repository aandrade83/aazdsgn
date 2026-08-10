l  <?
   $main =  get_page_data(2,$lang,'descripcion');
   $total = count($main);
  
  ?>
    <main>
      <div class="projects-masonry projects-masonry--pt">
        <div class="projects-masonry__inner container">
          <h1 class="visually-hidden"><?php echo $lang === '_en' ? 'Architecture, design and construction in Costa Rica' : 'Arquitectura, diseño y construcción en Costa Rica'; ?></h1>
          <p class="article__header-text" style="max-width: 900px; margin: 0 auto 48px; text-align: justify; font-size: 18px; line-height: 1.7;">
            <strong class="color-yellow" style="font-size: 1.3em;">AAZ DSGN</strong>
            <?php echo $lang === '_en'
              ? " develops architecture, design, and construction projects in Costa Rica with a personalized, functional, and wellbeing-centered approach. We integrate creativity, technical expertise, and contemporary principles such as neuroarchitecture to design homes, renovations, and spaces that respond to real life, natural light, the surrounding environment, and each client's needs."
              : ' desarrolla proyectos de arquitectura, diseño y construcción en Costa Rica con una visión personalizada, funcional y sensible al bienestar de quienes habitan cada espacio. Integramos creatividad, criterio técnico y principios contemporáneos como la neuroarquitectura para diseñar hogares, remodelaciones y espacios que respondan a la vida real, la luz natural, la conexión con el entorno y las necesidades de cada cliente.'; ?>
          </p>
          <div class="row __js_projects-grid">
           <? // 428 x 428_1 ?>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="1" data-aos="fade">
              <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 428_1']->vars['url']?>" srcset="<? echo $main['428 x 428_1']->vars['url2']?> 2x" width="428" height="428" alt="<? echo $main['428 x 428_1']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 428_1']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 428_1']->vars['text2'],450)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 428_1']->vars['link'])?>"><? echo $main['428 x 428_1']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            
            <? // 428 x 428_2 ?>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="2" data-aos="fade">
              <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 428_2']->vars['url']?>" srcset="<? echo $main['428 x 428_2']->vars['url2']?> 2x" width="428" height="428" alt="<? echo $main['428 x 428_2']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 428_2']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 428_2']->vars['text2'],450)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 428_2']->vars['link'])?>"><? echo $main['428 x 428_2']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
         
            <? // 428 x 428_3 ?>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="3" data-aos="fade">
              <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 428_3']->vars['url']?>" srcset="<? echo $main['428 x 428_3']->vars['url2']?> 2x" width="428" height="428" alt="<? echo $main['428 x 428_3']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 428_3']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 428_3']->vars['text2'],450)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 428_3']->vars['link'])?>"><? echo $main['428 x 428_3']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>

          
            <div class="projects-masonry__item __js_masonry-item  col-12  col-lg-6" data-order="4" data-aos="fade">
              <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['886 x 428_1']->vars['url']?>" srcset="<? echo $main['886 x 428_1']->vars['url2']?> 2x" width="886" height="428" alt="<? echo $main['886 x 428_1']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['886 x 428_1']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['886 x 428_1']->vars['text2'],800)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['886 x 428_1']->vars['link'])?>"><? echo $main['886 x 428_1']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="5" data-aos="fade">
             <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 886_1']->vars['url']?>" srcset="<? echo $main['428 x 886_1']->vars['url2']?> 2x" width="428" height="886" alt="<? echo $main['428 x 886_1']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 886_1']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 886_1']->vars['text2'],400)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 886_1']->vars['link'])?>"><? echo $main['428 x 886_1']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="6" data-aos="fade">
             <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 428_4']->vars['url']?>" srcset="<? echo $main['428 x 428_4']->vars['url2']?> 2x" width="428" height="428" alt="<? echo $main['428 x 428_4']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 428_4']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 428_4']->vars['text2'],400)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 428_4']->vars['link'])?>"><? echo $main['428 x 428_4']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <div class="projects-masonry__item __js_masonry-item  col-12  col-lg-6" data-order="7" data-aos="fade">
              <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['886 x 428_2']->vars['url']?>" srcset="<? echo $main['886 x 428_2']->vars['url2']?> 2x" width="886" height="428" alt="<? echo $main['886 x 428_2']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['886 x 428_2']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['886 x 428_2']->vars['text2'],400)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['886 x 428_2']->vars['link'])?>"><? echo $main['886 x 428_2']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="8" data-aos="fade">
           <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 428_5']->vars['url']?>" srcset="<? echo $main['428 x 428_5']->vars['url2']?> 2x" width="428" height="428" alt="<? echo $main['428 x 428_5']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 428_5']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 428_5']->vars['text2'],400)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 428_5']->vars['link'])?>"><? echo $main['428 x 428_5']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
            <div class="projects-masonry__item __js_masonry-item  col-12 col-md-6 col-lg-4 col-xl-3" data-order="9" data-aos="fade">
             <div class="preview-card">
                <div class="preview-card__image">
                  <img  src="<? echo $main['428 x 428_6']->vars['url']?>" srcset="<? echo $main['428 x 428_6']->vars['url2']?> 2x" width="428" height="428" alt="<? echo $main['428 x 428_6']->vars['alt']?>"  loading="lazy">
                </div>
                <div class="preview-card__content">
                  <h3 class="preview-card__heading"><? echo $main['428 x 428_6']->vars['text1']?></h3>
                  <div class="preview-card__text"><? echo text_preview($main['428 x 428_6']->vars['text2'],400)?></div>
                  <a class="preview-card__btn link-arrow" href="<? echo site_url($main['428 x 428_6']->vars['link'])?>"><? echo $main['428 x 428_6']->vars['text3']?>
                    <svg width="20" height="20">
                      <use xlink:href="#chevron-right"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
